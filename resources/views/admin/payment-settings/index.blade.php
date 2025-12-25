@extends('layouts.admin')

@section('content')
    <style>
        .page-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 16px;
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
            background: rgba(59, 130, 246, 0.1);
            color: #3B82F6;
            border: 1px solid rgba(59, 130, 246, 0.2);
        }
        
        .btn-secondary:hover {
            background: rgba(59, 130, 246, 0.2);
        }
        
        .btn-warning {
            background: rgba(251, 191, 36, 0.1);
            color: #FBBF24;
            border: 1px solid rgba(251, 191, 36, 0.2);
        }
        
        .btn-warning:hover {
            background: rgba(251, 191, 36, 0.2);
        }
        
        .btn-danger {
            background: rgba(239, 68, 68, 0.1);
            color: #EF4444;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }
        
        .btn-danger:hover {
            background: rgba(239, 68, 68, 0.2);
        }
        
        .btn-success {
            background: rgba(52, 211, 153, 0.1);
            color: #34D399;
            border: 1px solid rgba(52, 211, 153, 0.2);
        }
        
        .btn-success:hover {
            background: rgba(52, 211, 153, 0.2);
        }
        
        .btn-sm {
            padding: 8px 12px;
            font-size: 13px;
        }
        
        .card {
            background: rgba(30, 41, 59, 0.5);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 24px;
            border: 1px solid rgba(139, 92, 246, 0.1);
        }
        
        .table-container {
            overflow-x: auto;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table thead tr {
            border-bottom: 1px solid rgba(139, 92, 246, 0.2);
        }
        
        .table th {
            color: #94A3B8;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 16px 12px;
            text-align: left;
        }
        
        .table td {
            color: #CBD5E1;
            font-size: 14px;
            padding: 20px 12px;
            border-bottom: 1px solid rgba(139, 92, 246, 0.05);
        }
        
        .table tbody tr {
            transition: all 0.3s ease;
        }
        
        .table tbody tr:hover {
            background: rgba(139, 92, 246, 0.05);
        }
        
        .badge {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }
        
        .badge-qris {
            background: rgba(59, 130, 246, 0.1);
            color: #3B82F6;
        }
        
        .badge-bank {
            background: rgba(52, 211, 153, 0.1);
            color: #34D399;
        }
        
        .action-buttons {
            display: flex;
            gap: 8px;
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
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #64748B;
        }
        
        .empty-state i {
            font-size: 64px;
            margin-bottom: 16px;
            opacity: 0.3;
        }
        
        .qris-image {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            object-fit: cover;
            border: 2px solid rgba(139, 92, 246, 0.2);
        }
        
        .payment-details {
            color: white;
            font-weight: 600;
            margin-bottom: 4px;
        }
        
        .payment-info {
            color: #94A3B8;
            font-size: 13px;
        }
    </style>
    
    <div class="page-header">
        <h1 class="page-title">Payment Settings</h1>
        <p class="page-subtitle">Kelola metode pembayaran (QRIS & Bank Transfer)</p>
    </div>
    
    @if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
    </div>
    @endif
    
    <div class="page-actions">
        <div></div>
        <a href="{{ route('admin.payment-settings.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Tambah Metode Pembayaran
        </a>
    </div>
    
    <div class="card">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Type</th>
                        <th>Name</th>
                        <th>Details</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paymentSettings as $setting)
                    <tr>
                        <td>{{ $setting->display_order }}</td>
                        <td>
                            @if($setting->type === 'qris')
                                <span class="badge badge-qris">
                                    <i class="fas fa-qrcode"></i> QRIS
                                </span>
                            @else
                                <span class="badge badge-bank">
                                    <i class="fas fa-university"></i> Bank
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="payment-details">{{ $setting->name }}</div>
                            @if($setting->instructions)
                            <div class="payment-info">{{ Str::limit($setting->instructions, 50) }}</div>
                            @endif
                        </td>
                        <td>
                            @if($setting->type === 'qris')
                                @if($setting->qris_image)
                                <img src="{{ asset('storage/' . $setting->qris_image) }}" 
                                     alt="QRIS" 
                                     class="qris-image">
                                @else
                                <span class="payment-info">No image</span>
                                @endif
                            @else
                                <div>
                                    <div class="payment-details">{{ $setting->bank_name }}</div>
                                    <div class="payment-info">{{ $setting->account_number }}</div>
                                    <div class="payment-info">a.n. {{ $setting->account_holder }}</div>
                                </div>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('admin.payment-settings.toggle', $setting) }}" 
                                  method="POST" 
                                  class="d-inline">
                                @csrf
                                <button type="submit" 
                                        class="btn btn-sm {{ $setting->is_active ? 'btn-success' : 'btn-secondary' }}">
                                    <i class="fas fa-{{ $setting->is_active ? 'check' : 'times' }}"></i>
                                    {{ $setting->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </form>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.payment-settings.edit', $setting) }}" 
                                   class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.payment-settings.destroy', $setting) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Yakin ingin menghapus metode pembayaran ini?')">
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
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="fas fa-credit-card"></i>
                                <div style="font-size: 18px; font-weight: 600; margin-bottom: 8px; color: #CBD5E1;">
                                    Belum ada metode pembayaran
                                </div>
                                <div>
                                    Klik tombol "Tambah Metode Pembayaran" untuk menambahkan QRIS atau Bank Transfer
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
