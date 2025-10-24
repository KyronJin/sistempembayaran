@extends('layouts.modern')

@section('title', 'Kelola Kategori')

@section('content')
<div class="content-header">
    <h1>Kelola Kategori</h1>
    <p>Manajemen kategori produk</p>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
    <!-- Categories List -->
    <div class="modern-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 style="margin: 0; font-size: 1.25rem;"><i class="fas fa-tags"></i> Daftar Kategori</h2>
            <button type="button" class="btn-primary" onclick="showAddForm()">
                <i class="fas fa-plus"></i> Tambah Kategori
            </button>
        </div>

        <div class="table-responsive">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Nama Kategori</th>
                        <th style="text-align: center;">Jumlah Produk</th>
                        <th style="text-align: center;">Status</th>
                        <th style="text-align: center; width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr>
                        <td>
                            <div style="font-weight: 600; color: #1f2937;">{{ $category->name }}</div>
                            @if($category->description)
                            <div style="font-size: 0.875rem; color: #6b7280; margin-top: 4px;">{{ $category->description }}</div>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <span class="badge" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                {{ $category->products_count }} produk
                            </span>
                        </td>
                        <td style="text-align: center;">
                            @if($category->is_active)
                            <span class="badge" style="background: #10b981;"><i class="fas fa-check"></i> Aktif</span>
                            @else
                            <span class="badge" style="background: #6b7280;"><i class="fas fa-times"></i> Nonaktif</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <button onclick="editCategory({{ $category->id }}, '{{ $category->name }}', '{{ $category->description }}', {{ $category->is_active ? 'true' : 'false' }})" 
                                    class="btn-icon" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 3rem;">
                            <div style="opacity: 0.5;">
                                <i class="fas fa-tags" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                                <p>Belum ada kategori</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
        <div style="padding: 1.5rem; border-top: 1px solid #e5e7eb;">
            {{ $categories->links() }}
        </div>
        @endif
    </div>

    <!-- Add/Edit Form -->
    <div class="modern-card" id="categoryForm" style="display: none;">
        <h3 style="margin: 0 0 1.5rem 0; font-size: 1.125rem;" id="formTitle">
            <i class="fas fa-plus"></i> Tambah Kategori
        </h3>

        <form id="categoryFormElement" method="POST" action="{{ route('admin.categories.index') }}">
            @csrf
            <input type="hidden" name="_method" value="POST" id="formMethod">
            <input type="hidden" name="category_id" id="categoryId">

            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                <div>
                    <label class="form-label">Nama Kategori *</label>
                    <input type="text" name="name" id="categoryName" class="form-input" required>
                </div>

                <div>
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" id="categoryDescription" class="form-input" rows="3" style="resize: vertical;"></textarea>
                </div>

                <div>
                    <label style="display: flex; align-items: center; cursor: pointer; gap: 0.5rem;">
                        <input type="checkbox" name="is_active" id="categoryStatus" value="1" checked style="width: 18px; height: 18px;">
                        <span class="form-label" style="margin: 0;">Kategori Aktif</span>
                    </label>
                </div>

                <div style="display: flex; gap: 1rem;">
                    <button type="button" onclick="hideForm()" class="btn-secondary" style="flex: 1;">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="submit" class="btn-primary" style="flex: 1;">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Info Card -->
    <div class="modern-card" id="infoCard">
        <div style="text-align: center; padding: 2rem;">
            <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: white; font-size: 2rem;">
                <i class="fas fa-tags"></i>
            </div>
            <h3 style="margin: 0 0 0.5rem 0; color: #1f2937;">Total Kategori</h3>
            <p style="font-size: 2.5rem; font-weight: 700; color: #4f46e5; margin: 0;">{{ $categories->total() }}</p>
        </div>

        <div style="border-top: 1px solid #e5e7eb; padding-top: 1.5rem; margin-top: 1.5rem;">
            <h4 style="margin: 0 0 1rem 0; font-size: 1rem; color: #1f2937;"><i class="fas fa-info-circle"></i> Tips</h4>
            <ul style="margin: 0; padding-left: 1.5rem; color: #6b7280; font-size: 0.9375rem; line-height: 1.8;">
                <li>Gunakan nama kategori yang jelas</li>
                <li>Kategori membantu pengorganisasian produk</li>
                <li>Nonaktifkan kategori yang tidak digunakan</li>
            </ul>
        </div>
    </div>
</div>

<script>
function showAddForm() {
    document.getElementById('categoryForm').style.display = 'block';
    document.getElementById('infoCard').style.display = 'none';
    document.getElementById('formTitle').innerHTML = '<i class="fas fa-plus"></i> Tambah Kategori';
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('categoryFormElement').reset();
    document.getElementById('categoryId').value = '';
    document.getElementById('categoryStatus').checked = true;
}

function editCategory(id, name, description, isActive) {
    document.getElementById('categoryForm').style.display = 'block';
    document.getElementById('infoCard').style.display = 'none';
    document.getElementById('formTitle').innerHTML = '<i class="fas fa-edit"></i> Edit Kategori';
    document.getElementById('formMethod').value = 'PUT';
    document.getElementById('categoryId').value = id;
    document.getElementById('categoryName').value = name;
    document.getElementById('categoryDescription').value = description;
    document.getElementById('categoryStatus').checked = isActive;
}

function hideForm() {
    document.getElementById('categoryForm').style.display = 'none';
    document.getElementById('infoCard').style.display = 'block';
}
</script>
@endsection
