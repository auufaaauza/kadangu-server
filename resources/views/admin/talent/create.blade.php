@extends('layouts.admin')

@section('title', 'Tambah Talent')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Tambah Talent</h1>
        <p class="page-subtitle">Tambahkan talent/artist baru</p>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Informasi Talent</h2>
            <p class="card-subtitle">Lengkapi form di bawah untuk menambah talent baru</p>
        </div>
        
        <form action="{{ route('admin.talent.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            @include('admin.talent._form')
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Simpan Talent
                </button>
                <a href="{{ route('admin.talent.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
