@extends('layouts.modern')

@section('title', 'Dashboard Admin')
@section('breadcrumb')
    <i class="fas fa-home mr-2"></i>
    <span class="breadcrumb-active">Dashboard</span>
@endsection

@section('content')
<div class="fade-in">
    <!-- Welcome Section -->
    <div class="card" style="background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%); margin-bottom: 32px;">
        <div style="display: flex; justify-content: space-between; align-items: center; color: white;">
            <div>
                <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">
                    Selamat datang, Administrator
                </div>
                <h1 style="font-size: 36px; font-weight: 700; margin-bottom: 8px;">
                    {{ auth()->user()->name }}
                </h1>
                <p style="opacity: 0.9;">Kelola sistem POS Anda dengan mudah</p>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 14px; opacity: 0.9;">{{ now()->isoFormat('dddd, D MMMM Y') }}</div>
                <div style="font-size: 40px; font-weight: 700; margin-top: 4px;">{{ now()->format('H:i') }}</div>
            </div>
        </div>
    </div>

    <!-- Main Stats Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 32px;">
        <!-- Total Sales -->
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #D1FAE5 0%, #A7F3D0 100%); color: #065F46;">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-label">Total Penjualan Hari Ini</div>
            <div class="stat-value">Rp {{ number_format($today_sales ?? 0, 0, ',', '.') }}</div>
            <div style="margin-top: 12px; font-size: 13px; color: #10B981; font-weight: 600;">
                <i class="fas fa-arrow-up mr-1"></i> +8.2% dari kemarin
            </div>
        </div>

        <!-- Transactions -->
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #DBEAFE 0%, #BFDBFE 100%); color: #1E40AF;">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="stat-label">Total Transaksi</div>
            <div class="stat-value">{{ $today_transactions ?? 0 }}</div>
            <div style="margin-top: 12px; font-size: 13px; color: #6B7280; font-weight: 600;">
                <i class="fas fa-clock mr-1"></i> Hari ini
            </div>
        </div>

        <!-- Products -->
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #E9D5FF 0%, #D8B4FE 100%); color: #6B21A8;">
                <i class="fas fa-box"></i>
            </div>
            <div class="stat-label">Total Produk</div>
            <div class="stat-value">{{ $total_products ?? 0 }}</div>
            <div style="margin-top: 12px; font-size: 13px; color: #6B7280; font-weight: 600;">
                <i class="fas fa-check-circle mr-1"></i> {{ $active_products ?? 0 }} Aktif
            </div>
        </div>

        <!-- Members -->
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #FED7AA 0%, #FDBA74 100%); color: #92400E;">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-label">Total Member</div>
            <div class="stat-value">{{ $total_members ?? 0 }}</div>
            <div style="margin-top: 12px; font-size: 13px; color: #10B981; font-weight: 600;">
                <i class="fas fa-user-plus mr-1"></i> +{{ $new_members_this_month ?? 0 }} bulan ini
            </div>
        </div>
    </div>

    <!-- Charts & Recent Transactions -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; margin-bottom: 32px;">
        <!-- Sales Chart -->
        <div class="card">
            <div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h2 style="font-size: 20px; font-weight: 700; color: #111827; margin-bottom: 4px;">
                        <i class="fas fa-chart-line" style="color: var(--primary); margin-right: 8px;"></i>
                        Grafik Penjualan
                    </h2>
                    <p style="font-size: 14px; color: #6B7280;">Penjualan 7 hari terakhir</p>
                </div>
                <select style="padding: 8px 12px; border: 2px solid #E5E7EB; border-radius: 8px; font-size: 14px; font-weight: 600;">
                    <option>7 Hari</option>
                    <option>30 Hari</option>
                    <option>3 Bulan</option>
                </select>
            </div>
            <div style="height: 300px; display: flex; align-items: end; justify-content: space-around; border-bottom: 2px solid #E5E7EB; gap: 8px;">
                @php
                $sampleData = [65, 85, 70, 90, 78, 95, 88];
                $days = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
                @endphp
                @foreach($sampleData as $index => $value)
                <div style="flex: 1; display: flex; flex-direction: column; align-items: center;">
                    <div style="width: 100%; background: linear-gradient(180deg, #4F46E5 0%, #6366F1 100%); border-radius: 8px 8px 0 0; height: {{ $value }}%; position: relative; transition: all 0.3s;" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
                        <div style="position: absolute; top: -30px; left: 50%; transform: translateX(-50%); background: #111827; color: white; padding: 4px 8px; border-radius: 6px; font-size: 11px; font-weight: 600; white-space: nowrap;">
                            Rp {{ number_format($value * 100000, 0, ',', '.') }}
                        </div>
                    </div>
                    <div style="margin-top: 12px; font-size: 13px; font-weight: 600; color: #6B7280;">{{ $days[$index] }}</div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Top Products -->
        <div class="card">
            <h2 style="font-size: 18px; font-weight: 700; color: #111827; margin-bottom: 20px;">
                <i class="fas fa-fire" style="color: #EF4444; margin-right: 8px;"></i>
                Produk Terlaris
            </h2>
            <div style="display: flex; flex-direction: column; gap: 16px;">
                @php
                $topProducts = [
                    ['name' => 'Indomie Goreng', 'sold' => 245, 'revenue' => 735000],
                    ['name' => 'Aqua 600ml', 'sold' => 198, 'revenue' => 594000],
                    ['name' => 'Teh Botol', 'sold' => 156, 'revenue' => 468000],
                    ['name' => 'Pocari Sweat', 'sold' => 132, 'revenue' => 660000],
                ];
                @endphp
                @foreach($topProducts as $index => $product)
                <div style="display: flex; align-items: center; gap: 12px; padding: 12px; background: #F9FAFB; border-radius: 12px; transition: all 0.2s;" onmouseover="this.style.background='#F3F4F6'" onmouseout="this.style.background='#F9FAFB'">
                    <div style="width: 32px; height: 32px; background: linear-gradient(135deg, #4F46E5 0%, #6366F1 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; flex-shrink: 0;">
                        {{ $index + 1 }}
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-weight: 600; font-size: 14px; color: #111827; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $product['name'] }}</div>
                        <div style="font-size: 12px; color: #6B7280;">{{ $product['sold'] }} terjual • Rp {{ number_format($product['revenue'], 0, ',', '.') }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h2 style="font-size: 20px; font-weight: 700; color: #111827;">
                <i class="fas fa-history" style="color: var(--primary); margin-right: 8px;"></i>
                Transaksi Terbaru
            </h2>
            <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary" style="padding: 10px 20px; font-size: 14px;">
                <span>Lihat Semua</span>
                <i class="fas fa-arrow-right"></i>
            </a>
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
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recent_transactions ?? [] as $transaction)
                    <tr>
                        <td>
                            <div style="font-weight: 600; color: #4F46E5;">{{ $transaction->transaction_code }}</div>
                        </td>
                        <td>
                            <div style="font-size: 14px; color: #374151;">{{ $transaction->transaction_date->format('d/m/Y H:i') }}</div>
                        </td>
                        <td>
                            <div style="font-size: 14px; color: #374151;">{{ $transaction->cashier->name ?? '-' }}</div>
                        </td>
                        <td>
                            @if($transaction->member)
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <div style="width: 32px; height: 32px; background: linear-gradient(135deg, #4F46E5 0%, #6366F1 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 12px;">
                                        {{ strtoupper(substr($transaction->member->user->name, 0, 1)) }}
                                    </div>
                                    <span style="font-size: 14px; color: #374151;">{{ $transaction->member->user->name }}</span>
                                </div>
                            @else
                                <span class="badge badge-secondary">Guest</span>
                            @endif
                        </td>
                        <td>
                            <div style="font-weight: 700; color: #111827;">Rp {{ number_format($transaction->total, 0, ',', '.') }}</div>
                        </td>
                        <td>
                            @if($transaction->status === 'completed')
                                <span class="badge badge-success">
                                    <i class="fas fa-check-circle mr-1"></i>Selesai
                                </span>
                            @elseif($transaction->status === 'pending')
                                <span class="badge badge-warning">
                                    <i class="fas fa-clock mr-1"></i>Pending
                                </span>
                            @else
                                <span class="badge badge-danger">
                                    <i class="fas fa-times-circle mr-1"></i>Batal
                                </span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.transactions.show', $transaction->id) }}" class="btn btn-secondary" style="padding: 6px 12px; font-size: 12px;">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 48px; color: #6B7280;">
                            <i class="fas fa-inbox" style="font-size: 48px; color: #D1D5DB; margin-bottom: 16px; display: block;"></i>
                            <div style="font-size: 16px; font-weight: 600; margin-bottom: 4px;">Belum ada transaksi</div>
                            <div style="font-size: 14px;">Transaksi akan muncul di sini</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Links -->
    <div style="margin-top: 32px; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
        <a href="{{ route('admin.products.create') }}" class="card card-hover" style="text-decoration: none; text-align: center; padding: 20px;">
            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #10B981 0%, #059669 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
                <i class="fas fa-plus" style="font-size: 24px; color: white;"></i>
            </div>
            <div style="font-weight: 700; color: #111827; margin-bottom: 4px;">Tambah Produk</div>
            <div style="font-size: 13px; color: #6B7280;">Produk baru</div>
        </a>

        <a href="{{ route('admin.users.create') }}" class="card card-hover" style="text-decoration: none; text-align: center; padding: 20px;">
            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
                <i class="fas fa-user-plus" style="font-size: 24px; color: white;"></i>
            </div>
            <div style="font-weight: 700; color: #111827; margin-bottom: 4px;">Tambah User</div>
            <div style="font-size: 13px; color: #6B7280;">Admin/Kasir baru</div>
        </a>

        <a href="{{ route('admin.reports.index') }}" class="card card-hover" style="text-decoration: none; text-align: center; padding: 20px;">
            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
                <i class="fas fa-chart-bar" style="font-size: 24px; color: white;"></i>
            </div>
            <div style="font-weight: 700; color: #111827; margin-bottom: 4px;">Laporan</div>
            <div style="font-size: 13px; color: #6B7280;">Lihat laporan</div>
        </a>

        <a href="{{ route('admin.settings.index') }}" class="card card-hover" style="text-decoration: none; text-align: center; padding: 20px;">
            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #6B7280 0%, #4B5563 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
                <i class="fas fa-cog" style="font-size: 24px; color: white;"></i>
            </div>
            <div style="font-weight: 700; color: #111827; margin-bottom: 4px;">Pengaturan</div>
            <div style="font-size: 13px; color: #6B7280;">Konfigurasi sistem</div>
        </a>
    </div>
</div>
@endsection
