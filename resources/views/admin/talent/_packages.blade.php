<div class="form-section">
    <h3 class="section-title">
        <i class="fas fa-box"></i> Packages
        <button type="button" class="btn-add-package" onclick="addPackage()">
            <i class="fas fa-plus"></i> Add Package
        </button>
    </h3>

    <div id="packages-container">
        @if(count($packages) > 0)
            @foreach($packages as $index => $package)
                <div class="package-card" data-package-index="{{ $index }}">
                    <div class="package-header">
                        <h4>Package #{{ $index + 1 }}</h4>
                        <button type="button" class="btn-remove-package" onclick="removePackage(this)">
                            <i class="fas fa-trash"></i> Remove
                        </button>
                    </div>

                    <input type="hidden" name="packages[{{ $index }}][id]" value="{{ $package->id }}">

                    <div class="form-grid">
                        <div class="form-group">
                            <label>Nama Package <span class="required">*</span></label>
                            <input 
                                type="text" 
                                name="packages[{{ $index }}][name]" 
                                class="form-control" 
                                value="{{ old('packages.'.$index.'.name', $package->name) }}" 
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label>Harga <span class="required">*</span></label>
                            <input 
                                type="text" 
                                name="packages[{{ $index }}][price]" 
                                class="form-control rupiah-input" 
                                value="{{ old('packages.'.$index.'.price', $package->price) }}" 
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label>Durasi (Jam) <span class="required">*</span></label>
                            <input 
                                type="number" 
                                name="packages[{{ $index }}][duration_hours]" 
                                class="form-control" 
                                value="{{ old('packages.'.$index.'.duration_hours', $package->duration_hours) }}" 
                                min="1"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label>Status <span class="required">*</span></label>
                            <select name="packages[{{ $index }}][status]" class="form-control" required>
                                <option value="active" {{ old('packages.'.$index.'.status', $package->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('packages.'.$index.'.status', $package->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div class="form-group full-width">
                            <label>Deskripsi <span class="required">*</span></label>
                            <textarea 
                                name="packages[{{ $index }}][description]" 
                                class="form-control" 
                                rows="3" 
                                required
                            >{{ old('packages.'.$index.'.description', $package->description) }}</textarea>
                        </div>

                        <div class="form-group full-width">
                            <label>Yang Termasuk (Includes)</label>
                            <div class="includes-container" data-package-index="{{ $index }}">
                                @if($package->includes && count($package->includes) > 0)
                                    @foreach($package->includes as $includeIndex => $include)
                                        <div class="include-item">
                                            <input 
                                                type="text" 
                                                name="packages[{{ $index }}][includes][]" 
                                                class="form-control" 
                                                value="{{ $include }}"
                                                placeholder="Contoh: Sound system, Lighting, MC"
                                            >
                                            <button type="button" class="btn-remove-include" onclick="removeInclude(this)">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="include-item">
                                        <input 
                                            type="text" 
                                            name="packages[{{ $index }}][includes][]" 
                                            class="form-control" 
                                            placeholder="Contoh: Sound system, Lighting, MC"
                                        >
                                        <button type="button" class="btn-remove-include" onclick="removeInclude(this)">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <button type="button" class="btn-add-include" onclick="addInclude({{ $index }})">
                                <i class="fas fa-plus"></i> Add Item
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <!-- Default empty package -->
            <div class="package-card" data-package-index="0">
                <div class="package-header">
                    <h4>Package #1</h4>
                    <button type="button" class="btn-remove-package" onclick="removePackage(this)">
                        <i class="fas fa-trash"></i> Remove
                    </button>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Nama Package <span class="required">*</span></label>
                        <input 
                            type="text" 
                            name="packages[0][name]" 
                            class="form-control" 
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label>Harga <span class="required">*</span></label>
                        <input 
                            type="text" 
                            name="packages[0][price]" 
                            class="form-control rupiah-input" 
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label>Durasi (Jam) <span class="required">*</span></label>
                        <input 
                            type="number" 
                            name="packages[0][duration_hours]" 
                            class="form-control" 
                            min="1"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label>Status <span class="required">*</span></label>
                        <select name="packages[0][status]" class="form-control" required>
                            <option value="active" selected>Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="form-group full-width">
                        <label>Deskripsi <span class="required">*</span></label>
                        <textarea 
                            name="packages[0][description]" 
                            class="form-control" 
                            rows="3" 
                            required
                        ></textarea>
                    </div>

                    <div class="form-group full-width">
                        <label>Yang Termasuk (Includes)</label>
                        <div class="includes-container" data-package-index="0">
                            <div class="include-item">
                                <input 
                                    type="text" 
                                    name="packages[0][includes][]" 
                                    class="form-control" 
                                    placeholder="Contoh: Sound system, Lighting, MC"
                                >
                                <button type="button" class="btn-remove-include" onclick="removeInclude(this)">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <button type="button" class="btn-add-include" onclick="addInclude(0)">
                            <i class="fas fa-plus"></i> Add Item
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
let packageIndex = {{ count($packages) > 0 ? count($packages) : 1 }};

function addPackage() {
    const container = document.getElementById('packages-container');
    const packageCard = document.createElement('div');
    packageCard.className = 'package-card';
    packageCard.setAttribute('data-package-index', packageIndex);
    
    packageCard.innerHTML = `
        <div class="package-header">
            <h4>Package #${packageIndex + 1}</h4>
            <button type="button" class="btn-remove-package" onclick="removePackage(this)">
                <i class="fas fa-trash"></i> Remove
            </button>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label>Nama Package <span class="required">*</span></label>
                <input 
                    type="text" 
                    name="packages[${packageIndex}][name]" 
                    class="form-control" 
                    required
                >
            </div>

            <div class="form-group">
                <label>Harga <span class="required">*</span></label>
                <input 
                    type="text" 
                    name="packages[${packageIndex}][price]" 
                    class="form-control rupiah-input" 
                    required
                >
            </div>

            <div class="form-group">
                <label>Durasi (Jam) <span class="required">*</span></label>
                <input 
                    type="number" 
                    name="packages[${packageIndex}][duration_hours]" 
                    class="form-control" 
                    min="1"
                    required
                >
            </div>

            <div class="form-group">
                <label>Status <span class="required">*</span></label>
                <select name="packages[${packageIndex}][status]" class="form-control" required>
                    <option value="active" selected>Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <div class="form-group full-width">
                <label>Deskripsi <span class="required">*</span></label>
                <textarea 
                    name="packages[${packageIndex}][description]" 
                    class="form-control" 
                    rows="3" 
                    required
                ></textarea>
            </div>

            <div class="form-group full-width">
                <label>Yang Termasuk (Includes)</label>
                <div class="includes-container" data-package-index="${packageIndex}">
                    <div class="include-item">
                        <input 
                            type="text" 
                            name="packages[${packageIndex}][includes][]" 
                            class="form-control" 
                            placeholder="Contoh: Sound system, Lighting, MC"
                        >
                        <button type="button" class="btn-remove-include" onclick="removeInclude(this)">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <button type="button" class="btn-add-include" onclick="addInclude(${packageIndex})">
                    <i class="fas fa-plus"></i> Add Item
                </button>
            </div>
        </div>
    `;
    
    container.appendChild(packageCard);
    
    // Initialize rupiah formatting for new inputs
    const rupiahInput = packageCard.querySelector('.rupiah-input');
    rupiahInput.addEventListener('keyup', function(e) {
        rupiahInput.value = formatRupiah(rupiahInput.value);
    });
    
    packageIndex++;
    updatePackageNumbers();
}

function removePackage(button) {
    const packageCard = button.closest('.package-card');
    const container = document.getElementById('packages-container');
    
    // Don't allow removing if it's the last package
    if (container.children.length <= 1) {
        alert('Minimal harus ada 1 package!');
        return;
    }
    
    packageCard.remove();
    updatePackageNumbers();
}

function addInclude(packageIdx) {
    const container = document.querySelector(`.includes-container[data-package-index="${packageIdx}"]`);
    const includeItem = document.createElement('div');
    includeItem.className = 'include-item';
    
    includeItem.innerHTML = `
        <input 
            type="text" 
            name="packages[${packageIdx}][includes][]" 
            class="form-control" 
            placeholder="Contoh: Sound system, Lighting, MC"
        >
        <button type="button" class="btn-remove-include" onclick="removeInclude(this)">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    container.appendChild(includeItem);
}

function removeInclude(button) {
    const includeItem = button.closest('.include-item');
    const container = includeItem.closest('.includes-container');
    
    // Don't allow removing if it's the last include item
    if (container.children.length <= 1) {
        // Just clear the input instead
        includeItem.querySelector('input').value = '';
        return;
    }
    
    includeItem.remove();
}

function updatePackageNumbers() {
    const packages = document.querySelectorAll('.package-card');
    packages.forEach((pkg, index) => {
        pkg.querySelector('.package-header h4').textContent = `Package #${index + 1}`;
    });
}

function formatRupiah(angka) {
    let number_string = angka.replace(/[^,\d]/g, '').toString();
    let split = number_string.split(',');
    let sisa = split[0].length % 3;
    let rupiah = split[0].substr(0, sisa);
    let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        let separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return rupiah;
}
</script>

<style>
.package-card {
    background: #f8f9fa;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
}

.package-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #dee2e6;
}

.package-header h4 {
    margin: 0;
    color: #495057;
    font-size: 18px;
    font-weight: 600;
}

.btn-add-package {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    transition: transform 0.2s;
    margin-left: auto;
}

.btn-add-package:hover {
    transform: translateY(-2px);
}

.btn-remove-package {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 13px;
    font-weight: 600;
    transition: transform 0.2s;
}

.btn-remove-package:hover {
    transform: translateY(-2px);
}

.includes-container {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 10px;
}

.include-item {
    display: flex;
    gap: 10px;
    align-items: center;
}

.include-item input {
    flex: 1;
}

.btn-remove-include {
    background: #dc3545;
    color: white;
    border: none;
    padding: 8px 12px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    transition: background 0.2s;
}

.btn-remove-include:hover {
    background: #c82333;
}

.btn-add-include {
    background: #28a745;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 13px;
    font-weight: 600;
    transition: background 0.2s;
}

.btn-add-include:hover {
    background: #218838;
}

.section-title {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
</style>
