@extends('layouts.modern')

@section('title', 'Kelola Transaksi')

@section('content')
<div class="fade-in">
    <!-- Page Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
        <div>
            <h1 style="font-size: 28px; font-weight: 700; color: var(--dark-gray); margin-bottom: 8px;">
                <i class="fas fa-receipt" style="color: var(--cream); margin-right: 12px;"></i>
                Kelola Transaksi
            </h1>
            <p style="color: #6B7280; font-size: 14px;">Daftar semua transaksi yang telah dilakukan</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 24px; margin-bottom: 32px;">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(221, 208, 200, 0.2);">
                <i class="fas fa-shopping-cart" style="color: var(--cream);"></i>
            </div>
            <div class="stat-label">Total Transaksi</div>
            <div class="stat-value">{{ $transactions->total() }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(16, 185, 129, 0.2);">
                <i class="fas fa-check-circle" style="color: #10B981;"></i>
            </div>
            <div class="stat-label">Transaksi Selesai</div>
            <div class="stat-value">{{ $transactions->where('status', 'completed')->count() }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(239, 68, 68, 0.2);">
                <i class="fas fa-times-circle" style="color: #EF4444;"></i>
            </div>
            <div class="stat-label">Transaksi Dibatalkan</div>
            <div class="stat-value">{{ $transactions->where('status', 'cancelled')->count() }}</div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="card">
        <div style="padding: 24px; border-bottom: 1px solid rgba(221, 208, 200, 0.3);">
            <h2 style="font-size: 18px; font-weight: 700; color: var(--dark-gray);">
                <i class="fas fa-list" style="color: var(--cream); margin-right: 8px;"></i>
                Daftar Transaksi
            </h2>
        </div>

        <div style="overflow-x: auto;">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Kode Transaksi</th>
                        <th>Tanggal</th>
                        <th>Kasir</th>
                        <th>Member</th>
                        <th>Total</th>
                        <th>Pembayaran</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                    <tr>
                        <td>
                            <strong style="color: var(--dark-gray);">{{ $transaction->transaction_code }}</strong>
                        </td>
                        <td>
                            <div style="font-size: 14px; color: #6B7280;">
                                <i class="far fa-calendar" style="margin-right: 6px;"></i>
                                {{ $transaction->transaction_date->format('d M Y') }}
                            </div>
                            <div style="font-size: 12px; color: #9CA3AF;">
                                {{ $transaction->transaction_date->format('H:i') }}
                            </div>
                        </td>
                        <td>
                            <div style="font-size: 14px; color: var(--dark-gray);">
                                <i class="fas fa-user-tie" style="color: var(--cream); margin-right: 6px;"></i>
                                {{ $transaction->cashier->name }}
                            </div>
                        </td>
                        <td>
                            @if($transaction->member)
                                <div style="font-size: 14px; color: var(--dark-gray);">
                                    <i class="fas fa-user" style="color: var(--cream); margin-right: 6px;"></i>
                                    {{ $transaction->member->user->name }}
                                </div>
                            @else
                                <span style="color: #9CA3AF; font-size: 14px;">Guest</span>
                            @endif
                        </td>
                        <td>
                            <strong style="color: var(--dark-gray); font-size: 15px;">
                                Rp {{ number_format($transaction->total, 0, ',', '.') }}
                            </strong>
                        </td>
                        <td>
                            <div style="font-size: 14px; color: #6B7280; text-transform: capitalize;">
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
                            </div>
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
                                    <i class="fas fa-times-circle"></i> Dibatalkan
                                </span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <a href="{{ route('admin.transactions.show', $transaction->id) }}" 
                                   class="btn btn-secondary" 
                                   style="padding: 8px 12px; font-size: 13px;"
                                   title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 48px; color: #9CA3AF;">
                            <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                            <div style="font-size: 16px;">Belum ada transaksi</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
        <div style="padding: 24px; border-top: 1px solid rgba(221, 208, 200, 0.3);">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
