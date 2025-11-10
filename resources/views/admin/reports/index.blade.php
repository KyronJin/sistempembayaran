@extends('layouts.modern')

@section('title', 'Laporan')

@section('content')
<div class="fade-in">
    <!-- Page Header -->
    <div style="margin-bottom: 32px;">
        <h1 style="font-size: 28px; font-weight: 700; color: var(--dark-gray); margin-bottom: 8px;">
            <i class="fas fa-chart-bar" style="color: var(--cream); margin-right: 12px;"></i>
            Laporan
        </h1>
        <p style="color: #6B7280; font-size: 14px;">Kelola dan lihat berbagai laporan bisnis</p>
    </div>

    <!-- Report Cards Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px;">
        <!-- Sales Report Card -->
        <a href="{{ route('admin.reports.sales') }}" class="card feature-card" style="text-decoration: none; display: block; padding: 32px; transition: all 0.3s;">
            <div style="display: flex; align-items: flex-start; gap: 20px;">
                <div style="width: 64px; height: 64px; border-radius: 16px; background: rgba(221, 208, 200, 0.2); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="fas fa-cash-register" style="font-size: 28px; color: var(--cream);"></i>
                </div>
                <div style="flex: 1;">
                    <h3 style="font-size: 18px; font-weight: 700; color: var(--dark-gray); margin-bottom: 8px;">
                        Laporan Penjualan
                    </h3>
                    <p style="color: #6B7280; font-size: 14px; line-height: 1.6; margin-bottom: 12px;">
                        Lihat ringkasan transaksi, total penjualan, dan analisis dalam rentang tanggal tertentu
                    </p>
                    <div style="display: flex; align-items: center; color: var(--cream); font-weight: 600; font-size: 14px;">
                        <span>Buka Laporan</span>
                        <i class="fas fa-arrow-right" style="margin-left: 8px; transition: transform 0.3s;"></i>
                    </div>
                </div>
            </div>
        </a>

        <!-- Product Performance Card -->
        <div class="card" style="padding: 32px; opacity: 0.6;">
            <div style="display: flex; align-items: flex-start; gap: 20px;">
                <div style="width: 64px; height: 64px; border-radius: 16px; background: rgba(221, 208, 200, 0.2); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="fas fa-box" style="font-size: 28px; color: var(--cream);"></i>
                </div>
                <div style="flex: 1;">
                    <h3 style="font-size: 18px; font-weight: 700; color: var(--dark-gray); margin-bottom: 8px;">
                        Laporan Produk
                        <span class="badge badge-warning" style="margin-left: 8px; font-size: 11px;">Segera Hadir</span>
                    </h3>
                    <p style="color: #6B7280; font-size: 14px; line-height: 1.6;">
                        Analisis performa produk terlaris, stok, dan margin keuntungan
                    </p>
                </div>
            </div>
        </div>

        <!-- Member Activity Card -->
        <div class="card" style="padding: 32px; opacity: 0.6;">
            <div style="display: flex; align-items: flex-start; gap: 20px;">
                <div style="width: 64px; height: 64px; border-radius: 16px; background: rgba(221, 208, 200, 0.2); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="fas fa-users" style="font-size: 28px; color: var(--cream);"></i>
                </div>
                <div style="flex: 1;">
                    <h3 style="font-size: 18px; font-weight: 700; color: var(--dark-gray); margin-bottom: 8px;">
                        Laporan Member
                        <span class="badge badge-warning" style="margin-left: 8px; font-size: 11px;">Segera Hadir</span>
                    </h3>
                    <p style="color: #6B7280; font-size: 14px; line-height: 1.6;">
                        Aktivitas member, poin terkumpul, dan tingkat engagement
                    </p>
                </div>
            </div>
        </div>

        <!-- Inventory Card -->
        <div class="card" style="padding: 32px; opacity: 0.6;">
            <div style="display: flex; align-items: flex-start; gap: 20px;">
                <div style="width: 64px; height: 64px; border-radius: 16px; background: rgba(221, 208, 200, 0.2); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="fas fa-warehouse" style="font-size: 28px; color: var(--cream);"></i>
                </div>
                <div style="flex: 1;">
                    <h3 style="font-size: 18px; font-weight: 700; color: var(--dark-gray); margin-bottom: 8px;">
                        Laporan Stok
                        <span class="badge badge-warning" style="margin-left: 8px; font-size: 11px;">Segera Hadir</span>
                    </h3>
                    <p style="color: #6B7280; font-size: 14px; line-height: 1.6;">
                        Monitor stok barang, produk habis, dan pergerakan inventori
                    </p>
                </div>
            </div>
        </div>

        <!-- Financial Card -->
        <div class="card" style="padding: 32px; opacity: 0.6;">
            <div style="display: flex; align-items: flex-start; gap: 20px;">
                <div style="width: 64px; height: 64px; border-radius: 16px; background: rgba(221, 208, 200, 0.2); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="fas fa-chart-line" style="font-size: 28px; color: var(--cream);"></i>
                </div>
                <div style="flex: 1;">
                    <h3 style="font-size: 18px; font-weight: 700; color: var(--dark-gray); margin-bottom: 8px;">
                        Laporan Keuangan
                        <span class="badge badge-warning" style="margin-left: 8px; font-size: 11px;">Segera Hadir</span>
                    </h3>
                    <p style="color: #6B7280; font-size: 14px; line-height: 1.6;">
                        Analisis profit, loss, dan cash flow bulanan
                    </p>
                </div>
            </div>
        </div>

        <!-- Cashier Performance Card -->
        <div class="card" style="padding: 32px; opacity: 0.6;">
            <div style="display: flex; align-items: flex-start; gap: 20px;">
                <div style="width: 64px; height: 64px; border-radius: 16px; background: rgba(221, 208, 200, 0.2); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="fas fa-user-tie" style="font-size: 28px; color: var(--cream);"></i>
                </div>
                <div style="flex: 1;">
                    <h3 style="font-size: 18px; font-weight: 700; color: var(--dark-gray); margin-bottom: 8px;">
                        Laporan Kasir
                        <span class="badge badge-warning" style="margin-left: 8px; font-size: 11px;">Segera Hadir</span>
                    </h3>
                    <p style="color: #6B7280; font-size: 14px; line-height: 1.6;">
                        Performa kasir, jumlah transaksi, dan akurasi
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Box -->
    <div class="card" style="margin-top: 32px; padding: 24px; background: var(--light-cream); border-left: 4px solid var(--cream);">
        <div style="display: flex; align-items: start; gap: 16px;">
            <i class="fas fa-info-circle" style="font-size: 24px; color: var(--cream); margin-top: 2px;"></i>
            <div>
                <h4 style="font-weight: 700; color: var(--dark-gray); margin-bottom: 8px;">Informasi Laporan</h4>
                <ul style="color: #6B7280; font-size: 14px; line-height: 1.8; padding-left: 20px;">
                    <li>Semua laporan dapat diexport ke format Excel atau PDF</li>
                    <li>Data laporan diperbarui secara real-time</li>
                    <li>Anda dapat memfilter laporan berdasarkan rentang tanggal</li>
                    <li>Laporan tambahan akan segera ditambahkan</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
.feature-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(221, 208, 200, 0.3);
}

.feature-card:hover .fa-arrow-right {
    transform: translateX(4px);
}
</style>
@endsection