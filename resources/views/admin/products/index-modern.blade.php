@extends('layouts.modern')

@section('title', 'Kelola Produk')

@section('content')
<div class="content-header">
    <h1>Kelola Produk</h1>
    <p>Manajemen data produk dan kategori</p>
</div>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <i class="fas fa-box"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $products->total() }}</h3>
            <p>Total Produk</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $products->where('is_active', true)->count() }}</h3>
            <p>Produk Aktif</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
            <i class="fas fa-tags"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $categories->count() }}</h3>
            <p>Kategori</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $products->where('stock', '<', 10)->count() }}</h3>
            <p>Stok Menipis</p>
        </div>
    </div>
</div>

<!-- Actions & Filters -->
<div class="modern-card" style="margin-bottom: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div style="display: flex; gap: 1rem; flex: 1;">
            <input type="text" id="searchProduct" placeholder="🔍 Cari produk..." 
                   style="padding: 0.75rem 1rem; border: 2px solid #e5e7eb; border-radius: 12px; flex: 1; max-width: 400px; transition: all 0.3s;">
            <select id="filterCategory" style="padding: 0.75rem 1rem; border: 2px solid #e5e7eb; border-radius: 12px; min-width: 150px;">
                <option value="">Semua Kategori</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            <select id="filterStatus" style="padding: 0.75rem 1rem; border: 2px solid #e5e7eb; border-radius: 12px; min-width: 120px;">
                <option value="">Semua Status</option>
                <option value="active">Aktif</option>
                <option value="inactive">Nonaktif</option>
            </select>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i> Tambah Produk
        </a>
    </div>
</div>

<!-- Products Table -->
<div class="modern-card">
    <div class="table-responsive">
        <table class="modern-table">
            <thead>
                <tr>
                    <th style="width: 80px;">Gambar</th>
                    <th>Kode</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th style="text-align: right;">Harga</th>
                    <th style="text-align: center;">Stok</th>
                    <th style="text-align: center;">Status</th>
                    <th style="text-align: center;">QR Code</th>
                    <th style="text-align: center; width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="productTableBody">
                @forelse($products as $product)
                <tr data-category="{{ $product->category_id }}" data-status="{{ $product->is_active ? 'active' : 'inactive' }}" data-name="{{ strtolower($product->name) }}" data-code="{{ strtolower($product->product_code) }}">
                    <td>
                        @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" 
                             style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px; border: 2px solid #e5e7eb;">
                        @else
                        <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white;">
                            <i class="fas fa-box"></i>
                        </div>
                        @endif
                    </td>
                    <td><strong>{{ $product->product_code }}</strong></td>
                    <td>
                        <div style="font-weight: 600; color: #1f2937;">{{ $product->name }}</div>
                        @if($product->description)
                        <div style="font-size: 0.875rem; color: #6b7280; margin-top: 4px;">{{ Str::limit($product->description, 50) }}</div>
                        @endif
                    </td>
                    <td>
                        <span class="badge" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            {{ $product->category->name }}
                        </span>
                    </td>
                    <td style="text-align: right; font-weight: 700; color: #059669;">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td style="text-align: center;">
                        @if($product->stock < 10)
                        <span class="badge" style="background: #ef4444;">{{ $product->stock }}</span>
                        @elseif($product->stock < 30)
                        <span class="badge" style="background: #f59e0b;">{{ $product->stock }}</span>
                        @else
                        <span class="badge" style="background: #10b981;">{{ $product->stock }}</span>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        @if($product->is_active)
                        <span class="badge" style="background: #10b981;"><i class="fas fa-check"></i> Aktif</span>
                        @else
                        <span class="badge" style="background: #6b7280;"><i class="fas fa-times"></i> Nonaktif</span>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        <a href="{{ route('admin.products.qr-download', $product->id) }}" class="btn-icon" title="Download QR">
                            <i class="fas fa-qrcode"></i>
                        </a>
                    </td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 0.5rem; justify-content: center;">
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn-icon" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon" style="background: #fee2e2; color: #dc2626;" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 3rem;">
                        <div style="opacity: 0.5;">
                            <i class="fas fa-box" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                            <p>Belum ada produk</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
    <div style="padding: 1.5rem; border-top: 1px solid #e5e7eb;">
        {{ $products->links() }}
    </div>
    @endif
</div>

<script>
// Real-time search and filter
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchProduct');
    const categoryFilter = document.getElementById('filterCategory');
    const statusFilter = document.getElementById('filterStatus');
    const tableBody = document.getElementById('productTableBody');
    const rows = tableBody.querySelectorAll('tr[data-category]');

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedCategory = categoryFilter.value;
        const selectedStatus = statusFilter.value;

        rows.forEach(row => {
            const name = row.dataset.name;
            const code = row.dataset.code;
            const category = row.dataset.category;
            const status = row.dataset.status;

            const matchesSearch = name.includes(searchTerm) || code.includes(searchTerm);
            const matchesCategory = !selectedCategory || category === selectedCategory;
            const matchesStatus = !selectedStatus || status === selectedStatus;

            if (matchesSearch && matchesCategory && matchesStatus) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterTable);
    categoryFilter.addEventListener('change', filterTable);
    statusFilter.addEventListener('change', filterTable);

    // Focus effect
    searchInput.addEventListener('focus', function() {
        this.style.borderColor = '#4f46e5';
        this.style.boxShadow = '0 0 0 3px rgba(79, 70, 229, 0.1)';
    });
    searchInput.addEventListener('blur', function() {
        this.style.borderColor = '#e5e7eb';
        this.style.boxShadow = 'none';
    });
});
</script>
@endsection
