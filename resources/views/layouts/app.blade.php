<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Pembayaran')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .card-hover:hover { transform: translateY(-2px); transition: all 0.3s; }
        .btn-primary { @apply bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition; }
        .btn-secondary { @apply bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-medium transition; }
        .btn-danger { @apply bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition; }
        .btn-success { @apply bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    @auth
    <!-- Modern Navigation -->
    <nav class="bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <!-- Brand -->
                <div class="flex items-center space-x-3">
                    <i class="fas fa-store text-2xl"></i>
                    <span class="text-xl font-bold">Sistem POS</span>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-1">
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-lg hover:bg-blue-500 transition {{ request()->routeIs('admin.dashboard') ? 'bg-blue-500' : '' }}">
                            <i class="fas fa-chart-line mr-2"></i>Dashboard
                        </a>
                        <a href="{{ route('admin.products.index') }}" class="px-4 py-2 rounded-lg hover:bg-blue-500 transition {{ request()->routeIs('admin.products.*') ? 'bg-blue-500' : '' }}">
                            <i class="fas fa-box mr-2"></i>Produk
                        </a>
                        <a href="{{ route('admin.members.index') }}" class="px-4 py-2 rounded-lg hover:bg-blue-500 transition {{ request()->routeIs('admin.members.*') ? 'bg-blue-500' : '' }}">
                            <i class="fas fa-users mr-2"></i>Member
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 rounded-lg hover:bg-blue-500 transition {{ request()->routeIs('admin.users.*') ? 'bg-blue-500' : '' }}">
                            <i class="fas fa-user-shield mr-2"></i>Users
                        </a>
                        <a href="{{ route('admin.reports.index') }}" class="px-4 py-2 rounded-lg hover:bg-blue-500 transition {{ request()->routeIs('admin.reports.*') ? 'bg-blue-500' : '' }}">
                            <i class="fas fa-file-alt mr-2"></i>Laporan
                        </a>
                        <a href="{{ route('admin.settings.index') }}" class="px-4 py-2 rounded-lg hover:bg-blue-500 transition {{ request()->routeIs('admin.settings.*') ? 'bg-blue-500' : '' }}">
                            <i class="fas fa-cog mr-2"></i>Pengaturan
                        </a>
                    @elseif(auth()->user()->role === 'kasir')
                        <a href="{{ route('kasir.dashboard') }}" class="px-4 py-2 rounded-lg hover:bg-blue-500 transition {{ request()->routeIs('kasir.dashboard') ? 'bg-blue-500' : '' }}">
                            <i class="fas fa-chart-line mr-2"></i>Dashboard
                        </a>
                        <a href="{{ route('kasir.pos') }}" class="px-4 py-2 rounded-lg hover:bg-blue-500 transition {{ request()->routeIs('kasir.pos') ? 'bg-blue-500' : '' }}">
                            <i class="fas fa-cash-register mr-2"></i>POS
                        </a>
                        <a href="{{ route('kasir.transactions') }}" class="px-4 py-2 rounded-lg hover:bg-blue-500 transition {{ request()->routeIs('kasir.transactions') ? 'bg-blue-500' : '' }}">
                            <i class="fas fa-receipt mr-2"></i>Transaksi
                        </a>
                        <a href="{{ route('kasir.reports.index') }}" class="px-4 py-2 rounded-lg hover:bg-blue-500 transition {{ request()->routeIs('kasir.reports.*') ? 'bg-blue-500' : '' }}">
                            <i class="fas fa-file-alt mr-2"></i>Laporan
                        </a>
                    @else
                        <a href="{{ route('member.dashboard') }}" class="px-4 py-2 rounded-lg hover:bg-blue-500 transition">
                            <i class="fas fa-home mr-2"></i>Dashboard
                        </a>
                    @endif
                </div>

                <!-- User Menu -->
                <div class="flex items-center space-x-3">
                    <div class="hidden md:block text-right">
                        <div class="text-sm font-medium">{{ auth()->user()->name }}</div>
                        <div class="text-xs opacity-75">{{ ucfirst(auth()->user()->role) }}</div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 rounded-lg transition">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    @endauth

    <!-- Main Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    @auth
    <footer class="bg-white border-t mt-8">
        <div class="container mx-auto px-4 py-6">
            <div class="flex flex-col md:flex-row justify-between items-center text-sm text-gray-600">
                <div>&copy; {{ date('Y') }} Sistem POS - All Rights Reserved</div>
                <div class="mt-2 md:mt-0">Made with <i class="fas fa-heart text-red-500"></i> for better business</div>
            </div>
        </div>
    </footer>
    @endauth
</body>
</html>
