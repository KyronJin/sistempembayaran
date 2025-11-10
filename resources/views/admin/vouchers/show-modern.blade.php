@extends('layouts.modern')

@section('title', 'Detail Voucher')

@section('content')
<div class="content-header">
    <div style="display: flex; align-items: center; gap: 1rem;">
        <a href="{{ route('admin.vouchers.index') }}" style="color: #6b7280; text-decoration: none; display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 10px; background: white; transition: all 0.3s;">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1>Detail Voucher</h1>
            <p>Informasi lengkap voucher {{ $voucher->code }}</p>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
    <!-- Main Info -->
    <div>
        <!-- Voucher Info Card -->
        <div class="modern-card" style="margin-bottom: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 2rem;">
                <div>
                    <h2 style="margin: 0 0 0.5rem 0; color: #1F2937; font-size: 1.8rem;">{{ $voucher->name }}</h2>
                    <div style="font-size: 1.5rem; font-weight: 700; color: #FF6F00; font-family: 'Courier New', monospace;">
                        {{ $voucher->code }}
                    </div>
                </div>
                <div style="display: flex; gap: 0.5rem;">
                    <a href="{{ route('admin.vouchers.edit', $voucher->id) }}" class="btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form action="{{ route('admin.vouchers.destroy', $voucher->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus voucher ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>

            @if($voucher->description)
            <div style="padding: 1rem; background: #f9fafb; border-radius: 8px; margin-bottom: 2rem; color: #4b5563;">
                {{ $voucher->description }}
            </div>
            @endif

            <!-- Stats Grid -->
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 2rem;">
                <div style="background: #fffbf5; border: 2px solid #fef3c7; border-radius: 12px; padding: 1.25rem; text-align: center;">
                    <div style="color: #FF6F00; font-size: 2rem; margin-bottom: 0.5rem;">
                        <i class="fas fa-star"></i>
                    </div>
                    <div style="font-size: 1.8rem; font-weight: 700; color: #1F2937; margin-bottom: 0.25rem;">
                        {{ number_format($voucher->points_required) }}
                    </div>
                    <div style="font-size: 0.9rem; color: #6b7280;">Poin Dibutuhkan</div>
                </div>
                <div style="background: #f0fdf4; border: 2px solid #d1fae5; border-radius: 12px; padding: 1.25rem; text-align: center;">
                    <div style="color: #10b981; font-size: 2rem; margin-bottom: 0.5rem;">
                        <i class="fas fa-users"></i>
                    </div>
                    <div style="font-size: 1.8rem; font-weight: 700; color: #1F2937; margin-bottom: 0.25rem;">
                        {{ $voucher->memberVouchers->count() }}
                    </div>
                    <div style="font-size: 0.9rem; color: #6b7280;">Total Redeemed</div>
                </div>
                <div style="background: #eff6ff; border: 2px solid #dbeafe; border-radius: 12px; padding: 1.25rem; text-align: center;">
                    <div style="color: #3b82f6; font-size: 2rem; margin-bottom: 0.5rem;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div style="font-size: 1.8rem; font-weight: 700; color: #1F2937; margin-bottom: 0.25rem;">
                        {{ $voucher->memberVouchers->where('status', 'used')->count() }}
                    </div>
                    <div style="font-size: 0.9rem; color: #6b7280;">Sudah Digunakan</div>
                </div>
            </div>

            <!-- Details Grid -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div>
                    <label style="font-size: 0.85rem; color: #6b7280; display: block; margin-bottom: 0.5rem;">Tipe Diskon</label>
                    <div style="font-weight: 600; color: #1F2937;">
                        @if($voucher->discount_type === 'percentage')
                            <span style="background: #dbeafe; color: #1e40af; padding: 0.5rem 1rem; border-radius: 8px;">
                                <i class="fas fa-percent"></i> Persentase
                            </span>
                        @else
                            <span style="background: #dcfce7; color: #15803d; padding: 0.5rem 1rem; border-radius: 8px;">
                                <i class="fas fa-money-bill-wave"></i> Fixed Amount
                            </span>
                        @endif
                    </div>
                </div>
                <div>
                    <label style="font-size: 0.85rem; color: #6b7280; display: block; margin-bottom: 0.5rem;">Nilai Diskon</label>
                    <div style="font-weight: 700; font-size: 1.5rem; color: #FF6F00;">
                        @if($voucher->discount_type === 'percentage')
                            {{ $voucher->discount_value }}%
                        @else
                            Rp {{ number_format($voucher->discount_value, 0, ',', '.') }}
                        @endif
                    </div>
                </div>
                <div>
                    <label style="font-size: 0.85rem; color: #6b7280; display: block; margin-bottom: 0.5rem;">Minimum Transaksi</label>
                    <div style="font-weight: 600; color: #1F2937;">Rp {{ number_format($voucher->min_transaction, 0, ',', '.') }}</div>
                </div>
                @if($voucher->max_discount)
                <div>
                    <label style="font-size: 0.85rem; color: #6b7280; display: block; margin-bottom: 0.5rem;">Maksimal Diskon</label>
                    <div style="font-weight: 600; color: #1F2937;">Rp {{ number_format($voucher->max_discount, 0, ',', '.') }}</div>
                </div>
                @endif
                <div>
                    <label style="font-size: 0.85rem; color: #6b7280; display: block; margin-bottom: 0.5rem;">Periode Berlaku</label>
                    <div style="font-weight: 600; color: #1F2937;">
                        {{ $voucher->valid_from->format('d M Y') }} - {{ $voucher->valid_until->format('d M Y') }}
                    </div>
                </div>
                <div>
                    <label style="font-size: 0.85rem; color: #6b7280; display: block; margin-bottom: 0.5rem;">Stok Voucher</label>
                    <div style="font-weight: 600; color: #1F2937;">
                        @if($voucher->stock === null)
                            <i class="fas fa-infinity" style="color: #10b981;"></i> Unlimited
                        @else
                            {{ $voucher->stock }} voucher
                        @endif
                    </div>
                </div>
                <div>
                    <label style="font-size: 0.85rem; color: #6b7280; display: block; margin-bottom: 0.5rem;">Max Usage Total</label>
                    <div style="font-weight: 600; color: #1F2937;">
                        @if($voucher->max_usage === null)
                            <i class="fas fa-infinity" style="color: #10b981;"></i> Unlimited
                        @else
                            {{ $voucher->max_usage }}x
                        @endif
                    </div>
                </div>
                <div>
                    <label style="font-size: 0.85rem; color: #6b7280; display: block; margin-bottom: 0.5rem;">Max Usage Per Member</label>
                    <div style="font-weight: 600; color: #1F2937;">{{ $voucher->max_usage_per_member }}x</div>
                </div>
            </div>
        </div>

        <!-- Redemption History -->
        <div class="modern-card">
            <h3 style="margin: 0 0 1.5rem 0; color: #1F2937; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-history" style="color: #FF6F00;"></i>
                Riwayat Penukaran ({{ $voucher->memberVouchers->count() }})
            </h3>

            @if($voucher->memberVouchers->count() > 0)
            <div class="table-responsive">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Member</th>
                            <th>Kode Voucher</th>
                            <th style="text-align: center;">Poin</th>
                            <th style="text-align: center;">Tanggal Tukar</th>
                            <th style="text-align: center;">Kadaluarsa</th>
                            <th style="text-align: center;">Status</th>
                            <th style="text-align: center;">Dipakai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($voucher->memberVouchers as $mv)
                        <tr>
                            <td>
                                <div style="font-weight: 600;">{{ $mv->member->user->name }}</div>
                                <div style="font-size: 0.85rem; color: #6b7280;">{{ $mv->member->member_code }}</div>
                            </td>
                            <td>
                                <span style="font-family: 'Courier New', monospace; font-weight: 600; color: #FF6F00;">
                                    {{ $mv->voucher_code }}
                                </span>
                            </td>
                            <td style="text-align: center; font-weight: 600;">
                                {{ number_format($mv->points_used) }}
                            </td>
                            <td style="text-align: center;">
                                {{ $mv->redeemed_at->format('d M Y') }}
                            </td>
                            <td style="text-align: center;">
                                {{ $mv->expires_at->format('d M Y') }}
                            </td>
                            <td style="text-align: center;">
                                @if($mv->status === 'available')
                                    <span style="background: #d1fae5; color: #065f46; padding: 0.35rem 0.75rem; border-radius: 12px; font-weight: 500; font-size: 0.85rem;">
                                        <i class="fas fa-check-circle"></i> Tersedia
                                    </span>
                                @elseif($mv->status === 'used')
                                    <span style="background: #e5e7eb; color: #4b5563; padding: 0.35rem 0.75rem; border-radius: 12px; font-weight: 500; font-size: 0.85rem;">
                                        <i class="fas fa-check"></i> Dipakai
                                    </span>
                                @else
                                    <span style="background: #fee2e2; color: #991b1b; padding: 0.35rem 0.75rem; border-radius: 12px; font-weight: 500; font-size: 0.85rem;">
                                        <i class="fas fa-times-circle"></i> Expired
                                    </span>
                                @endif
                            </td>
                            <td style="text-align: center; font-size: 0.85rem; color: #6b7280;">
                                @if($mv->used_at)
                                    {{ $mv->used_at->format('d M Y') }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div style="text-align: center; padding: 3rem; color: #6b7280;">
                <i class="fas fa-inbox" style="font-size: 3rem; opacity: 0.3; margin-bottom: 1rem;"></i>
                <p style="margin: 0;">Belum ada member yang menukar voucher ini</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Sidebar -->
    <div>
        <!-- Status Card -->
        <div class="modern-card" style="margin-bottom: 2rem;">
            <h3 style="margin: 0 0 1rem 0; color: #1F2937;">Status Voucher</h3>
            <div style="text-align: center; padding: 1.5rem;">
                @if($voucher->valid_until < now())
                    <div style="width: 80px; height: 80px; background: #fee2e2; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: #991b1b; font-size: 2.5rem;">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div style="font-weight: 600; font-size: 1.1rem; color: #991b1b; margin-bottom: 0.5rem;">Expired</div>
                    <div style="font-size: 0.9rem; color: #6b7280;">Voucher sudah kadaluarsa</div>
                @elseif($voucher->is_active)
                    <div style="width: 80px; height: 80px; background: #d1fae5; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: #065f46; font-size: 2.5rem;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div style="font-weight: 600; font-size: 1.1rem; color: #065f46; margin-bottom: 0.5rem;">Aktif</div>
                    <div style="font-size: 0.9rem; color: #6b7280;">Voucher dapat ditukar member</div>
                @else
                    <div style="width: 80px; height: 80px; background: #f3f4f6; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: #4b5563; font-size: 2.5rem;">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div style="font-weight: 600; font-size: 1.1rem; color: #4b5563; margin-bottom: 0.5rem;">Nonaktif</div>
                    <div style="font-size: 0.9rem; color: #6b7280;">Voucher tidak dapat ditukar</div>
                @endif
            </div>
        </div>

        <!-- Preview Card -->
        <div class="modern-card" style="background: linear-gradient(135deg, #FF6F00 0%, #FF8F00 100%); color: white;">
            <div style="text-align: center; padding: 1.5rem 1rem;">
                <div style="font-size: 2.5rem; margin-bottom: 1rem;">
                    <i class="fas fa-ticket-alt"></i>
                </div>
                <div style="font-size: 2rem; font-weight: 700; margin-bottom: 1rem; font-family: 'Courier New', monospace; word-break: break-all;">
                    {{ $voucher->code }}
                </div>
                <div style="background: rgba(255,255,255,0.2); padding: 1.5rem; border-radius: 12px; margin-bottom: 1rem;">
                    <div style="font-size: 0.9rem; opacity: 0.9; margin-bottom: 0.5rem;">Nilai Diskon</div>
                    <div style="font-size: 2.5rem; font-weight: 700;">
                        @if($voucher->discount_type === 'percentage')
                            {{ $voucher->discount_value }}%
                        @else
                            Rp {{ number_format($voucher->discount_value / 1000) }}K
                        @endif
                    </div>
                </div>
                <div style="font-size: 0.9rem; opacity: 0.9; margin-bottom: 0.5rem;">Butuh Poin</div>
                <div style="font-size: 1.5rem; font-weight: 600;">
                    {{ number_format($voucher->points_required) }} Poin
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
