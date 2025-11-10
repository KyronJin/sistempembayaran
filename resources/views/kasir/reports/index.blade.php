@extends('layouts.modern')
@section('title','Laporan')
@section('content')
<div class="container mx-auto px-4 py-6">
  <div style="margin-bottom: 32px;">
    <h1 style="font-size: 28px; font-weight: 700; color: var(--dark-gray); margin-bottom: 8px;">
      <i class="fas fa-file-chart-line" style="color: var(--cream); margin-right: 12px;"></i>
      Laporan Kasir
    </h1>
    <p style="color: var(--dark-gray); opacity: 0.7; font-size: 14px;">
      Akses laporan penjualan dan kinerja Anda
    </p>
  </div>

  <!-- Info Box -->
  <div style="background: rgba(221, 208, 200, 0.15); border-left: 4px solid var(--cream); padding: 16px; border-radius: 8px; margin-bottom: 32px;">
    <div style="display: flex; align-items: center; gap: 12px;">
      <i class="fas fa-info-circle" style="color: var(--cream); font-size: 20px;"></i>
      <div>
        <div style="font-weight: 600; color: var(--dark-gray); margin-bottom: 4px;">Informasi Laporan</div>
        <div style="font-size: 14px; color: var(--dark-gray); opacity: 0.7;">
          Laporan menampilkan data transaksi yang Anda tangani sebagai kasir
        </div>
      </div>
    </div>
  </div>

  <!-- Reports Grid -->
  <div class="grid md:grid-cols-2 gap-6">
    <!-- Sales Report Card -->
    <a href="{{ route('kasir.reports.sales') }}" 
       style="background: white; border-radius: 12px; border: 1px solid rgba(221, 208, 200, 0.3); padding: 24px; display: block; text-decoration: none; transition: all 0.3s ease; position: relative; overflow: hidden;"
       onmouseover="this.style.borderColor='var(--cream)'; this.style.boxShadow='0 4px 12px rgba(221, 208, 200, 0.2)'; this.querySelector('.arrow-icon').style.transform='translateX(5px)'; this.querySelector('.report-icon').style.background='rgba(16, 185, 129, 0.2)';"
       onmouseout="this.style.borderColor='rgba(221, 208, 200, 0.3)'; this.style.boxShadow='none'; this.querySelector('.arrow-icon').style.transform='translateX(0)'; this.querySelector('.report-icon').style.background='rgba(16, 185, 129, 0.15)';">
      
      <div style="display: flex; align-items: start; justify-content: space-between; gap: 16px;">
        <div style="flex: 1;">
          <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
            <div class="report-icon" style="width: 48px; height: 48px; background: rgba(16, 185, 129, 0.15); border-radius: 12px; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease;">
              <i class="fas fa-cash-register" style="color: #10B981; font-size: 22px;"></i>
            </div>
            <div>
              <h2 style="font-size: 18px; font-weight: 700; color: var(--dark-gray); margin: 0;">
                Laporan Penjualan
              </h2>
              <span style="display: inline-block; margin-top: 4px; padding: 4px 10px; background: rgba(16, 185, 129, 0.1); color: #10B981; border-radius: 6px; font-size: 11px; font-weight: 600;">
                AKTIF
              </span>
            </div>
          </div>
          <p style="color: var(--dark-gray); opacity: 0.7; font-size: 14px; line-height: 1.6; margin: 0;">
            Lihat laporan penjualan Anda dengan filter tanggal, member, dan produk
          </p>
        </div>
        <i class="fas fa-arrow-right arrow-icon" style="color: var(--cream); font-size: 20px; transition: transform 0.3s ease; margin-top: 12px;"></i>
      </div>
    </a>

  </div>
</div>
@endsection
