@extends('layouts.admin')

@section('title', 'Talent Bookings')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Talent Bookings</h1>
        <p class="page-subtitle">Kelola booking talent/artist</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Filters -->
    <div class="page-actions">
        <div class="search-box">
            <form method="GET" action="{{ route('admin.talent-booking.index') }}" style="display: flex; gap: 12px; width: 100%;">
                <select name="status" class="search-input" style="max-width: 200px;">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <select name="talent_id" class="search-input" style="max-width: 200px;">
                    <option value="">Semua Talent</option>
                    @foreach($talents as $talent)
                        <option value="{{ $talent->id }}" {{ request('talent_id') == $talent->id ? 'selected' : '' }}>
                            {{ $talent->name }}
                        </option>
                    @endforeach
                </select>
                <input 
                    type="date" 
                    name="date_from" 
                    class="search-input" 
                    value="{{ request('date_from') }}"
                    placeholder="From Date"
                    style="max-width: 180px;"
                >
                <input 
                    type="date" 
                    name="date_to" 
                    class="search-input" 
                    value="{{ request('date_to') }}"
                    placeholder="To Date"
                    style="max-width: 180px;"
                >
                <button type="submit" class="btn btn-secondary">
                    <i class="fas fa-filter"></i>
                </button>
                <a href="{{ route('admin.talent-booking.index') }}" class="btn btn-secondary">
                    <i class="fas fa-redo"></i>
                </a>
            </form>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="card">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Booking Code</th>
                        <th>User</th>
                        <th>Talent</th>
                        <th>Package</th>
                        <th>Event Date & Time</th>
                        <th>Location</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                            <td><strong>{{ $booking->booking_code }}</strong></td>
                            <td>{{ $booking->user->name }}</td>
                            <td>{{ $booking->talent->name }}</td>
                            <td>{{ $booking->package->name }}</td>
                            <td>
                                {{ $booking->formatted_event_date }}<br>
                                <small style="color: #94A3B8;">{{ $booking->formatted_event_time }}</small>
                            </td>
                            <td>{{ Str::limit($booking->event_location, 30) }}</td>
                            <td>{{ $booking->formatted_total_price }}</td>
                            <td>
                                <span class="badge badge-{{ $booking->status == 'confirmed' ? 'success' : ($booking->status == 'pending' ? 'warning' : ($booking->status == 'completed' ? 'info' : 'danger')) }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td>{{ $booking->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.talent-booking.show', $booking->id) }}" class="btn btn-secondary btn-sm" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.talent-booking.destroy', $booking->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus booking ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10">
                                <div class="empty-state">
                                    <i class="fas fa-calendar-check"></i>
                                    <div style="font-size: 18px; font-weight: 600; margin-bottom: 8px; color: #CBD5E1;">
                                        Belum ada booking
                                    </div>
                                    <div>
                                        Booking talent akan muncul di sini
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($bookings->hasPages())
            <div style="margin-top: 24px;">
                {{ $bookings->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
@endsection
