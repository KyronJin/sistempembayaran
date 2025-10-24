@extends('layouts.app')
@section('title','Manajemen User')
@section('content')
<div class="container mx-auto px-4 py-6">
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-bold text-gray-800"><i class="fas fa-users mr-2"></i>Manajemen User</h1>
    <a href="{{ route('admin.users.create') }}" class="btn-primary"><i class="fas fa-plus mr-2"></i>Tambah User</a>
  </div>

  @if (session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded flex items-center gap-2">
      <i class="fas fa-check-circle"></i>
      <span>{{ session('success') }}</span>
    </div>
  @endif

  @if (session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded flex items-center gap-2">
      <i class="fas fa-exclamation-circle"></i>
      <span>{{ session('error') }}</span>
    </div>
  @endif

  <div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="min-w-full">
      <thead class="bg-gray-50">
        <tr class="text-left text-sm text-gray-600">
          <th class="px-4 py-3">Nama</th>
          <th class="px-4 py-3">Email</th>
          <th class="px-4 py-3">Role</th>
          <th class="px-4 py-3">Telepon</th>
          <th class="px-4 py-3">Status</th>
          <th class="px-4 py-3">Member Code</th>
          <th class="px-4 py-3">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y">
        @forelse($users as $user)
        <tr class="text-sm hover:bg-gray-50">
          <td class="px-4 py-3 font-medium">{{ $user->name }}</td>
          <td class="px-4 py-3">{{ $user->email }}</td>
          <td class="px-4 py-3">
            <span class="px-2 py-1 rounded text-xs font-semibold
              {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : '' }}
              {{ $user->role === 'kasir' ? 'bg-blue-100 text-blue-800' : '' }}
              {{ $user->role === 'member' ? 'bg-green-100 text-green-800' : '' }}">
              {{ ucfirst($user->role) }}
            </span>
          </td>
          <td class="px-4 py-3">{{ $user->phone ?? '-' }}</td>
          <td class="px-4 py-3">
            @if($user->is_active)
              <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">Aktif</span>
            @else
              <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs font-semibold">Nonaktif</span>
            @endif
          </td>
          <td class="px-4 py-3">{{ $user->member ? $user->member->member_code : '-' }}</td>
          <td class="px-4 py-3 space-x-2">
            <a href="{{ route('admin.users.edit', $user->id) }}" class="text-blue-600 hover:text-blue-800">
              <i class="fas fa-edit"></i> Edit
            </a>
            @if($user->id !== auth()->id())
              <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus user ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-800">
                  <i class="fas fa-trash"></i> Hapus
                </button>
              </form>
            @endif
          </td>
        </tr>
        @empty
        <tr><td colspan="7" class="px-4 py-6 text-center text-gray-500">Belum ada user</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-4">
    {{ $users->links() }}
  </div>
</div>
@endsection
