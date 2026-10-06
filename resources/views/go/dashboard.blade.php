@extends('layouts.admin')

@section('title', 'Dashboard General Affair')

@section('content')

@if(session('success'))
<div class="admin-alert--success">
    <i class="bi bi-check-circle-fill"></i>
    <span>{{ session('success') }}</span>
</div>
@endif

{{-- Hero dashboard — sengaja beda arah gradasi & warna aksen dari dashboard
     lain (cokelat/amber, bukan hijau), supaya GA terasa punya identitas
     visual sendiri meski strukturnya tetap konsisten (admin-card, dsb). --}}
<div class="dashboard-hero" style="background: linear-gradient(135deg, var(--sw-earth-brown, #8A6A45) 0%, var(--sw-dark-green, #164A2E) 100%);">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <p class="dashboard-hero__greeting mb-1">Selamat datang kembali,</p>
            <h1 class="text-white font-heading fw-bold mb-0" style="font-size: 1.5rem;">
                Dashboard General Affair
            </h1>
        </div>
        <a href="{{ route('go.assets.create') }}" class="btn-ssrs-primary">
            <i class="bi bi-plus-lg"></i> Tambah Aset
        </a>
    </div>
</div>

{{-- Kartu ringkasan --}}
<div class="row g-4 mt-1">
    <div class="col-sm-6 col-lg-3">
        <div class="admin-card stat-card d-flex align-items-center gap-3">
            <div class="stat-icon" style="background: var(--sw-earth-brown, #8A6A45);">
                <i class="bi bi-box-seam"></i>
            </div>
            <div>
                <p class="text-muted small mb-1">Total Aset</p>
                <h3 class="fw-bold mb-0">{{ $totalAset }}</h3>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="admin-card stat-card d-flex align-items-center gap-3">
            <div class="stat-icon stat-icon--green">
                <i class="bi bi-check-circle"></i>
            </div>
            <div>
                <p class="text-muted small mb-1">Kondisi Baik</p>
                <h3 class="fw-bold mb-0">{{ $asetKondisiBaik }}</h3>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="admin-card stat-card d-flex align-items-center gap-3">
            <div class="stat-icon" style="background: #C0392B;">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <div>
                <p class="text-muted small mb-1">Perlu Perhatian</p>
                <h3 class="fw-bold mb-0">{{ $asetPerluPerhatian }}</h3>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="admin-card stat-card d-flex align-items-center gap-3">
            <div class="stat-icon stat-icon--dark">
                <i class="bi bi-cash-stack"></i>
            </div>
            <div>
                <p class="text-muted small mb-1">Total Nilai Aset</p>
                <h3 class="fw-bold mb-0">Rp {{ number_format($totalNilaiAset, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-1">
    {{-- Aset per kategori --}}
    <div class="col-lg-5">
        <div class="admin-card h-100">
            <div class="admin-card-head">
                <div>
                    <h2 class="admin-card-title"><i class="bi bi-tags"></i> Aset per Kategori</h2>
                    <p class="admin-card-desc">Sebaran jumlah aset berdasarkan kategorinya.</p>
                </div>
            </div>

            @forelse($asetPerKategori as $row)
            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                <span>{{ $row->kategori }}</span>
                <span class="admin-count-badge">{{ $row->total }}</span>
            </div>
            @empty
            <div class="admin-empty">
                <i class="bi bi-inbox admin-empty-icon"></i>
                <p>Belum ada data aset.</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Aset terbaru --}}
    <div class="col-lg-7">
        <div class="admin-card h-100">
            <div class="admin-card-head">
                <div>
                    <h2 class="admin-card-title"><i class="bi bi-clock-history"></i> Aset Terbaru Ditambahkan</h2>
                    <p class="admin-card-desc">5 aset terakhir yang dicatat ke sistem.</p>
                </div>
                <a href="{{ route('go.assets.index') }}" class="page-action-btn">
                    Lihat Semua <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            @if($asetTerbaru->isNotEmpty())
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Kondisi</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($asetTerbaru as $asset)
                        <tr>
                            <td class="admin-table-name">{{ $asset->kode_aset }}</td>
                            <td>{{ $asset->nama }}</td>
                            <td><span class="admin-status {{ $asset->kondisiBadgeClass() }}">{{ $asset->kondisi }}</span></td>
                            <td class="text-end">
                                <a href="{{ route('go.assets.edit', $asset) }}" class="admin-action-btn">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="admin-empty">
                <i class="bi bi-inbox admin-empty-icon"></i>
                <p>Belum ada aset yang tercatat. <strong>Mulai tambahkan aset pertama kamu.</strong></p>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection