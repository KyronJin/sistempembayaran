@extends('layouts.app')
@section('title','Pengaturan Sistem')
@section('content')
<div class="container mx-auto px-4 py-6">
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-bold text-gray-800"><i class="fas fa-cog mr-2"></i>Pengaturan</h1>
  </div>

  @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded mb-4">{{ session('success') }}</div>
  @endif

  <form action="{{ route('admin.settings.update') }}" method="POST" class="bg-white rounded-lg shadow p-4">
    @csrf
    @method('PUT')
    <div class="grid md:grid-cols-2 gap-4">
      @forelse($settings as $s)
      <div>
        <label class="block text-sm text-gray-600 mb-1">{{ ucwords(str_replace('_',' ', $s->key)) }}</label>
        <input type="text" name="{{ $s->key }}" value="{{ $s->value }}" class="w-full border rounded-lg px-3 py-2">
      </div>
      @empty
      <div class="md:col-span-2 text-gray-500">Belum ada pengaturan.</div>
      @endforelse
    </div>
    <div class="mt-4">
      <button class="btn-primary"><i class="fas fa-save mr-2"></i>Simpan</button>
    </div>
  </form>
</div>
@endsection