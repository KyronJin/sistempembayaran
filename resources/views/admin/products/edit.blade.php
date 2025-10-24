@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Edit Produk</h1>

    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded shadow p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-sm font-medium">Nama</label>
            <input type="text" name="name" value="{{ old('name', $product->name) }}" class="mt-1 w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block text-sm font-medium">SKU</label>
            <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="mt-1 w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block text-sm font-medium">Barcode</label>
            <input type="text" name="barcode" value="{{ old('barcode', $product->barcode) }}" class="mt-1 w-full border rounded px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium">Kategori</label>
            <select name="category_id" class="mt-1 w-full border rounded px-3 py-2" required>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id)==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm font-medium">Deskripsi</label>
            <textarea name="description" rows="3" class="mt-1 w-full border rounded px-3 py-2">{{ old('description', $product->description) }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium">Harga</label>
            <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" class="mt-1 w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block text-sm font-medium">Harga Member (opsional)</label>
            <input type="number" step="0.01" name="member_price" value="{{ old('member_price', $product->member_price) }}" class="mt-1 w-full border rounded px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium">Stok</label>
            <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" class="mt-1 w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block text-sm font-medium">Stok</label>
            <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" class="mt-1 w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block text-sm font-medium">Satuan</label>
            <input type="text" name="unit" value="{{ old('unit', $product->unit ?? 'pcs') }}" class="mt-1 w-full border rounded px-3 py-2" required placeholder="mis. pcs, pack, box">
        </div>
        <div>
            <label class="block text-sm font-medium">Stok Minimal</label>
            <input type="number" name="min_stock" value="{{ old('min_stock', $product->min_stock) }}" class="mt-1 w-full border rounded px-3 py-2" required>
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm font-medium">Gambar (opsional)</label>
            <div class="flex items-start gap-4">
                <div>
                    <input id="imageInput" type="file" name="image" accept="image/*" class="mt-1 w-full border rounded px-3 py-2">
                    <p class="text-xs text-gray-500 mt-1">Format: JPG/PNG, maksimum 2MB</p>
                </div>
                <div class="shrink-0">
                    <img id="imagePreview" src="{{ $product->image ? asset('storage/'.$product->image) : '' }}" alt="Preview" class="h-20 w-20 object-cover rounded border {{ $product->image ? '' : 'hidden' }}">
                </div>
            </div>
        </div>
        <div class="md:col-span-2 flex items-center space-x-2">
            <a href="{{ route('admin.products.index') }}" class="px-4 py-2 border rounded">Batal</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
        </div>
    </form>
    <script>
        const input = document.getElementById('imageInput');
        const preview = document.getElementById('imagePreview');
        if (input) {
            input.addEventListener('change', function () {
                const file = this.files && this.files[0];
                if (!file) { return; }
                const reader = new FileReader();
                reader.onload = e => { preview.src = e.target.result; preview.classList.remove('hidden'); };
                reader.readAsDataURL(file);
            });
        }
    </script>
</div>
@endsection
