@extends('layouts.modern')

@section('title', 'Kelola User')

@section('content')
<div class="content-header">
    <h1>Kelola User</h1>
    <p>Manajemen user admin dan kasir</p>
</div>

<!-- Stats -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $users->total() }}</h3>
            <p>Total User</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
            <i class="fas fa-user-shield"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $users->where('role', 'admin')->count() }}</h3>
            <p>Admin</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
            <i class="fas fa-cash-register"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $users->where('role', 'kasir')->count() }}</h3>
            <p>Kasir</p>
        </div>
    </div>
</div>

<!-- Actions -->
<div class="modern-card" style="margin-bottom: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <input type="text" id="searchUser" placeholder="🔍 Cari nama atau email..." 
               style="padding: 0.75rem 1rem; border: 2px solid #e5e7eb; border-radius: 12px; flex: 1; max-width: 400px;">
        <a href="{{ route('admin.users.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i> Tambah User
        </a>
    </div>
</div>

<!-- Users Table -->
<div class="modern-card">
    <div class="table-responsive">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th style="text-align: center;">Role</th>
                    <th style="text-align: center;">Tanggal Dibuat</th>
                    <th style="text-align: center; width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="userTableBody">
                @forelse($users as $user)
                <tr data-search="{{ strtolower($user->name . ' ' . $user->email) }}">
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight: 600; color: #1f2937;">{{ $user->name }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td style="text-align: center;">
                        @if($user->role === 'admin')
                        <span class="badge" style="background: #ef4444;"><i class="fas fa-shield-alt"></i> Admin</span>
                        @elseif($user->role === 'kasir')
                        <span class="badge" style="background: #10b981;"><i class="fas fa-cash-register"></i> Kasir</span>
                        @else
                        <span class="badge" style="background: #667eea;"><i class="fas fa-user"></i> Member</span>
                        @endif
                    </td>
                    <td style="text-align: center;">{{ $user->created_at->format('d M Y') }}</td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 0.5rem; justify-content: center;">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-icon" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.reset-password', $user->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Reset password user ini?');">
                                @csrf
                                <button type="submit" class="btn-icon" style="background: #fef3c7; color: #f59e0b;" title="Reset Password">
                                    <i class="fas fa-key"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus user ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon" style="background: #fee2e2; color: #dc2626;" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 3rem;">
                        <div style="opacity: 0.5;">
                            <i class="fas fa-users" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                            <p>Belum ada user</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div style="padding: 1.5rem; border-top: 1px solid #e5e7eb;">
        {{ $users->links() }}
    </div>
    @endif
</div>

<script>
// Search functionality
document.getElementById('searchUser').addEventListener('input', function(e) {
    const search = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('#userTableBody tr[data-search]');
    
    rows.forEach(row => {
        const searchData = row.dataset.search;
        row.style.display = searchData.includes(search) ? '' : 'none';
    });
});
</script>
@endsection
