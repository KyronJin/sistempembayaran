@extends('layouts.app')
@section('title','Manajemen Member')
@section('content')
<div class="container mx-auto px-4 py-6">
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-bold text-gray-800"><i class="fas fa-users mr-2"></i>Daftar Member</h1>
  </div>

  @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded mb-4">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded mb-4">{{ session('error') }}</div>
  @endif

  <div class="bg-white rounded-lg shadow">
    <div class="overflow-x-auto">
      <table class="min-w-full">
        <thead class="bg-gray-50">
          <tr class="text-left text-sm text-gray-600">
            <th class="px-4 py-3">Nama</th>
            <th class="px-4 py-3">Email</th>
            <th class="px-4 py-3">Kode Member</th>
            <th class="px-4 py-3">Status</th>
            <th class="px-4 py-3">Poin</th>
            <th class="px-4 py-3 text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y">
          @forelse($members as $m)
          <tr class="text-sm">
            <td class="px-4 py-3 font-medium text-gray-800">{{ $m->user->name }}</td>
            <td class="px-4 py-3">{{ $m->user->email }}</td>
            <td class="px-4 py-3">{{ $m->member_code }}</td>
            <td class="px-4 py-3">
              <span class="px-2 py-1 rounded text-xs {{ $m->status === 'active' ? 'bg-green-100 text-green-700' : ($m->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                {{ ucfirst($m->status) }}
              </span>
            </td>
            <td class="px-4 py-3">{{ $m->points }}</td>
            <td class="px-4 py-3 text-right space-x-2">
              @if($m->status !== 'active')
              <form action="{{ route('admin.members.approve', $m->id) }}" method="POST" class="inline">
                @csrf
                <button class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white rounded">Approve</button>
              </form>
              @endif
              @if($m->status !== 'suspended')
              <form action="{{ route('admin.members.suspend', $m->id) }}" method="POST" class="inline" onsubmit="return confirm('Suspend member ini?');">
                @csrf
                <button class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded">Suspend</button>
              </form>
              @endif
            </td>
          </tr>
          @empty
          <tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">Belum ada member</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="p-3">
      {{ $members->links() }}
    </div>
  </div>
</div>
@endsection