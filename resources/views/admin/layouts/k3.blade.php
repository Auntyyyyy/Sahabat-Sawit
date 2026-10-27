<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script>document.documentElement.classList.add('js-on');</script>
    <title>@yield('title', 'Dashboard K3') - PT Sahabat Sawit</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    {{-- Font dimuat tanpa menahan halaman (non-blocking). Kalau koneksi ke Google Fonts lambat,
         script jam dan lainnya tetap jalan dan font cadangan dipakai sementara. --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
          rel="stylesheet" media="print" onload="this.media='all'">
    <noscript>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    </noscript>

    <style>
        /* ===== PALET WARNA K3: merah, putih, hitam, dongker =====
           Ubah warna cukup di sini, seluruh dashboard ikut berubah. */
        :root{
            --k3-red:#C8102E;
            --k3-red-dark:#9E0B22;
            --k3-navy:#0B1F3A;
            --k3-navy-2:#12294A;
            --k3-black:#0A0F1A;
            --k3-bg:#F3F5F9;
            --k3-text:#1F2937;
            --k3-muted:#6B7280;
            --k3-sidebar-w:264px;
        }

        *{ box-sizing:border-box; }
        body{
            margin:0;
            font-family:'Inter', system-ui, sans-serif;
            background:var(--k3-bg);
            color:var(--k3-text);
        }

        /* ===== SIDEBAR ===== */
        .k3-sidebar{
            position:fixed; top:0; left:0; bottom:0;
            width:var(--k3-sidebar-w);
            background:linear-gradient(180deg, var(--k3-navy) 0%, var(--k3-black) 100%);
            color:#fff;
            display:flex; flex-direction:column;
            padding:20px 14px;
            z-index:1040;
            overflow-y:auto;
            transition:transform .3s ease;
        }
        .k3-brand{ text-align:center; margin-bottom:14px; }
        .k3-brand-logo{
            width:84px; height:84px; margin:0 auto 10px;
            background:#fff; border-radius:50%;
            display:grid; place-items:center;
            border:3px solid var(--k3-red);
            box-shadow:0 8px 20px rgba(0,0,0,.35);
            overflow:hidden;
        }
        .k3-brand-logo img{ width:68px; height:68px; object-fit:contain; }
        .k3-brand-title{ font-weight:800; letter-spacing:.04em; font-size:1rem; }
        .k3-brand-sub{ font-size:.72rem; color:rgba(255,255,255,.6); }

        .k3-clock{
            background:rgba(255,255,255,.06);
            border:1px solid rgba(255,255,255,.1);
            border-radius:14px;
            padding:10px 14px;
            text-align:center;
            margin-bottom:18px;
        }
        .k3-clock-time{ font-family:'Courier New', monospace; font-size:1.35rem; font-weight:700; letter-spacing:.08em; }
        .k3-clock-date{ font-size:.7rem; color:rgba(255,255,255,.55); text-transform:uppercase; letter-spacing:.08em; }

        .k3-nav{ display:flex; flex-direction:column; gap:4px; flex:1; }
        .k3-link{
            display:flex; align-items:center; gap:12px;
            padding:11px 14px;
            border-radius:12px;
            color:rgba(255,255,255,.78);
            text-decoration:none;
            font-size:.9rem; font-weight:500;
            border-left:4px solid transparent;
            transition:background .2s, color .2s;
        }
        .k3-link i{ font-size:1.1rem; width:20px; text-align:center; }
        .k3-link:hover{ background:rgba(255,255,255,.07); color:#fff; }
        .k3-link.is-active{
            background:rgba(200,16,46,.22);
            border-left-color:var(--k3-red);
            color:#fff; font-weight:600;
        }
        .k3-link.is-soon{ cursor:default; opacity:.5; }
        .k3-link.is-soon:hover{ background:transparent; color:rgba(255,255,255,.78); }
        .k3-soon{
            margin-left:auto; font-size:.6rem; font-weight:700;
            text-transform:uppercase; letter-spacing:.05em;
            background:rgba(255,255,255,.14);
            padding:2px 7px; border-radius:999px;
        }

        .k3-logout{ margin-top:14px; }
        .k3-logout button{
            width:100%; display:flex; align-items:center; justify-content:center; gap:8px;
            background:var(--k3-red); color:#fff; border:none;
            padding:11px; border-radius:12px; font-weight:600; font-size:.9rem;
        }
        .k3-logout button:hover{ background:var(--k3-red-dark); }

        /* ===== AREA UTAMA ===== */
        .k3-main{ margin-left:var(--k3-sidebar-w); min-height:100vh; }

        .k3-topbar{
            background:var(--k3-red);
            color:#fff;
            height:72px;
            display:flex; align-items:center; justify-content:space-between;
            padding:0 28px;
            position:sticky; top:0; z-index:1030;
            box-shadow:0 4px 14px rgba(158,11,34,.35);
        }
        .k3-topbar h1{ font-size:1.25rem; font-weight:700; margin:0; }
        .k3-toggle{
            display:none; background:transparent; border:none; color:#fff; font-size:1.6rem; margin-right:12px; padding:0;
            cursor:pointer; line-height:1;
        }
        .k3-user{ display:flex; align-items:center; gap:12px; }
        .k3-avatar{
            width:40px; height:40px; border-radius:50%;
            background:#fff; color:var(--k3-red);
            display:grid; place-items:center; font-weight:800;
        }
        .k3-user-name{ font-weight:600; font-size:.9rem; line-height:1.2; }
        .k3-user-role{ font-size:.72rem; opacity:.85; }

        .k3-content{ padding:28px; }

        /* ===== KOMPONEN KARTU ===== */
        .k3-card{
            background:#fff;
            border-radius:18px;
            border:1px solid #E8EBF2;
            box-shadow:0 6px 20px rgba(11,31,58,.07);
            padding:22px;
            height:100%;
        }
        .k3-card-title{ font-size:1rem; font-weight:700; color:var(--k3-navy); margin:0 0 4px; }
        .k3-card-sub{ font-size:.8rem; color:var(--k3-muted); margin:0 0 16px; }

        .k3-kpi-icon{
            width:46px; height:46px; border-radius:13px;
            display:grid; place-items:center; font-size:1.3rem;
        }
        .is-red{ background:rgba(200,16,46,.1); color:var(--k3-red); }
        .is-navy{ background:rgba(11,31,58,.09); color:var(--k3-navy); }
        .is-black{ background:rgba(10,15,26,.09); color:var(--k3-black); }

        .k3-kpi-label{ font-size:.72rem; letter-spacing:.06em; text-transform:uppercase; color:var(--k3-muted); font-weight:700; }
        .k3-kpi-value{ font-size:2.3rem; font-weight:800; color:var(--k3-navy); line-height:1.1; }
        .k3-timer{ font-family:'Courier New', monospace; font-size:1.55rem; font-weight:800; color:var(--k3-red); line-height:1.3; }

        .k3-pill{
            display:inline-flex; align-items:center; gap:6px;
            padding:5px 12px; border-radius:999px;
            font-size:.75rem; font-weight:600;
        }
        .pill-red{ background:rgba(200,16,46,.1); color:var(--k3-red-dark); }
        .pill-navy{ background:rgba(11,31,58,.09); color:var(--k3-navy); }
        .pill-black{ background:rgba(10,15,26,.08); color:var(--k3-black); }

        .k3-equip{ display:grid; grid-template-columns:1.1fr 1fr 1fr 1fr; align-items:center; }
        .k3-equip > div{ padding:6px 22px; }
        .k3-equip > div + div{ border-left:1px solid #E8EBF2; }

        /* ===== TOMBOL, TABEL, FORM (dipakai halaman modul K3) ===== */
        .k3-btn{
            display:inline-flex; align-items:center; gap:6px;
            padding:9px 16px; border-radius:10px;
            border:1px solid transparent;
            font-weight:600; font-size:.85rem;
            text-decoration:none; cursor:pointer;
            transition:background .2s, color .2s;
        }
        .k3-btn-red{ background:var(--k3-red); color:#fff; }
        .k3-btn-red:hover{ background:var(--k3-red-dark); color:#fff; }
        .k3-btn-outline{ background:#fff; color:var(--k3-navy); border-color:#D5DAE5; }
        .k3-btn-outline:hover{ background:var(--k3-bg); color:var(--k3-navy); }
        .k3-btn-icon{ width:34px; height:34px; padding:0; justify-content:center; border-radius:9px; }

        .k3-table thead th{
            background:var(--k3-navy); color:#fff;
            font-size:.8rem; font-weight:600; white-space:nowrap;
            border:none; padding:12px 14px;
        }
        .k3-table tbody td{ vertical-align:middle; padding:12px 14px; font-size:.9rem; }

        .k3-label{ display:block; font-weight:600; font-size:.85rem; margin-bottom:6px; color:var(--k3-navy); }
        .form-control:focus, .form-select:focus{
            border-color:var(--k3-red);
            box-shadow:0 0 0 .2rem rgba(200,16,46,.15);
        }

        /* Peringatan jam: tersembunyi, baru muncul 2 detik setelah halaman dibuka
           dan hanya kalau jam belum berjalan. Kalau jam normal, ini tidak pernah terlihat. */
        .k3-clock-warn{ display:none; font-size:.65rem; color:#FFB4B4; margin-top:6px; line-height:1.3; opacity:0; }
        html:not(.js-on) .k3-clock-warn,
        html.js-on:not(.clock-on) .k3-clock-warn{ display:block; animation:k3WarnIn 0s linear 2s forwards; }
        html:not(.js-on) .k3-clock-warn::before{ content:'JavaScript halaman ini nonaktif'; }
        html.js-on:not(.clock-on) .k3-clock-warn::before{ content:'Jam belum berjalan (cek Console)'; }
        @keyframes k3WarnIn{ to{ opacity:1; } }

        /* ===== OVERLAY SIDEBAR (HP / TABLET) ===== */
        .k3-overlay{
            display:none; position:fixed; inset:0; background:rgba(0,0,0,.55); z-index:1035;
        }

        @media (max-width: 991.98px){
            .k3-sidebar{ transform:translateX(-100%); }
            .k3-main{ margin-left:0; }
            .k3-toggle{ display:block; }
            .k3-topbar{ padding:0 16px; }
            .k3-content{ padding:18px 14px; }
            .k3-equip{ grid-template-columns:1fr; gap:10px; }
            .k3-equip > div + div{ border-left:none; border-top:1px solid #E8EBF2; padding-top:14px; }

            /* Sidebar dibuka lewat checkbox tersembunyi (#k3NavToggle), tanpa JavaScript.
               Tombol menu dan area gelap sama-sama berupa <label> untuk checkbox itu. */
            .k3-nav-toggle:checked ~ .k3-sidebar{ transform:translateX(0); }
            .k3-nav-toggle:checked ~ .k3-overlay{ display:block; }
        }
    </style>
    @stack('styles')
</head>
<body>

@php
    // Daftar menu sidebar. Menu yang belum punya 'route' tampil redup dengan label "Segera".
    // Saat modulnya jadi, cukup tambahkan 'route' => 'k3.nama-route' pada baris menu terkait.
    $menu = [
        ['icon' => 'bi-speedometer2',     'label' => 'Dashboard',            'route' => 'k3.dashboard', 'active' => request()->routeIs('k3.dashboard')],
        ['icon' => 'bi-file-earmark-check','label' => 'Dokumen Keselamatan', 'route' => \Illuminate\Support\Facades\Route::has('k3.dokumen.index') ? 'k3.dokumen.index' : null, 'active' => request()->routeIs('k3.dokumen.*')],
        ['icon' => 'bi-clipboard-check',  'label' => 'Safety Patrol'],
        ['icon' => 'bi-heart-pulse',      'label' => 'Monitoring Kesehatan'],
        ['icon' => 'bi-wind',             'label' => 'Pengukuran Lingkungan'],
        ['icon' => 'bi-person-gear',      'label' => 'Ergonomi'],
        ['icon' => 'bi-award',            'label' => 'Sertifikat'],
        ['icon' => 'bi-fire',             'label' => 'Peralatan'],
        ['icon' => 'bi-calendar-check',   'label' => 'Program K3L'],
        ['icon' => 'bi-bar-chart-line',   'label' => 'Statistik'],
    ];
    $user = auth()->user();
    $inisial = strtoupper(mb_substr($user->name ?? 'K', 0, 1));
@endphp

{{-- Checkbox tersembunyi: pengatur buka/tutup sidebar di HP/tablet (tanpa JavaScript) --}}
<input type="checkbox" id="k3NavToggle" class="k3-nav-toggle" hidden>
<label for="k3NavToggle" class="k3-overlay" id="k3Overlay"></label>

<aside class="k3-sidebar" id="k3Sidebar">
    <div class="k3-brand">
        <div class="k3-brand-logo">
            <img src="{{ asset('images/logo (2).png') }}" alt="PT Sahabat Sawit Rokan Sejahtera">
        </div>
        <div class="k3-brand-title">K3 &middot; SAHABAT SAWIT</div>
        <div class="k3-brand-sub">Keselamatan &amp; Kesehatan Kerja</div>
    </div>

    <div class="k3-clock">
        <div class="k3-clock-time" id="k3ClockTime">--:--:--</div>
        <div class="k3-clock-date" id="k3ClockDate">&nbsp;</div>
        <div class="k3-clock-warn" id="k3ClockWarn"></div>
    </div>

    <nav class="k3-nav">
        @foreach ($menu as $item)
            @if (! empty($item['route']))
                <a href="{{ route($item['route']) }}" class="k3-link {{ ! empty($item['active']) ? 'is-active' : '' }}">
                    <i class="bi {{ $item['icon'] }}"></i>
                    <span>{{ $item['label'] }}</span>
                </a>
            @else
                <span class="k3-link is-soon">
                    <i class="bi {{ $item['icon'] }}"></i>
                    <span>{{ $item['label'] }}</span>
                    <span class="k3-soon">Segera</span>
                </span>
            @endif
        @endforeach
    </nav>

    <form action="{{ route('admin.logout') }}" method="POST" class="k3-logout">
        @csrf
        <button type="submit"><i class="bi bi-box-arrow-right"></i> Keluar</button>
    </form>
</aside>

<div class="k3-main">
    <header class="k3-topbar">
        <div class="d-flex align-items-center">
            <label for="k3NavToggle" class="k3-toggle" id="k3Toggle" role="button" aria-label="Buka menu"><i class="bi bi-list"></i></label>
            <h1>@yield('page_title', 'Dashboard')</h1>
        </div>
        <div class="k3-user">
            <div class="text-end d-none d-sm-block">
                <div class="k3-user-name">{{ $user->name }}</div>
                <div class="k3-user-role">Petugas K3</div>
            </div>
            <div class="k3-avatar">{{ $inisial }}</div>
        </div>
    </header>

    <main class="k3-content">
        @yield('content')
    </main>
</div>

<script>
    /* Jam sidebar + penghitung Jam Kerja Aman.
       Dibuat sederhana dan tahan gangguan: tidak memakai toLocale*, dan dijalankan ulang
       saat jendela diubah ukurannya, difokuskan, atau halaman dipulihkan dari cache. */
    (function () {
        var BULAN = ['JAN','FEB','MAR','APR','MEI','JUN','JUL','AGU','SEP','OKT','NOV','DES'];

        function pad(n) { return (n < 10 ? '0' : '') + n; }

        function tickClock() {
            var timeEl = document.getElementById('k3ClockTime');
            var dateEl = document.getElementById('k3ClockDate');
            if (! timeEl) { return; }
            var now = new Date();
            timeEl.textContent = pad(now.getHours()) + ':' + pad(now.getMinutes()) + ':' + pad(now.getSeconds());
            if (dateEl) {
                dateEl.textContent = pad(now.getDate()) + ' ' + BULAN[now.getMonth()] + ' ' + now.getFullYear();
            }
        }

        // Semua elemen ber-atribut data-since dihitung sebagai "waktu berjalan sejak tanggal itu"
        function tickSince() {
            var els = document.querySelectorAll('[data-since]');
            for (var i = 0; i < els.length; i++) {
                var start = new Date(els[i].getAttribute('data-since')).getTime();
                if (isNaN(start)) { continue; }
                var s = Math.max(0, Math.floor((Date.now() - start) / 1000));
                var d = Math.floor(s / 86400); s = s % 86400;
                els[i].textContent = d + ' hari ' + pad(Math.floor(s / 3600)) + ':' + pad(Math.floor((s % 3600) / 60)) + ':' + pad(s % 60);
            }
        }

        function tick() {
            try {
                tickClock();
                tickSince();
                document.documentElement.classList.add('clock-on');
            } catch (e) {
                console.error('K3 jam error:', e);
            }
        }

        tick();
        setInterval(tick, 1000);
        document.addEventListener('visibilitychange', tick);
        window.addEventListener('pageshow', tick);
        window.addEventListener('focus', tick);
        window.addEventListener('resize', tick);
    })();
</script>
@stack('scripts')
</body>
</html>