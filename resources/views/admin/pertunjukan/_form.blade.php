<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<div class="form-grid">
    <!-- Judul -->
    <div class="form-group full-width">
        <label class="form-label">
            Judul Pertunjukan <span class="required">*</span>
        </label>
        <input 
            type="text" 
            name="judul" 
            class="form-input" 
            value="{{ old('judul', $pertunjukan->judul ?? '') }}"
            placeholder="Masukkan judul pertunjukan"
            required
        >
        @error('judul')
        <span class="form-error">{{ $message }}</span>
        @enderror
    </div>
    
    <!-- Deskripsi -->
    <div class="form-group full-width">
        <label class="form-label">
            Deskripsi <span class="required">*</span>
        </label>
        <textarea 
            name="deskripsi" 
            class="form-textarea" 
            placeholder="Masukkan deskripsi pertunjukan&#10;&#10;Gunakan:&#10;- Enter untuk baris baru&#10;- Numbering: 1. Item pertama&#10;- Bullet: - Item dengan bullet"
            required
        >{{ old('deskripsi', $pertunjukan->deskripsi ?? '') }}</textarea>
        <span class="form-hint">Deskripsi mendukung line breaks dan formatting. Tekan Enter untuk baris baru.</span>
        @error('deskripsi')
        <span class="form-error">{{ $message }}</span>
        @enderror
    </div>
    
    <!-- Seniman / Kategori -->
    <div class="form-group">
        <label class="form-label">
            Seniman / Kategori <span class="required">*</span>
        </label>
        <input 
            type="text" 
            name="seniman_nama" 
            class="form-input" 
            value="{{ old('seniman_nama', request('kategori', isset($pertunjukan) ? $pertunjukan->seniman->nama : '')) }}"
            placeholder="Contoh: Wayang Kulit, Tari Saman, Teater Koma"
            required
        >
        <span class="form-hint">Masukkan nama seniman atau kategori pertunjukan</span>
        @error('seniman_nama')
        <span class="form-error">{{ $message }}</span>
        @enderror
    </div>
    
    <!-- Tanggal Pertunjukan -->
    <div class="form-group">
        <label class="form-label">
            Tanggal Pertunjukan <span class="required">*</span>
        </label>
        <input 
            type="text" 
            name="tanggal_pertunjukan" 
            id="tanggal_pertunjukan"
            class="form-input" 
            value="{{ old('tanggal_pertunjukan', isset($pertunjukan) ? $pertunjukan->tanggal_pertunjukan->format('Y-m-d H:i') : '') }}"
            placeholder="Pilih tanggal dan waktu"
            required
            readonly
        >
        <span class="form-hint">Klik untuk membuka kalender popup</span>
        @error('tanggal_pertunjukan')
        <span class="form-error">{{ $message }}</span>
        @enderror
    </div>
    
    <!-- Lokasi -->
    <div class="form-group full-width">
        <label class="form-label">
            Lokasi <span class="required">*</span>
        </label>
        <input 
            type="text" 
            name="lokasi" 
            class="form-input" 
            value="{{ old('lokasi', $pertunjukan->lokasi ?? '') }}"
            placeholder="Masukkan lokasi pertunjukan"
            required
        >
        @error('lokasi')
        <span class="form-error">{{ $message }}</span>
        @enderror
    </div>
    
    <!-- Gambar -->
    <div class="form-group full-width">
        <label class="form-label">
            Gambar Pertunjukan {{ isset($pertunjukan) ? '' : '*' }}
        </label>
        <input 
            type="file" 
            name="gambar" 
            class="form-file" 
            accept="image/*"
            onchange="previewImage(event)"
            {{ isset($pertunjukan) ? '' : 'required' }}
        >
        <span class="form-hint">Format: JPG, PNG, JPEG. Maksimal 2MB</span>
        @error('gambar')
        <span class="form-error">{{ $message }}</span>
        @enderror
        
        @if(isset($pertunjukan) && $pertunjukan->gambar)
        <div class="image-preview">
            <img src="{{ asset('storage/' . $pertunjukan->gambar) }}" alt="Current Image" id="preview">
        </div>
        @else
        <div class="image-preview" id="previewContainer" style="display: none;">
            <img src="" alt="Preview" id="preview">
        </div>
        @endif
    </div>
</div>

@include('admin.pertunjukan._ticket_categories')

<!-- Biaya & Pajak Section -->
<div class="form-grid" style="margin-top: 32px; padding-top: 32px; border-top: 2px solid rgba(139, 92, 246, 0.2);">
    <!-- Biaya Layanan -->
    <div class="form-group">
        <label class="form-label">
            Biaya Layanan (%) <span class="required">*</span>
        </label>
        <input 
            type="number" 
            name="biaya_layanan" 
            class="form-input" 
            value="{{ old('biaya_layanan', $pertunjukan->biaya_layanan ?? '5') }}"
            placeholder="5"
            min="0"
            max="100"
            step="0.1"
            required
        >
        <span class="form-hint">Persentase biaya layanan (default: 5%)</span>
        @error('biaya_layanan')
        <span class="form-error">{{ $message }}</span>
        @enderror
    </div>
    
    <!-- PPN -->
    <div class="form-group">
        <label class="form-label">
            PPN (%) <span class="required">*</span>
        </label>
        <input 
            type="number" 
            name="ppn" 
            class="form-input" 
            value="{{ old('ppn', $pertunjukan->ppn ?? '11') }}"
            placeholder="11"
            min="0"
            max="100"
            step="0.1"
            required
        >
        <span class="form-hint">Persentase PPN (default: 11%)</span>
        @error('ppn')
        <span class="form-error">{{ $message }}</span>
        @enderror
    </div>
    
    <!-- Status -->
    <div class="form-group">
        <label class="form-label">
            Status <span class="required">*</span>
        </label>
        <select name="status" class="form-select" required>
            <option value="active" {{ old('status', $pertunjukan->status ?? 'active') == 'active' ? 'selected' : '' }}>
                Active
            </option>
            <option value="inactive" {{ old('status', $pertunjukan->status ?? '') == 'inactive' ? 'selected' : '' }}>
                Inactive
            </option>
            <option value="passed" {{ old('status', $pertunjukan->status ?? '') == 'passed' ? 'selected' : '' }}>
                Passed
            </option>
        </select>
        <span class="form-hint">Active: Tampil & bisa dibeli | Inactive: Tidak tampil | Passed: Event sudah lewat (tampil tapi tidak bisa dibeli)</span>
        @error('status')
        <span class="form-error">{{ $message }}</span>
        @enderror
    </div>
</div>

<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
// Initialize Flatpickr
document.addEventListener('DOMContentLoaded', function() {
    flatpickr("#tanggal_pertunjukan", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        time_24hr: true,
        minDate: "today",
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
    });
});

// Format currency input
function formatCurrency(input) {
    let value = input.value.replace(/\D/g, '');
    
    if (value) {
        value = parseInt(value).toLocaleString('id-ID');
        input.value = value;
        document.getElementById('harga').value = value.replace(/\./g, '');
    } else {
        input.value = '';
        document.getElementById('harga').value = '';
    }
}

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
