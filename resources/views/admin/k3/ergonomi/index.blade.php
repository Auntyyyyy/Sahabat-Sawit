@extends('admin.layouts.k3')

@section('title', 'Ergonomi')
@section('page_title', 'Ergonomi')

@push('styles')
<style>
    .pill-green{ background:rgba(25,135,84,.12); color:#146c43; }
    .pill-amber{ background:rgba(217,119,6,.14); color:#92400e; }
    .pill-darkred{ background:var(--k3-red); color:#fff; }
</style>
@endpush

@php
    $bulanList = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];
    $adaFilter = request('metode') || request('tingkat') || request('status') || request('bulan') || request('tahun') || request('q');

    // Warna label tingkat risiko
    $kelasTingkat = [
        'rendah'        => 'pill-green',
        'sedang'        => 'pill-amber',
        'tinggi'        => 'pill-red',
        'sangat_tinggi' => 'pill-darkred',
    ];

    // Perbandingan tanggal memakai teks Y-m-d supaya tidak terpengaruh zona waktu
    $hariStr = now('Asia/Jakarta')->toDateString();
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
                <div class="k3-kpi-label">Total Penilaian</div>
                <div class="k3-kpi-value">{{ $ringkasan['total'] }}</div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="k3-card">
                <div class="k3-kpi-label">Risiko Tinggi</div>
                <div class="k3-kpi-value">{{ $ringkasan['tinggi'] }}</div>
                <div class="small text-muted mt-1">tinggi dan sangat tinggi</div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="k3-card">
                <div class="k3-kpi-label">Perlu Perhatian</div>
                <div class="k3-kpi-value" style="color:var(--k3-red)">{{ $ringkasan['perhatian'] }}</div>
                <div class="small text-muted mt-1">risiko tinggi, tindak lanjut belum selesai</div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="k3-card">
                <div class="k3-kpi-label">Tindak Lanjut Terlambat</div>
                <div class="k3-kpi-value" style="color:var(--k3-red)">{{ $ringkasan['terlambat'] }}</div>
                <div class="small text-muted mt-1">target tanggal sudah lewat</div>
            </div>
        </div>
    </div>

    <div class="k3-card">

        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
            <div>
                <h2 class="k3-card-title" style="font-size:1.25rem">Penilaian Risiko Ergonomi</h2>
                <p class="k3-card-sub mb-0">Catat penilaian per pekerjaan beserta tindak lanjutnya. Yang berisiko tinggi dan belum selesai tampil paling atas.</p>
            </div>
            <a href="{{ route('k3.ergonomi.create') }}" class="k3-btn k3-btn-red">
                <i class="bi bi-plus-lg"></i> Tambah Penilaian
            </a>
        </div>

        {{-- ===== FILTER ===== --}}
        <form method="GET" action="{{ route('k3.ergonomi.index') }}" class="row g-3 align-items-end mb-4">
            <div class="col-md-2">
                <label class="k3-label" for="metode">Metode</label>
                <select name="metode" id="metode" class="form-select">
                    <option value="">Semua</option>
                    @foreach (\App\Models\K3Ergonomi::METODE as $kunci => $label)
                        <option value="{{ $kunci }}" @selected(request('metode') === $kunci)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label class="k3-label" for="tingkat">Tingkat Risiko</label>
                <select name="tingkat" id="tingkat" class="form-select">
                    <option value="">Semua</option>
                    @foreach (\App\Models\K3Ergonomi::TINGKAT as $kunci => $label)
                        <option value="{{ $kunci }}" @selected(request('tingkat') === $kunci)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label class="k3-label" for="status">Tindak Lanjut</label>
                <select name="status" id="status" class="form-select">
                    <option value="">Semua</option>
                    @foreach (\App\Models\K3Ergonomi::STATUS_TINDAK as $kunci => $label)
                        <option value="{{ $kunci }}" @selected(request('status') === $kunci)>{{ $label }}</option>
                    @endforeach
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
                <label class="k3-label" for="q">Cari</label>
                <input type="text" name="q" id="q" class="form-control" value="{{ request('q') }}" placeholder="Pekerjaan / bagian">
            </div>

            <div class="col-12 d-flex gap-2">
                <button type="submit" class="k3-btn k3-btn-red">
                    <i class="bi bi-funnel"></i> Terapkan
                </button>
                @if ($adaFilter)
                    <a href="{{ route('k3.ergonomi.index') }}" class="k3-btn k3-btn-outline">
                        <i class="bi bi-x-lg"></i> Reset filter
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
                        <th>Pekerjaan</th>
                        <th style="width:120px">Metode</th>
                        <th style="width:140px">Risiko</th>
                        <th style="width:200px">Tindak Lanjut</th>
                        <th class="text-center" style="width:140px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ergonomis as $e)
                        @php
                            $target = $e->target_tanggal?->toDateString();
                            $terlambat = $e->status_tindak === 'terbuka' && $target && $target < $hariStr;
                            $lokasiBagian = trim(($e->bagian ?: '') . ($e->bagian && $e->lokasi ? ' · ' : '') . ($e->lokasi ?: ''));
                        @endphp
                        <tr>
                            <td>{{ $ergonomis->firstItem() + $loop->index }}</td>
                            <td>{{ $e->tanggal->format('d/m/Y') }}</td>
                            <td>
                                <div class="fw-semibold" style="color:var(--k3-navy)">{{ $e->pekerjaan }}</div>
                                @if ($lokasiBagian !== '')
                                    <div class="small text-muted">{{ $lokasiBagian }}</div>
                                @endif
                                @if ($e->rekomendasi)
                                    <div class="small text-muted"><i class="bi bi-lightbulb"></i> {{ \Illuminate\Support\Str::limit($e->rekomendasi, 70) }}</div>
                                @endif
                            </td>
                            <td>{{ $e->metode }}@if ($e->skor !== null) <span class="text-muted">· skor</span> <strong>{{ $e->skor }}</strong>@endif</td>
                            <td><span class="k3-pill {{ $kelasTingkat[$e->tingkat_risiko] ?? 'pill-black' }}">{{ $e->tingkat_label }}</span></td>
                            <td>
                                @if ($e->status_tindak === 'terbuka')
                                    <span class="k3-pill pill-red">Terbuka</span>
                                @else
                                    <span class="k3-pill pill-green">Selesai</span>
                                @endif

                                @if ($terlambat)
                                    <span class="k3-pill pill-darkred ms-1">Terlambat</span>
                                @endif

                                @if ($e->status_tindak === 'terbuka' && $target)
                                    <div class="small text-muted mt-1">Target {{ $e->target_tanggal->format('d/m/Y') }}</div>
                                @elseif ($e->status_tindak === 'selesai' && $e->tanggal_selesai)
                                    <div class="small text-muted mt-1">Selesai {{ $e->tanggal_selesai->format('d/m/Y') }}</div>
                                @endif

                                @if ($e->pic)
                                    <div class="small text-muted">PIC: {{ $e->pic }}</div>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    @if ($e->status_tindak === 'terbuka')
                                        <form action="{{ route('k3.ergonomi.selesai', $e) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="k3-btn k3-btn-outline k3-btn-icon" title="Tandai selesai">
                                                <i class="bi bi-check2-circle"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <a href="{{ route('k3.ergonomi.edit', $e) }}" class="k3-btn k3-btn-outline k3-btn-icon" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <form action="{{ route('k3.ergonomi.destroy', $e) }}" method="POST"
                                          onsubmit="return confirm('Hapus penilaian ergonomi ini?')">
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
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="bi bi-person-gear fs-2 d-block mb-2"></i>
                                @if ($adaFilter)
                                    Tidak ada data yang cocok dengan filter.
                                @else
                                    Belum ada penilaian ergonomi. Klik <strong>Tambah Penilaian</strong> untuk mulai.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($ergonomis->hasPages())
            <div class="mt-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="small text-muted">
                    Menampilkan {{ $ergonomis->firstItem() }} - {{ $ergonomis->lastItem() }} dari {{ $ergonomis->total() }} data
                </div>
                {{ $ergonomis->links('pagination::bootstrap-5') }}
            </div>
        @endif

    </div>

@endsection