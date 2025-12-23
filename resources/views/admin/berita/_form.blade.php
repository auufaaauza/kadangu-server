<style>
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }
    
    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .form-group.full-width {
        grid-column: 1 / -1;
    }
    
    .form-label {
        color: #CBD5E1;
        font-size: 14px;
        font-weight: 600;
    }
    
    .form-label .required {
        color: #EF4444;
    }
    
    .form-input,
    .form-select,
    .form-textarea {
        padding: 12px 16px;
        background: rgba(15, 23, 42, 0.5);
        border: 1px solid rgba(139, 92, 246, 0.2);
        border-radius: 12px;
        color: white;
        font-size: 14px;
        font-family: 'Inter', sans-serif;
        transition: all 0.3s ease;
    }
    
    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #8B5CF6;
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
    }
    
    .form-textarea {
        min-height: 200px;
        resize: vertical;
        line-height: 1.6;
    }
    
    .form-file {
        padding: 12px;
        background: rgba(15, 23, 42, 0.5);
        border: 2px dashed rgba(139, 92, 246, 0.3);
        border-radius: 12px;
        color: #CBD5E1;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .form-file:hover {
        border-color: #8B5CF6;
        background: rgba(139, 92, 246, 0.05);
    }
    
    .form-error {
        color: #EF4444;
        font-size: 13px;
        margin-top: 4px;
    }
    
    .form-hint {
        color: #64748B;
        font-size: 12px;
        margin-top: 4px;
    }
    
    .image-preview {
        margin-top: 12px;
        border-radius: 12px;
        overflow: hidden;
        max-width: 400px;
    }
    
    .image-preview img {
        width: 100%;
        height: auto;
        display: block;
    }
    
    .flatpickr-input {
        cursor: pointer;
    }
</style>

<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<div class="form-grid">
    <!-- Judul -->
    <div class="form-group full-width">
        <label class="form-label">
            Judul Berita <span class="required">*</span>
        </label>
        <input 
            type="text" 
            name="judul" 
            class="form-input" 
            value="{{ old('judul', $berita->judul ?? '') }}"
            placeholder="Masukkan judul berita"
            required
        >
        @error('judul')
        <span class="form-error">{{ $message }}</span>
        @enderror
    </div>
    
    <!-- Konten -->
    <div class="form-group full-width">
        <label class="form-label">
            Konten <span class="required">*</span>
        </label>
        <textarea 
            name="konten" 
            class="form-textarea" 
            placeholder="Masukkan konten berita&#10;&#10;Gunakan:&#10;- Enter untuk baris baru&#10;- Numbering: 1. Item pertama&#10;- Bullet: - Item dengan bullet"
            required
        >{{ old('konten', $berita->konten ?? '') }}</textarea>
        <span class="form-hint">Konten mendukung line breaks dan formatting. Tekan Enter untuk baris baru.</span>
        @error('konten')
        <span class="form-error">{{ $message }}</span>
        @enderror
    </div>
    
    <!-- Kategori -->
    <div class="form-group">
        <label class="form-label">
            Kategori <span class="required">*</span>
        </label>
        <input 
            type="text" 
            name="kategori" 
            class="form-input" 
            value="{{ old('kategori', $berita->kategori ?? '') }}"
            placeholder="Contoh: Seni, Budaya, Event"
            required
        >
        <span class="form-hint">Masukkan kategori berita</span>
        @error('kategori')
        <span class="form-error">{{ $message }}</span>
        @enderror
    </div>
    
    <!-- Tanggal Publish -->
    <div class="form-group">
        <label class="form-label">
            Tanggal Publish
        </label>
        <input 
            type="text" 
            name="published_at" 
            id="published_at"
            class="form-input" 
            value="{{ old('published_at', isset($berita) && $berita->published_at ? $berita->published_at->format('Y-m-d H:i') : '') }}"
            placeholder="Pilih tanggal publish (opsional)"
            readonly
        >
        <span class="form-hint">Kosongkan jika ingin save sebagai draft</span>
        @error('published_at')
        <span class="form-error">{{ $message }}</span>
        @enderror
    </div>
    
    <!-- Gambar -->
    <div class="form-group full-width">
        <label class="form-label">
            Gambar Berita {{ isset($berita) ? '' : '*' }}
        </label>
        <input 
            type="file" 
            name="gambar" 
            class="form-file" 
            accept="image/*"
            onchange="previewImage(event)"
            {{ isset($berita) ? '' : 'required' }}
        >
        <span class="form-hint">Format: JPG, PNG, JPEG. Maksimal 2MB</span>
        @error('gambar')
        <span class="form-error">{{ $message }}</span>
        @enderror
        
        @if(isset($berita) && $berita->gambar)
        <div class="image-preview">
            <img src="{{ asset('storage/' . $berita->gambar) }}" alt="Current Image" id="preview">
        </div>
        @else
        <div class="image-preview" id="previewContainer" style="display: none;">
            <img src="" alt="Preview" id="preview">
        </div>
        @endif
    </div>
</div>

<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
// Initialize Flatpickr
document.addEventListener('DOMContentLoaded', function() {
    flatpickr("#published_at", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        time_24hr: true,
        locale: {
            firstDayOfWeek: 1,
            weekdays: {
                shorthand: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                longhand: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
            },
            months: {
                shorthand: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                longhand: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            },
        },
        allowInput: true,
    });
});

// Image preview
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('preview');
            const container = document.getElementById('previewContainer');
            preview.src = e.target.result;
            if (container) {
                container.style.display = 'block';
            }
        }
        reader.readAsDataURL(file);
    }
}
</script>
