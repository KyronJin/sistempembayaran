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
        :root {
            --primary: #FFFFFF;
            --secondary: #1F2937;
            --accent: #FF6F00;
            --accent-hover: #F57C00;
            --light-bg: #F9FAFB;
            --border: #E5E7EB;
            --text-primary: #1F2937;
            --text-secondary: #6B7280;
        }
        
        * {
            transition: all 0.3s ease;
        }
        
        body {
            background: var(--light-bg);
            animation: fadeIn 0.5s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .gradient-bg { 
            background: linear-gradient(135deg, var(--secondary) 0%, #111827 100%);
        }
        
        .info-card {
            animation: slideInLeft 0.8s ease-out;
        }
        
        .form-card {
            animation: slideInRight 0.8s ease-out;
        }
        
        .btn-primary {
            background: var(--accent);
            color: white;
        }
        .btn-primary:hover {
            background: var(--accent-hover);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 111, 0, 0.3);
        }
        .btn-primary:active {
            transform: translateY(0);
        }
        .text-primary {
            color: var(--accent);
        }
        .border-primary {
            border-color: var(--accent);
        }
        
        .feature-item {
            transition: all 0.3s ease;
        }
        .feature-item:hover {
            transform: translateX(10px);
        }
    </style>
</head>
<body>
    <div class="min-h-screen py-12 px-4">
        <!-- Back Button -->
        <div class="container mx-auto max-w-6xl mb-6">
            <a href="{{ route('home') }}" class="inline-flex items-center font-semibold transition hover:scale-105" style="color: var(--accent);">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Beranda
            </a>
        </div>

        <div class="container mx-auto max-w-6xl">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left: Info Panel -->
                <div class="lg:col-span-1">
                    <div class="gradient-bg rounded-2xl p-8 text-white shadow-2xl sticky top-8 info-card">
                        <i class="fas fa-crown text-5xl mb-4 text-primary"></i>
                        <h2 class="text-3xl font-bold mb-4">Member Premium</h2>
                        <p class="mb-6 opacity-90">Bergabunglah dengan ribuan member yang sudah menikmati keuntungan berbelanja!</p>
                        
                        <div class="space-y-4 mb-8">
                            <div class="flex items-start feature-item">
                                <i class="fas fa-check-circle text-2xl mr-3 mt-1 text-primary"></i>
                                <div>
                                    <div class="font-bold">Diskon Spesial</div>
                                    <div class="text-sm opacity-80">Harga member untuk produk pilihan</div>
                                </div>
                            </div>
                            <div class="flex items-start feature-item">
                                <i class="fas fa-check-circle text-2xl mr-3 mt-1 text-primary"></i>
                                <div>
                                    <div class="font-bold">Poin Reward</div>
                                    <div class="text-sm opacity-80">Setiap Rp 10.000 = 1 poin</div>
                                </div>
                            </div>
                            <div class="flex items-start feature-item">
                                <i class="fas fa-check-circle text-2xl mr-3 mt-1 text-primary"></i>
                                <div>
                                    <div class="font-bold">Promo Eksklusif</div>
                                    <div class="text-sm opacity-80">Akses promo member only</div>
                                </div>
                            </div>
                            <div class="flex items-start feature-item">
                                <i class="fas fa-check-circle text-2xl mr-3 mt-1 text-primary"></i>
                                <div>
                                    <div class="font-bold">QR Member Card</div>
                                    <div class="text-sm opacity-80">Belanja praktis dengan scan QR</div>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-xl p-4 text-center" style="background: var(--accent); color: white;">
                            <div class="text-sm font-medium mb-1">Biaya Membership</div>
                            <div class="text-3xl font-bold">GRATIS</div>
                            <div class="text-sm">Pendaftaran Gratis!</div>
                        </div>

                        <div class="mt-6 rounded-lg p-4" style="background: rgba(255, 255, 255, 0.1);">
                            <div class="text-sm font-medium mb-2">
                                <i class="fas fa-info-circle mr-2"></i>Cara Aktivasi:
                            </div>
                            <ul class="text-sm space-y-1 opacity-90">
                                <li>• Isi formulir pendaftaran</li>
                                <li>• Tunggu persetujuan admin (maks 1x24 jam)</li>
                                <li>• Aktivasi akun & mulai belanja</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Right: Registration Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-2xl p-8 form-card">
                        <div class="mb-8">
                            <h1 class="text-3xl font-bold mb-2" style="color: var(--text-primary);">
                                <i class="fas fa-user-plus mr-3" style="color: var(--accent);"></i>Formulir Pendaftaran Member
                            </h1>
                            <p style="color: var(--text-secondary);">Isi data diri Anda dengan lengkap dan benar</p>
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
                                <h3 class="text-lg font-bold mb-4 pb-2 border-b-2" style="color: var(--text-primary); border-color: var(--accent);">
                                    <i class="fas fa-user mr-2" style="color: var(--accent);"></i>Data Pribadi
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-bold mb-2" style="color: var(--text-primary);">Nama Lengkap <span style="color: #EF4444;">*</span></label>
                                        <input type="text" name="name" value="{{ old('name') }}" required 
                                               class="w-full rounded-lg px-4 py-3 focus:outline-none"
                                               style="border: 2px solid var(--border);"
                                               onfocus="this.style.borderColor='var(--accent)'; this.style.boxShadow='0 0 0 3px rgba(255, 111, 0, 0.1)'"
                                               onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'"
                                               placeholder="Masukkan nama lengkap Anda" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold mb-2" style="color: var(--text-primary);">Email <span style="color: #EF4444;">*</span></label>
                                        <input type="email" name="email" value="{{ old('email') }}" required 
                                               class="w-full rounded-lg px-4 py-3 focus:outline-none"
                                               style="border: 2px solid var(--border);"
                                               onfocus="this.style.borderColor='var(--accent)'; this.style.boxShadow='0 0 0 3px rgba(255, 111, 0, 0.1)'"
                                               onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'"
                                               placeholder="nama@email.com" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold mb-2" style="color: var(--text-primary);">Nomor HP <span style="color: #EF4444;">*</span></label>
                                        <input type="text" name="phone" value="{{ old('phone') }}" required 
                                               class="w-full rounded-lg px-4 py-3 focus:outline-none"
                                               style="border: 2px solid var(--border);"
                                               onfocus="this.style.borderColor='var(--accent)'; this.style.boxShadow='0 0 0 3px rgba(255, 111, 0, 0.1)'"
                                               onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'"
                                               placeholder="08xxxxxxxxxx" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold mb-2" style="color: var(--text-primary);">Tanggal Lahir <span style="color: var(--text-secondary); font-size: 0.75rem;">(opsional)</span></label>
                                        <input type="date" name="birthdate" value="{{ old('birthdate') }}" 
                                               class="w-full rounded-lg px-4 py-3 focus:outline-none"
                                               style="border: 2px solid var(--border);"
                                               onfocus="this.style.borderColor='var(--accent)'; this.style.boxShadow='0 0 0 3px rgba(255, 111, 0, 0.1)'"
                                               onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'" />
                                        <p class="text-xs mt-1" style="color: var(--text-secondary);"><i class="fas fa-gift mr-1" style="color: var(--accent);"></i>Dapatkan bonus poin di hari ulang tahun!</p>
                                    </div>
                                    <div class="md:col-span-1">
                                        <label class="block text-sm font-bold mb-2" style="color: var(--text-primary);">Alamat Lengkap <span style="color: #EF4444;">*</span></label>
                                        <textarea name="address" required rows="3"
                                                  class="w-full rounded-lg px-4 py-3 focus:outline-none"
                                                  style="border: 2px solid var(--border);"
                                                  onfocus="this.style.borderColor='var(--accent)'; this.style.boxShadow='0 0 0 3px rgba(255, 111, 0, 0.1)'"
                                                  onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'"
                                                  placeholder="Jl. Nama Jalan No. XX, RT/RW, Kelurahan, Kecamatan, Kota">{{ old('address') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Account Security Section -->
                            <div class="mb-6">
                                <h3 class="text-lg font-bold mb-4 pb-2 border-b-2" style="color: var(--text-primary); border-color: var(--accent);">
                                    <i class="fas fa-lock mr-2" style="color: var(--accent);"></i>Keamanan Akun
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-bold mb-2" style="color: var(--text-primary);">Password <span style="color: #EF4444;">*</span></label>
                                        <input type="password" name="password" required 
                                               class="w-full rounded-lg px-4 py-3 focus:outline-none"
                                               style="border: 2px solid var(--border);"
                                               onfocus="this.style.borderColor='var(--accent)'; this.style.boxShadow='0 0 0 3px rgba(255, 111, 0, 0.1)'"
                                               onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'"
                                               placeholder="Minimal 8 karakter" />
                                        <p class="text-xs mt-1" style="color: var(--text-secondary);"><i class="fas fa-info-circle mr-1"></i>Gunakan kombinasi huruf dan angka</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold mb-2" style="color: var(--text-primary);">Konfirmasi Password <span style="color: #EF4444;">*</span></label>
                                        <input type="password" name="password_confirmation" required 
                                               class="w-full rounded-lg px-4 py-3 focus:outline-none"
                                               style="border: 2px solid var(--border);"
                                               onfocus="this.style.borderColor='var(--accent)'; this.style.boxShadow='0 0 0 3px rgba(255, 111, 0, 0.1)'"
                                               onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'"
                                               placeholder="Ketik ulang password" />
                                    </div>
                                </div>
                            </div>

                            <!-- Terms & Conditions -->
                            <div class="mb-6 rounded-lg p-4" style="background: var(--light-bg); border: 2px solid var(--border);">
                                <label class="flex items-start cursor-pointer">
                                    <input type="checkbox" required class="mt-1 mr-3 w-5 h-5" style="accent-color: var(--accent);" />
                                    <span class="text-sm" style="color: var(--text-primary);">
                                        Saya telah membaca dan menyetujui <a href="#" class="font-bold hover:underline" style="color: var(--accent);">syarat dan ketentuan</a> yang berlaku. 
                                        Saya memahami bahwa membership akan aktif setelah disetujui oleh admin.
                                    </span>
                                </label>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="w-full btn-primary py-4 rounded-lg font-bold text-lg transition transform">
                                <i class="fas fa-paper-plane mr-2"></i>Kirim Pendaftaran
                            </button>

                            <p class="mt-6 text-center" style="color: var(--text-secondary);">
                                Sudah punya akun? 
                                <a class="font-bold hover:underline transition" style="color: var(--accent);" href="{{ route('member.login') }}">
                                    <i class="fas fa-sign-in-alt mr-1"></i>Login di sini
                                </a>
                            </p>
                        </form>
                    </div>

                    <!-- Information Notice -->
                    <div class="mt-6 border-l-4 rounded-lg p-4" style="background: #FFF7ED; border-color: var(--accent);">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-2xl mr-3 mt-1" style="color: var(--accent);"></i>
                            <div>
                                <h4 class="font-bold mb-2" style="color: var(--text-primary);">Informasi Pendaftaran</h4>
                                <ul class="text-sm space-y-1" style="color: var(--text-primary);">
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
