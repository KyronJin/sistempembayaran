<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pembayaran - Member & Kasir</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .hero-gradient { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .feature-card { transition: all 0.3s ease; }
        .feature-card:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,0.15); }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-store text-3xl text-purple-600"></i>
                    <span class="text-2xl font-bold text-gray-800">Sistem POS</span>
                </div>
                <div class="space-x-3">
                    <a href="{{ route('login') }}" class="px-6 py-2 text-purple-600 hover:text-purple-800 font-medium transition">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </a>
                    <a href="{{ route('register') }}" class="px-6 py-2 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-lg hover:shadow-lg transition transform hover:-translate-y-0.5">
                        <i class="fas fa-user-plus mr-2"></i>Daftar Member
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-gradient text-white py-20">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div>
                    <h1 class="text-5xl font-bold mb-6 leading-tight">
                        Belanja Lebih Hemat dengan Membership
                    </h1>
                    <p class="text-xl mb-8 text-purple-100">
                        Dapatkan diskon eksklusif, kumpulkan poin, dan nikmati berbagai keuntungan lainnya dengan pendaftaran GRATIS!
                    </p>
                    <div class="flex space-x-4">
                        <a href="{{ route('register') }}" class="px-8 py-4 bg-white text-purple-600 rounded-lg font-bold text-lg hover:bg-gray-100 transition shadow-xl transform hover:scale-105">
                            <i class="fas fa-rocket mr-2"></i>Daftar Sekarang
                        </a>
                        <a href="#benefits" class="px-8 py-4 border-2 border-white text-white rounded-lg font-bold text-lg hover:bg-white hover:text-purple-600 transition">
                            Lihat Benefit
                        </a>
                    </div>
                    
                    <!-- Price Tag -->
                    <div class="mt-8 inline-block bg-green-400 text-gray-900 px-6 py-3 rounded-full font-bold text-xl shadow-lg">
                        <i class="fas fa-gift mr-2"></i>Pendaftaran 100% GRATIS!
                    </div>
                </div>

                <!-- Right Image/Illustration -->
                <div class="hidden lg:block">
                    <div class="bg-white bg-opacity-10 backdrop-blur-lg rounded-2xl p-8 shadow-2xl">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-white rounded-xl p-6 text-center transform hover:scale-105 transition">
                                <i class="fas fa-percentage text-4xl text-green-600 mb-3"></i>
                                <div class="font-bold text-gray-800">Diskon Member</div>
                                <div class="text-sm text-gray-600">Hingga 15%</div>
                            </div>
                            <div class="bg-white rounded-xl p-6 text-center transform hover:scale-105 transition">
                                <i class="fas fa-coins text-4xl text-yellow-600 mb-3"></i>
                                <div class="font-bold text-gray-800">Poin Reward</div>
                                <div class="text-sm text-gray-600">Setiap Belanja</div>
                            </div>
                            <div class="bg-white rounded-xl p-6 text-center transform hover:scale-105 transition">
                                <i class="fas fa-gift text-4xl text-red-600 mb-3"></i>
                                <div class="font-bold text-gray-800">Promo Eksklusif</div>
                                <div class="text-sm text-gray-600">Member Only</div>
                            </div>
                            <div class="bg-white rounded-xl p-6 text-center transform hover:scale-105 transition">
                                <i class="fas fa-qrcode text-4xl text-blue-600 mb-3"></i>
                                <div class="font-bold text-gray-800">QR Member</div>
                                <div class="text-sm text-gray-600">Praktis & Cepat</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section id="benefits" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Keuntungan Member Premium</h2>
                <p class="text-xl text-gray-600">Investasi terbaik untuk penghematan jangka panjang</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="feature-card bg-gradient-to-br from-green-50 to-green-100 rounded-2xl p-8 border-2 border-green-200">
                    <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-tags text-3xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Harga Member Spesial</h3>
                    <p class="text-gray-600 mb-4">Nikmati harga khusus member untuk produk-produk pilihan. Hemat hingga puluhan ribu setiap belanja!</p>
                    <ul class="space-y-2 text-gray-700">
                        <li><i class="fas fa-check text-green-600 mr-2"></i>Diskon produk tertentu</li>
                        <li><i class="fas fa-check text-green-600 mr-2"></i>Harga member 5-15% lebih murah</li>
                        <li><i class="fas fa-check text-green-600 mr-2"></i>Update produk diskon mingguan</li>
                    </ul>
                </div>

                <!-- Feature 2 -->
                <div class="feature-card bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl p-8 border-2 border-purple-200">
                    <div class="w-16 h-16 bg-purple-500 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-star text-3xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Poin Reward Berlimpah</h3>
                    <p class="text-gray-600 mb-4">Setiap pembelanjaan Rp 10.000 = 1 poin. Tukar poin dengan diskon atau produk gratis!</p>
                    <ul class="space-y-2 text-gray-700">
                        <li><i class="fas fa-check text-purple-600 mr-2"></i>1 Poin = Rp 1.000</li>
                        <li><i class="fas fa-check text-purple-600 mr-2"></i>Poin tidak hangus</li>
                        <li><i class="fas fa-check text-purple-600 mr-2"></i>Bonus poin di hari ulang tahun</li>
                    </ul>
                </div>

                <!-- Feature 3 -->
                <div class="feature-card bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-8 border-2 border-blue-200">
                    <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-bolt text-3xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Promo Member Eksklusif</h3>
                    <p class="text-gray-600 mb-4">Akses promo khusus member yang tidak tersedia untuk customer biasa. Penawaran terbatas!</p>
                    <ul class="space-y-2 text-gray-700">
                        <li><i class="fas fa-check text-blue-600 mr-2"></i>Flash sale member only</li>
                        <li><i class="fas fa-check text-blue-600 mr-2"></i>Promo bundling spesial</li>
                        <li><i class="fas fa-check text-blue-600 mr-2"></i>Early access produk baru</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="py-20 bg-gray-100">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Cara Daftar Member</h2>
                <p class="text-xl text-gray-600">Mudah dan cepat, hanya 3 langkah!</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                <!-- Step 1 -->
                <div class="bg-white rounded-2xl p-8 text-center shadow-lg relative">
                    <div class="absolute -top-6 left-1/2 transform -translate-x-1/2 w-12 h-12 bg-purple-600 text-white rounded-full flex items-center justify-center font-bold text-xl">
                        1
                    </div>
                    <div class="mt-6">
                        <i class="fas fa-user-plus text-5xl text-purple-600 mb-4"></i>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Daftar Akun</h3>
                        <p class="text-gray-600">Isi formulir pendaftaran dengan data diri lengkap Anda</p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="bg-white rounded-2xl p-8 text-center shadow-lg relative">
                    <div class="absolute -top-6 left-1/2 transform -translate-x-1/2 w-12 h-12 bg-purple-600 text-white rounded-full flex items-center justify-center font-bold text-xl">
                        2
                    </div>
                    <div class="mt-6">
                        <i class="fas fa-user-check text-5xl text-purple-600 mb-4"></i>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Menunggu Persetujuan</h3>
                        <p class="text-gray-600">Admin akan meninjau pendaftaran Anda (maksimal 1x24 jam)</p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="bg-white rounded-2xl p-8 text-center shadow-lg relative">
                    <div class="absolute -top-6 left-1/2 transform -translate-x-1/2 w-12 h-12 bg-purple-600 text-white rounded-full flex items-center justify-center font-bold text-xl">
                        3
                    </div>
                    <div class="mt-6">
                        <i class="fas fa-check-circle text-5xl text-purple-600 mb-4"></i>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Aktivasi & Belanja</h3>
                        <p class="text-gray-600">Admin akan aktivasi akun, lalu Anda bisa langsung mulai belanja!</p>
                    </div>
                </div>
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('register') }}" class="inline-block px-12 py-4 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-lg font-bold text-xl hover:shadow-2xl transition transform hover:scale-105">
                    <i class="fas fa-arrow-right mr-2"></i>Daftar Sekarang
                </a>
            </div>
        </div>
    </section>

    <!-- Staff Login Section -->
    <section class="py-16 bg-gradient-to-r from-gray-800 to-gray-900 text-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Admin Login -->
                <div class="bg-white bg-opacity-10 backdrop-blur-lg rounded-2xl p-8 text-center hover:bg-opacity-20 transition">
                    <i class="fas fa-user-shield text-5xl mb-4"></i>
                    <h3 class="text-2xl font-bold mb-3">Portal Admin</h3>
                    <p class="text-gray-300 mb-6">Kelola produk, member, dan laporan penjualan</p>
                    <a href="{{ route('login') }}" class="inline-block px-8 py-3 bg-red-600 hover:bg-red-700 rounded-lg font-bold transition">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login Admin
                    </a>
                </div>

                <!-- Kasir Login -->
                <div class="bg-white bg-opacity-10 backdrop-blur-lg rounded-2xl p-8 text-center hover:bg-opacity-20 transition">
                    <i class="fas fa-cash-register text-5xl mb-4"></i>
                    <h3 class="text-2xl font-bold mb-3">Portal Kasir</h3>
                    <p class="text-gray-300 mb-6">Akses sistem POS untuk transaksi penjualan</p>
                    <a href="{{ route('login') }}" class="inline-block px-8 py-3 bg-blue-600 hover:bg-blue-700 rounded-lg font-bold transition">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login Kasir
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4 max-w-4xl">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Pertanyaan Umum</h2>
            </div>

            <div class="space-y-4">
                <div class="bg-gray-50 rounded-lg p-6 hover:bg-gray-100 transition">
                    <h4 class="font-bold text-lg text-gray-800 mb-2">
                        <i class="fas fa-question-circle text-purple-600 mr-2"></i>
                        Apakah ada biaya untuk menjadi member?
                    </h4>
                    <p class="text-gray-600">Tidak ada biaya! Pendaftaran member 100% GRATIS. Anda hanya perlu mendaftar dan menunggu persetujuan admin.</p>
                </div>

                <div class="bg-gray-50 rounded-lg p-6 hover:bg-gray-100 transition">
                    <h4 class="font-bold text-lg text-gray-800 mb-2">
                        <i class="fas fa-question-circle text-purple-600 mr-2"></i>
                        Berapa lama masa aktif membership?
                    </h4>
                    <p class="text-gray-600">Membership berlaku permanen setelah diaktivasi. Nikmati benefit member selamanya tanpa biaya tambahan!</p>
                </div>

                <div class="bg-gray-50 rounded-lg p-6 hover:bg-gray-100 transition">
                    <h4 class="font-bold text-lg text-gray-800 mb-2">
                        <i class="fas fa-question-circle text-purple-600 mr-2"></i>
                        Apakah poin bisa hangus?
                    </h4>
                    <p class="text-gray-600">Tidak! Poin Anda tidak akan hangus selama membership masih aktif. Kumpulkan sebanyak-banyaknya!</p>
                </div>

                <div class="bg-gray-50 rounded-lg p-6 hover:bg-gray-100 transition">
                    <h4 class="font-bold text-lg text-gray-800 mb-2">
                        <i class="fas fa-question-circle text-purple-600 mr-2"></i>
                        Bagaimana cara menggunakan QR member?
                    </h4>
                    <p class="text-gray-600">Setelah akun diaktivasi, Anda bisa akses QR code member di dashboard. Tunjukkan QR ke kasir saat belanja untuk otomatis dapat diskon dan poin.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="container mx-auto px-4 text-center">
            <div class="flex items-center justify-center space-x-3 mb-4">
                <i class="fas fa-store text-3xl text-purple-400"></i>
                <span class="text-2xl font-bold">Sistem POS</span>
            </div>
            <p class="text-gray-400 mb-6">Solusi pembayaran modern untuk toko Anda</p>
            <div class="flex justify-center space-x-6 mb-6">
                <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-facebook text-2xl"></i></a>
                <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-instagram text-2xl"></i></a>
                <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-whatsapp text-2xl"></i></a>
                <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-twitter text-2xl"></i></a>
            </div>
            <p class="text-gray-500 text-sm">&copy; 2025 Sistem POS. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if(target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    </script>
</body>
</html>



        @if (Route::has('login'))
            <div class="h-14.5 hidden lg:block"></div>
        @endif
    </body>
</html>
