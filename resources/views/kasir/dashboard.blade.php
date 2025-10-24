@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl shadow-lg p-8 text-white mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">
                    <i class="fas fa-cash-register mr-3"></i>Dashboard Kasir
                </h1>
                <p class="text-blue-100">Selamat datang, <strong>{{ auth()->user()->name }}</strong></p>
            </div>
            <div class="text-right">
                <div class="text-blue-100 text-sm">{{ now()->isoFormat('dddd, D MMMM Y') }}</div>
                <div class="text-2xl font-bold">{{ now()->format('H:i') }}</div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- POS Card -->
        <a href="{{ route('kasir.pos') }}" class="group">
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-8 text-white hover:shadow-xl transition transform hover:-translate-y-1">
                <div class="flex items-center justify-between mb-4">
                    <i class="fas fa-cash-register text-5xl opacity-80"></i>
                    <i class="fas fa-arrow-right text-2xl opacity-0 group-hover:opacity-100 transition"></i>
                </div>
                <h2 class="text-2xl font-bold mb-2">Point of Sale</h2>
                <p class="text-green-100">Mulai transaksi penjualan baru</p>
            </div>
        </a>

        <!-- Register Member Card -->
        <button onclick="openRegisterModal()" type="button" class="group text-left w-full">
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-8 text-white hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 hover:scale-105 cursor-pointer">
                <div class="flex items-center justify-between mb-4">
                    <i class="fas fa-user-plus text-5xl opacity-80 group-hover:opacity-100 transition-opacity"></i>
                    <i class="fas fa-arrow-right text-2xl opacity-0 group-hover:opacity-100 transition-all duration-300 transform group-hover:translate-x-2"></i>
                </div>
                <h2 class="text-2xl font-bold mb-2">Daftar Member Baru</h2>
                <p class="text-orange-100">Tambahkan pelanggan baru sebagai member</p>
            </div>
        </button>

        <!-- Transactions Card -->
        <a href="{{ route('kasir.transactions') }}" class="group">
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-8 text-white hover:shadow-xl transition transform hover:-translate-y-1">
                <div class="flex items-center justify-between mb-4">
                    <i class="fas fa-receipt text-5xl opacity-80"></i>
                    <i class="fas fa-arrow-right text-2xl opacity-0 group-hover:opacity-100 transition"></i>
                </div>
                <h2 class="text-2xl font-bold mb-2">Riwayat Transaksi</h2>
                <p class="text-purple-100">Lihat semua transaksi hari ini</p>
            </div>
        </a>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Today's Transactions -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-blue-600 text-xl"></i>
                </div>
            </div>
            <h3 class="text-gray-500 text-sm font-medium mb-1">Transaksi Hari Ini</h3>
            <p class="text-3xl font-bold text-gray-800">0</p>
            <p class="text-xs text-gray-400 mt-2">Data real-time</p>
        </div>

        <!-- Today's Sales -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-money-bill-wave text-green-600 text-xl"></i>
                </div>
            </div>
            <h3 class="text-gray-500 text-sm font-medium mb-1">Penjualan Hari Ini</h3>
            <p class="text-3xl font-bold text-gray-800">Rp 0</p>
            <p class="text-xs text-gray-400 mt-2">Total pendapatan</p>
        </div>

        <!-- Active Members -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-purple-600 text-xl"></i>
                </div>
            </div>
            <h3 class="text-gray-500 text-sm font-medium mb-1">Member Aktif</h3>
            <p class="text-3xl font-bold text-gray-800">0</p>
            <p class="text-xs text-gray-400 mt-2">Transaksi hari ini</p>
        </div>
    </div>

    <!-- Tips Section -->
    <div class="mt-8 bg-blue-50 border-l-4 border-blue-600 rounded-lg p-6">
        <div class="flex items-start">
            <i class="fas fa-lightbulb text-blue-600 text-2xl mr-4 mt-1"></i>
            <div>
                <h3 class="font-bold text-gray-800 mb-2">Tips Kasir</h3>
                <ul class="text-gray-600 text-sm space-y-1">
                    <li><i class="fas fa-check text-green-600 mr-2"></i>Gunakan QR scanner untuk mempercepat transaksi</li>
                    <li><i class="fas fa-check text-green-600 mr-2"></i>Tanyakan member untuk mendapatkan diskon otomatis</li>
                    <li><i class="fas fa-check text-green-600 mr-2"></i>Gunakan fitur Hold untuk transaksi yang tertunda</li>
                    <li><i class="fas fa-check text-green-600 mr-2"></i>Daftarkan pelanggan baru sebagai member untuk meningkatkan loyalitas</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Register New Member -->
<div id="register-member-modal" class="hidden fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 p-4 backdrop-blur-sm" style="animation: fadeIn 0.3s ease-out;">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl transform transition-all" style="animation: slideIn 0.3s ease-out;">
        <!-- Header with Gradient -->
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 p-6 rounded-t-2xl">
            <div class="flex items-center justify-between text-white">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-user-plus text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold">Daftar Member Baru</h3>
                        <p class="text-orange-100 text-sm">Tambahkan pelanggan baru ke sistem</p>
                    </div>
                </div>
                <button onclick="closeRegisterModal()" class="w-10 h-10 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg transition flex items-center justify-center">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        <!-- Form Content -->
        <form id="register-member-form" class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Nama Lengkap -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-user text-orange-500 mr-1"></i>
                        Nama Lengkap <span class="text-red-600">*</span>
                    </label>
                    <div class="relative group">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-orange-500 transition">
                            <i class="fas fa-user"></i>
                        </div>
                        <input type="text" name="name" required 
                               class="pl-11 pr-4 py-3.5 block w-full border-2 border-gray-200 rounded-xl focus:border-orange-500 focus:ring-2 focus:ring-orange-200 focus:outline-none transition shadow-sm hover:border-gray-300" 
                               placeholder="Contoh: Budi Santoso" />
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-envelope text-orange-500 mr-1"></i>
                        Email <span class="text-red-600">*</span>
                    </label>
                    <div class="relative group">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-orange-500 transition">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <input type="email" name="email" required 
                               class="pl-11 pr-4 py-3.5 block w-full border-2 border-gray-200 rounded-xl focus:border-orange-500 focus:ring-2 focus:ring-orange-200 focus:outline-none transition shadow-sm hover:border-gray-300" 
                               placeholder="email@contoh.com" />
                    </div>
                </div>

                <!-- No. HP -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-phone text-orange-500 mr-1"></i>
                        No. HP <span class="text-red-600">*</span>
                    </label>
                    <div class="relative group">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-orange-500 transition">
                            <i class="fas fa-phone"></i>
                        </div>
                        <input type="text" name="phone" required 
                               class="pl-11 pr-4 py-3.5 block w-full border-2 border-gray-200 rounded-xl focus:border-orange-500 focus:ring-2 focus:ring-orange-200 focus:outline-none transition shadow-sm hover:border-gray-300" 
                               placeholder="08123456789" />
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-lock text-orange-500 mr-1"></i>
                        Password <span class="text-gray-400 text-xs">(opsional)</span>
                    </label>
                    <div class="relative group">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-orange-500 transition">
                            <i class="fas fa-lock"></i>
                        </div>
                        <input type="password" name="password" minlength="8" 
                               class="pl-11 pr-4 py-3.5 block w-full border-2 border-gray-200 rounded-xl focus:border-orange-500 focus:ring-2 focus:ring-orange-200 focus:outline-none transition shadow-sm hover:border-gray-300" 
                               placeholder="member123" />
                    </div>
                    <p class="text-xs text-gray-500 mt-1.5 ml-1">
                        <i class="fas fa-info-circle mr-1"></i>Kosongkan untuk password default
                    </p>
                </div>
            </div>

            <!-- Alamat (Full Width) -->
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">
                    <i class="fas fa-map-marker-alt text-orange-500 mr-1"></i>
                    Alamat Lengkap <span class="text-red-600">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute left-4 top-4 text-gray-400 group-focus-within:text-orange-500 transition">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <textarea name="address" required rows="3" 
                              class="pl-11 pr-4 py-3.5 block w-full border-2 border-gray-200 rounded-xl focus:border-orange-500 focus:ring-2 focus:ring-orange-200 focus:outline-none transition shadow-sm hover:border-gray-300 resize-none" 
                              placeholder="Contoh: Jl. Sudirman No. 123, Kelurahan ABC, Kecamatan XYZ, Jakarta Selatan"></textarea>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 pt-2">
                <button type="submit" 
                        class="flex-1 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white px-6 py-4 rounded-xl font-bold text-lg transition transform hover:scale-[1.02] shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                    <i class="fas fa-save"></i>
                    <span>Simpan Member</span>
                </button>
                <button type="button" onclick="closeRegisterModal()" 
                        class="px-8 bg-gray-100 hover:bg-gray-200 text-gray-700 py-4 rounded-xl font-bold text-lg transition hover:shadow-md flex items-center justify-center gap-2">
                    <i class="fas fa-times"></i>
                    <span>Batal</span>
                </button>
            </div>
        </form>
        
        <!-- Success Message -->
        <div id="success-message" class="hidden m-6 mt-0">
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-500 rounded-xl p-6 shadow-lg">
                <div class="flex items-start">
                    <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                        <i class="fas fa-check text-white text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-lg font-bold text-green-800 mb-2">
                            <i class="fas fa-party-horn mr-2"></i>Member Berhasil Didaftarkan!
                        </h4>
                        <div id="member-info" class="text-green-700 bg-white bg-opacity-50 rounded-lg p-3 text-sm"></div>
                        <p class="text-green-600 text-xs mt-3">
                            <i class="fas fa-info-circle mr-1"></i>Halaman akan di-refresh otomatis...
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideIn {
    from { 
        opacity: 0;
        transform: translateY(-20px) scale(0.95);
    }
    to { 
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

#register-member-modal.show {
    display: flex !important;
    animation: fadeIn 0.3s ease-out;
}

#register-member-modal.show > div {
    animation: slideIn 0.3s ease-out;
}
</style>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

function openRegisterModal() {
    const modal = document.getElementById('register-member-modal');
    modal.classList.remove('hidden');
    modal.classList.add('show');
    document.getElementById('register-member-form').reset();
    document.getElementById('success-message').classList.add('hidden');
    document.getElementById('register-member-form').classList.remove('hidden');
    document.body.style.overflow = 'hidden'; // Prevent background scrolling
}

function closeRegisterModal() {
    const modal = document.getElementById('register-member-modal');
    modal.classList.remove('show');
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = ''; // Restore scrolling
    }, 300);
}

// Close modal when clicking outside
document.getElementById('register-member-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRegisterModal();
    }
});

// Close modal with ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('register-member-modal');
        if (!modal.classList.contains('hidden')) {
            closeRegisterModal();
        }
    }
});

// Handle form submission
document.getElementById('register-member-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Disable button and show loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-2"></i><span>Menyimpan...</span>';
    submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
    
    try {
        const response = await fetch('{{ route("kasir.register-member") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: formData
        });
        
        const result = await response.json();
        
        if (response.ok && result.success) {
            // Show success message with member details
            const memberInfo = `
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white font-bold">
                        ${result.member.name.charAt(0).toUpperCase()}
                    </div>
                    <div>
                        <div class="font-bold text-green-800">${result.member.name}</div>
                        <div class="text-sm text-green-600">${result.member.email} • ${result.member.phone}</div>
                    </div>
                </div>
            `;
            document.getElementById('member-info').innerHTML = memberInfo;
            document.getElementById('success-message').classList.remove('hidden');
            
            // Hide form
            this.classList.add('hidden');
            
            // Play success sound (optional)
            const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSl+zPLaizsKGGS57OihUBELTKXh8bllHgU2jdXzzn0vBSh+zPLaizsKGGS57OihUBELTKXh8bllHgU2jdXzzn0vBSh+zPLaizsKGGS57OihUBELTKXh8bllHgU2jdXzzn0vBQ==');
            audio.play().catch(() => {}); // Silent fail if audio doesn't play
            
            // Auto close modal after 3 seconds
            setTimeout(() => {
                closeRegisterModal();
                setTimeout(() => {
                    this.classList.remove('hidden');
                    location.reload(); // Refresh untuk update stats
                }, 300);
            }, 3000);
        } else {
            // Show detailed error
            let errorMsg = 'Gagal mendaftarkan member:\n\n';
            if (result.errors) {
                for (let field in result.errors) {
                    errorMsg += `• ${result.errors[field].join(', ')}\n`;
                }
            } else {
                errorMsg = result.message || 'Terjadi kesalahan. Silakan coba lagi.';
            }
            alert(errorMsg);
            
            // Re-enable button
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
            submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan koneksi. Silakan periksa koneksi internet Anda dan coba lagi.');
        
        // Re-enable button
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
        submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
    }
});
</script>

@endsection
