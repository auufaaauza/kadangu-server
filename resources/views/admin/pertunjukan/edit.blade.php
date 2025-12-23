@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Edit Pertunjukan</h1>
        <p class="page-subtitle">Update informasi pertunjukan</p>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">{{ $pertunjukan->judul }}</h2>
            <p class="card-subtitle">Edit detail pertunjukan di bawah</p>
        </div>
        
        <form action="{{ route('admin.pertunjukan.update', $pertunjukan) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            @include('admin.pertunjukan._form')
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Update Pertunjukan
                </button>
                <a href="{{ route('admin.pertunjukan.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
