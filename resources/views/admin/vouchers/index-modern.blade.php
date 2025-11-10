@extends('layouts.modern')

@section('title', 'Kelola Voucher')

@section('content')
<div class="content-header">
    <h1>Kelola Voucher</h1>
    <p>Manajemen voucher untuk member</p>
</div>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #FF6F00 0%, #FF8F00 100%);">
            <i class="fas fa-ticket-alt"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $vouchers->count() }}</h3>
            <p>Total Voucher</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $vouchers->where('is_active', true)->count() }}</h3>
            <p>Voucher Aktif</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $vouchers->sum('member_vouchers_count') }}</h3>
            <p>Total Redeemed</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $vouchers->where('valid_until', '<', now())->count() }}</h3>
            <p>Expired</p>
        </div>
    </div>
</div>

<!-- Actions & Filters -->
<div class="modern-card" style="margin-bottom: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div style="display: flex; gap: 1rem; flex: 1;">
            <input type="text" id="searchVoucher" placeholder="🔍 Cari voucher..." 
                   style="padding: 0.75rem 1rem; border: 2px solid #e5e7eb; border-radius: 12px; flex: 1; max-width: 400px; transition: all 0.3s;">
            <select id="filterStatus" style="padding: 0.75rem 1rem; border: 2px solid #e5e7eb; border-radius: 12px; min-width: 150px;">
                <option value="">Semua Status</option>
                <option value="active">Aktif</option>
                <option value="inactive">Nonaktif</option>
                <option value="expired">Expired</option>
            </select>
            <select id="filterType" style="padding: 0.75rem 1rem; border: 2px solid #e5e7eb; border-radius: 12px; min-width: 150px;">
                <option value="">Semua Tipe</option>
                <option value="percentage">Persentase</option>
                <option value="fixed">Fixed</option>
            </select>
        </div>
        <a href="{{ route('admin.vouchers.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i> Tambah Voucher
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success" style="margin-bottom: 1.5rem; padding: 1rem; background: #d1fae5; border-left: 4px solid #10b981; border-radius: 8px; color: #065f46;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-error" style="margin-bottom: 1.5rem; padding: 1rem; background: #fee2e2; border-left: 4px solid #ef4444; border-radius: 8px; color: #991b1b;">
    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
</div>
@endif

<!-- Vouchers Table -->
<div class="modern-card">
    <div class="table-responsive">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Voucher</th>
                    <th style="text-align: center;">Poin</th>
                    <th style="text-align: center;">Tipe</th>
                    <th style="text-align: center;">Nilai</th>
                    <th style="text-align: center;">Min. Transaksi</th>
                    <th style="text-align: center;">Periode</th>
                    <th style="text-align: center;">Stok</th>
                    <th style="text-align: center;">Redeemed</th>
                    <th style="text-align: center;">Status</th>
                    <th style="text-align: center; width: 180px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="voucherTableBody">
                @forelse($vouchers as $voucher)
                <tr data-status="{{ $voucher->is_active ? 'active' : 'inactive' }}" 
                    data-type="{{ $voucher->discount_type }}" 
                    data-expired="{{ $voucher->valid_until < now() ? 'yes' : 'no' }}"
                    data-name="{{ strtolower($voucher->name) }}" 
                    data-code="{{ strtolower($voucher->code) }}">
                    <td>
                        <div style="font-weight: 600; color: #FF6F00; font-family: 'Courier New', monospace;">
                            {{ $voucher->code }}
                        </div>
                    </td>
                    <td>
                        <div style="font-weight: 500;">{{ $voucher->name }}</div>
                        @if($voucher->description)
                        <div style="font-size: 0.85rem; color: #6b7280; margin-top: 0.25rem;">
                            {{ Str::limit($voucher->description, 50) }}
                        </div>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        <span style="background: #fff7ed; color: #FF6F00; padding: 0.25rem 0.75rem; border-radius: 8px; font-weight: 600; font-size: 0.9rem;">
                            {{ number_format($voucher->points_required) }} pts
                        </span>
                    </td>
                    <td style="text-align: center;">
                        @if($voucher->discount_type === 'percentage')
                        <span style="background: #dbeafe; color: #1e40af; padding: 0.25rem 0.75rem; border-radius: 8px; font-weight: 500; font-size: 0.85rem;">
                            <i class="fas fa-percent"></i> Persentase
                        </span>
                        @else
                        <span style="background: #dcfce7; color: #15803d; padding: 0.25rem 0.75rem; border-radius: 8px; font-weight: 500; font-size: 0.85rem;">
                            <i class="fas fa-money-bill-wave"></i> Fixed
                        </span>
                        @endif
                    </td>
                    <td style="text-align: center; font-weight: 600;">
                        @if($voucher->discount_type === 'percentage')
                            {{ $voucher->discount_value }}%
                            @if($voucher->max_discount)
                                <div style="font-size: 0.75rem; color: #6b7280; font-weight: 400;">Max: Rp {{ number_format($voucher->max_discount, 0, ',', '.') }}</div>
                            @endif
                        @else
                            Rp {{ number_format($voucher->discount_value, 0, ',', '.') }}
                        @endif
                    </td>
                    <td style="text-align: center;">
                        <span style="font-size: 0.9rem;">
                            Rp {{ number_format($voucher->min_transaction, 0, ',', '.') }}
                        </span>
                    </td>
                    <td style="text-align: center; font-size: 0.85rem;">
                        <div>{{ $voucher->valid_from->format('d M Y') }}</div>
                        <div style="color: #6b7280;">s/d</div>
                        <div>{{ $voucher->valid_until->format('d M Y') }}</div>
                    </td>
                    <td style="text-align: center;">
                        @if($voucher->stock === null)
                        <span style="color: #10b981; font-weight: 500;">
                            <i class="fas fa-infinity"></i> Unlimited
                        </span>
                        @else
                        <span style="font-weight: 600; {{ $voucher->stock <= 10 ? 'color: #ef4444;' : 'color: #1F2937;' }}">
                            {{ $voucher->stock }}
                        </span>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        <span style="font-weight: 600; color: #3b82f6;">
                            {{ $voucher->member_vouchers_count }}
                        </span>
                        @if($voucher->max_usage)
                        <span style="color: #6b7280; font-size: 0.85rem;">/ {{ $voucher->max_usage }}</span>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        @if($voucher->valid_until < now())
                            <span style="background: #fee2e2; color: #991b1b; padding: 0.35rem 0.75rem; border-radius: 12px; font-weight: 500; font-size: 0.85rem;">
                                <i class="fas fa-clock"></i> Expired
                            </span>
                        @elseif($voucher->is_active)
                            <span style="background: #d1fae5; color: #065f46; padding: 0.35rem 0.75rem; border-radius: 12px; font-weight: 500; font-size: 0.85rem;">
                                <i class="fas fa-check-circle"></i> Aktif
                            </span>
                        @else
                            <span style="background: #f3f4f6; color: #4b5563; padding: 0.35rem 0.75rem; border-radius: 12px; font-weight: 500; font-size: 0.85rem;">
                                <i class="fas fa-times-circle"></i> Nonaktif
                            </span>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 0.5rem; justify-content: center;">
                            <a href="{{ route('admin.vouchers.show', $voucher->id) }}" 
                               class="btn-icon btn-info" 
                               title="Detail"
                               style="background: #dbeafe; color: #1e40af; padding: 0.5rem; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; transition: all 0.3s;">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.vouchers.edit', $voucher->id) }}" 
                               class="btn-icon btn-warning" 
                               title="Edit"
                               style="background: #fef3c7; color: #92400e; padding: 0.5rem; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; transition: all 0.3s;">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.vouchers.destroy', $voucher->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus voucher ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn-icon btn-danger" 
                                        title="Hapus"
                                        style="background: #fee2e2; color: #991b1b; padding: 0.5rem; border-radius: 8px; border: none; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; transition: all 0.3s;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" style="text-align: center; padding: 3rem; color: #6b7280;">
                        <i class="fas fa-ticket-alt" style="font-size: 3rem; opacity: 0.3; margin-bottom: 1rem;"></i>
                        <p style="margin: 0;">Belum ada voucher. <a href="{{ route('admin.vouchers.create') }}" style="color: #FF6F00;">Tambah voucher pertama</a></p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
.btn-icon:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

#searchVoucher:focus, #filterStatus:focus, #filterType:focus {
    outline: none;
    border-color: #FF6F00;
    box-shadow: 0 0 0 3px rgba(255, 111, 0, 0.1);
}

.modern-table tbody tr:hover {
    background-color: #fffbf5;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchVoucher');
    const statusFilter = document.getElementById('filterStatus');
    const typeFilter = document.getElementById('filterType');
    const tableBody = document.getElementById('voucherTableBody');
    const rows = tableBody.querySelectorAll('tr');

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value;
        const typeValue = typeFilter.value;

        rows.forEach(row => {
            if (row.cells.length === 1) return; // Skip empty row

            const name = row.dataset.name || '';
            const code = row.dataset.code || '';
            const status = row.dataset.status || '';
            const type = row.dataset.type || '';
            const expired = row.dataset.expired || '';

            let matchSearch = name.includes(searchTerm) || code.includes(searchTerm);
            let matchStatus = true;
            
            if (statusValue === 'expired') {
                matchStatus = expired === 'yes';
            } else if (statusValue) {
                matchStatus = status === statusValue;
            }
            
            let matchType = !typeValue || type === typeValue;

            if (matchSearch && matchStatus && matchType) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterTable);
    statusFilter.addEventListener('change', filterTable);
    typeFilter.addEventListener('change', filterTable);

    // Auto-hide alerts
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
});
</script>
@endsection
