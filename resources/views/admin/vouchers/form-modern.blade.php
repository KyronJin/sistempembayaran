@extends('layouts.modern')

@section('title', isset($voucher) ? 'Edit Voucher' : 'Tambah Voucher')

@section('content')
<div class="content-header">
    <div style="display: flex; align-items: center; gap: 1rem;">
        <a href="{{ route('admin.vouchers.index') }}" style="color: #6b7280; text-decoration: none; display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 10px; background: white; transition: all 0.3s;">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1>{{ isset($voucher) ? 'Edit Voucher' : 'Tambah Voucher Baru' }}</h1>
            <p>{{ isset($voucher) ? 'Perbarui informasi voucher' : 'Buat voucher baru untuk member' }}</p>
        </div>
    </div>
</div>

<form action="{{ isset($voucher) ? route('admin.vouchers.update', $voucher->id) : route('admin.vouchers.store') }}" method="POST">
    @csrf
    @if(isset($voucher))
        @method('PUT')
    @endif

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
        <!-- Main Info -->
        <div>
            <div class="modern-card" style="margin-bottom: 2rem;">
                <h3 style="margin: 0 0 1.5rem 0; color: #1F2937; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-info-circle" style="color: #FF6F00;"></i>
                    Informasi Dasar
                </h3>

                <div style="display: grid; gap: 1.5rem;">
                    <!-- Code -->
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #1F2937;">
                            Kode Voucher <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" 
                               name="code" 
                               value="{{ old('code', $voucher->code ?? '') }}" 
                               required
                               placeholder="Contoh: DISKON50K, GRATIS100"
                               style="width: 100%; padding: 0.75rem 1rem; border: 2px solid {{ $errors->has('code') ? '#ef4444' : '#e5e7eb' }}; border-radius: 12px; font-family: 'Courier New', monospace; text-transform: uppercase;">
                        @error('code')
                        <p style="color: #ef4444; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Name -->
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #1F2937;">
                            Nama Voucher <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               value="{{ old('name', $voucher->name ?? '') }}" 
                               required
                               placeholder="Contoh: Diskon 50 Ribu Member Baru"
                               style="width: 100%; padding: 0.75rem 1rem; border: 2px solid {{ $errors->has('name') ? '#ef4444' : '#e5e7eb' }}; border-radius: 12px;">
                        @error('name')
                        <p style="color: #ef4444; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #1F2937;">
                            Deskripsi
                        </label>
                        <textarea name="description" 
                                  rows="3"
                                  placeholder="Deskripsi lengkap voucher..."
                                  style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #e5e7eb; border-radius: 12px; resize: vertical;">{{ old('description', $voucher->description ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Discount Settings -->
            <div class="modern-card" style="margin-bottom: 2rem;">
                <h3 style="margin: 0 0 1.5rem 0; color: #1F2937; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-percent" style="color: #FF6F00;"></i>
                    Pengaturan Diskon
                </h3>

                <div style="display: grid; gap: 1.5rem;">
                    <!-- Discount Type -->
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #1F2937;">
                            Tipe Diskon <span style="color: #ef4444;">*</span>
                        </label>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <label style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; border: 2px solid #e5e7eb; border-radius: 12px; cursor: pointer; transition: all 0.3s;" class="discount-type-option">
                                <input type="radio" name="discount_type" value="percentage" {{ old('discount_type', $voucher->discount_type ?? 'percentage') === 'percentage' ? 'checked' : '' }} required style="width: 20px; height: 20px; accent-color: #FF6F00;">
                                <div>
                                    <div style="font-weight: 600; color: #1F2937;">Persentase</div>
                                    <div style="font-size: 0.85rem; color: #6b7280;">Diskon dalam persen (%)</div>
                                </div>
                            </label>
                            <label style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; border: 2px solid #e5e7eb; border-radius: 12px; cursor: pointer; transition: all 0.3s;" class="discount-type-option">
                                <input type="radio" name="discount_type" value="fixed" {{ old('discount_type', $voucher->discount_type ?? '') === 'fixed' ? 'checked' : '' }} required style="width: 20px; height: 20px; accent-color: #FF6F00;">
                                <div>
                                    <div style="font-weight: 600; color: #1F2937;">Fixed Amount</div>
                                    <div style="font-size: 0.85rem; color: #6b7280;">Diskon nominal rupiah</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <!-- Discount Value -->
                        <div>
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #1F2937;">
                                Nilai Diskon <span style="color: #ef4444;">*</span>
                            </label>
                            <input type="number" 
                                   name="discount_value" 
                                   value="{{ old('discount_value', $voucher->discount_value ?? '') }}" 
                                   required
                                   step="0.01"
                                   min="0"
                                   placeholder="Contoh: 10 atau 50000"
                                   style="width: 100%; padding: 0.75rem 1rem; border: 2px solid {{ $errors->has('discount_value') ? '#ef4444' : '#e5e7eb' }}; border-radius: 12px;">
                            @error('discount_value')
                            <p style="color: #ef4444; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Max Discount (for percentage) -->
                        <div id="maxDiscountField">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #1F2937;">
                                Maksimal Diskon (Opsional)
                            </label>
                            <input type="number" 
                                   name="max_discount" 
                                   value="{{ old('max_discount', $voucher->max_discount ?? '') }}" 
                                   step="0.01"
                                   min="0"
                                   placeholder="Maks potongan untuk %"
                                   style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #e5e7eb; border-radius: 12px;">
                            <p style="font-size: 0.85rem; color: #6b7280; margin-top: 0.5rem;">Untuk membatasi diskon persentase</p>
                        </div>
                    </div>

                    <!-- Min Transaction -->
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #1F2937;">
                            Minimum Transaksi
                        </label>
                        <input type="number" 
                               name="min_transaction" 
                               value="{{ old('min_transaction', $voucher->min_transaction ?? 0) }}" 
                               step="0.01"
                               min="0"
                               placeholder="0"
                               style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #e5e7eb; border-radius: 12px;">
                        <p style="font-size: 0.85rem; color: #6b7280; margin-top: 0.5rem;">Minimal belanja untuk pakai voucher</p>
                    </div>
                </div>
            </div>

            <!-- Period & Usage -->
            <div class="modern-card">
                <h3 style="margin: 0 0 1.5rem 0; color: #1F2937; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-calendar-alt" style="color: #FF6F00;"></i>
                    Periode & Penggunaan
                </h3>

                <div style="display: grid; gap: 1.5rem;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <!-- Valid From -->
                        <div>
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #1F2937;">
                                Berlaku Dari <span style="color: #ef4444;">*</span>
                            </label>
                            <input type="date" 
                                   name="valid_from" 
                                   value="{{ old('valid_from', isset($voucher) ? $voucher->valid_from->format('Y-m-d') : '') }}" 
                                   required
                                   style="width: 100%; padding: 0.75rem 1rem; border: 2px solid {{ $errors->has('valid_from') ? '#ef4444' : '#e5e7eb' }}; border-radius: 12px;">
                            @error('valid_from')
                            <p style="color: #ef4444; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Valid Until -->
                        <div>
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #1F2937;">
                                Berlaku Hingga <span style="color: #ef4444;">*</span>
                            </label>
                            <input type="date" 
                                   name="valid_until" 
                                   value="{{ old('valid_until', isset($voucher) ? $voucher->valid_until->format('Y-m-d') : '') }}" 
                                   required
                                   style="width: 100%; padding: 0.75rem 1rem; border: 2px solid {{ $errors->has('valid_until') ? '#ef4444' : '#e5e7eb' }}; border-radius: 12px;">
                            @error('valid_until')
                            <p style="color: #ef4444; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
                        <!-- Max Usage -->
                        <div>
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #1F2937;">
                                Maks Usage Total
                            </label>
                            <input type="number" 
                                   name="max_usage" 
                                   value="{{ old('max_usage', $voucher->max_usage ?? '') }}" 
                                   min="1"
                                   placeholder="Unlimited"
                                   style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #e5e7eb; border-radius: 12px;">
                            <p style="font-size: 0.85rem; color: #6b7280; margin-top: 0.5rem;">Kosongkan = unlimited</p>
                        </div>

                        <!-- Max Usage Per Member -->
                        <div>
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #1F2937;">
                                Per Member <span style="color: #ef4444;">*</span>
                            </label>
                            <input type="number" 
                                   name="max_usage_per_member" 
                                   value="{{ old('max_usage_per_member', $voucher->max_usage_per_member ?? 1) }}" 
                                   required
                                   min="1"
                                   style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #e5e7eb; border-radius: 12px;">
                        </div>

                        <!-- Stock -->
                        <div>
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #1F2937;">
                                Stok Voucher
                            </label>
                            <input type="number" 
                                   name="stock" 
                                   value="{{ old('stock', $voucher->stock ?? '') }}" 
                                   min="1"
                                   placeholder="Unlimited"
                                   style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #e5e7eb; border-radius: 12px;">
                            <p style="font-size: 0.85rem; color: #6b7280; margin-top: 0.5rem;">Kosongkan = unlimited</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div>
            <!-- Points & Status -->
            <div class="modern-card" style="margin-bottom: 2rem;">
                <h3 style="margin: 0 0 1.5rem 0; color: #1F2937; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-star" style="color: #FF6F00;"></i>
                    Poin & Status
                </h3>

                <div style="display: grid; gap: 1.5rem;">
                    <!-- Points Required -->
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #1F2937;">
                            Poin Dibutuhkan <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="number" 
                               name="points_required" 
                               value="{{ old('points_required', $voucher->points_required ?? '') }}" 
                               required
                               min="1"
                               placeholder="Contoh: 1000"
                               style="width: 100%; padding: 0.75rem 1rem; border: 2px solid {{ $errors->has('points_required') ? '#ef4444' : '#e5e7eb' }}; border-radius: 12px; font-weight: 600; font-size: 1.1rem; color: #FF6F00;">
                        @error('points_required')
                        <p style="color: #ef4444; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
                        @enderror
                        <p style="font-size: 0.85rem; color: #6b7280; margin-top: 0.5rem;">Member perlu poin ini untuk redeem</p>
                    </div>

                    <!-- Is Active -->
                    <div>
                        <label style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; border: 2px solid #e5e7eb; border-radius: 12px; cursor: pointer; transition: all 0.3s;" id="activeToggle">
                            <input type="checkbox" 
                                   name="is_active" 
                                   value="1"
                                   {{ old('is_active', $voucher->is_active ?? true) ? 'checked' : '' }}
                                   style="width: 20px; height: 20px; accent-color: #10b981;">
                            <div>
                                <div style="font-weight: 600; color: #1F2937;">Aktifkan Voucher</div>
                                <div style="font-size: 0.85rem; color: #6b7280;">Member bisa redeem voucher ini</div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Preview Card -->
            <div class="modern-card" style="background: linear-gradient(135deg, #FF6F00 0%, #FF8F00 100%); color: white;">
                <div style="text-align: center; padding: 1rem 0;">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <div style="font-size: 0.9rem; opacity: 0.9; margin-bottom: 0.5rem;">VOUCHER PREVIEW</div>
                    <div style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1rem; font-family: 'Courier New', monospace;" id="previewCode">
                        {{ old('code', $voucher->code ?? 'VOUCHERCODE') }}
                    </div>
                    <div style="background: rgba(255,255,255,0.2); padding: 1rem; border-radius: 12px; margin-bottom: 1rem;">
                        <div style="font-size: 0.85rem; opacity: 0.9; margin-bottom: 0.25rem;">Nilai Diskon</div>
                        <div style="font-size: 1.8rem; font-weight: 700;" id="previewDiscount">-</div>
                    </div>
                    <div style="font-size: 0.85rem; opacity: 0.9; margin-bottom: 0.25rem;">Butuh Poin</div>
                    <div style="font-size: 1.3rem; font-weight: 600;" id="previewPoints">
                        {{ old('points_required', $voucher->points_required ?? '0') }} Poin
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="btn-primary" style="flex: 1;">
                    <i class="fas fa-save"></i> {{ isset($voucher) ? 'Update Voucher' : 'Simpan Voucher' }}
                </button>
                <a href="{{ route('admin.vouchers.index') }}" class="btn-secondary" style="flex: 0; padding: 0.75rem 1.5rem;">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </div>
    </div>
</form>

<style>
.discount-type-option:has(input:checked) {
    border-color: #FF6F00;
    background: #fffbf5;
}

#activeToggle:has(input:checked) {
    border-color: #10b981;
    background: #f0fdf4;
}

input:focus, textarea:focus, select:focus {
    outline: none;
    border-color: #FF6F00 !important;
    box-shadow: 0 0 0 3px rgba(255, 111, 0, 0.1);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preview updates
    const codeInput = document.querySelector('input[name="code"]');
    const discountTypeInputs = document.querySelectorAll('input[name="discount_type"]');
    const discountValueInput = document.querySelector('input[name="discount_value"]');
    const pointsInput = document.querySelector('input[name="points_required"]');
    const maxDiscountField = document.getElementById('maxDiscountField');

    const previewCode = document.getElementById('previewCode');
    const previewDiscount = document.getElementById('previewDiscount');
    const previewPoints = document.getElementById('previewPoints');

    function updatePreview() {
        previewCode.textContent = codeInput.value || 'VOUCHERCODE';
        
        const discountType = document.querySelector('input[name="discount_type"]:checked').value;
        const discountValue = discountValueInput.value || '0';
        
        if (discountType === 'percentage') {
            previewDiscount.textContent = discountValue + '%';
            maxDiscountField.style.display = 'block';
        } else {
            previewDiscount.textContent = 'Rp ' + parseInt(discountValue).toLocaleString('id-ID');
            maxDiscountField.style.display = 'none';
        }
        
        previewPoints.textContent = (pointsInput.value || '0') + ' Poin';
    }

    codeInput.addEventListener('input', updatePreview);
    discountValueInput.addEventListener('input', updatePreview);
    pointsInput.addEventListener('input', updatePreview);
    discountTypeInputs.forEach(input => input.addEventListener('change', updatePreview));

    // Initial update
    updatePreview();

    // Code uppercase
    codeInput.addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });
});
</script>
@endsection
