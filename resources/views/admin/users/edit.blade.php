@extends('layouts.app')
@section('title','Edit User')
@section('content')
<div class="container mx-auto px-4 py-6">
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-bold text-gray-800"><i class="fas fa-user-edit mr-2"></i>Edit User</h1>
    <a href="{{ route('admin.users.index') }}" class="btn-secondary"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>
  </div>

  @if ($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded">
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
    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded flex items-center gap-2">
      <i class="fas fa-check-circle"></i>
      <span>{{ session('success') }}</span>
    </div>
  @endif

  <div class="grid md:grid-cols-3 gap-4">
    <!-- Main Form -->
    <div class="md:col-span-2">
      <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="bg-white rounded-lg shadow p-6">
        @csrf
        @method('PUT')
        <div class="grid md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span class="text-red-600">*</span></label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-600">*</span></label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Role <span class="text-red-600">*</span></label>
            <select name="role" required class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
              <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
              <option value="kasir" {{ old('role', $user->role) === 'kasir' ? 'selected' : '' }}>Kasir</option>
              <option value="member" {{ old('role', $user->role) === 'member' ? 'selected' : '' }}>Member</option>
            </select>
            <p class="text-xs text-gray-500 mt-1">* Member profile akan otomatis dibuat jika diubah ke Member</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Telepon <span class="text-red-600">*</span></label>
            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" required class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <div class="flex items-center gap-4">
              <label class="flex items-center gap-2">
                <input type="radio" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }} class="text-blue-600">
                <span>Aktif</span>
              </label>
              <label class="flex items-center gap-2">
                <input type="radio" name="is_active" value="0" {{ !old('is_active', $user->is_active) ? 'checked' : '' }} class="text-blue-600">
                <span>Nonaktif</span>
              </label>
            </div>
          </div>
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">Alamat <span class="text-red-600">*</span></label>
            <textarea name="address" rows="3" required class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">{{ old('address', $user->address) }}</textarea>
          </div>
        </div>

        <div class="mt-6 flex gap-2">
          <button type="submit" class="btn-primary">
            <i class="fas fa-save mr-2"></i>Update User
          </button>
          <a href="{{ route('admin.users.index') }}" class="btn-secondary">Batal</a>
        </div>
      </form>
    </div>

    <!-- Reset Password Panel -->
    <div>
      <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">
          <i class="fas fa-key mr-2"></i>Reset Password
        </h2>
        <form action="{{ route('admin.users.reset-password', $user->id) }}" method="POST">
          @csrf
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Password Baru <span class="text-red-600">*</span></label>
            <input type="password" name="new_password" required class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" minlength="8">
          </div>
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password <span class="text-red-600">*</span></label>
            <input type="password" name="new_password_confirmation" required class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" minlength="8">
          </div>
          <button type="submit" class="w-full btn-primary" onclick="return confirm('Reset password untuk user ini?')">
            <i class="fas fa-sync mr-2"></i>Reset Password
          </button>
        </form>

        @if($user->member)
        <div class="mt-6 pt-6 border-t">
          <h3 class="text-sm font-bold text-gray-700 mb-2">
            <i class="fas fa-id-card mr-2"></i>Info Member
          </h3>
          <div class="text-sm space-y-1">
            <div><span class="text-gray-600">Kode:</span> <span class="font-mono">{{ $user->member->member_code }}</span></div>
            <div><span class="text-gray-600">Poin:</span> {{ $user->member->points }}</div>
            <div><span class="text-gray-600">Status:</span> 
              <span class="px-2 py-1 rounded text-xs font-semibold
                {{ $user->member->status === 'active' ? 'bg-green-100 text-green-800' : '' }}
                {{ $user->member->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                {{ $user->member->status === 'suspended' ? 'bg-red-100 text-red-800' : '' }}">
                {{ ucfirst($user->member->status) }}
              </span>
            </div>
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
