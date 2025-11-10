@extends('layouts.modern')

@section('title', 'Dashboard Kasir')
@section('breadcrumb')
    <i class="fas fa-home mr-2"></i>
    <span class="breadcrumb-active">Dashboard</span>
@endsection

@section('content')
<div class="fade-in">
    <!-- Welcome Section -->
    <div class="card" style="background: linear-gradient(135deg, #4F46E5 0%, #6366F1 100%); margin-bottom: 32px;">
        <div style="display: flex; justify-content: space-between; align-items: center; color: white;">
            <div>
                <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">
                    Selamat datang kembali,
                </div>
                <h1 style="font-size: 32px; font-weight: 700; margin-bottom: 8px;">
                    {{ auth()->user()->name }}
                </h1>
                <p style="opacity: 0.9;">Siap melayani pelanggan hari ini!</p>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 14px; opacity: 0.9;" id="kasirCurrentDate">{{ now()->isoFormat('dddd, D MMMM Y') }}</div>
                <div style="font-size: 36px; font-weight: 700; margin-top: 4px;" id="kasirCurrentTime">{{ now()->format('H:i') }}</div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div style="margin-bottom: 32px;">
        <h2 style="font-size: 20px; font-weight: 700; color: #111827; margin-bottom: 16px;">
            <i class="fas fa-bolt" style="color: var(--accent); margin-right: 8px;"></i>
            Quick Actions
        </h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
            <!-- POS Card -->
            <a href="{{ route('kasir.pos') }}" class="card card-hover" style="background: linear-gradient(135deg, #10B981 0%, #059669 100%); text-decoration: none; position: relative; overflow: hidden;">
                <div style="position: absolute; top: -20px; right: -20px; width: 120px; height: 120px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
                <div style="position: relative; z-index: 1;">
                    <div style="width: 64px; height: 64px; background: rgba(255,255,255,0.2); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                        <i class="fas fa-cash-register" style="font-size: 32px; color: white;"></i>
                    </div>
                    <h3 style="font-size: 24px; font-weight: 700; color: white; margin-bottom: 8px;">Point of Sale</h3>
                    <p style="color: rgba(255,255,255,0.9); font-size: 14px;">Mulai transaksi penjualan baru</p>
                    <div style="margin-top: 16px; display: flex; align-items: center; color: white; font-weight: 600;">
                        <span>Buka POS</span>
                        <i class="fas fa-arrow-right" style="margin-left: 8px;"></i>
                    </div>
                </div>
            </a>

            <!-- Register Member Card -->
            <button onclick="openRegisterModal()" class="card card-hover" style="background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); border: none; text-align: left; cursor: pointer; position: relative; overflow: hidden;">
                <div style="position: absolute; top: -20px; right: -20px; width: 120px; height: 120px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
                <div style="position: relative; z-index: 1;">
                    <div style="width: 64px; height: 64px; background: rgba(255,255,255,0.2); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                        <i class="fas fa-user-plus" style="font-size: 32px; color: white;"></i>
                    </div>
                    <h3 style="font-size: 24px; font-weight: 700; color: white; margin-bottom: 8px;">Daftar Member</h3>
                    <p style="color: rgba(255,255,255,0.9); font-size: 14px;">Tambahkan pelanggan baru</p>
                    <div style="margin-top: 16px; display: flex; align-items: center; color: white; font-weight: 600;">
                        <span>Daftar Sekarang</span>
                        <i class="fas fa-arrow-right" style="margin-left: 8px;"></i>
                    </div>
                </div>
            </button>

            <!-- Transactions Card -->
            <a href="{{ route('kasir.transactions') }}" class="card card-hover" style="background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%); text-decoration: none; position: relative; overflow: hidden;">
                <div style="position: absolute; top: -20px; right: -20px; width: 120px; height: 120px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
                <div style="position: relative; z-index: 1;">
                    <div style="width: 64px; height: 64px; background: rgba(255,255,255,0.2); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                        <i class="fas fa-receipt" style="font-size: 32px; color: white;"></i>
                    </div>
                    <h3 style="font-size: 24px; font-weight: 700; color: white; margin-bottom: 8px;">Riwayat Transaksi</h3>
                    <p style="color: rgba(255,255,255,0.9); font-size: 14px;">Lihat semua transaksi hari ini</p>
                    <div style="margin-top: 16px; display: flex; align-items: center; color: white; font-weight: 600;">
                        <span>Lihat Riwayat</span>
                        <i class="fas fa-arrow-right" style="margin-left: 8px;"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Stats Overview -->
    <div style="margin-bottom: 32px;">
        <h2 style="font-size: 20px; font-weight: 700; color: #111827; margin-bottom: 16px;">
            <i class="fas fa-chart-line" style="color: var(--primary); margin-right: 8px;"></i>
            Statistik Hari Ini
        </h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            <!-- Total Transactions -->
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #DBEAFE 0%, #BFDBFE 100%); color: #1E40AF;">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-label">Total Transaksi</div>
                <div class="stat-value">{{ $today_transactions ?? 0 }}</div>
                <div style="margin-top: 12px; font-size: 13px; color: #10B981; font-weight: 600;">
                    <i class="fas fa-arrow-up mr-1"></i> +12% dari kemarin
                </div>
            </div>

            <!-- Total Sales -->
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #D1FAE5 0%, #A7F3D0 100%); color: #065F46;">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="stat-label">Total Penjualan</div>
                <div class="stat-value">{{ number_format($today_sales ?? 0, 0, ',', '.') }}</div>
                <div style="margin-top: 4px; font-size: 12px; color: #6B7280;">Rupiah</div>
            </div>

            <!-- Active Members -->
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #E9D5FF 0%, #D8B4FE 100%); color: #6B21A8;">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-label">Member Aktif</div>
                <div class="stat-value">{{ $active_members ?? 0 }}</div>
                <div style="margin-top: 12px; font-size: 13px; color: #6B7280;">
                    <i class="fas fa-info-circle mr-1"></i> Transaksi hari ini
                </div>
            </div>

            <!-- Average Transaction -->
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #FED7AA 0%, #FDBA74 100%); color: #92400E;">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <div class="stat-label">Rata-rata Transaksi</div>
                <div class="stat-value">{{ number_format($avg_transaction ?? 0, 0, ',', '.') }}</div>
                <div style="margin-top: 4px; font-size: 12px; color: #6B7280;">Per transaksi</div>
            </div>
        </div>
    </div>

    <!-- Tips & Info -->
    <div class="card" style="background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%); border-left: 4px solid #3B82F6;">
        <div style="display: flex; gap: 20px;">
            <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%); border-radius: 14px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <i class="fas fa-lightbulb" style="font-size: 28px; color: white;"></i>
            </div>
            <div style="flex: 1;">
                <h3 style="font-size: 18px; font-weight: 700; color: #1E3A8A; margin-bottom: 12px;">
                    Tips untuk Kasir
                </h3>
                <ul style="list-style: none; padding: 0; margin: 0; color: #1E40AF;">
                    <li style="padding: 8px 0; border-bottom: 1px solid rgba(59, 130, 246, 0.2); display: flex; align-items: start; gap: 12px;">
                        <i class="fas fa-check-circle" style="color: #10B981; margin-top: 2px;"></i>
                        <span>Gunakan QR scanner untuk mempercepat transaksi</span>
                    </li>
                    <li style="padding: 8px 0; border-bottom: 1px solid rgba(59, 130, 246, 0.2); display: flex; align-items: start; gap: 12px;">
                        <i class="fas fa-check-circle" style="color: #10B981; margin-top: 2px;"></i>
                        <span>Tanyakan member untuk mendapatkan diskon otomatis</span>
                    </li>
                    <li style="padding: 8px 0; border-bottom: 1px solid rgba(59, 130, 246, 0.2); display: flex; align-items: start; gap: 12px;">
                        <i class="fas fa-check-circle" style="color: #10B981; margin-top: 2px;"></i>
                        <span>Gunakan fitur Hold untuk transaksi yang tertunda</span>
                    </li>
                    <li style="padding: 8px 0; display: flex; align-items: start; gap: 12px;">
                        <i class="fas fa-check-circle" style="color: #10B981; margin-top: 2px;"></i>
                        <span>Daftarkan pelanggan baru sebagai member untuk meningkatkan loyalitas</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Register New Member -->
<div id="register-member-modal" class="hidden fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 p-4 backdrop-blur-sm" style="animation: fadeIn 0.3s ease-out;">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl transform transition-all" style="animation: slideIn 0.3s ease-out; max-height: 90vh; overflow-y: auto;">
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); padding: 32px; border-radius: 16px 16px 0 0;">
            <div style="display: flex; justify-content: space-between; align-items: center; color: white;">
                <div style="display: flex; align-items: center; gap: 16px;">
                    <div style="width: 64px; height: 64px; background: rgba(255,255,255,0.2); border-radius: 16px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-user-plus" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h3 style="font-size: 28px; font-weight: 700; margin-bottom: 4px;">Daftar Member Baru</h3>
                        <p style="font-size: 14px; opacity: 0.9;">Tambahkan pelanggan baru ke sistem</p>
                    </div>
                </div>
                <button onclick="closeRegisterModal()" style="width: 48px; height: 48px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; border: none; cursor: pointer; transition: all 0.2s;">
                    <i class="fas fa-times" style="font-size: 24px; color: white;"></i>
                </button>
            </div>
        </div>

        <!-- Form Content -->
        <form id="register-member-form" style="padding: 32px;">
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
                <!-- Nama -->
                <div>
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px;">
                        <i class="fas fa-user" style="color: #F59E0B; margin-right: 6px;"></i>
                        Nama Lengkap <span style="color: #EF4444;">*</span>
                    </label>
                    <input type="text" name="name" required 
                           style="width: 100%; padding: 14px 16px; border: 2px solid #E5E7EB; border-radius: 12px; font-size: 14px; transition: all 0.2s;"
                           placeholder="Contoh: Budi Santoso"
                           onfocus="this.style.borderColor='#F59E0B'; this.style.boxShadow='0 0 0 3px rgba(245, 158, 11, 0.1)';"
                           onblur="this.style.borderColor='#E5E7EB'; this.style.boxShadow='none';" />
                </div>

                <!-- Email -->
                <div>
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px;">
                        <i class="fas fa-envelope" style="color: #F59E0B; margin-right: 6px;"></i>
                        Email <span style="color: #EF4444;">*</span>
                    </label>
                    <input type="email" name="email" required 
                           style="width: 100%; padding: 14px 16px; border: 2px solid #E5E7EB; border-radius: 12px; font-size: 14px; transition: all 0.2s;"
                           placeholder="email@contoh.com"
                           onfocus="this.style.borderColor='#F59E0B'; this.style.boxShadow='0 0 0 3px rgba(245, 158, 11, 0.1)';"
                           onblur="this.style.borderColor='#E5E7EB'; this.style.boxShadow='none';" />
                </div>

                <!-- Phone -->
                <div>
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px;">
                        <i class="fas fa-phone" style="color: #F59E0B; margin-right: 6px;"></i>
                        No. HP <span style="color: #EF4444;">*</span>
                    </label>
                    <input type="text" name="phone" required 
                           style="width: 100%; padding: 14px 16px; border: 2px solid #E5E7EB; border-radius: 12px; font-size: 14px; transition: all 0.2s;"
                           placeholder="08123456789"
                           onfocus="this.style.borderColor='#F59E0B'; this.style.boxShadow='0 0 0 3px rgba(245, 158, 11, 0.1)';"
                           onblur="this.style.borderColor='#E5E7EB'; this.style.boxShadow='none';" />
                </div>

                <!-- Password -->
                <div>
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px;">
                        <i class="fas fa-lock" style="color: #F59E0B; margin-right: 6px;"></i>
                        Password <span style="color: #6B7280; font-size: 12px;">(opsional)</span>
                    </label>
                    <input type="password" name="password" minlength="8" 
                           style="width: 100%; padding: 14px 16px; border: 2px solid #E5E7EB; border-radius: 12px; font-size: 14px; transition: all 0.2s;"
                           placeholder="member123"
                           onfocus="this.style.borderColor='#F59E0B'; this.style.boxShadow='0 0 0 3px rgba(245, 158, 11, 0.1)';"
                           onblur="this.style.borderColor='#E5E7EB'; this.style.boxShadow='none';" />
                    <p style="margin-top: 4px; font-size: 12px; color: #6B7280;">
                        <i class="fas fa-info-circle"></i> Kosongkan untuk password default
                    </p>
                </div>
            </div>

            <!-- Alamat (Full Width) -->
            <div style="margin-bottom: 24px;">
                <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px;">
                    <i class="fas fa-map-marker-alt" style="color: #F59E0B; margin-right: 6px;"></i>
                    Alamat Lengkap <span style="color: #EF4444;">*</span>
                </label>
                <textarea name="address" required rows="3" 
                          style="width: 100%; padding: 14px 16px; border: 2px solid #E5E7EB; border-radius: 12px; font-size: 14px; transition: all 0.2s; resize: none;"
                          placeholder="Contoh: Jl. Sudirman No. 123, Kelurahan ABC, Kecamatan XYZ, Jakarta Selatan"
                          onfocus="this.style.borderColor='#F59E0B'; this.style.boxShadow='0 0 0 3px rgba(245, 158, 11, 0.1)';"
                          onblur="this.style.borderColor='#E5E7EB'; this.style.boxShadow='none';"></textarea>
            </div>

            <!-- Buttons -->
            <div style="display: flex; gap: 12px;">
                <button type="submit" class="btn btn-primary" style="flex: 1; padding: 16px; background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);">
                    <i class="fas fa-save"></i>
                    <span>Simpan Member</span>
                </button>
                <button type="button" onclick="closeRegisterModal()" class="btn btn-secondary" style="padding: 16px;">
                    <i class="fas fa-times"></i>
                    <span>Batal</span>
                </button>
            </div>
        </form>

        <!-- Success Message -->
        <div id="success-message" class="hidden" style="margin: 0 32px 32px 32px;">
            <div style="background: linear-gradient(135deg, #D1FAE5 0%, #A7F3D0 100%); border: 2px solid #10B981; border-radius: 16px; padding: 24px;">
                <div style="display: flex; gap: 16px;">
                    <div style="width: 56px; height: 56px; background: #10B981; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fas fa-check" style="font-size: 28px; color: white;"></i>
                    </div>
                    <div style="flex: 1;">
                        <h4 style="font-size: 18px; font-weight: 700; color: #065F46; margin-bottom: 8px;">
                            Member Berhasil Didaftarkan!
                        </h4>
                        <div id="member-info" style="color: #047857; font-size: 14px;"></div>
                        <p style="margin-top: 12px; font-size: 12px; color: #10B981;">
                            <i class="fas fa-clock mr-1"></i> Halaman akan di-refresh otomatis...
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
</style>

@push('scripts')
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

function openRegisterModal() {
    document.getElementById('register-member-modal').classList.remove('hidden');
    document.getElementById('register-member-form').reset();
    document.getElementById('success-message').classList.add('hidden');
    document.getElementById('register-member-form').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeRegisterModal() {
    document.getElementById('register-member-modal').classList.add('hidden');
    document.body.style.overflow = '';
}

// Close on outside click
document.getElementById('register-member-modal')?.addEventListener('click', function(e) {
    if (e.target === this) closeRegisterModal();
});

// Close on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('register-member-modal').classList.contains('hidden')) {
        closeRegisterModal();
    }
});

// Form submission
document.getElementById('register-member-form')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i><span>Menyimpan...</span>';
    submitBtn.style.opacity = '0.7';
    
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
            const memberInfo = `
                <div style="display: flex; align-items: center; gap: 12px; padding: 16px; background: white; border-radius: 12px; margin-top: 12px;">
                    <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #10B981 0%, #059669 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 20px;">
                        ${result.member.name.charAt(0).toUpperCase()}
                    </div>
                    <div>
                        <div style="font-weight: 700; color: #065F46; font-size: 16px;">${result.member.name}</div>
                        <div style="font-size: 13px; color: #047857;">${result.member.email} • ${result.member.phone}</div>
                    </div>
                </div>
            `;
            document.getElementById('member-info').innerHTML = memberInfo;
            document.getElementById('success-message').classList.remove('hidden');
            this.style.display = 'none';
            
            setTimeout(() => {
                closeRegisterModal();
                setTimeout(() => {
                    location.reload();
                }, 300);
            }, 3000);
        } else {
            let errorMsg = 'Gagal mendaftarkan member:\n\n';
            if (result.errors) {
                for (let field in result.errors) {
                    errorMsg += `• ${result.errors[field].join(', ')}\n`;
                }
            } else {
                errorMsg = result.message || 'Terjadi kesalahan. Silakan coba lagi.';
            }
            alert(errorMsg);
            
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
            submitBtn.style.opacity = '1';
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan koneksi. Silakan coba lagi.');
        
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
        submitBtn.style.opacity = '1';
    }
});

// Update waktu real-time setiap detik
function updateKasirTime() {
    const now = new Date();
    
    // Format jam (HH:MM)
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const timeString = `${hours}:${minutes}`;
    
    // Format tanggal (Hari, DD Bulan YYYY)
    const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    
    const dayName = days[now.getDay()];
    const day = now.getDate();
    const monthName = months[now.getMonth()];
    const year = now.getFullYear();
    
    const dateString = `${dayName}, ${day} ${monthName} ${year}`;
    
    // Update DOM
    const timeElement = document.getElementById('kasirCurrentTime');
    const dateElement = document.getElementById('kasirCurrentDate');
    
    if (timeElement) timeElement.textContent = timeString;
    if (dateElement) dateElement.textContent = dateString;
}

// Update setiap detik
setInterval(updateKasirTime, 1000);

// Update langsung saat page load
updateKasirTime();
</script>
@endpush
@endsection
