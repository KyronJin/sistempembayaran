@extends('layouts.app')
@section('title','Laporan Penjualan')
@section('content')
<div class="container mx-auto px-4 py-6">
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-bold text-gray-800"><i class="fas fa-cash-register mr-2"></i>Laporan Penjualan</h1>
    <div class="flex gap-2">
      <button onclick="window.print()" class="btn-primary"><i class="fas fa-print mr-2"></i>Cetak Laporan</button>
      <a href="{{ route('admin.reports.index') }}" class="btn-secondary"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>
    </div>
  </div>

  <form method="GET" action="{{ route('admin.reports.sales') }}" class="bg-white rounded-lg shadow p-4 mb-4">
    <div class="grid md:grid-cols-4 gap-3 items-end">
      <div>
        <label class="block text-sm text-gray-600 mb-1">Tanggal Mulai</label>
        <input type="date" name="start_date" value="{{ request('start_date', \Carbon\Carbon::parse($startDate)->format('Y-m-d')) }}" class="w-full border rounded-lg px-3 py-2">
      </div>
      <div>
        <label class="block text-sm text-gray-600 mb-1">Tanggal Akhir</label>
        <input type="date" name="end_date" value="{{ request('end_date', \Carbon\Carbon::parse($endDate)->format('Y-m-d')) }}" class="w-full border rounded-lg px-3 py-2">
      </div>
      <div>
        <label class="block text-sm text-gray-600 mb-1">Member</label>
        <select name="member_id" class="w-full border rounded-lg px-3 py-2">
          <option value="">Semua Member</option>
          @foreach($members as $member)
            <option value="{{ $member->id }}" {{ request('member_id') == $member->id ? 'selected' : '' }}>
              {{ $member->user->name ?? $member->member_code }}
            </option>
          @endforeach
        </select>
      </div>
      <div>
        <label class="block text-sm text-gray-600 mb-1">Produk</label>
        <select name="product_id" class="w-full border rounded-lg px-3 py-2">
          <option value="">Semua Produk</option>
          @foreach($products as $product)
            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
              {{ $product->name }}
            </option>
          @endforeach
        </select>
      </div>
    </div>
    <div class="mt-3">
      <button type="submit" class="btn-primary"><i class="fas fa-search mr-2"></i>Terapkan Filter</button>
      <a href="{{ route('admin.reports.sales') }}" class="btn-secondary ml-2"><i class="fas fa-times mr-2"></i>Reset</a>
    </div>
  </form>

  <div class="grid md:grid-cols-3 gap-3 mb-4">
    <div class="bg-white rounded-lg shadow p-4">
      <div class="text-sm text-gray-500">Total Penjualan</div>
      <div class="text-xl font-bold">Rp {{ number_format($totalSales,0,',','.') }}</div>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
      <div class="text-sm text-gray-500">Total Transaksi</div>
      <div class="text-xl font-bold">{{ $totalTransactions }}</div>
    </div>
  </div>

  <div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="min-w-full">
      <thead class="bg-gray-50">
        <tr class="text-left text-sm text-gray-600">
          <th class="px-4 py-3">Tanggal</th>
          <th class="px-4 py-3">Kode</th>
          <th class="px-4 py-3">Kasir</th>
          <th class="px-4 py-3">Member</th>
          <th class="px-4 py-3">Total</th>
          <th class="px-4 py-3">Status</th>
        </tr>
      </thead>
      <tbody class="divide-y">
        @forelse($transactions as $t)
        <tr class="text-sm">
          <td class="px-4 py-3">{{ \Carbon\Carbon::parse($t->transaction_date)->format('d/m/Y H:i') }}</td>
          <td class="px-4 py-3">{{ $t->transaction_code }}</td>
          <td class="px-4 py-3">{{ $t->cashier?->name ?? '-' }}</td>
          <td class="px-4 py-3">{{ $t->member?->user?->name ?? '-' }}</td>
          <td class="px-4 py-3">Rp {{ number_format($t->total,0,',','.') }}</td>
          <td class="px-4 py-3">{{ ucfirst($t->status) }}</td>
        </tr>
        @empty
        <tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">Tidak ada data</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<style media="print">
  @media print {
    .no-print { display: none !important; }
    body { background: white; }
    nav, footer { display: none !important; }
    .btn-primary, .btn-secondary { display: none !important; }
    form { display: none !important; }
    @page { margin: 1cm; }
  }
</style>
@endsection