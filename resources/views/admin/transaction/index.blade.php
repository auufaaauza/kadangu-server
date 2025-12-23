@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Transactions</h1>
        <p class="page-subtitle">Kelola semua transaksi pembayaran</p>
    </div>
    
    @if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
    </div>
    @endif
    
    <div class="page-actions">
        <div class="search-box">
            <input type="text" class="search-input" placeholder="Cari transaksi..." id="searchInput">
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
                        <th>Transaction ID</th>
                        <th>User</th>
                        <th>Pertunjukan</th>
                        <th>Jumlah</th>
                        <th>Payment Method</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                    <tr>
                        <td>
                            <div class="show-title">{{ $transaction->transaction_id ?? 'TRX-' . $transaction->id }}</div>
                        </td>
                        <td>
                            <div class="show-title">{{ $transaction->booking->user->name }}</div>
                            <div class="show-seniman">{{ $transaction->booking->user->email }}</div>
                        </td>
                        <td>
                            <div class="show-title">{{ $transaction->booking->pertunjukan->judul }}</div>
                            <div class="show-seniman">{{ $transaction->booking->kode_booking }}</div>
                        </td>
                        <td>Rp {{ number_format($transaction->amount ?? $transaction->booking->total_harga, 0, ',', '.') }}</td>
                        <td>{{ $transaction->payment_method ?? 'Manual' }}</td>
                        <td>
                            <span class="badge badge-{{ $transaction->status }}">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </td>
                        <td>{{ $transaction->created_at->format('d M Y, H:i') }}</td>
                        <td>
                            <div class="action-buttons">
                                @if($transaction->status != 'paid')
                                <form action="{{ route('admin.transaction.updateStatus', $transaction) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="paid">
                                    <button type="submit" class="btn btn-secondary btn-sm" title="Mark as Paid">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <i class="fas fa-money-bill-wave"></i>
                                <div style="font-size: 18px; font-weight: 600; margin-bottom: 8px; color: #CBD5E1;">
                                    Belum ada transaksi
                                </div>
                                <div>
                                    Belum ada transaksi pembayaran
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($transactions->hasPages())
        <div style="margin-top: 24px;">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
@endsection
