@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-receipt text-purple-600 mr-3"></i>
                    Riwayat Transaksi
                </h1>
                <p class="text-gray-500 text-sm mt-1">Daftar semua transaksi hari ini</p>
            </div>
            <a href="{{ route('kasir.pos') }}" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:shadow-lg transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke POS
            </a>
        </div>
    </div>

    @if(isset($transactions) && $transactions->count())
      <!-- Transactions Table -->
      <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
              <tr>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                  <i class="fas fa-barcode mr-2"></i>Kode Transaksi
                </th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                  <i class="fas fa-calendar mr-2"></i>Tanggal & Waktu
                </th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                  <i class="fas fa-user mr-2"></i>Member
                </th>
                <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">
                  <i class="fas fa-money-bill-wave mr-2"></i>Total
                </th>
                <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                  <i class="fas fa-cog mr-2"></i>Aksi
                </th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
            @foreach ($transactions as $trx)
              <tr class="hover:bg-blue-50 transition">
                <td class="px-6 py-4">
                  <div class="font-bold text-gray-800">{{ $trx->transaction_code }}</div>
                  <div class="text-xs text-gray-500">ID: {{ $trx->id }}</div>
                </td>
                <td class="px-6 py-4">
                  <div class="text-gray-800">{{ \Carbon\Carbon::parse($trx->transaction_date)->isoFormat('D MMM Y') }}</div>
                  <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($trx->transaction_date)->format('H:i:s') }}</div>
                </td>
                <td class="px-6 py-4">
                  @if($trx->member_id)
                    <div class="flex items-center">
                      <i class="fas fa-user-circle text-purple-600 mr-2"></i>
                      <div>
                        <div class="font-medium text-gray-800">{{ $trx->member->name ?? '-' }}</div>
                        <div class="text-xs text-gray-500">{{ $trx->member->member_code ?? '-' }}</div>
                      </div>
                    </div>
                  @else
                    <span class="text-gray-400 text-sm">Non-member</span>
                  @endif
                </td>
                <td class="px-6 py-4 text-right">
                  <div class="font-bold text-lg text-gray-800">Rp {{ number_format($trx->total,0,',','.') }}</div>
                </td>
                <td class="px-6 py-4 text-center">
                  <a class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition" 
                     href="{{ route('kasir.print-receipt', $trx->id) }}" target="_blank">
                    <i class="fas fa-print mr-2"></i>Cetak
                  </a>
                </td>
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>
      </div>
      
      <!-- Pagination -->
      <div class="mt-6">
        {{ $transactions->links() }}
      </div>
    @else
      <!-- Empty State -->
      <div class="bg-white rounded-xl shadow-lg p-12 text-center">
        <i class="fas fa-receipt text-gray-300 text-6xl mb-4"></i>
        <h3 class="text-xl font-bold text-gray-800 mb-2">Belum Ada Transaksi</h3>
        <p class="text-gray-500 mb-6">Mulai transaksi pertama Anda di POS</p>
        <a href="{{ route('kasir.pos') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:shadow-lg transition">
          <i class="fas fa-cash-register mr-2"></i>Buka POS
        </a>
      </div>
    @endif
</div>
@endsection
