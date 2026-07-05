@extends('layouts.admin')
@section('title','Detail User')
@section('nav_users','active')

@section('content')
<div class="topbar" style="display:flex;justify-content:space-between;align-items:center">
    <div><p class="page-title">{{ $user->nama }}</p><p class="page-sub">{{ $user->username }} · {{ $user->email }}</p></div>
    <a href="{{ route('admin.users.index') }}" class="btn btn-ghost"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>
<div class="page-content">
    <div class="grid-2" style="margin-bottom:20px">
        <div class="card">
            <div class="card-header"><p class="card-title">Info User</p></div>
            <div class="card-body">
                <table style="width:100%;font-size:13px">
                    <tr><td style="color:var(--muted);padding:6px 0;width:120px">Nama</td><td style="font-weight:700">{{ $user->nama }}</td></tr>
                    <tr><td style="color:var(--muted);padding:6px 0">Username</td><td>{{ $user->username }}</td></tr>
                    <tr><td style="color:var(--muted);padding:6px 0">Email</td><td>{{ $user->email }}</td></tr>
                    <tr><td style="color:var(--muted);padding:6px 0">Daftar</td><td>{{ $user->created_at->format('d M Y, H:i') }}</td></tr>
                    <tr><td style="color:var(--muted);padding:6px 0">Status</td><td>
                        @if($user->is_suspended)<span class="badge badge-red">Suspended</span>@else<span class="badge badge-green">Aktif</span>@endif
                    </td></tr>
                    <tr><td style="color:var(--muted);padding:6px 0">Total Nominal</td><td style="font-weight:800;color:var(--green)">Rp {{ number_format($totalNominal,0,',','.') }}</td></tr>
                </table>
                <div style="margin-top:16px;display:flex;gap:8px">
                    <form method="POST" action="{{ route('admin.users.suspend',$user) }}">
                        @csrf
                        <button type="submit" class="btn {{ $user->is_suspended ? 'btn-success' : 'btn-danger' }} btn-sm">
                            <i class="bi bi-{{ $user->is_suspended ? 'check-circle' : 'slash-circle' }}"></i>
                            {{ $user->is_suspended ? 'Aktifkan' : 'Suspend' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><p class="card-title">Statistik</p></div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                    <div style="background:var(--dark3);border-radius:8px;padding:14px;text-align:center">
                        <div style="font-size:22px;font-weight:800;color:var(--green)">{{ $donasi->total() }}</div>
                        <div style="font-size:11px;color:var(--muted);margin-top:4px">Total Donasi</div>
                    </div>
                    <div style="background:var(--dark3);border-radius:8px;padding:14px;text-align:center">
                        <div style="font-size:16px;font-weight:800;color:var(--yellow)">Rp {{ number_format($totalNominal/1000,0) }}rb</div>
                        <div style="font-size:11px;color:var(--muted);margin-top:4px">Total Nominal</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><p class="card-title">Riwayat Donasi</p></div>
        <table class="table">
            <thead><tr><th>Donor</th><th>Nominal</th><th>Metode</th><th>Pesan</th><th>Waktu</th></tr></thead>
            <tbody>
            @forelse($donasi as $d)
            <tr>
                <td style="font-weight:700">{{ $d->donor_nama }}</td>
                <td style="font-weight:700;color:var(--green)">{{ $d->jumlahFormat }}</td>
                <td><span class="badge badge-muted">{{ $d->payment_method }}</span></td>
                <td style="color:var(--muted)">{{ Str::limit($d->pesan,50) ?: '-' }}</td>
                <td style="color:var(--muted)">{{ $d->paid_at?->format('d M Y, H:i') }}</td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center;color:var(--muted);padding:20px">Belum ada donasi</td></tr>
            @endforelse
            </tbody>
        </table>
        <div class="pagination">{{ $donasi->links() }}</div>
    </div>
</div>
@endsection