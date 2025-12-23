<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TalentBooking;
use App\Models\Talent;
use Illuminate\Http\Request;

class TalentBookingController extends Controller
{
    public function index(Request $request)
    {
        $query = TalentBooking::with(['user', 'talent', 'package']);

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by talent
        if ($request->has('talent_id') && $request->talent_id != '') {
            $query->where('talent_id', $request->talent_id);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('event_date', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('event_date', '<=', $request->date_to);
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(15);
        $talents = Talent::all();

        return view('admin.talent-booking.index', compact('bookings', 'talents'));
    }

    public function show($id)
    {
        $booking = TalentBooking::with(['user', 'talent.seniman', 'package'])
            ->findOrFail($id);

        return view('admin.talent-booking.show', compact('booking'));
    }

    public function updateStatus(Request $request, $id)
    {
        $booking = TalentBooking::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,rejected,completed,cancelled',
            'admin_notes' => 'nullable|string',
        ]);

        $booking->update($validated);

        return redirect()->back()
            ->with('success', 'Status booking berhasil diupdate!');
    }

    public function destroy($id)
    {
        $booking = TalentBooking::findOrFail($id);
        $booking->delete();

        return redirect()->route('admin.talent-booking.index')
            ->with('success', 'Booking berhasil dihapus!');
    }
}
