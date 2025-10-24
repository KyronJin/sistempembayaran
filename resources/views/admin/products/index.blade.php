@extends('layouts.app')

@section('title', 'Produk')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Daftar Produk</h1>
        <a href="{{ route('admin.products.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded">+ Tambah Produk</a>
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded shadow">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left px-4 py-2">Gambar</th>
                        <th class="text-left px-4 py-2">Nama</th>
                        <th class="text-left px-4 py-2">SKU</th>
                        <th class="text-left px-4 py-2">Kategori</th>
                        <th class="text-left px-4 py-2">Harga</th>
                        <th class="text-left px-4 py-2">Harga Member</th>
                        <th class="text-left px-4 py-2">Stok</th>
                        <th class="text-left px-4 py-2">Satuan</th>
                        <th class="text-left px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr class="border-t">
                            <td class="px-4 py-2">
                                @if($product->image)
                                    <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" class="h-10 w-10 object-cover rounded border" />
                                @else
                                    <div class="h-10 w-10 rounded border flex items-center justify-center text-xs text-gray-400">-</div>
                                @endif
                            </td>
                            <td class="px-4 py-2">{{ $product->name }}</td>
                            <td class="px-4 py-2">{{ $product->sku }}</td>
                            <td class="px-4 py-2">{{ $product->category->name ?? '-' }}</td>
                            <td class="px-4 py-2">Rp {{ number_format($product->price,0,',','.') }}</td>
                            <td class="px-4 py-2">{{ $product->member_price ? 'Rp '.number_format($product->member_price,0,',','.') : '-' }}</td>
                            <td class="px-4 py-2">{{ $product->stock }}</td>
                            <td class="px-4 py-2">{{ $product->unit ?? '-' }}</td>
                            <td class="px-4 py-2 space-x-2">
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="text-blue-600">Edit</a>
                                <a href="{{ route('admin.products.qr-download', $product->id) }}" class="text-green-600">QR</a>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-6 text-center text-gray-500">Belum ada produk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $products->links() }}</div>
    </div>
</div>
@endsection
