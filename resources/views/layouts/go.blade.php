<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page-title', 'Dashboard General Affair') - PT. Sahabat Sawit Rokan Sejahtera</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --rose-dark: #7A1F4B;
            --rose-mid: #9C2963;
            --pink: #D6478F;
            --pink-light: #F2A6C8;
            --bg: #FFF7FA;
            --ink: #3B2430;
            --muted: #9C7B8C;
            --line: #F0DCE6;
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
            background: var(--rose-dark);
            color: #F7E3EC;
            display: flex;
            flex-direction: column;
            padding: 1.75rem 1.25rem;
        }

        .sidebar .brand { display: flex; align-items: center; gap: .65rem; margin-bottom: 2.5rem; padding: 0 .25rem; }
        .sidebar .brand img { height: 36px; width: auto; }
        .sidebar .brand-text { display: flex; flex-direction: column; line-height: 1.2; }
        .sidebar .brand-text strong { font-family: 'Poppins', sans-serif; font-size: .88rem; color: #fff; }
        .sidebar .brand-text span { font-size: .68rem; color: #E5B8CE; }

        .sidebar nav { display: flex; flex-direction: column; gap: .25rem; }
        .sidebar .nav-label { font-size: .7rem; color: #C98DAE; margin: 1.1rem .6rem .4rem; }

        .sidebar a.nav-item {
            display: flex; align-items: center; gap: .7rem;
            padding: .62rem .75rem; border-radius: 8px;
            color: #EFCBDC; font-size: .88rem; font-weight: 500;
            transition: background .15s, color .15s;
        }
        .sidebar a.nav-item svg { width: 18px; height: 18px; flex-shrink: 0; }
        .sidebar a.nav-item:hover { background: var(--rose-mid); color: #fff; }
        .sidebar a.nav-item.active { background: var(--pink); color: #fff; }

        /* ================= SUB-MENU SIDEBAR ================= */
        .sidebar details.nav-group > summary {
            list-style: none; cursor: pointer;
            display: flex; align-items: center; gap: .7rem;
            padding: .62rem .75rem; border-radius: 8px;
            color: #EFCBDC; font-size: .88rem; font-weight: 500;
            transition: background .15s, color .15s;
        }
        .sidebar details.nav-group > summary::-webkit-details-marker { display: none; }
        .sidebar details.nav-group > summary .nav-icon { width: 18px; height: 18px; flex-shrink: 0; }
        .sidebar details.nav-group > summary .chevron { margin-left: auto; width: 14px; height: 14px; flex-shrink: 0; transition: transform .2s; }
        .sidebar details.nav-group[open] > summary .chevron { transform: rotate(180deg); }
        .sidebar details.nav-group > summary:hover { background: var(--rose-mid); color: #fff; }
        .sidebar details.nav-group.active > summary { background: rgba(255,255,255,.08); color: #fff; }

        .sidebar .nav-sub {
            display: flex; flex-direction: column; gap: .15rem;
            margin: .25rem 0 .35rem 1.3rem; padding-left: .7rem;
            border-left: 1px solid rgba(255,255,255,.16);
        }
        .sidebar .nav-sub a {
            display: block; padding: .5rem .75rem; border-radius: 8px;
            color: #EFCBDC; font-size: .84rem; font-weight: 500;
            transition: background .15s, color .15s;
        }
        .sidebar .nav-sub a:hover { background: var(--rose-mid); color: #fff; }
        .sidebar .nav-sub a.active { background: var(--pink); color: #fff; }

        .sidebar .sidebar-footer { margin-top: auto; padding-top: 1.25rem; border-top: 1px solid rgba(255,255,255,.1); }
        .sidebar form button {
            width: 100%; display: flex; align-items: center; gap: .7rem;
            padding: .62rem .75rem; border-radius: 8px; background: transparent; border: none;
            color: #EFCBDC; font-family: 'Inter', sans-serif; font-size: .88rem; font-weight: 500;
            cursor: pointer; text-align: left;
        }
        .sidebar form button:hover { background: var(--rose-mid); color: #fff; }
        .sidebar form button svg { width: 18px; height: 18px; }

        /* ================= MAIN ================= */
        .main { flex: 1; min-width: 0; }

        .topbar {
            background: var(--card); border-bottom: 1px solid var(--line);
            padding: 1.25rem 2rem; display: flex; align-items: center; justify-content: space-between;
        }
        .topbar h1 { font-size: 1.3rem; color: var(--rose-dark); }
        .topbar .topbar-sub { font-size: .85rem; color: var(--muted); margin-top: .2rem; }
        .topbar .user-chip { display: flex; align-items: center; gap: .6rem; font-size: .85rem; color: var(--ink); }
        .topbar .user-chip .avatar {
            width: 36px; height: 36px; border-radius: 50%; background: var(--pink-light); color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Poppins', sans-serif; font-weight: 700; font-size: .85rem;
        }

        .content { padding: 2rem; }

        .menu-toggle { display: none; background: none; border: none; padding: .4rem; cursor: pointer; color: var(--rose-dark); }
        .menu-toggle svg { width: 24px; height: 24px; }

        .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(122, 31, 75, .45); z-index: 15; }

        /* ================= RINGKASAN ================= */
        .summary-row {
            display: grid; grid-template-columns: repeat(4, 1fr);
            border: 1px solid var(--line); border-radius: 12px; background: var(--card);
            overflow: hidden; margin-bottom: 1.75rem;
        }
        .summary-item { padding: 1.4rem 1.6rem; border-right: 1px solid var(--line); }
        .summary-item:last-child { border-right: none; }
        .summary-item .label { font-size: .78rem; color: var(--muted); margin-bottom: .5rem; }
        .summary-item .value { font-family: 'Poppins', sans-serif; font-size: 1.65rem; font-weight: 700; color: var(--rose-dark); }
        .summary-item .value.attention { color: var(--amber); }
        .summary-item .delta { font-size: .78rem; color: var(--pink); margin-top: .35rem; }

        /* ================= PANEL ================= */
        .panel { background: var(--card); border: 1px solid var(--line); border-radius: 12px; margin-bottom: 1.75rem; }
        .panel-head { display: flex; align-items: center; justify-content: space-between; padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--line); }
        .panel-head h2 { font-size: 1.02rem; color: var(--rose-dark); }
        .panel-head .panel-link { font-size: .82rem; color: var(--pink); font-weight: 600; }
        .panel-body { padding: 1.25rem 1.5rem; }

        .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }

        /* Bar chart sederhana */
        .bar-row { margin-bottom: 1rem; }
        .bar-row:last-child { margin-bottom: 0; }
        .bar-row .bar-label { display: flex; justify-content: space-between; font-size: .82rem; margin-bottom: .35rem; }
        .bar-row .bar-label span:first-child { color: var(--ink); font-weight: 500; }
        .bar-row .bar-label span:last-child { color: var(--muted); }
        .bar-track { height: 8px; background: #FBEAF1; border-radius: 4px; overflow: hidden; }
        .bar-fill { height: 100%; background: var(--pink); border-radius: 4px; }
        .bar-fill.green { background: var(--green); }
        .bar-fill.amber { background: var(--amber); }
        .bar-fill.red { background: var(--red); }
        .bar-fill.light { background: var(--pink-light); }

        /* ================= TABEL ================= */
        table.data-table { width: 100%; border-collapse: collapse; }
        table.data-table th {
            text-align: left; font-size: .74rem; color: var(--muted); font-weight: 600;
            padding: .8rem 1.5rem; border-bottom: 1px solid var(--line);
        }
        table.data-table td { padding: .85rem 1.5rem; font-size: .88rem; border-bottom: 1px solid var(--line); }
        table.data-table tr:last-child td { border-bottom: none; }

        /* Kolom aksi: tetap sebagai sel tabel supaya garis baris tidak patah */
        table.data-table td.row-actions { white-space: nowrap; text-align: right; }
        table.data-table .row-actions form { display: inline-block; margin: 0; }
        table.data-table .row-actions > * + * { margin-left: .4rem; }

        table.data-table .row-actions a,
        table.data-table .row-actions button {
            display: inline-flex; align-items: center; justify-content: center;
            padding: .4rem .85rem; border-radius: 999px; border: 1px solid transparent;
            font-family: 'Inter', sans-serif; font-size: .78rem; font-weight: 600; line-height: 1;
            cursor: pointer; transition: background .15s, color .15s, border-color .15s;
        }
        table.data-table .row-actions a:focus-visible,
        table.data-table .row-actions button:focus-visible {
            outline: none; box-shadow: 0 0 0 3px rgba(214,71,143,.22);
        }

        .action-view { background: #FBEAF1; color: var(--pink); }
        .action-view:hover { background: var(--pink); color: #fff; }

        .action-edit { background: #FDF1DF; color: #B9791F; }
        .action-edit:hover { background: var(--amber); color: #fff; }

        .action-delete { background: #FBE7E8; color: var(--red); }
        .action-delete:hover { background: var(--red); color: #fff; }

        .status-pill { display: inline-block; padding: .25rem .65rem; border-radius: 6px; font-size: .76rem; font-weight: 600; }
        .status-pill.aktif { background: #E4F1EA; color: var(--green); }
        .status-pill.cuti { background: #FDF1DF; color: #B9791F; }
        .status-pill.nonaktif { background: #FBE7E8; color: var(--red); }
        .status-pill.info { background: #E8F0FB; color: #2B5FB3; }

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
        .btn-primary { background: var(--pink); color: #fff; }
        .btn-primary:hover { background: var(--rose-dark); }
        .btn-secondary { background: #fff; color: var(--ink); border: 1px solid var(--line); }
        .btn-secondary:hover { background: #FBEAF1; }

        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.1rem; padding: 1.5rem; }
        .form-grid .full { grid-column: 1 / -1; }
        .form-field label { display: block; font-size: .82rem; font-weight: 600; color: var(--rose-dark); margin-bottom: .4rem; }
        .form-field input, .form-field select, .form-field textarea {
            width: 100%; padding: .62rem .85rem; border: 1px solid var(--line); border-radius: 8px;
            font-size: .88rem; font-family: 'Inter', sans-serif; background: #fff;
        }
        .form-field input:focus, .form-field select:focus, .form-field textarea:focus {
            outline: none; border-color: var(--pink); box-shadow: 0 0 0 3px rgba(214,71,143,.14);
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

        /* ================= HALAMAN DETAIL ================= */
        .panel-head .head-title { display: flex; align-items: center; gap: .75rem; flex-wrap: wrap; }

        .detail-layout {
            display: grid; grid-template-columns: 340px 1fr; gap: 1.75rem;
            padding: 1.5rem; align-items: start;
        }

        .detail-photo {
            display: block; aspect-ratio: 4 / 3; overflow: hidden;
            border-radius: 12px; border: 1px solid var(--line); background: #FBEAF1;
        }
        .detail-photo img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform .25s ease; }
        a.detail-photo:hover img { transform: scale(1.03); }
        .detail-photo.empty { display: flex; flex-direction: column; align-items: center; justify-content: center; gap: .5rem; color: var(--pink-light); font-size: .8rem; }
        .detail-photo.empty svg { width: 52px; height: 52px; }

        .detail-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: .9rem; margin-bottom: 1.5rem; }
        .detail-stat { border: 1px solid var(--line); border-radius: 10px; padding: .95rem 1.1rem; background: #FFFBFD; }
        .detail-stat .label { font-size: .74rem; color: var(--muted); margin-bottom: .45rem; }
        .detail-stat .value { font-family: 'Poppins', sans-serif; font-size: 1.15rem; font-weight: 700; color: var(--rose-dark); line-height: 1.3; }
        .detail-stat .hint { font-size: .74rem; color: var(--muted); margin-top: .25rem; }

        .detail-layout .detail-grid { padding: 0; grid-template-columns: 150px 1fr; }
        .detail-actions { display: flex; gap: .75rem; margin-top: 1.5rem; padding-top: 1.25rem; border-top: 1px solid var(--line); flex-wrap: wrap; }

        /* ================= SURAT-MENYURAT ================= */
        .status-pill.netral { background: #FBEAF1; color: var(--pink); }

        .btn-sm { padding: .45rem .9rem; font-size: .8rem; }

        .file-card { border: 1px solid var(--line); border-radius: 12px; background: #FFFBFD; padding: 1.5rem 1.25rem; text-align: center; }
        .file-card .file-icon {
            width: 64px; height: 76px; margin: 0 auto .9rem; border-radius: 10px;
            background: #FBEAF1; color: var(--pink);
            display: flex; align-items: center; justify-content: center;
            font-family: 'Poppins', sans-serif; font-weight: 700; font-size: .85rem;
        }
        .file-card .file-icon.pdf { background: #FBE7E8; color: var(--red); }
        .file-card .file-icon.word { background: #E8F0FB; color: #2B5FB3; }
        .file-card .file-name { font-weight: 600; font-size: .88rem; word-break: break-all; margin-bottom: .2rem; }
        .file-card .file-hint { font-size: .76rem; color: var(--muted); margin-bottom: 1rem; }
        .file-card .file-actions { display: flex; gap: .5rem; justify-content: center; flex-wrap: wrap; }
        .file-card.empty { color: var(--muted); font-size: .85rem; }

        /* ================= KOMPONEN BERSAMA (alur status, peringatan) ================= */
        .alert-error {
            background: #FBE7E8; border: 1px solid #F1C4C6; color: var(--red);
            border-radius: 10px; padding: .85rem 1.25rem; font-size: .85rem; margin-bottom: 1.5rem;
        }
        .alert-warning {
            background: #FDF1DF; border: 1px solid #F1D9AE; color: #8A5A12;
            border-radius: 10px; padding: .8rem 1.1rem; font-size: .84rem; margin-bottom: 1.1rem;
        }
        .alert-warning a { color: inherit; font-weight: 600; text-decoration: underline; }

        .btn-danger-soft { background: #FBE7E8; color: var(--red); border: 1px solid #F1C4C6; }
        .btn-danger-soft:hover { background: var(--red); color: #fff; border-color: var(--red); }

        .steps { display: flex; align-items: center; padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--line); }
        .step { display: flex; align-items: center; gap: .55rem; flex: 1; color: var(--muted); font-size: .84rem; font-weight: 500; }
        .step:last-child { flex: 0 0 auto; }
        .step:not(:last-child)::after { content: ''; flex: 1; height: 2px; margin: 0 .9rem; border-radius: 2px; background: var(--line); }
        .step-dot {
            width: 26px; height: 26px; border-radius: 50%; flex-shrink: 0;
            display: inline-flex; align-items: center; justify-content: center;
            background: #FBEAF1; color: var(--muted); font-size: .75rem; font-weight: 700;
        }
        .step.current { color: var(--rose-dark); font-weight: 600; }
        .step.current .step-dot { background: var(--pink); color: #fff; }
        .step.done { color: var(--green); }
        .step.done .step-dot { background: var(--green); color: #fff; }
        .step.done:not(:last-child)::after { background: var(--green); }

        .step-note { padding: 1rem 1.5rem; border-bottom: 1px solid var(--line); background: #FFF8F8; color: var(--red); font-size: .85rem; font-weight: 500; }

        .stage-hint { margin: 0 0 1.1rem; color: var(--muted); font-size: .86rem; }

        .danger-zone {
            display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap;
            margin-top: 1.25rem; padding-top: 1.1rem; border-top: 1px solid var(--line);
        }
        .danger-zone .danger-text { font-size: .84rem; color: var(--muted); }
        .danger-zone .danger-text strong { display: block; color: var(--ink); font-size: .86rem; margin-bottom: .15rem; }

        /* ================= PENGATURAN ================= */
        .settings-layout { display: grid; grid-template-columns: 220px 1fr; gap: 1.5rem; align-items: start; }
        .settings-menu {
            background: var(--card); border: 1px solid var(--line); border-radius: 12px;
            padding: .75rem; display: flex; flex-direction: column; gap: .2rem;
        }
        .settings-menu .settings-menu-title { font-size: .7rem; color: var(--muted); font-weight: 600; letter-spacing: .04em; margin: .4rem .6rem .5rem; }
        .settings-menu a {
            padding: .58rem .75rem; border-radius: 8px; color: var(--ink);
            font-size: .86rem; font-weight: 500; transition: background .15s, color .15s;
        }
        .settings-menu a:hover { background: #FBEAF1; color: var(--rose-dark); }
        .settings-menu a.active { background: var(--pink); color: #fff; }

        .inline-form { display: flex; gap: .75rem; padding: 1.25rem 1.5rem; flex-wrap: wrap; align-items: flex-start; }
        .inline-form input {
            flex: 1; min-width: 200px; padding: .62rem .85rem; border: 1px solid var(--line); border-radius: 8px;
            font-size: .88rem; font-family: 'Inter', sans-serif; background: #fff;
        }
        .inline-form input:focus { outline: none; border-color: var(--pink); box-shadow: 0 0 0 3px rgba(214,71,143,.14); }
        .field-hint { font-size: .78rem; color: var(--muted); }

        @media (max-width: 960px) {
            .menu-toggle { display: inline-flex; }
            .sidebar { position: fixed; inset: 0 auto 0 0; z-index: 20; transform: translateX(-100%); transition: transform .25s ease; }
            .sidebar.open { transform: translateX(0); }
            .sidebar-overlay.open { display: block; }
            .summary-row { grid-template-columns: repeat(2, 1fr); }
            .summary-item:nth-child(2) { border-right: none; }
            .two-col { grid-template-columns: 1fr; }
            .form-grid { grid-template-columns: 1fr; }
            .detail-layout { grid-template-columns: 1fr; }
            .detail-stats { grid-template-columns: 1fr; }
            .settings-layout { grid-template-columns: 1fr; }
            .settings-menu { flex-direction: row; flex-wrap: wrap; }
        }

        @media (max-width: 640px) {
            .step-label { display: none; }
            .step.current .step-label { display: inline; }
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
                    <span>Panel General Affair</span>
                </div>
            </div>

            <nav>
                <a href="{{ route('go.dashboard') }}" class="nav-item {{ request()->routeIs('go.dashboard') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/></svg>
                    Dashboard
                </a>

                <p class="nav-label">DATA</p>
                <a href="{{ route('go.assets.index') }}" class="nav-item {{ request()->routeIs('go.assets.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><path d="M3.27 6.96L12 12.01l8.73-5.05"/><path d="M12 22.08V12"/></svg>
                    Aset / Inventaris
                </a>

                @php $atkActive = request()->routeIs('go.atk-*'); @endphp
                <details class="nav-group {{ $atkActive ? 'active' : '' }}" @if($atkActive) open @endif>
                    <summary>
                        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M16 13H8"/><path d="M16 17H8"/><path d="M10 9H8"/></svg>
                        ATK
                        <svg class="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="m6 9 6 6 6-6"/></svg>
                    </summary>
                    <div class="nav-sub">
                        <a href="{{ route('go.atk-requests.index') }}" class="{{ request()->routeIs('go.atk-requests.*') ? 'active' : '' }}">Permintaan</a>
                        <a href="{{ route('go.atk-items.index') }}" class="{{ request()->routeIs('go.atk-items.*') ? 'active' : '' }}">Barang ATK</a>
                        <a href="{{ route('go.atk-stock-ins.index') }}" class="{{ request()->routeIs('go.atk-stock-ins.*') ? 'active' : '' }}">Barang Masuk</a>
                    </div>
                </details>

                @php $fasilitasActive = request()->routeIs('go.facilities.*', 'go.facility-loans.*'); @endphp
                <details class="nav-group {{ $fasilitasActive ? 'active' : '' }}" @if($fasilitasActive) open @endif>
                    <summary>
                        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18"/><path d="M5 21V7l8-4v18"/><path d="M19 21V11l-6-4"/><path d="M9 9v.01M9 12v.01M9 15v.01M9 18v.01"/></svg>
                        Fasilitas
                        <svg class="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="m6 9 6 6 6-6"/></svg>
                    </summary>
                    <div class="nav-sub">
                        <a href="{{ route('go.facilities.index') }}" class="{{ request()->routeIs('go.facilities.*') ? 'active' : '' }}">Daftar Fasilitas</a>
                        <a href="{{ route('go.facility-loans.index') }}" class="{{ request()->routeIs('go.facility-loans.*') ? 'active' : '' }}">Peminjaman</a>
                    </div>
                </details>

                <a href="{{ route('go.letters.index') }}" class="nav-item {{ request()->routeIs('go.letters.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16v16H4z"/><path d="m4 6 8 7 8-7"/></svg>
                    Surat-Menyurat
                </a>

                <p class="nav-label">LAINNYA</p>
                <a href="{{ route('go.settings.index') }}" class="nav-item {{ request()->routeIs('go.settings.*') ? 'active' : '' }}">
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
                        <h1>@yield('page-title', 'Dashboard General Affair')</h1>
                        <p class="topbar-sub">@yield('page-subtitle', now()->translatedFormat('l, d F Y'))</p>
                    </div>
                </div>
                <div class="user-chip">
                    <div class="avatar">D</div>
                    <span>Dwi Jasri Agustin, SE</span>
                </div>
            </div>

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