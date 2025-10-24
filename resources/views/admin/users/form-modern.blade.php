@extends('layouts.modern')

@section('title', isset($user) ? 'Edit User' : 'Tambah User')

@section('content')
<div class="content-header">
    <h1>{{ isset($user) ? 'Edit User' : 'Tambah User' }}</h1>
    <p>{{ isset($user) ? 'Perbarui informasi user' : 'Tambahkan user admin atau kasir baru' }}</p>
</div>

<div class="modern-card" style="max-width: 800px; margin: 0 auto;">
    <form action="{{ isset($user) ? route('admin.users.update', $user->id) : route('admin.users.store') }}" method="POST">
        @csrf
        @if(isset($user))
            @method('PUT')
        @endif

        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <div>
                <label class="form-label">Nama Lengkap *</label>
                <input type="text" name="name" class="form-input" value="{{ old('name', $user->name ?? '') }}" required>
                @error('name')
                <span style="color: #dc2626; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="form-label">Email *</label>
                <input type="email" name="email" class="form-input" value="{{ old('email', $user->email ?? '') }}" required>
                @error('email')
                <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="form-label">Role *</label>
                <select name="role" class="form-input" required>
                    <option value="">Pilih Role</option>
                    <option value="admin" {{ old('role', $user->role ?? '') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="kasir" {{ old('role', $user->role ?? '') == 'kasir' ? 'selected' : '' }}>Kasir</option>
                </select>
                @error('role')
                <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span>
                @enderror
            </div>

            @if(!isset($user))
            <div>
                <label class="form-label">Password *</label>
                <input type="password" name="password" class="form-input" required>
                @error('password')
                <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="form-label">Konfirmasi Password *</label>
                <input type="password" name="password_confirmation" class="form-input" required>
            </div>
            @endif

            <div style="padding: 1rem; background: #fef3c7; border-radius: 8px; border-left: 4px solid #f59e0b;">
                <strong style="color: #92400e; display: block; margin-bottom: 0.5rem;">
                    <i class="fas fa-info-circle"></i> Informasi:
                </strong>
                <ul style="margin: 0; padding-left: 1.5rem; color: #92400e; font-size: 0.875rem;">
                    <li><strong>Admin</strong> dapat mengakses semua fitur manajemen</li>
                    <li><strong>Kasir</strong> dapat mengakses POS dan transaksi</li>
                    @if(isset($user))
                    <li>Password tidak akan berubah jika tidak diisi</li>
                    @endif
                </ul>
            </div>

            <!-- Action Buttons -->
            <div style="display: flex; gap: 1rem; justify-content: flex-end; padding-top: 1.5rem; border-top: 2px solid #e5e7eb;">
                <a href="{{ route('admin.users.index') }}" class="btn-secondary">
                    <i class="fas fa-times"></i> Batal
                </a>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i> {{ isset($user) ? 'Update User' : 'Simpan User' }}
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
