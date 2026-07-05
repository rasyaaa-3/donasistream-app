@extends('layouts.app')

@section('title', 'Transaksi')

@section('nav_transaksi', 'active')

@section('styles')
<style>
    .tx-table { width: 100%; border-collapse: collapse; }
    .tx-table th { text-align: left; padding: 12px 16px; font-size: 11px; font-weight: 700; color: var(--muted); letter-spacing: 0.5px; text-transform: uppercase; border-bottom: 1px solid var(--border); }
    .tx-table td { padding: 14px 16px; border-bottom: 1px solid var(--border); font-size: 14px; color: var(--text); vertical-align: middle; }
    .tx-table tbody tr:last-child td { border-bottom: none; }
    .tx-table tbody tr:hover td { background: rgba(255,255,255,0.02); }
    .donor-cell { display: flex; align-items: center; gap: 12px; }
    .tx-avatar { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px; color: #1a1a2e; flex-shrink: 0; }
    .tx-name { font-weight: 700; color: white; }
    .tx-amount { font-weight: 800; color: var(--sky); }
    .tx-msg { font-size: 12px; color: var(--muted2); font-style: italic; max-width: 260px; }
    .filter-bar { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }
    .filter-btn { padding: 8px 16px; border-radius: 8px; border: 1px solid var(--border); background: transparent; color: var(--muted2); font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s; font-family: inherit; text-decoration: none; display: inline-block; }
    .filter-btn.active, .filter-btn:hover { background: var(--sky-dim); border-color: rgba(77,200,240,0.25); color: var(--sky); }
    .search-box { display: flex; }
    .search-input { flex: 1; background: var(--dark3); border: 1px solid var(--border); border-radius: 8px 0 0 8px; padding: 8px 14px; font-size: 13px; color: white; outline: none; font-family: inherit; }
    .search-input:focus { border-color: var(--sky); }
    .search-btn { background: var(--sky); border: none; padding: 8px 14px; border-radius: 0 8px 8px 0; cursor: pointer; color: var(--dark); font-size: 14px; }
    .empty-state { text-align: center; padding: 60px 20px; color: var(--muted); }
    .empty-state i { font-size: 48px; opacity: 0.3; display: block; margin-bottom: 12px; }
    .rank-badge { display: inline-flex; align-items: center; justify-content: center; width: 24px; height: 24px; border-radius: 6px; font-size: 11px; font-weight: 800; }
</style>
@endsection

@section('content')
<div class="topbar">
    <div class="topbar-left">
        <p class="page-title">Transaksi</p>
        <p class="page-sub">Riwayat seluruh donasi yang masuk</p>
    </div>
</div>

<div class="page-content">
    {{-- Stats --}}
    <div class="grid-4" style="margin-bottom:24px">
        <div class="stat-card">
            <i class="bi bi-wallet2 stat-icon"></i>
            <p class="stat-label">Total Masuk</p>
            <p class="stat-value">{{ \App\Helpers\Rupiah::format($totalMasuk) }}</p>
        </div>
        <div class="stat-card">
            <i class="bi bi-people stat-icon"></i>
            <p class="stat-label">Total Donatur</p>
            <p class="stat-value">{{ $totalOrang }} Orang</p>
        </div>
        <div class="stat-card">
            <i class="bi bi-trophy stat-icon"></i>
            <p class="stat-label">Donasi Tertinggi</p>
            <p class="stat-value">{{ \App\Helpers\Rupiah::format($tertinggi) }}</p>
        </div>
        <div class="stat-card">
            <i class="bi bi-bar-chart stat-icon"></i>
            <p class="stat-label">Rata-rata</p>
            <p class="stat-value">{{ \App\Helpers\Rupiah::format($rataRata) }}</p>
        </div>
    </div>

    {{-- Table --}}
    <div class="card">
        <div class="card-header" style="gap:16px; flex-wrap:wrap">
            <div>
                <p class="card-title">Riwayat Transaksi</p>
                <p class="card-sub">{{ $transaksi->count() }} transaksi ditemukan</p>
            </div>
            <div style="display:flex; gap:12px; align-items:center; flex-wrap:wrap; margin-left:auto">
                {{-- Filter --}}
                <div class="filter-bar">
                    @foreach(['semua' => 'Semua', 'hari_ini' => 'Hari Ini', 'bulan_ini' => 'Bulan Ini'] as $key => $label)
                    <a href="{{ route('transaksi.index', array_merge(['filter' => $key], $search ? ['q' => $search] : [])) }}"
                       class="filter-btn {{ $filter === $key ? 'active' : '' }}">
                        {{ $label }}
                    </a>
                    @endforeach
                </div>
                {{-- Search --}}
                <form method="GET" action="{{ route('transaksi.index') }}" class="search-box">
                    <input type="hidden" name="filter" value="{{ $filter }}">
                    <input type="text" name="q" class="search-input" placeholder="Cari donatur..." value="{{ $search }}" style="width:220px">
                    <button type="submit" class="search-btn"><i class="bi bi-search"></i></button>
                </form>
            </div>
        </div>

        @if($transaksi->isEmpty())
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <p>Belum ada transaksi ditemukan</p>
        </div>
        @else
        <div style="overflow-x:auto">
            <table class="tx-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Donatur</th>
                        <th>Pesan</th>
                        <th>Jumlah</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksi as $i => $tx)
                    <tr>
                        <td>
                            @if($i < 3)
                            @php $colors = ['#f0d54d','#8a94b0','#cd7f32']; @endphp
                            <span class="rank-badge"
                                style="background:{{ $colors[$i] }}22; color:{{ $colors[$i] }}">
                                {{ $i + 1 }}
                            </span>
                            @else
                            <span style="color:var(--muted); font-size:13px">{{ $i + 1 }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="donor-cell">
                                <div class="tx-avatar" style="background:{{ $tx->donor_color }}">
                                    {{ $tx->donor_initial }}
                                </div>
                                <p class="tx-name">{{ $tx->donor_nama }}</p>
                            </div>
                        </td>
                        <td>
                            <p class="tx-msg">"{{ $tx->pesan ?? '-' }}"</p>
                        </td>
                        <td class="tx-amount">{{ $tx->jumlahFormat }}</td>
                        <td>
                            <span style="font-size:12px; color:var(--muted2)">{{ $tx->waktu }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection
