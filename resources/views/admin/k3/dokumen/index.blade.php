@extends('admin.layouts.k3')

@section('title', 'Dokumen Keselamatan')
@section('page_title', 'Dokumen Keselamatan')

@php
    $bulanList = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];
    $adaFilter = request()->hasAny(['kategori', 'bulan', 'tahun', 'q'])
        && (request('kategori') || request('bulan') || request('tahun') || request('q'));
@endphp

@section('content')

    @if (session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        </div>
    @endif

    <div class="k3-card">

        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
            <div>
                <h2 class="k3-card-title" style="font-size:1.25rem">Perpustakaan Dokumen Keselamatan</h2>
                <p class="k3-card-sub mb-0">Kelola dan akses semua dokumen kepatuhan K3.</p>
            </div>
            <a href="{{ route('k3.dokumen.create') }}" class="k3-btn k3-btn-red">
                <i class="bi bi-plus-lg"></i> Tambah Dokumen
            </a>
        </div>

        {{-- ===== FILTER ===== --}}
        <form method="GET" action="{{ route('k3.dokumen.index') }}" class="row g-3 align-items-end mb-4">
            <div class="col-md-3">
                <label class="k3-label" for="kategori">Kategori</label>
                <select name="kategori" id="kategori" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach ($kategoriList as $kat)
                        <option value="{{ $kat }}" @selected(request('kategori') === $kat)>{{ $kat }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label class="k3-label" for="bulan">Bulan</label>
                <select name="bulan" id="bulan" class="form-select">
                    <option value="">Semua Bulan</option>
                    @foreach ($bulanList as $num => $nama)
                        <option value="{{ $num }}" @selected((string) request('bulan') === (string) $num)>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label class="k3-label" for="tahun">Tahun</label>
                <select name="tahun" id="tahun" class="form-select">
                    <option value="">Semua Tahun</option>
                    @foreach ($tahunList as $th)
                        <option value="{{ $th }}" @selected((string) request('tahun') === (string) $th)>{{ $th }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="k3-label" for="q">Cari judul</label>
                <input type="text" name="q" id="q" class="form-control" value="{{ request('q') }}" placeholder="Ketik judul dokumen...">
            </div>

            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="k3-btn k3-btn-red flex-grow-1 justify-content-center">
                    <i class="bi bi-funnel"></i> Terapkan
                </button>
                @if ($adaFilter)
                    <a href="{{ route('k3.dokumen.index') }}" class="k3-btn k3-btn-outline k3-btn-icon" title="Reset filter">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </form>

        {{-- ===== TABEL ===== --}}
        <div class="table-responsive">
            <table class="table k3-table align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width:60px">No.</th>
                        <th>Judul Dokumen</th>
                        <th>Kategori</th>
                        <th style="width:130px">Tanggal</th>
                        <th style="width:100px">Status</th>
                        <th class="text-center" style="width:190px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dokumens as $dok)
                        <tr>
                            <td>{{ $dokumens->firstItem() + $loop->index }}</td>
                            <td class="fw-semibold" style="color:var(--k3-navy)">{{ $dok->judul }}</td>
                            <td>{{ $dok->kategori }}</td>
                            <td>{{ $dok->tanggal->format('d/m/Y') }}</td>
                            <td>
                                @if ($dok->status === 'aktif')
                                    <span class="k3-pill pill-navy">Aktif</span>
                                @else
                                    <span class="k3-pill pill-black">Arsip</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ asset('storage/' . $dok->file) }}" target="_blank" rel="noopener"
                                       class="k3-btn k3-btn-outline k3-btn-icon" title="Lihat PDF">
                                        <i class="bi bi-file-earmark-pdf"></i>
                                    </a>

                                    <a href="{{ route('k3.dokumen.edit', $dok) }}"
                                       class="k3-btn k3-btn-outline k3-btn-icon" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <form action="{{ route('k3.dokumen.status', $dok) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="k3-btn k3-btn-outline k3-btn-icon"
                                                title="{{ $dok->status === 'aktif' ? 'Pindahkan ke arsip' : 'Aktifkan kembali' }}">
                                            <i class="bi {{ $dok->status === 'aktif' ? 'bi-archive' : 'bi-arrow-counterclockwise' }}"></i>
                                        </button>
                                    </form>

                                    <form action="{{ route('k3.dokumen.destroy', $dok) }}" method="POST"
                                          onsubmit="return confirm('Hapus dokumen ini beserta file PDF-nya?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="k3-btn k3-btn-icon k3-btn-red" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="bi bi-folder2-open fs-2 d-block mb-2"></i>
                                @if ($adaFilter)
                                    Tidak ada dokumen yang cocok dengan filter.
                                @else
                                    Belum ada dokumen. Klik <strong>Tambah Dokumen</strong> untuk mulai.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($dokumens->hasPages())
            <div class="mt-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="small text-muted">
                    Menampilkan {{ $dokumens->firstItem() }} - {{ $dokumens->lastItem() }} dari {{ $dokumens->total() }} dokumen
                </div>
                {{ $dokumens->links('pagination::bootstrap-5') }}
            </div>
        @endif

    </div>

@endsection