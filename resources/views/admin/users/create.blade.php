@extends('layouts.app')
@section('title','Tambah User')
@section('content')
<div class="container mx-auto px-4 py-6">
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-bold text-gray-800"><i class="fas fa-user-plus mr-2"></i>Tambah User Baru</h1>
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

  <form action="{{ route('admin.users.store') }}" method="POST" class="bg-white rounded-lg shadow p-6">
    @csrf
    <div class="grid md:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span class="text-red-600">*</span></label>
        <input type="text" name="name" value="{{ old('name') }}" required class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-600">*</span></label>
        <input type="email" name="email" value="{{ old('email') }}" required class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Password <span class="text-red-600">*</span></label>
        <input type="password" name="password" required class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" minlength="8">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password <span class="text-red-600">*</span></label>
        <input type="password" name="password_confirmation" required class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" minlength="8">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Role <span class="text-red-600">*</span></label>
        <select name="role" required class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
          <option value="">-- Pilih Role --</option>
          <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
          <option value="kasir" {{ old('role') === 'kasir' ? 'selected' : '' }}>Kasir</option>
          <option value="member" {{ old('role') === 'member' ? 'selected' : '' }}>Member</option>
        </select>
        <p class="text-xs text-gray-500 mt-1">* Member profile akan otomatis dibuat untuk role Member</p>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Telepon <span class="text-red-600">*</span></label>
        <input type="text" name="phone" value="{{ old('phone') }}" required class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
      </div>
      <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-2">Alamat <span class="text-red-600">*</span></label>
        <textarea name="address" rows="3" required class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">{{ old('address') }}</textarea>
      </div>
    </div>

    <div class="mt-6 flex gap-2">
      <button type="submit" class="btn-primary">
        <i class="fas fa-save mr-2"></i>Simpan User
      </button>
      <a href="{{ route('admin.users.index') }}" class="btn-secondary">Batal</a>
    </div>
  </form>
</div>
@endsection
