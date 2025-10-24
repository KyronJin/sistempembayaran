<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Struk Transaksi</title>
  <style>
    body { font-family: Arial, sans-serif; }
    .receipt { width: 320px; margin: 0 auto; }
    .center { text-align: center; }
    .right { text-align: right; }
    .line { border-top: 1px dashed #000; margin: 8px 0; }
    table { width: 100%; font-size: 12px; }
  </style>
</head>
<body onload="window.print()">
  <div class="receipt">
    <div class="center">
      <div><strong>{{ $settings['store_name'] ?? 'Toko' }}</strong></div>
      <div>{{ $settings['store_address'] ?? '' }}</div>
      <div>{{ $settings['store_phone'] ?? '' }}</div>
    </div>
    <div class="line"></div>
    <div>Kode: {{ $transaction->transaction_code }}</div>
    <div>Tanggal: {{ $transaction->transaction_date }}</div>
    <div>Kasir: {{ $transaction->cashier->name }}</div>
    @if($transaction->member)
      <div>Member: {{ $transaction->member->user->name }}</div>
    @endif
    <div class="line"></div>
    <table>
      <tbody>
        @foreach($transaction->details as $d)
          <tr>
            <td>{{ $d->product->name }} x{{ $d->quantity }}</td>
            <td class="right">Rp {{ number_format($d->subtotal,0,',','.') }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
    <div class="line"></div>
    <table>
      <tr><td>Subtotal</td><td class="right">Rp {{ number_format($transaction->subtotal,0,',','.') }}</td></tr>
      <tr><td>Pajak</td><td class="right">Rp {{ number_format($transaction->tax,0,',','.') }}</td></tr>
      @if($transaction->member_discount>0)
      <tr><td>Diskon Member</td><td class="right">- Rp {{ number_format($transaction->member_discount,0,',','.') }}</td></tr>
      @endif
      @if($transaction->points_used>0)
      <tr><td>Poin Digunakan</td><td class="right">{{ $transaction->points_used }}</td></tr>
      @endif
      <tr><td><strong>Total</strong></td><td class="right"><strong>Rp {{ number_format($transaction->total,0,',','.') }}</strong></td></tr>
      <tr><td>Bayar</td><td class="right">Rp {{ number_format($transaction->payment_amount,0,',','.') }}</td></tr>
      <tr><td>Kembali</td><td class="right">Rp {{ number_format($transaction->change_amount,0,',','.') }}</td></tr>
    </table>
    <div class="line"></div>
    <div class="center">Terima kasih telah berbelanja</div>
  </div>
</body>
</html>
