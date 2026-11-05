@extends('admin.layouts.k3')

@section('title', 'Pengukuran Lingkungan')
@section('page_title', 'Pengukuran Lingkungan')

@push('styles')
<style>
    .pill-green{ background:rgba(25,135,84,.12); color:#146c43; }
</style>
@endpush

@php
    $bulanList = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];
    $adaFilter = request('parameter') || request('kepatuhan') || request('bulan') || request('tahun') || request('q');
@endphp

@section('content')

    @if (session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        </div>
    @endif

    {{-- ===== RINGKASAN ===== --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="k3-card">
                <div class="k3-kpi-label">Total Pengukuran</div>
                <div class="k3-kpi-value">{{ $ringkasan['total'] }}</div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="k3-card">
                <div class="k3-kpi-label">Titik Pengukuran</div>
                <div class="k3-kpi-value">{{ $ringkasan['titik'] }}</div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="k3-card">
                <div class="k3-kpi-label">Kepatuhan</div>
                <div class="k3-kpi-value">{{ $ringkasan['persen'] }}%</div>
                <div class="small text-muted mt-1">dari {{ $ringkasan['dengan_batas'] }} pengukuran yang punya nilai batas</div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="k3-card">
                <div class="k3-kpi-label">Tidak Memenuhi</div>
                <div class="k3-kpi-value" style="color:var(--k3-red)">{{ $ringkasan['tidak'] }}</div>
            </div>
        </div>
    </div>

    <div class="k3-card">

        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
            <div>
                <h2 class="k3-card-title" style="font-size:1.25rem">Hasil Pengukuran Lingkungan Kerja</h2>
                <p class="k3-card-sub mb-0">Catat hasil ukur kebisingan, pencahayaan, suhu, debu, dan parameter lain beserta nilai batasnya.</p>
            </div>
            <a href="{{ route('k3.pengukuran.create') }}" class="k3-btn k3-btn-red">
                <i class="bi bi-plus-lg"></i> Tambah Pengukuran
            </a>
        </div>

        {{-- ===== FILTER ===== --}}
        <form method="GET" action="{{ route('k3.pengukuran.index') }}" class="row g-3 align-items-end mb-4">
            <div class="col-md-2">
                <label class="k3-label" for="parameter">Parameter</label>
                <select name="parameter" id="parameter" class="form-select">
                    <option value="">Semua</option>
                    @foreach ($parameterList as $par)
                        <option value="{{ $par }}" @selected(request('parameter') === $par)>{{ $par }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label class="k3-label" for="kepatuhan">Penilaian</label>
                <select name="kepatuhan" id="kepatuhan" class="form-select">
                    <option value="">Semua</option>
                    <option value="memenuhi" @selected(request('kepatuhan') === 'memenuhi')>Memenuhi</option>
                    <option value="tidak" @selected(request('kepatuhan') === 'tidak')>Tidak memenuhi</option>
                </select>
            </div>

            <div class="col-md-2">
                <label class="k3-label" for="bulan">Bulan</label>
                <select name="bulan" id="bulan" class="form-select">
                    <option value="">Semua</option>
                    @foreach ($bulanList as $num => $nama)
                        <option value="{{ $num }}" @selected((string) request('bulan') === (string) $num)>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label class="k3-label" for="tahun">Tahun</label>
                <select name="tahun" id="tahun" class="form-select">
                    <option value="">Semua</option>
                    @foreach ($tahunList as $th)
                        <option value="{{ $th }}" @selected((string) request('tahun') === (string) $th)>{{ $th }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label class="k3-label" for="q">Cari titik</label>
                <input type="text" name="q" id="q" class="form-control" value="{{ request('q') }}" placeholder="Nama titik / lokasi">
            </div>

            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="k3-btn k3-btn-red flex-grow-1 justify-content-center">
                    <i class="bi bi-funnel"></i> Terapkan
                </button>
                @if ($adaFilter)
                    <a href="{{ route('k3.pengukuran.index') }}" class="k3-btn k3-btn-outline k3-btn-icon" title="Reset filter">
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
                        <th style="width:110px">Tanggal</th>
                        <th>Titik</th>
                        <th>Parameter</th>
                        <th style="width:130px">Hasil</th>
                        <th style="width:130px">Batas</th>
                        <th style="width:140px">Penilaian</th>
                        <th class="text-center" style="width:100px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pengukurans as $p)
                        <tr>
                            <td>{{ $pengukurans->firstItem() + $loop->index }}</td>
                            <td>{{ $p->tanggal->format('d/m/Y') }}</td>
                            <td class="fw-semibold" style="color:var(--k3-navy)">
                                {{ $p->titik }}
                                @if ($p->keterangan)
                                    <div class="small fw-normal text-muted">{{ \Illuminate\Support\Str::limit($p->keterangan, 60) }}</div>
                                @endif
                            </td>
                            <td>{{ $p->parameter }}</td>
                            <td class="fw-semibold">{{ $p->nilai_tampil }} {{ $p->satuan }}</td>
                            <td>
                                @if ($p->nilai_batas !== null)
                                    {{ $p->arah_batas === 'minimum' ? '≥' : '≤' }} {{ $p->batas_tampil }} {{ $p->satuan }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if ($p->kepatuhan === 'memenuhi')
                                    <span class="k3-pill pill-green"><i class="bi bi-check-circle"></i> Memenuhi</span>
                                @elseif ($p->kepatuhan === 'tidak_memenuhi')
                                    <span class="k3-pill pill-red"><i class="bi bi-exclamation-triangle"></i> Tidak memenuhi</span>
                                @else
                                    <span class="text-muted">Tanpa batas</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('k3.pengukuran.edit', $p) }}" class="k3-btn k3-btn-outline k3-btn-icon" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <form action="{{ route('k3.pengukuran.destroy', $p) }}" method="POST"
                                          onsubmit="return confirm('Hapus hasil pengukuran ini?')">
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
                                <i class="bi bi-wind fs-2 d-block mb-2"></i>
                                @if ($adaFilter)
                                    Tidak ada data yang cocok dengan filter.
                                @else
                                    Belum ada hasil pengukuran. Klik <strong>Tambah Pengukuran</strong> untuk mulai.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($pengukurans->hasPages())
            <div class="mt-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="small text-muted">
                    Menampilkan {{ $pengukurans->firstItem() }} - {{ $pengukurans->lastItem() }} dari {{ $pengukurans->total() }} data
                </div>
                {{ $pengukurans->links('pagination::bootstrap-5') }}
            </div>
        @endif

    </div>

@endsection