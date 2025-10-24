@extends('layouts.modern')

@section('title', 'Dashboard Member')
@section('breadcrumb')
    <i class="fas fa-home mr-2"></i>
    <span class="breadcrumb-active">Dashboard</span>
@endsection

@section('content')
<div class="fade-in">
    <!-- Welcome Card with Member Info -->
    <div class="card" style="background: linear-gradient(135deg, #10B981 0%, #059669 100%); margin-bottom: 32px; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        
        <div style="position: relative; z-index: 1; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 24px;">
            <div style="display: flex; align-items: center; gap: 20px;">
                <div style="width: 80px; height: 80px; background: rgba(255,255,255,0.2); border-radius: 20px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(10px);">
                    <i class="fas fa-user" style="font-size: 40px; color: white;"></i>
                </div>
                <div style="color: white;">
                    <div style="font-size: 14px; opacity: 0.9; margin-bottom: 4px;">Selamat datang kembali,</div>
                    <h1 style="font-size: 32px; font-weight: 700; margin-bottom: 8px;">{{ auth()->user()->name }}</h1>
                    <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
                        <div style="display: flex; align-items: center; gap: 6px; background: rgba(255,255,255,0.2); padding: 6px 12px; border-radius: 8px;">
                            <i class="fas fa-id-card"></i>
                            <span style="font-weight: 600; font-size: 14px;">{{ $member->member_code }}</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 6px; background: rgba(255,255,255,0.2); padding: 6px 12px; border-radius: 8px;">
                            <i class="fas fa-coins"></i>
                            <span style="font-weight: 700; font-size: 14px;">{{ number_format($member->points, 0, ',', '.') }} Poin</span>
                        </div>
                    </div>
                </div>
            </div>

            <a href="{{ route('member.qr-code') }}" class="btn btn-secondary" style="background: rgba(255,255,255,0.95); color: #059669; font-weight: 700; padding: 16px 24px;">
                <i class="fas fa-qrcode mr-2"></i>
                <span>Lihat QR Code</span>
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 32px;">
        <!-- Total Belanja -->
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #DBEAFE 0%, #BFDBFE 100%); color: #1E40AF;">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <div class="stat-label">Total Belanja</div>
            <div class="stat-value">Rp {{ number_format($total_spent ?? 0, 0, ',', '.') }}</div>
            <div style="margin-top: 12px; font-size: 13px; color: #6B7280; font-weight: 600;">
                <i class="fas fa-history mr-1"></i> Sepanjang waktu
            </div>
        </div>

        <!-- Total Transaksi -->
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #E9D5FF 0%, #D8B4FE 100%); color: #6B21A8;">
                <i class="fas fa-receipt"></i>
            </div>
            <div class="stat-label">Total Transaksi</div>
            <div class="stat-value">{{ $total_transactions ?? 0 }}</div>
            <div style="margin-top: 12px; font-size: 13px; color: #6B7280; font-weight: 600;">
                <i class="fas fa-check-circle mr-1"></i> Transaksi selesai
            </div>
        </div>

        <!-- Poin Tersedia -->
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #FED7AA 0%, #FDBA74 100%); color: #92400E;">
                <i class="fas fa-coins"></i>
            </div>
            <div class="stat-label">Poin Tersedia</div>
            <div class="stat-value">{{ number_format($member->points ?? 0, 0, ',', '.') }}</div>
            <div style="margin-top: 12px; font-size: 13px; color: #6B7280; font-weight: 600;">
                <i class="fas fa-gift mr-1"></i> Dapat digunakan
            </div>
        </div>

        <!-- Member Status -->
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #D1FAE5 0%, #A7F3D0 100%); color: #065F46;">
                <i class="fas fa-star"></i>
            </div>
            <div class="stat-label">Status Member</div>
            <div class="stat-value" style="font-size: 24px;">
                @if($member->status === 'active')
                    <span class="badge badge-success" style="padding: 8px 16px; font-size: 16px;">
                        <i class="fas fa-check-circle mr-1"></i>Aktif
                    </span>
                @else
                    <span class="badge badge-warning" style="padding: 8px 16px; font-size: 16px;">
                        <i class="fas fa-clock mr-1"></i>{{ ucfirst($member->status) }}
                    </span>
                @endif
            </div>
            <div style="margin-top: 12px; font-size: 13px; color: #6B7280; font-weight: 600;">
                <i class="fas fa-calendar mr-1"></i> Bergabung {{ $member->created_at->diffForHumans() }}
            </div>
        </div>
    </div>

    <!-- Quick Actions & Promotions -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 32px;">
        <!-- Quick Actions -->
        <div class="card">
            <h2 style="font-size: 20px; font-weight: 700; color: #111827; margin-bottom: 20px;">
                <i class="fas fa-bolt" style="color: #F59E0B; margin-right: 8px;"></i>
                Menu Cepat
            </h2>
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
                <a href="{{ route('member.transactions') }}" class="card card-hover" style="text-decoration: none; padding: 20px; text-align: center; background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%); border: 2px solid #BFDBFE;">
                    <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%); border-radius: 14px; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
                        <i class="fas fa-history" style="font-size: 28px; color: white;"></i>
                    </div>
                    <div style="font-weight: 700; color: #1E40AF; font-size: 15px;">Riwayat Transaksi</div>
                </a>

                <a href="{{ route('member.points-history') }}" class="card card-hover" style="text-decoration: none; padding: 20px; text-align: center; background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%); border: 2px solid #FDE047;">
                    <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); border-radius: 14px; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
                        <i class="fas fa-coins" style="font-size: 28px; color: white;"></i>
                    </div>
                    <div style="font-weight: 700; color: #92400E; font-size: 15px;">Riwayat Poin</div>
                </a>

                <a href="{{ route('member.products') }}" class="card card-hover" style="text-decoration: none; padding: 20px; text-align: center; background: linear-gradient(135deg, #F3E8FF 0%, #E9D5FF 100%); border: 2px solid #D8B4FE;">
                    <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%); border-radius: 14px; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
                        <i class="fas fa-box" style="font-size: 28px; color: white;"></i>
                    </div>
                    <div style="font-weight: 700; color: #6B21A8; font-size: 15px;">Produk</div>
                </a>

                <a href="{{ route('member.profile') }}" class="card card-hover" style="text-decoration: none; padding: 20px; text-align: center; background: linear-gradient(135deg, #F3F4F6 0%, #E5E7EB 100%); border: 2px solid #D1D5DB;">
                    <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #6B7280 0%, #4B5563 100%); border-radius: 14px; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
                        <i class="fas fa-user-cog" style="font-size: 28px; color: white;"></i>
                    </div>
                    <div style="font-weight: 700; color: #374151; font-size: 15px;">Profil Saya</div>
                </a>
            </div>
        </div>

        <!-- Active Promotions -->
        <div class="card">
            <h2 style="font-size: 20px; font-weight: 700; color: #111827; margin-bottom: 20px;">
                <i class="fas fa-tags" style="color: #EF4444; margin-right: 8px;"></i>
                Promo Aktif
            </h2>
            <div style="display: flex; flex-direction: column; gap: 12px; max-height: 300px; overflow-y: auto;">
                @forelse($active_promotions ?? [] as $promo)
                <div style="background: linear-gradient(135deg, #FEE2E2 0%, #FECACA 100%); padding: 16px; border-radius: 12px; border-left: 4px solid #EF4444;">
                    <div style="display: flex; justify-content: between; align-items: start; gap: 12px;">
                        <div style="width: 48px; height: 48px; background: #EF4444; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fas fa-percentage" style="font-size: 24px; color: white;"></i>
                        </div>
                        <div style="flex: 1;">
                            <div style="font-weight: 700; color: #991B1B; font-size: 15px; margin-bottom: 4px;">{{ $promo->name }}</div>
                            <div style="font-size: 13px; color: #B91C1C; margin-bottom: 8px;">{{ $promo->description }}</div>
                            <div style="display: flex; align-items: center; gap: 8px; font-size: 12px; color: #DC2626;">
                                <i class="fas fa-calendar"></i>
                                <span>s/d {{ $promo->end_date->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div style="text-align: center; padding: 32px; color: #6B7280;">
                    <i class="fas fa-tags" style="font-size: 48px; color: #D1D5DB; margin-bottom: 12px;"></i>
                    <div style="font-weight: 600;">Tidak ada promo aktif</div>
                    <div style="font-size: 13px; margin-top: 4px;">Promo akan ditampilkan di sini</div>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h2 style="font-size: 20px; font-weight: 700; color: #111827;">
                <i class="fas fa-clock" style="color: var(--primary); margin-right: 8px;"></i>
                Transaksi Terakhir
            </h2>
            <a href="{{ route('member.transactions') }}" class="btn btn-secondary" style="padding: 10px 20px; font-size: 14px;">
                <span>Lihat Semua</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div style="overflow-x: auto;">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Kode Transaksi</th>
                        <th>Tanggal</th>
                        <th>Kasir</th>
                        <th>Item</th>
                        <th>Total Belanja</th>
                        <th>Poin</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recent_transactions ?? [] as $transaction)
                    <tr>
                        <td>
                            <div style="font-weight: 700; color: #4F46E5;">{{ $transaction->transaction_code }}</div>
                        </td>
                        <td>
                            <div style="font-size: 14px; color: #374151;">{{ $transaction->transaction_date->format('d/m/Y') }}</div>
                            <div style="font-size: 12px; color: #6B7280;">{{ $transaction->transaction_date->format('H:i') }}</div>
                        </td>
                        <td>
                            <div style="font-size: 14px; color: #374151;">{{ $transaction->cashier->name ?? '-' }}</div>
                        </td>
                        <td>
                            <div style="font-size: 14px; color: #374151;">{{ $transaction->details->count() }} item</div>
                        </td>
                        <td>
                            <div style="font-weight: 700; color: #111827;">Rp {{ number_format($transaction->total, 0, ',', '.') }}</div>
                        </td>
                        <td>
                            @if($transaction->points_earned > 0)
                                <span class="badge badge-warning">
                                    <i class="fas fa-plus mr-1"></i>{{ $transaction->points_earned }} poin
                                </span>
                            @else
                                <span style="color: #9CA3AF; font-size: 13px;">-</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('member.transaction-detail', $transaction->id) }}" class="btn btn-secondary" style="padding: 6px 12px; font-size: 12px;">
                                <i class="fas fa-eye"></i>
                                <span>Detail</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 48px; color: #6B7280;">
                            <i class="fas fa-shopping-bag" style="font-size: 48px; color: #D1D5DB; margin-bottom: 16px; display: block;"></i>
                            <div style="font-size: 16px; font-weight: 600; margin-bottom: 4px;">Belum ada transaksi</div>
                            <div style="font-size: 14px;">Mulai berbelanja untuk melihat riwayat transaksi</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Info Card -->
    <div class="card" style="margin-top: 32px; background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%); border-left: 4px solid #3B82F6;">
        <div style="display: flex; gap: 20px;">
            <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%); border-radius: 14px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <i class="fas fa-info-circle" style="font-size: 28px; color: white;"></i>
            </div>
            <div style="flex: 1;">
                <h3 style="font-size: 18px; font-weight: 700; color: #1E3A8A; margin-bottom: 12px;">
                    Info Member
                </h3>
                <ul style="list-style: none; padding: 0; margin: 0; color: #1E40AF;">
                    <li style="padding: 8px 0; border-bottom: 1px solid rgba(59, 130, 246, 0.2); display: flex; align-items: start; gap: 12px;">
                        <i class="fas fa-check-circle" style="color: #10B981; margin-top: 2px;"></i>
                        <span>Dapatkan 1 poin untuk setiap Rp 10.000 pembelanjaan</span>
                    </li>
                    <li style="padding: 8px 0; border-bottom: 1px solid rgba(59, 130, 246, 0.2); display: flex; align-items: start; gap: 12px;">
                        <i class="fas fa-check-circle" style="color: #10B981; margin-top: 2px;"></i>
                        <span>Tunjukkan QR Code member untuk mendapatkan diskon</span>
                    </li>
                    <li style="padding: 8px 0; display: flex; align-items: start; gap: 12px;">
                        <i class="fas fa-check-circle" style="color: #10B981; margin-top: 2px;"></i>
                        <span>Tukarkan poin Anda dengan diskon atau hadiah menarik</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
