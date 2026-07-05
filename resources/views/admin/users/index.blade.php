@extends('layouts.admin')
@section('title','Kelola User')
@section('nav_users','active')

@section('content')
<div class="topbar" style="display:flex;justify-content:space-between;align-items:center">
    <div><p class="page-title">Kelola User</p><p class="page-sub">{{ $users->total() }} user terdaftar</p></div>
</div>
<div class="page-content">
    @if(session('success'))<div class="alert alert-success"><i class="bi bi-check-circle-fill"></i>{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-error"><i class="bi bi-exclamation-circle-fill"></i>{{ session('error') }}</div>@endif

    <form method="GET" class="search-bar">
        <input type="text" name="search" class="form-control" placeholder="Cari nama, username, email..." value="{{ request('search') }}" style="max-width:300px">
        <select name="status" class="form-control" style="max-width:150px">
            <option value="">Semua Status</option>
            <option value="active" {{ request('status')==='active'?'selected':'' }}>Aktif</option>
            <option value="suspended" {{ request('status')==='suspended'?'selected':'' }}>Suspended</option>
        </select>
        <button type="submit" class="btn btn-ghost"><i class="bi bi-search"></i> Cari</button>
        @if(request('search') || request('status'))<a href="{{ route('admin.users.index') }}" class="btn btn-ghost">Reset</a>@endif
    </form>

    <div class="card">
        <table class="table">
            <thead><tr><th>User</th><th>Email</th><th>Donasi</th><th>Total Nominal</th><th>Daftar</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
            @forelse($users as $u)
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:10px">
                        <div style="width:32px;height:32px;border-radius:50%;background:var(--sky);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;color:#1a1a2e;flex-shrink:0">{{ $u->initials }}</div>
                        <div><div style="font-weight:700">{{ $u->nama }}</div><div style="font-size:11px;color:var(--muted)">{{ $u->username }}</div></div>
                    </div>
                </td>
                <td style="color:var(--muted)">{{ $u->email }}</td>
                <td>{{ number_format($u->total_donasi) }}x</td>
                <td style="font-weight:700;color:var(--green)">Rp {{ number_format($u->total_nominal ?? 0,0,',','.') }}</td>
                <td style="color:var(--muted)">{{ $u->created_at->format('d M Y') }}</td>
                <td>
                    @if($u->is_suspended)
                        <span class="badge badge-red">Suspended</span>
                    @else
                        <span class="badge badge-green">Aktif</span>
                    @endif
                </td>
                <td>
                    <div style="display:flex;gap:6px">
                        <a href="{{ route('admin.users.show',$u) }}" class="btn btn-ghost btn-sm"><i class="bi bi-eye"></i></a>
                        <form method="POST" action="{{ route('admin.users.suspend',$u) }}">
                            @csrf
                            <button type="submit" class="btn btn-sm {{ $u->is_suspended ? 'btn-success' : 'btn-danger' }}" onclick="return confirm('{{ $u->is_suspended ? 'Aktifkan' : 'Suspend' }} user ini?')">
                                <i class="bi bi-{{ $u->is_suspended ? 'check-circle' : 'slash-circle' }}"></i>
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.users.destroy',$u) }}">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus user {{ $u->nama }}? Semua data donasi ikut terhapus!')">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;color:var(--muted);padding:30px">Tidak ada user ditemukan</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $users->withQueryString()->links() }}</div>
</div>
@endsection