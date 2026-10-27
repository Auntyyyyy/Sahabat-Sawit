@extends('admin.layouts.k3')

@section('title', 'Dashboard K3')
@section('page_title', 'Dashboard')

@push('styles')
<style>
    /* ===== HERO ===== */
    .k3-hero{
        display:grid; grid-template-columns:1.35fr 1fr; gap:24px; align-items:center;
        padding:30px 32px; border-radius:22px; color:#fff;
        background:
            radial-gradient(circle at 88% 12%, rgba(200,16,46,.60), transparent 46%),
            linear-gradient(135deg, var(--k3-navy) 0%, var(--k3-black) 100%);
        box-shadow:0 14px 34px rgba(11,31,58,.28);
    }
    .k3-hero-eyebrow{ font-size:.78rem; letter-spacing:.08em; text-transform:uppercase; color:rgba(255,255,255,.65); font-weight:600; }
    .k3-hero h2{ font-size:1.7rem; font-weight:800; margin:6px 0 8px; line-height:1.2; }
    .k3-hero p{ color:rgba(255,255,255,.75); margin:0 0 18px; font-size:.92rem; max-width:460px; }
    .k3-hero-actions{ display:flex; flex-wrap:wrap; gap:10px; }
    .k3-btn-ghost{ background:rgba(255,255,255,.1); color:#fff; border-color:rgba(255,255,255,.25); }
    .k3-btn-ghost:hover{ background:rgba(255,255,255,.2); color:#fff; }

    .k3-hero-safe{
        background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.16);
        border-radius:18px; padding:22px; text-align:center; backdrop-filter:blur(4px);
    }
    .k3-hero-safe-label{ font-size:.72rem; letter-spacing:.07em; text-transform:uppercase; color:rgba(255,255,255,.7); font-weight:700; }
    .k3-hero-safe-timer{ font-family:'Courier New', monospace; font-size:2rem; font-weight:800; margin:10px 0 12px; letter-spacing:.02em; }
    .pill-white{ background:rgba(255,255,255,.14); color:#fff; }

    /* ===== KARTU RINGKAS ===== */
    .k3-kpi-link{ color:inherit; transition:transform .2s, box-shadow .2s; }
    .k3-kpi-link:hover{ transform:translateY(-3px); box-shadow:0 12px 26px rgba(11,31,58,.14); color:inherit; }
    .k3-kpi-arrow{ color:var(--k3-muted); font-size:1.1rem; }
    .k3-kpi-note{ font-size:.8rem; color:var(--k3-muted); margin-top:6px; }
    .k3-kpi-alert .k3-kpi-value{ color:var(--k3-red); }

    /* ===== CINCIN PERSENTASE (tanpa pustaka grafik) ===== */
    .k3-ring{
        --p:0; --c:var(--k3-red);
        position:relative; width:96px; height:96px; border-radius:50%;
        background:conic-gradient(var(--c) calc(var(--p) * 1%), #E8EBF2 0);
        display:grid; place-items:center;
    }
    .k3-ring::before{ content:''; position:absolute; inset:10px; background:#fff; border-radius:50%; }
    .k3-ring span{ position:relative; font-weight:800; font-size:1.1rem; color:var(--k3-navy); }
    .k3-ring-label{ font-weight:700; font-size:.88rem; margin-top:10px; color:var(--k3-navy); }
    .k3-ring-sub{ font-size:.75rem; color:var(--k3-muted); }

    /* ===== BARIS PERALATAN ===== */
    .k3-equip-row{ display:flex; align-items:center; gap:14px; padding:14px 0; }
    .k3-equip-row + .k3-equip-row{ border-top:1px solid #E8EBF2; }

    .k3-stack{ display:flex; flex-direction:column; gap:24px; height:100%; }
    .k3-stack .k3-card{ height:auto; }

    @media (max-width: 991.98px){
        .k3-hero{ grid-template-columns:1fr; padding:24px 20px; }
        .k3-hero h2{ font-size:1.35rem; }
        .k3-hero-safe-timer{ font-size:1.6rem; }
    }
</style>
@endpush

@section('content')

    {{-- ===== HERO: SAPAAN + JAM KERJA AMAN ===== --}}
    <section class="k3-hero mb-4">
        <div>
            <div class="k3-hero-eyebrow">{{ $tanggalHariIni }}</div>
            <h2>{{ $sapaan }}, {{ auth()->user()->name }}</h2>
            <p>Pantau kondisi keselamatan dan kesehatan kerja PT Sahabat Sawit Rokan Sejahtera hari ini.</p>
            @if ($bisaDokumen)
                <div class="k3-hero-actions">
                    <a href="{{ route('k3.dokumen.create') }}" class="k3-btn k3-btn-red"><i class="bi bi-plus-lg"></i> Tambah Dokumen</a>
                    <a href="{{ route('k3.dokumen.index') }}" class="k3-btn k3-btn-ghost"><i class="bi bi-folder2-open"></i> Lihat Dokumen</a>
                </div>
            @endif
        </div>

        <div class="k3-hero-safe">
            <div class="k3-hero-safe-label">Jam Kerja Aman (Safe Manhours)</div>
            @if ($stats['safe_since'])
                <div class="k3-hero-safe-timer" data-since="{{ $stats['safe_since'] }}">0 hari 00:00:00</div>
                <span class="k3-pill pill-white"><i class="bi bi-shield-check"></i> LTI Free Shift Ongoing</span>
            @else
                <div class="k3-hero-safe-timer">Belum diatur</div>
                <span class="k3-pill pill-white"><i class="bi bi-info-circle"></i> Isi tanggal insiden LTI terakhir</span>
            @endif
        </div>
    </section>

    {{-- ===== KARTU RINGKAS ===== --}}
    <div class="row g-4 mb-4">

        <div class="col-sm-6 col-xl-3">
            <a href="{{ $bisaDokumen ? route('k3.dokumen.index') : '#' }}" class="k3-card k3-kpi-link d-block text-decoration-none">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="k3-kpi-icon is-red"><i class="bi bi-file-earmark-check"></i></div>
                    <i class="bi bi-arrow-up-right k3-kpi-arrow"></i>
                </div>
                <div class="k3-kpi-label">Dokumen Keselamatan</div>
                <div class="k3-kpi-value">{{ $stats['dokumen_aktif'] }}</div>
                <div class="k3-kpi-note">Dokumen berstatus aktif</div>
            </a>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="k3-card">
                <div class="mb-3"><div class="k3-kpi-icon is-navy"><i class="bi bi-wind"></i></div></div>
                <div class="k3-kpi-label">Pengukuran Lingkungan</div>
                <div class="k3-kpi-value">{{ $stats['pengukuran_total'] }}</div>
                <div class="k3-kpi-note">{{ $stats['titik_pengukuran'] }} titik pengukuran</div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="k3-card k3-kpi-alert">
                <div class="mb-3"><div class="k3-kpi-icon is-red"><i class="bi bi-fire"></i></div></div>
                <div class="k3-kpi-label">APAR Perlu Refill</div>
                <div class="k3-kpi-value">{{ $stats['apar_refill'] }}</div>
                <div class="k3-kpi-note">dari {{ $stats['apar_total'] }} tabung terpasang</div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="k3-card">
                <div class="mb-3"><div class="k3-kpi-icon is-black"><i class="bi bi-exclamation-triangle"></i></div></div>
                <div class="k3-kpi-label">Temuan Belum Selesai</div>
                <div class="k3-kpi-value">{{ $stats['temuan_open'] }}</div>
                <div class="k3-kpi-note">Menunggu tindakan perbaikan</div>
            </div>
        </div>

    </div>

    {{-- ===== RINGKASAN KINERJA + PERALATAN ===== --}}
    <div class="row g-4 mb-4">

        <div class="col-lg-7">
            <div class="k3-card">
                <h2 class="k3-card-title">Ringkasan Kinerja</h2>
                <p class="k3-card-sub">Pencapaian K3 tahun berjalan</p>

                <div class="row g-3 text-center">
                    <div class="col-4">
                        <div class="k3-ring mx-auto" style="--p: {{ $stats['program_persen'] }}; --c: var(--k3-red);">
                            <span>{{ $stats['program_persen'] }}%</span>
                        </div>
                        <div class="k3-ring-label">Program K3L</div>
                        <div class="k3-ring-sub">{{ $stats['program_selesai'] }} dari {{ $stats['program_total'] }} selesai</div>
                    </div>

                    <div class="col-4">
                        <div class="k3-ring mx-auto" style="--p: {{ $stats['patrol_sesuai'] }}; --c: var(--k3-navy);">
                            <span>{{ $stats['patrol_sesuai'] }}%</span>
                        </div>
                        <div class="k3-ring-label">Kepatuhan Patrol</div>
                        <div class="k3-ring-sub">{{ $stats['patrol_item'] }} item inspeksi</div>
                    </div>

                    <div class="col-4">
                        <div class="k3-ring mx-auto" style="--p: {{ $stats['apar_siap_persen'] }}; --c: var(--k3-black);">
                            <span>{{ $stats['apar_siap_persen'] }}%</span>
                        </div>
                        <div class="k3-ring-label">Kesiapan APAR</div>
                        <div class="k3-ring-sub">{{ $stats['apar_total'] - $stats['apar_refill'] }} dari {{ $stats['apar_total'] }} siap pakai</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="k3-card">
                <h2 class="k3-card-title">Peralatan Keselamatan</h2>
                <p class="k3-card-sub mb-2">Status APD, APAR &amp; P3K terintegrasi</p>

                <div class="k3-equip-row">
                    <div class="k3-kpi-icon is-navy flex-shrink-0"><i class="bi bi-shield-fill-check"></i></div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold">Alat Pelindung Diri</div>
                        <div class="small text-muted">{{ $stats['apd_jenis'] }} jenis &middot; stok {{ $stats['apd_stock'] }} unit</div>
                    </div>
                </div>

                <div class="k3-equip-row">
                    <div class="k3-kpi-icon is-red flex-shrink-0"><i class="bi bi-fire"></i></div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold">Proteksi Kebakaran</div>
                        <div class="small text-muted">{{ $stats['apar_total'] }} tabung APAR</div>
                    </div>
                    <span class="k3-pill pill-red">{{ $stats['apar_refill'] }} perlu refill</span>
                </div>

                <div class="k3-equip-row">
                    <div class="k3-kpi-icon is-black flex-shrink-0"><i class="bi bi-bandaid-fill"></i></div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold">Kotak &amp; Ruang P3K</div>
                        <div class="small text-muted">{{ $stats['p3k_area'] }} area &middot; {{ $stats['p3k_item'] }} item standar</div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ===== DOKUMEN TERBARU + AGENDA / PERHATIAN ===== --}}
    <div class="row g-4">

        <div class="col-lg-7">
            <div class="k3-card">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h2 class="k3-card-title">Dokumen Terbaru</h2>
                        <p class="k3-card-sub mb-0">5 dokumen keselamatan terakhir</p>
                    </div>
                    @if ($bisaDokumen)
                        <a href="{{ route('k3.dokumen.index') }}" class="k3-btn k3-btn-outline">Lihat semua</a>
                    @endif
                </div>

                @if ($dokumenTerbaru->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table k3-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Judul</th>
                                    <th>Kategori</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dokumenTerbaru as $dok)
                                    <tr>
                                        <td class="fw-semibold" style="color:var(--k3-navy)">
                                            <a href="{{ asset('storage/' . $dok->file) }}" target="_blank" rel="noopener" class="text-decoration-none" style="color:inherit">
                                                {{ $dok->judul }}
                                            </a>
                                        </td>
                                        <td>{{ $dok->kategori }}</td>
                                        <td>{{ $dok->tanggal->format('d/m/Y') }}</td>
                                        <td>
                                            @if ($dok->status === 'aktif')
                                                <span class="k3-pill pill-navy">Aktif</span>
                                            @else
                                                <span class="k3-pill pill-black">Arsip</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-folder2-open fs-2 d-block mb-2"></i>
                        Belum ada dokumen.
                        @if ($bisaDokumen)
                            <div class="mt-3">
                                <a href="{{ route('k3.dokumen.create') }}" class="k3-btn k3-btn-red"><i class="bi bi-plus-lg"></i> Tambah Dokumen</a>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="col-lg-5">
            <div class="k3-stack">

                <div class="k3-card">
                    <h2 class="k3-card-title">Perlu Perhatian</h2>
                    <p class="k3-card-sub mb-2">Hal yang perlu segera ditindaklanjuti</p>

                    <div class="k3-equip-row">
                        <div class="k3-kpi-icon is-red flex-shrink-0"><i class="bi bi-fire"></i></div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">APAR perlu refill</div>
                            <div class="small text-muted">Masa aktif habis dalam 90 hari</div>
                        </div>
                        <span class="k3-pill pill-red">{{ $stats['apar_refill'] }}</span>
                    </div>

                    <div class="k3-equip-row">
                        <div class="k3-kpi-icon is-navy flex-shrink-0"><i class="bi bi-exclamation-triangle"></i></div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">Temuan patrol belum selesai</div>
                            <div class="small text-muted">Menunggu tindakan perbaikan</div>
                        </div>
                        <span class="k3-pill pill-navy">{{ $stats['temuan_open'] }}</span>
                    </div>

                    <div class="k3-equip-row">
                        <div class="k3-kpi-icon is-black flex-shrink-0"><i class="bi bi-award"></i></div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">Sertifikat hampir kedaluwarsa</div>
                            <div class="small text-muted">Dalam 60 hari ke depan atau sudah lewat</div>
                        </div>
                        <span class="k3-pill pill-black">{{ $stats['sertifikat_hampir'] }}</span>
                    </div>
                </div>

                <div class="k3-card">
                    <h2 class="k3-card-title">Agenda / Reminders</h2>
                    <p class="k3-card-sub mb-2">Kegiatan K3 yang akan datang</p>

                    @forelse ($agenda as $item)
                        <div class="k3-equip-row">
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
        </div>

    </div>

    {{-- ===== CATATAN: MODUL YANG BELUM DIBUAT ===== --}}
    @if (! empty($modulBelumAktif))
        <div class="k3-card mt-4" style="height:auto; border-style:dashed;">
            <div class="d-flex gap-3 align-items-start">
                <div class="k3-kpi-icon is-navy flex-shrink-0"><i class="bi bi-info-circle"></i></div>
                <div>
                    <div class="fw-semibold" style="color:var(--k3-navy)">Sebagian angka masih 0</div>
                    <div class="small text-muted">
                        Modul berikut belum dibuat: {{ implode(', ', $modulBelumAktif) }}.
                        Angkanya terisi otomatis begitu modul itu dibuat dan datanya ditambahkan.
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection