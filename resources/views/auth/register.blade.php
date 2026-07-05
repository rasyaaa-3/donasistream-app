<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Qeqink.id — Register</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --sky: #4DC8F0; --sky-dim: rgba(77,200,240,0.12); --sky-glow: rgba(77,200,240,0.28);
            --dark: #0d1117; --dark2: #161b27; --dark3: #1e2540;
            --card: #1a2035; --border: rgba(255,255,255,0.07);
            --text: #e2e8f4; --muted: #6b7db3; --muted2: #8a94b0;
        }
        html, body { height: 100%; font-family: 'Plus Jakarta Sans', sans-serif; background: var(--dark); color: var(--text); }
        body { display: flex; align-items: stretch; min-height: 100vh; }

        .left-panel {
            width: 420px; flex-shrink: 0;
            background: var(--dark2); border-right: 1px solid var(--border);
            display: flex; flex-direction: column; justify-content: center;
            padding: 60px 48px; position: relative; overflow: hidden;
        }
        .left-panel::before {
            content: ''; position: absolute; bottom: -80px; left: -80px;
            width: 360px; height: 360px;
            background: radial-gradient(circle, rgba(77,200,240,0.1) 0%, transparent 65%);
            border-radius: 50%; pointer-events: none;
        }
        .left-panel::after {
            content: ''; position: absolute; top: -60px; right: -60px;
            width: 260px; height: 260px;
            background: radial-gradient(circle, rgba(77,200,240,0.06) 0%, transparent 65%);
            border-radius: 50%; pointer-events: none;
        }
        .brand-logo { display: flex; align-items: center; gap: 12px; margin-bottom: 52px; position: relative; z-index: 1; }
        .logo-icon { width: 44px; height: 44px; border-radius: 12px; background: var(--sky); display: flex; align-items: center; justify-content: center; font-size: 22px; color: var(--dark); box-shadow: 0 6px 20px var(--sky-glow); }
        .logo-text { font-size: 18px; font-weight: 800; color: white; letter-spacing: -0.5px; }
        .logo-text span { color: var(--sky); }
        .panel-headline { position: relative; z-index: 1; }
        .panel-headline h1 { font-size: 32px; font-weight: 800; color: white; line-height: 1.2; letter-spacing: -1px; margin-bottom: 6px; }
        .panel-headline h1 span { color: var(--sky); }
        .panel-headline p { font-size: 14px; color: var(--muted2); line-height: 1.6; margin-bottom: 40px; }
        .feature-list { list-style: none; display: flex; flex-direction: column; gap: 16px; position: relative; z-index: 1; }
        .feature-item { display: flex; align-items: center; gap: 14px; }
        .feature-icon { width: 36px; height: 36px; border-radius: 10px; background: var(--sky-dim); border: 1px solid rgba(77,200,240,0.15); display: flex; align-items: center; justify-content: center; font-size: 16px; color: var(--sky); flex-shrink: 0; }
        .feature-item span { font-size: 14px; font-weight: 600; color: var(--muted2); }

        .right-panel { flex: 1; display: flex; align-items: center; justify-content: center; padding: 40px 60px; }
        .auth-box { width: 100%; max-width: 460px; }

        .tab-bar { display: flex; background: var(--dark3); border-radius: 12px; padding: 4px; margin-bottom: 28px; border: 1px solid var(--border); }
        .tab-btn { flex: 1; padding: 10px; border-radius: 9px; border: none; background: transparent; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; font-weight: 700; color: var(--muted2); cursor: pointer; transition: all 0.2s; }
        .tab-btn.active { background: var(--sky); color: var(--dark); box-shadow: 0 2px 12px var(--sky-glow); }
        .tab-btn:not(.active):hover { color: var(--text); }

        .form-heading { margin-bottom: 24px; }
        .form-heading h2 { font-size: 22px; font-weight: 800; color: white; letter-spacing: -0.5px; }
        .form-heading p { font-size: 13px; color: var(--muted2); margin-top: 4px; }

        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        .form-group { margin-bottom: 14px; }
        .form-label { display: block; font-size: 12px; font-weight: 700; color: var(--muted2); margin-bottom: 7px; letter-spacing: 0.3px; }
        .input-wrap { position: relative; }
        .input-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); font-size: 16px; color: var(--muted); }
        .form-control { width: 100%; background: var(--dark3); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 12px 14px 12px 14px; font-size: 14px; font-weight: 500; color: white; outline: none; transition: border-color 0.2s, box-shadow 0.2s; font-family: inherit; }
        .form-control:focus { border-color: var(--sky); box-shadow: 0 0 0 3px var(--sky-dim); }
        .form-control.is-invalid { border-color: #ef4444; }
        .form-control::placeholder { color: var(--muted); }
        .invalid-feedback { font-size: 11px; color: #ef4444; margin-top: 5px; display: block; }

        .form-control.with-icon { padding-left: 42px; }

        .alert { padding: 12px 16px; border-radius: 10px; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px; margin-bottom: 20px; background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2); color: #ef4444; }

        .btn-submit { width: 100%; padding: 13px; border-radius: 10px; background: var(--sky); color: var(--dark); font-size: 15px; font-weight: 800; border: none; cursor: pointer; transition: all 0.2s; font-family: inherit; box-shadow: 0 4px 20px var(--sky-glow); display: flex; align-items: center; justify-content: center; gap: 8px; margin-top: 8px; }
        .btn-submit:hover { background: #38b0d8; transform: translateY(-1px); }

        @media (max-width: 768px) { .left-panel { display: none; } .right-panel { padding: 24px; } }
    </style>
</head>
<body>

<div class="left-panel">
    <div class="brand-logo">
        <div class="logo-icon"><i class="bi bi-broadcast"></i></div>
        <span class="logo-text">Qeqink<span>.id</span></span>
    </div>
    <div class="panel-headline">
        <h1>Platform Donasi<br>Untuk <span>Streamer</span></h1>
        <p>Kelola semua donasi live stream kamu dalam satu dashboard yang modern & Real-time.</p>
    </div>
    <ul class="feature-list">
        <li class="feature-item"><div class="feature-icon"><i class="bi bi-bell-fill"></i></div><span>Notifikasi donasi real-time</span></li>
        <li class="feature-item"><div class="feature-icon"><i class="bi bi-display"></i></div><span>Overlay OBS yang costumisable</span></li>
        <li class="feature-item"><div class="feature-icon"><i class="bi bi-bar-chart-fill"></i></div><span>Dashboard analytics lengkap</span></li>
        <li class="feature-item"><div class="feature-icon"><i class="bi bi-shield-check-fill"></i></div><span>Transaksi aman & terenkripsi</span></li>
    </ul>
</div>

<div class="right-panel">
    <div class="auth-box">
        <div class="tab-bar">
            <button class="tab-btn" onclick="window.location.href='{{ route('login') }}'">Login</button>
            <button class="tab-btn active" onclick="window.location.href='{{ route('register') }}'">Register</button>
        </div>

        <div class="form-heading">
            <h2>Buat Akun Baru</h2>
            <p>Daftar gratis dan mulai terima donasi</p>
        </div>

        @if ($errors->any())
        <div class="alert">
            <i class="bi bi-exclamation-circle-fill"></i>
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('register.post') }}">
            @csrf

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="nama">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama"
                        class="form-control @error('nama') is-invalid @enderror"
                        value="{{ old('nama') }}" placeholder="Nama Kamu" required autofocus>
                    @error('nama')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="username">Username</label>
                    <input type="text" id="username" name="username"
                        class="form-control @error('username') is-invalid @enderror"
                        value="{{ old('username') }}" placeholder="Username_unik" required>
                    @error('username')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <input type="email" id="email" name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}" placeholder="email@kamu.com" required>
                @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" id="password" name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="••••••••" required>
                    @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        class="form-control" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <i class="bi bi-person-plus-fill"></i> Buat Akun
            </button>
        </form>
    </div>
</div>

</body>
</html>
