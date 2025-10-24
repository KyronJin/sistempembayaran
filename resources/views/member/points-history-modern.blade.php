@extends('layouts.modern')

@section('title', 'Riwayat Poin')

@section('content')
<div class="content-header">
    <h1>Riwayat Poin</h1>
    <p>Pantau perolehan dan penggunaan poin Anda</p>
</div>

<!-- Points Summary -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="modern-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                <i class="fas fa-star"></i>
            </div>
            <div>
                <div style="opacity: 0.9; margin-bottom: 0.25rem;">Total Poin Anda</div>
                <div style="font-size: 2rem; font-weight: 700;">{{ number_format($member->points, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    <div class="modern-card">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: white;">
                <i class="fas fa-plus"></i>
            </div>
            <div>
                <div style="color: #6b7280; margin-bottom: 0.25rem;">Poin Didapat</div>
                <div style="font-size: 1.75rem; font-weight: 700; color: #10b981;">+{{ number_format($total_earned, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    <div class="modern-card">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: white;">
                <i class="fas fa-minus"></i>
            </div>
            <div>
                <div style="color: #6b7280; margin-bottom: 0.25rem;">Poin Digunakan</div>
                <div style="font-size: 1.75rem; font-weight: 700; color: #ef4444;">-{{ number_format($total_used, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Filter -->
<div class="modern-card" style="margin-bottom: 2rem;">
    <form method="GET" style="display: flex; gap: 1rem; flex-wrap: wrap;">
        <select name="type" class="form-input" style="flex: 1; min-width: 200px;">
            <option value="">Semua Tipe</option>
            <option value="earned" {{ request('type') == 'earned' ? 'selected' : '' }}>Poin Didapat</option>
            <option value="redeemed" {{ request('type') == 'redeemed' ? 'selected' : '' }}>Poin Digunakan</option>
            <option value="expired" {{ request('type') == 'expired' ? 'selected' : '' }}>Poin Expired</option>
        </select>
        <input type="date" name="from" class="form-input" value="{{ request('from') }}" placeholder="Dari Tanggal" style="flex: 1; min-width: 150px;">
        <input type="date" name="to" class="form-input" value="{{ request('to') }}" placeholder="Sampai Tanggal" style="flex: 1; min-width: 150px;">
        <button type="submit" class="btn-primary">
            <i class="fas fa-filter"></i> Filter
        </button>
        @if(request()->hasAny(['type', 'from', 'to']))
        <a href="{{ route('member.points-history') }}" class="btn-secondary">
            <i class="fas fa-times"></i> Reset
        </a>
        @endif
    </form>
</div>

<!-- Points History Timeline -->
<div class="modern-card">
    <h2 style="margin: 0 0 1.5rem 0; font-size: 1.25rem;"><i class="fas fa-history"></i> Riwayat Transaksi Poin</h2>

    @forelse($points_history as $history)
    <div style="display: flex; gap: 1.5rem; padding: 1.25rem; border-left: 3px solid {{ $history->type === 'earned' ? '#10b981' : '#ef4444' }}; background: #f9fafb; border-radius: 0 12px 12px 0; margin-bottom: 1rem;">
        <div style="flex-shrink: 0;">
            <div style="width: 50px; height: 50px; background: linear-gradient(135deg, {{ $history->type === 'earned' ? '#10b981, #059669' : '#ef4444, #dc2626' }}); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.25rem;">
                <i class="fas fa-{{ $history->type === 'earned' ? 'plus' : 'minus' }}"></i>
            </div>
        </div>
        <div style="flex: 1;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                <div>
                    <div style="font-weight: 700; font-size: 1.125rem; color: #1f2937; margin-bottom: 0.25rem;">
                        {{ $history->description }}
                    </div>
                    <div style="color: #6b7280; font-size: 0.9375rem;">
                        <i class="far fa-calendar"></i> {{ $history->created_at->format('d M Y, H:i') }}
                    </div>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 1.5rem; font-weight: 700; color: {{ $history->type === 'earned' ? '#10b981' : '#ef4444' }};">
                        {{ $history->type === 'earned' ? '+' : '-' }}{{ number_format($history->points, 0, ',', '.') }}
                    </div>
                    @if($history->transaction)
                    <div style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem;">
                        Transaksi: {{ $history->transaction->transaction_code }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @empty
    <div style="text-align: center; padding: 4rem; color: #6b7280;">
        <i class="fas fa-history" style="font-size: 4rem; opacity: 0.3; margin-bottom: 1rem; display: block;"></i>
        <p style="font-size: 1.125rem;">Belum ada riwayat poin</p>
        <p style="margin-top: 0.5rem;">Mulai berbelanja untuk mendapatkan poin!</p>
    </div>
    @endforelse

    @if($points_history->hasPages())
    <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #e5e7eb;">
        {{ $points_history->links() }}
    </div>
    @endif
</div>

<!-- Info Card -->
<div class="modern-card" style="margin-top: 2rem; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border: none;">
    <div style="display: flex; gap: 1rem; align-items: start;">
        <div style="font-size: 2rem; color: #f59e0b;">
            <i class="fas fa-lightbulb"></i>
        </div>
        <div>
            <h3 style="margin: 0 0 0.5rem 0; color: #92400e;">Tips Mengumpulkan Poin</h3>
            <ul style="margin: 0; padding-left: 1.5rem; color: #92400e; line-height: 1.8;">
                <li>Belanja lebih banyak untuk mendapatkan poin lebih banyak</li>
                <li>Gunakan kartu member setiap transaksi</li>
                <li>Pantau promo untuk bonus poin khusus</li>
                <li>Poin dapat digunakan untuk diskon pembelian berikutnya</li>
            </ul>
        </div>
    </div>
</div>
@endsection
