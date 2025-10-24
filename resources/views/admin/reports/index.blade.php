@extends('layouts.app')
@section('title','Laporan Admin')
@section('content')
<div class="container mx-auto px-4 py-6">
  <h1 class="text-2xl font-bold text-gray-800 mb-4"><i class="fas fa-file-alt mr-2"></i>Laporan</h1>

  <div class="grid md:grid-cols-2 gap-4">
    <a href="{{ route('admin.reports.sales') }}" class="block bg-white rounded-lg shadow p-6 card-hover">
      <div class="flex items-center">
        <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mr-4">
          <i class="fas fa-cash-register"></i>
        </div>
        <div>
          <div class="font-semibold text-gray-800">Laporan Penjualan</div>
          <div class="text-sm text-gray-500">Ringkasan transaksi dalam rentang tanggal</div>
        </div>
      </div>
    </a>
  </div>
</div>
@endsection