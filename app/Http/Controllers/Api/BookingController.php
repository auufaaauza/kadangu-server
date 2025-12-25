<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Pertunjukan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Display user's booking history
     */
    public function index(Request $request)
    {
        $bookings = Booking::with(['pertunjukan.artistGroup', 'transaction', 'ticketCategory'])
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($bookings);
    }

    /**
     * Store a new booking
     */
    /**
     * Store a new booking
     */
    public function store(Request $request)
    {
        $request->validate([
            'pertunjukan_id' => 'required|exists:pertunjukans,id',
            'ticket_category_id' => 'required|exists:ticket_categories,id',
            'jumlah_tiket' => 'required|integer|min:1',
            'payment_method' => 'required|in:manual,midtrans',
            'payment_proof' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048', // 2MB max
        ]);

        $pertunjukan = Pertunjukan::findOrFail($request->pertunjukan_id);
        $category = $pertunjukan->ticketCategories()->findOrFail($request->ticket_category_id);

        // Check if enough quota available in category
        if ($category->kuota_tersisa < $request->jumlah_tiket) {
            return response()->json([
                'message' => 'Kuota tiket kategori ini tidak mencukupi'
            ], 400);
        }

        DB::beginTransaction();
        try {
            $totalHarga = $category->harga * $request->jumlah_tiket;
            
            // Handle payment proof upload
            $paymentProofPath = null;
            if ($request->hasFile('payment_proof')) {
                $paymentProofPath = $request->file('payment_proof')->store('payment_proofs', 'public');
            }

            // Create booking
            $booking = Booking::create([
                'user_id' => $request->user()->id,
                'pertunjukan_id' => $request->pertunjukan_id,
                'ticket_category_id' => $request->ticket_category_id,
                'jumlah_tiket' => $request->jumlah_tiket,
                'total_harga' => $totalHarga,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'payment_proof' => $paymentProofPath,
                'payment_status' => $paymentProofPath ? 'pending_verification' : 'unpaid',
            ]);

            // Update kuota
            $category->decrement('kuota_tersisa', $request->jumlah_tiket);
            $pertunjukan->decrement('kuota_tersisa', $request->jumlah_tiket);

            // Handle Midtrans
            $snapToken = null;
            if ($request->payment_method === 'midtrans') {
                // Set Midtrans Configuration from .env
                \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
                \Midtrans\Config::$isProduction = config('services.midtrans.is_production', false);
                \Midtrans\Config::$isSanitized = config('services.midtrans.is_sanitized', true);
                \Midtrans\Config::$is3ds = config('services.midtrans.is_3ds', true);

                $params = [
                    'transaction_details' => [
                        'order_id' => $booking->kode_booking, // Unique Order ID
                        'gross_amount' => (int) $totalHarga,
                    ],
                    'customer_details' => [
                        'first_name' => $request->name ?? $request->user()->name,
                        'email' => $request->email ?? $request->user()->email,
                        'phone' => $request->phone ?? $request->user()->phone,
                    ],
                    'item_details' => [
                        [
                            'id' => $category->id,
                            'price' => (int) $category->harga,
                            'quantity' => $request->jumlah_tiket,
                            'name' => 'Tiket: ' . $pertunjukan->judul . ' - ' . $category->nama,
                        ]
                    ]
                ];

                $snapToken = \Midtrans\Snap::getSnapToken($params);
                $booking->update(['snap_token' => $snapToken]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Booking berhasil dibuat',
                'booking' => $booking->load('pertunjukan.artistGroup', 'ticketCategory'),
                'snap_token' => $snapToken
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Booking gagal',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified booking
     */
    public function show(Request $request, $id)
    {
        $booking = Booking::with(['pertunjukan.artistGroup', 'ticketCategory', 'transaction'])
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);

        return response()->json($booking);
    }

    /**
     * Upload payment proof for a booking
     */
    public function uploadProof(Request $request, $id)
    {
        $request->validate([
            'payment_proof' => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120', // 5MB max
        ]);

        $booking = Booking::where('user_id', $request->user()->id)
            ->findOrFail($id);

        // Delete old payment proof if exists
        if ($booking->payment_proof) {
            \Storage::disk('public')->delete($booking->payment_proof);
        }

        // Store new payment proof
        $path = $request->file('payment_proof')->store('payment_proofs', 'public');

        // Update booking
        $booking->update([
            'payment_proof' => $path,
            'payment_status' => 'pending_verification',
        ]);

        return response()->json([
            'message' => 'Bukti pembayaran berhasil diupload',
            'booking' => $booking->load(['pertunjukan.artistGroup', 'ticketCategory'])
        ]);
    }
}
