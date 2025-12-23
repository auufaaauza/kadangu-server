@extends('layouts.admin')

@section('title', 'Booking Details')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Booking Details</h1>
        <p class="page-subtitle">{{ $booking->booking_code }}</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="page-actions">
        <a href="{{ route('admin.talent-booking.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; margin-bottom: 24px;">
        <!-- Booking Information -->
        <div class="card">
            <h3 style="color: white; font-size: 18px; font-weight: 600; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid rgba(139, 92, 246, 0.2);">
                <i class="fas fa-info-circle"></i> Booking Information
            </h3>
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <div style="display: flex; justify-content: space-between;">
                    <strong style="color: #94A3B8;">Booking Code:</strong>
                    <span style="color: #CBD5E1;">{{ $booking->booking_code }}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <strong style="color: #94A3B8;">Status:</strong>
                    <span class="badge badge-{{ $booking->status == 'confirmed' ? 'success' : ($booking->status == 'pending' ? 'warning' : ($booking->status == 'completed' ? 'info' : 'danger')) }}">
                        {{ ucfirst($booking->status) }}
                    </span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <strong style="color: #94A3B8;">Created At:</strong>
                    <span style="color: #CBD5E1;">{{ $booking->created_at->format('d F Y, H:i') }}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <strong style="color: #94A3B8;">Total Price:</strong>
                    <span style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 6px 16px; border-radius: 20px; font-weight: 600;">
                        {{ $booking->formatted_total_price }}
                    </span>
                </div>
            </div>
        </div>

        <!-- User Information -->
        <div class="card">
            <h3 style="color: white; font-size: 18px; font-weight: 600; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid rgba(139, 92, 246, 0.2);">
                <i class="fas fa-user"></i> User Information
            </h3>
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <div style="display: flex; justify-content: space-between;">
                    <strong style="color: #94A3B8;">Name:</strong>
                    <span style="color: #CBD5E1;">{{ $booking->user->name }}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <strong style="color: #94A3B8;">Email:</strong>
                    <span style="color: #CBD5E1;">{{ $booking->user->email }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Talent & Package Information -->
    <div class="card" style="margin-bottom: 24px;">
        <h3 style="color: white; font-size: 18px; font-weight: 600; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid rgba(139, 92, 246, 0.2);">
            <i class="fas fa-star"></i> Talent & Package Information
        </h3>
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 30px;">
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <div style="display: flex; justify-content: space-between;">
                    <strong style="color: #94A3B8;">Talent:</strong>
                    <span style="color: #CBD5E1;">{{ $booking->talent->name }}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <strong style="color: #94A3B8;">Kategori:</strong>
                    <span style="color: #CBD5E1;">{{ $booking->talent->seniman->nama }}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <strong style="color: #94A3B8;">Genre:</strong>
                    <span class="badge badge-info">{{ $booking->talent->genre }}</span>
                </div>
            </div>
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <div style="display: flex; justify-content: space-between;">
                    <strong style="color: #94A3B8;">Package:</strong>
                    <span style="color: #CBD5E1;">{{ $booking->package->name }}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <strong style="color: #94A3B8;">Duration:</strong>
                    <span style="color: #CBD5E1;">{{ $booking->package->formatted_duration }}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <strong style="color: #94A3B8;">Package Price:</strong>
                    <span style="color: #CBD5E1;">{{ $booking->package->formatted_price }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Event Details -->
    <div class="card" style="margin-bottom: 24px;">
        <h3 style="color: white; font-size: 18px; font-weight: 600; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid rgba(139, 92, 246, 0.2);">
            <i class="fas fa-calendar-alt"></i> Event Details
        </h3>
        <div style="display: flex; flex-direction: column; gap: 15px;">
            <div style="display: flex; justify-content: space-between;">
                <strong style="color: #94A3B8;">Event Date:</strong>
                <span style="color: #CBD5E1;">{{ $booking->formatted_event_date }}</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <strong style="color: #94A3B8;">Event Time:</strong>
                <span style="color: #CBD5E1;">{{ $booking->formatted_event_time }}</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <strong style="color: #94A3B8;">Location:</strong>
                <span style="color: #CBD5E1;">{{ $booking->event_location }}</span>
            </div>
            @if($booking->event_details)
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <strong style="color: #94A3B8;">Additional Details:</strong>
                    <p style="background: rgba(30, 41, 59, 0.5); padding: 15px; border-radius: 8px; line-height: 1.6; margin: 0; color: #CBD5E1;">
                        {{ $booking->event_details }}
                    </p>
                </div>
            @endif
        </div>
    </div>

    <!-- Admin Notes & Actions -->
    <div class="card">
        <h3 style="color: white; font-size: 18px; font-weight: 600; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid rgba(139, 92, 246, 0.2);">
            <i class="fas fa-sticky-note"></i> Admin Notes & Actions
        </h3>
        <form action="{{ route('admin.talent-booking.update-status', $booking->id) }}" method="POST">
            @csrf
            <div style="margin-bottom: 20px;">
                <label for="status" style="display: block; color: #94A3B8; font-weight: 600; margin-bottom: 8px;">Update Status</label>
                <select name="status" id="status" class="search-input">
                    <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="rejected" {{ $booking->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div style="margin-bottom: 20px;">
                <label for="admin_notes" style="display: block; color: #94A3B8; font-weight: 600; margin-bottom: 8px;">Admin Notes</label>
                <textarea 
                    name="admin_notes" 
                    id="admin_notes" 
                    class="search-input" 
                    rows="4"
                    placeholder="Tambahkan catatan untuk booking ini..."
                    style="resize: vertical; font-family: inherit;"
                >{{ $booking->admin_notes }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update Status & Notes
            </button>
        </form>
    </div>
@endsection
