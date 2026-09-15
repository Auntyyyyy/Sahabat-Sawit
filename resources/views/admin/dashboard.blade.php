@extends('layouts.admin')

@section('content')
<div class="admin-dashboard">

    {{-- Header Sambutan --}}
    <div class="dashboard-hero d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <p class="dashboard-hero__greeting mb-1" id="greetingText">Selamat datang kembali,</p>
            <h3 class="fw-bold mb-1 text-white">{{ Auth::user()->name ?? 'Administrator' }} 👋</h3>
            <p class="text-white-50 mb-0">
                Panel Admin PT. Sahabat Sawit Rokan Sejahtera
            </p>
        </div>

        <div class="mt-3 mt-md-0">
            <div class="dashboard-hero__clock">
                <div class="small text-white-50">Waktu Sekarang</div>
                <div class="fw-semibold text-white" id="currentDateTime">Memuat waktu...</div>
            </div>
        </div>
    </div>

    {{-- Statistik --}}
    <div class="row g-3 mb-4">

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted small mb-1">Total Produk</div>
                            <h2 class="fw-bold mb-0">{{ $stats['total_products'] ?? 0 }}</h2>
                            <small class="text-muted">produk terdaftar</small>
                        </div>
                        <div class="stat-icon stat-icon--green">
                            <i class="bi bi-box-seam"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted small mb-1">Halaman Statis Terisi</div>
                            <h2 class="fw-bold mb-0">{{ $stats['total_pages'] ?? 0 }}</h2>
                            <small class="text-muted">halaman aktif</small>
                        </div>
                        <div class="stat-icon stat-icon--dark">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted small mb-1">Total Artikel</div>
                            <h2 class="fw-bold mb-0">{{ $stats['total_articles'] ?? 0 }}</h2>
                            <small class="text-muted">artikel dipublikasikan</small>
                        </div>
                        <div class="stat-icon stat-icon--gold">
                            <i class="bi bi-newspaper"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted small mb-1">Pesan Masuk</div>
                            <h2 class="fw-bold mb-0">{{ $stats['total_messages'] ?? 0 }}</h2>
                            @if(($stats['unread_messages'] ?? 0) > 0)
                                <span class="badge dashboard-badge-unread">{{ $stats['unread_messages'] }} belum dibaca</span>
                            @else
                                <small class="text-muted">semua sudah dibaca</small>
                            @endif
                        </div>
                        <div class="stat-icon stat-icon--brown">
                            <i class="bi bi-envelope"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Aksi Cepat --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h6 class="fw-bold text-muted mb-3">Aksi Cepat</h6>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ Route::has('admin.products.create') ? route('admin.products.create') : '#' }}" class="quick-action-btn">
                    <i class="bi bi-plus-circle"></i> Tambah Produk
                </a>
                <a href="{{ Route::has('admin.articles.create') ? route('admin.articles.create') : '#' }}" class="quick-action-btn">
                    <i class="bi bi-pencil-square"></i> Tulis Artikel
                </a>
                <a href="{{ Route::has('admin.pages.index') ? route('admin.pages.index') : '#' }}" class="quick-action-btn">
                    <i class="bi bi-file-earmark-richtext"></i> Kelola Halaman
                </a>
                <a href="{{ Route::has('admin.messages.index') ? route('admin.messages.index') : '#' }}" class="quick-action-btn">
                    <i class="bi bi-envelope-open"></i> Lihat Pesan
                </a>
            </div>
        </div>
    </div>

    {{-- Informasi Website --}}
    <div class="row g-3">

        {{-- Aktivitas Terbaru --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Aktivitas Terbaru</h5>
                    @if(isset($activities) && count($activities) > 0)
                        <span class="text-muted small">{{ count($activities) }} aktivitas</span>
                    @endif
                </div>

                <div class="card-body">
                    @if(isset($activities) && count($activities) > 0)
                        <div class="activity-timeline">
                            @foreach($activities as $activity)
                                <div class="activity-item">
                                    <div class="activity-item__dot"></div>
                                    <div class="activity-item__content">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="fw-semibold">{{ $activity->title }}</div>
                                            <small class="text-muted text-nowrap ms-2">{{ $activity->created_at->diffForHumans() }}</small>
                                        </div>
                                        <small class="text-muted">{{ $activity->description }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-clock-history fs-1 text-muted"></i>
                            <p class="text-muted mt-2 mb-0">Belum ada aktivitas terbaru.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Informasi Perusahaan --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-3">
                    <h5 class="fw-bold mb-0">Informasi Website</h5>
                </div>

                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">Nama Perusahaan</small>
                        <span class="fw-semibold">PT. Sahabat Sawit Rokan Sejahtera</span>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block">Lokasi</small>
                        <span class="fw-semibold">KM. 1, Pujud, Rokan Hilir</span>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block">Status Website</small>
                        <span class="badge dashboard-badge-active">
                            <i class="bi bi-check-circle-fill"></i> Aktif
                        </span>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Login Sebagai</small>
                        <span class="fw-semibold">{{ Auth::user()->name ?? 'Administrator' }}</span>
                    </div>

                    <a href="{{ route('home') }}" target="_blank" class="btn btn-sm dashboard-btn-outline w-100">
                        <i class="bi bi-box-arrow-up-right"></i> Kunjungi Website
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Script Waktu & Sapaan --}}
<script>
    function updateDateTime() {
        const now = new Date();
        const options = {
            weekday: 'long', year: 'numeric', month: 'long',
            day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit'
        };
        document.getElementById('currentDateTime').textContent = now.toLocaleDateString('id-ID', options);

        const hour = now.getHours();
        let greeting = 'Selamat malam,';
        if (hour >= 4 && hour < 11) greeting = 'Selamat pagi,';
        else if (hour >= 11 && hour < 15) greeting = 'Selamat siang,';
        else if (hour >= 15 && hour < 18) greeting = 'Selamat sore,';
        document.getElementById('greetingText').textContent = greeting;
    }

    updateDateTime();
    setInterval(updateDateTime, 1000);
</script>
@endsection