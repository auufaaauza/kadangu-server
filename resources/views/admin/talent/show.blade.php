@extends('layouts.admin')

@section('title', 'Talent Details')

@section('content')
<div class="page-header">
    <h1 class="page-title">{{ $talent->name }}</h1>
    <p class="page-subtitle">{{ $talent->artistGroup?->nama }} - {{ $talent->genre }}</p>
</div>

@if(session('success'))
<div class="alert alert-success">
    <i class="fas fa-check-circle"></i>
    {{ session('success') }}
</div>
@endif

<div class="page-actions" style="margin-bottom: 32px;">
    <a href="{{ route('admin.talent.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to List
    </a>
    <a href="{{ route('admin.talent.edit', $talent->id) }}" class="btn btn-primary">
        <i class="fas fa-edit"></i> Edit Talent
    </a>
</div>

<!-- Talent Info Card -->
<div class="card talent-info-card">
    <div class="talent-photo-section">
        @if($talent->photo)
            <img src="{{ asset('storage/' . $talent->photo) }}" alt="{{ $talent->name }}" class="talent-photo-large">
        @else
            <div class="no-photo-large">
                <div class="no-photo-initial">{{ strtoupper(substr($talent->name, 0, 1)) }}</div>
            </div>
        @endif
    </div>
    <div class="talent-details-section">
        <div class="detail-grid">
            <div class="detail-item">
                <div class="detail-label">Kategori</div>
                <div class="detail-value">{{ $talent->artistGroup?->nama }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Genre</div>
                <div class="detail-value">
                    <span class="badge badge-info">{{ $talent->genre }}</span>
                </div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Rating</div>
                <div class="detail-value" style="display: flex; align-items: center; gap: 6px;">
                    <i class="fas fa-star" style="color: #FBBF24;"></i>
                    <span>{{ $talent->rating ?? 'N/A' }}</span>
                </div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Base Price</div>
                <div class="detail-value price-text">{{ $talent->formatted_base_price }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Availability</div>
                <div class="detail-value">
                    <span class="badge badge-{{ $talent->availability_status == 'available' ? 'success' : ($talent->availability_status == 'booked' ? 'warning' : 'danger') }}">
                        {{ ucfirst($talent->availability_status) }}
                    </span>
                </div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Status</div>
                <div class="detail-value">
                    <span class="badge badge-{{ $talent->status == 'active' ? 'success' : 'inactive' }}">
                        {{ ucfirst($talent->status) }}
                    </span>
                </div>
            </div>
        </div>
        
        <div class="detail-divider"></div>
        
        <div class="detail-section">
            <div class="detail-label">Bio</div>
            <div class="detail-text">{{ $talent->bio }}</div>
        </div>
        
        <div class="detail-section">
            <div class="detail-label">Service Description</div>
            <div class="detail-text">{{ $talent->service_description }}</div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card purple">
        <div class="stat-icon">
            <i class="fas fa-box"></i>
        </div>
        <div class="stat-label">Total Packages</div>
        <div class="stat-value">{{ $talent->packages->count() }}</div>
    </div>
    <div class="stat-card blue">
        <div class="stat-icon">
            <i class="fas fa-calendar-check"></i>
        </div>
        <div class="stat-label">Total Bookings</div>
        <div class="stat-value">{{ $talent->total_bookings }}</div>
    </div>
    <div class="stat-card orange">
        <div class="stat-icon">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-label">Pending Bookings</div>
        <div class="stat-value">{{ $talent->pending_bookings_count }}</div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-label">Completed Bookings</div>
        <div class="stat-value">{{ $talent->completed_bookings_count }}</div>
    </div>
</div>

<!-- Portfolio Gallery -->
@if($talent->portfolio && count($talent->portfolio) > 0)
    <div class="card" style="margin-bottom: 30px;">
        <h3 class="card-title" style="margin-bottom: 20px;">
            <i class="fas fa-images"></i> Portfolio
        </h3>
        <div class="portfolio-gallery-large">
            @foreach($talent->portfolio as $image)
                <div class="portfolio-item-large">
                    <img src="{{ asset('storage/' . $image) }}" alt="Portfolio">
                </div>
            @endforeach
        </div>
    </div>
@endif

<!-- Packages Section -->
<div class="card" style="margin-bottom: 30px;">
    <h3 class="card-title" style="margin-bottom: 20px;">
        <i class="fas fa-box"></i> Packages
    </h3>
    <div class="packages-grid">
        @forelse($talent->packages as $package)
            <div class="package-detail-card">
                <div class="package-detail-header">
                    <h4>{{ $package->name }}</h4>
                    <span class="badge badge-{{ $package->status == 'active' ? 'success' : 'inactive' }}">
                        {{ ucfirst($package->status) }}
                    </span>
                </div>
                <div class="package-detail-body">
                    <div class="package-price">{{ $package->formatted_price }}</div>
                    <div class="package-duration">
                        <i class="fas fa-clock"></i> {{ $package->formatted_duration }}
                    </div>
                    <p class="package-description">{{ $package->description }}</p>
                    @if($package->includes && count($package->includes) > 0)
                        <div class="package-includes">
                            <strong>Includes:</strong>
                            <ul>
                                @foreach($package->includes as $include)
                                    <li><i class="fas fa-check"></i> {{ $include }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-muted" style="color: #94A3B8;">Belum ada package</p>
        @endforelse
    </div>
</div>

<!-- Recent Bookings Section -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-calendar-alt"></i> Recent Bookings
        </h3>
        <a href="{{ route('admin.talent-booking.index', ['talent_id' => $talent->id]) }}" class="btn btn-secondary btn-sm">
            View All
        </a>
    </div>
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Booking Code</th>
                    <th>User</th>
                    <th>Package</th>
                    <th>Event Date</th>
                    <th>Location</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($talent->bookings()->latest()->take(10)->get() as $booking)
                    <tr>
                        <td><strong>{{ $booking->booking_code }}</strong></td>
                        <td>{{ $booking->user->name }}</td>
                        <td>{{ $booking->package->name }}</td>
                        <td>{{ $booking->formatted_event_date }}</td>
                        <td>{{ $booking->event_location }}</td>
                        <td>{{ $booking->formatted_total_price }}</td>
                        <td>
                            <span class="badge badge-{{ $booking->status == 'confirmed' ? 'success' : ($booking->status == 'pending' ? 'warning' : ($booking->status == 'completed' ? 'info' : 'danger')) }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                        <td class="action-buttons">
                            <a href="{{ route('admin.talent-booking.show', $booking->id) }}" class="btn btn-secondary btn-sm" title="View Details">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center" style="text-align: center; color: #94A3B8; padding: 30px;">Belum ada booking</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
    :root {
        --primary: #8B5CF6;
        --bg-card: rgba(30, 41, 59, 0.5);
        --border-color: rgba(139, 92, 246, 0.1);
        --text-primary: #FFFFFF;
        --text-secondary: #94A3B8;
    }

    .talent-info-card {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 32px;
        margin-bottom: 32px;
    }

    .talent-photo-large {
        width: 100%;
        height: 280px;
        object-fit: cover;
        border-radius: 16px;
        border: 2px solid var(--border-color);
    }

    .no-photo-large {
        width: 100%;
        height: 280px;
        background: linear-gradient(135deg, #8B5CF6 0%, #EC4899 100%);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    .no-photo-initial {
        font-size: 80px;
        font-weight: 700;
    }

    .talent-details-section {
        display: flex;
        flex-direction: column;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 24px;
        margin-bottom: 24px;
    }

    .detail-label {
        color: var(--text-secondary);
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .detail-value {
        color: var(--text-primary);
        font-size: 16px;
        font-weight: 500;
    }

    .price-text {
        color: #34D399;
        font-weight: 700;
        font-size: 18px;
    }

    .detail-divider {
        height: 1px;
        background: var(--border-color);
        margin: 0 0 24px 0;
    }

    .detail-section {
        margin-bottom: 24px;
    }

    .detail-text {
        color: #CBD5E1;
        line-height: 1.6;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
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
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
    }

    .stat-card.purple::before { background: linear-gradient(90deg, #8B5CF6 0%, #EC4899 100%); }
    .stat-card.blue::before { background: linear-gradient(90deg, #3B82F6 0%, #06B6D4 100%); }
    .stat-card.green::before { background: linear-gradient(90deg, #10B981 0%, #34D399 100%); }
    .stat-card.orange::before { background: linear-gradient(90deg, #F59E0B 0%, #FBBF24 100%); }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: white;
        margin-bottom: 16px;
        background: rgba(255,255,255,0.1);
    }

    .stat-label {
        color: var(--text-secondary);
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 4px;
    }

    .stat-value {
        color: white;
        font-size: 28px;
        font-weight: 700;
    }

    /* Portfolio */
    .portfolio-gallery-large {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
    }

    .portfolio-item-large {
        aspect-ratio: 1;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid var(--border-color);
    }

    .portfolio-item-large img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s;
    }

    .portfolio-item-large:hover img {
        transform: scale(1.05);
    }

    /* Packages */
    .packages-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 24px;
    }

    .package-detail-card {
        background: rgba(15, 23, 42, 0.4);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .package-detail-card:hover {
        border-color: #8B5CF6;
        transform: translateY(-4px);
    }

    .package-detail-header {
        background: linear-gradient(135deg, rgba(139, 92, 246, 0.1) 0%, rgba(236, 72, 153, 0.1) 100%);
        padding: 20px;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .package-detail-header h4 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
        color: white;
    }

    .package-detail-body {
        padding: 24px;
    }

    .package-price {
        font-size: 24px;
        font-weight: 700;
        color: #34D399;
        margin-bottom: 8px;
    }

    .package-duration {
        color: var(--text-secondary);
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 16px;
    }

    .package-description {
        color: #CBD5E1;
        margin-bottom: 20px;
        line-height: 1.6;
    }

    .package-includes {
        background: rgba(15, 23, 42, 0.3);
        padding: 16px;
        border-radius: 12px;
    }

    .package-includes strong {
        display: block;
        color: white;
        margin-bottom: 12px;
        font-size: 14px;
    }

    .package-includes ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .package-includes li {
        color: #CBD5E1;
        font-size: 14px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .package-includes li:last-child {
        margin-bottom: 0;
    }

    .package-includes li i {
        color: #34D399;
        font-size: 12px;
    }

    /* Badges */
    .badge {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }
    
    .badge-info { background: rgba(59, 130, 246, 0.2); color: #60A5FA; }
    .badge-success { background: rgba(52, 211, 153, 0.2); color: #34D399; }
    .badge-warning { background: rgba(251, 191, 36, 0.2); color: #FBBF24; }
    .badge-danger, .badge-inactive { background: rgba(248, 113, 113, 0.2); color: #F87171; }

    @media (max-width: 768px) {
        .talent-info-card {
            grid-template-columns: 1fr;
        }
        
        .talent-photo-large {
            height: 300px;
        }
        
        .packages-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection
