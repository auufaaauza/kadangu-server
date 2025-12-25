<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['user', 'pertunjukan'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('admin.booking.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'pertunjukan', 'transaction']);
        return view('admin.booking.show', compact('booking'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);

        $booking->update($validated);

        return redirect()->back()->with('success', 'Status booking berhasil diupdate!');
    }

    public function updatePayment(Booking $booking)
    {
        if ($booking->transaction) {
            $booking->transaction->update(['status' => 'paid']);
            return redirect()->back()->with('success', 'Payment status berhasil diupdate!');
        }

        return redirect()->back()->with('error', 'Transaction tidak ditemukan!');
    }

    public function validatePayment(Booking $booking)
    {
        if (!$booking->payment_proof) {
            return redirect()->back()->with('error', 'Bukti pembayaran belum diupload!');
        }

        $booking->update([
            'payment_status' => 'paid',
            'status' => 'paid'
        ]);

        if ($booking->transaction) {
            $booking->transaction->update(['status' => 'paid']);
        }

        return redirect()->back()->with('success', 'Pembayaran berhasil divalidasi!');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->back()->with('success', 'Booking berhasil dihapus!');
    }

    public function bulkUpdatePayment(Request $request)
    {
        $validated = $request->validate([
            'booking_ids' => 'required|array',
            'booking_ids.*' => 'exists:bookings,id',
        ]);

        $updated = 0;
        foreach ($validated['booking_ids'] as $bookingId) {
            $booking = Booking::find($bookingId);
            if ($booking && $booking->transaction) {
                $booking->transaction->update(['status' => 'paid']);
                $updated++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "$updated bookings updated successfully"
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'booking_ids' => 'required|array',
            'booking_ids.*' => 'exists:bookings,id',
        ]);

        $deleted = Booking::whereIn('id', $validated['booking_ids'])->delete();

        return response()->json([
            'success' => true,
            'message' => "$deleted bookings deleted successfully"
        ]);
    }
}
