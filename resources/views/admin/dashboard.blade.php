@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <style>
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }
        
        .stat-card {
            background: rgba(30, 41, 59, 0.5);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 24px;
            border: 1px solid rgba(139, 92, 246, 0.1);
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
        }
        
        .stat-card.purple::before {
            background: linear-gradient(90deg, #8B5CF6 0%, #EC4899 100%);
        }
        
        .stat-card.blue::before {
            background: linear-gradient(90deg, #3B82F6 0%, #06B6D4 100%);
        }
        
        .stat-card.green::before {
            background: linear-gradient(90deg, #10B981 0%, #34D399 100%);
        }
        
        .stat-card.orange::before {
            background: linear-gradient(90deg, #F59E0B 0%, #FBBF24 100%);
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
            border-color: rgba(139, 92, 246, 0.3);
        }
        
        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            margin-bottom: 16px;
        }
        
        .stat-card.purple .stat-icon {
            background: linear-gradient(135deg, #8B5CF6 0%, #EC4899 100%);
        }
        
        .stat-card.blue .stat-icon {
            background: linear-gradient(135deg, #3B82F6 0%, #06B6D4 100%);
        }
        
        .stat-card.green .stat-icon {
            background: linear-gradient(135deg, #10B981 0%, #34D399 100%);
        }
        
        .stat-card.orange .stat-icon {
            background: linear-gradient(135deg, #F59E0B 0%, #FBBF24 100%);
        }
        
        .stat-label {
            color: #94A3B8;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
        }
        
        .stat-value {
            color: white;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        
        .stat-change {
            font-size: 13px;
            font-weight: 500;
        }
        
        .stat-change.positive {
            color: #34D399;
        }
        
        .stat-change.negative {
            color: #F87171;
        }
        
        .content-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
            margin-bottom: 32px;
        }
        
        .card {
            background: rgba(30, 41, 59, 0.5);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 24px;
            border: 1px solid rgba(139, 92, 246, 0.1);
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .card-title {
            color: white;
            font-size: 18px;
            font-weight: 600;
        }
        
        .btn {
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #8B5CF6 0%, #EC4899 100%);
            color: white;
            border: none;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(139, 92, 246, 0.3);
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table thead tr {
            border-bottom: 1px solid rgba(139, 92, 246, 0.1);
        }
        
        .table th {
            color: #94A3B8;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 12px;
            text-align: left;
        }
        
        .table td {
            color: #CBD5E1;
            font-size: 14px;
            padding: 16px 12px;
            border-bottom: 1px solid rgba(139, 92, 246, 0.05);
        }
        
        .badge {
            padding: 4px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }
        
        .badge-pending {
            background: rgba(251, 191, 36, 0.1);
            color: #FBBF24;
        }
        
        .badge-paid {
            background: rgba(52, 211, 153, 0.1);
            color: #34D399;
        }
        
        .badge-confirmed {
            background: rgba(59, 130, 246, 0.1);
            color: #3B82F6;
        }
        
        .badge-cancelled {
            background: rgba(248, 113, 113, 0.1);
            color: #F87171;
        }
        
        .upcoming-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        
        .upcoming-item {
            display: flex;
            gap: 16px;
            padding: 16px;
            background: rgba(15, 23, 42, 0.3);
            border-radius: 12px;
            border: 1px solid rgba(139, 92, 246, 0.1);
            transition: all 0.3s ease;
        }
        
        .upcoming-item:hover {
            border-color: rgba(139, 92, 246, 0.3);
            transform: translateX(4px);
        }
        
        .upcoming-date {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-width: 60px;
            padding: 12px;
            background: linear-gradient(135deg, #8B5CF6 0%, #EC4899 100%);
            border-radius: 12px;
        }
        
        .upcoming-day {
            color: white;
            font-size: 24px;
            font-weight: 700;
            line-height: 1;
        }
        
        .upcoming-month {
            color: rgba(255, 255, 255, 0.8);
            font-size: 12px;
            font-weight: 600;
        }
        
        .upcoming-info {
            flex: 1;
        }
        
        .upcoming-title {
            color: white;
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 4px;
        }
        
        .upcoming-location {
            color: #94A3B8;
            font-size: 13px;
        }
        
        @media (max-width: 1024px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
    
    <div class="page-header">
        <h1 class="page-title">Dashboard</h1>
        <p class="page-subtitle">Selamat datang kembali, {{ Auth::user()->name }}! 👋</p>
    </div>
    
    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card purple">
            <div class="stat-icon">
                <i class="fas fa-theater-masks"></i>
            </div>
            <div class="stat-label">Total Pertunjukan</div>
            <div class="stat-value">{{ $stats['total_pertunjukans'] ?? 0 }}</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i> {{ $stats['total_pertunjukans_active'] ?? 0 }} Active
            </div>
        </div>
        
        <div class="stat-card blue">
            <div class="stat-icon">
                <i class="fas fa-ticket"></i>
            </div>
            <div class="stat-label">Total Bookings</div>
            <div class="stat-value">{{ $stats['total_bookings'] ?? 0 }}</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i> {{ $stats['bookings_confirmed'] ?? 0 }} Confirmed
            </div>
        </div>
        
        <div class="stat-card green">
            <div class="stat-icon">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="stat-label">Total Revenue</div>
            <div class="stat-value">Rp {{ number_format($stats['total_revenue'] ?? 0, 0, ',', '.') }}</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i> Bulan Ini: Rp {{ number_format($stats['revenue_this_month'] ?? 0, 0, ',', '.') }}
            </div>
        </div>
        
        <div class="stat-card orange">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-label">Total Users</div>
            <div class="stat-value">{{ $stats['total_users'] ?? 0 }}</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i> Active Users
            </div>
        </div>
    </div>
    
    <!-- Content Grid -->
    <div class="content-grid">
        <!-- Recent Bookings -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Recent Bookings</h2>
                <a href="{{ route('admin.booking.index') }}" class="btn btn-primary">
                    View All <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            
            <table class="table">
                <thead>
                    <tr>
                        <th>Kode Booking</th>
                        <th>User</th>
                        <th>Pertunjukan</th>
                        <th>Tiket</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentBookings ?? [] as $booking)
                    <tr>
                        <td><strong>{{ $booking->kode_booking }}</strong></td>
                        <td>{{ $booking->user->name }}</td>
                        <td>{{ Str::limit($booking->pertunjukan->judul, 30) }}</td>
                        <td>{{ $booking->jumlah_tiket }}x</td>
                        <td>
                            <span class="badge badge-{{ $booking->status }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: #64748B;">
                            Belum ada booking
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Upcoming Shows -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Upcoming Shows</h2>
            </div>
            
            <div class="upcoming-list">
                @forelse($upcomingShows ?? [] as $show)
                <div class="upcoming-item">
                    <div class="upcoming-date">
                        <div class="upcoming-day">{{ $show->tanggal_pertunjukan->format('d') }}</div>
                        <div class="upcoming-month">{{ $show->tanggal_pertunjukan->format('M') }}</div>
                    </div>
                    <div class="upcoming-info">
                        <div class="upcoming-title">{{ $show->judul }}</div>
                        <div class="upcoming-location">
                            <i class="fas fa-map-marker-alt"></i> {{ $show->lokasi }}
                        </div>
                    </div>
                </div>
                @empty
                <div style="text-align: center; color: #64748B; padding: 20px;">
                    Tidak ada pertunjukan mendatang
                </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
