@extends('layouts.admin')

@section('content')
    <style>
        .card {
            background: rgba(30, 41, 59, 0.5);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 32px;
            border: 1px solid rgba(139, 92, 246, 0.1);
        }
        
        .card-header {
            margin-bottom: 32px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(139, 92, 246, 0.1);
        }
        
        .card-title {
            color: white;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        
        .card-subtitle {
            color: #94A3B8;
            font-size: 14px;
        }
        
        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid rgba(139, 92, 246, 0.1);
        }
        
        .btn {
            padding: 14px 28px;
            border-radius: 12px;
            font-size: 15px;
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
            background: rgba(100, 116, 139, 0.1);
            color: #CBD5E1;
            border: 1px solid rgba(100, 116, 139, 0.2);
        }
        
        .btn-secondary:hover {
            background: rgba(100, 116, 139, 0.2);
        }
    </style>
    
    <div class="page-header">
        <h1 class="page-title">Edit {{ $kategoriName }}</h1>
        <p class="page-subtitle">Update informasi {{ strtolower($kategoriName) }}</p>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">{{ $pertunjukan->judul }}</h2>
            <p class="card-subtitle">Edit detail {{ strtolower($kategoriName) }} di bawah</p>
        </div>
        
        <form action="{{ route('admin.kategori.update', [$kategori, $pertunjukan->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            @include('admin.pertunjukan._form')
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Update {{ $kategoriName }}
                </button>
                <a href="{{ route('admin.kategori.index', $kategori) }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
