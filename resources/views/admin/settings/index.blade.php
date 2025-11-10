@extends('layouts.modern')

@section('title', 'Pengaturan Sistem')

@section('content')
<div class="fade-in">
    <!-- Page Header -->
    <div style="margin-bottom: 32px;">
        <h1 style="font-size: 28px; font-weight: 700; color: var(--dark-gray); margin-bottom: 8px;">
            <i class="fas fa-cog" style="color: var(--cream); margin-right: 12px;"></i>
            Pengaturan Sistem
        </h1>
        <p style="color: #6B7280; font-size: 14px;">Kelola konfigurasi dan pengaturan aplikasi</p>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="alert alert-success" style="margin-bottom: 24px; padding: 16px; background: #D1FAE5; border-left: 4px solid #10B981; border-radius: 12px;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <i class="fas fa-check-circle" style="color: #10B981; font-size: 20px;"></i>
            <span style="color: #065F46; font-weight: 600;">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    <!-- Error Messages -->
    @if($errors->any())
    <div class="alert alert-danger" style="margin-bottom: 24px; padding: 16px; background: #FEE2E2; border-left: 4px solid #EF4444; border-radius: 12px;">
        <div style="display: flex; align-items-start; gap: 12px;">
            <i class="fas fa-exclamation-circle" style="color: #EF4444; font-size: 20px; margin-top: 2px;"></i>
            <div style="flex: 1;">
                <div style="color: #991B1B; font-weight: 600; margin-bottom: 8px;">Terjadi kesalahan:</div>
                <ul style="color: #991B1B; font-size: 14px; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <!-- Settings Form -->
    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card" style="margin-bottom: 24px;">
            <div style="padding: 24px; border-bottom: 1px solid rgba(221, 208, 200, 0.3);">
                <h2 style="font-size: 18px; font-weight: 700; color: var(--dark-gray);">
                    <i class="fas fa-store" style="color: var(--cream); margin-right: 8px;"></i>
                    Informasi Toko
                </h2>
            </div>
            <div style="padding: 24px;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px;">
                    @forelse($settings->where('key', 'like', 'store_%') as $setting)
                    <div>
                        <label style="display: block; color: #6B7280; font-size: 14px; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-{{ $setting->key === 'store_name' ? 'store' : ($setting->key === 'store_phone' ? 'phone' : ($setting->key === 'store_email' ? 'envelope' : 'map-marker-alt')) }}" 
                               style="color: var(--cream); margin-right: 6px; width: 16px;"></i>
                            {{ ucwords(str_replace(['store_', '_'], ['', ' '], $setting->key)) }}
                        </label>
                        <input type="text" 
                               name="{{ $setting->key }}" 
                               value="{{ old($setting->key, $setting->value) }}" 
                               class="form-input"
                               style="width: 100%; padding: 12px 16px; border: 2px solid rgba(221, 208, 200, 0.5); border-radius: 12px; font-size: 14px; color: var(--dark-gray); transition: all 0.3s;"
                               placeholder="Masukkan {{ strtolower(str_replace(['store_', '_'], ['', ' '], $setting->key)) }}">
                    </div>
                    @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 32px; color: #9CA3AF;">
                        <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                        <div>Belum ada pengaturan toko</div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="card" style="margin-bottom: 24px;">
            <div style="padding: 24px; border-bottom: 1px solid rgba(221, 208, 200, 0.3);">
                <h2 style="font-size: 18px; font-weight: 700; color: var(--dark-gray);">
                    <i class="fas fa-tags" style="color: var(--cream); margin-right: 8px;"></i>
                    Pengaturan Member & Diskon
                </h2>
            </div>
            <div style="padding: 24px;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px;">
                    @forelse($settings->whereIn('key', ['member_discount_percentage', 'points_per_amount', 'points_value']) as $setting)
                    <div>
                        <label style="display: block; color: #6B7280; font-size: 14px; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-{{ $setting->key === 'member_discount_percentage' ? 'percent' : 'coins' }}" 
                               style="color: var(--cream); margin-right: 6px; width: 16px;"></i>
                            {{ ucwords(str_replace('_', ' ', $setting->key)) }}
                            @if($setting->key === 'member_discount_percentage')
                                <span style="color: #9CA3AF; font-size: 12px; font-weight: 400;">(dalam %)</span>
                            @elseif($setting->key === 'points_per_amount')
                                <span style="color: #9CA3AF; font-size: 12px; font-weight: 400;">(Rp per poin)</span>
                            @endif
                        </label>
                        <input type="number" 
                               name="{{ $setting->key }}" 
                               value="{{ old($setting->key, $setting->value) }}" 
                               step="{{ $setting->key === 'member_discount_percentage' ? '0.01' : '1' }}"
                               class="form-input"
                               style="width: 100%; padding: 12px 16px; border: 2px solid rgba(221, 208, 200, 0.5); border-radius: 12px; font-size: 14px; color: var(--dark-gray); transition: all 0.3s;"
                               placeholder="0">
                        @if($setting->key === 'points_per_amount')
                        <p style="font-size: 12px; color: #9CA3AF; margin-top: 6px;">
                            <i class="fas fa-info-circle"></i> Setiap kelipatan Rp berapa member mendapat 1 poin
                        </p>
                        @elseif($setting->key === 'points_value')
                        <p style="font-size: 12px; color: #9CA3AF; margin-top: 6px;">
                            <i class="fas fa-info-circle"></i> Nilai tukar 1 poin dalam Rupiah
                        </p>
                        @endif
                    </div>
                    @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 32px; color: #9CA3AF;">
                        <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                        <div>Belum ada pengaturan member</div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="card" style="margin-bottom: 24px;">
            <div style="padding: 24px; border-bottom: 1px solid rgba(221, 208, 200, 0.3);">
                <h2 style="font-size: 18px; font-weight: 700; color: var(--dark-gray);">
                    <i class="fas fa-receipt" style="color: var(--cream); margin-right: 8px;"></i>
                    Pengaturan Transaksi & Pajak
                </h2>
            </div>
            <div style="padding: 24px;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px;">
                    @forelse($settings->whereIn('key', ['tax_percentage', 'transaction_prefix']) as $setting)
                    <div>
                        <label style="display: block; color: #6B7280; font-size: 14px; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-{{ $setting->key === 'tax_percentage' ? 'percentage' : 'hashtag' }}" 
                               style="color: var(--cream); margin-right: 6px; width: 16px;"></i>
                            {{ ucwords(str_replace('_', ' ', $setting->key)) }}
                            @if($setting->key === 'tax_percentage')
                                <span style="color: #9CA3AF; font-size: 12px; font-weight: 400;">(dalam %)</span>
                            @endif
                        </label>
                        <input type="{{ $setting->key === 'tax_percentage' ? 'number' : 'text' }}" 
                               name="{{ $setting->key }}" 
                               value="{{ old($setting->key, $setting->value) }}" 
                               {{ $setting->key === 'tax_percentage' ? 'step=0.01' : '' }}
                               class="form-input"
                               style="width: 100%; padding: 12px 16px; border: 2px solid rgba(221, 208, 200, 0.5); border-radius: 12px; font-size: 14px; color: var(--dark-gray); transition: all 0.3s;"
                               placeholder="{{ $setting->key === 'transaction_prefix' ? 'TRX' : '0' }}">
                        @if($setting->key === 'transaction_prefix')
                        <p style="font-size: 12px; color: #9CA3AF; margin-top: 6px;">
                            <i class="fas fa-info-circle"></i> Contoh: TRX akan menghasilkan TRX-20251029-0001
                        </p>
                        @endif
                    </div>
                    @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 32px; color: #9CA3AF;">
                        <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                        <div>Belum ada pengaturan transaksi</div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Other Settings -->
        @if($settings->whereNotIn('key', ['store_name', 'store_phone', 'store_email', 'store_address', 'member_discount_percentage', 'points_per_amount', 'points_value', 'tax_percentage', 'transaction_prefix'])->count() > 0)
        <div class="card" style="margin-bottom: 24px;">
            <div style="padding: 24px; border-bottom: 1px solid rgba(221, 208, 200, 0.3);">
                <h2 style="font-size: 18px; font-weight: 700; color: var(--dark-gray);">
                    <i class="fas fa-sliders-h" style="color: var(--cream); margin-right: 8px;"></i>
                    Pengaturan Lainnya
                </h2>
            </div>
            <div style="padding: 24px;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px;">
                    @foreach($settings->whereNotIn('key', ['store_name', 'store_phone', 'store_email', 'store_address', 'member_discount_percentage', 'points_per_amount', 'points_value', 'tax_percentage', 'transaction_prefix']) as $setting)
                    <div>
                        <label style="display: block; color: #6B7280; font-size: 14px; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-cog" style="color: var(--cream); margin-right: 6px; width: 16px;"></i>
                            {{ ucwords(str_replace('_', ' ', $setting->key)) }}
                        </label>
                        <input type="text" 
                               name="{{ $setting->key }}" 
                               value="{{ old($setting->key, $setting->value) }}" 
                               class="form-input"
                               style="width: 100%; padding: 12px 16px; border: 2px solid rgba(221, 208, 200, 0.5); border-radius: 12px; font-size: 14px; color: var(--dark-gray); transition: all 0.3s;"
                               placeholder="Masukkan nilai">
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Submit Button -->
        <div style="display: flex; justify-content: flex-end; gap: 12px;">
            <button type="reset" class="btn btn-secondary" style="padding: 12px 32px;">
                <i class="fas fa-undo"></i>
                <span>Reset</span>
            </button>
            <button type="submit" class="btn btn-primary" style="padding: 12px 32px;">
                <i class="fas fa-save"></i>
                <span>Simpan Pengaturan</span>
            </button>
        </div>
    </form>

    <!-- Info Box -->
    <div class="card" style="margin-top: 32px; padding: 24px; background: var(--light-cream); border-left: 4px solid var(--cream);">
        <div style="display: flex; align-items-start; gap: 16px;">
            <i class="fas fa-info-circle" style="font-size: 24px; color: var(--cream); margin-top: 2px;"></i>
            <div>
                <h4 style="font-weight: 700; color: var(--dark-gray); margin-bottom: 8px;">Catatan Penting</h4>
                <ul style="color: #6B7280; font-size: 14px; line-height: 1.8; padding-left: 20px;">
                    <li>Perubahan pengaturan akan langsung diterapkan ke sistem</li>
                    <li>Pastikan nilai yang diinput sudah benar sebelum menyimpan</li>
                    <li>Perubahan diskon member akan berlaku untuk transaksi berikutnya</li>
                    <li>Backup data secara berkala untuk keamanan</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
.form-input:focus {
    outline: none;
    border-color: var(--cream);
    box-shadow: 0 0 0 3px rgba(221, 208, 200, 0.1);
}

.btn:hover {
    transform: translateY(-2px);
}
</style>
@endsection