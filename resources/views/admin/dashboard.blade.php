@extends('layouts.admin')
@section('title','Dashboard')
@section('nav_dashboard','active')

@section('styles')
<style>
.two-col{display:grid;grid-template-columns:1fr 1fr;gap:20px}
.donor-row{display:flex;align-items:center;gap:10px;padding:10px 0;border-bottom:1px solid var(--border)}
.donor-row:last-child{border-bottom:none}
.donor-avatar{width:34px;height:34px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;color:#1a1a2e;flex-shrink:0}
.chart-bar-wrap{display:flex;flex-direction:column;gap:6px}
.chart-bar-row{display:flex;align-items:center;gap:8px;font-size:11px}
.chart-bar-label{width:36px;color:var(--muted);text-align:right;flex-shrink:0}
.chart-bar-track{flex:1;height:20px;background:var(--dark3);border-radius:4px;overflow:hidden}
.chart-bar-fill{height:100%;background:var(--red);border-radius:4px;transition:width 0.8s ease;display:flex;align-items:center;padding-left:8px;font-size:10px;font-weight:700;color:#fff;white-space:nowrap}
.chart-bar-val{color:var(--muted2);font-size:10px}
</style>
@endsection

@section('content')
<div class="topbar">
    <div>
        <p class="page-title">Dashboard Admin</p>
        <p class="page-sub">Selamat datang, {{ auth()->user()->nama }} 👋</p>
    </div>
</div>

<div class="page-content">
    {{-- Stats --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background:var(--sky-dim);color:var(--sky)"><i class="bi bi-people-fill"></i></div>
            <div class="stat-label">Total User</div>
            <div class="stat-value">{{ number_format($totalUser) }}</div>
            <div class="stat-sub">Streamer terdaftar</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:var(--green-dim);color:var(--green)"><i class="bi bi-heart-fill"></i></div>
            <div class="stat-label">Total Donasi</div>
            <div class="stat-value">{{ number_format($totalDonasi) }}</div>
            <div class="stat-sub">Transaksi berhasil</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:var(--yellow-dim);color:var(--yellow)"><i class="bi bi-cash-stack"></i></div>
            <div class="stat-label">Total Nominal</div>
            <div class="stat-value" style="font-size:18px">Rp {{ number_format($totalNominal,0,',','.') }}</div>
            <div class="stat-sub">Semua waktu</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:var(--red-dim);color:var(--red)"><i class="bi bi-calendar-day"></i></div>
            <div class="stat-label">Hari Ini</div>
            <div class="stat-value" style="font-size:18px">Rp {{ number_format($todayNominal,0,',','.') }}</div>
            <div class="stat-sub">{{ $todayDonasi }} transaksi</div>
        </div>
    </div>

    <div class="two-col">
        {{-- Grafik 7 hari --}}
        <div class="card">
            <div class="card-header"><div><p class="card-title">Donasi 7 Hari Terakhir</p></div></div>
            <div class="card-body">
                @php $maxNominal = max(collect($chart)->pluck('nominal')->toArray() ?: [1]); @endphp
                <div class="chart-bar-wrap">
                    @foreach($chart as $c)
                    @php $pct = $maxNominal > 0 ? ($c['nominal'] / $maxNominal * 100) : 0; @endphp
                    <div class="chart-bar-row">
                        <span class="chart-bar-label">{{ $c['label'] }}</span>
                        <div class="chart-bar-track">
                            <div class="chart-bar-fill" style="width:{{ max($pct,2) }}%">
                                @if($c['nominal'] > 0) Rp {{ number_format($c['nominal']/1000,0) }}rb @endif
                            </div>
                        </div>
                        <span class="chart-bar-val">{{ $c['count'] }}x</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Recent Users --}}
        <div class="card">
            <div class="card-header">
                <div><p class="card-title">User Terbaru</p></div>
                <a href="{{ route('admin.users.index') }}" class="btn btn-ghost btn-sm">Semua <i class="bi bi-arrow-right"></i></a>
            </div>
            <div class="card-body">
                @foreach($recentUsers as $u)
                <div class="donor-row">
                    <div class="donor-avatar" style="background:var(--sky)">{{ $u->initials }}</div>
                    <div style="flex:1">
                        <div style="font-size:13px;font-weight:700">{{ $u->nama }}</div>
                        <div style="font-size:11px;color:var(--muted)">@{{ $u->username }} · {{ $u->created_at->diffForHumans() }}</div>
                    </div>
                    @if($u->is_suspended)<span class="badge badge-red">Suspended</span>@endif
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Recent Donasi --}}
    <div class="card" style="margin-top:20px">
        <div class="card-header">
            <div><p class="card-title">Donasi Terbaru</p></div>
            <a href="{{ route('admin.donasi.index') }}" class="btn btn-ghost btn-sm">Semua <i class="bi bi-arrow-right"></i></a>
        </div>
        <table class="table">
            <thead><tr><th>Donor</th><th>Streamer</th><th>Nominal</th><th>Metode</th><th>Waktu</th></tr></thead>
            <tbody>
            @forelse($recentDonasi as $d)
            <tr>
                <td><div style="font-weight:700">{{ $d->donor_nama }}</div><div style="font-size:11px;color:var(--muted)">{{ Str::limit($d->pesan,40) }}</div></td>
                <td>{{ $d->user->nama ?? '-' }}</td>
                <td style="font-weight:700;color:var(--green)">{{ $d->jumlahFormat }}</td>
                <td><span class="badge badge-muted">{{ $d->payment_method }}</span></td>
                <td style="color:var(--muted)">{{ $d->paid_at?->diffForHumans() }}</td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center;color:var(--muted);padding:30px">Belum ada donasi</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
