@extends('layouts.modern')

@section('title', 'Profil Saya')

@section('content')
<div class="content-header">
    <h1>Profil Saya</h1>
    <p>Kelola informasi akun dan kartu member Anda</p>
</div>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem;">
    <!-- Member Card -->
    <div>
        <div class="modern-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2rem;">
            <div style="text-align: center;">
                <div style="width: 100px; height: 100px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 3rem;">
                    <i class="fas fa-user"></i>
                </div>
                <h2 style="margin: 0 0 0.5rem 0; font-size: 1.5rem;">{{ $member->name }}</h2>
                <p style="opacity: 0.9; margin-bottom: 1.5rem;">{{ auth()->user()->email }}</p>
                
                <div style="background: rgba(255,255,255,0.2); padding: 1rem; border-radius: 12px; margin-bottom: 1rem;">
                    <div style="font-size: 0.875rem; opacity: 0.9; margin-bottom: 0.25rem;">Kode Member</div>
                    <div style="font-size: 1.5rem; font-weight: 700;">{{ $member->member_code }}</div>
                </div>
                
                <div style="background: rgba(255,255,255,0.2); padding: 1rem; border-radius: 12px;">
                    <div style="font-size: 0.875rem; opacity: 0.9; margin-bottom: 0.25rem;">Total Poin</div>
                    <div style="font-size: 2rem; font-weight: 700;">{{ number_format($member->points, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        
        <div class="modern-card" style="margin-top: 1.5rem; text-align: center;">
            <a href="{{ route('member.qr-code') }}" class="btn-primary" style="display: block;">
                <i class="fas fa-qrcode"></i> Lihat QR Member
            </a>
        </div>
    </div>

    <!-- Profile Forms -->
    <div>
        <!-- Update Profile -->
        <div class="modern-card" style="margin-bottom: 2rem;">
            <h3 style="margin: 0 0 1.5rem 0; font-size: 1.25rem; border-bottom: 2px solid #e5e7eb; padding-bottom: 1rem;">
                <i class="fas fa-user-edit"></i> Informasi Profil
            </h3>

            @if(session('success'))
            <div style="padding: 1rem; background: #d1fae5; color: #065f46; border-radius: 8px; margin-bottom: 1.5rem;">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
            @endif

            <form action="{{ route('member.profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                    <div>
                        <label class="form-label">Nama Lengkap *</label>
                        <input type="text" name="name" class="form-input" value="{{ old('name', $member->name) }}" required>
                        @error('name')
                        <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-input" value="{{ old('email', auth()->user()->email) }}" required>
                        @error('email')
                        <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label">No. Telepon *</label>
                        <input type="tel" name="phone" class="form-input" value="{{ old('phone', $member->phone) }}" required>
                        @error('phone')
                        <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label">Alamat</label>
                        <textarea name="address" class="form-input" rows="4" style="resize: vertical;">{{ old('address', $member->address) }}</textarea>
                    </div>

                    <div style="text-align: right;">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Change Password -->
        <div class="modern-card">
            <h3 style="margin: 0 0 1.5rem 0; font-size: 1.25rem; border-bottom: 2px solid #e5e7eb; padding-bottom: 1rem;">
                <i class="fas fa-lock"></i> Ubah Password
            </h3>

            <form action="{{ route('member.password.change') }}" method="POST">
                @csrf
                @method('PUT')

                <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                    <div>
                        <label class="form-label">Password Lama *</label>
                        <input type="password" name="current_password" class="form-input" required>
                        @error('current_password')
                        <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label">Password Baru *</label>
                        <input type="password" name="new_password" class="form-input" required>
                        @error('new_password')
                        <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label">Konfirmasi Password Baru *</label>
                        <input type="password" name="new_password_confirmation" class="form-input" required>
                    </div>

                    <div style="padding: 1rem; background: #fef3c7; border-radius: 8px; border-left: 4px solid #f59e0b;">
                        <strong style="color: #92400e; display: block; margin-bottom: 0.5rem;">
                            <i class="fas fa-info-circle"></i> Syarat Password:
                        </strong>
                        <ul style="margin: 0; padding-left: 1.5rem; color: #92400e; font-size: 0.875rem;">
                            <li>Minimal 8 karakter</li>
                            <li>Kombinasi huruf dan angka</li>
                        </ul>
                    </div>

                    <div style="text-align: right;">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-key"></i> Ubah Password
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
