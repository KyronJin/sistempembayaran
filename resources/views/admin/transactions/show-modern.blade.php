@extends('layouts.modern')

@section('title', 'Detail Transaksi')

@section('content')
<div class="fade-in">
    <!-- Back Button & Header -->
    <div style="margin-bottom: 32px;">
        <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary" style="margin-bottom: 16px;">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali</span>
        </a>
        
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="font-size: 28px; font-weight: 700; color: var(--dark-gray); margin-bottom: 8px;">
                    <i class="fas fa-receipt" style="color: var(--cream); margin-right: 12px;"></i>
                    Detail Transaksi
                </h1>
                <p style="color: #6B7280; font-size: 14px;">{{ $transaction->transaction_code }}</p>
            </div>
            
            <div>
                @if($transaction->status === 'completed')
                    <span class="badge badge-success" style="padding: 8px 16px; font-size: 14px;">
                        <i class="fas fa-check-circle"></i> Selesai
                    </span>
                @elseif($transaction->status === 'pending')
                    <span class="badge badge-warning" style="padding: 8px 16px; font-size: 14px;">
                        <i class="fas fa-clock"></i> Pending
                    </span>
                @else
                    <span class="badge badge-danger" style="padding: 8px 16px; font-size: 14px;">
                        <i class="fas fa-times-circle"></i> Dibatalkan
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
        <!-- Left Column: Transaction Details -->
        <div>
            <!-- Transaction Info -->
            <div class="card" style="margin-bottom: 24px;">
                <div style="padding: 24px; border-bottom: 1px solid rgba(221, 208, 200, 0.3);">
                    <h2 style="font-size: 18px; font-weight: 700; color: var(--dark-gray);">
                        <i class="fas fa-info-circle" style="color: var(--cream); margin-right: 8px;"></i>
                        Informasi Transaksi
                    </h2>
                </div>
                <div style="padding: 24px;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                        <div>
                            <div style="color: #6B7280; font-size: 13px; margin-bottom: 6px;">Kode Transaksi</div>
                            <div style="color: var(--dark-gray); font-weight: 600; font-size: 15px;">{{ $transaction->transaction_code }}</div>
                        </div>
                        <div>
                            <div style="color: #6B7280; font-size: 13px; margin-bottom: 6px;">Tanggal & Waktu</div>
                            <div style="color: var(--dark-gray); font-weight: 600; font-size: 15px;">
                                {{ $transaction->transaction_date->format('d M Y, H:i') }}
                            </div>
                        </div>
                        <div>
                            <div style="color: #6B7280; font-size: 13px; margin-bottom: 6px;">Kasir</div>
                            <div style="color: var(--dark-gray); font-weight: 600; font-size: 15px;">
                                <i class="fas fa-user-tie" style="color: var(--cream); margin-right: 6px;"></i>
                                {{ $transaction->cashier->name }}
                            </div>
                        </div>
                        <div>
                            <div style="color: #6B7280; font-size: 13px; margin-bottom: 6px;">Member</div>
                            <div style="color: var(--dark-gray); font-weight: 600; font-size: 15px;">
                                @if($transaction->member)
                                    <i class="fas fa-user" style="color: var(--cream); margin-right: 6px;"></i>
                                    {{ $transaction->member->user->name }}
                                @else
                                    <span style="color: #9CA3AF;">Guest</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items -->
            <div class="card">
                <div style="padding: 24px; border-bottom: 1px solid rgba(221, 208, 200, 0.3);">
                    <h2 style="font-size: 18px; font-weight: 700; color: var(--dark-gray);">
                        <i class="fas fa-shopping-cart" style="color: var(--cream); margin-right: 8px;"></i>
                        Item Transaksi
                    </h2>
                </div>
                <div style="overflow-x: auto;">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaction->details as $detail)
                            <tr>
                                <td>
                                    <div style="font-weight: 600; color: var(--dark-gray); margin-bottom: 4px;">
                                        {{ $detail->product->name }}
                                    </div>
                                    <div style="font-size: 12px; color: #9CA3AF;">
                                        SKU: {{ $detail->product->sku }}
                                    </div>
                                </td>
                                <td>Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                                <td>
                                    <span style="background: var(--light-cream); padding: 4px 12px; border-radius: 6px; font-weight: 600; color: var(--dark-gray);">
                                        {{ $detail->quantity }}
                                    </span>
                                </td>
                                <td>
                                    <strong style="color: var(--dark-gray);">
                                        Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                    </strong>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Column: Payment Summary -->
        <div>
            <div class="card">
                <div style="padding: 24px; border-bottom: 1px solid rgba(221, 208, 200, 0.3);">
                    <h2 style="font-size: 18px; font-weight: 700; color: var(--dark-gray);">
                        <i class="fas fa-calculator" style="color: var(--cream); margin-right: 8px;"></i>
                        Ringkasan Pembayaran
                    </h2>
                </div>
                <div style="padding: 24px;">
                    <div style="margin-bottom: 16px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                            <span style="color: #6B7280;">Subtotal</span>
                            <span style="color: var(--dark-gray); font-weight: 600;">
                                Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}
                            </span>
                        </div>
                        
                        @if($transaction->discount > 0)
                        <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                            <span style="color: #6B7280;">Diskon</span>
                            <span style="color: #EF4444; font-weight: 600;">
                                - Rp {{ number_format($transaction->discount, 0, ',', '.') }}
                            </span>
                        </div>
                        @endif
                        
                        @if($transaction->tax > 0)
                        <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                            <span style="color: #6B7280;">Pajak</span>
                            <span style="color: var(--dark-gray); font-weight: 600;">
                                Rp {{ number_format($transaction->tax, 0, ',', '.') }}
                            </span>
                        </div>
                        @endif
                    </div>
                    
                    <div style="border-top: 2px solid rgba(221, 208, 200, 0.3); padding-top: 16px; margin-bottom: 24px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-size: 18px; font-weight: 700; color: var(--dark-gray);">Total</span>
                            <span style="font-size: 24px; font-weight: 700; color: var(--dark-gray);">
                                Rp {{ number_format($transaction->total, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                    
                    <div style="background: var(--light-cream); padding: 16px; border-radius: 12px; margin-bottom: 16px;">
                        <div style="color: #6B7280; font-size: 13px; margin-bottom: 8px;">Metode Pembayaran</div>
                        <div style="color: var(--dark-gray); font-weight: 600; font-size: 16px; text-transform: capitalize;">
                            @if($transaction->payment_method === 'cash')
                                <i class="fas fa-money-bill-wave" style="color: #10B981; margin-right: 8px;"></i>
                                Tunai
                            @elseif($transaction->payment_method === 'qris')
                                <i class="fas fa-qrcode" style="color: #6366F1; margin-right: 8px;"></i>
                                QRIS
                            @else
                                <i class="fas fa-credit-card" style="color: #F59E0B; margin-right: 8px;"></i>
                                {{ $transaction->payment_method }}
                            @endif
                        </div>
                    </div>
                    
                    @if($transaction->payment_method === 'cash' && $transaction->payment_amount)
                    <div style="background: var(--light-cream); padding: 16px; border-radius: 12px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <span style="color: #6B7280; font-size: 13px;">Dibayar</span>
                            <span style="color: var(--dark-gray); font-weight: 600;">
                                Rp {{ number_format($transaction->payment_amount, 0, ',', '.') }}
                            </span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: #6B7280; font-size: 13px;">Kembalian</span>
                            <span style="color: #10B981; font-weight: 700; font-size: 16px;">
                                Rp {{ number_format($transaction->change_amount ?? 0, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
