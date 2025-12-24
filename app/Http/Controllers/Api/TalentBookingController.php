<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TalentBooking;
use App\Models\TalentPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TalentBookingController extends Controller
{
    /**
     * Store a new talent booking
     */
    public function store(Request $request)
    {
        $request->validate([
            'talent_id' => 'required|exists:talents,id',
            'talent_package_id' => 'required|exists:talent_packages,id',
            'event_date' => 'required|string', // Format: YYYY-MM-DD HH:mm
            'event_location' => 'required|string',
            'user_name' => 'required|string',
            'user_phone' => 'required|string',
        ]);

        $package = TalentPackage::findOrFail($request->talent_package_id);

        // Parse date and time
        // Input is "2025-12-24 20:42"
        try {
            $dt = Carbon::parse($request->event_date);
            $date = $dt->format('Y-m-d');
            $time = $dt->format('H:i:s');
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invalid date format'], 422);
        }

        // Check talent global availability
        $talent = \App\Models\Talent::findOrFail($request->talent_id);
        if ($talent->availability_status !== 'available') {
             return response()->json(['message' => 'Maaf, talent ini sedang tidak menerima booking (Status: Tidak Tersedia)'], 422);
        }

        // Check availability for specific date (Prevent Double Booking)
        $isBooked = TalentBooking::where('talent_id', $request->talent_id)
            ->whereDate('event_date', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($isBooked) {
            return response()->json(['message' => 'Maaf, talent sudah dibooking pada tanggal tersebut. Silakan pilih tanggal lain.'], 422);
        }

        DB::beginTransaction();
        try {
            // Create booking
            $booking = TalentBooking::create([
                'user_id' => $request->user()->id,
                'talent_id' => $request->talent_id,
                'package_id' => $request->talent_package_id,
                'event_date' => $date,
                'event_time' => $time,
                'event_location' => $request->event_location,
                'event_details' => $request->event_details,
                'total_price' => $package->price,
                'status' => 'pending',
                // user_name, user_email, user_phone are usually for reference or updating user profile, 
                // but if not in table, we might ignore them or store in details?
                // For now, ignoring extra fields or putting them in notes if needed.
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Booking berhasil dibuat',
                'data' => $booking
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
     * Display user's talent bookings
     */
    public function index(Request $request)
    {
        $bookings = TalentBooking::with(['talent.artistGroup', 'package'])
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($bookings);
    }

    /**
     * Display the specified booking
     */
    public function show($id)
    {
        $booking = TalentBooking::with(['talent.artistGroup', 'package'])
            ->where('user_id', request()->user()->id)
            ->findOrFail($id);

        return response()->json($booking);
    }
}
