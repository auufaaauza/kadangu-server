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
        $bookings = Booking::with(['pertunjukan.seniman', 'transaction'])
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($bookings);
    }

    /**
     * Store a new booking
     */
    public function store(Request $request)
    {
        $request->validate([
            'pertunjukan_id' => 'required|exists:pertunjukans,id',
            'jumlah_tiket' => 'required|integer|min:1',
        ]);

        $pertunjukan = Pertunjukan::findOrFail($request->pertunjukan_id);

        // Check if enough quota available
        if ($pertunjukan->kuota_tersisa < $request->jumlah_tiket) {
            return response()->json([
                'message' => 'Kuota tiket tidak mencukupi'
            ], 400);
        }

        DB::beginTransaction();
        try {
            // Create booking
            $booking = Booking::create([
                'user_id' => $request->user()->id,
                'pertunjukan_id' => $request->pertunjukan_id,
                'jumlah_tiket' => $request->jumlah_tiket,
                'total_harga' => $pertunjukan->harga * $request->jumlah_tiket,
                'status' => 'pending',
            ]);

            // Update kuota
            $pertunjukan->decrement('kuota_tersisa', $request->jumlah_tiket);

            DB::commit();

            return response()->json([
                'message' => 'Booking berhasil dibuat',
                'booking' => $booking->load('pertunjukan.seniman')
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
        $booking = Booking::with(['pertunjukan.seniman', 'transaction'])
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);

        return response()->json($booking);
    }
}
