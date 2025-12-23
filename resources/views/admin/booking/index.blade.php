@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Bookings</h1>
        <p class="page-subtitle">Kelola semua booking tiket</p>
    </div>
    
    @if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
    </div>
    @endif
    
    <div class="page-actions">
        <div class="search-box">
            <input type="text" class="search-input" placeholder="Cari booking..." id="searchInput">
            <button class="btn btn-secondary">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>
    
    <div class="card">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Kode Booking</th>
                        <th>User</th>
                        <th>Pertunjukan</th>
                        <th>Kategori Tiket</th>
                        <th>Jumlah</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                    <tr>
                        <td>
                            <div class="show-title">{{ $booking->kode_booking }}</div>
                        </td>
                        <td>
                            <div class="show-title">{{ $booking->user->name }}</div>
                            <div class="show-seniman">{{ $booking->user->email }}</div>
                        </td>
                        <td>
                            <div class="show-title">{{ $booking->pertunjukan->judul }}</div>
                            <div class="show-seniman">{{ $booking->pertunjukan->tanggal_pertunjukan->format('d M Y') }}</div>
                        </td>
                        <td>{{ $booking->ticketCategory ? $booking->ticketCategory->nama : '-' }}</td>
                        <td>{{ $booking->jumlah_tiket }} tiket</td>
                        <td>Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge badge-{{ $booking->status }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                        <td>{{ $booking->created_at->format('d M Y, H:i') }}</td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.pertunjukan.show', $booking->pertunjukan_id) }}" class="btn btn-secondary btn-sm" title="View Event">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('admin.booking.destroy', $booking) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9">
                            <div class="empty-state">
                                <i class="fas fa-ticket"></i>
                                <div style="font-size: 18px; font-weight: 600; margin-bottom: 8px; color: #CBD5E1;">
                                    Belum ada booking
                                </div>
                                <div>
                                    Belum ada booking yang masuk
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($bookings->hasPages())
        <div style="margin-top: 24px;">
            {{ $bookings->links() }}
        </div>
        @endif
    </div>
@endsection
