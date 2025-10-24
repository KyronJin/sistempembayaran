@extends('layouts.app')
@section('title','Laporan')
@section('content')
<div class="container mx-auto px-4 py-6">
  <h1 class="text-2xl font-bold text-gray-800 mb-6"><i class="fas fa-file-alt mr-2"></i>Laporan</h1>

  <div class="grid md:grid-cols-2 gap-4">
    <a href="{{ route('kasir.reports.sales') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition card-hover">
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-lg font-bold text-gray-800 mb-2">
            <i class="fas fa-cash-register text-blue-600 mr-2"></i>Laporan Penjualan
          </h2>
          <p class="text-sm text-gray-600">Lihat laporan penjualan Anda dengan filter tanggal, member, dan produk</p>
        </div>
        <i class="fas fa-chevron-right text-gray-400 text-2xl"></i>
      </div>
    </a>
  </div>
</div>
@endsection
