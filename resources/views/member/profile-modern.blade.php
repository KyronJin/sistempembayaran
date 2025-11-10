@extends('layouts.modern')

@section('title', 'Profil Saya')

@section('content')
<div class="fade-in">
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
</div>
@endsection
