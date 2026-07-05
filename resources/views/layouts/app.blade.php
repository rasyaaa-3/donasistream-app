<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DonasiStream — @yield('title')</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --sky:       #4DC8F0;
            --sky-dim:   rgba(77,200,240,0.12);
            --sky-glow:  rgba(77,200,240,0.25);
            --dark:      #0d1117;
            --dark2:     #161b27;
            --dark3:     #1e2540;
            --card:      #1a2035;
            --card2:     #222840;
            --border:    rgba(255,255,255,0.06);
            --text:      #e2e8f4;
            --muted:     #6b7db3;
            --muted2:    #8a94b0;
            --sidebar-w: 240px;
            --header-h:  64px;
        }
        html, body { height: 100%; font-family: 'Plus Jakarta Sans', sans-serif; background: var(--dark); color: var(--text); }
        a { text-decoration: none; color: inherit; }
        .app-shell { display: flex; min-height: 100vh; }
        .sidebar { width: var(--sidebar-w); background: var(--dark2); border-right: 1px solid var(--border); display: flex; flex-direction: column; position: fixed; top: 0; left: 0; height: 100vh; z-index: 100; }
        .sidebar-logo { padding: 24px 20px; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid var(--border); }
        .sidebar-logo .logo-icon { width: 36px; height: 36px; border-radius: 10px; background: var(--sky); display: flex; align-items: center; justify-content: center; color: var(--dark); font-size: 18px; }
        .sidebar-logo .logo-text { font-size: 16px; font-weight: 800; color: white; letter-spacing: -0.4px; }
        .sidebar-logo .logo-text span { color: var(--sky); }
        .sidebar-nav { padding: 16px 12px; flex: 1; display: flex; flex-direction: column; gap: 4px; overflow-y: auto; }
        .nav-section-label { font-size: 10px; font-weight: 700; color: var(--muted); letter-spacing: 1.2px; text-transform: uppercase; padding: 12px 8px 6px; }
        .nav-link { display: flex; align-items: center; gap: 12px; padding: 10px 12px; border-radius: 10px; font-size: 14px; font-weight: 600; color: var(--muted2); transition: all 0.2s; cursor: pointer; }
        .nav-link i { font-size: 18px; }
        .nav-link:hover { background: rgba(255,255,255,0.05); color: var(--text); }
        .nav-link.active { background: var(--sky-dim); color: var(--sky); }
        .sidebar-footer { padding: 16px 12px; border-top: 1px solid var(--border); }
        .user-chip { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 10px; background: var(--dark3); border: 1px solid var(--border); }
        .user-chip .avatar { width: 34px; height: 34px; border-radius: 50%; background: var(--sky); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 12px; color: var(--dark); flex-shrink: 0; }
        .user-chip .user-meta { flex: 1; overflow: hidden; }
        .user-chip .user-name { font-size: 13px; font-weight: 700; color: white; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .user-chip .user-role { font-size: 11px; color: var(--muted); }
        .user-chip .logout-btn { color: var(--muted); font-size: 16px; cursor: pointer; transition: color 0.2s; background: none; border: none; }
        .user-chip .logout-btn:hover { color: #ef4444; }
        .main-area { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-height: 100vh; }
        .topbar { height: var(--header-h); background: var(--dark2); border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; padding: 0 32px; position: sticky; top: 0; z-index: 50; }
        .topbar-left .page-title { font-size: 18px; font-weight: 800; color: white; letter-spacing: -0.4px; }
        .topbar-left .page-sub { font-size: 12px; color: var(--muted); margin-top: 1px; }
        .topbar-right { display: flex; align-items: center; gap: 12px; }
        .topbar-badge { display: flex; align-items: center; gap: 6px; background: var(--sky-dim); border: 1px solid rgba(77,200,240,0.2); border-radius: 8px; padding: 6px 12px; font-size: 12px; font-weight: 700; color: var(--sky); }
        .page-content { padding: 32px; flex: 1; }
        .card { background: var(--card); border-radius: 16px; border: 1px solid var(--border); overflow: hidden; }
        .card-header { padding: 18px 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
        .card-title { font-size: 14px; font-weight: 700; color: white; }
        .card-sub { font-size: 12px; color: var(--muted); margin-top: 2px; }
        .card-body { padding: 20px 24px; }
        .stat-card { background: var(--card); border: 1px solid var(--border); border-radius: 16px; padding: 22px 24px; position: relative; overflow: hidden; transition: border-color 0.2s, transform 0.2s; }
        .stat-card:hover { border-color: rgba(77,200,240,0.25); transform: translateY(-2px); }
        .stat-card::before { content: ''; position: absolute; top: 0; right: 0; width: 80px; height: 80px; background: radial-gradient(circle, var(--sky-dim) 0%, transparent 70%); border-radius: 50%; transform: translate(20px, -20px); }
        .stat-label { font-size: 12px; font-weight: 600; color: var(--muted); letter-spacing: 0.3px; }
        .stat-value { font-size: 26px; font-weight: 800; color: white; margin-top: 6px; letter-spacing: -1px; }
        .stat-icon { position: absolute; top: 20px; right: 22px; font-size: 22px; color: var(--sky); opacity: 0.7; }
        .stat-change { margin-top: 8px; font-size: 11px; font-weight: 700; color: #4ade80; display: flex; align-items: center; gap: 4px; }
        .grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .grid-auto { display: grid; grid-template-columns: 2fr 1fr; gap: 24px; }
        .form-group { margin-bottom: 18px; }
        .form-label { display: block; font-size: 12px; font-weight: 700; color: var(--muted2); margin-bottom: 7px; letter-spacing: 0.3px; }
        .form-control { width: 100%; background: var(--dark3); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 11px 14px; font-size: 14px; font-weight: 500; color: white; outline: none; transition: border-color 0.2s, box-shadow 0.2s; font-family: 'Plus Jakarta Sans', sans-serif; }
        .form-control:focus { border-color: var(--sky); box-shadow: 0 0 0 3px var(--sky-dim); }
        textarea.form-control { min-height: 100px; resize: vertical; }
        .form-control.is-invalid { border-color: #ef4444; }
        .invalid-feedback { font-size: 11px; color: #ef4444; margin-top: 5px; display: block; }
        .btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; border-radius: 10px; font-size: 14px; font-weight: 700; border: none; cursor: pointer; transition: all 0.2s; font-family: 'Plus Jakarta Sans', sans-serif; text-decoration: none; }
        .btn-primary { background: var(--sky); color: var(--dark); box-shadow: 0 4px 16px var(--sky-glow); }
        .btn-primary:hover { background: #38b0d8; transform: translateY(-1px); }
        .btn-ghost { background: rgba(255,255,255,0.05); color: var(--muted2); border: 1px solid var(--border); }
        .btn-ghost:hover { background: rgba(255,255,255,0.1); color: white; }
        .btn-danger { background: rgba(239,68,68,0.12); color: #ef4444; border: 1px solid rgba(239,68,68,0.2); }
        .btn-danger:hover { background: rgba(239,68,68,0.2); }
        .alert { padding: 12px 16px; border-radius: 10px; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px; margin-bottom: 20px; }
        .alert-success { background: rgba(74,222,128,0.1); border: 1px solid rgba(74,222,128,0.2); color: #4ade80; }
        .alert-danger { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2); color: #ef4444; }
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--dark3); border-radius: 4px; }
        </style>
        @yield('styles')
</head>
<body>
<div class="app-shell">
    <aside class="sidebar">
        <div class="sidebar-logo">
            <div class="logo-icon"><i class="bi bi-broadcast"></i></div>
            <span class="logo-text">Qeqink<span>.id</span></span>
        </div>
        <nav class="sidebar-nav">
            <span class="nav-section-label">Menu</span>
            <a href="{{ route('dashboard') }}" class="nav-link @yield('nav_dashboard')">
                <i class="bi bi-house-fill"></i> Dashboard
            </a>
            <a href="{{ route('donate.show') }}" class="nav-link @yield('nav_donate')">
                <i class="bi bi-heart-fill"></i> Donate
            </a>
            <a href="{{ route('transaksi.index') }}" class="nav-link @yield('nav_transaksi')">
                <i class="bi bi-currency-dollar"></i> Transaksi
            </a>
            <a href="{{ route('overlay.edit') }}" class="nav-link @yield('nav_overlay')">
                <i class="bi bi-display"></i> Overlay
            </a>
            <span class="nav-section-label" style="margin-top:8px">Akun</span>
            <a href="{{ route('profil.edit') }}" class="nav-link @yield('nav_profil')">
                <i class="bi bi-person-fill"></i> Profil
            </a>
        </nav>
        <div class="sidebar-footer">
            <div class="user-chip">
                <div class="avatar">{{ auth()->user()->initials }}</div>
                <div class="user-meta">
                    <p class="user-name">{{ auth()->user()->nama }}</p>
                    <p class="user-role">Streamer</p>
                </div>
                <form method="POST" action="{{ route('logout') }}" style="display:inline">
                    @csrf
                    <button type="submit" class="logout-btn" title="Logout">
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>
    <div class="main-area">
        @yield('content')
    </div>
</div>
@yield('scripts')
</body>
</html>
