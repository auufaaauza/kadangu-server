<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display a listing of all bookings
     */
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'pertunjukan.artistGroup', 'transaction']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by pertunjukan
        if ($request->has('pertunjukan_id')) {
            $query->where('pertunjukan_id', $request->pertunjukan_id);
        }

        // Search by kode_booking or user name
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_booking', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $bookings = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json($bookings);
    }

    /**
     * Display the specified booking
     */
    public function show($id)
    {
        $booking = Booking::with(['user', 'pertunjukan.artistGroup', 'transaction'])
            ->findOrFail($id);

        return response()->json($booking);
    }

    /**
     * Update booking status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,confirmed,cancelled',
        ]);

        $booking = Booking::findOrFail($id);
        $oldStatus = $booking->status;
        
        $booking->update(['status' => $request->status]);

        // If cancelled, restore quota
        if ($request->status === 'cancelled' && $oldStatus !== 'cancelled') {
            $booking->pertunjukan->increment('kuota_tersisa', $booking->jumlah_tiket);
        }

        return response()->json([
            'message' => 'Status booking berhasil diupdate',
            'booking' => $booking->load(['user', 'pertunjukan', 'transaction'])
        ]);
    }

    /**
     * Remove the specified booking
     */
    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);

        // Restore quota if not already cancelled
        if ($booking->status !== 'cancelled') {
            $booking->pertunjukan->increment('kuota_tersisa', $booking->jumlah_tiket);
        }

        $booking->delete();

        return response()->json([
            'message' => 'Booking berhasil dihapus'
        ]);
    }
}
