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

@if(session('error'))
<div class="alert alert-error" style="margin-bottom: 1.5rem; padding: 1rem; background: #fee2e2; border-left: 4px solid #ef4444; border-radius: 8px; color: #991b1b;">
    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
</div>
@endif

<!-- Tab Navigation -->
<div class="modern-card" style="margin-bottom: 2rem; padding: 0; overflow: hidden;">
    <div style="display: flex; border-bottom: 2px solid #e5e7eb;">
        <div style="flex: 1; padding: 1.25rem 2rem; text-align: center; color: #FF6F00; font-weight: 600; border-bottom: 3px solid #FF6F00; background: #fffbf5; display: flex; align-items: center; justify-content: center; gap: 0.75rem;">
            <i class="fas fa-exchange-alt"></i>
            <span>Tukar Voucher</span>
        </div>
        <a href="{{ route('member.my-vouchers') }}" 
           style="flex: 1; padding: 1.25rem 2rem; text-align: center; text-decoration: none; color: #6b7280; font-weight: 600; border-bottom: 3px solid transparent; transition: all 0.3s; display: flex; align-items: center; justify-content: center; gap: 0.75rem;"
           onmouseover="this.style.background='#f9fafb'; this.style.color='#FF6F00';"
           onmouseout="this.style.background=''; this.style.color='#6b7280';">
            <i class="fas fa-wallet"></i>
            <span>Voucher Saya</span>
        </a>
    </div>
</div>

<!-- Member Points Card -->
<div class="modern-card" style="background: linear-gradient(135deg, #FF6F00 0%, #FF8F00 100%); color: white; margin-bottom: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <div style="font-size: 0.9rem; opacity: 0.9; margin-bottom: 0.5rem;">Poin Anda Saat Ini</div>
            <div style="font-size: 2.5rem; font-weight: 700;">{{ number_format($member->points) }}</div>
            <div style="font-size: 0.9rem; opacity: 0.9; margin-top: 0.5rem;">
                <i class="fas fa-info-circle"></i> Gunakan poin untuk menukar voucher diskon
            </div>
        </div>
        <div style="font-size: 4rem; opacity: 0.2;">
            <i class="fas fa-star"></i>
        </div>
    </div>
</div>

<!-- Vouchers Grid -->
@if($vouchers->count() > 0)
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.75rem;">
    @foreach($vouchers as $voucher)
    <div class="voucher-card" style="position: relative; background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: all 0.3s ease; border: 1px solid #f3f4f6;">
        <!-- Ribbon if redeemed -->
        @if($voucher->member_vouchers_count > 0)
        <div style="position: absolute; top: 12px; right: -32px; background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 4px 35px; transform: rotate(45deg); font-size: 0.7rem; font-weight: 700; box-shadow: 0 2px 6px rgba(0,0,0,0.2); z-index: 10; letter-spacing: 0.5px;">
            DITUKAR
        </div>
        @endif

        <!-- Voucher Header with Ticket Style -->
        <div style="background: linear-gradient(135deg, #FF6F00 0%, #FF8F00 100%); position: relative; padding: 1.75rem 1.5rem;">
            <!-- Notch circles for ticket effect -->
            <div style="position: absolute; bottom: -10px; left: -10px; width: 20px; height: 20px; background: #f9fafb; border-radius: 50%;"></div>
            <div style="position: absolute; bottom: -10px; right: -10px; width: 20px; height: 20px; background: #f9fafb; border-radius: 50%;"></div>
            
            <div style="display: flex; align-items: start; justify-content: space-between; gap: 1rem;">
                <div style="flex: 1; min-width: 0;">
                    <div style="font-size: 1.6rem; font-weight: 800; margin-bottom: 0.5rem; font-family: 'Courier New', monospace; color: white; text-shadow: 0 2px 4px rgba(0,0,0,0.1); word-break: break-all;">
                        {{ $voucher->code }}
                    </div>
                    <div style="font-size: 1.05rem; font-weight: 600; color: rgba(255,255,255,0.95); line-height: 1.4;">
                        {{ $voucher->name }}
                    </div>
                </div>
                <div style="background: rgba(255,255,255,0.25); backdrop-filter: blur(10px); padding: 0.85rem; border-radius: 14px; text-align: center; min-width: 75px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                    <div style="font-size: 0.7rem; opacity: 0.9; margin-bottom: 0.3rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Diskon</div>
                    <div style="font-size: 1.65rem; font-weight: 800; color: white; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                        @if($voucher->discount_type === 'percentage')
                            {{ $voucher->discount_value }}%
                        @else
                            {{ number_format($voucher->discount_value / 1000) }}K
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Dashed separator -->
        <div style="border-top: 2px dashed #e5e7eb; margin: 0 1rem;"></div>

        <!-- Voucher Details -->
        <div style="padding: 1.5rem;">
            @if($voucher->description)
            <div style="color: #6b7280; margin-bottom: 1.25rem; font-size: 0.9rem; line-height: 1.6;">
                {{ Str::limit($voucher->description, 80) }}
            </div>
            @endif

            <!-- Info Grid with Icons -->
            <div style="display: flex; flex-direction: column; gap: 0.85rem; margin-bottom: 1.25rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: linear-gradient(to right, #fffbf5, #fff); border-radius: 10px; border: 1px solid #fef3c7;">
                    <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #FF6F00, #FF8F00); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.1rem; flex-shrink: 0;">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-size: 0.75rem; color: #6b7280; margin-bottom: 0.15rem; font-weight: 500;">Min. Belanja</div>
                        <div style="font-weight: 700; color: #1F2937; font-size: 0.95rem;">Rp {{ number_format($voucher->min_transaction, 0, ',', '.') }}</div>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: linear-gradient(to right, #f0fdf4, #fff); border-radius: 10px; border: 1px solid #d1fae5;">
                    <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #10b981, #059669); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.1rem; flex-shrink: 0;">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-size: 0.75rem; color: #6b7280; margin-bottom: 0.15rem; font-weight: 500;">Berlaku s/d</div>
                        <div style="font-weight: 700; color: #1F2937; font-size: 0.95rem;">{{ $voucher->valid_until->format('d M Y') }}</div>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: linear-gradient(to right, #eff6ff, #fff); border-radius: 10px; border: 1px solid #dbeafe;">
                    <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #3b82f6, #2563eb); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.1rem; flex-shrink: 0;">
                        <i class="fas fa-cubes"></i>
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-size: 0.75rem; color: #6b7280; margin-bottom: 0.15rem; font-weight: 500;">Stok Tersedia</div>
                        <div style="font-weight: 700; color: #1F2937; font-size: 0.95rem;">
                            @if($voucher->stock === null)
                                <span style="color: #10b981;"><i class="fas fa-infinity"></i> Unlimited</span>
                            @else
                                {{ $voucher->stock }} voucher
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if($voucher->discount_type === 'percentage' && $voucher->max_discount)
            <div style="background: linear-gradient(to right, #fef3c7, #fffbeb); border-left: 4px solid #f59e0b; padding: 0.85rem; border-radius: 10px; margin-bottom: 1.25rem; font-size: 0.85rem; color: #92400e; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-info-circle" style="flex-shrink: 0;"></i> 
                <span>Maks. potongan <strong>Rp {{ number_format($voucher->max_discount, 0, ',', '.') }}</strong></span>
            </div>
            @endif

            <!-- Points & Redemption Info -->
            <div style="background: linear-gradient(135deg, #fffbf5, #fff7ed); padding: 1.25rem; border-radius: 12px; margin-bottom: 1.25rem; border: 2px solid #fed7aa;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div style="flex: 1;">
                        <div style="font-size: 0.8rem; color: #92400e; margin-bottom: 0.4rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                            <i class="fas fa-star"></i> Butuh Poin
                        </div>
                        <div style="font-size: 1.75rem; font-weight: 800; color: #FF6F00; display: flex; align-items: baseline; gap: 0.25rem;">
                            {{ number_format($voucher->points_required) }}
                            <span style="font-size: 0.85rem; font-weight: 600; color: #f59e0b;">pts</span>
                        </div>
                    </div>
                    <div style="text-align: right; padding-left: 1rem; border-left: 2px solid #fed7aa;">
                        <div style="font-size: 0.8rem; color: #92400e; margin-bottom: 0.4rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                            <i class="fas fa-users"></i> Ditukar
                        </div>
                        <div style="font-size: 1.5rem; font-weight: 800; color: #1F2937;">
                            {{ $voucher->member_vouchers_count }}
                            @if($voucher->max_usage_per_member > 1)
                                <span style="font-size: 0.85rem; color: #6b7280; font-weight: 600;">/{{ $voucher->max_usage_per_member }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Button -->
            @if($voucher->member_vouchers_count >= $voucher->max_usage_per_member)
                <button disabled class="btn-secondary" style="width: 100%; opacity: 0.5; cursor: not-allowed;">
                    <i class="fas fa-check-circle"></i> Sudah Mencapai Batas
                </button>
            @elseif($member->points < $voucher->points_required)
                <button disabled class="btn-secondary" style="width: 100%; opacity: 0.5; cursor: not-allowed;">
                    <i class="fas fa-lock"></i> Poin Tidak Cukup
                </button>
            @else
                <form action="{{ route('member.vouchers.redeem', $voucher->id) }}" method="POST" onsubmit="return confirm('Tukar {{ number_format($voucher->points_required) }} poin dengan voucher {{ $voucher->code }}?');">
                    @csrf
                    <button type="submit" class="btn-primary" style="width: 100%;">
                        <i class="fas fa-exchange-alt"></i> Tukar dengan {{ number_format($voucher->points_required) }} Poin
                    </button>
                </form>
            @endif
        </div>
    </div>
    @endforeach
</div>
@else
<div class="modern-card" style="text-align: center; padding: 4rem 2rem;">
    <div style="font-size: 4rem; color: #e5e7eb; margin-bottom: 1rem;">
        <i class="fas fa-ticket-alt"></i>
    </div>
    <h3 style="color: #6b7280; margin-bottom: 0.5rem;">Belum Ada Voucher Tersedia</h3>
    <p style="color: #9ca3af;">Voucher akan muncul di sini ketika tersedia</p>
</div>
@endif

<style>
.voucher-tab.active {
    color: #FF6F00;
    border-bottom-color: #FF6F00;
    background: #fffbf5;
}

.voucher-tab:hover {
    color: #FF6F00;
    background: #fffbf5;
}

.voucher-card {
    animation: fadeInUp 0.4s ease-out;
}

.voucher-card:hover {
    transform: translateY(-6px) scale(1.01);
    box-shadow: 0 16px 32px rgba(255, 111, 0, 0.15), 0 0 0 1px rgba(255, 111, 0, 0.1);
}

.alert {
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .voucher-card {
        margin-bottom: 1rem;
    }
}
</style>

<script>
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
