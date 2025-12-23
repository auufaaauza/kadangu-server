<!-- Ticket Categories Section -->
<div class="form-group full-width" style="margin-top: 32px; padding-top: 32px; border-top: 2px solid rgba(139, 92, 246, 0.2);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <label class="form-label" style="margin: 0;">
                Kategori Tiket <span class="required">*</span>
            </label>
            <span class="form-hint">Tambahkan berbagai kategori tiket dengan harga berbeda (VIP, Regular, Student, dll)</span>
        </div>
        <button type="button" onclick="addTicketCategory()" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i>
            Tambah Kategori
        </button>
    </div>
    
    <div id="ticketCategoriesContainer">
        @if(isset($pertunjukan) && $pertunjukan->ticketCategories->count() > 0)
            @foreach($pertunjukan->ticketCategories as $index => $category)
            <div class="ticket-category-item" data-index="{{ $index }}">
                <div style="background: rgba(15, 23, 42, 0.5); border: 1px solid rgba(139, 92, 246, 0.2); border-radius: 12px; padding: 20px; margin-bottom: 16px;">
                    <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 16px;">
                        <h4 style="color: #8B5CF6; margin: 0;">Kategori #<span class="category-number">{{ $index + 1 }}</span></h4>
                        <button type="button" onclick="removeTicketCategory(this)" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i>
                            Hapus
                        </button>
                    </div>
                    
                    <input type="hidden" name="ticket_categories[{{ $index }}][id]" value="{{ $category->id }}">
                    
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
                        <div class="form-group">
                            <label class="form-label">Nama Kategori <span class="required">*</span></label>
                            <input type="text" name="ticket_categories[{{ $index }}][nama]" class="form-input" value="{{ $category->nama }}" placeholder="VIP, Regular, Student" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Harga <span class="required">*</span></label>
                            <div class="currency-wrapper">
                                <span class="currency-prefix">Rp</span>
                                <input type="text" class="form-input currency-input category-price-display" value="{{ number_format($category->harga, 0, ',', '.') }}" placeholder="0" required oninput="formatCategoryPrice(this, {{ $index }})">
                                <input type="hidden" name="ticket_categories[{{ $index }}][harga]" class="category-price-value" value="{{ $category->harga }}">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Kuota <span class="required">*</span></label>
                            <input type="number" name="ticket_categories[{{ $index }}][kuota]" class="form-input" value="{{ $category->kuota }}" placeholder="0" min="1" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Deskripsi</label>
                            <input type="text" name="ticket_categories[{{ $index }}][deskripsi]" class="form-input" value="{{ $category->deskripsi }}" placeholder="Akses VIP, Meet & Greet">
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <!-- Default category -->
            <div class="ticket-category-item" data-index="0">
                <div style="background: rgba(15, 23, 42, 0.5); border: 1px solid rgba(139, 92, 246, 0.2); border-radius: 12px; padding: 20px; margin-bottom: 16px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                        <h4 style="color: #8B5CF6; margin: 0;">Kategori #<span class="category-number">1</span></h4>
                        <button type="button" onclick="removeTicketCategory(this)" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i>
                            Hapus
                        </button>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
                        <div class="form-group">
                            <label class="form-label">Nama Kategori <span class="required">*</span></label>
                            <input type="text" name="ticket_categories[0][nama]" class="form-input" value="Regular" placeholder="VIP, Regular, Student" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Harga <span class="required">*</span></label>
                            <div class="currency-wrapper">
                                <span class="currency-prefix">Rp</span>
                                <input type="text" class="form-input currency-input category-price-display" value="" placeholder="0" required oninput="formatCategoryPrice(this, 0)">
                                <input type="hidden" name="ticket_categories[0][harga]" class="category-price-value" value="">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Kuota <span class="required">*</span></label>
                            <input type="number" name="ticket_categories[0][kuota]" class="form-input" value="" placeholder="0" min="1" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Deskripsi</label>
                            <input type="text" name="ticket_categories[0][deskripsi]" class="form-input" value="" placeholder="Akses VIP, Meet & Greet">
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
let categoryIndex = {{ isset($pertunjukan) && $pertunjukan->ticketCategories->count() > 0 ? $pertunjukan->ticketCategories->count() : 1 }};

function addTicketCategory() {
    const container = document.getElementById('ticketCategoriesContainer');
    const newCategory = `
        <div class="ticket-category-item" data-index="${categoryIndex}">
            <div style="background: rgba(15, 23, 42, 0.5); border: 1px solid rgba(139, 92, 246, 0.2); border-radius: 12px; padding: 20px; margin-bottom: 16px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <h4 style="color: #8B5CF6; margin: 0;">Kategori #<span class="category-number">${categoryIndex + 1}</span></h4>
                    <button type="button" onclick="removeTicketCategory(this)" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i>
                        Hapus
                    </button>
                </div>
                
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
                    <div class="form-group">
                        <label class="form-label">Nama Kategori <span class="required">*</span></label>
                        <input type="text" name="ticket_categories[${categoryIndex}][nama]" class="form-input" placeholder="VIP, Regular, Student" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Harga <span class="required">*</span></label>
                        <div class="currency-wrapper">
                            <span class="currency-prefix">Rp</span>
                            <input type="text" class="form-input currency-input category-price-display" placeholder="0" required oninput="formatCategoryPrice(this, ${categoryIndex})">
                            <input type="hidden" name="ticket_categories[${categoryIndex}][harga]" class="category-price-value" value="">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Kuota <span class="required">*</span></label>
                        <input type="number" name="ticket_categories[${categoryIndex}][kuota]" class="form-input" placeholder="0" min="1" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Deskripsi</label>
                        <input type="text" name="ticket_categories[${categoryIndex}][deskripsi]" class="form-input" placeholder="Akses VIP, Meet & Greet">
                    </div>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', newCategory);
    categoryIndex++;
    updateCategoryNumbers();
}

function removeTicketCategory(button) {
    const item = button.closest('.ticket-category-item');
    const container = document.getElementById('ticketCategoriesContainer');
    
    // Don't allow removing if only one category left
    if (container.querySelectorAll('.ticket-category-item').length <= 1) {
        alert('Minimal harus ada 1 kategori tiket!');
        return;
    }
    
    item.remove();
    updateCategoryNumbers();
}

function updateCategoryNumbers() {
    const items = document.querySelectorAll('.ticket-category-item');
    items.forEach((item, index) => {
        item.querySelector('.category-number').textContent = index + 1;
    });
}

function formatCategoryPrice(input, index) {
    let value = input.value.replace(/\D/g, '');
    
    if (value) {
        value = parseInt(value).toLocaleString('id-ID');
        input.value = value;
        
        const hiddenInput = input.parentElement.querySelector('.category-price-value');
        if (hiddenInput) {
            hiddenInput.value = value.replace(/\./g, '');
        }
    } else {
        input.value = '';
        const hiddenInput = input.parentElement.querySelector('.category-price-value');
        if (hiddenInput) {
            hiddenInput.value = '';
        }
    }
}
</script>
