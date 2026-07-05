@extends('layouts.admin')
@section('title','Pengaturan Sistem')
@section('nav_settings','active')

@section('content')
<div class="topbar"><p class="page-title">Pengaturan Sistem</p><p class="page-sub">Konfigurasi Duitku, fee, metode bayar, dan sistem</p></div>
<div class="page-content">
    @if(session('success'))<div class="alert alert-success"><i class="bi bi-check-circle-fill"></i>{{ session('success') }}</div>@endif

    <form method="POST" action="{{ route('admin.settings.update') }}">
    @csrf

    <div class="grid-2">
        {{-- Duitku --}}
        <div class="card">
            <div class="card-header"><p class="card-title"><i class="bi bi-credit-card-fill" style="color:var(--sky);margin-right:6px"></i>Duitku Payment Gateway</p></div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Merchant Code</label>
                    <input type="text" name="duitku_merchant_code" class="form-control" value="{{ $settings['duitku_merchant_code'] }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">API Key</label>
                    <input type="text" name="duitku_api_key" class="form-control" value="{{ $settings['duitku_api_key'] }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Environment</label>
                    <select name="duitku_env" class="form-control">
                        <option value="sandbox" {{ $settings['duitku_env']==='sandbox'?'selected':'' }}>Sandbox (Testing)</option>
                        <option value="production" {{ $settings['duitku_env']==='production'?'selected':'' }}>Production (Live)</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Fee & Sistem --}}
        <div class="card">
            <div class="card-header"><p class="card-title"><i class="bi bi-percent" style="color:var(--yellow);margin-right:6px"></i>Fee Platform & Sistem</p></div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Fee Platform (%)</label>
                    <input type="number" name="platform_fee_pct" class="form-control" value="{{ $settings['platform_fee_pct'] }}" min="0" max="20" step="0.1">
                    <p style="font-size:11px;color:var(--muted);margin-top:4px">0 = tidak ada fee. Contoh: 1 = ambil 1% dari tiap donasi</p>
                </div>
                <div class="form-group">
                    <label class="form-label">Pengumuman Global</label>
                    <textarea name="announcement" class="form-control" rows="3" placeholder="Kosongkan jika tidak ada pengumuman...">{{ $settings['announcement'] }}</textarea>
                    <p style="font-size:11px;color:var(--muted);margin-top:4px">Ditampilkan sebagai banner di dashboard semua user</p>
                </div>
                <div class="form-group" style="margin-bottom:0">
                    <label class="form-label">Mode Maintenance</label>
                    <div class="toggle-wrap">
                        <label class="toggle">
                            <input type="checkbox" name="maintenance_mode" {{ $settings['maintenance_mode']==='1'?'checked':'' }}>
                            <span class="toggle-slider"></span>
                        </label>
                        <span style="font-size:13px;color:var(--muted2)">Matikan akses publik sementara</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Metode Pembayaran --}}
    <div class="card" style="margin-top:20px">
        <div class="card-header"><p class="card-title"><i class="bi bi-wallet2" style="color:var(--green);margin-right:6px"></i>Metode Pembayaran & Minimal Nominal</p></div>
        <div class="card-body">
            <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:16px">
                @php
                $methods = [
                    'OV' => 'OVO',
                    'DA' => 'DANA',
                    'SA' => 'ShopeePay',
                    'SP' => 'QRIS',
                ];
                @endphp
                @foreach($methods as $code => $name)
                <div style="background:var(--dark3);border-radius:10px;padding:14px 16px;display:flex;align-items:center;gap:12px">
                    <div class="toggle-wrap" style="flex:1">
                        <label class="toggle">
                            <input type="checkbox" name="payment_methods[]" value="{{ $code }}" {{ in_array($code, $settings['payment_methods'] ?? []) ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </label>
                        <span style="font-size:13px;font-weight:700">{{ $name }}</span>
                    </div>
                    <div style="display:flex;align-items:center;gap:6px">
                        <span style="font-size:11px;color:var(--muted)">Min. Rp</span>
                        <input type="number" name="min_donate_{{ strtolower($code) }}" class="form-control" value="{{ $settings['min_donate_'.strtolower($code)] }}" style="width:90px;padding:6px 8px;font-size:12px">
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div style="margin-top:20px;display:flex;justify-content:flex-end">
        <button type="submit" class="btn btn-primary"><i class="bi bi-floppy-fill"></i> Simpan Semua Pengaturan</button>
    </div>
    </form>
</div>
@endsection
