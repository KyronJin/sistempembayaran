@extends('layouts.modern')

@section('title', 'Kelola Member')

@section('content')
<div class="content-header">
    <h1>Kelola Member</h1>
    <p>Manajemen data member dan persetujuan pendaftaran</p>
</div>

<!-- Success Message -->
@if(session('success'))
<div class="alert alert-success" style="margin-bottom: 2rem; padding: 1rem 1.5rem; background: #dcfce7; border-left: 4px solid #10b981; border-radius: 8px; color: #065f46;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

<!-- Error Message -->
@if(session('error'))
<div class="alert alert-error" style="margin-bottom: 2rem; padding: 1rem 1.5rem; background: #fee2e2; border-left: 4px solid #ef4444; border-radius: 8px; color: #991b1b;">
    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
</div>
@endif

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
    <form method="GET" action="{{ route('kasir.members.index') }}" style="display: flex; gap: 1rem; flex-wrap: wrap;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="🔍 Cari nama, kode member atau no. HP..." 
               style="padding: 0.75rem 1rem; border: 2px solid #e5e7eb; border-radius: 12px; flex: 1; min-width: 300px;">
        <select name="status" style="padding: 0.75rem 1rem; border: 2px solid #e5e7eb; border-radius: 12px;">
            <option value="">Semua Status</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
        </select>
        <button type="submit" class="btn btn-primary" style="padding: 0.75rem 1.5rem; border-radius: 12px;">
            <i class="fas fa-search"></i> Cari
        </button>
        @if(request('search') || request('status'))
        <a href="{{ route('kasir.members.index') }}" class="btn" style="padding: 0.75rem 1.5rem; border-radius: 12px; background: #f3f4f6; color: #4b5563;">
            <i class="fas fa-times"></i> Reset
        </a>
        @endif
    </form>
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
                    <th style="text-align: center; width: 100px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($members as $member)
                <tr>
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
                        <span class="badge" style="background: #10b981;"><i class="fas fa-check"></i> <span class="mobile-hide">Aktif</span></span>
                        @elseif($member->status === 'pending')
                        <span class="badge" style="background: #f59e0b;"><i class="fas fa-clock"></i> <span class="mobile-hide">Pending</span></span>
                        @else
                        <span class="badge" style="background: #ef4444;"><i class="fas fa-ban"></i> <span class="mobile-hide">Suspended</span></span>
                        @endif
                    </td>
                    <td style="text-align: center;" class="mobile-hide">{{ $member->created_at->format('d/m/Y') }}</td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 0.5rem; justify-content: center;">
                            @if($member->status === 'pending')
                            <form id="approve-form-{{ $member->id }}" action="{{ route('kasir.members.approve', $member->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="button" onclick="if(confirm('Setujui member {{ addslashes($member->user->name) }}?')) { document.getElementById('approve-form-{{ $member->id }}').submit(); }" class="btn-icon" style="background: #dcfce7; color: #059669;" title="Approve Member">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            @endif
                            <button class="btn-icon" title="Detail" onclick="showMemberDetail({{ json_encode([
                                'id' => $member->id,
                                'code' => $member->member_code,
                                'name' => $member->user->name,
                                'email' => $member->user->email,
                                'phone' => $member->user->phone ?? '-',
                                'points' => $member->points,
                                'status' => $member->status,
                                'created_at' => $member->created_at->format('d M Y H:i')
                            ]) }})">
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
<div id="memberDetailModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div class="modern-card" style="max-width: 600px; width: 90%; max-height: 90vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 2px solid #e5e7eb;">
            <h2 style="margin: 0; color: #323232;">Detail Member</h2>
            <button onclick="closeMemberDetail()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #6b7280;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div id="memberDetailContent">
            <!-- Will be populated by JavaScript -->
        </div>
    </div>
</div>

<style>
.modern-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    padding: 2rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.stat-info h3 {
    margin: 0;
    font-size: 2rem;
    font-weight: 700;
    color: #323232;
}

.stat-info p {
    margin: 0;
    color: #6b7280;
    font-size: 0.9375rem;
}

.modern-table {
    width: 100%;
    border-collapse: collapse;
}

.modern-table thead {
    background: #f9fafb;
}

.modern-table th {
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    color: #323232;
    border-bottom: 2px solid #e5e7eb;
}

.modern-table td {
    padding: 1rem;
    border-bottom: 1px solid #f3f4f6;
}

.modern-table tbody tr:hover {
    background: #fafafa;
}

.badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.375rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.875rem;
    font-weight: 600;
    color: white;
}

.btn-icon {
    width: 36px;
    height: 36px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    background: #f3f4f6;
    color: #4b5563;
}

.btn-icon:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.btn {
    border: none;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

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
function showMemberDetail(memberData) {
    const modal = document.getElementById('memberDetailModal');
    const content = document.getElementById('memberDetailContent');
    
    const statusColors = {
        'active': { bg: '#dcfce7', text: '#059669', icon: 'check' },
        'pending': { bg: '#fef3c7', text: '#d97706', icon: 'clock' },
        'suspended': { bg: '#fee2e2', text: '#dc2626', icon: 'ban' }
    };
    
    const statusColor = statusColors[memberData.status];
    
    content.innerHTML = `
        <div style="display: grid; gap: 1.5rem;">
            <div style="background: #f9fafb; padding: 1.5rem; border-radius: 12px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div>
                        <div style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.25rem;">Kode Member</div>
                        <div style="font-weight: 700; font-size: 1.125rem; color: #323232;">${memberData.code}</div>
                    </div>
                    <div>
                        <div style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.25rem;">Status</div>
                        <span class="badge" style="background: ${statusColor.bg}; color: ${statusColor.text};">
                            <i class="fas fa-${statusColor.icon}"></i> ${memberData.status.toUpperCase()}
                        </span>
                    </div>
                </div>
            </div>
            
            <div>
                <div style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.5rem;">Nama Lengkap</div>
                <div style="font-weight: 600; font-size: 1.125rem; color: #323232;">${memberData.name}</div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <div style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.5rem;">Email</div>
                    <div style="color: #323232;">${memberData.email}</div>
                </div>
                <div>
                    <div style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.5rem;">No. HP</div>
                    <div style="color: #323232;">${memberData.phone}</div>
                </div>
            </div>
            
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 1.5rem; border-radius: 12px; color: white;">
                <div style="font-size: 0.875rem; margin-bottom: 0.5rem; opacity: 0.9;">Total Poin</div>
                <div style="font-weight: 700; font-size: 2rem;">${memberData.points.toLocaleString('id-ID')}</div>
            </div>
            
            <div>
                <div style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.5rem;">Tanggal Daftar</div>
                <div style="color: #323232;"><i class="far fa-calendar"></i> ${memberData.created_at}</div>
            </div>
        </div>
    `;
    
    modal.style.display = 'flex';
}

function closeMemberDetail() {
    document.getElementById('memberDetailModal').style.display = 'none';
}

// Close modal when clicking outside
document.getElementById('memberDetailModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeMemberDetail();
    }
});

// Auto-hide success message after 5 seconds
setTimeout(function() {
    const alert = document.querySelector('.alert-success');
    if (alert) {
        alert.style.transition = 'opacity 0.5s';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
    }
}, 5000);
</script>
@endsection
