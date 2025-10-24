@extends('layouts.modern')

@section('title', isset($product) ? 'Edit Produk' : 'Tambah Produk')

@section('content')
<div class="content-header">
    <h1>{{ isset($product) ? 'Edit Produk' : 'Tambah Produk' }}</h1>
    <p>{{ isset($product) ? 'Perbarui informasi produk' : 'Tambahkan produk baru ke sistem' }}</p>
</div>

<div class="modern-card">
    <form action="{{ isset($product) ? route('admin.products.update', $product->id) : route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($product))
            @method('PUT')
        @endif

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <!-- Left Column -->
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                <div>
                    <label class="form-label">Nama Produk *</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name', $product->name ?? '') }}" required>
                    @error('name')
                    <span style="color: #dc2626; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div>
                        <label class="form-label">SKU *</label>
                        <input type="text" name="sku" class="form-input" value="{{ old('sku', $product->sku ?? '') }}" required>
                        @error('sku')
                        <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="form-label">Barcode</label>
                        <input type="text" name="barcode" class="form-input" value="{{ old('barcode', $product->barcode ?? '') }}">
                    </div>
                </div>

                <div>
                    <label class="form-label">Kategori *</label>
                    <select name="category_id" class="form-input" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('category_id')
                    <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-input" rows="4" style="resize: vertical;">{{ old('description', $product->description ?? '') }}</textarea>
                </div>

                <div>
                    <label class="form-label">Gambar Produk</label>
                    <input type="file" name="image" class="form-input" accept="image/*" onchange="previewImage(event)">
                    <span style="font-size: 0.875rem; color: #6b7280; margin-top: 4px; display: block;">Max: 2MB (JPG, PNG)</span>
                    
                    @if(isset($product) && $product->image)
                    <div style="margin-top: 1rem;">
                        <img id="imagePreview" src="{{ asset('storage/' . $product->image) }}" alt="Preview" style="max-width: 200px; border-radius: 8px; border: 2px solid #e5e7eb;">
                    </div>
                    @else
                    <div id="imagePreview" style="margin-top: 1rem; display: none;">
                        <img src="" alt="Preview" style="max-width: 200px; border-radius: 8px; border: 2px solid #e5e7eb;">
                    </div>
                    @endif
                </div>
            </div>

            <!-- Right Column -->
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div>
                        <label class="form-label">Harga Normal *</label>
                        <div style="position: relative;">
                            <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #6b7280;">Rp</span>
                            <input type="number" name="price" class="form-input" style="padding-left: 40px;" value="{{ old('price', $product->price ?? '') }}" required min="0">
                        </div>
                        @error('price')
                        <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="form-label">Harga Member</label>
                        <div style="position: relative;">
                            <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #6b7280;">Rp</span>
                            <input type="number" name="member_price" class="form-input" style="padding-left: 40px;" value="{{ old('member_price', $product->member_price ?? '') }}" min="0">
                        </div>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div>
                        <label class="form-label">Stok Awal *</label>
                        <input type="number" name="stock" class="form-input" value="{{ old('stock', $product->stock ?? '') }}" required min="0">
                        @error('stock')
                        <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="form-label">Minimal Stok *</label>
                        <input type="number" name="min_stock" class="form-input" value="{{ old('min_stock', $product->min_stock ?? 10) }}" required min="0">
                    </div>
                </div>

                <div>
                    <label class="form-label">Satuan *</label>
                    <input type="text" name="unit" class="form-input" value="{{ old('unit', $product->unit ?? 'pcs') }}" required placeholder="pcs, kg, liter, dll">
                </div>

                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 1.5rem; border-radius: 12px; color: white;">
                    <h3 style="margin: 0 0 1rem 0; font-size: 1.125rem;"><i class="fas fa-qrcode"></i> QR Code</h3>
                    <p style="margin: 0; opacity: 0.9; font-size: 0.9375rem;">
                        QR Code akan otomatis dibuat setelah produk disimpan. QR Code dapat digunakan untuk scanning cepat di POS.
                    </p>
                    @if(isset($product) && $product->qr_code)
                    <div style="margin-top: 1rem; text-align: center; background: white; padding: 1rem; border-radius: 8px;">
                        <img src="{{ asset('storage/' . $product->qr_code) }}" alt="QR Code" style="width: 150px; height: 150px;">
                        <div style="margin-top: 0.5rem;">
                            <a href="{{ route('admin.products.qr-download', $product->id) }}" class="btn-secondary" style="display: inline-block; margin-top: 0.5rem;">
                                <i class="fas fa-download"></i> Download QR
                            </a>
                        </div>
                    </div>
                    @endif
                </div>

                <div style="background: #fef3c7; padding: 1rem; border-radius: 8px; border-left: 4px solid #f59e0b;">
                    <strong style="color: #92400e; display: block; margin-bottom: 0.5rem;"><i class="fas fa-info-circle"></i> Tips:</strong>
                    <ul style="margin: 0; padding-left: 1.5rem; color: #92400e; font-size: 0.9375rem;">
                        <li>Gunakan SKU yang unik untuk setiap produk</li>
                        <li>Harga member bisa lebih murah dari harga normal</li>
                        <li>Atur minimal stok untuk notifikasi otomatis</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem; padding-top: 2rem; border-top: 2px solid #e5e7eb;">
            <a href="{{ route('admin.products.index') }}" class="btn-secondary">
                <i class="fas fa-times"></i> Batal
            </a>
            <button type="submit" class="btn-primary">
                <i class="fas fa-save"></i> {{ isset($product) ? 'Update Produk' : 'Simpan Produk' }}
            </button>
        </div>
    </form>
</div>

<script>
function previewImage(event) {
    const preview = document.getElementById('imagePreview');
    const file = event.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.style.display = 'block';
            preview.querySelector('img').src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
}

// Auto-generate SKU suggestion
document.querySelector('input[name="name"]')?.addEventListener('input', function(e) {
    const skuInput = document.querySelector('input[name="sku"]');
    if (!skuInput.value || skuInput.dataset.auto) {
        const sku = 'PRD-' + e.target.value.substring(0, 3).toUpperCase() + '-' + Date.now().toString().slice(-4);
        skuInput.value = sku;
        skuInput.dataset.auto = 'true';
    }
});

document.querySelector('input[name="sku"]')?.addEventListener('input', function() {
    delete this.dataset.auto;
});
</script>
@endsection
