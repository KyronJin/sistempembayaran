<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Struk #{{ $transaction->transaction_code }}</title>
  <style>
    * { 
      margin: 0; 
      padding: 0; 
      box-sizing: border-box; 
    }
    body { 
      font-family: 'Courier New', Courier, monospace; 
      padding: 0;
      background: white;
      font-size: 11px;
      line-height: 1.3;
    }
    .receipt { 
      width: 302px; /* 80mm = 302px at 96 DPI */
      margin: 0 auto; 
      background: white;
      padding: 8px;
    }
    .center { text-align: center; }
    .left { text-align: left; }
    .right { text-align: right; }
    .line { 
      border-top: 1px dashed #000; 
      margin: 5px 0; 
    }
    table { 
      width: 100%; 
      font-size: 11px; 
      border-collapse: collapse;
    }
    table td {
      padding: 1px 0;
      vertical-align: top;
    }
    .store-name {
      font-size: 14px;
      font-weight: bold;
      margin: 0;
    }
    .store-info {
      font-size: 10px;
      margin: 0;
    }
    .info-row {
      font-size: 10px;
      margin: 1px 0;
    }
    .info-label {
      display: inline-block;
      width: 80px;
    }
    .item-line {
      margin: 2px 0;
      font-size: 11px;
    }
    .item-qty {
      padding-left: 15px;
      font-size: 10px;
    }
    .total-section {
      font-size: 12px;
      font-weight: bold;
      margin: 3px 0;
    }
    .points-info {
      font-size: 10px;
      margin: 3px 0;
    }
    .footer-text {
      font-size: 9px;
      margin: 2px 0;
    }
    
    @media print {
      @page {
        size: 80mm auto;
        margin: 0;
      }
      body { 
        margin: 0;
        padding: 0;
      }
      .receipt {
        width: 80mm;
        padding: 8px;
        margin: 0;
      }
      .no-print { 
        display: none !important; 
      }
      * {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }
    }
  </style>
</head>
<body>
  <div class="receipt">
    <!-- Header -->
    <div class="center">
      <div class="store-name">{{ strtoupper($settings['store_name'] ?? 'RJ SHOP') }}</div>
      @if($settings['store_address'])
      <div class="store-info">{{ $settings['store_address'] }}</div>
      @endif
      @if($settings['store_phone'])
      <div class="store-info">Telp: {{ $settings['store_phone'] }}</div>
      @endif
    </div>
    
    <div class="line"></div>
    
    <!-- Transaction Info -->
    <div class="info-row">
      <span class="info-label">No. Transaksi</span> : {{ $transaction->transaction_code }}
    </div>
    <div class="info-row">
      <span class="info-label">Tanggal</span> : {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d/m/Y H:i') }}
    </div>
    <div class="info-row">
      <span class="info-label">Kasir</span> : {{ $transaction->cashier_name ?? $transaction->cashier->name ?? 'Kasir' }}
    </div>
    @if($transaction->member)
    <div class="info-row">
      <span class="info-label">Member</span> : {{ $transaction->member->user->name ?? $transaction->member->name }}
    </div>
    <div class="info-row">
      <span class="info-label">Kode Member</span> : {{ $transaction->member->member_code }}
    </div>
    @else
    <div class="info-row">
      <span class="info-label">Member</span> : nopal
    </div>
    @endif
    
    <div class="line"></div>
    
    <!-- Items -->
    @foreach($transaction->details as $d)
    <div class="item-line">{{ $d->product->name }}</div>
    <div class="item-qty">
      <table>
        <tr>
          <td style="width: 50%;">{{ $d->quantity }} x Rp {{ number_format($d->price, 0, ',', '.') }}</td>
          <td class="right" style="font-weight: bold;">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
        </tr>
      </table>
      @if($d->discount > 0)
      <table style="margin-top: 1px;">
        <tr>
          <td style="width: 50%;">DISKON :</td>
          <td class="right">({{ number_format($d->discount, 0, ',', '.') }})</td>
        </tr>
      </table>
      @endif
    </div>
    @endforeach
    
    <div class="line"></div>
    
    <!-- Summary -->
    <table style="font-size: 11px;">
      <tr>
        <td style="width: 60%;">Subtotal</td>
        <td class="right">Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</td>
      </tr>
      @if($transaction->member_discount > 0)
      <tr>
        <td>Diskon Member-</td>
        <td class="right">Rp {{ number_format($transaction->member_discount, 0, ',', '.') }}</td>
      </tr>
      @endif
      @if($transaction->discount > 0)
      <tr>
        <td>Diskon</td>
        <td class="right">-Rp {{ number_format($transaction->discount, 0, ',', '.') }}</td>
      </tr>
      @endif
      <tr>
        <td>Pajak ({{ \App\Models\Setting::getValue('tax_rate', 11) }}%)</td>
        <td class="right">Rp {{ number_format($transaction->tax, 0, ',', '.') }}</td>
      </tr>
    </table>
    
    <div class="line"></div>
    
    <!-- Total -->
    <table class="total-section">
      <tr>
        <td style="width: 60%;"><strong>TOTAL</strong></td>
        <td class="right"><strong>Rp {{ number_format($transaction->total, 0, ',', '.') }}</strong></td>
      </tr>
    </table>
    
    <div class="line"></div>
    
    <!-- Payment -->
    <table style="font-size: 11px;">
      <tr>
        <td style="width: 60%;">Bayar ({{ ucfirst($transaction->payment_method) }})</td>
        <td class="right">Rp {{ number_format($transaction->payment_amount, 0, ',', '.') }}</td>
      </tr>
      <tr style="font-weight: bold;">
        <td><strong>Kembali</strong></td>
        <td class="right"><strong>Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</strong></td>
      </tr>
    </table>
    
    <!-- Points Info -->
    @if($transaction->member)
    <div class="line"></div>
    @if($transaction->points_earned > 0)
    <div class="points-info">+ {{ $transaction->points_earned }} POIN</div>
    @endif
    <div class="points-info">Total Poin Anda: {{ $transaction->member->points }} poin</div>
    @endif
    
    <div class="line"></div>
    
    <!-- Footer -->
    <div class="center">
      <div class="footer-text" style="font-weight: bold; margin-top: 5px;">TERIMA KASIH</div>
      <div class="footer-text" style="font-weight: bold;">TELAH BERBELANJA!</div>
      <div class="footer-text" style="margin-top: 5px;">
        Barang yang sudah dibeli<br>
        tidak dapat ditukar/dikembalikan
      </div>
    </div>
    
    <div style="height: 15px;"></div>
    
    <!-- Print Button (only shown on screen) -->
    <div class="no-print center" style="margin-top: 20px; padding: 20px; background: #f5f5f5; border-radius: 8px;">
      <button onclick="window.print()" style="padding: 12px 24px; background: #FF6F00; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; font-size: 14px; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
        <i class="fas fa-print"></i> Cetak Ulang
      </button>
      <button onclick="window.close()" style="padding: 12px 24px; background: #6B7280; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; margin-left: 10px; font-size: 14px; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
        <i class="fas fa-times"></i> Tutup
      </button>
    </div>
  </div>
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</body>
</html>
