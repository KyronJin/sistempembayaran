@extends('layouts.modern')

@section('title', 'Katalog Produk')

@section('content')
<div class="content-header">
    <h1>Katalog Produk</h1>
    <p>Lihat produk yang tersedia dengan harga khusus member</p>
</div>

<!-- Search & Filter -->
<div class="modern-card" style="margin-bottom: 2rem;">
    <form method="GET" style="display: flex; gap: 1rem; flex-wrap: wrap;">
        <input type="text" name="search" class="form-input" placeholder="🔍 Cari produk..." value="{{ request('search') }}" style="flex: 1; min-width: 250px;">
        <select name="category" class="form-input" style="min-width: 200px;">
            <option value="">Semua Kategori</option>
            @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
            @endforeach
        </select>
        <button type="submit" class="btn-primary">
            <i class="fas fa-search"></i> Cari
        </button>
        @if(request()->hasAny(['search', 'category']))
        <a href="{{ route('member.products') }}" class="btn-secondary">
            <i class="fas fa-times"></i> Reset
        </a>
        @endif
    </form>
</div>

<!-- Products Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1.5rem;">
    @forelse($products as $product)
    <div class="modern-card" style="padding: 0; overflow: hidden; transition: all 0.3s; cursor: pointer;" onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 20px 40px rgba(0,0,0,0.15)'" onmouseout="this.style.transform=''; this.style.boxShadow=''">
        @if($product->image)
        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" 
             style="width: 100%; height: 200px; object-fit: cover;">
        @else
        <div style="width: 100%; height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem;">
            <i class="fas fa-box"></i>
        </div>
        @endif
        
        <div style="padding: 1.25rem;">
            <div style="margin-bottom: 0.5rem;">
                <span class="badge" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); font-size: 0.75rem;">
                    {{ $product->category->name }}
                </span>
            </div>
            
            <h3 style="margin: 0 0 0.75rem 0; font-size: 1.125rem; color: #1f2937;">{{ $product->name }}</h3>
            
            @if($product->description)
            <p style="color: #6b7280; font-size: 0.9375rem; margin-bottom: 1rem; line-height: 1.5;">
                {{ Str::limit($product->description, 80) }}
            </p>
            @endif
            
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <div>
                    @if($product->member_price && $product->member_price < $product->price)
                    <div style="text-decoration: line-through; color: #9ca3af; font-size: 0.875rem;">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </div>
                    <div style="color: #059669; font-size: 1.5rem; font-weight: 700;">
                        Rp {{ number_format($product->member_price, 0, ',', '.') }}
                    </div>
                    <div style="font-size: 0.75rem; color: #10b981;">
                        <i class="fas fa-tag"></i> Harga Member
                    </div>
                    @else
                    <div style="color: #059669; font-size: 1.5rem; font-weight: 700;">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </div>
                    @endif
                </div>
                <div style="text-align: right;">
                    @if($product->stock > 0)
                    <span class="badge" style="background: #10b981;">
                        <i class="fas fa-check"></i> Tersedia
                    </span>
                    @else
                    <span class="badge" style="background: #ef4444;">
                        <i class="fas fa-times"></i> Habis
                    </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @empty
    <div style="grid-column: 1 / -1; text-align: center; padding: 4rem;">
        <i class="fas fa-box-open" style="font-size: 4rem; color: #d1d5db; margin-bottom: 1rem;"></i>
        <p style="color: #6b7280; font-size: 1.125rem;">Tidak ada produk ditemukan</p>
    </div>
    @endforelse
</div>

@if($products->hasPages())
<div style="margin-top: 2rem;">
    {{ $products->links() }}
</div>
@endif
@endsection
