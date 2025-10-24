@extends('layouts.modern')

@section('title', 'Riwayat Transaksi')

@section('content')
<div class="content-header">
    <h1>Riwayat Transaksi</h1>
    <p>Lihat semua transaksi yang telah diproses</p>
</div>

<!-- Filter & Search -->
<div class="modern-card" style="margin-bottom: 2rem;">
    <form method="GET" style="display: flex; gap: 1rem; flex-wrap: wrap;">
        <input type="text" name="search" class="form-input" placeholder="🔍 Cari kode transaksi..." value="{{ request('search') }}" style="flex: 1; min-width: 250px;">
        <input type="date" name="date" class="form-input" value="{{ request('date') }}" style="min-width: 180px;">
        <select name="payment_method" class="form-input" style="min-width: 150px;">
            <option value="">Semua Pembayaran</option>
            <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Tunai</option>
            <option value="card" {{ request('payment_method') == 'card' ? 'selected' : '' }}>Kartu</option>
            <option value="qris" {{ request('payment_method') == 'qris' ? 'selected' : '' }}>QRIS</option>
        </select>
        <button type="submit" class="btn-primary">
            <i class="fas fa-filter"></i> Filter
        </button>
        @if(request()->hasAny(['search', 'date', 'payment_method']))
        <a href="{{ route('kasir.transactions') }}" class="btn-secondary">
            <i class="fas fa-times"></i> Reset
        </a>
        @endif
    </form>
</div>

<!-- Transactions Table -->
<div class="modern-card">
    <div class="table-responsive">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>Kode Transaksi</th>
                    <th>Tanggal & Waktu</th>
                    <th>Member</th>
                    <th>Item</th>
                    <th style="text-align: right;">Total</th>
                    <th style="text-align: center;">Pembayaran</th>
                    <th style="text-align: center; width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $transaction)
                <tr>
                    <td><strong>{{ $transaction->transaction_code }}</strong></td>
                    <td>
                        <div>{{ $transaction->transaction_date->format('d M Y') }}</div>
                        <div style="font-size: 0.875rem; color: #6b7280;">{{ $transaction->transaction_date->format('H:i') }}</div>
                    </td>
                    <td>
                        @if($transaction->member)
                        <div style="font-weight: 600; color: #1f2937;">{{ $transaction->member->name }}</div>
                        <div style="font-size: 0.875rem; color: #6b7280;">{{ $transaction->member->member_code }}</div>
                        @else
                        <span style="color: #9ca3af;">Guest</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            {{ $transaction->details->count() }} item
                        </span>
                    </td>
                    <td style="text-align: right; font-weight: 700; color: #059669;">
                        Rp {{ number_format($transaction->final_total, 0, ',', '.') }}
                    </td>
                    <td style="text-align: center;">
                        @foreach($transaction->payments as $payment)
                        <span class="badge" style="background: #10b981; margin: 2px;">
                            @if($payment->payment_method === 'cash')
                            <i class="fas fa-money-bill"></i> Tunai
                            @elseif($payment->payment_method === 'card')
                            <i class="fas fa-credit-card"></i> Kartu
                            @elseif($payment->payment_method === 'qris')
                            <i class="fas fa-qrcode"></i> QRIS
                            @else
                            {{ ucfirst($payment->payment_method) }}
                            @endif
                        </span>
                        @endforeach
                    </td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 0.5rem; justify-content: center;">
                            <a href="{{ route('kasir.get-transaction', $transaction->id) }}" class="btn-icon" title="Detail" target="_blank">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('kasir.print-receipt', $transaction->id) }}" class="btn-icon" style="background: #dbeafe; color: #1e40af;" title="Print" target="_blank">
                                <i class="fas fa-print"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 3rem;">
                        <div style="opacity: 0.5;">
                            <i class="fas fa-receipt" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                            <p>Belum ada transaksi</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($transactions->hasPages())
    <div style="padding: 1.5rem; border-top: 1px solid #e5e7eb;">
        {{ $transactions->links() }}
    </div>
    @endif
</div>

<!-- Summary Stats -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-top: 2rem;">
    <div class="modern-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
        <div style="text-align: center;">
            <div style="font-size: 0.875rem; opacity: 0.9; margin-bottom: 0.5rem;">Total Transaksi Hari Ini</div>
            <div style="font-size: 2rem; font-weight: 700;">{{ $daily_count }}</div>
        </div>
    </div>
    
    <div class="modern-card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white;">
        <div style="text-align: center;">
            <div style="font-size: 0.875rem; opacity: 0.9; margin-bottom: 0.5rem;">Penjualan Hari Ini</div>
            <div style="font-size: 1.5rem; font-weight: 700;">Rp {{ number_format($daily_total, 0, ',', '.') }}</div>
        </div>
    </div>
</div>
@endsection
