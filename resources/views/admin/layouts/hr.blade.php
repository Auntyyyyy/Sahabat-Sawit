<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page-title', 'Dashboard HR') - PT. Sahabat Sawit Rokan Sejahtera</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --navy: #0F2A4A;
            --navy-soft: #16385E;
            --blue: #1E5B94;
            --blue-light: #4FA3D1;
            --bg: #F5F8FB;
            --ink: #1F2933;
            --muted: #6B7A8D;
            --line: #DCE4EC;
            --amber: #E0A458;
            --green: #1F7A4D;
            --red: #B33A3A;
            --card: #FFFFFF;
        }

        * { box-sizing: border-box; }

        html, body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            color: var(--ink);
            background: var(--bg);
        }

        h1, h2, h3 { font-family: 'Poppins', sans-serif; margin: 0; }
        a { text-decoration: none; }

        .app-shell { display: flex; min-height: 100vh; }

        /* ================= SIDEBAR ================= */
        .sidebar {
            width: 250px;
            flex-shrink: 0;
            background: var(--navy);
            color: #E7EEF6;
            display: flex;
            flex-direction: column;
            padding: 1.75rem 1.25rem;
        }

        .sidebar .brand { display: flex; align-items: center; gap: .65rem; margin-bottom: 2.5rem; padding: 0 .25rem; }
        .sidebar .brand img { height: 36px; width: auto; }
        .sidebar .brand-text { display: flex; flex-direction: column; line-height: 1.2; }
        .sidebar .brand-text strong { font-family: 'Poppins', sans-serif; font-size: .88rem; color: #fff; }
        .sidebar .brand-text span { font-size: .68rem; color: #9FB4CC; }

        .sidebar nav { display: flex; flex-direction: column; gap: .25rem; }
        .sidebar .nav-label { font-size: .7rem; color: #6E88A6; margin: 1.1rem .6rem .4rem; }

        .sidebar a.nav-item {
            display: flex; align-items: center; gap: .7rem;
            padding: .62rem .75rem; border-radius: 8px;
            color: #C9D8E8; font-size: .88rem; font-weight: 500;
            transition: background .15s, color .15s;
        }
        .sidebar a.nav-item svg { width: 18px; height: 18px; flex-shrink: 0; }
        .sidebar a.nav-item:hover { background: var(--navy-soft); color: #fff; }
        .sidebar a.nav-item.active { background: var(--blue); color: #fff; }

        .sidebar .sidebar-footer { margin-top: auto; padding-top: 1.25rem; border-top: 1px solid rgba(255,255,255,.08); }
        .sidebar form button {
            width: 100%; display: flex; align-items: center; gap: .7rem;
            padding: .62rem .75rem; border-radius: 8px; background: transparent; border: none;
            color: #C9D8E8; font-family: 'Inter', sans-serif; font-size: .88rem; font-weight: 500;
            cursor: pointer; text-align: left;
        }
        .sidebar form button:hover { background: var(--navy-soft); color: #fff; }
        .sidebar form button svg { width: 18px; height: 18px; }

        /* ================= MAIN ================= */
        .main { flex: 1; min-width: 0; }

        .topbar {
            background: var(--card); border-bottom: 1px solid var(--line);
            padding: 1.25rem 2rem; display: flex; align-items: center; justify-content: space-between;
        }
        .topbar h1 { font-size: 1.3rem; color: var(--navy); }
        .topbar .topbar-sub { font-size: .85rem; color: var(--muted); margin-top: .2rem; }
        .topbar .user-chip { display: flex; align-items: center; gap: .6rem; font-size: .85rem; color: var(--ink); }
        .topbar .user-chip .avatar {
            width: 36px; height: 36px; border-radius: 50%; background: var(--blue-light); color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Poppins', sans-serif; font-weight: 700; font-size: .85rem;
        }

        .content { padding: 2rem; }

        .menu-toggle { display: none; background: none; border: none; padding: .4rem; cursor: pointer; color: var(--navy); }
        .menu-toggle svg { width: 24px; height: 24px; }

        .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(15, 42, 74, .45); z-index: 15; }

        /* ================= RINGKASAN ================= */
        .summary-row {
            display: grid; grid-template-columns: repeat(4, 1fr);
            border: 1px solid var(--line); border-radius: 12px; background: var(--card);
            overflow: hidden; margin-bottom: 1.75rem;
        }
        .summary-item { padding: 1.4rem 1.6rem; border-right: 1px solid var(--line); }
        .summary-item:last-child { border-right: none; }
        .summary-item .label { font-size: .78rem; color: var(--muted); margin-bottom: .5rem; }
        .summary-item .value { font-family: 'Poppins', sans-serif; font-size: 1.65rem; font-weight: 700; color: var(--navy); }
        .summary-item .value.attention { color: var(--amber); }
        .summary-item .delta { font-size: .78rem; color: var(--blue); margin-top: .35rem; }

        /* ================= PANEL ================= */
        .panel { background: var(--card); border: 1px solid var(--line); border-radius: 12px; margin-bottom: 1.75rem; }
        .panel-head { display: flex; align-items: center; justify-content: space-between; padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--line); }
        .panel-head h2 { font-size: 1.02rem; color: var(--navy); }
        .panel-head .panel-link { font-size: .82rem; color: var(--blue); font-weight: 600; }
        .panel-body { padding: 1.25rem 1.5rem; }

        .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }

        /* Bar chart sederhana */
        .bar-row { margin-bottom: 1rem; }
        .bar-row:last-child { margin-bottom: 0; }
        .bar-row .bar-label { display: flex; justify-content: space-between; font-size: .82rem; margin-bottom: .35rem; }
        .bar-row .bar-label span:first-child { color: var(--ink); font-weight: 500; }
        .bar-row .bar-label span:last-child { color: var(--muted); }
        .bar-track { height: 8px; background: #EEF3F8; border-radius: 4px; overflow: hidden; }
        .bar-fill { height: 100%; background: var(--blue); border-radius: 4px; }
        .bar-fill.green { background: var(--green); }
        .bar-fill.amber { background: var(--amber); }
        .bar-fill.red { background: var(--red); }
        .bar-fill.light { background: var(--blue-light); }

        /* ================= TABEL ================= */
        table.data-table { width: 100%; border-collapse: collapse; }
        table.data-table th {
            text-align: left; font-size: .74rem; color: var(--muted); font-weight: 600;
            padding: .8rem 1.5rem; border-bottom: 1px solid var(--line);
        }
        table.data-table td { padding: .85rem 1.5rem; font-size: .88rem; border-bottom: 1px solid var(--line); }
        table.data-table tr:last-child td { border-bottom: none; }
        table.data-table .row-actions { display: flex; gap: .6rem; }
        table.data-table .row-actions a, table.data-table .row-actions button {
            font-size: .8rem; font-weight: 600; border: none; background: none; cursor: pointer; padding: 0;
        }
        .action-view { color: var(--blue); }
        .action-edit { color: var(--amber); }
        .action-delete { color: var(--red); }

        .status-pill { display: inline-block; padding: .25rem .65rem; border-radius: 6px; font-size: .76rem; font-weight: 600; }
        .status-pill.aktif { background: #E4F1EA; color: var(--green); }
        .status-pill.cuti { background: #FDF1DF; color: #B9791F; }
        .status-pill.nonaktif { background: #FBE7E8; color: var(--red); }

        .empty-note { padding: 2rem 1.5rem; text-align: center; color: var(--muted); font-size: .88rem; }

        /* ================= FILTER & FORM ================= */
        .filter-bar { display: flex; gap: .75rem; padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--line); flex-wrap: wrap; }
        .filter-bar input, .filter-bar select {
            padding: .55rem .8rem; border: 1px solid var(--line); border-radius: 8px;
            font-size: .85rem; font-family: 'Inter', sans-serif; background: #fff;
        }
        .filter-bar input { flex: 1; min-width: 180px; }

        .btn {
            display: inline-flex; align-items: center; gap: .5rem;
            padding: .6rem 1.1rem; border-radius: 8px; font-size: .85rem; font-weight: 600;
            border: none; cursor: pointer; font-family: 'Inter', sans-serif;
        }
        .btn-primary { background: var(--blue); color: #fff; }
        .btn-primary:hover { background: var(--navy); }
        .btn-secondary { background: #fff; color: var(--ink); border: 1px solid var(--line); }
        .btn-secondary:hover { background: #F3F6F9; }

        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.1rem; padding: 1.5rem; }
        .form-grid .full { grid-column: 1 / -1; }
        .form-field label { display: block; font-size: .82rem; font-weight: 600; color: var(--navy); margin-bottom: .4rem; }
        .form-field input, .form-field select, .form-field textarea {
            width: 100%; padding: .62rem .85rem; border: 1px solid var(--line); border-radius: 8px;
            font-size: .88rem; font-family: 'Inter', sans-serif; background: #fff;
        }
        .form-field input:focus, .form-field select:focus, .form-field textarea:focus {
            outline: none; border-color: var(--blue); box-shadow: 0 0 0 3px rgba(30,91,148,.12);
        }
        .form-field .error { color: var(--red); font-size: .78rem; margin-top: .35rem; }
        .form-actions { display: flex; gap: .75rem; padding: 0 1.5rem 1.5rem; }

        .alert-success {
            background: #E4F1EA; border: 1px solid #BFE3CD; color: var(--green);
            border-radius: 10px; padding: .85rem 1.25rem; font-size: .85rem; margin-bottom: 1.5rem;
        }

        .detail-grid { display: grid; grid-template-columns: 160px 1fr; row-gap: .9rem; padding: 1.5rem; font-size: .9rem; }
        .detail-grid dt { color: var(--muted); }
        .detail-grid dd { margin: 0; font-weight: 500; }

        @media (max-width: 960px) {
            .menu-toggle { display: inline-flex; }
            .sidebar { position: fixed; inset: 0 auto 0 0; z-index: 20; transform: translateX(-100%); transition: transform .25s ease; }
            .sidebar.open { transform: translateX(0); }
            .sidebar-overlay.open { display: block; }
            .summary-row { grid-template-columns: repeat(2, 1fr); }
            .summary-item:nth-child(2) { border-right: none; }
            .two-col { grid-template-columns: 1fr; }
            .form-grid { grid-template-columns: 1fr; }
        }

        @yield('extra-style')
    </style>
</head>
<body>

    <div class="app-shell">
        <aside class="sidebar">
            <div class="brand">
                <img src="{{ asset('images/logoaja.png') }}" alt="Logo">
                <div class="brand-text">
                    <strong>Sahabat Sawit</strong>
                    <span>Panel HR</span>
                </div>
            </div>

            <nav>
                <a href="{{ route('hr.dashboard') }}" class="nav-item {{ request()->routeIs('hr.dashboard') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/></svg>
                    Dashboard
                </a>

                <p class="nav-label">DATA</p>
                <a href="{{ route('hr.karyawan.index') }}" class="nav-item {{ request()->routeIs('hr.karyawan.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    Data Karyawan
                </a>
                <a href="#" class="nav-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                    Absensi
                </a>
                <a href="#" class="nav-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                    Cuti &amp; Izin
                </a>

                <p class="nav-label">LAINNYA</p>
                <a href="#" class="nav-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                    Pengaturan
                </a>
            </nav>

            <div class="sidebar-footer">
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg>
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        <div class="main">
            <div class="topbar">
                <div style="display:flex; align-items:center; gap:.9rem;">
                    <button id="menuToggle" class="menu-toggle" aria-label="Buka menu" aria-expanded="false">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
                    </button>
                    <div>
                        <h1>@yield('page-title', 'Dashboard HR')</h1>
                        <p class="topbar-sub">@yield('page-subtitle', now()->translatedFormat('l, d F Y'))</p>
                    </div>
                </div>
                <div class="user-chip">
                    <div class="avatar">{{ strtoupper(substr(auth()->user()->name ?? 'H', 0, 1)) }}</div>
                    <span>{{ auth()->user()->name ?? 'Staff HR' }}</span>
                </div>
            </div>

            <div class="content">
                @if (session('success'))
                    <div class="alert-success">{{ session('success') }}</div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <div id="sidebarOverlay" class="sidebar-overlay"></div>

    <script>
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const toggleBtn = document.getElementById('menuToggle');

        function closeSidebar() {
            sidebar.classList.remove('open');
            overlay.classList.remove('open');
            toggleBtn.setAttribute('aria-expanded', 'false');
        }

        function openSidebar() {
            sidebar.classList.add('open');
            overlay.classList.add('open');
            toggleBtn.setAttribute('aria-expanded', 'true');
        }

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
        });

        overlay.addEventListener('click', closeSidebar);
    </script>

    @yield('extra-script')
</body>
</html>