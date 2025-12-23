@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Tambah Pertunjukan</h1>
        <p class="page-subtitle">Buat pertunjukan seni baru</p>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Informasi Pertunjukan</h2>
            <p class="card-subtitle">Lengkapi form di bawah untuk menambah pertunjukan baru</p>
        </div>
        
        <form action="{{ route('admin.pertunjukan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            @include('admin.pertunjukan._form')
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Simpan Pertunjukan
                </button>
                <a href="{{ route('admin.pertunjukan.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
