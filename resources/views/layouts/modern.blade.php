<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem POS')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            /* New Color Scheme: White, Black, Orange */
            --primary: #FFFFFF;
            --secondary: #1F2937;
            --accent: #FF6F00;
            --accent-hover: #F57C00;
            --light-bg: #F9FAFB;
            --border: #E5E7EB;
            --text-primary: #1F2937;
            --text-secondary: #6B7280;
            --success: #10B981;
            --danger: #EF4444;
            --warning: #F59E0B;
            --sidebar-width: 280px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--light-bg);
            color: var(--text-primary);
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: var(--secondary);
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 50;
            overflow-y: auto;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
        }

        .sidebar-logo {
            padding: 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-menu {
            padding: 16px 12px;
        }

        .menu-item {
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
        }

        .menu-item:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #FFFFFF;
            transform: translateX(4px);
        }

        .menu-item.active {
            background: var(--accent);
            color: #FFFFFF;
            box-shadow: 0 4px 12px rgba(255, 111, 0, 0.4);
        }

        .menu-item i {
            width: 24px;
            margin-right: 12px;
            font-size: 18px;
        }

        .menu-label {
            margin-top: 24px;
            margin-bottom: 8px;
            padding: 0 16px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: rgba(255, 255, 255, 0.5);
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Top Bar */
        .topbar {
            background: var(--primary);
            height: 72px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            position: sticky;
            top: 0;
            z-index: 40;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            border-bottom: 1px solid var(--border);
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-secondary);
            font-size: 14px;
        }

        .breadcrumb-active {
            color: var(--text-primary);
            font-weight: 600;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 16px;
            border-radius: 12px;
            background: var(--light-bg);
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid var(--border);
        }

        .user-menu:hover {
            background: var(--border);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #FFFFFF;
            font-weight: 700;
            font-size: 16px;
        }

        /* Cards */
        .card {
            background: var(--primary);
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            padding: 24px;
            transition: all 0.3s;
            border: 1px solid rgba(221, 208, 200, 0.3);
        }

        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(50, 50, 50, 0.15);
        }

        /* Stat Card */
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(50, 50, 50, 0.08);
            transition: all 0.3s;
            border: 1px solid rgba(221, 208, 200, 0.3);
        }

        .stat-card:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.12);
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 16px;
        }

        .stat-label {
            font-size: 14px;
            color: var(--text-secondary);
            margin-bottom: 8px;
            font-weight: 500;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s;
            cursor: pointer;
            border: none;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--accent);
            color: #FFFFFF;
            box-shadow: 0 4px 12px rgba(255, 111, 0, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(255, 111, 0, 0.5);
            background: var(--accent-hover);
        }

        .btn-secondary {
            background: var(--secondary);
            color: white;
            border: 1px solid var(--border);
        }

        .btn-secondary:hover {
            background: #374151;
        }

        .btn-success {
            background: var(--success);
            color: white;
        }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        /* Mobile Toggle */
        .mobile-toggle {
            display: none;
            width: 44px;
            height: 44px;
            background: var(--accent);
            border: none;
            border-radius: 12px;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: #FFFFFF;
            transition: all 0.2s;
        }

        .mobile-toggle:hover {
            background: var(--accent-hover);
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 45;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .sidebar-overlay.show {
            display: block;
            opacity: 1;
        }

        /* Content Wrapper */
        .content-wrapper {
            padding: 32px;
        }

        .content-header {
            margin-bottom: 32px;
        }

        .content-header h1 {
            font-size: 32px;
            font-weight: 700;
            color: var(--dark-gray);
            margin-bottom: 8px;
        }

        .content-header p {
            color: #888;
            font-size: 16px;
        }

        /* Responsive Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }

        .responsive-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
        }

        /* Mobile & Tablet Styles */
        @media (max-width: 1024px) {
            :root {
                --sidebar-width: 260px;
            }

            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 16px;
            }

            .content-wrapper {
                padding: 24px;
            }

            .topbar {
                padding: 0 24px;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-toggle {
                display: flex;
            }

            .topbar {
                padding: 0 16px;
                height: 64px;
            }

            .topbar-left h2 {
                font-size: 18px;
            }

            .breadcrumb {
                display: none;
            }

            .user-menu {
                padding: 6px 12px;
            }

            .user-avatar {
                width: 36px;
                height: 36px;
                font-size: 14px;
            }

            .user-menu span {
                display: none;
            }

            .content-wrapper {
                padding: 16px;
            }

            .content-header h1 {
                font-size: 24px;
            }

            .content-header p {
                font-size: 14px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .stat-card {
                padding: 16px;
            }

            .stat-value {
                font-size: 24px;
            }

            .card {
                padding: 16px;
            }

            .btn {
                padding: 10px 16px;
                font-size: 13px;
            }

            /* Table responsive */
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .modern-table {
                min-width: 600px;
            }

            .modern-table th,
            .modern-table td {
                padding: 12px 8px;
                font-size: 13px;
            }

            /* Hide less important columns on mobile */
            .mobile-hide {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .content-wrapper {
                padding: 12px;
            }

            .content-header {
                margin-bottom: 20px;
            }

            .content-header h1 {
                font-size: 20px;
            }

            .stats-grid {
                gap: 8px;
            }

            .stat-card {
                padding: 12px;
            }

            .stat-icon {
                width: 44px;
                height: 44px;
                font-size: 20px;
                margin-bottom: 12px;
            }

            .stat-value {
                font-size: 20px;
            }

            .stat-label {
                font-size: 12px;
            }

            .card {
                padding: 12px;
            }

            .modern-table th,
            .modern-table td {
                padding: 10px 6px;
                font-size: 12px;
            }

            .btn {
                padding: 8px 12px;
                font-size: 12px;
            }

            /* Make forms stack vertically */
            form .flex,
            form .grid {
                flex-direction: column !important;
                grid-template-columns: 1fr !important;
            }

            /* Modal on mobile */
            .modal-content,
            .modern-card {
                width: 95% !important;
                max-width: 95% !important;
                margin: 10px !important;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.3s ease-out;
        }

        /* Table Styles */
        .modern-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .modern-table thead {
            background: var(--light-bg);
        }

        .modern-table th {
            padding: 16px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 2px solid var(--border);
        }

        .modern-table td {
            padding: 16px;
            border-bottom: 1px solid var(--border);
            color: var(--text-primary);
        }

        .modern-table tbody tr {
            transition: all 0.2s;
        }

        .modern-table tbody tr:hover {
            background: var(--light-bg);
        }

        /* Badge */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-success {
            background: #D1FAE5;
            color: #065F46;
        }

        .badge-warning {
            background: #FEF3C7;
            color: #92400E;
        }

        .badge-danger {
            background: #FEE2E2;
            color: #991B1B;
        }

        .badge-info {
            background: var(--light-bg);
            color: var(--text-primary);
            border: 1px solid var(--border);
        }
    </style>
    @stack('styles')
</head>
<body>
    @auth
    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>
    
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-logo">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 48px; height: 48px; background: var(--accent); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-store" style="color: #FFFFFF; font-size: 24px;"></i>
                </div>
                <div>
                    <div style="color: white; font-weight: 700; font-size: 18px;">Sistem POS</div>
                    <div style="color: rgba(255, 255, 255, 0.7); font-size: 12px;">
                        @if(auth()->user()->role === 'admin') Administrator
                        @elseif(auth()->user()->role === 'kasir') Kasir
                        @else Member @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="sidebar-menu">
            @if(auth()->user()->role === 'admin')
                <div class="menu-label">Main Menu</div>
                <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.products.index') }}" class="menu-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <i class="fas fa-box"></i>
                    <span>Produk</span>
                </a>
                <a href="{{ route('admin.categories.index') }}" class="menu-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <i class="fas fa-tags"></i>
                    <span>Kategori</span>
                </a>
                <a href="{{ route('admin.transactions.index') }}" class="menu-item {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}">
                    <i class="fas fa-receipt"></i>
                    <span>Transaksi</span>
                </a>
                <a href="{{ route('admin.vouchers.index') }}" class="menu-item {{ request()->routeIs('admin.vouchers.*') ? 'active' : '' }}">
                    <i class="fas fa-ticket-alt"></i>
                    <span>Voucher</span>
                </a>

                <div class="menu-label">User Management</div>
                <a href="{{ route('admin.members.index') }}" class="menu-item {{ request()->routeIs('admin.members.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Member</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-user-shield"></i>
                    <span>Users</span>
                </a>

                <div class="menu-label">Reports & Settings</div>
                <a href="{{ route('admin.reports.index') }}" class="menu-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i>
                    <span>Laporan</span>
                </a>
                <a href="{{ route('admin.settings.index') }}" class="menu-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i>
                    <span>Pengaturan</span>
                </a>

            @elseif(auth()->user()->role === 'kasir')
                <div class="menu-label">Main Menu</div>
                <a href="{{ route('kasir.dashboard') }}" class="menu-item {{ request()->routeIs('kasir.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('kasir.pos') }}" class="menu-item {{ request()->routeIs('kasir.pos') ? 'active' : '' }}">
                    <i class="fas fa-cash-register"></i>
                    <span>Point of Sale</span>
                </a>
                <a href="{{ route('kasir.transactions') }}" class="menu-item {{ request()->routeIs('kasir.transactions') ? 'active' : '' }}">
                    <i class="fas fa-receipt"></i>
                    <span>Transaksi</span>
                </a>
                <a href="{{ route('kasir.members.index') }}" class="menu-item {{ request()->routeIs('kasir.members.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Kelola Member</span>
                </a>
                <a href="{{ route('kasir.reports.index') }}" class="menu-item {{ request()->routeIs('kasir.reports.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i>
                    <span>Laporan</span>
                </a>

            @else
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

                <div class="menu-label">Main Menu</div>
                <a href="{{ route('member.dashboard') }}" class="menu-item {{ request()->routeIs('member.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('member.profile') }}" class="menu-item {{ request()->routeIs('member.profile*') ? 'active' : '' }}">
                    <i class="fas fa-user-circle"></i>
                    <span>Profil Saya</span>
                </a>
                <a href="{{ route('member.qr-code') }}" class="menu-item {{ request()->routeIs('member.qr-code') ? 'active' : '' }}">
                    <i class="fas fa-qrcode"></i>
                    <span>QR Member</span>
                </a>
                <a href="{{ route('member.transactions') }}" class="menu-item {{ request()->routeIs('member.transactions*') ? 'active' : '' }}">
                    <i class="fas fa-receipt"></i>
                    <span>Riwayat Transaksi</span>
                </a>
                <a href="{{ route('member.points-history') }}" class="menu-item {{ request()->routeIs('member.points-history') ? 'active' : '' }}">
                    <i class="fas fa-star"></i>
                    <span>Riwayat Poin</span>
                </a>
                <a href="{{ route('member.vouchers') }}" class="menu-item {{ request()->routeIs('member.vouchers*') || request()->routeIs('member.my-vouchers') ? 'active' : '' }}">
                    <i class="fas fa-ticket-alt"></i>
                    <span>Voucher</span>
                </a>
                <a href="{{ route('member.products') }}" class="menu-item {{ request()->routeIs('member.products') ? 'active' : '' }}">
                    <i class="fas fa-box"></i>
                    <span>Produk</span>
                </a>
            @endif
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Bar -->
        <div class="topbar">
            <div class="topbar-left">
                <button class="mobile-toggle btn-secondary" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="breadcrumb">
                    <i class="fas fa-home"></i>
                    @yield('breadcrumb', 'Dashboard')
                </div>
            </div>

            <div class="topbar-right">
                <div class="user-menu" onclick="toggleUserMenu()">
                    <div class="user-avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div style="text-align: left;">
                        <div style="font-weight: 600; font-size: 14px; color: #111827;">{{ auth()->user()->name }}</div>
                        <div style="font-size: 12px; color: #6B7280;">{{ ucfirst(auth()->user()->role) }}</div>
                    </div>
                    <i class="fas fa-chevron-down" style="color: #9CA3AF; font-size: 12px;"></i>
                </div>

                <!-- User Dropdown Menu -->
                <div id="userDropdown" style="display: none; position: absolute; top: 80px; right: 32px; background: white; border-radius: 12px; box-shadow: 0 8px 24px rgba(0,0,0,0.12); min-width: 200px; z-index: 100;">
                    <form method="POST" action="{{ route('logout') }}" style="padding: 8px;">
                        @csrf
                        <button type="submit" style="display: flex; align-items: center; gap: 12px; width: 100%; padding: 12px; border-radius: 8px; background: transparent; border: none; cursor: pointer; color: #DC2626; font-weight: 600; transition: all 0.2s;">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <div style="padding: 32px;">
            @yield('content')
        </div>
    </main>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('show');
        }

        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
        }

        function toggleUserMenu() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const userMenu = document.querySelector('.user-menu');
            const dropdown = document.getElementById('userDropdown');
            if (!userMenu.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.style.display = 'none';
            }
        });

        // Close sidebar when clicking menu item on mobile
        document.querySelectorAll('.menu-item').forEach(item => {
            item.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    closeSidebar();
                }
            });
        });

        // Auto close sidebar on window resize to desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                closeSidebar();
            }
        });
    </script>
    @endauth

    @guest
        @yield('content')
    @endguest

    @stack('scripts')
</body>
</html>
