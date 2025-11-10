@extends('layouts.modern')

@section('title', isset($product) ? 'Edit Produk' : 'Tambah Produk')

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
@endpush

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

                <div>
                    <label class="form-label">
                        <i class="fas fa-qrcode" style="color: #FF6F00; margin-right: 6px;"></i>
                        Kode Produk / Barcode / QR Code *
                    </label>
                    <div style="display: flex; gap: 8px;">
                        <input type="text" id="barcode-input" name="barcode" class="form-input" 
                               value="{{ old('barcode', $product->barcode ?? '') }}" 
                               placeholder="Contoh: 8992761111014 atau PROD001"
                               required style="flex: 1;">
                        <button type="button" id="btn-scan-barcode" class="btn-scan-barcode" style="background: #FF6F00; color: white; border: none; padding: 0 20px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; gap: 6px; font-weight: 600; transition: all 0.3s;">
                            <i class="fas fa-camera"></i>
                            <span>Scan</span>
                        </button>
                    </div>
                    <span style="font-size: 0.875rem; color: #6b7280; margin-top: 4px; display: block;">
                        <i class="fas fa-info-circle"></i> Klik "Scan" untuk scan barcode dari produk fisik atau ketik manual
                    </span>
                    
                    <!-- QR Scanner Modal -->
                    <div id="barcode-scanner-modal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.75); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
                        <div style="background: white; padding: 24px; border-radius: 12px; max-width: 500px; width: 90%; border: 3px solid #FF6F00; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 2px solid #FF6F00;">
                                <h3 style="margin: 0; color: #1F2937; font-size: 1.25rem; font-weight: 700;">
                                    <i class="fas fa-camera mr-2" style="color: #FF6F00;"></i>Scan Barcode Produk
                                </h3>
                                <button type="button" id="close-scanner" style="background: #FEE2E2; border: none; font-size: 1.25rem; cursor: pointer; color: #DC2626; width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; transition: all 0.2s;" onmouseover="this.style.background='#FCA5A5'" onmouseout="this.style.background='#FEE2E2'">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div id="barcode-reader" style="border-radius: 8px; overflow: hidden; background: #F3F4F6; border: 2px solid #E5E7EB;"></div>
                            <p id="barcode-scan-status" style="text-align: center; margin-top: 12px; font-size: 0.875rem; color: #1F2937; font-weight: 500;">Arahkan kamera ke barcode pada produk</p>
                        </div>
                    </div>
                    
                    @error('barcode')
                    <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="form-label">SKU (Stock Keeping Unit) *</label>
                    <input type="text" name="sku" class="form-input" value="{{ old('sku', $product->sku ?? '') }}" required>
                    @error('sku')
                    <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span>
                    @enderror
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

                <div style="background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%); padding: 1.5rem; border-radius: 12px; color: white; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);">
                    <h3 style="margin: 0 0 1rem 0; font-size: 1.125rem; font-weight: 700;"><i class="fas fa-qrcode"></i> QR Code Produk</h3>
                    <p style="margin: 0; opacity: 0.95; font-size: 0.9375rem; line-height: 1.6;">
                        QR Code akan otomatis dibuat setelah produk disimpan. QR Code dapat digunakan untuk scanning cepat di POS.
                    </p>
                    @if(isset($product) && $product->qr_code)
                    <div style="margin-top: 1rem; text-align: center; background: white; padding: 1.25rem; border-radius: 12px; border: 2px solid rgba(255,255,255,0.3);">
                        <img src="{{ asset('storage/' . $product->qr_code) }}" alt="QR Code" style="width: 150px; height: 150px; border-radius: 8px;">
                        <div style="margin-top: 0.75rem;">
                            <a href="{{ route('admin.products.qr-download', $product->id) }}" style="display: inline-flex; align-items: center; gap: 6px; background: #3B82F6; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.2s;" onmouseover="this.style.background='#2563EB'" onmouseout="this.style.background='#3B82F6'">
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

// Barcode Scanner
let barcodeScanner = null;
const scannerModal = document.getElementById('barcode-scanner-modal');
const btnScanBarcode = document.getElementById('btn-scan-barcode');
const closeScanner = document.getElementById('close-scanner');
const barcodeInput = document.getElementById('barcode-input');
const scanStatus = document.getElementById('barcode-scan-status');

btnScanBarcode.addEventListener('click', () => {
    scannerModal.style.display = 'flex';
    startBarcodeScanner();
});

closeScanner.addEventListener('click', () => {
    stopBarcodeScanner();
    scannerModal.style.display = 'none';
});

// Close on overlay click
scannerModal.addEventListener('click', (e) => {
    if (e.target === scannerModal) {
        stopBarcodeScanner();
        scannerModal.style.display = 'none';
    }
});

function startBarcodeScanner() {
    scanStatus.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Memulai kamera...';
    
    if (!barcodeScanner && typeof Html5Qrcode !== 'undefined') {
        barcodeScanner = new Html5Qrcode("barcode-reader");
    }
    
    if (barcodeScanner) {
        Html5Qrcode.getCameras().then(cameras => {
            if (cameras && cameras.length) {
                console.log('Cameras found:', cameras.length);
                scanStatus.innerHTML = '<i class="fas fa-camera mr-1"></i> Kamera aktif, arahkan ke barcode...';
                
                const cameraId = cameras.length > 1 ? cameras[1].id : cameras[0].id;
                
                barcodeScanner.start(
                    cameraId,
                    { 
                        fps: 10, 
                        qrbox: { width: 300, height: 150 },
                        formatsToSupport: [
                            Html5QrcodeSupportedFormats.QR_CODE,
                            Html5QrcodeSupportedFormats.EAN_13,
                            Html5QrcodeSupportedFormats.EAN_8,
                            Html5QrcodeSupportedFormats.CODE_128,
                            Html5QrcodeSupportedFormats.CODE_39,
                            Html5QrcodeSupportedFormats.UPC_A,
                            Html5QrcodeSupportedFormats.UPC_E,
                            Html5QrcodeSupportedFormats.ITF,
                            Html5QrcodeSupportedFormats.CODABAR
                        ]
                    },
                    onBarcodeScanned,
                    onBarcodeScanError
                ).then(() => {
                    console.log('Barcode scanner started - supports all formats');
                }).catch(err => {
                    console.error('Error starting scanner:', err);
                    scanStatus.innerHTML = '<i class="fas fa-exclamation-triangle text-red-600 mr-1"></i> Gagal membuka kamera';
                    
                    // Fallback
                    setTimeout(() => {
                        barcodeScanner.start(
                            { facingMode: "environment" },
                            { 
                                fps: 10, 
                                qrbox: { width: 300, height: 150 },
                                formatsToSupport: [
                                    Html5QrcodeSupportedFormats.QR_CODE,
                                    Html5QrcodeSupportedFormats.EAN_13,
                                    Html5QrcodeSupportedFormats.EAN_8,
                                    Html5QrcodeSupportedFormats.CODE_128,
                                    Html5QrcodeSupportedFormats.CODE_39,
                                    Html5QrcodeSupportedFormats.UPC_A,
                                    Html5QrcodeSupportedFormats.UPC_E,
                                    Html5QrcodeSupportedFormats.ITF,
                                    Html5QrcodeSupportedFormats.CODABAR
                                ]
                            },
                            onBarcodeScanned,
                            onBarcodeScanError
                        ).catch(err2 => {
                            console.error('Fallback failed:', err2);
                            
                            const isPermissionError = err2.toString().includes('Permission') || 
                                                     err2.toString().includes('NotAllowedError') ||
                                                     err2.name === 'NotAllowedError';
                            
                            if (isPermissionError) {
                                scanStatus.innerHTML = `
                                    <div style="color: #dc2626; font-size: 0.875rem; text-align: left;">
                                        <div style="background: #FEF3C7; border: 2px solid #FCD34D; border-radius: 8px; padding: 12px; margin-top: 8px;">
                                            <p style="font-weight: 700; color: #92400E; margin-bottom: 8px;">
                                                <i class="fas fa-exclamation-triangle mr-1"></i> Kamera Tidak Dapat Diakses
                                            </p>
                                            <p style="color: #78350F; font-size: 0.813rem; margin-bottom: 8px;">Pastikan:</p>
                                            <ol style="color: #78350F; font-size: 0.813rem; margin-left: 20px; line-height: 1.6;">
                                                <li>Kamera tidak sedang digunakan aplikasi lain</li>
                                                <li>Klik icon <strong>🔒 atau 🎥</strong> di address bar</li>
                                                <li>Ubah Camera dari <strong>Block</strong> ke <strong>Allow</strong></li>
                                                <li>Refresh halaman (F5)</li>
                                            </ol>
                                        </div>
                                    </div>
                                `;
                            } else {
                                scanStatus.innerHTML = `
                                    <div style="color: #dc2626; font-size: 0.875rem;">
                                        <i class="fas fa-times-circle mr-1"></i> Error: ${err2.message || err2}<br>
                                        <small>Pastikan kamera terhubung dan tidak digunakan aplikasi lain</small>
                                    </div>
                                `;
                            }
                        });
                    }, 1000);
                });
            } else {
                scanStatus.innerHTML = '<i class="fas fa-times-circle text-red-600 mr-1"></i> Tidak ada kamera ditemukan';
            }
        }).catch(err => {
            console.error('Error getting cameras:', err);
            scanStatus.innerHTML = '<i class="fas fa-exclamation-triangle text-red-600 mr-1"></i> Error: ' + err.message;
        });
    }
}

function stopBarcodeScanner() {
    if (barcodeScanner) {
        if (barcodeScanner.isScanning) {
            barcodeScanner.stop().then(() => {
                console.log('Barcode scanner stopped successfully');
                // Clear the scanner instance to release camera
                barcodeScanner.clear();
                scanStatus.innerHTML = 'Scanner dihentikan';
            }).catch(err => {
                console.error('Error stopping scanner:', err);
                // Force clear even if stop fails
                try {
                    barcodeScanner.clear();
                } catch(e) {
                    console.error('Error clearing scanner:', e);
                }
            });
        } else {
            // Scanner not running but instance exists, clear it
            try {
                barcodeScanner.clear();
                console.log('Scanner instance cleared');
            } catch(e) {
                console.error('Error clearing scanner:', e);
            }
        }
    }
}

function onBarcodeScanned(decodedText) {
    console.log('Barcode scanned:', decodedText);
    
    // Play beep sound
    const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSl+zPLaizsKGGS57OihUBELTKXh8bllHgU2jdXzzn0vBSh+zPLaizsKGGS57OihUBELTKXh8bllHgU2jdXzzn0vBSh+zPLaizsKGGS57OihUBELTKXh8bllHgU2jdXzzn0vBSh+zPLaizsKGGS57OihUBELTKXh8bllHgU2jdXzzn0vBQ==');
    audio.play().catch(() => {});
    
    // Set barcode to input
    barcodeInput.value = decodedText;
    barcodeInput.focus();
    
    // Show success message
    scanStatus.innerHTML = '<i class="fas fa-check-circle" style="color: #10B981; margin-right: 4px;"></i> Berhasil! Kode: ' + decodedText;
    
    // Close scanner after 1 second
    setTimeout(() => {
        stopBarcodeScanner();
        scannerModal.style.display = 'none';
        scanStatus.innerHTML = 'Arahkan kamera ke barcode pada produk';
    }, 1500);
}

function onBarcodeScanError(error) {
    // Ignore scan errors (no barcode detected)
}

// Hover effect for scan button
btnScanBarcode.addEventListener('mouseenter', function() {
    this.style.backgroundColor = '#F57C00';
    this.style.transform = 'translateY(-2px)';
    this.style.boxShadow = '0 4px 12px rgba(255,111,0,0.3)';
});
btnScanBarcode.addEventListener('mouseleave', function() {
    this.style.backgroundColor = '#FF6F00';
    this.style.transform = 'translateY(0)';
    this.style.boxShadow = 'none';
});

// Clean up scanner when page is about to be closed/navigated away
window.addEventListener('beforeunload', function() {
    if (barcodeScanner) {
        try {
            if (barcodeScanner.isScanning) {
                barcodeScanner.stop();
            }
            barcodeScanner.clear();
            console.log('Scanner cleaned up before page unload');
        } catch(e) {
            console.error('Error cleaning up scanner:', e);
        }
    }
});

// Also clean up when navigating away (for SPA-like behavior)
window.addEventListener('pagehide', function() {
    if (barcodeScanner) {
        try {
            if (barcodeScanner.isScanning) {
                barcodeScanner.stop();
            }
            barcodeScanner.clear();
        } catch(e) {}
    }
});
</script>
@endsection
