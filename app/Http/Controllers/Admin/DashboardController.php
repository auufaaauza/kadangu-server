<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pertunjukan;
use App\Models\Booking;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_pertunjukans' => Pertunjukan::count(),
            'total_pertunjukans_active' => Pertunjukan::where('status', 'active')->count(),
            'total_bookings' => Booking::count(),
            'bookings_confirmed' => Booking::where('status', 'confirmed')->count(),
            'total_revenue' => Transaction::where('status', 'paid')->sum('jumlah'),
            'revenue_this_month' => Transaction::where('status', 'paid')
                ->whereMonth('tanggal_bayar', now()->month)
                ->whereYear('tanggal_bayar', now()->year)
                ->sum('jumlah'),
            'total_users' => User::where('role', 'user')->count(),
        ];
        
        $recentBookings = Booking::with(['user', 'pertunjukan'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        $upcomingShows = Pertunjukan::with('seniman')
            ->where('status', 'active')
            ->where('tanggal_pertunjukan', '>=', now())
            ->orderBy('tanggal_pertunjukan', 'asc')
            ->limit(5)
            ->get();
        
        return view('admin.dashboard', compact('stats', 'recentBookings', 'upcomingShows'));
    }
}
