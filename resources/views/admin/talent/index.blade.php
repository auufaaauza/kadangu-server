@extends('layouts.admin')

@section('title', 'Talent Management')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Talent Management</h1>
        <p class="page-subtitle">Kelola talent/artist untuk booking</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="page-actions">
        <div class="search-box">
            <form method="GET" action="{{ route('admin.talent.index') }}" style="display: flex; gap: 12px; width: 100%;">
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Cari talent..." 
                    value="{{ request('search') }}"
                    class="search-input"
                >
                <select name="kategori" class="search-input" style="max-width: 200px;">
                    <option value="">Semua Kategori</option>
                    @foreach($artistGroups as $artistGroup)
                        <option value="{{ $artistGroup->id }}" {{ request('kategori') == $artistGroup->id ? 'selected' : '' }}>
                            {{ $artistGroup->nama }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-secondary">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
        
        <a href="{{ route('admin.talent.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Tambah Talent
        </a>
    </div>

    <div class="card">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Talent</th>
                        <th>Kategori</th>
                        <th>Genre</th>
                        <th>Base Price</th>
                        <th>Packages</th>
                        <th>Bookings</th>
                        <th>Availability</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($talents as $talent)
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    @if($talent->photo)
                                        <img src="{{ asset('storage/' . $talent->photo) }}" alt="{{ $talent->name }}" class="show-image">
                                    @else
                                        <div class="show-image" style="background: linear-gradient(135deg, #8B5CF6 0%, #EC4899 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                                            {{ strtoupper(substr($talent->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="show-title">{{ $talent->name }}</div>
                                        <div class="show-seniman">{{ Str::limit($talent->bio, 50) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $talent->artistGroup->nama }}</td>
                            <td>
                                <span class="badge badge-info">{{ $talent->genre }}</span>
                            </td>
                            <td>{{ $talent->formatted_base_price }}</td>
                            <td>
                                <span class="badge badge-secondary">{{ $talent->active_packages_count }} Active</span>
                            </td>
                            <td>
                                <span class="badge badge-secondary">{{ $talent->total_bookings }} Total</span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $talent->availability_status == 'available' ? 'success' : ($talent->availability_status == 'booked' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($talent->availability_status) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $talent->status == 'active' ? 'success' : 'inactive' }}">
                                    {{ ucfirst($talent->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.talent.show', $talent->id) }}" class="btn btn-secondary btn-sm" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.talent.edit', $talent->id) }}" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.talent.destroy', $talent->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus talent ini?')">
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
                                    <i class="fas fa-star"></i>
                                    <div style="font-size: 18px; font-weight: 600; margin-bottom: 8px; color: #CBD5E1;">
                                        Belum ada talent
                                    </div>
                                    <div>
                                        Klik tombol "Tambah Talent" untuk menambahkan talent baru
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($talents->hasPages())
        <div style="margin-top: 24px;">
            {{ $talents->links() }}
        </div>
        @endif
    </div>
@endsection
