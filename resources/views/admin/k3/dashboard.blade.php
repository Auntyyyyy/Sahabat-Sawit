<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard K3') - PT Sahabat Sawit</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.@extends('admin.layouts.k3')

@section('title', 'Dashboard K3')
@section('page_title', 'Dashboard')

@section('content')

    {{-- ===== BARIS 1: KARTU RINGKASAN ===== --}}
    <div class="row g-4 mb-4">

        <div class="col-md-6 col-xl-4">
            <div class="k3-card">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="k3-kpi-icon is-red"><i class="bi bi-file-earmark-check"></i></div>
                </div>
                <div class="k3-kpi-label">Dokumen Keselamatan</div>
                <div class="k3-kpi-value">{{ $stats['dokumen_aktif'] }}</div>
                <div class="mt-3">
                    <span class="k3-pill pill-red"><i class="bi bi-check-circle-fill"></i> {{ $stats['dokumen_aktif'] }} Dokumen Aktif</span>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-4">
            <div class="k3-card">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="k3-kpi-icon is-navy"><i class="bi bi-wind"></i></div>
                </div>
                <div class="k3-kpi-label">Pengukuran Lingkungan</div>
                <div class="k3-kpi-value">{{ $stats['pengukuran_total'] }}</div>
                <div class="mt-3">
                    <span class="k3-pill pill-navy"><i class="bi bi-geo-alt-fill"></i> {{ $stats['titik_pengukuran'] }} Titik Pengukuran</span>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-xl-4">
            <div class="k3-card">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="k3-kpi-icon is-black"><i class="bi bi-clock-history"></i></div>
                </div>
                <div class="k3-kpi-label">Jam Kerja Aman (Safe Manhours)</div>
                <div class="k3-timer" id="safeTimer" data-start="{{ $stats['safe_since'] }}">0 hari 00:00:00</div>
                <div class="mt-3">
                    <span class="k3-pill pill-black"><i class="bi bi-shield-check"></i> LTI Free Shift Ongoing</span>
                </div>
            </div>
        </div>

    </div>

    {{-- ===== BARIS 2: PERALATAN KESELAMATAN ===== --}}
    <div class="k3-card mb-4">
        <div class="k3-equip">
            <div>
                <div class="d-flex align-items-center gap-3">
                    <div class="k3-kpi-icon is-black"><i class="bi bi-tools"></i></div>
                    <div>
                        <div class="fw-bold" style="color:var(--k3-navy)">Peralatan Keselamatan</div>
                        <div class="small text-muted">Status APD, APAR &amp; P3K terintegrasi</div>
                    </div>
                </div>
            </div>

            <div>
                <div class="d-flex align-items-center gap-3">
                    <div class="k3-kpi-icon is-navy"><i class="bi bi-shield-fill-check"></i></div>
                    <div>
                        <div class="k3-kpi-label">Alat Pelindung Diri</div>
                        <div><span class="fs-5 fw-semibold">{{ $stats['apd_jenis'] }}</span> <span class="text-muted">Jenis APD</span></div>
                        <div class="small">Stock: <strong>{{ $stats['apd_stock'] }} Unit</strong></div>
                    </div>
                </div>
            </div>

            <div>
                <div class="d-flex align-items-center gap-3">
                    <div class="k3-kpi-icon is-red"><i class="bi bi-fire"></i></div>
                    <div>
                        <div class="k3-kpi-label">Proteksi Kebakaran</div>
                        <div><span class="fs-5 fw-semibold">{{ $stats['apar_total'] }}</span> <span class="text-muted">Tabung</span></div>
                        <div class="small" style="color:var(--k3-red)">Status: <strong>{{ $stats['apar_refill'] }} Perlu Refill</strong></div>
                    </div>
                </div>
            </div>

            <div>
                <div class="d-flex align-items-center gap-3">
                    <div class="k3-kpi-icon is-black"><i class="bi bi-bandaid-fill"></i></div>
                    <div>
                        <div class="k3-kpi-label">Kotak &amp; Ruang P3K</div>
                        <div><span class="fs-5 fw-semibold">{{ $stats['p3k_area'] }}</span> <span class="text-muted">Area Standar</span></div>
                        <div class="small">Item Standar: <strong>{{ $stats['p3k_item'] }} Item</strong></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== BARIS 3: PROGRESS PROGRAM & STATISTIK PATROL ===== --}}
    <div class="row g-4 mb-4">

        <div class="col-lg-6">
            <div class="k3-card">
                <h2 class="k3-card-title">Progress Program K3L</h2>
                <p class="k3-card-sub">Pencapaian program kerja tahun berjalan</p>

                <div class="d-flex align-items-end gap-3 mb-3">
                    <div class="k3-kpi-value" style="color:var(--k3-red)">{{ $stats['program_persen'] }}%</div>
                    <div class="text-muted pb-2">Selesai</div>
                </div>

                <div class="progress mb-3" style="height:12px; border-radius:999px; background:#E8EBF2;">
                    <div class="progress-bar" role="progressbar"
                         style="width: {{ $stats['program_persen'] }}%; background:var(--k3-red); border-radius:999px;"
                         aria-valuenow="{{ $stats['program_persen'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>

                <div class="d-flex gap-2 flex-wrap">
                    <span class="k3-pill pill-navy">Total: {{ $stats['program_total'] }} Program</span>
                    <span class="k3-pill pill-red"><i class="bi bi-check2-circle"></i> {{ $stats['program_selesai'] }} Selesai</span>
                    <span class="k3-pill pill-black"><i class="bi bi-hourglass-split"></i> {{ $stats['program_rencana'] }} Rencana</span>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="k3-card">
                <h2 class="k3-card-title">Statistik Safety Patrol</h2>
                <p class="k3-card-sub">Tingkat kepatuhan inspeksi keselamatan</p>

                <div class="d-flex align-items-end gap-3 mb-3">
                    <div class="k3-kpi-value">{{ $stats['patrol_item'] }}</div>
                    <div class="text-muted pb-2">Item Inspeksi</div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between small fw-semibold mb-1">
                        <span>Ya (Sesuai)</span><span>{{ $stats['patrol_sesuai'] }}%</span>
                    </div>
                    <div class="progress" style="height:10px; border-radius:999px; background:#E8EBF2;">
                        <div class="progress-bar" style="width: {{ $stats['patrol_sesuai'] }}%; background:var(--k3-navy); border-radius:999px;"></div>
                    </div>
                </div>

                <div>
                    <div class="d-flex justify-content-between small fw-semibold mb-1">
                        <span>Tidak (Temuan)</span><span>{{ 100 - $stats['patrol_sesuai'] }}%</span>
                    </div>
                    <div class="progress" style="height:10px; border-radius:999px; background:#E8EBF2;">
                        <div class="progress-bar" style="width: {{ 100 - $stats['patrol_sesuai'] }}%; background:var(--k3-red); border-radius:999px;"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ===== BARIS 4: AGENDA & PERLU PERHATIAN ===== --}}
    <div class="row g-4">

        <div class="col-lg-7">
            <div class="k3-card">
                <h2 class="k3-card-title">Agenda / Reminders</h2>
                <p class="k3-card-sub">Kegiatan K3 yang akan datang</p>

                @forelse ($agenda as $item)
                    <div class="d-flex gap-3 py-3 {{ ! $loop->last ? 'border-bottom' : '' }}">
                        <div class="k3-kpi-icon is-red flex-shrink-0"><i class="bi bi-calendar-event"></i></div>
                        <div>
                            <div class="fw-semibold" style="color:var(--k3-navy)">{{ $item['judul'] }}</div>
                            <div class="small text-muted">PIC: {{ $item['pic'] }}</div>
                            <div class="small"><i class="bi bi-clock"></i> {{ $item['tanggal'] }} &middot; {{ $item['waktu'] }}</div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted mb-0">Belum ada agenda.</p>
                @endforelse
            </div>
        </div>

        <div class="col-lg-5">
            <div class="k3-card">
                <h2 class="k3-card-title">Perlu Perhatian</h2>
                <p class="k3-card-sub">Hal yang perlu segera ditindaklanjuti</p>

                <div class="d-flex align-items-center gap-3 py-3 border-bottom">
                    <div class="k3-kpi-icon is-red"><i class="bi bi-fire"></i></div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold">APAR perlu refill</div>
                        <div class="small text-muted">Masa aktif habis dalam 90 hari</div>
                    </div>
                    <span class="k3-pill pill-red">{{ $stats['apar_refill'] }}</span>
                </div>

                <div class="d-flex align-items-center gap-3 py-3 border-bottom">
                    <div class="k3-kpi-icon is-navy"><i class="bi bi-exclamation-triangle"></i></div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold">Temuan patrol belum selesai</div>
                        <div class="small text-muted">Menunggu tindakan perbaikan</div>
                    </div>
                    <span class="k3-pill pill-navy">{{ $stats['temuan_open'] }}</span>
                </div>

                <div class="d-flex align-items-center gap-3 py-3">
                    <div class="k3-kpi-icon is-black"><i class="bi bi-award"></i></div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold">Sertifikat hampir kedaluwarsa</div>
                        <div class="small text-muted">Dalam 60 hari ke depan</div>
                    </div>
                    <span class="k3-pill pill-black">{{ $stats['sertifikat_hampir'] }}</span>
                </div>
            </div>
        </div>

    </div>

@endsection

@push('scripts')
<script>
    // Penghitung Jam Kerja Aman: menghitung waktu berjalan sejak tanggal insiden terakhir
    (function () {
        const el = document.getElementById('safeTimer');
        if (! el) return;

        const start = new Date(el.dataset.start).getTime();
        const pad = (n) => String(n).padStart(2, '0');

        function tick() {
            let s = Math.max(0, Math.floor((Date.now() - start) / 1000));
            const d = Math.floor(s / 86400); s %= 86400;
            const h = Math.floor(s / 3600);
            const m = Math.floor((s % 3600) / 60);
            const sec = s % 60;
            el.textContent = d + ' hari ' + pad(h) + ':' + pad(m) + ':' + pad(sec);
        }

        tick();
        setInterval(tick, 1000);
    })();
</script>
@endpushgoogleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

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

        @media (max-width: 991.98px){
            .k3-sidebar{ transform:translateX(-100%); }
            .k3-sidebar.is-open{ transform:translateX(0); }
            .k3-main{ margin-left:0; }
            .k3-toggle{ display:block; }
            .k3-topbar{ padding:0 16px; }
            .k3-content{ padding:18px 14px; }
            .k3-equip{ grid-template-columns:1fr; gap:10px; }
            .k3-equip > div + div{ border-left:none; border-top:1px solid #E8EBF2; padding-top:14px; }
        }
        .k3-overlay{
            display:none; position:fixed; inset:0; background:rgba(0,0,0,.55); z-index:1035;
        }
        .k3-overlay.is-open{ display:block; }
    </style>
    @stack('styles')
</head>
<body>

@php
    // Daftar menu sidebar. Menu yang belum punya 'route' tampil redup dengan label "Segera".
    // Saat modulnya jadi, cukup tambahkan 'route' => 'k3.nama-route' pada baris menu terkait.
    $menu = [
        ['icon' => 'bi-speedometer2',     'label' => 'Dashboard',            'route' => 'k3.dashboard', 'active' => request()->routeIs('k3.dashboard')],
        ['icon' => 'bi-file-earmark-check','label' => 'Dokumen Keselamatan'],
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

<div class="k3-overlay" id="k3Overlay"></div>

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
            <button class="k3-toggle" id="k3Toggle" type="button" aria-label="Buka menu"><i class="bi bi-list"></i></button>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    (function () {
        // Jam digital di sidebar
        const timeEl = document.getElementById('k3ClockTime');
        const dateEl = document.getElementById('k3ClockDate');
        function tickClock() {
            const now = new Date();
            timeEl.textContent = now.toLocaleTimeString('id-ID', { hour12: false }).replace(/\./g, ':');
            dateEl.textContent = now.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
        }
        tickClock();
        setInterval(tickClock, 1000);

        // Buka/tutup sidebar di layar kecil
        const sidebar = document.getElementById('k3Sidebar');
        const overlay = document.getElementById('k3Overlay');
        const toggle = document.getElementById('k3Toggle');
        function setOpen(open) {
            sidebar.classList.toggle('is-open', open);
            overlay.classList.toggle('is-open', open);
        }
        toggle.addEventListener('click', () => setOpen(! sidebar.classList.contains('is-open')));
        overlay.addEventListener('click', () => setOpen(false));
    })();
</script>
@stack('scripts')
</body>
</html>