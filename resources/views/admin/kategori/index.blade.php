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
        
        .search-box {
            display: flex;
            gap: 12px;
            flex: 1;
            max-width: 500px;
        }
        
        .search-input {
            flex: 1;
            padding: 12px 16px;
            background: rgba(30, 41, 59, 0.5);
            border: 1px solid rgba(139, 92, 246, 0.2);
            border-radius: 12px;
            color: white;
            font-size: 14px;
        }
        
        .search-input::placeholder {
            color: #64748B;
        }
        
        .search-input:focus {
            outline: none;
            border-color: #8B5CF6;
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
        
        .btn-danger {
            background: rgba(239, 68, 68, 0.1);
            color: #EF4444;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }
        
        .btn-danger:hover {
            background: rgba(239, 68, 68, 0.2);
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
        
        .show-image {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            object-fit: cover;
            border: 2px solid rgba(139, 92, 246, 0.2);
        }
        
        .show-title {
            color: white;
            font-weight: 600;
            margin-bottom: 4px;
        }
        
        .show-seniman {
            color: #94A3B8;
            font-size: 13px;
        }
        
        .badge {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }
        
        .badge-active {
            background: rgba(52, 211, 153, 0.1);
            color: #34D399;
        }
        
        .badge-inactive {
            background: rgba(148, 163, 184, 0.1);
            color: #94A3B8;
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
    </style>
    
    <div class="page-header">
        <h1 class="page-title">{{ $kategoriName }}</h1>
        <p class="page-subtitle">Kelola semua {{ strtolower($kategoriName) }}</p>
    </div>
    
    @if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
    </div>
    @endif
    
    <div class="page-actions">
        <div class="search-box">
            <input type="text" class="search-input" placeholder="Cari {{ strtolower($kategoriName) }}..." id="searchInput">
            <button class="btn btn-secondary">
                <i class="fas fa-search"></i>
            </button>
        </div>
        
        <a href="{{ route('admin.kategori.create', $kategori) }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Tambah {{ $kategoriName }}
        </a>
    </div>
    
    <div class="card">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Tanggal</th>
                        <th>Lokasi</th>
                        <th>Harga</th>
                        <th>Kuota</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pertunjukans as $pertunjukan)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                @if($pertunjukan->gambar)
                                <img src="{{ asset('storage/' . $pertunjukan->gambar) }}" alt="{{ $pertunjukan->judul }}" class="show-image">
                                @else
                                <div class="show-image" style="background: linear-gradient(135deg, #8B5CF6 0%, #EC4899 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                                    {{ strtoupper(substr($pertunjukan->judul, 0, 1)) }}
                                </div>
                                @endif
                                <div>
                                    <div class="show-title">{{ $pertunjukan->judul }}</div>
                                    <div class="show-seniman">{{ Str::limit($pertunjukan->deskripsi, 50) }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $pertunjukan->artistGroup?->nama }}</td>
                        <td>{{ $pertunjukan->tanggal_pertunjukan->format('d M Y') }}</td>
                        <td>{{ Str::limit($pertunjukan->lokasi, 20) }}</td>
                        <td>Rp {{ number_format($pertunjukan->harga, 0, ',', '.') }}</td>
                        <td>{{ $pertunjukan->kuota_tersisa }}/{{ $pertunjukan->kuota }}</td>
                        <td>
                            <span class="badge badge-{{ $pertunjukan->status }}">
                                {{ ucfirst($pertunjukan->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.kategori.edit', [$kategori, $pertunjukan->id]) }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.kategori.destroy', [$kategori, $pertunjukan->id]) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
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
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="fas fa-theater-masks"></i>
                                <div style="font-size: 18px; font-weight: 600; margin-bottom: 8px; color: #CBD5E1;">
                                    Belum ada {{ strtolower($kategoriName) }}
                                </div>
                                <div>
                                    Klik tombol "Tambah {{ $kategoriName }}" untuk membuat data baru
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($pertunjukans->hasPages())
        <div style="margin-top: 24px;">
            {{ $pertunjukans->links() }}
        </div>
        @endif
    </div>
@endsection
