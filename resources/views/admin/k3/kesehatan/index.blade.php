@extends('admin.layouts.k3')

@section('title', 'Monitoring Kesehatan')
@section('page_title', 'Monitoring Kesehatan')

@php
    $bulanList = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];
    $adaFilter = request('hasil') || request('jenis') || request('bulan') || request('tahun') || request('q') || request('tampil');

    // Warna label hasil kelayakan
    $kelasHasil = [
        'fit'                 => 'pill-navy',
        'fit_catatan'         => 'pill-black',
        'tidak_fit_sementara' => 'pill-red',
        'tidak_fit'           => 'pill-red',
    ];

    // Perbandingan jadwal memakai teks tanggal (Y-m-d) supaya tidak terpengaruh zona waktu
    $hariStr  = now('Asia/Jakarta')->toDateString();
    $batasStr = now('Asia/Jakarta')->addDays(30)->toDateString();
@endphp

@section('content')

    @if (session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        </div>
    @endif

    {{-- ===== RINGKASAN (berdasarkan pemeriksaan terbaru tiap karyawan) ===== --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="k3-card">
                <div class="k3-kpi-label">Karyawan Terpantau</div>
                <div class="k3-kpi-value">{{ $ringkasan['karyawan'] }}</div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="k3-card">
                <div class="k3-kpi-label">Fit (Termasuk Dengan Catatan)</div>
                <div class="k3-kpi-value">{{ $ringkasan['fit'] }}</div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="k3-card">
                <div class="k3-kpi-label">Tidak Fit</div>
                <div class="k3-kpi-value" style="color:var(--k3-red)">{{ $ringkasan['tidak_fit'] }}</div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="k3-card">
                <div class="k3-kpi-label">Jadwal MCU Dekat / Lewat</div>
                <div class="k3-kpi-value" style="color:var(--k3-red)">{{ $ringkasan['jatuh_tempo'] }}</div>
                <div class="small text-muted mt-1">dalam 30 hari ke depan</div>
            </div>
        </div>
    </div>

    <div class="k3-card">

        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
            <div>
                <h2 class="k3-card-title" style="font-size:1.25rem">Pemeriksaan Kesehatan Karyawan</h2>
                <p class="k3-card-sub mb-0">Pantau status kelayakan kerja dan jadwal pemeriksaan berikutnya.</p>
            </div>
            <a href="{{ route('k3.kesehatan.create') }}" class="k3-btn k3-btn-red">
                <i class="bi bi-plus-lg"></i> Tambah Pemeriksaan
            </a>
        </div>

        {{-- ===== FILTER ===== --}}
        <form method="GET" action="{{ route('k3.kesehatan.index') }}" class="row g-3 align-items-end mb-4">
            <div class="col-md-2">
                <label class="k3-label" for="tampil">Tampilan</label>
                <select name="tampil" id="tampil" class="form-select">
                    <option value="">Semua riwayat</option>
                    <option value="terbaru" @selected(request('tampil') === 'terbaru')>Terbaru per karyawan</option>
                </select>
            </div>

            <div class="col-md-2">
                <label class="k3-label" for="hasil">Hasil</label>
                <select name="hasil" id="hasil" class="form-select">
                    <option value="">Semua</option>
                    @foreach (\App\Models\K3Kesehatan::HASIL as $kunci => $label)
                        <option value="{{ $kunci }}" @selected(request('hasil') === $kunci)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label class="k3-label" for="jenis">Jenis</label>
                <select name="jenis" id="jenis" class="form-select">
                    <option value="">Semua</option>
                    @foreach (\App\Models\K3Kesehatan::JENIS as $kunci => $label)
                        <option value="{{ $kunci }}" @selected(request('jenis') === $kunci)>{{ $label }}</option>
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
                <input type="text" name="q" id="q" class="form-control" value="{{ request('q') }}" placeholder="Nama / jabatan">
            </div>

            <div class="col-12 d-flex gap-2">
                <button type="submit" class="k3-btn k3-btn-red">
                    <i class="bi bi-funnel"></i> Terapkan
                </button>
                @if ($adaFilter)
                    <a href="{{ route('k3.kesehatan.index') }}" class="k3-btn k3-btn-outline">
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
                        <th style="width:60px">No.</th>
                        <th style="width:110px">Tanggal</th>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th style="width:120px">Jenis</th>
                        <th style="width:160px">Hasil</th>
                        <th>Catatan</th>
                        <th style="width:150px">Jadwal Berikutnya</th>
                        <th class="text-center" style="width:100px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kesehatans as $k)
                        @php
                            $jadwal = $k->tanggal_berikutnya?->toDateString();
                            $terlambat = $jadwal && $jadwal < $hariStr;
                            $dekat = $jadwal && ! $terlambat && $jadwal <= $batasStr;
                        @endphp
                        <tr>
                            <td>{{ $kesehatans->firstItem() + $loop->index }}</td>
                            <td>{{ $k->tanggal->format('d/m/Y') }}</td>
                            <td class="fw-semibold" style="color:var(--k3-navy)">{{ $k->nama }}</td>
                            <td>{{ $k->jabatan ?: '-' }}</td>
                            <td>{{ $k->jenis_label }}</td>
                            <td><span class="k3-pill {{ $kelasHasil[$k->hasil] ?? 'pill-navy' }}">{{ $k->hasil_label }}</span></td>
                            <td>{{ $k->catatan ? \Illuminate\Support\Str::limit($k->catatan, 60) : '-' }}</td>
                            <td>
                                @if ($jadwal)
                                    {{ $k->tanggal_berikutnya->format('d/m/Y') }}
                                    @if ($terlambat)
                                        <span class="k3-pill pill-red ms-1">Lewat</span>
                                    @elseif ($dekat)
                                        <span class="k3-pill pill-red ms-1">Segera</span>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('k3.kesehatan.edit', $k) }}" class="k3-btn k3-btn-outline k3-btn-icon" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <form action="{{ route('k3.kesehatan.destroy', $k) }}" method="POST"
                                          onsubmit="return confirm('Hapus data pemeriksaan ini?')">
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
                            <td colspan="9" class="text-center text-muted py-5">
                                <i class="bi bi-heart-pulse fs-2 d-block mb-2"></i>
                                @if ($adaFilter)
                                    Tidak ada data yang cocok dengan filter.
                                @else
                                    Belum ada data pemeriksaan. Klik <strong>Tambah Pemeriksaan</strong> untuk mulai.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($kesehatans->hasPages())
            <div class="mt-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="small text-muted">
                    Menampilkan {{ $kesehatans->firstItem() }} - {{ $kesehatans->lastItem() }} dari {{ $kesehatans->total() }} data
                </div>
                {{ $kesehatans->links('pagination::bootstrap-5') }}
            </div>
        @endif

    </div>

@endsection