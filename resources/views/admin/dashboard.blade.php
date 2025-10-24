@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-purple-600 to-purple-700 rounded-xl shadow-lg p-8 text-white mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">
                    <i class="fas fa-chart-line mr-3"></i>Dashboard Admin
                </h1>
                <p class="text-purple-100">Selamat datang, <strong>{{ auth()->user()->name }}</strong></p>
            </div>
            <div class="text-right">
                <div class="text-purple-100 text-sm">{{ now()->isoFormat('dddd, D MMMM Y') }}</div>
                <div class="text-2xl font-bold">{{ now()->format('H:i') }}</div>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Sales Today -->
        <div class="stat-card from-green-500 to-green-600">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-money-bill-wave text-3xl opacity-80"></i>
                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-arrow-up text-xl"></i>
                </div>
            </div>
            <div class="text-white text-opacity-90 text-sm mb-1">Penjualan Hari Ini</div>
            <div class="text-3xl font-bold">Rp {{ number_format($total_sales_today,0,',','.') }}</div>
        </div>

        <!-- Transactions Today -->
        <div class="stat-card from-blue-500 to-blue-600">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-shopping-cart text-3xl opacity-80"></i>
                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-receipt text-xl"></i>
                </div>
            </div>
            <div class="text-white text-opacity-90 text-sm mb-1">Transaksi Hari Ini</div>
            <div class="text-3xl font-bold">{{ $total_transactions_today }}</div>
        </div>

        <!-- Active Members -->
        <div class="stat-card from-purple-500 to-purple-600">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-users text-3xl opacity-80"></i>
                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-check text-xl"></i>
                </div>
            </div>
            <div class="text-white text-opacity-90 text-sm mb-1">Member Aktif</div>
            <div class="text-3xl font-bold">{{ $total_members }}</div>
        </div>

        <!-- Pending Members -->
        <div class="stat-card from-yellow-500 to-yellow-600">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-user-clock text-3xl opacity-80"></i>
                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-hourglass-half text-xl"></i>
                </div>
            </div>
            <div class="text-white text-opacity-90 text-sm mb-1">Member Pending</div>
            <div class="text-3xl font-bold">{{ $pending_members }}</div>
        </div>
    </div>

    <!-- Second Row Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Low Stock Products -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800">Produk Stok Rendah</h3>
                    <p class="text-gray-500 text-sm">Perlu restock segera</p>
                </div>
            </div>
            <div class="text-4xl font-bold text-red-600">{{ $low_stock_products }}</div>
            <a href="{{ route('admin.products.index') }}" class="mt-4 inline-block text-blue-600 hover:text-blue-800 text-sm font-medium">
                Lihat Detail <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <!-- Top Products -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-star text-green-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800">Produk Terlaris</h3>
                    <p class="text-gray-500 text-sm">Penjualan terbaik</p>
                </div>
            </div>
            <ul class="space-y-2">
                @forelse($top_products as $product)
                    <li class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-box text-blue-600 mr-2"></i>
                            <span class="font-medium text-gray-800">{{ $product->name }}</span>
                        </div>
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-bold">
                            {{ $product->transaction_details_count }} terjual
                        </span>
                    </li>
                @empty
                    <li class="text-gray-400 text-center py-4">Belum ada data penjualan</li>
                @endforelse
            </ul>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b">
            <h3 class="font-bold text-gray-800 flex items-center">
                <i class="fas fa-history text-blue-600 mr-3"></i>
                10 Transaksi Terbaru
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase">Kasir</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase">Total</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-600 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($recent_transactions as $trx)
                    <tr class="hover:bg-blue-50 transition">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-800">{{ $trx->transaction_code }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-gray-800">{{ \Carbon\Carbon::parse($trx->transaction_date)->isoFormat('D MMM Y') }}</div>
                            <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($trx->transaction_date)->format('H:i:s') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <i class="fas fa-user text-gray-400 mr-2"></i>
                                <span class="text-gray-800">{{ $trx->cashier->name ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="font-bold text-gray-800">Rp {{ number_format($trx->total,0,',','.') }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($trx->status === 'completed')
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">
                                    <i class="fas fa-check-circle mr-1"></i>Selesai
                                </span>
                            @else
                                <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-bold">
                                    {{ ucfirst($trx->status) }}
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-400">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p>Belum ada transaksi</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .stat-card { @apply bg-gradient-to-br p-6 rounded-xl text-white shadow-lg transition transform hover:scale-105; }
</style>
@endsection
