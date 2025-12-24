@extends('layouts.admin')

@section('content')
<div class="admin-container">
    <!-- Header -->
    <div class="admin-header">
        <div>
            <h1>Tambah Banner</h1>
            <p class="admin-subtitle">Upload banner baru untuk homepage</p>
        </div>
        <a href="{{ route('admin.banner.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="card">
        <form action="{{ route('admin.banner.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-grid">
                <!-- Title -->
                <div class="form-group full-width">
                    <label class="form-label">
                        Judul Banner <span class="required">*</span>
                    </label>
                    <input type="text" 
                           name="title" 
                           class="form-input @error('title') border-red-500 @enderror" 
                           value="{{ old('title') }}" 
                           required
                           placeholder="Masukkan judul banner">
                    @error('title')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Image -->
                <div class="form-group full-width">
                    <label class="form-label">
                        Gambar Banner <span class="required">*</span>
                    </label>
                    <input type="file" 
                           name="image" 
                           class="form-file @error('image') border-red-500 @enderror" 
                           accept="image/*"
                           required
                           onchange="previewImage(event)">
                    <span class="form-hint">Format: JPG, PNG, GIF. Maksimal 2MB. Rekomendasi ukuran: 1920x600px</span>
                    @error('image')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                    
                    <!-- Image Preview -->
                    <div id="imagePreview" class="image-preview" style="display: none;">
                        <img id="preview" src="" alt="Preview">
                    </div>
                </div>

                <!-- Link -->
                <div class="form-group full-width">
                    <label class="form-label">
                        Link (Optional)
                    </label>
                    <input type="url" 
                           name="link" 
                           class="form-input @error('link') border-red-500 @enderror" 
                           value="{{ old('link') }}" 
                           placeholder="https://example.com">
                    <span class="form-hint">URL yang akan dibuka saat banner diklik</span>
                    @error('link')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Order -->
                <div class="form-group">
                    <label class="form-label">
                        Urutan <span class="required">*</span>
                    </label>
                    <input type="number" 
                           name="order" 
                           class="form-input @error('order') border-red-500 @enderror" 
                           value="{{ old('order', 0) }}" 
                           min="0"
                           required>
                    <span class="form-hint">Semakin kecil angka, semakin awal ditampilkan</span>
                    @error('order')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Status -->
                <div class="form-group">
                    <label class="form-label">
                        Status <span class="required">*</span>
                    </label>
                    <select name="status" class="form-select @error('status') border-red-500 @enderror" required>
                        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Actions -->
            <div class="form-actions">
                <a href="{{ route('admin.banner.index') }}" class="btn btn-secondary">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Simpan Banner
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(event) {
    const preview = document.getElementById('preview');
    const previewContainer = document.getElementById('imagePreview');
    const file = event.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewContainer.style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endsection
