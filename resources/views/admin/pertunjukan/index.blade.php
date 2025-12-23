@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Pertunjukan</h1>
        <p class="page-subtitle">Kelola semua pertunjukan seni</p>
    </div>
    
    @if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
    </div>
    @endif
    
    <div class="page-actions">
        <div class="search-box">
            <input type="text" class="search-input" placeholder="Cari pertunjukan..." id="searchInput">
            <button class="btn btn-secondary">
                <i class="fas fa-search"></i>
            </button>
        </div>
        
        <a href="{{ route('admin.pertunjukan.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Tambah Pertunjukan
        </a>
    </div>
    
    <div class="card">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Pertunjukan</th>
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
                        <td>{{ $pertunjukan->seniman->nama }}</td>
                        <td>{{ $pertunjukan->tanggal_pertunjukan->format('d M Y') }}</td>
                        <td>{{ Str::limit($pertunjukan->lokasi, 20) }}</td>
                        <td>
                            @if($pertunjukan->ticketCategories->count() > 0)
                                @php
                                    $minPrice = $pertunjukan->ticketCategories->min('harga');
                                    $maxPrice = $pertunjukan->ticketCategories->max('harga');
                                @endphp
                                @if($minPrice == $maxPrice)
                                    Rp {{ number_format($minPrice, 0, ',', '.') }}
                                @else
                                    Rp {{ number_format($minPrice, 0, ',', '.') }} - Rp {{ number_format($maxPrice, 0, ',', '.') }}
                                @endif
                            @else
                                Rp {{ number_format($pertunjukan->harga ?? 0, 0, ',', '.') }}
                            @endif
                        </td>
                        <td>{{ $pertunjukan->kuota_tersisa }}/{{ $pertunjukan->kuota }}</td>
                        <td>
                            <span class="badge badge-{{ $pertunjukan->status }}">
                                {{ ucfirst($pertunjukan->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.pertunjukan.show', $pertunjukan) }}" class="btn btn-secondary btn-sm" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.pertunjukan.edit', $pertunjukan) }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.pertunjukan.destroy', $pertunjukan) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
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
                                <i class="fas fa-theater-masks"></i>
                                <div style="font-size: 18px; font-weight: 600; margin-bottom: 8px; color: #CBD5E1;">
                                    Belum ada pertunjukan
                                </div>
                                <div>
                                    Klik tombol "Tambah Pertunjukan" untuk membuat pertunjukan baru
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
