@extends('layouts.admin')
@section('title','Semua Donasi')
@section('nav_donasi','active')

@section('content')
<div class="topbar" style="display:flex;justify-content:space-between;align-items:center">
    <div><p class="page-title">Semua Donasi</p><p class="page-sub">Rp {{ number_format($totalPaid,0,',','.') }} total berhasil · {{ $totalPending }} pending</p></div>
    <a href="{{ route('admin.donasi.export', request()->query()) }}" class="btn btn-ghost"><i class="bi bi-download"></i> Export CSV</a>
</div>
<div class="page-content">
    <form method="GET" style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:16px">
        <input type="text" name="search" class="form-control" placeholder="Cari donor / order ID..." value="{{ request('search') }}" style="max-width:220px">
        <select name="status" class="form-control" style="max-width:140px">
            <option value="">Semua Status</option>
            <option value="paid" {{ request('status')==='paid'?'selected':'' }}>Paid</option>
            <option value="pending" {{ request('status')==='pending'?'selected':'' }}>Pending</option>
            <option value="failed" {{ request('status')==='failed'?'selected':'' }}>Failed</option>
        </select>
        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" style="max-width:150px">
        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" style="max-width:150px">
        <button type="submit" class="btn btn-ghost"><i class="bi bi-search"></i> Filter</button>
        @if(request()->anyFilled(['search','status','date_from','date_to']))<a href="{{ route('admin.donasi.index') }}" class="btn btn-ghost">Reset</a>@endif
    </form>

    <div class="card">
        <table class="table">
            <thead><tr><th>Order ID</th><th>Donor</th><th>Streamer</th><th>Nominal</th><th>Metode</th><th>Status</th><th>Waktu</th></tr></thead>
            <tbody>
            @forelse($donasi as $d)
            <tr>
                <td style="font-size:11px;color:var(--muted);font-family:monospace">{{ $d->merchant_order_id ?? '-' }}</td>
                <td><div style="font-weight:700">{{ $d->donor_nama }}</div><div style="font-size:11px;color:var(--muted)">{{ Str::limit($d->pesan,30) }}</div></td>
                <td style="color:var(--muted)">{{ $d->user->nama ?? '-' }}</td>
                <td style="font-weight:800;color:var(--green)">{{ $d->jumlahFormat }}</td>
                <td><span class="badge badge-muted">{{ $d->payment_method ?? '-' }}</span></td>
                <td>
                    @if($d->payment_status==='paid') <span class="badge badge-green">Paid</span>
                    @elseif($d->payment_status==='pending') <span class="badge badge-yellow">Pending</span>
                    @elseif($d->payment_status==='failed') <span class="badge badge-red">Failed</span>
                    @else <span class="badge badge-muted">{{ $d->payment_status }}</span>
                    @endif
                </td>
                <td style="color:var(--muted);font-size:11px">{{ $d->created_at->format('d M Y, H:i') }}</td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;color:var(--muted);padding:30px">Tidak ada data</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $donasi->withQueryString()->links() }}</div>
</div>
@endsection
