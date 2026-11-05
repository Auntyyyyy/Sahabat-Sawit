@extends('admin.layouts.k3')

@section('title', 'Program K3L')
@section('page_title', 'Program K3L')

@push('styles')
<style>
    .pill-green{ background:rgba(25,135,84,.12); color:#146c43; }
</style>
@endpush

@php
    $tahunIni = (int) now('Asia/Jakarta')->format('Y');
    $adaFilter = request('status') || request('kategori') || request('q') || ($tahun !== (string) $tahunIni);

    // Warna label
    $kelasStatus   = ['rencana' => 'pill-black', 'berjalan' => 'pill-navy', 'selesai' => 'pill-green'];
    $kelasKategori = ['keselamatan' => 'pill-red', 'kesehatan' => 'pill-navy', 'lingkungan' => 'pill-green', 'lainnya' => 'pill-black'];

    // Perbandingan tanggal memakai teks Y-m-d supaya tidak terpengaruh zona waktu
    $hariStr = now('Asia/Jakarta')->toDateString();
@endphp

@section('content')

    @if (session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        </div>
    @endif

    {{-- ===== RINGKASAN (mengikuti tahun yang dipilih) ===== --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="k3-card">
                <div class="k3-kpi-label">Progres {{ $tahun !== '' ? $tahun : 'Semua Tahun' }}</div>
                <div class="k3-kpi-value" style="color:var(--k3-red)">{{ $ringkasan['persen'] }}%</div>
                <div class="progress mt-2" style="height:8px; border-radius:999px; background:#E8EBF2;">
                    <div class="progress-bar" role="progressbar"
                         style="width: {{ $ringkasan['persen'] }}%; background:var(--k3-red); border-radius:999px;"></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="k3-card">
                <div class="k3-kpi-label">Total Program</div>
                <div class="k3-kpi-value">{{ $ringkasan['total'] }}</div>
                <div class="small text-muted mt-1">{{ $ringkasan['selesai'] }} selesai</div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="k3-card">
                <div class="k3-kpi-label">Berjalan / Rencana</div>
                <div class="k3-kpi-value">{{ $ringkasan['berjalan'] }} <span class="fs-5 text-muted">/ {{ $ringkasan['rencana'] }}</span></div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="k3-card">
                <div class="k3-kpi-label">Terlambat</div>
                <div class="k3-kpi-value" style="color:var(--k3-red)">{{ $ringkasan['terlambat'] }}</div>
                <div class="small text-muted mt-1">belum selesai, target sudah lewat</div>
            </div>
        </div>
    </div>

    <div class="k3-card">

        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
            <div>
                <h2 class="k3-card-title" style="font-size:1.25rem">Program Kerja K3L</h2>
                <p class="k3-card-sub mb-0">Program keselamatan, kesehatan kerja, dan lingkungan beserta progresnya.</p>
            </div>
            <a href="{{ route('k3.program.create') }}" class="k3-btn k3-btn-red">
                <i class="bi bi-plus-lg"></i> Tambah Program
            </a>
        </div>

        {{-- ===== FILTER ===== --}}
        <form method="GET" action="{{ route('k3.program.index') }}" class="row g-3 align-items-end mb-4">
            <div class="col-md-2">
                <label class="k3-label" for="tahun">Tahun</label>
                <select name="tahun" id="tahun" class="form-select">
                    <option value="" @selected($tahun === '')>Semua tahun</option>
                    @foreach ($tahunList as $th)
                        <option value="{{ $th }}" @selected($tahun === (string) $th)>{{ $th }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label class="k3-label" for="status">Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="">Semua</option>
                    @foreach (\App\Models\K3Program::STATUS as $kunci => $label)
                        <option value="{{ $kunci }}" @selected(request('status') === $kunci)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label class="k3-label" for="kategori">Kategori</label>
                <select name="kategori" id="kategori" class="form-select">
                    <option value="">Semua</option>
                    @foreach (\App\Models\K3Program::KATEGORI as $kunci => $label)
                        <option value="{{ $kunci }}" @selected(request('kategori') === $kunci)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="k3-label" for="q">Cari</label>
                <input type="text" name="q" id="q" class="form-control" value="{{ request('q') }}" placeholder="Nama program / PIC">
            </div>

            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="k3-btn k3-btn-red flex-grow-1 justify-content-center">
                    <i class="bi bi-funnel"></i> Terapkan
                </button>
                @if ($adaFilter)
                    <a href="{{ route('k3.program.index') }}" class="k3-btn k3-btn-outline k3-btn-icon" title="Reset filter">
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
                        <th style="width:55px">No.</th>
                        <th>Program</th>
                        <th style="width:120px">Kategori</th>
                        <th>PIC</th>
                        <th style="width:150px">Target</th>
                        <th style="width:130px">Status</th>
                        <th style="width:110px">Selesai</th>
                        <th class="text-center" style="width:140px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($programs as $p)
                        @php
                            $target = $p->target_tanggal?->toDateString();
                            $terlambat = $p->status !== 'selesai' && $target && $target < $hariStr;
                        @endphp
                        <tr>
                            <td>{{ $programs->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="fw-semibold" style="color:var(--k3-navy)">{{ $p->nama }}</div>
                                @if ($p->keterangan)
                                    <div class="small text-muted">{{ \Illuminate\Support\Str::limit($p->keterangan, 70) }}</div>
                                @endif
                            </td>
                            <td><span class="k3-pill {{ $kelasKategori[$p->kategori] ?? 'pill-black' }}">{{ $p->kategori_label }}</span></td>
                            <td>{{ $p->pic ?: '-' }}</td>
                            <td>
                                @if ($target)
                                    {{ $p->target_tanggal->format('d/m/Y') }}
                                    @if ($terlambat)
                                        <span class="k3-pill pill-red ms-1">Terlambat</span>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td><span class="k3-pill {{ $kelasStatus[$p->status] ?? 'pill-black' }}">{{ $p->status_label }}</span></td>
                            <td>{{ $p->tanggal_selesai ? $p->tanggal_selesai->format('d/m/Y') : '-' }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    @if ($p->status !== 'selesai')
                                        <form action="{{ route('k3.program.selesai', $p) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="k3-btn k3-btn-outline k3-btn-icon" title="Tandai selesai">
                                                <i class="bi bi-check2-circle"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <a href="{{ route('k3.program.edit', $p) }}" class="k3-btn k3-btn-outline k3-btn-icon" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <form action="{{ route('k3.program.destroy', $p) }}" method="POST"
                                          onsubmit="return confirm('Hapus program ini?')">
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
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="bi bi-calendar-check fs-2 d-block mb-2"></i>
                                @if ($adaFilter)
                                    Tidak ada program yang cocok dengan filter.
                                @else
                                    Belum ada program untuk tahun {{ $tahunIni }}. Klik <strong>Tambah Program</strong> untuk mulai.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($programs->hasPages())
            <div class="mt-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="small text-muted">
                    Menampilkan {{ $programs->firstItem() }} - {{ $programs->lastItem() }} dari {{ $programs->total() }} program
                </div>
                {{ $programs->links('pagination::bootstrap-5') }}
            </div>
        @endif

    </div>

@endsection