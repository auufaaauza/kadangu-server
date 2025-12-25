@extends('layouts.admin')

@section('content')
    <style>
        .card {
            background: rgba(30, 41, 59, 0.5);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 24px;
            border: 1px solid rgba(139, 92, 246, 0.1);
            margin-bottom: 24px;
        }
        
        .card-header {
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid rgba(139, 92, 246, 0.1);
        }
        
        .card-title {
            color: white;
            font-size: 18px;
            font-weight: 700;
            margin: 0;
        }
        
        .info-table {
            width: 100%;
        }
        
        .info-table tr {
            border-bottom: 1px solid rgba(139, 92, 246, 0.05);
        }
        
        .info-table tr:last-child {
            border-bottom: none;
        }
        
        .info-table th {
            color: #94A3B8;
            font-size: 13px;
            font-weight: 600;
            padding: 12px 0;
            width: 180px;
            vertical-align: top;
        }
        
        .info-table td {
            color: #CBD5E1;
            font-size: 14px;
            padding: 12px 0;
        }
        
        .badge {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }
        
        .badge-pending {
            background: rgba(251, 191, 36, 0.1);
            color: #FBBF24;
        }
        
        .badge-paid, .badge-confirmed {
            background: rgba(52, 211, 153, 0.1);
            color: #34D399;
        }
        
        .badge-cancelled {
            background: rgba(248, 113, 113, 0.1);
            color: #F87171;
        }
        
        .badge-unpaid {
            background: rgba(248, 113, 113, 0.1);
            color: #F87171;
        }
        
        .badge-pending_verification {
            background: rgba(59, 130, 246, 0.1);
            color: #3B82F6;
        }
        
        .btn {
            padding: 12px 20px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
            cursor: pointer;
            justify-content: center;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #8B5CF6 0%, #EC4899 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(139, 92, 246, 0.4);
        }
        
        .btn-secondary {
            background: rgba(100, 116, 139, 0.1);
            color: #CBD5E1;
            border: 1px solid rgba(100, 116, 139, 0.2);
        }
        
        .btn-secondary:hover {
            background: rgba(100, 116, 139, 0.2);
        }
        
        .btn-success {
            background: rgba(52, 211, 153, 0.1);
            color: #34D399;
            border: 1px solid rgba(52, 211, 153, 0.2);
        }
        
        .btn-success:hover {
            background: rgba(52, 211, 153, 0.2);
        }
        
        .btn-info {
            background: rgba(59, 130, 246, 0.1);
            color: #3B82F6;
            border: 1px solid rgba(59, 130, 246, 0.2);
        }
        
        .btn-info:hover {
            background: rgba(59, 130, 246, 0.2);
        }
        
        .form-label {
            color: #94A3B8;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
            display: block;
        }
        
        .form-control, .form-select {
            background: rgba(15, 23, 42, 0.5);
            border: 1px solid rgba(139, 92, 246, 0.2);
            border-radius: 12px;
            padding: 12px 16px;
            color: white;
            font-size: 14px;
            width: 100%;
        }
        
        .form-control:focus, .form-select:focus {
            outline: none;
            border-color: #8B5CF6;
            background: rgba(15, 23, 42, 0.7);
        }
        
        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .alert-success {
            background: rgba(52, 211, 153, 0.1);
            border: 1px solid rgba(52, 211, 153, 0.2);
            color: #34D399;
        }
        
        .alert-danger {
            background: rgba(248, 113, 113, 0.1);
            border: 1px solid rgba(248, 113, 113, 0.2);
            color: #F87171;
        }
        
        .payment-proof-container {
            background: rgba(15, 23, 42, 0.5);
            border: 1px solid rgba(139, 92, 246, 0.2);
            border-radius: 12px;
            padding: 20px;
            text-align: center;
        }
        
        .payment-proof-container img {
            max-width: 100%;
            max-height: 500px;
            border-radius: 12px;
            border: 2px solid rgba(139, 92, 246, 0.3);
        }
        
        .empty-state {
            padding: 60px 20px;
            text-align: center;
            color: #64748B;
        }
        
        .empty-state i {
            font-size: 64px;
            margin-bottom: 16px;
            opacity: 0.3;
        }
        
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -12px;
        }
        
        .col-md-8, .col-md-4 {
            padding: 0 12px;
            width: 100%;
        }
        
        @media (min-width: 768px) {
            .col-md-4 { width: 33.333%; }
            .col-md-8 { width: 66.666%; }
        }
        
        .mb-3 { margin-bottom: 16px; }
        .mb-4 { margin-bottom: 24px; }
        .mt-3 { margin-top: 16px; }
        .w-100 { width: 100%; }
        
        hr {
            border: none;
            border-top: 1px solid rgba(139, 92, 246, 0.1);
            margin: 20px 0;
        }
        
        .page-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }
        
        .text-danger {
            color: #F87171;
            font-size: 12px;
        }
    </style>
    
    <div class="page-header">
        <div class="page-actions">
            <div>
                <h1 class="page-title">Detail Booking: {{ $booking->kode_booking }}</h1>
                <p class="page-subtitle">Detail informasi booking dan pembayaran</p>
            </div>
            <a href="{{ route('admin.booking.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i>
        {{ session('error') }}
    </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <!-- Informasi Pesanan -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Informasi Pesanan</h5>
                </div>
                <table class="info-table">
                    <tr>
                        <th>User</th>
                        <td>
                            <div style="color: white; font-weight: 600;">{{ $booking->user->name }}</div>
                            <div style="color: #94A3B8; font-size: 13px;">{{ $booking->user->email }}</div>
                        </td>
                    </tr>
                    <tr>
                        <th>Pertunjukan</th>
                        <td>
                            <div style="color: white; font-weight: 600;">{{ $booking->pertunjukan->judul }}</div>
                            <div style="color: #94A3B8; font-size: 13px;">
                                <i class="fas fa-calendar"></i> {{ $booking->pertunjukan->tanggal_pertunjukan->format('d M Y, H:i') }} WIB
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>Kategori Tiket</th>
                        <td>{{ $booking->ticketCategory ? $booking->ticketCategory->nama : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Jumlah Tiket</th>
                        <td><strong>{{ $booking->jumlah_tiket }}</strong> tiket</td>
                    </tr>
                    <tr>
                        <th>Total Harga</th>
                        <td style="color: white; font-weight: 700; font-size: 16px;">
                            Rp {{ number_format($booking->total_harga, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <th>Status Booking</th>
                        <td>
                            <span class="badge badge-{{ $booking->status }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Metode Pembayaran</th>
                        <td>{{ ucfirst($booking->payment_method ?? '-') }}</td>
                    </tr>
                    <tr>
                        <th>Status Pembayaran</th>
                        <td>
                            <span class="badge badge-{{ $booking->payment_status }}">
                                {{ ucfirst(str_replace('_', ' ', $booking->payment_status ?? 'unpaid')) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Tanggal Order</th>
                        <td>{{ $booking->created_at->format('d M Y, H:i') }} WIB</td>
                    </tr>
                </table>
            </div>

            <!-- Bukti Pembayaran -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Bukti Pembayaran</h5>
                </div>
                <div class="payment-proof-container">
                    @if($booking->payment_proof)
                        <img src="{{ asset('storage/' . $booking->payment_proof) }}" alt="Bukti Pembayaran">
                        <div class="mt-3">
                            <a href="{{ asset('storage/' . $booking->payment_proof) }}" target="_blank" class="btn btn-info">
                                <i class="fas fa-expand"></i> Lihat Ukuran Penuh
                            </a>
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-image"></i>
                            <div style="font-size: 16px; font-weight: 600; margin-bottom: 8px; color: #CBD5E1;">
                                Bukti pembayaran belum diupload
                            </div>
                            <div>
                                User belum mengupload bukti pembayaran
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Validasi Pembayaran</h5>
                </div>

                <form action="{{ route('admin.booking.updateStatus', $booking) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Update Status Pesanan</label>
                        <select name="status" id="status" class="form-select">
                            <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ $booking->status == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mb-3">
                        <i class="fas fa-save"></i> Update Status Pesanan
                    </button>
                </form>

                <hr>

                @if($booking->payment_proof)
                    <form action="{{ route('admin.booking.validatePayment', $booking) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success w-100" onclick="return confirm('Apakah anda yakin ingin memvalidasi pembayaran ini? Status akan berubah menjadi Paid/Confirmed.')">
                            <i class="fas fa-check"></i> Validasi Pembayaran
                        </button>
                    </form>
                @else
                    <button class="btn btn-secondary w-100" disabled title="Bukti pembayaran belum diupload">
                        <i class="fas fa-check"></i> Validasi Pembayaran
                    </button>
                    <small class="text-danger d-block mt-3 text-center">
                        * User belum upload bukti bayar
                    </small>
                @endif
            </div>
        </div>
    </div>
@endsection
