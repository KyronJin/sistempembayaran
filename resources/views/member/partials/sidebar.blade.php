<!-- Member Sidebar -->
<div class="member-sidebar" style="position: sticky; top: 2rem; background: #1F2937; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.15); overflow: hidden; max-height: calc(100vh - 4rem); overflow-y: auto;">
    <!-- Logo Header -->
    <div style="padding: 24px; border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="width: 48px; height: 48px; background: #FF6F00; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-user" style="color: #FFFFFF; font-size: 24px;"></i>
            </div>
            <div>
                <div style="color: white; font-weight: 700; font-size: 18px;">{{ auth()->user()->member->name }}</div>
                <div style="color: rgba(255, 255, 255, 0.7); font-size: 12px;">Member</div>
            </div>
        </div>
    </div>
    
    <!-- Member Info Card -->
    <div style="margin: 16px 12px; background: rgba(255, 111, 0, 0.1); border: 1px solid rgba(255, 111, 0, 0.3); border-radius: 12px; padding: 16px;">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
            <div style="flex: 1;">
                <div style="color: rgba(255, 255, 255, 0.7); font-size: 11px; margin-bottom: 4px;">Kode Member</div>
                <div style="color: #FF6F00; font-weight: 700; font-size: 14px;">{{ auth()->user()->member->member_code }}</div>
            </div>
        </div>
        <div style="border-top: 1px solid rgba(255, 111, 0, 0.2); padding-top: 12px;">
            <div style="color: rgba(255, 255, 255, 0.7); font-size: 11px; margin-bottom: 4px;">Total Poin</div>
            <div style="display: flex; align-items: baseline; gap: 8px;">
                <div style="color: #FF6F00; font-weight: 700; font-size: 24px;">{{ number_format(auth()->user()->member->points, 0, ',', '.') }}</div>
                <div style="color: rgba(255, 255, 255, 0.5); font-size: 12px;">poin</div>
            </div>
            <div style="color: rgba(255, 255, 255, 0.5); font-size: 11px; margin-top: 4px;">≈ Rp {{ number_format(auth()->user()->member->points * 100, 0, ',', '.') }}</div>
        </div>
    </div>
    
    <!-- Menu Label -->
    <div style="margin-top: 24px; margin-bottom: 8px; padding: 0 24px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: rgba(255, 255, 255, 0.5);">
        Main Menu
    </div>
    
    <!-- Navigation Menu -->
    <nav style="padding: 0 12px 16px 12px;">
        <a href="{{ route('member.dashboard') }}" 
           class="member-menu-item {{ request()->routeIs('member.dashboard') ? 'active' : '' }}">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
        
        <a href="{{ route('member.profile') }}" 
           class="member-menu-item {{ request()->routeIs('member.profile*') ? 'active' : '' }}">
            <i class="fas fa-user-circle"></i>
            <span>Profil Saya</span>
        </a>
        
        <a href="{{ route('member.qr-code') }}" 
           class="member-menu-item {{ request()->routeIs('member.qr-code') ? 'active' : '' }}">
            <i class="fas fa-qrcode"></i>
            <span>QR Member</span>
        </a>
        
        <a href="{{ route('member.transactions') }}" 
           class="member-menu-item {{ request()->routeIs('member.transactions*') ? 'active' : '' }}">
            <i class="fas fa-receipt"></i>
            <span>Riwayat Transaksi</span>
        </a>
        
        <a href="{{ route('member.points-history') }}" 
           class="member-menu-item {{ request()->routeIs('member.points-history') ? 'active' : '' }}">
            <i class="fas fa-star"></i>
            <span>Riwayat Poin</span>
        </a>
        
        <a href="{{ route('member.products') }}" 
           class="member-menu-item {{ request()->routeIs('member.products') ? 'active' : '' }}">
            <i class="fas fa-box"></i>
            <span>Produk</span>
        </a>
        
        <div style="margin: 16px 0; height: 1px; background: rgba(255, 255, 255, 0.1);"></div>
        
        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
            @csrf
            <button type="submit" class="member-menu-item logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span>Keluar</span>
            </button>
        </form>
    </nav>
</div>

<style>
    .member-sidebar {
        scrollbar-width: thin;
        scrollbar-color: #FF6F00 #1F2937;
    }
    
    .member-sidebar::-webkit-scrollbar {
        width: 6px;
    }
    
    .member-sidebar::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 10px;
    }
    
    .member-sidebar::-webkit-scrollbar-thumb {
        background: #FF6F00;
        border-radius: 10px;
    }
    
    .member-sidebar::-webkit-scrollbar-thumb:hover {
        background: #F57C00;
    }
    
    .member-menu-item {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        margin-bottom: 4px;
        border-radius: 12px;
        color: rgba(255, 255, 255, 0.7);
        text-decoration: none;
        transition: all 0.2s;
        font-weight: 500;
        font-size: 14px;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
        cursor: pointer;
    }
    
    .member-menu-item:hover {
        background: rgba(255, 255, 255, 0.1);
        color: #FFFFFF;
        transform: translateX(4px);
    }
    
    .member-menu-item.active {
        background: #FF6F00;
        color: #FFFFFF;
        box-shadow: 0 4px 12px rgba(255, 111, 0, 0.4);
    }
    
    .member-menu-item i {
        width: 24px;
        margin-right: 12px;
        font-size: 18px;
    }
    
    .member-menu-item.logout-btn {
        color: #EF4444;
    }
    
    .member-menu-item.logout-btn:hover {
        background: rgba(239, 68, 68, 0.1);
        color: #EF4444;
    }
    
    @media (max-width: 768px) {
        .member-sidebar {
            position: relative !important;
            top: auto !important;
            max-height: none !important;
            margin-bottom: 1.5rem;
        }
    }
</style>
