<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of all transactions
     */
    public function index(Request $request)
    {
        $query = Transaction::with(['user', 'booking.pertunjukan']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by metode pembayaran
        if ($request->has('metode_pembayaran')) {
            $query->where('metode_pembayaran', $request->metode_pembayaran);
        }

        $transactions = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json($transactions);
    }

    /**
     * Display the specified transaction
     */
    public function show($id)
    {
        $transaction = Transaction::with(['user', 'booking.pertunjukan'])
            ->findOrFail($id);

        return response()->json($transaction);
    }

    /**
     * Update transaction status (approve/reject payment)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,failed',
        ]);

        $transaction = Transaction::findOrFail($id);
        
        $transaction->update([
            'status' => $request->status,
            'tanggal_bayar' => $request->status === 'paid' ? now() : $transaction->tanggal_bayar,
        ]);

        // Update booking status if payment approved
        if ($request->status === 'paid') {
            $transaction->booking->update(['status' => 'paid']);
        }

        return response()->json([
            'message' => 'Status transaksi berhasil diupdate',
            'transaction' => $transaction->load(['user', 'booking.pertunjukan'])
        ]);
    }
}
