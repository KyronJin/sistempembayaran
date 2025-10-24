<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Sistem POS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen py-12 px-4">
        <!-- Back Button -->
        <div class="container mx-auto max-w-6xl mb-6">
            <a href="{{ route('home') }}" class="inline-flex items-center text-purple-600 hover:text-purple-800 font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Beranda
            </a>
        </div>

        <div class="container mx-auto max-w-6xl">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left: Info Panel -->
                <div class="lg:col-span-1">
                    <div class="gradient-bg rounded-2xl p-8 text-white shadow-2xl sticky top-8">
                        <i class="fas fa-sign-in-alt text-5xl mb-4"></i>
                        <h2 class="text-3xl font-bold mb-4">Login Akun</h2>
                        <p class="text-purple-100 mb-6">Masuk untuk mengakses dashboard, transaksi, dan fitur member/admin/kasir.</p>
                        <div class="space-y-4 mb-8">
                            <div class="flex items-start">
                                <i class="fas fa-check-circle text-2xl mr-3 mt-1"></i>
                                <div>
                                    <div class="font-bold">Akses Dashboard</div>
                                    <div class="text-sm text-purple-100">Pantau transaksi & status akun</div>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-check-circle text-2xl mr-3 mt-1"></i>
                                <div>
                                    <div class="font-bold">Transaksi Cepat</div>
                                    <div class="text-sm text-purple-100">Belanja & kelola produk dengan mudah</div>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-check-circle text-2xl mr-3 mt-1"></i>
                                <div>
                                    <div class="font-bold">Keamanan Data</div>
                                    <div class="text-sm text-purple-100">Akun aman dengan autentikasi</div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-lg p-4 mt-6">
                            <div class="text-sm font-medium mb-2">
                                <i class="fas fa-info-circle mr-2"></i>Tips:
                            </div>
                            <ul class="text-sm space-y-1 text-purple-100">
                                <li>• Gunakan email & password yang terdaftar</li>
                                <li>• Hubungi admin jika lupa password</li>
                                <li>• Belum punya akun? Daftar sebagai member</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Right: Login Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-2xl p-8">
                        <div class="mb-8">
                            <h1 class="text-3xl font-bold text-gray-800 mb-2">
                                <i class="fas fa-sign-in-alt text-purple-600 mr-3"></i>Formulir Login
                            </h1>
                            <p class="text-gray-600">Masukkan email dan password akun Anda</p>
                        </div>

                        @if ($errors->any())
                            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    <div class="font-bold">Terjadi kesalahan:</div>
                                </div>
                                <ul class="list-disc list-inside text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded flex items-center gap-2">
                                <i class="fas fa-check-circle"></i>
                                <span>{{ session('success') }}</span>
                            </div>
                        @endif

                        <form method="POST" action="{{ url('/login') }}">
                            @csrf
                            <div class="mb-6">
                                <label class="block text-sm font-bold text-gray-700 mb-2" for="email">Email <span class="text-red-600">*</span></label>
                                <div class="relative">
                                    <span class="absolute left-3 top-3 text-gray-400"><i class="fas fa-envelope"></i></span>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autocomplete="email" class="pl-10 pr-3 py-3 block w-full border-2 border-gray-300 rounded-lg focus:border-purple-500 focus:outline-none text-gray-800 placeholder-gray-400" placeholder="you@email.com" />
                                </div>
                            </div>
                            <div class="mb-6">
                                <label class="block text-sm font-bold text-gray-700 mb-2" for="password">Password <span class="text-red-600">*</span></label>
                                <div class="relative">
                                    <span class="absolute left-3 top-3 text-gray-400"><i class="fas fa-lock"></i></span>
                                    <input type="password" name="password" id="password" required autocomplete="current-password" class="pl-10 pr-3 py-3 block w-full border-2 border-gray-300 rounded-lg focus:border-purple-500 focus:outline-none text-gray-800 placeholder-gray-400" placeholder="Password" />
                                </div>
                            </div>
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center">
                                    <input type="checkbox" name="remember" id="remember" class="mr-2 rounded border-gray-300" />
                                    <label for="remember" class="text-sm text-gray-600">Ingat saya</label>
                                </div>
                                <a href="#" class="text-xs text-gray-500 hover:text-purple-600">Lupa password?</a>
                            </div>
                            <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-blue-600 text-white py-4 rounded-lg font-bold text-lg hover:shadow-2xl transition transform hover:scale-105 flex items-center justify-center gap-2">
                                <i class="fas fa-sign-in-alt"></i> Masuk
                            </button>
                        </form>

                        <p class="mt-6 text-center text-gray-600">
                            Belum punya akun?
                            <a class="text-purple-600 font-bold hover:underline" href="{{ route('register') }}">
                                <i class="fas fa-user-plus mr-1"></i>Daftar Member
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
