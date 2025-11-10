@extends('layouts.modern')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="fade-in">
    <!-- Page Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
        <div>
            <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary" style="margin-bottom: 12px; display: inline-flex;">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
            <h1 style="font-size: 28px; font-weight: 700; color: var(--dark-gray); margin-bottom: 8px;">
                <i class="fas fa-cash-register" style="color: var(--cream); margin-right: 12px;"></i>
                Laporan Penjualan
            </h1>
            <p style="color: #6B7280; font-size: 14px;">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
        </div>
        <div style="display: flex; gap: 12px;">
            <button onclick="window.print()" class="btn btn-secondary no-print">
                <i class="fas fa-print"></i>
                <span>Cetak</span>
            </button>
            <button onclick="exportToExcel()" class="btn btn-primary no-print">
                <i class="fas fa-file-excel"></i>
                <span>Export Excel</span>
            </button>
        </div>
    </div>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('admin.reports.sales') }}" class="card no-print" style="margin-bottom: 32px;">
        <div style="padding: 24px; border-bottom: 1px solid rgba(221, 208, 200, 0.3);">
            <h2 style="font-size: 16px; font-weight: 700; color: var(--dark-gray);">
                <i class="fas fa-filter" style="color: var(--cream); margin-right: 8px;"></i>
                Filter Laporan
            </h2>
        </div>
        <div style="padding: 24px;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; color: #6B7280; font-size: 13px; font-weight: 600; margin-bottom: 8px;">
                        <i class="far fa-calendar" style="margin-right: 6px;"></i>
                        Tanggal Mulai
                    </label>
                    <input type="date" 
                           name="start_date" 
                           value="{{ request('start_date', \Carbon\Carbon::parse($startDate)->format('Y-m-d')) }}" 
                           class="form-input"
                           style="width: 100%; padding: 10px 14px; border: 2px solid rgba(221, 208, 200, 0.5); border-radius: 10px; font-size: 14px;">
                </div>
                <div>
                    <label style="display: block; color: #6B7280; font-size: 13px; font-weight: 600; margin-bottom: 8px;">
                        <i class="far fa-calendar" style="margin-right: 6px;"></i>
                        Tanggal Akhir
                    </label>
                    <input type="date" 
                           name="end_date" 
                           value="{{ request('end_date', \Carbon\Carbon::parse($endDate)->format('Y-m-d')) }}" 
                           class="form-input"
                           style="width: 100%; padding: 10px 14px; border: 2px solid rgba(221, 208, 200, 0.5); border-radius: 10px; font-size: 14px;">
                </div>
                <div>
                    <label style="display: block; color: #6B7280; font-size: 13px; font-weight: 600; margin-bottom: 8px;">
                        <i class="fas fa-user" style="margin-right: 6px;"></i>
                        Member
                    </label>
                    <select name="member_id" 
                            class="form-input"
                            style="width: 100%; padding: 10px 14px; border: 2px solid rgba(221, 208, 200, 0.5); border-radius: 10px; font-size: 14px;">
                        <option value="">Semua Member</option>
                        @foreach($members as $member)
                        <option value="{{ $member->id }}" {{ request('member_id') == $member->id ? 'selected' : '' }}>
                            {{ $member->user->name ?? $member->member_code }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display: block; color: #6B7280; font-size: 13px; font-weight: 600; margin-bottom: 8px;">
                        <i class="fas fa-box" style="margin-right: 6px;"></i>
                        Produk
                    </label>
                    <select name="product_id" 
                            class="form-input"
                            style="width: 100%; padding: 10px 14px; border: 2px solid rgba(221, 208, 200, 0.5); border-radius: 10px; font-size: 14px;">
                        <option value="">Semua Produk</option>
                        @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div style="display: flex; gap: 12px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i>
                    <span>Terapkan Filter</span>
                </button>
                <a href="{{ route('admin.reports.sales') }}" class="btn btn-secondary">
                    <i class="fas fa-undo"></i>
                    <span>Reset</span>
                </a>
            </div>
        </div>
    </form>

    <!-- Stats Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; margin-bottom: 32px;">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(221, 208, 200, 0.2);">
                <i class="fas fa-money-bill-wave" style="color: var(--cream);"></i>
            </div>
            <div class="stat-label">Total Penjualan</div>
            <div class="stat-value" style="color: #10B981;">Rp {{ number_format($totalSales, 0, ',', '.') }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(99, 102, 241, 0.2);">
                <i class="fas fa-receipt" style="color: #6366F1;"></i>
            </div>
            <div class="stat-label">Total Transaksi</div>
            <div class="stat-value">{{ $totalTransactions }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(245, 158, 11, 0.2);">
                <i class="fas fa-chart-line" style="color: #F59E0B;"></i>
            </div>
            <div class="stat-label">Rata-rata Transaksi</div>
            <div class="stat-value" style="font-size: 20px;">
                Rp {{ $totalTransactions > 0 ? number_format($totalSales / $totalTransactions, 0, ',', '.') : 0 }}
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="card">
        <div style="padding: 24px; border-bottom: 1px solid rgba(221, 208, 200, 0.3);">
            <h2 style="font-size: 18px; font-weight: 700; color: var(--dark-gray);">
                <i class="fas fa-list" style="color: var(--cream); margin-right: 8px;"></i>
                Detail Transaksi
            </h2>
        </div>

        <div style="overflow-x: auto;">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Kode Transaksi</th>
                        <th>Kasir</th>
                        <th>Member</th>
                        <th>Total</th>
                        <th>Pembayaran</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                    <tr>
                        <td>
                            <div style="font-size: 14px; color: var(--dark-gray);">
                                {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d M Y') }}
                            </div>
                            <div style="font-size: 12px; color: #9CA3AF;">
                                {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('H:i') }}
                            </div>
                        </td>
                        <td>
                            <strong style="color: var(--dark-gray);">{{ $transaction->transaction_code }}</strong>
                        </td>
                        <td>
                            <div style="font-size: 14px; color: var(--dark-gray);">
                                <i class="fas fa-user-tie" style="color: var(--cream); margin-right: 6px;"></i>
                                {{ $transaction->cashier?->name ?? '-' }}
                            </div>
                        </td>
                        <td>
                            @if($transaction->member)
                                <div style="font-size: 14px; color: var(--dark-gray);">
                                    <i class="fas fa-user" style="color: var(--cream); margin-right: 6px;"></i>
                                    {{ $transaction->member->user->name }}
                                </div>
                            @else
                                <span style="color: #9CA3AF;">Guest</span>
                            @endif
                        </td>
                        <td>
                            <strong style="color: #10B981; font-size: 15px;">
                                Rp {{ number_format($transaction->total, 0, ',', '.') }}
                            </strong>
                        </td>
                        <td style="text-transform: capitalize; font-size: 14px;">
                            @if($transaction->payment_method === 'cash')
                                <i class="fas fa-money-bill-wave" style="color: #10B981; margin-right: 6px;"></i>
                                Tunai
                            @elseif($transaction->payment_method === 'qris')
                                <i class="fas fa-qrcode" style="color: #6366F1; margin-right: 6px;"></i>
                                QRIS
                            @else
                                <i class="fas fa-credit-card" style="color: #F59E0B; margin-right: 6px;"></i>
                                {{ $transaction->payment_method }}
                            @endif
                        </td>
                        <td>
                            @if($transaction->status === 'completed')
                                <span class="badge badge-success">
                                    <i class="fas fa-check-circle"></i> Selesai
                                </span>
                            @elseif($transaction->status === 'pending')
                                <span class="badge badge-warning">
                                    <i class="fas fa-clock"></i> Pending
                                </span>
                            @else
                                <span class="badge badge-danger">
                                    <i class="fas fa-times-circle"></i> Batal
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 48px; color: #9CA3AF;">
                            <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                            <div style="font-size: 16px;">Tidak ada data transaksi untuk periode ini</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
@media print {
    .no-print {
        display: none !important;
    }
    
    body {
        background: white !important;
    }
    
    .sidebar, nav, footer {
        display: none !important;
    }
    
    .main-content {
        margin-left: 0 !important;
        padding: 20px !important;
    }
    
    .card {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
    }
    
    @page {
        margin: 1cm;
    }
}

.form-input:focus {
    outline: none;
    border-color: var(--cream) !important;
}
</style>

<script>
function exportToExcel() {
    alert('Fitur export Excel akan segera tersedia');
    // TODO: Implement Excel export functionality
}
</script>
@endsection
