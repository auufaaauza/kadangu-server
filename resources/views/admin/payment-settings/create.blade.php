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
        
        .form-label {
            color: #CBD5E1;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
            display: block;
        }
        
        .form-control, .form-select {
            background: rgba(15, 23, 42, 0.5);
            border: 1px solid rgba(139, 92, 246, 0.2);
            border-radius: 12px;
            padding: 12px 16px;
            color: white;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            outline: none;
            border-color: #8B5CF6;
            background: rgba(15, 23, 42, 0.7);
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
        }
        
        .form-control::placeholder {
            color: #64748B;
        }
        
        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }
        
        .text-danger {
            color: #F87171 !important;
        }
        
        .text-muted {
            color: #64748B !important;
            font-size: 13px;
        }
        
        .invalid-feedback {
            color: #F87171;
            font-size: 13px;
            margin-top: 4px;
        }
        
        .is-invalid {
            border-color: #F87171 !important;
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
        
        .mb-3 {
            margin-bottom: 20px;
        }
        
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -12px;
        }
        
        .col-md-4, .col-md-6, .col-md-8, .col-md-2 {
            padding: 0 12px;
            width: 100%;
        }
        
        @media (min-width: 768px) {
            .col-md-2 { width: 16.666%; }
            .col-md-4 { width: 33.333%; }
            .col-md-6 { width: 50%; }
            .col-md-8 { width: 66.666%; }
        }
        
        .qris-preview {
            margin-top: 12px;
            padding: 16px;
            background: rgba(15, 23, 42, 0.5);
            border: 1px solid rgba(139, 92, 246, 0.2);
            border-radius: 12px;
        }
        
        .qris-preview img {
            max-width: 200px;
            border-radius: 8px;
            border: 2px solid rgba(139, 92, 246, 0.3);
        }
    </style>
    
    <div class="page-header">
        <h1 class="page-title">Tambah Metode Pembayaran</h1>
        <p class="page-subtitle">Tambahkan QRIS atau Bank Transfer sebagai metode pembayaran</p>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Informasi Pembayaran</h2>
            <p class="card-subtitle">Lengkapi form di bawah untuk menambah metode pembayaran baru</p>
        </div>
        
        <form action="{{ route('admin.payment-settings.store') }}" 
              method="POST" 
              enctype="multipart/form-data"
              id="paymentForm">
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="type" class="form-label">Tipe Pembayaran <span class="text-danger">*</span></label>
                        <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                            <option value="">Pilih Tipe</option>
                            <option value="qris" {{ old('type') === 'qris' ? 'selected' : '' }}>QRIS</option>
                            <option value="bank_account" {{ old('type') === 'bank_account' ? 'selected' : '' }}>Bank Transfer</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Tampilan <span class="text-danger">*</span></label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}"
                               placeholder="Contoh: QRIS Kadangu, BCA - Kadangu"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- QRIS Fields -->
            <div id="qrisFields" style="display: none;">
                <div class="mb-3">
                    <label for="qris_image" class="form-label">
                        Gambar QRIS <span class="text-danger">*</span>
                        <span class="text-muted">(Max 2MB, JPG/PNG)</span>
                    </label>
                    <input type="file" 
                           name="qris_image" 
                           id="qris_image" 
                           class="form-control @error('qris_image') is-invalid @enderror"
                           accept="image/jpeg,image/png,image/jpg">
                    @error('qris_image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Bank Account Fields -->
            <div id="bankFields" style="display: none;">
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="bank_name" class="form-label">Nama Bank <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="bank_name" 
                                   id="bank_name" 
                                   class="form-control @error('bank_name') is-invalid @enderror" 
                                   value="{{ old('bank_name') }}"
                                   placeholder="Contoh: BCA, Mandiri, BNI">
                            @error('bank_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="account_number" class="form-label">Nomor Rekening <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="account_number" 
                                   id="account_number" 
                                   class="form-control @error('account_number') is-invalid @enderror" 
                                   value="{{ old('account_number') }}"
                                   placeholder="Hanya angka"
                                   pattern="[0-9]+">
                            @error('account_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="account_holder" class="form-label">Nama Pemilik <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="account_holder" 
                                   id="account_holder" 
                                   class="form-control @error('account_holder') is-invalid @enderror" 
                                   value="{{ old('account_holder') }}"
                                   placeholder="Nama pemilik rekening">
                            @error('account_holder')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="instructions" class="form-label">Instruksi Pembayaran (Opsional)</label>
                        <textarea name="instructions" 
                                  id="instructions" 
                                  rows="3" 
                                  class="form-control @error('instructions') is-invalid @enderror"
                                  placeholder="Instruksi tambahan untuk customer">{{ old('instructions') }}</textarea>
                        @error('instructions')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="mb-3">
                        <label for="display_order" class="form-label">Urutan</label>
                        <input type="number" 
                               name="display_order" 
                               id="display_order" 
                               class="form-control @error('display_order') is-invalid @enderror" 
                               value="{{ old('display_order', 0) }}"
                               min="0">
                        @error('display_order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="mb-3">
                        <label for="is_active" class="form-label">Status</label>
                        <select name="is_active" id="is_active" class="form-select">
                            <option value="1" {{ old('is_active', true) ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !old('is_active', true) ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Simpan
                </button>
                <a href="{{ route('admin.payment-settings.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    Batal
                </a>
            </div>
        </form>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const qrisFields = document.getElementById('qrisFields');
    const bankFields = document.getElementById('bankFields');
    const qrisImage = document.getElementById('qris_image');
    const bankName = document.getElementById('bank_name');
    const accountNumber = document.getElementById('account_number');
    const accountHolder = document.getElementById('account_holder');

    function toggleFields() {
        const type = typeSelect.value;
        
        if (type === 'qris') {
            qrisFields.style.display = 'block';
            bankFields.style.display = 'none';
            qrisImage.required = true;
            bankName.required = false;
            accountNumber.required = false;
            accountHolder.required = false;
        } else if (type === 'bank_account') {
            qrisFields.style.display = 'none';
            bankFields.style.display = 'block';
            qrisImage.required = false;
            bankName.required = true;
            accountNumber.required = true;
            accountHolder.required = true;
        } else {
            qrisFields.style.display = 'none';
            bankFields.style.display = 'none';
        }
    }

    typeSelect.addEventListener('change', toggleFields);
    toggleFields(); // Initial call
});
</script>
@endsection
