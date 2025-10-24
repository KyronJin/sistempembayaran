<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar Member - Sistem POS</title>
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
                        <i class="fas fa-crown text-5xl mb-4"></i>
                        <h2 class="text-3xl font-bold mb-4">Member Premium</h2>
                        <p class="text-purple-100 mb-6">Bergabunglah dengan ribuan member yang sudah menikmati keuntungan berbelanja!</p>
                        
                        <div class="space-y-4 mb-8">
                            <div class="flex items-start">
                                <i class="fas fa-check-circle text-2xl mr-3 mt-1"></i>
                                <div>
                                    <div class="font-bold">Diskon Spesial</div>
                                    <div class="text-sm text-purple-100">Harga member untuk produk pilihan</div>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-check-circle text-2xl mr-3 mt-1"></i>
                                <div>
                                    <div class="font-bold">Poin Reward</div>
                                    <div class="text-sm text-purple-100">Setiap Rp 10.000 = 1 poin</div>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-check-circle text-2xl mr-3 mt-1"></i>
                                <div>
                                    <div class="font-bold">Promo Eksklusif</div>
                                    <div class="text-sm text-purple-100">Akses promo member only</div>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-check-circle text-2xl mr-3 mt-1"></i>
                                <div>
                                    <div class="font-bold">QR Member Card</div>
                                    <div class="text-sm text-purple-100">Belanja praktis dengan scan QR</div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-green-400 text-gray-900 rounded-xl p-4 text-center">
                            <div class="text-sm font-medium mb-1">Biaya Membership</div>
                            <div class="text-3xl font-bold">GRATIS</div>
                            <div class="text-sm">Pendaftaran Gratis!</div>
                        </div>

                        <div class="mt-6 bg-white bg-opacity-20 rounded-lg p-4">
                            <div class="text-sm font-medium mb-2">
                                <i class="fas fa-info-circle mr-2"></i>Cara Pembayaran:
                            </div>
                            <ul class="text-sm space-y-1 text-purple-100">
                                <li>• Bayar langsung di kasir</li>
                                <li>• Transfer bank (info dikirim email)</li>
                                <li>• Menunggu aktivasi admin</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Right: Registration Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-2xl p-8">
                        <div class="mb-8">
                            <h1 class="text-3xl font-bold text-gray-800 mb-2">
                                <i class="fas fa-user-plus text-purple-600 mr-3"></i>Formulir Pendaftaran Member
                            </h1>
                            <p class="text-gray-600">Isi data diri Anda dengan lengkap dan benar</p>
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

                        <form method="POST" action="{{ url('/register') }}">
                            @csrf
                            
                            <!-- Personal Info Section -->
                            <div class="mb-6">
                                <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b-2 border-purple-600">
                                    <i class="fas fa-user mr-2 text-purple-600"></i>Data Pribadi
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap <span class="text-red-600">*</span></label>
                                        <input type="text" name="name" value="{{ old('name') }}" required 
                                               class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:border-purple-500 focus:outline-none transition"
                                               placeholder="Masukkan nama lengkap Anda" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Email <span class="text-red-600">*</span></label>
                                        <input type="email" name="email" value="{{ old('email') }}" required 
                                               class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:border-purple-500 focus:outline-none transition"
                                               placeholder="nama@email.com" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Nomor HP <span class="text-red-600">*</span></label>
                                        <input type="text" name="phone" value="{{ old('phone') }}" required 
                                               class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:border-purple-500 focus:outline-none transition"
                                               placeholder="08xxxxxxxxxx" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Lahir <span class="text-gray-400 text-xs">(opsional)</span></label>
                                        <input type="date" name="birthdate" value="{{ old('birthdate') }}" 
                                               class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:border-purple-500 focus:outline-none transition" />
                                        <p class="text-xs text-gray-500 mt-1"><i class="fas fa-gift mr-1"></i>Dapatkan bonus poin di hari ulang tahun!</p>
                                    </div>
                                    <div class="md:col-span-1">
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Lengkap <span class="text-red-600">*</span></label>
                                        <textarea name="address" required rows="3"
                                                  class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:border-purple-500 focus:outline-none transition"
                                                  placeholder="Jl. Nama Jalan No. XX, RT/RW, Kelurahan, Kecamatan, Kota">{{ old('address') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Account Security Section -->
                            <div class="mb-6">
                                <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b-2 border-purple-600">
                                    <i class="fas fa-lock mr-2 text-purple-600"></i>Keamanan Akun
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Password <span class="text-red-600">*</span></label>
                                        <input type="password" name="password" required 
                                               class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:border-purple-500 focus:outline-none transition"
                                               placeholder="Minimal 8 karakter" />
                                        <p class="text-xs text-gray-500 mt-1"><i class="fas fa-info-circle mr-1"></i>Gunakan kombinasi huruf dan angka</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Konfirmasi Password <span class="text-red-600">*</span></label>
                                        <input type="password" name="password_confirmation" required 
                                               class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:border-purple-500 focus:outline-none transition"
                                               placeholder="Ketik ulang password" />
                                    </div>
                                </div>
                            </div>

                            <!-- Terms & Conditions -->
                            <div class="mb-6 bg-blue-50 rounded-lg p-4">
                                <label class="flex items-start cursor-pointer">
                                    <input type="checkbox" required class="mt-1 mr-3 w-5 h-5 text-purple-600" />
                                    <span class="text-sm text-gray-700">
                                        Saya telah membaca dan menyetujui <a href="#" class="text-purple-600 font-bold hover:underline">syarat dan ketentuan</a> yang berlaku. 
                                        Saya memahami bahwa membership akan aktif setelah disetujui oleh admin.
                                    </span>
                                </label>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-blue-600 text-white py-4 rounded-lg font-bold text-lg hover:shadow-2xl transition transform hover:scale-105">
                                <i class="fas fa-paper-plane mr-2"></i>Kirim Pendaftaran
                            </button>

                            <p class="mt-6 text-center text-gray-600">
                                Sudah punya akun? 
                                <a class="text-purple-600 font-bold hover:underline" href="{{ route('login') }}">
                                    <i class="fas fa-sign-in-alt mr-1"></i>Login di sini
                                </a>
                            </p>
                        </form>
                    </div>

                    <!-- Information Notice -->
                    <div class="mt-6 bg-blue-50 border-l-4 border-blue-400 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-600 text-2xl mr-3 mt-1"></i>
                            <div>
                                <h4 class="font-bold text-gray-800 mb-2">Informasi Pendaftaran</h4>
                                <ul class="text-sm text-gray-700 space-y-1">
                                    <li>• Pendaftaran member <strong>GRATIS</strong>, tanpa biaya apapun</li>
                                    <li>• Setelah mendaftar, status akun Anda akan <strong>"Pending"</strong></li>
                                    <li>• Admin akan meninjau dan mengaktivasi akun Anda</li>
                                    <li>• Proses aktivasi maksimal 1x24 jam</li>
                                    <li>• Anda akan mendapat notifikasi email saat akun sudah aktif</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
