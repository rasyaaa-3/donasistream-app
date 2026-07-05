<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — @yield('title')</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        :root{
            --red:#ef4444;--red-dim:rgba(239,68,68,0.12);
            --sky:#4DC8F0;--sky-dim:rgba(77,200,240,0.12);
            --green:#4ade80;--green-dim:rgba(74,222,128,0.12);
            --yellow:#f0d54d;--yellow-dim:rgba(240,213,77,0.12);
            --dark:#0d1117;--dark2:#161b27;--dark3:#1e2540;
            --card:#1a2035;--border:rgba(255,255,255,0.06);
            --text:#e2e8f4;--muted:#6b7db3;--muted2:#8a94b0;
            --sidebar-w:240px;
        }
        html,body{height:100%;font-family:'Plus Jakarta Sans',sans-serif;background:var(--dark);color:var(--text)}
        a{text-decoration:none;color:inherit}
        .app-shell{display:flex;min-height:100vh}
        .sidebar{width:var(--sidebar-w);background:var(--dark2);border-right:1px solid var(--border);display:flex;flex-direction:column;position:fixed;top:0;left:0;height:100vh;z-index:100}
        .sidebar-logo{padding:20px;display:flex;align-items:center;gap:10px;border-bottom:1px solid var(--border)}
        .logo-icon{width:34px;height:34px;border-radius:8px;background:var(--red);display:flex;align-items:center;justify-content:center;color:#fff;font-size:16px}
        .logo-text{font-size:15px;font-weight:800;color:white}
        .logo-text span{color:var(--red)}
        .admin-badge{font-size:9px;background:var(--red-dim);color:var(--red);border:1px solid rgba(239,68,68,0.2);border-radius:4px;padding:2px 6px;font-weight:700;margin-left:4px}
        .sidebar-nav{padding:12px;flex:1;display:flex;flex-direction:column;gap:2px;overflow-y:auto}
        .nav-section{font-size:10px;font-weight:700;color:var(--muted);letter-spacing:1.2px;text-transform:uppercase;padding:12px 8px 6px}
        .nav-link{display:flex;align-items:center;gap:10px;padding:9px 12px;border-radius:8px;font-size:13px;font-weight:600;color:var(--muted2);transition:all 0.2s}
        .nav-link i{font-size:16px}
        .nav-link:hover{background:rgba(255,255,255,0.05);color:var(--text)}
        .nav-link.active{background:var(--red-dim);color:var(--red)}
        .sidebar-footer{padding:12px;border-top:1px solid var(--border)}
        .user-chip{display:flex;align-items:center;gap:10px;padding:8px 10px;border-radius:8px;background:var(--dark3)}
        .avatar{width:32px;height:32px;border-radius:50%;background:var(--red);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;color:#fff;flex-shrink:0}
        .user-name{font-size:12px;font-weight:700;color:var(--text)}
        .user-role{font-size:10px;color:var(--red)}
        .logout-btn{background:none;border:none;color:var(--muted);cursor:pointer;font-size:15px;margin-left:auto;padding:4px}
        .logout-btn:hover{color:var(--red)}
        .main-area{margin-left:var(--sidebar-w);flex:1;display:flex;flex-direction:column;min-height:100vh}
        .topbar{padding:20px 28px;border-bottom:1px solid var(--border);background:var(--dark2)}
        .page-title{font-size:18px;font-weight:800;color:white}
        .page-sub{font-size:12px;color:var(--muted);margin-top:2px}
        .page-content{padding:24px 28px}
        .stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px}
        .stat-card{background:var(--card);border:1px solid var(--border);border-radius:12px;padding:18px 20px}
        .stat-label{font-size:11px;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:0.5px}
        .stat-value{font-size:24px;font-weight:800;color:white;margin-top:6px}
        .stat-sub{font-size:11px;color:var(--muted);margin-top:4px}
        .stat-icon{width:36px;height:36px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:18px;margin-bottom:10px}
        .card{background:var(--card);border:1px solid var(--border);border-radius:12px;overflow:hidden}
        .card-header{padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between}
        .card-title{font-size:14px;font-weight:700;color:white}
        .card-sub{font-size:11px;color:var(--muted);margin-top:2px}
        .card-body{padding:20px}
        .table{width:100%;border-collapse:collapse}
        .table th{font-size:10px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:0.5px;padding:10px 14px;border-bottom:1px solid var(--border);text-align:left}
        .table td{padding:12px 14px;border-bottom:1px solid var(--border);font-size:13px;color:var(--text)}
        .table tr:last-child td{border-bottom:none}
        .table tr:hover td{background:rgba(255,255,255,0.02)}
        .badge{display:inline-flex;align-items:center;padding:3px 8px;border-radius:5px;font-size:10px;font-weight:700}
        .badge-green{background:var(--green-dim);color:var(--green)}
        .badge-red{background:var(--red-dim);color:var(--red)}
        .badge-yellow{background:var(--yellow-dim);color:var(--yellow)}
        .badge-muted{background:rgba(255,255,255,0.05);color:var(--muted)}
        .btn{display:inline-flex;align-items:center;gap:6px;padding:8px 14px;border-radius:8px;font-size:12px;font-weight:700;border:none;cursor:pointer;font-family:inherit;transition:all 0.2s}
        .btn-primary{background:var(--red);color:#fff}
        .btn-primary:hover{background:#dc2626}
        .btn-ghost{background:transparent;border:1px solid var(--border);color:var(--muted2)}
        .btn-ghost:hover{border-color:rgba(255,255,255,0.15);color:var(--text)}
        .btn-sm{padding:5px 10px;font-size:11px}
        .btn-danger{background:var(--red-dim);color:var(--red);border:1px solid rgba(239,68,68,0.2)}
        .btn-success{background:var(--green-dim);color:var(--green);border:1px solid rgba(74,222,128,0.2)}
        .form-control{width:100%;background:var(--dark3);border:1px solid var(--border);border-radius:8px;padding:9px 12px;color:var(--text);font-size:13px;font-family:inherit}
        .form-control:focus{outline:none;border-color:rgba(239,68,68,0.4)}
        .form-label{font-size:12px;font-weight:700;color:var(--muted2);display:block;margin-bottom:6px}
        .form-group{margin-bottom:16px}
        .alert{padding:12px 16px;border-radius:8px;font-size:13px;margin-bottom:16px;display:flex;align-items:center;gap:10px}
        .alert-success{background:var(--green-dim);color:var(--green);border:1px solid rgba(74,222,128,0.2)}
        .alert-error{background:var(--red-dim);color:var(--red);border:1px solid rgba(239,68,68,0.2)}
        .search-bar{display:flex;gap:8px;margin-bottom:16px}
        .pagination{display:flex;gap:4px;justify-content:center;margin-top:20px}
        .pagination a,.pagination span{padding:6px 12px;border-radius:6px;font-size:12px;font-weight:600;border:1px solid var(--border);color:var(--muted2)}
        .pagination .active span{background:var(--red);color:#fff;border-color:var(--red)}
        .grid-2{display:grid;grid-template-columns:1fr 1fr;gap:20px}
        select.form-control option{background:var(--dark2)}
        .toggle-wrap{display:flex;align-items:center;gap:10px}
        .toggle{position:relative;width:40px;height:22px}
        .toggle input{opacity:0;width:0;height:0}
        .toggle-slider{position:absolute;cursor:pointer;inset:0;background:var(--dark3);border-radius:22px;border:1px solid var(--border);transition:.3s}
        .toggle-slider:before{content:'';position:absolute;width:16px;height:16px;left:2px;bottom:2px;background:var(--muted);border-radius:50%;transition:.3s}
        .toggle input:checked + .toggle-slider{background:var(--red-dim);border-color:var(--red)}
        .toggle input:checked + .toggle-slider:before{transform:translateX(18px);background:var(--red)}
    </style>
    @yield('styles')
</head>
<body>
<div class="app-shell">
    <aside class="sidebar">
        <div class="sidebar-logo">
            <div class="logo-icon"><i class="bi bi-shield-fill"></i></div>
            <span class="logo-text">Admin<span>Panel</span></span>
        </div>
        <nav class="sidebar-nav">
            <span class="nav-section">Panel</span>
            <a href="{{ route('admin.dashboard') }}" class="nav-link @yield('nav_dashboard')">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a href="{{ route('admin.users.index') }}" class="nav-link @yield('nav_users')">
                <i class="bi bi-people-fill"></i> Kelola User
            </a>
            <a href="{{ route('admin.donasi.index') }}" class="nav-link @yield('nav_donasi')">
                <i class="bi bi-currency-dollar"></i> Semua Donasi
            </a>
            <span class="nav-section">Sistem</span>
            <a href="{{ route('admin.settings.index') }}" class="nav-link @yield('nav_settings')">
                <i class="bi bi-gear-fill"></i> Pengaturan
            </a>

        </nav>
        <div class="sidebar-footer">
            <div class="user-chip">
                <div class="avatar">{{ auth()->user()->initials }}</div>
                <div style="flex:1;min-width:0">
                    <div class="user-name">{{ auth()->user()->nama }}</div>
                    <div class="user-role">Superadmin</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn"><i class="bi bi-box-arrow-right"></i></button>
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