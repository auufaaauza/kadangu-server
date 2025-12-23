<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['booking.user', 'booking.pertunjukan'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('admin.transaction.index', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['booking.user', 'booking.pertunjukan']);
        return view('admin.transaction.show', compact('transaction'));
    }

    public function updateStatus(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,paid,failed',
        ]);

        $transaction->update($validated);

        return redirect()->back()->with('success', 'Status transaksi berhasil diupdate!');
    }
}
