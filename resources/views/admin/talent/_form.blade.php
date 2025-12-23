<div class="form-grid">
    <!-- Name -->
    <div class="form-group full-width">
        <label class="form-label">
            Nama Talent <span class="required">*</span>
        </label>
        <input 
            type="text" 
            name="name" 
            class="form-input" 
            value="{{ old('name', $talent->name ?? '') }}"
            placeholder="Masukkan nama talent"
            required
        >
        @error('name')
        <span class="form-error">{{ $message }}</span>
        @enderror
    </div>
    
    <!-- Kategori -->
    <div class="form-group">
        <label class="form-label">
            Kategori <span class="required">*</span>
        </label>
        <select name="seniman_id" class="form-input" required>
            <option value="">Pilih Kategori</option>
            @foreach($senimans as $seniman)
                <option value="{{ $seniman->id }}" {{ old('seniman_id', $talent->seniman_id ?? '') == $seniman->id ? 'selected' : '' }}>
                    {{ $seniman->nama }}
                </option>
            @endforeach
        </select>
        @error('seniman_id')
        <span class="form-error">{{ $message }}</span>
        @enderror
    </div>
    
    <!-- Genre -->
    <div class="form-group">
        <label class="form-label">
            Genre <span class="required">*</span>
        </label>
        <input 
            type="text" 
            name="genre" 
            class="form-input" 
            value="{{ old('genre', $talent->genre ?? '') }}"
            placeholder="Contoh: Pop, Rock, Jazz, Kontemporer"
            required
        >
        <span class="form-hint">Genre dapat disesuaikan dengan kategori talent</span>
        @error('genre')
        <span class="form-error">{{ $message }}</span>
        @enderror
    </div>
    
    <!-- Bio -->
    <div class="form-group full-width">
        <label class="form-label">
            Bio / Deskripsi <span class="required">*</span>
        </label>
        <textarea 
            name="bio" 
            class="form-textarea" 
            placeholder="Masukkan bio atau deskripsi talent"
            required
        >{{ old('bio', $talent->bio ?? '') }}</textarea>
        @error('bio')
        <span class="form-error">{{ $message }}</span>
        @enderror
    </div>
    
    <!-- Base Price -->
    <div class="form-group">
        <label class="form-label">
            Harga Mulai Dari <span class="required">*</span>
        </label>
        <input 
            type="text" 
            name="base_price" 
            class="form-input rupiah-input" 
            value="{{ old('base_price', isset($talent) ? number_format($talent->base_price, 0, ',', '.') : '') }}"
            placeholder="0"
            required
        >
        <span class="form-hint">Harga dasar untuk talent ini</span>
        @error('base_price')
        <span class="form-error">{{ $message }}</span>
        @enderror
    </div>
    
    <!-- Service Description -->
    <div class="form-group">
        <label class="form-label">
            Deskripsi Layanan
        </label>
        <textarea 
            name="service_description" 
            class="form-textarea" 
            rows="3"
            placeholder="Deskripsi singkat tentang layanan yang ditawarkan"
        >{{ old('service_description', $talent->service_description ?? '') }}</textarea>
        @error('service_description')
        <span class="form-error">{{ $message }}</span>
        @enderror
    </div>
    
    <!-- Photo -->
    <div class="form-group">
        <label class="form-label">
            Foto Talent
        </label>
        <input 
            type="file" 
            name="photo" 
            class="form-input" 
            accept="image/*"
            onchange="previewPhoto(event)"
        >
        <span class="form-hint">Format: JPG, PNG. Max: 2MB</span>
        @if(isset($talent) && $talent->photo)
            <div class="image-preview" style="margin-top: 12px;">
                <img src="{{ asset('storage/' . $talent->photo) }}" alt="Current Photo" id="photoPreview" style="max-width: 200px; border-radius: 8px;">
            </div>
        @else
            <div class="image-preview" id="photoPreviewContainer" style="margin-top: 12px; display: none;">
                <img id="photoPreview" style="max-width: 200px; border-radius: 8px;">
            </div>
        @endif
        @error('photo')
        <span class="form-error">{{ $message }}</span>
        @enderror
    </div>
    
    <!-- Portfolio -->
    <div class="form-group">
        <label class="form-label">
            Portfolio (Multiple Images)
        </label>
        <input 
            type="file" 
            name="portfolio[]" 
            class="form-input" 
            accept="image/*"
            multiple
            onchange="previewPortfolio(event)"
        >
        <span class="form-hint">Pilih beberapa gambar untuk portfolio</span>
        @if(isset($talent) && $talent->portfolio)
            <div class="portfolio-preview" style="margin-top: 12px; display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 8px;">
                @foreach($talent->portfolio as $image)
                    <img src="{{ asset('storage/' . $image) }}" alt="Portfolio" style="width: 100%; aspect-ratio: 1; object-fit: cover; border-radius: 8px;">
                @endforeach
            </div>
        @endif
        <div id="portfolioPreviewContainer" style="margin-top: 12px; display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 8px;"></div>
        @error('portfolio')
        <span class="form-error">{{ $message }}</span>
        @enderror
    </div>
    
    <!-- Availability Status -->
    <div class="form-group">
        <label class="form-label">
            Status Ketersediaan <span class="required">*</span>
        </label>
        <select name="availability_status" class="form-input" required>
            <option value="available" {{ old('availability_status', $talent->availability_status ?? 'available') == 'available' ? 'selected' : '' }}>Available</option>
            <option value="booked" {{ old('availability_status', $talent->availability_status ?? '') == 'booked' ? 'selected' : '' }}>Booked</option>
            <option value="unavailable" {{ old('availability_status', $talent->availability_status ?? '') == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
        </select>
        @error('availability_status')
        <span class="form-error">{{ $message }}</span>
        @enderror
    </div>
    
    <!-- Status -->
    <div class="form-group">
        <label class="form-label">
            Status <span class="required">*</span>
        </label>
        <select name="status" class="form-input" required>
            <option value="active" {{ old('status', $talent->status ?? 'active') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status', $talent->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('status')
        <span class="form-error">{{ $message }}</span>
        @enderror
    </div>
</div>

<!-- Packages Section -->
<div class="form-section" style="margin-top: 32px;">
    <h3 class="section-title">
        <i class="fas fa-box"></i> Packages
    </h3>
    <p class="section-subtitle">Tambahkan paket-paket yang ditawarkan oleh talent ini</p>
    
    <div id="packages-container">
        @if(isset($talent) && $talent->packages->count() > 0)
            @foreach($talent->packages as $index => $package)
                <div class="package-item" data-index="{{ $index }}">
                    <div class="package-header">
                        <h4>Package #<span class="package-number">{{ $index + 1 }}</span></h4>
                        <button type="button" class="btn btn-danger btn-sm remove-package" onclick="removePackage(this)">
                            <i class="fas fa-trash"></i> Remove
                        </button>
                    </div>
                    
                    <div class="form-grid">
                        <input type="hidden" name="packages[{{ $index }}][id]" value="{{ $package->id }}">
                        
                        <div class="form-group">
                            <label class="form-label">Nama Package <span class="required">*</span></label>
                            <input type="text" name="packages[{{ $index }}][name]" class="form-input" value="{{ $package->name }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Harga <span class="required">*</span></label>
                            <input type="text" name="packages[{{ $index }}][price]" class="form-input rupiah-input" value="{{ number_format($package->price, 0, ',', '.') }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Durasi (Jam) <span class="required">*</span></label>
                            <input type="number" name="packages[{{ $index }}][duration_hours]" class="form-input" value="{{ $package->duration_hours }}" min="1" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Status <span class="required">*</span></label>
                            <select name="packages[{{ $index }}][status]" class="form-input" required>
                                <option value="active" {{ $package->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $package->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        
                        <div class="form-group full-width">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="packages[{{ $index }}][description]" class="form-textarea" rows="2">{{ $package->description }}</textarea>
                        </div>
                        
                        <div class="form-group full-width">
                            <label class="form-label">Yang Termasuk (satu per baris)</label>
                            <textarea name="packages[{{ $index }}][includes]" class="form-textarea" rows="3" placeholder="Contoh:&#10;Sound system&#10;Lighting&#10;2 jam perform">{{ is_array($package->includes) ? implode("\n", $package->includes) : '' }}</textarea>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="package-item" data-index="0">
                <div class="package-header">
                    <h4>Package #<span class="package-number">1</span></h4>
                    <button type="button" class="btn btn-danger btn-sm remove-package" onclick="removePackage(this)">
                        <i class="fas fa-trash"></i> Remove
                    </button>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Nama Package <span class="required">*</span></label>
                        <input type="text" name="packages[0][name]" class="form-input" placeholder="Contoh: Basic Package" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Harga <span class="required">*</span></label>
                        <input type="text" name="packages[0][price]" class="form-input rupiah-input" placeholder="0" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Durasi (Jam) <span class="required">*</span></label>
                        <input type="number" name="packages[0][duration_hours]" class="form-input" value="1" min="1" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Status <span class="required">*</span></label>
                        <select name="packages[0][status]" class="form-input" required>
                            <option value="active" selected>Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    
                    <div class="form-group full-width">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="packages[0][description]" class="form-textarea" rows="2" placeholder="Deskripsi package"></textarea>
                    </div>
                    
                    <div class="form-group full-width">
                        <label class="form-label">Yang Termasuk (satu per baris)</label>
                        <textarea name="packages[0][includes]" class="form-textarea" rows="3" placeholder="Contoh:&#10;Sound system&#10;Lighting&#10;2 jam perform"></textarea>
                    </div>
                </div>
            </div>
        @endif
    </div>
    
    <button type="button" class="btn btn-secondary" onclick="addPackage()" style="margin-top: 16px;">
        <i class="fas fa-plus"></i> Tambah Package
    </button>
</div>

<script>
let packageIndex = {{ isset($talent) && $talent->packages->count() > 0 ? $talent->packages->count() : 1 }};

function addPackage() {
    const container = document.getElementById('packages-container');
    const packageHtml = `
        <div class="package-item" data-index="${packageIndex}">
            <div class="package-header">
                <h4>Package #<span class="package-number">${packageIndex + 1}</span></h4>
                <button type="button" class="btn btn-danger btn-sm remove-package" onclick="removePackage(this)">
                    <i class="fas fa-trash"></i> Remove
                </button>
            </div>
            
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Nama Package <span class="required">*</span></label>
                    <input type="text" name="packages[${packageIndex}][name]" class="form-input" placeholder="Contoh: Basic Package" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Harga <span class="required">*</span></label>
                    <input type="text" name="packages[${packageIndex}][price]" class="form-input rupiah-input" placeholder="0" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Durasi (Jam) <span class="required">*</span></label>
                    <input type="number" name="packages[${packageIndex}][duration_hours]" class="form-input" value="1" min="1" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Status <span class="required">*</span></label>
                    <select name="packages[${packageIndex}][status]" class="form-input" required>
                        <option value="active" selected>Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                
                <div class="form-group full-width">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="packages[${packageIndex}][description]" class="form-textarea" rows="2" placeholder="Deskripsi package"></textarea>
                </div>
                
                <div class="form-group full-width">
                    <label class="form-label">Yang Termasuk (satu per baris)</label>
                    <textarea name="packages[${packageIndex}][includes]" class="form-textarea" rows="3" placeholder="Contoh:&#10;Sound system&#10;Lighting&#10;2 jam perform"></textarea>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', packageHtml);
    packageIndex++;
    updatePackageNumbers();
    initRupiahInputs();
}

function removePackage(button) {
    if (document.querySelectorAll('.package-item').length > 1) {
        button.closest('.package-item').remove();
        updatePackageNumbers();
    } else {
        alert('Minimal harus ada 1 package!');
    }
}

function updatePackageNumbers() {
    document.querySelectorAll('.package-number').forEach((el, index) => {
        el.textContent = index + 1;
    });
}

function previewPhoto(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('photoPreview');
            const container = document.getElementById('photoPreviewContainer');
            preview.src = e.target.result;
            if (container) container.style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
}

function previewPortfolio(event) {
    const files = event.target.files;
    const container = document.getElementById('portfolioPreviewContainer');
    container.innerHTML = '';
    
    Array.from(files).forEach(file => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.style.width = '100%';
            img.style.aspectRatio = '1';
            img.style.objectFit = 'cover';
            img.style.borderRadius = '8px';
            container.appendChild(img);
        }
        reader.readAsDataURL(file);
    });
}

// Rupiah formatting
function formatRupiah(value) {
    const number = value.replace(/[^,\d]/g, '');
    return number.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function initRupiahInputs() {
    document.querySelectorAll('.rupiah-input').forEach(input => {
        input.addEventListener('keyup', function(e) {
            this.value = formatRupiah(this.value);
        });
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initRupiahInputs();
});
</script>

<style>
.package-item {
    background: rgba(30, 41, 59, 0.3);
    border: 1px solid rgba(139, 92, 246, 0.2);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
}

.package-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 1px solid rgba(139, 92, 246, 0.2);
}

.package-header h4 {
    color: white;
    font-size: 16px;
    font-weight: 600;
    margin: 0;
}

.section-title {
    color: white;
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.section-subtitle {
    color: #94A3B8;
    font-size: 14px;
    margin-bottom: 20px;
}
</style>
