<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page-title', 'General Affair') - Sahabat Sawit</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --pink: #9D2459;
            --pink-light: #FBEAF1;
            --rose-dark: #7A1944;
            --rose-mid: #9D2459;
            --ink: #2D2327;
            --muted: #7A6E73;
            --line: #E8D7DF;
            --bg: #FDF9FA;
            --card: #FFFFFF;
            --red: #C93B42;
            --red-light: #FBE7E8;
            --green: #2B7A4B;
            --green-light: #E8F5EC;
            --yellow: #9C6A00;
            --yellow-light: #FEF6E7;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--ink); display: flex; min-height: 100vh; }

        /* Sidebar */
        .sidebar { width: 260px; background: var(--rose-dark); color: #fff; display: flex; flex-direction: column; position: fixed; top: 0; bottom: 0; left: 0; z-index: 100; }
        .sidebar-brand { padding: 1.5rem 1.25rem; display: flex; align-items: center; gap: .75rem; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar-brand img { width: 32px; height: 32px; object-fit: contain; }
        .sidebar-brand h1 { font-size: .95rem; font-weight: 700; color: #fff; }
        .sidebar-brand p { font-size: .7rem; color: rgba(255,255,255,0.7); }

        .sidebar-nav { padding: 1rem 0.75rem; overflow-y: auto; flex: 1; }
        .nav-section { font-size: .65rem; text-transform: uppercase; letter-spacing: .08em; color: rgba(255,255,255,0.5); padding: .75rem .5rem .3rem; font-weight: 600; }
        .nav-item { display: flex; align-items: center; gap: .75rem; padding: .65rem .75rem; color: rgba(255,255,255,0.85); text-decoration: none; border-radius: 8px; font-size: .85rem; font-weight: 500; margin-bottom: .2rem; transition: all .2s; }
        .nav-item svg { width: 18px; height: 18px; opacity: 0.8; }
        .nav-item:hover { background: rgba(255,255,255,0.08); color: #fff; }
        .nav-item.active { background: rgba(255,255,255,0.15); color: #fff; font-weight: 600; }

        /* Main Wrapper */
        .main-wrapper { margin-left: 260px; flex: 1; display: flex; flex-direction: column; }
        .topbar { background: var(--card); border-bottom: 1px solid var(--line); padding: 1rem 2rem; display: flex; align-items: center; justify-content: space-between; }
        .topbar-title h1 { font-size: 1.15rem; font-weight: 700; color: var(--ink); }
        .topbar-title p { font-size: .8rem; color: var(--muted); }
        .topbar-user { display: flex; align-items: center; gap: .75rem; font-size: .85rem; font-weight: 600; }
        .user-avatar { width: 32px; height: 32px; background: var(--pink-light); color: var(--pink); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: .8rem; }

        .content { padding: 2rem; flex: 1; }

        /* Global Components */
        .btn { display: inline-flex; align-items: center; justify-content: center; gap: .5rem; padding: .6rem 1.1rem; border-radius: 8px; font-size: .85rem; font-weight: 600; text-decoration: none; cursor: pointer; border: none; transition: background .2s; }
        .btn-primary { background: var(--pink); color: #fff; }
        .btn-primary:hover { background: var(--rose-dark); }
        .btn-secondary { background: #fff; color: var(--ink); border: 1px solid var(--line); }
        .btn-secondary:hover { background: var(--bg); border-color: var(--muted); }

        .alert-success { background: var(--green-light); border: 1px solid #C3E6CB; color: var(--green); border-radius: 10px; padding: .85rem 1.25rem; font-size: .85rem; margin-bottom: 1.5rem; }
        
        /* Status pill tambahan (Disetujui) */
        .status-pill.info { background: #FBEAF1; color: var(--rose-mid); }

        /* Pesan error */
        .alert-error {
            background: #FBE7E8; border: 1px solid #F0C4C6; color: var(--red);
            border-radius: 10px; padding: .85rem 1.25rem; font-size: .85rem; margin-bottom: 1.5rem;
        }

        /* Pagination */
        .go-pager { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: .75rem; padding: 1.1rem 1.5rem; border-top: 1px solid var(--line); }
        .go-pager-info { font-size: .8rem; color: var(--muted); }
        .go-pager ul { display: flex; gap: .3rem; list-style: none; margin: 0; padding: 0; }
        .go-pager li a, .go-pager li span {
            display: inline-flex; align-items: center; justify-content: center;
            min-width: 34px; height: 34px; padding: 0 .5rem;
            border: 1px solid var(--line); border-radius: 8px;
            font-size: .82rem; color: var(--ink); background: #fff;
        }
        .go-pager li a:hover { background: #FBEAF1; border-color: var(--pink-light); }
        .go-pager li.active span { background: var(--pink); border-color: var(--pink); color: #fff; font-weight: 600; }
        .go-pager li.disabled span { color: var(--muted); background: #FFF7FA; }

        /* Dashboard specific widgets */
        .summary-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
        .summary-item { background: var(--card); border: 1px solid var(--line); border-radius: 12px; padding: 1.25rem; }
        .summary-item .label { font-size: .8rem; color: var(--muted); margin-bottom: .4rem; font-weight: 500; }
        .summary-item .value { font-size: 1.5rem; font-weight: 700; color: var(--ink); }
        .summary-item .value.attention { color: var(--red); }

        .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem; }
        @media(max-width: 1024px) { .two-col { grid-template-columns: 1fr; } }

        .panel { background: var(--card); border: 1px solid var(--line); border-radius: 12px; overflow: hidden; display: flex; flex-direction: column; }
        .panel-head { padding: 1rem 1.25rem; border-bottom: 1px solid var(--line); display: flex; align-items: center; justify-content: space-between; }
        .panel-head h2 { font-size: .95rem; font-weight: 700; color: var(--ink); }
        .panel-link { font-size: .8rem; color: var(--pink); text-decoration: none; font-weight: 600; }
        .panel-link:hover { text-decoration: underline; }
        .panel-body { padding: 1.25rem; }

        .data-table { width: 100%; border-collapse: collapse; font-size: .85rem; }
        .data-table th { text-align: left; padding: .75rem 1rem; background: var(--bg); color: var(--muted); font-weight: 600; border-bottom: 1px solid var(--line); }
        .data-table td { padding: .75rem 1rem; border-bottom: 1px solid var(--line); color: var(--ink); }
        .data-table tr:last-child td { border-bottom: none; }

        .status-pill { display: inline-block; padding: .25rem .6rem; border-radius: 20px; font-size: .75rem; font-weight: 600; }
        .status-pill.success { background: var(--green-light); color: var(--green); }
        .status-pill.warning { background: var(--yellow-light); color: var(--yellow); }
        .status-pill.danger { background: var(--red-light); color: var(--red); }

        .empty-note { padding: 2rem; text-align: center; color: var(--muted); font-size: .85rem; }

        @yield('extra-style')
    </style>
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <img src="{{ asset('images/logo-sawit.png') }}" alt="Logo" onerror="this.style.display='none'">
            <div>
                <h1>Sahabat Sawit</h1>
                <p>Panel General Affair</p>
            </div>
        </div>
        <nav class="sidebar-nav">
            <a href="{{ route('go.dashboard') }}" class="nav-item {{ request()->routeIs('go.dashboard') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                Dashboard
            </a>

            <div class="nav-section">Data</div>
            <a href="{{ route('go.assets.index') }}" class="nav-item {{ request()->routeIs('go.assets.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                Aset / Inventaris
            </a>

            <!-- 3 LINK ATK & PERMINTAAN BARU -->
            <a href="{{ route('go.atk-requests.index') }}" class="nav-item {{ request()->routeIs('go.atk-requests.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1"/><path d="M9 12h6M9 16h6"/></svg>
                Permintaan ATK
            </a>
            <a href="{{ route('go.atk-items.index') }}" class="nav-item {{ request()->routeIs('go.atk-items.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                Barang ATK
            </a>
            <a href="{{ route('go.atk-stock-ins.index') }}" class="nav-item {{ request()->routeIs('go.atk-stock-ins.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="M7 10l5 5 5-5"/><path d="M12 15V3"/></svg>
                Barang Masuk
            </a>

            <a href="#" class="nav-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>
                Fasilitas
            </a>
            <a href="#" class="nav-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                Surat-Menyurat
            </a>

            <div class="nav-section">Lainnya</div>
            <a href="#" class="nav-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2_2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                Pengaturan
            </a>
        </nav>
    </aside>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <header class="topbar">
            <div class="topbar-title">
                <h1>@yield('page-title')</h1>
                <p>@yield('page-subtitle')</p>
            </div>
            <div class="topbar-user">
                <div class="user-avatar">{{ substr(Auth::user()->name ?? 'A', 0, 1) }}</div>
                <span>{{ Auth::user()->name ?? 'Administrator' }}</span>
            </div>
        </header>

        <div class="content">
            @if (session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert-error">{{ session('error') }}</div>
            @endif

            @yield('content')
        </div>
    </div>

</body>
</html>