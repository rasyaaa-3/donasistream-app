@extends('layouts.app')

@section('title', 'Dashboard')

@section('nav_dashboard', 'active')

@section('styles')
<style>
    .activity-dot { width: 8px; height: 8px; border-radius: 50%; background: #4ade80; flex-shrink: 0; box-shadow: 0 0 8px rgba(74,222,128,0.6); }
    .donor-row { display: flex; align-items: center; gap: 14px; padding: 14px 0; border-bottom: 1px solid var(--border); }
    .donor-row:last-child { border-bottom: none; padding-bottom: 0; }
    .donor-row:first-child { padding-top: 0; }
    .donor-avatar { width: 42px; height: 42px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px; color: #1a1a2e; flex-shrink: 0; }
    .donor-name { font-size: 14px; font-weight: 700; color: white; }
    .donor-time { font-size: 11px; color: var(--muted); margin-top: 2px; }
    .donor-amount { font-size: 14px; font-weight: 800; color: var(--sky); margin-left: auto; }
    .donor-msg { font-size: 12px; color: var(--muted2); margin-top: 4px; font-style: italic; }
    .link-box { display: flex; gap: 10px; align-items: center; }
    .link-display { flex: 1; background: var(--dark3); border: 1px solid var(--border); border-radius: 10px; padding: 11px 16px; font-size: 14px; color: var(--muted2); font-weight: 600; }
    .copy-btn { background: var(--sky); color: var(--dark); border: none; border-radius: 10px; padding: 11px 20px; font-weight: 700; font-size: 13px; cursor: pointer; white-space: nowrap; transition: all 0.2s; font-family: inherit; display: flex; align-items: center; gap: 6px; }
    .copy-btn:hover { background: #38b0d8; }
    .chart-bar-wrap { display: flex; flex-direction: column; gap: 10px; }
    .chart-bar-item { display: flex; align-items: center; gap: 12px; }
    .chart-bar-label { width: 90px; font-size: 12px; font-weight: 600; color: var(--muted2); text-align: right; flex-shrink: 0; }
    .chart-bar-track { flex: 1; height: 8px; background: rgba(255,255,255,0.06); border-radius: 4px; overflow: hidden; }
    .chart-bar-fill { height: 100%; border-radius: 4px; background: var(--sky); transition: width 0.8s ease; }
    .chart-bar-val { width: 70px; font-size: 12px; font-weight: 700; color: var(--sky); }
</style>
@endsection

@section('content')
{{-- Topbar --}}
<div class="topbar">
    <div class="topbar-left">
        <p class="page-title">Dashboard</p>
        <p class="page-sub">Ringkasan aktivitas donasi kamu</p>
    </div>
    <div class="topbar-right">
        <div class="topbar-badge">
            <div class="activity-dot"></div>
            Online
        </div>
        <div class="topbar-badge" style="background:var(--dark3); border-color:var(--border); color:var(--muted2)">
            <i class="bi bi-calendar3"></i>
            {{ now()->translatedFormat('d M Y') }}
        </div>
    </div>
</div>

<div class="page-content">
    {{-- Stats --}}
    <div class="grid-4" style="margin-bottom:24px">
        <div class="stat-card">
            <i class="bi bi-cash-stack stat-icon"></i>
            <p class="stat-label">Total Terkumpul</p>
            <p class="stat-value">{{ \App\Helpers\Rupiah::format($totalTerkumpul) }}</p>
            <p class="stat-change"><i class="bi bi-arrow-up-short"></i> Semua waktu</p>
        </div>
        <div class="stat-card">
            <i class="bi bi-people-fill stat-icon"></i>
            <p class="stat-label">Total Donatur</p>
            <p class="stat-value">{{ $totalDonatur }}</p>
            <p class="stat-change"><i class="bi bi-arrow-up-short"></i> Semua waktu</p>
        </div>
        <div class="stat-card">
            <i class="bi bi-calendar-heart stat-icon"></i>
            <p class="stat-label">Bulan Ini</p>
            <p class="stat-value">{{ \App\Helpers\Rupiah::format($bulanIni) }}</p>
            <p class="stat-change"><i class="bi bi-arrow-up-short"></i> Bulan berjalan</p>
        </div>
        <div class="stat-card">
            <i class="bi bi-star-fill stat-icon"></i>
            <p class="stat-label">Donasi Terbesar</p>
            <p class="stat-value">{{ \App\Helpers\Rupiah::format($terbesar) }}</p>
            <p class="stat-change"><i class="bi bi-arrow-up-short"></i> Record terkini</p>
        </div>
    </div>

    {{-- Main Grid --}}
    <div class="grid-auto">
        {{-- Recent Donations --}}
        <div class="card">
            <div class="card-header">
                <div>
                    <p class="card-title">Donasi Terbaru</p>
                    <p class="card-sub">5 transaksi terakhir</p>
                </div>
                <a href="{{ route('transaksi.index') }}" class="btn btn-ghost" style="font-size:12px; padding:7px 14px">
                    Lihat Semua <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="card-body">
                @forelse ($recentDonations as $d)
                <div class="donor-row">
                    <div class="donor-avatar" style="background:{{ $d->donor_color }}">
                        {{ $d->donor_initial }}
                    </div>
                    <div style="flex:1">
                        <p class="donor-name">{{ $d->donor_nama }}</p>
                        <p class="donor-time">{{ $d->waktu }}</p>
                        @if($d->pesan)
                        <p class="donor-msg">"{{ $d->pesan }}"</p>
                        @endif
                    </div>
                    <p class="donor-amount">{{ $d->jumlahFormat }}</p>
                </div>
                @empty
                <p style="color:var(--muted); text-align:center; padding:24px 0">Belum ada donasi</p>
                @endforelse
            </div>
        </div>

        {{-- Right Column --}}
        <div style="display:flex; flex-direction:column; gap:20px">
            {{-- Donation Link --}}
            <div class="card">
                <div class="card-header">
                    <div>
                        <p class="card-title">Link Donasi Kamu</p>
                        <p class="card-sub">Bagikan ke penonton</p>
                    </div>
                    <i class="bi bi-link-45deg" style="font-size:20px; color:var(--muted)"></i>
                </div>
                <div class="card-body">
                    <div class="link-box">
                        <div class="link-display" id="linkText">{{ $user->donate_url }}</div>
                        <button class="copy-btn" onclick="copyLink()">
                            <i class="bi bi-clipboard"></i> Salin
                        </button>
                    </div>
                </div>
            </div>

            {{-- Top Donors --}}
            <div class="card">
                <div class="card-header">
                    <div>
                        <p class="card-title">Top Donatur</p>
                        <p class="card-sub">Berdasarkan total</p>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-bar-wrap">
                        @foreach($topDonors as $td)
                        @php $pct = $maxDonor > 0 ? round(($td->total / $maxDonor) * 100) : 0; @endphp
                        <div class="chart-bar-item">
                            <span class="chart-bar-label">{{ $td->donor_nama }}</span>
                            <div class="chart-bar-track">
                                <div class="chart-bar-fill" style="width:{{ $pct }}%"></div>
                            </div>
                            <span class="chart-bar-val">{{ \App\Helpers\Rupiah::format($td->total) }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function copyLink() {
    const text = document.getElementById('linkText').textContent;
    navigator.clipboard.writeText(text).then(() => {
        const btn = document.querySelector('.copy-btn');
        const orig = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check-lg"></i> Tersalin!';
        btn.style.background = '#4ade80';
        setTimeout(() => { btn.innerHTML = orig; btn.style.background = ''; }, 2000);
    });
}
</script>
@endsection
