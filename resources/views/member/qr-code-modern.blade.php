@extends('layouts.modern')

@section('title', 'QR Code Member')

@section('content')
<div class="fade-in">
    <!-- Success Message -->
    @if(session('success'))
    <div style="max-width: 600px; margin: 0 auto 24px; padding: 16px 24px; background: #DCFCE7; border-left: 4px solid #10B981; border-radius: 12px; display: flex; align-items: center; gap: 12px; box-shadow: 0 4px 12px rgba(16,185,129,0.2);">
        <i class="fas fa-check-circle" style="color: #059669; font-size: 24px;"></i>
        <div style="flex: 1;">
            <div style="font-weight: 700; color: #065F46; font-size: 15px;">{{ session('success') }}</div>
        </div>
    </div>
    @endif

    <!-- Main QR Card -->
    <div style="max-width: 600px; margin: 0 auto;">
        <div class="card" style="background: linear-gradient(135deg, #FF6F00 0%, #F57C00 100%); position: relative; overflow: hidden; padding: 48px 32px;">
            <!-- Decorative circles -->
            <div style="position: absolute; top: -80px; right: -80px; width: 250px; height: 250px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
            <div style="position: absolute; bottom: -60px; left: -60px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
            
            <div style="position: relative; z-index: 1;">
                <!-- Header -->
                <div style="text-align: center; margin-bottom: 32px;">
                    <div style="width: 80px; height: 80px; background: rgba(255,255,255,0.95); border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                        <i class="fas fa-qrcode" style="font-size: 40px; color: #FF6F00;"></i>
                    </div>
                    <h1 style="font-size: 28px; font-weight: 700; color: white; margin-bottom: 8px;">Member Card</h1>
                    <p style="color: rgba(255,255,255,0.9); font-size: 15px;">Tunjukkan QR Code ini saat transaksi</p>
                </div>

                <!-- QR Code Container -->
                <div style="background: white; border-radius: 24px; padding: 32px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); margin-bottom: 24px;">
                    <!-- Member Info -->
                    <div style="text-align: center; margin-bottom: 24px; padding-bottom: 24px; border-bottom: 2px dashed #E5E7EB;">
                        <div style="font-size: 15px; color: #6B7280; margin-bottom: 8px; font-weight: 600;">Member</div>
                        <div style="font-size: 24px; font-weight: 700; color: #111827; margin-bottom: 12px;">{{ auth()->user()->name }}</div>
                        <div style="display: inline-flex; align-items: center; gap: 8px; background: linear-gradient(135deg, #FFF7ED 0%, #FFEDD5 100%); padding: 8px 16px; border-radius: 12px; border: 2px solid #FDBA74;">
                            <i class="fas fa-id-card" style="color: #FF6F00;"></i>
                            <span style="font-weight: 700; color: #C2410C; font-size: 16px;">{{ $member->member_code }}</span>
                        </div>
                    </div>

                    <!-- QR Code Image -->
                    <div style="text-align: center; margin-bottom: 24px;">
                        @if($member->qr_code && file_exists(storage_path('app/public/' . $member->qr_code)))
                            <div style="display: inline-block; padding: 20px; background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
                                <img src="{{ asset('storage/' . $member->qr_code) }}" 
                                     alt="QR Code Member {{ $member->member_code }}" 
                                     style="width: 280px; height: 280px; display: block;"
                                     onerror="this.parentElement.innerHTML='<div style=\'width:280px;height:280px;background:#FEE2E2;border-radius:16px;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:12px;\'><i class=\'fas fa-exclamation-triangle\' style=\'font-size:48px;color:#EF4444;\'></i><div style=\'color:#991B1B;font-size:14px;font-weight:600;\'>QR Code Error</div></div>'">
                            </div>
                        @else
                            <div style="width: 320px; height: 320px; margin: 0 auto; background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%); border-radius: 16px; display: flex; align-items: center; justify-content: center; flex-direction: column; gap: 16px; padding: 24px; border: 3px dashed #F59E0B;">
                                <i class="fas fa-qrcode" style="font-size: 64px; color: #D97706;"></i>
                                <div style="color: #92400E; font-size: 16px; font-weight: 700; text-align: center;">QR Code Belum Dibuat</div>
                                <div style="color: #B45309; font-size: 13px; text-align: center;">Hubungi admin untuk generate QR Code</div>
                                <form action="{{ route('member.generate-qr') }}" method="POST" style="margin-top: 8px;">
                                    @csrf
                                    <button type="submit" class="btn btn-warning" style="padding: 12px 24px; font-weight: 600; background: #F59E0B; color: white; border: none; border-radius: 10px; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                                        <i class="fas fa-sync-alt"></i>
                                        <span>Generate QR Code Sekarang</span>
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>

                    <!-- Member Details -->
                    <div style="background: linear-gradient(135deg, #F9FAFB 0%, #F3F4F6 100%); border-radius: 16px; padding: 20px; margin-bottom: 20px;">
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
                            <div style="text-align: center; padding: 16px; background: white; border-radius: 12px; border: 2px solid #E5E7EB;">
                                <div style="color: #6B7280; font-size: 13px; margin-bottom: 6px; font-weight: 600;">Total Poin</div>
                                <div style="font-size: 24px; font-weight: 700; color: #FF6F00;">
                                    {{ number_format($member->points, 0, ',', '.') }}
                                </div>
                            </div>
                            <div style="text-align: center; padding: 16px; background: white; border-radius: 12px; border: 2px solid #E5E7EB;">
                                <div style="color: #6B7280; font-size: 13px; margin-bottom: 6px; font-weight: 600;">Status</div>
                                <div style="font-size: 16px; font-weight: 700;">
                                    @if($member->status === 'active')
                                        <span class="badge badge-success" style="padding: 6px 12px; font-size: 14px;">
                                            <i class="fas fa-check-circle mr-1"></i>Aktif
                                        </span>
                                    @else
                                        <span class="badge badge-warning" style="padding: 6px 12px; font-size: 14px;">
                                            {{ ucfirst($member->status) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Download Button -->
                    @if($member->qr_code && file_exists(storage_path('app/public/' . $member->qr_code)))
                        <a href="{{ asset('storage/' . $member->qr_code) }}" 
                           download="member-qr-{{ $member->member_code }}.svg" 
                           class="btn btn-primary" 
                           style="width: 100%; padding: 16px; font-size: 16px; font-weight: 700; display: flex; align-items: center; justify-content: center; gap: 10px; background: #FF6F00; border: none; transition: all 0.3s;">
                            <i class="fas fa-download"></i>
                            <span>Download QR Code</span>
                        </a>
                    @endif
                </div>

                <!-- Info Text -->
                <div style="text-align: center; color: white;">
                    <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">
                        <i class="fas fa-info-circle mr-1"></i>
                        Simpan QR Code ini untuk kemudahan transaksi
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Instructions Card -->
    <div style="max-width: 600px; margin: 32px auto 0;">
        <div class="card">
            <h2 style="font-size: 20px; font-weight: 700; color: #111827; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-lightbulb" style="color: #3B82F6; font-size: 24px;"></i>
                </div>
                <span>Cara Menggunakan QR Code</span>
            </h2>
            <ul style="list-style: none; padding: 0; margin: 0;">
                <li style="padding: 16px; border-bottom: 1px solid #E5E7EB; display: flex; align-items: start; gap: 16px;">
                    <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #FF6F00 0%, #F57C00 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <span style="color: white; font-weight: 700; font-size: 18px;">1</span>
                    </div>
                    <div style="flex: 1;">
                        <div style="font-weight: 700; color: #111827; margin-bottom: 4px; font-size: 15px;">Tunjukkan QR Code</div>
                        <div style="color: #6B7280; font-size: 14px;">Buka halaman ini atau screenshot QR Code saat berbelanja</div>
                    </div>
                </li>
                <li style="padding: 16px; border-bottom: 1px solid #E5E7EB; display: flex; align-items: start; gap: 16px;">
                    <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #FF6F00 0%, #F57C00 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <span style="color: white; font-weight: 700; font-size: 18px;">2</span>
                    </div>
                    <div style="flex: 1;">
                        <div style="font-weight: 700; color: #111827; margin-bottom: 4px; font-size: 15px;">Kasir Scan QR</div>
                        <div style="color: #6B7280; font-size: 14px;">Kasir akan memindai QR Code Anda di sistem POS</div>
                    </div>
                </li>
                <li style="padding: 16px; display: flex; align-items: start; gap: 16px;">
                    <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #FF6F00 0%, #F57C00 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <span style="color: white; font-weight: 700; font-size: 18px;">3</span>
                    </div>
                    <div style="flex: 1;">
                        <div style="font-weight: 700; color: #111827; margin-bottom: 4px; font-size: 15px;">Dapatkan Poin</div>
                        <div style="color: #6B7280; font-size: 14px;">Otomatis mendapat poin dan diskon member setiap transaksi</div>
                    </div>
                </li>
            </ul>
        </div>
    </div>

    <!-- Back Button -->
    <div style="max-width: 600px; margin: 24px auto 0; text-align: center;">
        <a href="{{ route('member.dashboard') }}" class="btn btn-secondary" style="padding: 14px 32px; font-size: 15px; font-weight: 600;">
            <i class="fas fa-arrow-left mr-2"></i>
            <span>Kembali ke Dashboard</span>
        </a>
    </div>
</div>

<style>
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.fade-in {
    animation: fadeIn 0.6s ease-out;
}

@media print {
    .btn, .breadcrumb {
        display: none !important;
    }
}

@media (max-width: 768px) {
    .card {
        padding: 24px 20px !important;
    }
    
    h1 {
        font-size: 24px !important;
    }
    
    img {
        width: 220px !important;
        height: 220px !important;
    }
}
</style>
</div>
@endsection
