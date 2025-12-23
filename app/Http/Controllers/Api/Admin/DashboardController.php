<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pertunjukan;
use App\Models\Booking;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics
     */
    public function stats()
    {
        $stats = [
            // Total counts
            'total_pertunjukans' => Pertunjukan::count(),
            'total_pertunjukans_active' => Pertunjukan::where('status', 'active')->count(),
            'total_bookings' => Booking::count(),
            'total_users' => User::where('role', 'user')->count(),
            
            // Booking statistics
            'bookings_pending' => Booking::where('status', 'pending')->count(),
            'bookings_paid' => Booking::where('status', 'paid')->count(),
            'bookings_confirmed' => Booking::where('status', 'confirmed')->count(),
            'bookings_cancelled' => Booking::where('status', 'cancelled')->count(),
            
            // Transaction statistics
            'transactions_pending' => Transaction::where('status', 'pending')->count(),
            'transactions_paid' => Transaction::where('status', 'paid')->count(),
            'transactions_failed' => Transaction::where('status', 'failed')->count(),
            
            // Revenue
            'total_revenue' => Transaction::where('status', 'paid')->sum('jumlah'),
            'revenue_this_month' => Transaction::where('status', 'paid')
                ->whereMonth('tanggal_bayar', now()->month)
                ->whereYear('tanggal_bayar', now()->year)
                ->sum('jumlah'),
            
            // Recent bookings
            'recent_bookings' => Booking::with(['user', 'pertunjukan'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
            
            // Upcoming pertunjukans
            'upcoming_pertunjukans' => Pertunjukan::with('seniman')
                ->where('status', 'active')
                ->where('tanggal_pertunjukan', '>=', now())
                ->orderBy('tanggal_pertunjukan', 'asc')
                ->limit(5)
                ->get(),
            
            // Popular pertunjukans (most bookings)
            'popular_pertunjukans' => Pertunjukan::with('seniman')
                ->withCount('bookings')
                ->orderBy('bookings_count', 'desc')
                ->limit(5)
                ->get(),
        ];

        return response()->json($stats);
    }
}
