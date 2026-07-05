@extends('layouts.app')

@section('title', 'Profil')

@section('nav_profil', 'active')

@section('styles')
<style>
    .profile-hero { background: var(--card); border: 1px solid var(--border); border-radius: 16px; padding: 28px 32px; margin-bottom: 24px; display: flex !important; flex-direction: row !important; align-items: center; gap: 20px; position: relative; overflow: hidden; }
    .profile-hero::before { content: ""; position: absolute; top: -40px; right: -40px; width: 200px; height: 200px; background: radial-gradient(circle, var(--sky-dim) 0%, transparent 65%); border-radius: 50%; }
    .big-avatar { width: 72px; height: 72px; border-radius: 50%; background: linear-gradient(135deg, var(--sky), #2ba8d4); display: flex; align-items: center; justify-content: center; font-size: 26px; font-weight: 800; color: var(--dark); border: 3px solid rgba(77,200,240,0.3); flex-shrink: 0; position: relative; z-index: 1; min-width: 72px; }
    .profile-meta { position: relative; z-index: 1; flex: 1; }
    .profile-meta h2 { font-size: 20px; font-weight: 800; color: white; letter-spacing: -0.5px; margin: 0 0 2px; }
    .profile-meta .at { font-size: 13px; color: var(--muted2); margin: 0 0 8px; }
    .profile-meta .tag { display: inline-flex; align-items: center; gap: 6px; background: var(--sky-dim); border: 1px solid rgba(77,200,240,0.15); border-radius: 6px; padding: 4px 10px; font-size: 11px; font-weight: 700; color: var(--sky); }
    .donate-link-block { margin-left: auto; text-align: right; position: relative; z-index: 1; }
    .donate-link-block .label { font-size: 11px; color: var(--muted); margin-bottom: 2px; }
    .donate-link-block .url { font-size: 13px; font-weight: 700; color: var(--sky); }
    .section-card { background: var(--card); border: 1px solid var(--border); border-radius: 16px; overflow: hidden; margin-bottom: 20px; }
    .section-head { padding: 18px 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 10px; }
    .section-head h3 { font-size: 14px; font-weight: 700; color: white; }
    .section-head i { font-size: 16px; color: var(--sky); }
    .section-body { padding: 24px; }
    .input-with-icon { position: relative; }
    .input-with-icon .input-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); font-size: 16px; color: var(--muted); }
    .input-with-icon .form-control { padding-left: 42px; }
    .danger-zone { background: rgba(239,68,68,0.05); border: 1px solid rgba(239,68,68,0.15); border-radius: 16px; padding: 24px; }
    .danger-zone h3 { font-size: 14px; font-weight: 700; color: #ef4444; margin-bottom: 6px; }
    .danger-zone p { font-size: 13px; color: var(--muted2); margin-bottom: 16px; }
</style>
@endsection

@section('content')
<div class="topbar">
    <div class="topbar-left">
        <p class="page-title">Edit Profil</p>
        <p class="page-sub">Kelola informasi akun kamu</p>
    </div>
</div>

<div class="page-content" style="max-width:860px">
    @if(session('success'))
    <div class="alert alert-success"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
    @endif
    @if($errors->any())
    <div class="alert alert-danger"><i class="bi bi-exclamation-circle-fill"></i> {{ $errors->first() }}</div>
    @endif

    {{-- Profile Hero --}}
    <div class="profile-hero">
        <div class="big-avatar">{{ $user->initials }}</div>
        <div class="profile-meta">
            <h2>{{ $user->nama }}</h2>
            <p class="at"><span>@</span>{{ $user->username }}</p>
            <span class="tag"><i class="bi bi-broadcast"></i> Streamer Aktif</span>
        </div>
        <div class="donate-link-block">
            <p class="label">Link Donate</p>
            <p class="url">{{ $user->donate_url }}</p>
        </div>
    </div>

    <form method="POST" action="{{ route('profil.update') }}">
        @csrf
        <div class="grid-2" style="align-items:start; gap:24px">
            {{-- Left --}}
            <div>
                <div class="section-card">
                    <div class="section-head">
                        <i class="bi bi-person-fill"></i>
                        <h3>Informasi Dasar</h3>
                    </div>
                    <div class="section-body">
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                                value="{{ old('nama', $user->nama) }}" required>
                            @error('nama')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Username</label>
                            <div class="input-with-icon">
                                <span class="input-icon" style="font-weight:700; font-family:monospace">@</span>
                                <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                                    value="{{ old('username', $user->username) }}" required>
                            </div>
                            @error('username')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group" style="margin-bottom:0">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="{{ $user->email }}" readonly
                                style="opacity:0.6; cursor:not-allowed">
                            <p style="font-size:11px; color:var(--muted); margin-top:5px"><i class="bi bi-info-circle"></i> Email tidak dapat diubah</p>
                        </div>
                    </div>
                </div>

                <div class="section-card">
                    <div class="section-head">
                        <i class="bi bi-share-fill"></i>
                        <h3>Media Sosial</h3>
                    </div>
                    <div class="section-body">
                        <div class="form-group" style="margin-bottom:0">
                            <label class="form-label">Instagram</label>
                            <div class="input-with-icon">
                                <i class="input-icon bi bi-instagram"></i>
                                <input type="text" name="instagram" class="form-control"
                                    value="{{ old('instagram', $user->instagram) }}" placeholder="@username_instagram">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right --}}
            <div>
                <div class="section-card">
                    <div class="section-head">
                        <i class="bi bi-card-text"></i>
                        <h3>Bio & Deskripsi</h3>
                    </div>
                    <div class="section-body">
                        <div class="form-group" style="margin-bottom:0">
                            <label class="form-label">Bio</label>
                            <textarea name="bio" class="form-control" placeholder="Ceritakan tentang dirimu..."
                                style="min-height:120px">{{ old('bio', $user->bio) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="section-card">
                    <div class="section-head">
                        <i class="bi bi-shield-lock-fill"></i>
                        <h3>Ganti Password</h3>
                    </div>
                    <div class="section-body">
                        <div class="form-group">
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="new_password"
                                class="form-control @error('new_password') is-invalid @enderror"
                                placeholder="Kosongkan jika tidak ingin ubah">
                            @error('new_password')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group" style="margin-bottom:0">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" name="confirm_password" class="form-control" placeholder="••••••••">
                        </div>
                    </div>
                </div>

                <div style="display:flex; gap:12px; margin-top:4px">
                    <button type="submit" class="btn btn-primary" style="flex:1; justify-content:center; padding:13px">
                        <i class="bi bi-check-lg"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('dashboard') }}" class="btn btn-ghost" style="padding:13px 20px">Batal</a>
                </div>
            </div>
        </div>
    </form>

    {{-- Danger Zone --}}
    <div class="danger-zone" style="margin-top:24px">
        <h3><i class="bi bi-exclamation-triangle-fill"></i> Danger Zone</h3>
        <p>Aksi ini bersifat permanen dan tidak dapat dibatalkan.</p>
        <form method="POST" action="{{ route('logout') }}" style="display:inline">
            @csrf
            <button type="submit" class="btn btn-danger">
                <i class="bi bi-box-arrow-right"></i> Logout dari Akun
            </button>
        </form>
    </div>
</div>
@endsection
