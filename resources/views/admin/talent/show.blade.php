@extends('layouts.admin')

@section('title', 'Talent Details')

@section('content')
<div class="admin-container">
    <div class="admin-header">
        <div>
            <h1>{{ $talent->name }}</h1>
            <p class="admin-subtitle">{{ $talent->seniman->nama }} - {{ $talent->genre }}</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('admin.talent.edit', $talent->id) }}" class="btn-primary">
                <i class="fas fa-edit"></i> Edit Talent
            </a>
            <a href="{{ route('admin.talent.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <!-- Talent Info Card -->
    <div class="talent-info-card">
        <div class="talent-photo-section">
            @if($talent->photo)
                <img src="{{ asset('storage/' . $talent->photo) }}" alt="{{ $talent->name }}" class="talent-photo-large">
            @else
                <div class="no-photo-large">
                    <i class="fas fa-user"></i>
                </div>
            @endif
        </div>
        <div class="talent-details-section">
            <div class="detail-row">
                <strong>Kategori:</strong>
                <span>{{ $talent->seniman->nama }}</span>
            </div>
            <div class="detail-row">
                <strong>Genre:</strong>
                <span class="badge badge-genre">{{ $talent->genre }}</span>
            </div>
            <div class="detail-row">
                <strong>Base Price:</strong>
                <span class="price-tag">{{ $talent->formatted_base_price }}</span>
            </div>
            <div class="detail-row">
                <strong>Availability:</strong>
                <span class="badge badge-{{ $talent->availability_status == 'available' ? 'success' : ($talent->availability_status == 'booked' ? 'warning' : 'danger') }}">
                    {{ ucfirst($talent->availability_status) }}
                </span>
            </div>
            <div class="detail-row">
                <strong>Status:</strong>
                <span class="badge badge-{{ $talent->status == 'active' ? 'success' : 'inactive' }}">
                    {{ ucfirst($talent->status) }}
                </span>
            </div>
            <div class="detail-row full-width">
                <strong>Bio:</strong>
                <p>{{ $talent->bio }}</p>
            </div>
            <div class="detail-row full-width">
                <strong>Service Description:</strong>
                <p>{{ $talent->service_description }}</p>
            </div>
        </div>
    </div>

    <!-- Portfolio Gallery -->
    @if($talent->portfolio && count($talent->portfolio) > 0)
        <div class="section-card">
            <h3 class="section-title">
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

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card stat-purple">
            <div class="stat-icon">
                <i class="fas fa-box"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $talent->packages->count() }}</h3>
                <p>Total Packages</p>
            </div>
        </div>
        <div class="stat-card stat-blue">
            <div class="stat-icon">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $talent->total_bookings }}</h3>
                <p>Total Bookings</p>
            </div>
        </div>
        <div class="stat-card stat-orange">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $talent->pending_bookings_count }}</h3>
                <p>Pending Bookings</p>
            </div>
        </div>
        <div class="stat-card stat-green">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $talent->completed_bookings_count }}</h3>
                <p>Completed Bookings</p>
            </div>
        </div>
    </div>

    <!-- Packages Section -->
    <div class="section-card">
        <h3 class="section-title">
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
                <p class="text-muted">Belum ada package</p>
            @endforelse
        </div>
    </div>

    <!-- Recent Bookings Section -->
    <div class="section-card">
        <h3 class="section-title">
            <i class="fas fa-calendar-alt"></i> Recent Bookings
            <a href="{{ route('admin.talent-booking.index', ['talent_id' => $talent->id]) }}" class="btn-secondary btn-sm">
                View All Bookings
            </a>
        </h3>
        <div class="table-container">
            <table class="admin-table">
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
                                <a href="{{ route('admin.talent-booking.show', $booking->id) }}" class="btn-action btn-view" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada booking</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.talent-info-card {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-bottom: 30px;
    display: grid;
    grid-template-columns: 250px 1fr;
    gap: 30px;
}

.talent-photo-large {
    width: 100%;
    height: 250px;
    object-fit: cover;
    border-radius: 12px;
}

.no-photo-large {
    width: 100%;
    height: 250px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 80px;
}

.talent-details-section {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.detail-row {
    display: flex;
    gap: 15px;
}

.detail-row.full-width {
    flex-direction: column;
    gap: 8px;
}

.detail-row strong {
    min-width: 180px;
    color: #495057;
}

.price-tag {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
    padding: 6px 16px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 16px;
}

.portfolio-gallery-large {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
}

.portfolio-item-large {
    aspect-ratio: 1;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
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

.packages-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}

.package-detail-card {
    background: #f8f9fa;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    overflow: hidden;
}

.package-detail-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.package-detail-header h4 {
    margin: 0;
    font-size: 18px;
}

.package-detail-body {
    padding: 20px;
}

.package-price {
    font-size: 24px;
    font-weight: 700;
    color: #f5576c;
    margin-bottom: 10px;
}

.package-duration {
    color: #6c757d;
    margin-bottom: 15px;
    font-size: 14px;
}

.package-description {
    color: #495057;
    margin-bottom: 15px;
    line-height: 1.6;
}

.package-includes {
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid #dee2e6;
}

.package-includes ul {
    list-style: none;
    padding: 0;
    margin: 10px 0 0 0;
}

.package-includes li {
    padding: 5px 0;
    color: #495057;
}

.package-includes li i {
    color: #28a745;
    margin-right: 8px;
}

.btn-sm {
    padding: 6px 12px;
    font-size: 13px;
}
</style>
@endsection
