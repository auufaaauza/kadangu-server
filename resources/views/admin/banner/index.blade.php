@extends('layouts.admin')

@section('content')
<div class="admin-container">
    <!-- Header -->
    <div class="admin-header">
        <div>
            <h1>Banner Management</h1>
            <p class="admin-subtitle">Kelola banner untuk homepage</p>
        </div>
        <a href="{{ route('admin.banner.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Tambah Banner
        </a>
    </div>

    <!-- Success Alert -->
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Banners Table -->
    <div class="table-container">
        @if($banners->count() > 0)
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Preview</th>
                        <th>Title</th>
                        <th>Link</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($banners as $banner)
                        <tr>
                            <td>
                                <span class="badge badge-active">{{ $banner->order }}</span>
                            </td>
                            <td>
                                @if($banner->image)
                                    <img src="{{ asset('storage/' . $banner->image) }}" 
                                         alt="{{ $banner->title }}" 
                                         class="show-image"
                                         style="width: 120px; height: 60px; object-fit: cover;">
                                @else
                                    <div class="show-image" style="background: #e2e8f0; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="show-title">{{ $banner->title }}</div>
                            </td>
                            <td>
                                @if($banner->link)
                                    <a href="{{ $banner->link }}" target="_blank" class="text-primary" style="font-size: 12px;">
                                        <i class="fas fa-external-link-alt"></i>
                                        {{ Str::limit($banner->link, 30) }}
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($banner->status === 'active')
                                    <span class="badge badge-active">Active</span>
                                @else
                                    <span class="badge badge-inactive">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.banner.edit', $banner) }}" 
                                       class="btn-action btn-edit"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.banner.destroy', $banner) }}" 
                                          method="POST" 
                                          style="display: inline;"
                                          onsubmit="return confirm('Yakin ingin menghapus banner ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="pagination-container">
                {{ $banners->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-image"></i>
                <p>Belum ada banner. Klik tombol "Tambah Banner" untuk membuat banner baru.</p>
            </div>
        @endif
    </div>
</div>
@endsection
