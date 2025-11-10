@extends('layouts.modern')

@section('title', 'Kelola Member')

@section('content')
<div class="content-header">
    <h1>Kelola Member</h1>
    <p>Manajemen data member dan persetujuan pendaftaran</p>
</div>

<!-- Success Message -->
@if(session('success'))
<div class="alert alert-success" id="alertSuccess" style="margin-bottom: 2rem; padding: 1rem 1.5rem; background: #dcfce7; border-left: 4px solid #10b981; border-radius: 8px; color: #065f46;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

<!-- Error Message -->
@if(session('error'))
<div class="alert alert-error" id="alertError" style="margin-bottom: 2rem; padding: 1rem 1.5rem; background: #fee2e2; border-left: 4px solid #ef4444; border-radius: 8px; color: #991b1b;">
    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
</div>
@endif

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: #3B82F6;">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $members->total() }}</h3>
            <p>Total Member</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #10B981;">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $members->where('status', 'active')->count() }}</h3>
            <p>Member Aktif</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #F59E0B;">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $members->where('status', 'pending')->count() }}</h3>
            <p>Pending</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #EF4444;">
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
                    <th class="mobile-hide">Kode Member</th>
                    <th>Nama</th>
                    <th class="mobile-hide">Kontak</th>
                    <th style="text-align: right;" class="mobile-hide">Total Poin</th>
                    <th style="text-align: center;">Status</th>
                    <th style="text-align: center;" class="mobile-hide">Tanggal Daftar</th>
                    <th style="text-align: center; width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="memberTableBody">
                @forelse($members as $member)
                <tr data-status="{{ $member->status }}" data-search="{{ strtolower($member->user->name . ' ' . $member->member_code . ' ' . ($member->user->phone ?? '')) }}">
                    <td class="mobile-hide"><strong>{{ $member->member_code }}</strong></td>
                    <td>
                        <div style="font-weight: 600; color: #1f2937;">{{ $member->user->name }}</div>
                        <div style="font-size: 0.875rem; color: #6b7280;">{{ $member->user->email }}</div>
                        <div style="font-size: 0.75rem; color: #9ca3af; margin-top: 2px;" class="mobile-show">{{ $member->user->phone ?? '-' }}</div>
                    </td>
                    <td class="mobile-hide">
                        <div style="font-size: 0.9375rem;">{{ $member->user->phone ?? '-' }}</div>
                    </td>
                    <td style="text-align: right; font-weight: 700; color: #4f46e5;" class="mobile-hide">{{ number_format($member->points, 0, ',', '.') }}</td>
                    <td style="text-align: center;">
                        @if($member->status === 'active')
                        <span class="badge" style="background: #10B981; color: white; font-weight: 600;"><i class="fas fa-check"></i> <span class="mobile-hide">Aktif</span></span>
                        @elseif($member->status === 'pending')
                        <span class="badge" style="background: #F59E0B; color: white; font-weight: 600;"><i class="fas fa-clock"></i> <span class="mobile-hide">Pending</span></span>
                        @else
                        <span class="badge" style="background: #EF4444; color: white; font-weight: 600;"><i class="fas fa-ban"></i> <span class="mobile-hide">Suspended</span></span>
                        @endif
                    </td>
                    <td style="text-align: center;" class="mobile-hide">{{ $member->created_at->format('d/m/Y') }}</td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 0.5rem; justify-content: center;">
                            @if($member->status === 'pending')
                            <form id="approve-form-{{ $member->id }}" action="{{ route('admin.members.approve', $member->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="button" onclick="if(confirm('Setujui member {{ addslashes($member->user->name) }}?')) { document.getElementById('approve-form-{{ $member->id }}').submit(); }" class="btn-icon" style="background: #10B981; color: white;" title="Approve Member">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            @endif
                            @if($member->status === 'active')
                            <form id="suspend-form-{{ $member->id }}" action="{{ route('admin.members.suspend', $member->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="button" onclick="if(confirm('Suspend member {{ addslashes($member->user->name) }}?')) { document.getElementById('suspend-form-{{ $member->id }}').submit(); }" class="btn-icon" style="background: #EF4444; color: white;" title="Suspend Member">
                                    <i class="fas fa-ban"></i>
                                </button>
                            </form>
                            @endif
                            <button class="btn-icon" title="Detail" style="background: #FF6F00; color: white;" onclick="showMemberDetail({{ $member->id }}, '{{ $member->member_code }}', '{{ addslashes($member->user->name) }}', '{{ $member->user->phone ?? '-' }}', '{{ $member->user->email }}', {{ $member->points }}, '{{ $member->status }}', '{{ $member->created_at->format('d M Y') }}')">
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
<div id="memberModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.75); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
    <div class="modern-card" style="max-width: 600px; width: 90%; margin: 2rem; background: white; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5); animation: modalSlideIn 0.3s ease-out;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 2px solid #FF6F00;">
            <h3 style="margin: 0; font-size: 1.5rem; font-weight: 700; color: #1F2937;">
                <i class="fas fa-id-card" style="color: #FF6F00; margin-right: 8px;"></i> Detail Member
            </h3>
            <button onclick="closeMemberModal()" style="background: #FEE2E2; border: none; font-size: 1.5rem; cursor: pointer; color: #DC2626; width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; transition: all 0.2s;" onmouseover="this.style.background='#FCA5A5'" onmouseout="this.style.background='#FEE2E2'">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="memberDetailContent"></div>
    </div>
</div>

<style>
@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-20px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}
</style>

<style>
.mobile-show {
    display: none;
}

@media (max-width: 768px) {
    .mobile-show {
        display: block;
    }
}
</style>

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
        '<span class="badge" style="background: #10B981; color: white; padding: 8px 16px; font-weight: 600;"><i class="fas fa-check-circle"></i> Aktif</span>' :
        status === 'pending' ?
        '<span class="badge" style="background: #F59E0B; color: white; padding: 8px 16px; font-weight: 600;"><i class="fas fa-clock"></i> Pending</span>' :
        '<span class="badge" style="background: #EF4444; color: white; padding: 8px 16px; font-weight: 600;"><i class="fas fa-ban"></i> Suspended</span>';

    document.getElementById('memberDetailContent').innerHTML = `
        <div style="display: grid; gap: 1.5rem;">
            <!-- Member Code Card -->
            <div style="padding: 1.5rem; background: linear-gradient(135deg, #FF6F00 0%, #F57C00 100%); border-radius: 16px; box-shadow: 0 4px 12px rgba(255,111,0,0.3);">
                <div style="color: rgba(255,255,255,0.9); font-size: 0.875rem; margin-bottom: 4px; font-weight: 600;">KODE MEMBER</div>
                <div style="font-weight: 700; font-size: 1.75rem; color: white; letter-spacing: 2px;">${code}</div>
            </div>

            <!-- Personal Info -->
            <div style="display: grid; gap: 1rem; background: #F9FAFB; padding: 1.5rem; border-radius: 12px; border: 2px solid #E5E7EB;">
                <div>
                    <div style="color: #6B7280; font-size: 0.875rem; font-weight: 600; margin-bottom: 4px; text-transform: uppercase;">Nama Lengkap</div>
                    <div style="font-weight: 600; color: #1F2937; font-size: 1.125rem;">${name}</div>
                </div>
                <div style="border-top: 1px dashed #D1D5DB; padding-top: 1rem;">
                    <div style="color: #6B7280; font-size: 0.875rem; font-weight: 600; margin-bottom: 4px; text-transform: uppercase;">Kontak</div>
                    <div style="color: #1F2937;">
                        <div style="margin-bottom: 4px;"><i class="fas fa-phone" style="color: #FF6F00; width: 20px;"></i> ${phone}</div>
                        <div><i class="fas fa-envelope" style="color: #FF6F00; width: 20px;"></i> ${email}</div>
                    </div>
                </div>
            </div>

            <!-- Points & Status Grid -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div style="padding: 1.25rem; background: white; border: 2px solid #FF6F00; border-radius: 12px; text-align: center;">
                    <div style="color: #6B7280; font-size: 0.75rem; font-weight: 600; margin-bottom: 8px; text-transform: uppercase;">Total Poin</div>
                    <div style="font-weight: 700; font-size: 2rem; color: #FF6F00;">${points.toLocaleString('id-ID')}</div>
                </div>
                <div style="padding: 1.25rem; background: white; border: 2px solid #E5E7EB; border-radius: 12px; text-align: center;">
                    <div style="color: #6B7280; font-size: 0.75rem; font-weight: 600; margin-bottom: 8px; text-transform: uppercase;">Status</div>
                    <div style="margin-top: 4px;">${statusBadge}</div>
                </div>
            </div>

            <!-- Join Date -->
            <div style="padding: 1rem; background: #EFF6FF; border-left: 4px solid #3B82F6; border-radius: 8px;">
                <div style="color: #1E40AF; font-size: 0.875rem; font-weight: 600;">
                    <i class="fas fa-calendar-check"></i> Bergabung sejak ${joinDate}
                </div>
            </div>

            <!-- Action Buttons -->
            <div style="display: flex; gap: 0.75rem; padding-top: 1rem; border-top: 2px solid #E5E7EB;">
                ${status === 'pending' ? `
                    <form action="/admin/members/${id}/approve" method="POST" style="flex: 1;">
                        @csrf
                        <button type="submit" onclick="return confirm('Approve member ini?')" 
                                style="width: 100%; padding: 0.875rem; background: #10B981; color: white; border: none; border-radius: 12px; font-weight: 600; cursor: pointer; font-size: 0.938rem; transition: all 0.2s; box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);"
                                onmouseover="this.style.background='#059669'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(16, 185, 129, 0.4)'"
                                onmouseout="this.style.background='#10B981'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(16, 185, 129, 0.3)'">
                            <i class="fas fa-check-circle"></i> Approve Member
                        </button>
                    </form>
                    <form action="/admin/members/${id}/suspend" method="POST" style="flex: 1;">
                        @csrf
                        <button type="submit" onclick="return confirm('Suspend member ini?')" 
                                style="width: 100%; padding: 0.875rem; background: #EF4444; color: white; border: none; border-radius: 12px; font-weight: 600; cursor: pointer; font-size: 0.938rem; transition: all 0.2s; box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);"
                                onmouseover="this.style.background='#DC2626'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(239, 68, 68, 0.4)'"
                                onmouseout="this.style.background='#EF4444'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(239, 68, 68, 0.3)'">
                            <i class="fas fa-ban"></i> Tolak
                        </button>
                    </form>
                ` : status === 'suspended' ? `
                    <form action="/admin/members/${id}/approve" method="POST" style="flex: 1;">
                        @csrf
                        <button type="submit" onclick="return confirm('Pulihkan member ini?')" 
                                style="width: 100%; padding: 0.875rem; background: #10B981; color: white; border: none; border-radius: 12px; font-weight: 600; cursor: pointer; font-size: 0.938rem; transition: all 0.2s; box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);"
                                onmouseover="this.style.background='#059669'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(16, 185, 129, 0.4)'"
                                onmouseout="this.style.background='#10B981'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(16, 185, 129, 0.3)'">
                            <i class="fas fa-undo"></i> Pulihkan / Unsuspend Member
                        </button>
                    </form>
                ` : status === 'active' ? `
                    <form action="/admin/members/${id}/suspend" method="POST" style="flex: 1;">
                        @csrf
                        <button type="submit" onclick="return confirm('Suspend member ini?')" 
                                style="width: 100%; padding: 0.875rem; background: #EF4444; color: white; border: none; border-radius: 12px; font-weight: 600; cursor: pointer; font-size: 0.938rem; transition: all 0.2s; box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);"
                                onmouseover="this.style.background='#DC2626'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(239, 68, 68, 0.4)'"
                                onmouseout="this.style.background='#EF4444'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(239, 68, 68, 0.3)'">
                            <i class="fas fa-ban"></i> Suspend Member
                        </button>
                    </form>
                ` : ''}
            </div>
        </div>
    `;
    document.getElementById('memberModal').style.display = 'flex';
}

function closeMemberModal() {
    document.getElementById('memberModal').style.display = 'none';
}

// Auto-hide success message after 5 seconds
setTimeout(function() {
    const alert = document.querySelector('.alert-success, .alert-error');
    if (alert) {
        alert.style.transition = 'opacity 0.5s';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
    }
}, 5000);

// Debug: Log form submissions
document.querySelectorAll('form[action*="approve"], form[action*="suspend"]').forEach(form => {
    form.addEventListener('submit', function(e) {
        console.log('Form submitting to:', this.action);
        console.log('Method:', this.method);
        console.log('CSRF Token:', this.querySelector('[name="_token"]')?.value);
    });
});
</script>
@endsection
