@extends('layouts.modern')

@section('title', 'Detail Transaksi')

@section('content')
<div class="fade-in">
    <!-- Back Button -->
    <a href="{{ route('member.transactions') }}" style="display: inline-flex; align-items: center; gap: 8px; color: #6B7280; text-decoration: none; margin-bottom: 24px; font-weight: 600; transition: color 0.2s;" onmouseover="this.style.color='#FF6F00'" onmouseout="this.style.color='#6B7280'">
        <i class="fas fa-arrow-left"></i>
        <span>Kembali ke Riwayat Transaksi</span>
    </a>

    <!-- Transaction Header -->
    <div class="card" style="background: linear-gradient(135deg, #FF6F00 0%, #F57C00 100%); margin-bottom: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: start; color: white; flex-wrap: wrap; gap: 20px;">
            <div>
                <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(255,255,255,0.2); padding: 8px 16px; border-radius: 10px; margin-bottom: 16px;">
                    <i class="fas fa-receipt"></i>
                    <span style="font-weight: 700; font-size: 14px;">Kode Transaksi</span>
                </div>
                <h1 style="font-size: 36px; font-weight: 700; margin-bottom: 12px;">{{ $transaction->transaction_code }}</h1>
                <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-calendar"></i>
                        <span style="font-size: 15px;">{{ $transaction->transaction_date->format('d F Y, H:i') }}</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-user"></i>
                        <span style="font-size: 15px;">Kasir: {{ $transaction->cashier->name ?? '-' }}</span>
                    </div>
                </div>
            </div>
            <div style="text-align: right;">
                <span class="badge badge-success" style="padding: 12px 24px; font-size: 16px; background: rgba(16, 185, 129, 0.9);">
                    <i class="fas fa-check-circle mr-2"></i>Selesai
                </span>
                <div style="margin-top: 16px; font-size: 14px; opacity: 0.9;">Total Belanja</div>
                <div style="font-size: 40px; font-weight: 700; margin-top: 4px;">Rp {{ number_format($transaction->total, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
        <!-- Left Column: Items -->
        <div>
            <!-- Items List -->
            <div class="card" style="margin-bottom: 24px;">
                <h2 style="font-size: 20px; font-weight: 700; color: #111827; margin-bottom: 20px;">
                    <i class="fas fa-shopping-cart" style="color: var(--primary); margin-right: 8px;"></i>
                    Daftar Barang ({{ $transaction->details->count() }} item)
                </h2>

                <div style="display: flex; flex-direction: column; gap: 12px;">
                    @foreach($transaction->details as $detail)
                    <div style="display: flex; gap: 16px; padding: 16px; background: #F9FAFB; border-radius: 12px; transition: all 0.2s;" onmouseover="this.style.background='#F3F4F6'" onmouseout="this.style.background='#F9FAFB'">
                        <!-- Product Image Placeholder -->
                        <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #E5E7EB 0%, #D1D5DB 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            @if($detail->product && $detail->product->image)
                                <img src="{{ asset('storage/' . $detail->product->image) }}" alt="{{ $detail->product->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 12px;">
                            @else
                                <i class="fas fa-box" style="font-size: 32px; color: #9CA3AF;"></i>
                            @endif
                        </div>

                        <!-- Product Info -->
                        <div style="flex: 1;">
                            <h3 style="font-size: 16px; font-weight: 700; color: #111827; margin-bottom: 6px;">
                                {{ $detail->product->name ?? 'Produk tidak tersedia' }}
                            </h3>
                            <div style="display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 8px;">
                                <div style="font-size: 14px; color: #6B7280;">
                                    <i class="fas fa-tag mr-1"></i>
                                    Rp {{ number_format($detail->price, 0, ',', '.') }}
                                </div>
                                <div style="font-size: 14px; color: #6B7280;">
                                    <i class="fas fa-cubes mr-1"></i>
                                    {{ $detail->quantity }} {{ $detail->product->unit ?? 'pcs' }}
                                </div>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div style="font-size: 13px; color: #6B7280;">
                                    Subtotal
                                </div>
                                <div style="font-size: 18px; font-weight: 700; color: #4F46E5;">
                                    Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Payment Method -->
            @if($transaction->payments && $transaction->payments->count() > 0)
            <div class="card">
                <h2 style="font-size: 20px; font-weight: 700; color: #111827; margin-bottom: 20px;">
                    <i class="fas fa-credit-card" style="color: var(--secondary); margin-right: 8px;"></i>
                    Metode Pembayaran
                </h2>
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    @foreach($transaction->payments as $payment)
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 16px; background: linear-gradient(135deg, #D1FAE5 0%, #A7F3D0 100%); border-radius: 12px;">
                        <div>
                            <div style="font-weight: 700; color: #065F46; font-size: 16px; margin-bottom: 4px;">
                                {{ ucfirst($payment->payment_method) }}
                            </div>
                            <div style="font-size: 13px; color: #047857;">
                                <i class="fas fa-clock mr-1"></i>
                                {{ $payment->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                        <div style="font-size: 20px; font-weight: 700; color: #065F46;">
                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column: Summary -->
        <div>
            <!-- Price Summary -->
            <div class="card" style="margin-bottom: 24px;">
                <h2 style="font-size: 18px; font-weight: 700; color: #111827; margin-bottom: 20px;">
                    <i class="fas fa-calculator" style="color: var(--primary); margin-right: 8px;"></i>
                    Ringkasan Pembayaran
                </h2>

                <div style="display: flex; flex-direction: column; gap: 16px;">
                    <!-- Subtotal -->
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 16px; border-bottom: 2px solid #F3F4F6;">
                        <div style="color: #6B7280; font-size: 14px;">Subtotal</div>
                        <div style="font-weight: 700; color: #111827; font-size: 16px;">Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</div>
                    </div>

                    <!-- Discount -->
                    @if($transaction->discount > 0)
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 16px; border-bottom: 2px solid #F3F4F6;">
                        <div style="color: #6B7280; font-size: 14px;">
                            <i class="fas fa-percentage mr-1"></i>
                            Diskon
                            @if($transaction->member_discount > 0)
                                <span style="display: block; font-size: 12px; margin-top: 2px;">Member Discount</span>
                            @endif
                        </div>
                        <div style="font-weight: 700; color: #10B981; font-size: 16px;">- Rp {{ number_format($transaction->discount, 0, ',', '.') }}</div>
                    </div>
                    @endif

                    <!-- Tax -->
                    @if($transaction->tax > 0)
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 16px; border-bottom: 2px solid #F3F4F6;">
                        <div style="color: #6B7280; font-size: 14px;">
                            <i class="fas fa-file-invoice mr-1"></i>
                            Pajak ({{ round(($transaction->tax / $transaction->subtotal) * 100, 1) }}%)
                        </div>
                        <div style="font-weight: 700; color: #111827; font-size: 16px;">Rp {{ number_format($transaction->tax, 0, ',', '.') }}</div>
                    </div>
                    @endif

                    <!-- Total -->
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 20px; background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%); border-radius: 12px;">
                        <div style="font-weight: 700; color: #1E40AF; font-size: 16px;">Total Bayar</div>
                        <div style="font-weight: 700; color: #1E40AF; font-size: 24px;">Rp {{ number_format($transaction->total, 0, ',', '.') }}</div>
                    </div>

                    <!-- Cash & Change -->
                    @if($transaction->cash_received > 0)
                    <div style="background: #F9FAFB; padding: 16px; border-radius: 12px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                            <div style="color: #6B7280; font-size: 14px;">Uang Diterima</div>
                            <div style="font-weight: 600; color: #374151; font-size: 14px;">Rp {{ number_format($transaction->cash_received, 0, ',', '.') }}</div>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <div style="color: #6B7280; font-size: 14px;">Kembalian</div>
                            <div style="font-weight: 600; color: #374151; font-size: 14px;">Rp {{ number_format($transaction->change, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Points Earned -->
            @if($transaction->points_earned > 0)
            <div class="card" style="background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%); border: 2px solid #FBBF24;">
                <div style="text-align: center;">
                    <div style="width: 64px; height: 64px; background: #F59E0B; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                        <i class="fas fa-coins" style="font-size: 32px; color: white;"></i>
                    </div>
                    <h3 style="font-size: 18px; font-weight: 700; color: #92400E; margin-bottom: 8px;">Poin Didapat</h3>
                    <div style="font-size: 40px; font-weight: 700; color: #B45309; margin-bottom: 8px;">+{{ $transaction->points_earned }}</div>
                    <p style="font-size: 13px; color: #92400E;">Terimakasih telah berbelanja!</p>
                </div>
            </div>
            @endif

            <!-- Transaction Notes -->
            @if($transaction->notes)
            <div class="card" style="margin-top: 24px; background: #F9FAFB;">
                <h3 style="font-size: 16px; font-weight: 700; color: #374151; margin-bottom: 12px;">
                    <i class="fas fa-sticky-note mr-2"></i>Catatan
                </h3>
                <p style="color: #6B7280; font-size: 14px; line-height: 1.6;">{{ $transaction->notes }}</p>
            </div>
            @endif

            <!-- Print Button -->
            <button onclick="window.print()" class="btn btn-secondary" style="width: 100%; margin-top: 24px; padding: 14px;">
                <i class="fas fa-print mr-2"></i>
                <span>Cetak Struk</span>
            </button>
        </div>
    </div>
</div>

<style>
@media print {
    .sidebar,
    .topbar,
    .breadcrumb,
    .btn,
    a[href*="kembali"] {
        display: none !important;
    }

    .main-content {
        margin-left: 0 !important;
    }

    body {
        background: white !important;
    }

    .card {
        box-shadow: none !important;
        page-break-inside: avoid;
    }
}
</style>
</div>
@endsection
