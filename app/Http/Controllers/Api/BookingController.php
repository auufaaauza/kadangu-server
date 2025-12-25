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
            
            // Create booking
            $booking = Booking::create([
                'user_id' => $request->user()->id,
                'pertunjukan_id' => $request->pertunjukan_id,
                'ticket_category_id' => $request->ticket_category_id,
                'jumlah_tiket' => $request->jumlah_tiket,
                'total_harga' => $totalHarga,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
            ]);

            // Update kuota
            $category->decrement('kuota_tersisa', $request->jumlah_tiket);
            $pertunjukan->decrement('kuota_tersisa', $request->jumlah_tiket);

            // Handle Midtrans
            $snapToken = null;
            if ($request->payment_method === 'midtrans') {
                // Set your Merchant Server Key
                \Midtrans\Config::$serverKey = 'YOUR_MIDTRANS_SERVER_KEY'; // USER TO FILL THIS
                // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
                \Midtrans\Config::$isProduction = false;
                // Set sanitization on (default)
                \Midtrans\Config::$isSanitized = true;
                // Set 3DS transaction for credit card to true
                \Midtrans\Config::$is3ds = true;

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
}
