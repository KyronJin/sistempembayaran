@extends('layouts.modern')

@section('title', 'Voucher')

@section('content')
<div class="content-header">
    <h1>Voucher</h1>
    <p>Tukar poin dan kelola voucher Anda</p>
</div>

@if(session('success'))
<div class="alert alert-success" style="margin-bottom: 1.5rem; padding: 1rem; background: #d1fae5; border-left: 4px solid #10b981; border-radius: 8px; color: #065f46;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

<!-- Tab Navigation -->
<div class="modern-card" style="margin-bottom: 2rem; padding: 0; overflow: hidden;">
    <div style="display: flex; border-bottom: 2px solid #e5e7eb;">
        <a href="{{ route('member.vouchers') }}" 
           style="flex: 1; padding: 1.25rem 2rem; text-align: center; text-decoration: none; color: #6b7280; font-weight: 600; border-bottom: 3px solid transparent; transition: all 0.3s; display: flex; align-items: center; justify-content: center; gap: 0.75rem;"
           onmouseover="this.style.background='#f9fafb'; this.style.color='#FF6F00';"
           onmouseout="this.style.background=''; this.style.color='#6b7280';">
            <i class="fas fa-exchange-alt"></i>
            <span>Tukar Voucher</span>
        </a>
        <div style="flex: 1; padding: 1.25rem 2rem; text-align: center; color: #FF6F00; font-weight: 600; border-bottom: 3px solid #FF6F00; background: #fffbf5; display: flex; align-items: center; justify-content: center; gap: 0.75rem;">
            <i class="fas fa-wallet"></i>
            <span>Voucher Saya</span>
            @if($vouchers->count() > 0)
            <span style="background: #FF6F00; color: white; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.85rem; min-width: 28px; text-align: center;">
                {{ $vouchers->count() }}
            </span>
            @endif
        </div>
    </div>
</div>

<!-- Stats Summary -->
@if($vouchers->count() > 0)
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
    <div class="modern-card" style="background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); border-left: 4px solid #10b981;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <div style="font-size: 0.85rem; color: #065f46; margin-bottom: 0.25rem; font-weight: 500;">Tersedia</div>
                <div style="font-size: 2rem; font-weight: 700; color: #065f46;">{{ $vouchers->where('status', 'available')->count() }}</div>
            </div>
            <div style="width: 50px; height: 50px; background: rgba(16, 185, 129, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #065f46; font-size: 1.5rem;">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
    <div class="modern-card" style="background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%); border-left: 4px solid #6b7280;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <div style="font-size: 0.85rem; color: #374151; margin-bottom: 0.25rem; font-weight: 500;">Sudah Dipakai</div>
                <div style="font-size: 2rem; font-weight: 700; color: #374151;">{{ $vouchers->where('status', 'used')->count() }}</div>
            </div>
            <div style="width: 50px; height: 50px; background: rgba(107, 114, 128, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #374151; font-size: 1.5rem;">
                <i class="fas fa-check"></i>
            </div>
        </div>
    </div>
    <div class="modern-card" style="background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); border-left: 4px solid #ef4444;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <div style="font-size: 0.85rem; color: #991b1b; margin-bottom: 0.25rem; font-weight: 500;">Kadaluarsa</div>
                <div style="font-size: 2rem; font-weight: 700; color: #991b1b;">{{ $vouchers->where('status', 'expired')->count() }}</div>
            </div>
            <div style="width: 50px; height: 50px; background: rgba(239, 68, 68, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #991b1b; font-size: 1.5rem;">
                <i class="fas fa-times-circle"></i>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Filter Tabs -->
<div style="margin-bottom: 2rem;">
    <div class="modern-card" style="padding: 1rem;">
        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <button class="filter-tab active" data-status="all" onclick="filterVouchers('all')">
                <i class="fas fa-th"></i> Semua ({{ $vouchers->count() }})
            </button>
            <button class="filter-tab" data-status="available" onclick="filterVouchers('available')">
                <i class="fas fa-check-circle"></i> Tersedia ({{ $vouchers->where('status', 'available')->count() }})
            </button>
            <button class="filter-tab" data-status="used" onclick="filterVouchers('used')">
                <i class="fas fa-shopping-cart"></i> Sudah Dipakai ({{ $vouchers->where('status', 'used')->count() }})
            </button>
            <button class="filter-tab" data-status="expired" onclick="filterVouchers('expired')">
                <i class="fas fa-clock"></i> Kadaluarsa ({{ $vouchers->where('status', 'expired')->count() }})
            </button>
        </div>
    </div>
</div>

<!-- Vouchers List -->
<!-- Quick Action Banner -->
@if($vouchers->where('status', 'available')->count() === 0 && $vouchers->count() > 0)
<div class="modern-card" style="background: linear-gradient(135deg, #fffbf5 0%, #fff7ed 100%); border: 2px dashed #FF6F00; margin-bottom: 2rem;">
    <div style="display: flex; align-items: center; justify-content: space-between; gap: 2rem; flex-wrap: wrap;">
        <div style="flex: 1;">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.75rem;">
                <div style="width: 50px; height: 50px; background: #FF6F00; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem;">
                    <i class="fas fa-ticket-alt"></i>
                </div>
                <div>
                    <h3 style="margin: 0; color: #1F2937; font-size: 1.2rem;">Tidak ada voucher tersedia</h3>
                    <p style="margin: 0.25rem 0 0 0; color: #6b7280; font-size: 0.95rem;">Tukarkan poin Anda dengan voucher diskon menarik!</p>
                </div>
            </div>
        </div>
        <a href="{{ route('member.vouchers') }}" class="btn-voucher-action">
            <i class="fas fa-plus-circle"></i>
            <span>Tukar Voucher Sekarang</span>
        </a>
    </div>
</div>
@endif

@if($vouchers->count() > 0)
<div style="display: grid; gap: 1.5rem;">
    @foreach($vouchers as $memberVoucher)
    <div class="modern-card voucher-item" 
         data-status="{{ $memberVoucher->status }}" 
         style="position: relative; overflow: hidden; transition: all 0.3s;">
        
        <!-- Status Badge -->
        <div style="position: absolute; top: 1rem; right: 1rem; z-index: 10;">
            @if($memberVoucher->status === 'available')
                <span style="background: #d1fae5; color: #065f46; padding: 0.5rem 1rem; border-radius: 20px; font-weight: 600; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-check-circle"></i> Tersedia
                </span>
            @elseif($memberVoucher->status === 'used')
                <span style="background: #e5e7eb; color: #4b5563; padding: 0.5rem 1rem; border-radius: 20px; font-weight: 600; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-check"></i> Sudah Dipakai
                </span>
            @else
                <span style="background: #fee2e2; color: #991b1b; padding: 0.5rem 1rem; border-radius: 20px; font-weight: 600; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-clock"></i> Kadaluarsa
                </span>
            @endif
        </div>

        <div style="display: grid; grid-template-columns: auto 1fr auto; gap: 2rem; align-items: center;">
            <!-- Voucher Icon -->
            <div style="background: linear-gradient(135deg, #FF6F00 0%, #FF8F00 100%); color: white; width: 120px; height: 120px; border-radius: 16px; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 1rem;">
                <i class="fas fa-ticket-alt" style="font-size: 2.5rem; margin-bottom: 0.5rem;"></i>
                <div style="font-size: 1.2rem; font-weight: 700;">
                    @if($memberVoucher->voucher->discount_type === 'percentage')
                        {{ $memberVoucher->voucher->discount_value }}%
                    @else
                        {{ number_format($memberVoucher->voucher->discount_value / 1000) }}K
                    @endif
                </div>
            </div>

            <!-- Voucher Info -->
            <div>
                <div style="font-size: 1.5rem; font-weight: 700; color: #1F2937; margin-bottom: 0.5rem; font-family: 'Courier New', monospace;">
                    {{ $memberVoucher->voucher_code }}
                </div>
                <div style="font-size: 1.1rem; font-weight: 600; color: #FF6F00; margin-bottom: 0.75rem;">
                    {{ $memberVoucher->voucher->name }}
                </div>
                
                @if($memberVoucher->voucher->description)
                <div style="color: #6b7280; margin-bottom: 1rem; font-size: 0.95rem;">
                    {{ $memberVoucher->voucher->description }}
                </div>
                @endif

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; color: #6b7280; font-size: 0.9rem;">
                        <i class="fas fa-shopping-bag" style="color: #FF6F00;"></i>
                        Min. Transaksi: <strong style="color: #1F2937;">Rp {{ number_format($memberVoucher->voucher->min_transaction, 0, ',', '.') }}</strong>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem; color: #6b7280; font-size: 0.9rem;">
                        <i class="fas fa-star" style="color: #FF6F00;"></i>
                        Poin Digunakan: <strong style="color: #1F2937;">{{ number_format($memberVoucher->points_used) }}</strong>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem; color: #6b7280; font-size: 0.9rem;">
                        <i class="fas fa-calendar" style="color: #FF6F00;"></i>
                        Ditukar: <strong style="color: #1F2937;">{{ $memberVoucher->redeemed_at->format('d M Y') }}</strong>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem; color: #6b7280; font-size: 0.9rem;">
                        <i class="fas fa-clock" style="color: #FF6F00;"></i>
                        Berlaku s/d: <strong style="color: #1F2937;">{{ $memberVoucher->expires_at->format('d M Y') }}</strong>
                    </div>
                </div>

                @if($memberVoucher->voucher->discount_type === 'percentage' && $memberVoucher->voucher->max_discount)
                <div style="margin-top: 1rem; background: #fef3c7; border-left: 3px solid #f59e0b; padding: 0.75rem; border-radius: 6px; font-size: 0.9rem; color: #92400e;">
                    <i class="fas fa-info-circle"></i> Maksimal potongan: Rp {{ number_format($memberVoucher->voucher->max_discount, 0, ',', '.') }}
                </div>
                @endif

                @if($memberVoucher->status === 'used')
                <div style="margin-top: 1rem; background: #f3f4f6; border-left: 3px solid #6b7280; padding: 0.75rem; border-radius: 6px; font-size: 0.9rem; color: #4b5563;">
                    <i class="fas fa-check-circle"></i> Digunakan pada: {{ $memberVoucher->used_at->format('d M Y, H:i') }}
                    @if($memberVoucher->transaction_id)
                        - <a href="{{ route('member.transaction-detail', $memberVoucher->transaction_id) }}" style="color: #FF6F00; text-decoration: underline;">Lihat Transaksi</a>
                    @endif
                </div>
                @endif
            </div>

            <!-- Action / QR Code -->
            <div style="text-align: center;">
                @if($memberVoucher->status === 'available')
                <div style="background: white; padding: 1rem; border: 3px dashed #FF6F00; border-radius: 12px;">
                    <div style="width: 100px; height: 100px; background: white; display: flex; align-items: center; justify-content: center; margin-bottom: 0.5rem;">
                        {!! QrCode::size(100)->generate($memberVoucher->voucher_code) !!}
                    </div>
                    <div style="font-size: 0.75rem; color: #6b7280; font-weight: 500;">
                        Tunjukkan ke kasir
                    </div>
                </div>
                <div style="margin-top: 1rem; padding: 0.75rem; background: #d1fae5; border-radius: 8px; font-size: 0.85rem; color: #065f46; font-weight: 500;">
                    <i class="fas fa-info-circle"></i> Siap digunakan
                </div>
                @elseif($memberVoucher->status === 'used')
                <div style="width: 120px; height: 120px; background: #f3f4f6; border-radius: 16px; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #9ca3af;">
                    <i class="fas fa-check-circle" style="font-size: 3rem; margin-bottom: 0.5rem;"></i>
                    <div style="font-size: 0.85rem; font-weight: 600;">Sudah Dipakai</div>
                </div>
                @else
                <div style="width: 120px; height: 120px; background: #fee2e2; border-radius: 16px; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #991b1b;">
                    <i class="fas fa-times-circle" style="font-size: 3rem; margin-bottom: 0.5rem;"></i>
                    <div style="font-size: 0.85rem; font-weight: 600;">Kadaluarsa</div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="modern-card" style="text-align: center; padding: 4rem 2rem;">
    <div style="font-size: 4rem; color: #e5e7eb; margin-bottom: 1rem;">
        <i class="fas fa-wallet"></i>
    </div>
    <h3 style="color: #6b7280; margin-bottom: 0.5rem;">Belum Ada Voucher</h3>
    <p style="color: #9ca3af; margin-bottom: 2rem;">Tukarkan poin Anda dengan voucher diskon</p>
    <a href="{{ route('member.vouchers') }}" class="btn-primary">
        <i class="fas fa-ticket-alt"></i> Tukar Voucher
    </a>
</div>
@endif

<style>
.btn-voucher-nav {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.875rem 1.75rem;
    background: linear-gradient(135deg, #FF6F00 0%, #FF8F00 100%);
    color: white;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(255, 111, 0, 0.3);
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.btn-voucher-nav::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0) 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.btn-voucher-nav:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(255, 111, 0, 0.4);
}

.btn-voucher-nav:hover::before {
    opacity: 1;
}

.btn-voucher-nav:active {
    transform: translateY(0);
    box-shadow: 0 2px 8px rgba(255, 111, 0, 0.3);
}

.btn-voucher-nav i {
    font-size: 1.1rem;
}

.btn-voucher-action {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 2rem;
    background: linear-gradient(135deg, #FF6F00 0%, #FF8F00 100%);
    color: white;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    font-size: 1.05rem;
    box-shadow: 0 4px 16px rgba(255, 111, 0, 0.3);
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    white-space: nowrap;
}

.btn-voucher-action:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(255, 111, 0, 0.4);
}

.btn-voucher-action:active {
    transform: translateY(-1px);
}

.btn-voucher-action i {
    font-size: 1.2rem;
}

.stat-card-mini {
    padding: 1.25rem;
    border-radius: 12px;
    transition: all 0.3s ease;
    cursor: default;
}

.stat-card-mini:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.filter-tab {
    padding: 0.75rem 1.5rem;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    background: white;
    color: #6b7280;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.filter-tab:hover {
    border-color: #FF6F00;
    color: #FF6F00;
}

.filter-tab.active {
    background: #FF6F00;
    color: white;
    border-color: #FF6F00;
}

.voucher-item {
    animation: fadeIn 0.5s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (max-width: 768px) {
    .content-header > div {
        flex-direction: column;
        align-items: flex-start !important;
    }
    
    .btn-voucher-nav {
        width: 100%;
        justify-content: center;
    }
}
</style>

<script>
function filterVouchers(status) {
    // Update active tab
    document.querySelectorAll('.filter-tab').forEach(tab => {
        tab.classList.remove('active');
    });
    document.querySelector(`[data-status="${status}"]`).classList.add('active');

    // Filter vouchers
    document.querySelectorAll('.voucher-item').forEach(item => {
        if (status === 'all' || item.dataset.status === status) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide alerts
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
});
</script>
@endsection
