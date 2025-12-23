@extends('layouts.admin')

@section('title', 'Edit Talent')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Edit Talent</h1>
        <p class="page-subtitle">Update informasi talent</p>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Informasi Talent</h2>
            <p class="card-subtitle">Update form di bawah untuk mengubah informasi talent</p>
        </div>
        
        <form action="{{ route('admin.talent.update', $talent->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            @include('admin.talent._form')
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Update Talent
                </button>
                <a href="{{ route('admin.talent.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
