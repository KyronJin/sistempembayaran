@extends('layouts.modern')
@section('title','Laporan Penjualan')
@section('content')
<div class="container mx-auto px-4 py-6">
  <!-- Header -->
  <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
      <h1 style="font-size: 28px; font-weight: 700; color: var(--dark-gray); margin-bottom: 8px;">
        <i class="fas fa-cash-register" style="color: var(--cream); margin-right: 12px;"></i>
        Laporan Penjualan Saya
      </h1>
      <p style="color: var(--dark-gray); opacity: 0.7; font-size: 14px;">
        Data transaksi yang Anda tangani sebagai kasir
      </p>
    </div>
    <div style="display: flex; gap: 8px;">
      <button onclick="window.print()" 
              style="background: var(--light-cream); color: var(--dark-gray); border: 1px solid rgba(221, 208, 200, 0.5); padding: 10px 18px; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 8px;"
              onmouseover="this.style.background='var(--cream)'; this.style.borderColor='var(--cream)';"
              onmouseout="this.style.background='var(--light-cream)'; this.style.borderColor='rgba(221, 208, 200, 0.5)';">
        <i class="fas fa-print"></i>
        <span>Cetak</span>
      </button>
      <a href="{{ route('kasir.reports.index') }}" 
         style="background: white; color: var(--dark-gray); border: 1px solid rgba(221, 208, 200, 0.5); padding: 10px 18px; border-radius: 8px; font-weight: 600; font-size: 14px; text-decoration: none; transition: all 0.3s ease; display: flex; align-items: center; gap: 8px;"
         onmouseover="this.style.background='var(--light-cream)'; this.style.borderColor='var(--cream)';"
         onmouseout="this.style.background='white'; this.style.borderColor='rgba(221, 208, 200, 0.5)';">
        <i class="fas fa-arrow-left"></i>
        <span>Kembali</span>
      </a>
    </div>
  </div>

  <!-- Filter Form -->
  <form method="GET" action="{{ route('kasir.reports.sales') }}" 
        class="no-print"
        style="background: white; border-radius: 12px; border: 1px solid rgba(221, 208, 200, 0.3); padding: 24px; margin-bottom: 24px;">
    <div style="margin-bottom: 16px;">
      <h3 style="font-size: 16px; font-weight: 700; color: var(--dark-gray); margin-bottom: 4px;">
        <i class="fas fa-filter" style="color: var(--cream); margin-right: 8px;"></i>
        Filter Laporan
      </h3>
      <p style="font-size: 13px; color: var(--dark-gray); opacity: 0.6;">
        Pilih kriteria untuk memfilter data transaksi
      </p>
    </div>
    
    <div class="grid md:grid-cols-4 gap-4 items-end">
      <div>
        <label style="display: block; font-size: 13px; font-weight: 600; color: var(--dark-gray); margin-bottom: 8px;">
          <i class="fas fa-calendar-alt" style="color: var(--cream); font-size: 12px; margin-right: 6px;"></i>
          Tanggal Mulai
        </label>
        <input type="date" name="start_date" 
               value="{{ request('start_date', \Carbon\Carbon::parse($startDate)->format('Y-m-d')) }}" 
               style="width: 100%; border: 1px solid rgba(221, 208, 200, 0.5); border-radius: 8px; padding: 10px 14px; font-size: 14px; transition: all 0.3s ease;"
               onfocus="this.style.borderColor='var(--cream)'; this.style.outline='none';"
               onblur="this.style.borderColor='rgba(221, 208, 200, 0.5)';">
      </div>
      <div>
        <label style="display: block; font-size: 13px; font-weight: 600; color: var(--dark-gray); margin-bottom: 8px;">
          <i class="fas fa-calendar-check" style="color: var(--cream); font-size: 12px; margin-right: 6px;"></i>
          Tanggal Akhir
        </label>
        <input type="date" name="end_date" 
               value="{{ request('end_date', \Carbon\Carbon::parse($endDate)->format('Y-m-d')) }}" 
               style="width: 100%; border: 1px solid rgba(221, 208, 200, 0.5); border-radius: 8px; padding: 10px 14px; font-size: 14px; transition: all 0.3s ease;"
               onfocus="this.style.borderColor='var(--cream)'; this.style.outline='none';"
               onblur="this.style.borderColor='rgba(221, 208, 200, 0.5)';">
      </div>
      <div>
        <label style="display: block; font-size: 13px; font-weight: 600; color: var(--dark-gray); margin-bottom: 8px;">
          <i class="fas fa-user" style="color: var(--cream); font-size: 12px; margin-right: 6px;"></i>
          Member
        </label>
        <select name="member_id" 
                style="width: 100%; border: 1px solid rgba(221, 208, 200, 0.5); border-radius: 8px; padding: 10px 14px; font-size: 14px; transition: all 0.3s ease;"
                onfocus="this.style.borderColor='var(--cream)'; this.style.outline='none';"
                onblur="this.style.borderColor='rgba(221, 208, 200, 0.5)';">
          <option value="">Semua Member</option>
          @foreach($members as $member)
            <option value="{{ $member->id }}" {{ request('member_id') == $member->id ? 'selected' : '' }}>
              {{ $member->user->name ?? $member->member_code }}
            </option>
          @endforeach
        </select>
      </div>
      <div>
        <label style="display: block; font-size: 13px; font-weight: 600; color: var(--dark-gray); margin-bottom: 8px;">
          <i class="fas fa-box" style="color: var(--cream); font-size: 12px; margin-right: 6px;"></i>
          Produk
        </label>
        <select name="product_id" 
                style="width: 100%; border: 1px solid rgba(221, 208, 200, 0.5); border-radius: 8px; padding: 10px 14px; font-size: 14px; transition: all 0.3s ease;"
                onfocus="this.style.borderColor='var(--cream)'; this.style.outline='none';"
                onblur="this.style.borderColor='rgba(221, 208, 200, 0.5)';">
          <option value="">Semua Produk</option>
          @foreach($products as $product)
            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
              {{ $product->name }}
            </option>
          @endforeach
        </select>
      </div>
    </div>
    
    <div style="margin-top: 20px; display: flex; gap: 8px;">
      <button type="submit" 
              style="background: var(--cream); color: var(--dark-gray); border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 8px;"
              onmouseover="this.style.background='var(--accent-cream)'; this.style.transform='translateY(-1px)';"
              onmouseout="this.style.background='var(--cream)'; this.style.transform='translateY(0)';">
        <i class="fas fa-search"></i>
        <span>Terapkan Filter</span>
      </button>
      <a href="{{ route('kasir.reports.sales') }}" 
         style="background: white; color: var(--dark-gray); border: 1px solid rgba(221, 208, 200, 0.5); padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: 14px; text-decoration: none; transition: all 0.3s ease; display: flex; align-items: center; gap: 8px;"
         onmouseover="this.style.background='var(--light-cream)'; this.style.borderColor='var(--cream)';"
         onmouseout="this.style.background='white'; this.style.borderColor='rgba(221, 208, 200, 0.5)';">
        <i class="fas fa-times"></i>
        <span>Reset</span>
      </a>
    </div>
  </form>

  <!-- Stats Cards -->
  <div class="grid md:grid-cols-3 gap-6 mb-6">
    <div style="background: white; border-radius: 12px; border: 1px solid rgba(221, 208, 200, 0.3); padding: 20px;">
      <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
        <div style="width: 48px; height: 48px; background: rgba(16, 185, 129, 0.15); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
          <i class="fas fa-dollar-sign" style="color: #10B981; font-size: 20px;"></i>
        </div>
        <div>
          <div style="font-size: 13px; color: var(--dark-gray); opacity: 0.7; font-weight: 500;">Total Penjualan</div>
          <div style="font-size: 24px; font-weight: 700; color: var(--dark-gray); margin-top: 4px;">
            Rp {{ number_format($totalSales,0,',','.') }}
          </div>
        </div>
      </div>
    </div>
    
    <div style="background: white; border-radius: 12px; border: 1px solid rgba(221, 208, 200, 0.3); padding: 20px;">
      <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
        <div style="width: 48px; height: 48px; background: rgba(99, 102, 241, 0.15); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
          <i class="fas fa-receipt" style="color: #6366F1; font-size: 20px;"></i>
        </div>
        <div>
          <div style="font-size: 13px; color: var(--dark-gray); opacity: 0.7; font-weight: 500;">Total Transaksi</div>
          <div style="font-size: 24px; font-weight: 700; color: var(--dark-gray); margin-top: 4px;">
            {{ $totalTransactions }}
          </div>
        </div>
      </div>
    </div>
    
    <div style="background: white; border-radius: 12px; border: 1px solid rgba(221, 208, 200, 0.3); padding: 20px;">
      <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
        <div style="width: 48px; height: 48px; background: rgba(221, 208, 200, 0.3); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
          <i class="fas fa-chart-line" style="color: var(--dark-gray); font-size: 20px;"></i>
        </div>
        <div>
          <div style="font-size: 13px; color: var(--dark-gray); opacity: 0.7; font-weight: 500;">Rata-rata per Transaksi</div>
          <div style="font-size: 24px; font-weight: 700; color: var(--dark-gray); margin-top: 4px;">
            Rp {{ $totalTransactions > 0 ? number_format($totalSales/$totalTransactions,0,',','.') : 0 }}
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Transactions Table -->
  <div style="background: white; border-radius: 12px; border: 1px solid rgba(221, 208, 200, 0.3); overflow: hidden;">
    <div style="padding: 20px; border-bottom: 1px solid rgba(221, 208, 200, 0.3);">
      <h3 style="font-size: 16px; font-weight: 700; color: var(--dark-gray); margin: 0;">
        <i class="fas fa-list" style="color: var(--cream); margin-right: 8px;"></i>
        Detail Transaksi
      </h3>
    </div>
    
    <div style="overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: var(--light-cream); border-bottom: 1px solid rgba(221, 208, 200, 0.3);">
            <th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: var(--dark-gray);">
              <i class="fas fa-calendar" style="color: var(--cream); font-size: 11px; margin-right: 6px;"></i>
              Tanggal
            </th>
            <th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: var(--dark-gray);">
              <i class="fas fa-hashtag" style="color: var(--cream); font-size: 11px; margin-right: 6px;"></i>
              Kode
            </th>
            <th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: var(--dark-gray);">
              <i class="fas fa-user" style="color: var(--cream); font-size: 11px; margin-right: 6px;"></i>
              Member
            </th>
            <th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: var(--dark-gray);">
              <i class="fas fa-money-bill-wave" style="color: var(--cream); font-size: 11px; margin-right: 6px;"></i>
              Total
            </th>
            <th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: var(--dark-gray);">
              <i class="fas fa-credit-card" style="color: var(--cream); font-size: 11px; margin-right: 6px;"></i>
              Metode
            </th>
            <th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: var(--dark-gray);">
              <i class="fas fa-check-circle" style="color: var(--cream); font-size: 11px; margin-right: 6px;"></i>
              Status
            </th>
          </tr>
        </thead>
        <tbody>
          @forelse($transactions as $t)
          <tr style="border-bottom: 1px solid rgba(221, 208, 200, 0.2); transition: background 0.2s ease;"
              onmouseover="this.style.background='rgba(221, 208, 200, 0.05)';"
              onmouseout="this.style.background='white';">
            <td style="padding: 14px 16px; font-size: 14px; color: var(--dark-gray);">
              {{ \Carbon\Carbon::parse($t->transaction_date)->format('d/m/Y H:i') }}
            </td>
            <td style="padding: 14px 16px; font-size: 14px; color: var(--dark-gray); font-weight: 600;">
              {{ $t->transaction_code }}
            </td>
            <td style="padding: 14px 16px; font-size: 14px; color: var(--dark-gray);">
              <div style="display: flex; align-items: center; gap: 8px;">
                <div style="width: 32px; height: 32px; background: var(--light-cream); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                  <i class="fas fa-user" style="color: var(--cream); font-size: 12px;"></i>
                </div>
                <span>{{ $t->member?->user?->name ?? 'Umum' }}</span>
              </div>
            </td>
            <td style="padding: 14px 16px; font-size: 14px; color: var(--dark-gray); font-weight: 600;">
              Rp {{ number_format($t->total,0,',','.') }}
            </td>
            <td style="padding: 14px 16px; font-size: 14px; color: var(--dark-gray);">
              <span style="padding: 4px 10px; background: rgba(221, 208, 200, 0.2); color: var(--dark-gray); border-radius: 6px; font-size: 12px; font-weight: 600;">
                {{ ucfirst($t->payment_method) }}
              </span>
            </td>
            <td style="padding: 14px 16px; font-size: 14px;">
              <span style="padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600;
                {{ $t->status === 'completed' ? 'background: rgba(16, 185, 129, 0.15); color: #10B981;' : '' }}
                {{ $t->status === 'void' ? 'background: rgba(239, 68, 68, 0.15); color: #EF4444;' : '' }}">
                {{ ucfirst($t->status) }}
              </span>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" style="padding: 48px 16px; text-align: center; color: var(--dark-gray); opacity: 0.5;">
              <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 16px; display: block; color: var(--cream);"></i>
              <div style="font-size: 16px; font-weight: 600;">Tidak ada data transaksi</div>
              <div style="font-size: 13px; margin-top: 4px;">Silakan ubah filter untuk melihat data lain</div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<style media="print">
  @media print {
    .no-print { display: none !important; }
    body { background: white; }
    nav, footer, .sidebar { display: none !important; }
    button, a { display: none !important; }
    @page { margin: 1cm; }
  }
</style>
@endsection
