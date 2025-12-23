@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <h1 class="page-title">{{ $pertunjukan->judul }}</h1>
        <p class="page-subtitle">{{ $pertunjukan->seniman->nama }} • {{ $pertunjukan->tanggal_pertunjukan->format('d M Y, H:i') }}</p>
    </div>
    
    @if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
    </div>
    @endif
    
    <!-- Statistics Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 32px;">
        <!-- Total Tiket Terjual -->
        <div class="card" style="background: linear-gradient(135deg, #8B5CF6 0%, #EC4899 100%);">
            <div style="display: flex; align-items: center; gap: 16px;">
                <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-ticket fa-2x" style="color: white;"></i>
                </div>
                <div>
                    <div style="color: rgba(255,255,255,0.8); font-size: 14px; margin-bottom: 4px;">Total Tiket Terjual</div>
                    <div style="color: white; font-size: 32px; font-weight: 700;">{{ $pertunjukan->total_sold }}</div>
                </div>
            </div>
        </div>
        
        <!-- Sisa Tiket -->
        <div class="card" style="background: linear-gradient(135deg, #3B82F6 0%, #06B6D4 100%);">
            <div style="display: flex; align-items: center; gap: 16px;">
                <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-clock fa-2x" style="color: white;"></i>
                </div>
                <div>
                    <div style="color: rgba(255,255,255,0.8); font-size: 14px; margin-bottom: 4px;">Sisa Tiket</div>
                    <div style="color: white; font-size: 32px; font-weight: 700;">{{ $pertunjukan->kuota_tersisa }}</div>
                </div>
            </div>
        </div>
        
        <!-- Total Pendapatan -->
        <div class="card" style="background: linear-gradient(135deg, #10B981 0%, #34D399 100%);">
            <div style="display: flex; align-items: center; gap: 16px;">
                <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-money-bill-wave fa-2x" style="color: white;"></i>
                </div>
                <div>
                    <div style="color: rgba(255,255,255,0.8); font-size: 14px; margin-bottom: 4px;">Total Pendapatan</div>
                    <div style="color: white; font-size: 28px; font-weight: 700;">Rp {{ number_format($pertunjukan->total_revenue, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        
        <!-- Total Bookings -->
        <div class="card" style="background: linear-gradient(135deg, #F59E0B 0%, #FBBF24 100%);">
            <div style="display: flex; align-items: center; gap: 16px;">
                <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-users fa-2x" style="color: white;"></i>
                </div>
                <div>
                    <div style="color: rgba(255,255,255,0.8); font-size: 14px; margin-bottom: 4px;">Total Bookings</div>
                    <div style="color: white; font-size: 32px; font-weight: 700;">{{ $pertunjukan->total_bookings }}</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Participants Table -->
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h2 class="card-title" style="margin: 0;">Daftar Peserta</h2>
            
            <div style="display: flex; gap: 12px;">
                <select id="bulkAction" class="form-select" style="width: 200px;">
                    <option value="">Bulk Actions</option>
                    <option value="update-payment">Update Payment Status</option>
                    <option value="delete">Delete Selected</option>
                </select>
                <button onclick="executeBulkAction()" class="btn btn-primary btn-sm">
                    <i class="fas fa-check"></i>
                    Apply
                </button>
            </div>
        </div>
        
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 40px;">
                            <input type="checkbox" id="selectAll" onchange="toggleSelectAll(this)">
                        </th>
                        <th>Peserta</th>
                        <th>Kategori Tiket</th>
                        <th>Jumlah</th>
                        <th>Total Harga</th>
                        <th>Payment Status</th>
                        <th>Booking Date</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                    <tr>
                        <td>
                            <input type="checkbox" class="booking-checkbox" value="{{ $booking->id }}">
                        </td>
                        <td>
                            <div>
                                <div class="show-title">{{ $booking->user->name }}</div>
                                <div class="show-seniman">{{ $booking->user->email }}</div>
                                <div class="show-seniman">{{ $booking->kode_booking }}</div>
                            </div>
                        </td>
                        <td>{{ $booking->ticketCategory ? $booking->ticketCategory->nama : 'Regular' }}</td>
                        <td>{{ $booking->jumlah_tiket }} tiket</td>
                        <td>Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                        <td>
                            @if($booking->transaction)
                            <span class="badge badge-{{ $booking->transaction->status }}">
                                {{ ucfirst($booking->transaction->status) }}
                            </span>
                            @else
                            <span class="badge badge-pending">Pending</span>
                            @endif
                        </td>
                        <td>{{ $booking->created_at->format('d M Y, H:i') }}</td>
                        <td>
                            <div class="action-buttons">
                                @if($booking->transaction && $booking->transaction->status != 'paid')
                                <form action="{{ route('admin.booking.update-payment', $booking) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-secondary btn-sm" title="Mark as Paid">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                </form>
                                @endif
                                <form action="{{ route('admin.booking.destroy', $booking) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')" style="display: inline;">
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
                        <td colspan="8">
                            <div class="empty-state">
                                <i class="fas fa-users"></i>
                                <div style="font-size: 18px; font-weight: 600; margin-bottom: 8px; color: #CBD5E1;">
                                    Belum ada peserta
                                </div>
                                <div>
                                    Belum ada yang booking untuk event ini
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
    
    <div style="margin-top: 24px;">
        <a href="{{ route('admin.pertunjukan.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Kembali
        </a>
    </div>
@endsection

<script>
function toggleSelectAll(checkbox) {
    const checkboxes = document.querySelectorAll('.booking-checkbox');
    checkboxes.forEach(cb => cb.checked = checkbox.checked);
}

function executeBulkAction() {
    const action = document.getElementById('bulkAction').value;
    const selected = Array.from(document.querySelectorAll('.booking-checkbox:checked')).map(cb => cb.value);
    
    if (!action) {
        alert('Pilih action terlebih dahulu');
        return;
    }
    
    if (selected.length === 0) {
        alert('Pilih minimal 1 booking');
        return;
    }
    
    if (action === 'update-payment') {
        if (confirm(`Update payment status untuk ${selected.length} booking?`)) {
            bulkUpdatePayment(selected);
        }
    } else if (action === 'delete') {
        if (confirm(`Hapus ${selected.length} booking? Aksi ini tidak bisa dibatalkan!`)) {
            bulkDelete(selected);
        }
    }
}

function bulkUpdatePayment(ids) {
    fetch('{{ route("admin.booking.bulk-update-payment") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ booking_ids: ids })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        alert('Error: ' + error);
    });
}

function bulkDelete(ids) {
    fetch('{{ route("admin.booking.bulk-delete") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ booking_ids: ids })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        alert('Error: ' + error);
    });
}
</script>
