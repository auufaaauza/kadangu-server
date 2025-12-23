<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{
    /**
     * Display user's transaction history
     */
    public function index(Request $request)
    {
        $transactions = Transaction::with(['booking.pertunjukan'])
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($transactions);
    }

    /**
     * Upload bukti pembayaran
     */
    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'metode_pembayaran' => 'required|string',
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $booking = Booking::where('id', $request->booking_id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        // Upload bukti pembayaran
        $path = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');

        $transaction = Transaction::create([
            'booking_id' => $request->booking_id,
            'user_id' => $request->user()->id,
            'jumlah' => $booking->total_harga,
            'metode_pembayaran' => $request->metode_pembayaran,
            'bukti_pembayaran' => $path,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Bukti pembayaran berhasil diupload',
            'transaction' => $transaction->load('booking.pertunjukan')
        ], 201);
    }

    /**
     * Display the specified transaction
     */
    public function show(Request $request, $id)
    {
        $transaction = Transaction::with(['booking.pertunjukan'])
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);

        return response()->json($transaction);
    }
}
