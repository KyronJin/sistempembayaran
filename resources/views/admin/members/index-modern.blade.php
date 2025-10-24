@extends('layouts.modern')

@section('title', 'Kelola Member')

@section('content')
<div class="content-header">
    <h1>Kelola Member</h1>
    <p>Manajemen data member dan persetujuan pendaftaran</p>
</div>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $members->total() }}</h3>
            <p>Total Member</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $members->where('status', 'active')->count() }}</h3>
            <p>Member Aktif</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $members->where('status', 'pending')->count() }}</h3>
            <p>Pending</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
            <i class="fas fa-ban"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $members->where('status', 'suspended')->count() }}</h3>
            <p>Suspended</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="modern-card" style="margin-bottom: 2rem;">
    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
        <input type="text" id="searchMember" placeholder="🔍 Cari nama atau kode member..." 
               style="padding: 0.75rem 1rem; border: 2px solid #e5e7eb; border-radius: 12px; flex: 1; min-width: 300px;">
        <select id="filterStatus" style="padding: 0.75rem 1rem; border: 2px solid #e5e7eb; border-radius: 12px;">
            <option value="">Semua Status</option>
            <option value="active">Aktif</option>
            <option value="pending">Pending</option>
            <option value="suspended">Suspended</option>
        </select>
    </div>
</div>

<!-- Members Table -->
<div class="modern-card">
    <div class="table-responsive">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>Kode Member</th>
                    <th>Nama</th>
                    <th>Kontak</th>
                    <th style="text-align: right;">Total Poin</th>
                    <th style="text-align: center;">Status</th>
                    <th style="text-align: center;">Tanggal Daftar</th>
                    <th style="text-align: center; width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="memberTableBody">
                @forelse($members as $member)
                <tr data-status="{{ $member->status }}" data-search="{{ strtolower($member->name . ' ' . $member->member_code) }}">
                    <td><strong>{{ $member->member_code }}</strong></td>
                    <td>
                        <div style="font-weight: 600; color: #1f2937;">{{ $member->name }}</div>
                        <div style="font-size: 0.875rem; color: #6b7280;">{{ $member->user->email }}</div>
                    </td>
                    <td>
                        <div style="font-size: 0.9375rem;">{{ $member->phone }}</div>
                        @if($member->address)
                        <div style="font-size: 0.875rem; color: #6b7280;">{{ Str::limit($member->address, 30) }}</div>
                        @endif
                    </td>
                    <td style="text-align: right; font-weight: 700; color: #4f46e5;">{{ number_format($member->points, 0, ',', '.') }}</td>
                    <td style="text-align: center;">
                        @if($member->status === 'active')
                        <span class="badge" style="background: #10b981;"><i class="fas fa-check"></i> Aktif</span>
                        @elseif($member->status === 'pending')
                        <span class="badge" style="background: #f59e0b;"><i class="fas fa-clock"></i> Pending</span>
                        @else
                        <span class="badge" style="background: #ef4444;"><i class="fas fa-ban"></i> Suspended</span>
                        @endif
                    </td>
                    <td style="text-align: center;">{{ $member->created_at->format('d/m/Y') }}</td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 0.5rem; justify-content: center;">
                            @if($member->status === 'pending')
                            <form action="{{ route('admin.members.approve', $member->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn-icon" style="background: #dcfce7; color: #059669;" title="Approve">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            @endif
                            @if($member->status === 'active')
                            <form action="{{ route('admin.members.suspend', $member->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Suspend member ini?');">
                                @csrf
                                <button type="submit" class="btn-icon" style="background: #fee2e2; color: #dc2626;" title="Suspend">
                                    <i class="fas fa-ban"></i>
                                </button>
                            </form>
                            @endif
                            <button class="btn-icon" title="Detail" onclick="showMemberDetail({{ $member->id }}, '{{ $member->member_code }}', '{{ $member->name }}', '{{ $member->phone }}', '{{ $member->email }}', {{ $member->points }}, '{{ $member->status }}', '{{ $member->created_at->format('d M Y') }}')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 3rem;">
                        <div style="opacity: 0.5;">
                            <i class="fas fa-users" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                            <p>Belum ada member</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($members->hasPages())
    <div style="padding: 1.5rem; border-top: 1px solid #e5e7eb;">
        {{ $members->links() }}
    </div>
    @endif
</div>

<!-- Member Detail Modal -->
<div id="memberModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div class="modern-card" style="max-width: 500px; width: 90%; margin: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="margin: 0; font-size: 1.25rem;"><i class="fas fa-user"></i> Detail Member</h3>
            <button onclick="closeMemberModal()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #6b7280;">&times;</button>
        </div>
        <div id="memberDetailContent"></div>
    </div>
</div>

<script>
// Filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchMember');
    const statusFilter = document.getElementById('filterStatus');
    const tableBody = document.getElementById('memberTableBody');
    const rows = tableBody.querySelectorAll('tr[data-status]');

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedStatus = statusFilter.value;

        rows.forEach(row => {
            const searchData = row.dataset.search;
            const status = row.dataset.status;

            const matchesSearch = searchData.includes(searchTerm);
            const matchesStatus = !selectedStatus || status === selectedStatus;

            row.style.display = matchesSearch && matchesStatus ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', filterTable);
    statusFilter.addEventListener('change', filterTable);
});

function showMemberDetail(id, code, name, phone, email, points, status, joinDate) {
    const statusBadge = status === 'active' ? 
        '<span class="badge" style="background: #10b981;">Aktif</span>' :
        status === 'pending' ?
        '<span class="badge" style="background: #f59e0b;">Pending</span>' :
        '<span class="badge" style="background: #ef4444;">Suspended</span>';

    document.getElementById('memberDetailContent').innerHTML = `
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <div style="padding: 1rem; background: #f9fafb; border-radius: 8px;">
                <div style="color: #6b7280; font-size: 0.875rem;">Kode Member</div>
                <div style="font-weight: 700; font-size: 1.125rem; color: #1f2937;">${code}</div>
            </div>
            <div>
                <div style="color: #6b7280; font-size: 0.875rem;">Nama Lengkap</div>
                <div style="font-weight: 600; color: #1f2937;">${name}</div>
            </div>
            <div>
                <div style="color: #6b7280; font-size: 0.875rem;">Kontak</div>
                <div style="color: #1f2937;">${phone}<br>${email}</div>
            </div>
            <div>
                <div style="color: #6b7280; font-size: 0.875rem;">Total Poin</div>
                <div style="font-weight: 700; font-size: 1.5rem; color: #4f46e5;">${points.toLocaleString('id-ID')}</div>
            </div>
            <div>
                <div style="color: #6b7280; font-size: 0.875rem;">Status</div>
                <div>${statusBadge}</div>
            </div>
            <div>
                <div style="color: #6b7280; font-size: 0.875rem;">Tanggal Bergabung</div>
                <div style="color: #1f2937;">${joinDate}</div>
            </div>
        </div>
    `;
    document.getElementById('memberModal').style.display = 'flex';
}

function closeMemberModal() {
    document.getElementById('memberModal').style.display = 'none';
}
</script>
@endsection
