@extends('admin.layouts.k3')

@section('title', 'Safety Patrol')
@section('page_title', 'Safety Patrol')

@php
    $bulanList = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];
    $adaFilter = request('hasil') || request('status') || request('bulan') || request('tahun') || request('q');
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
                <div class="k3-kpi-label">Total Item Inspeksi</div>
                <div class="k3-kpi-value">{{ $ringkasan['total'] }}</div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="k3-card">
                <div class="k3-kpi-label">Kepatuhan</div>
                <div class="k3-kpi-value">{{ $ringkasan['persen'] }}%</div>
                <div class="small text-muted mt-1">{{ $ringkasan['sesuai'] }} item sesuai</div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="k3-card">
                <div class="k3-kpi-label">Total Temuan</div>
                <div class="k3-kpi-value">{{ $ringkasan['temuan'] }}</div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="k3-card">
                <div class="k3-kpi-label">Temuan Belum Selesai</div>
                <div class="k3-kpi-value" style="color:var(--k3-red)">{{ $ringkasan['terbuka'] }}</div>
            </div>
        </div>
    </div>

    <div class="k3-card">

        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
            <div>
                <h2 class="k3-card-title" style="font-size:1.25rem">Hasil Safety Patrol</h2>
                <p class="k3-card-sub mb-0">Catat hasil inspeksi keselamatan dan pantau tindak lanjut temuan.</p>
            </div>
            <a href="{{ route('k3.patrol.create') }}" class="k3-btn k3-btn-red">
                <i class="bi bi-plus-lg"></i> Tambah Hasil Patrol
            </a>
        </div>

        {{-- ===== FILTER ===== --}}
        <form method="GET" action="{{ route('k3.patrol.index') }}" class="row g-3 align-items-end mb-4">
            <div class="col-md-2">
                <label class="k3-label" for="hasil">Hasil</label>
                <select name="hasil" id="hasil" class="form-select">
                    <option value="">Semua</option>
                    <option value="sesuai" @selected(request('hasil') === 'sesuai')>Sesuai</option>
                    <option value="temuan" @selected(request('hasil') === 'temuan')>Temuan</option>
                </select>
            </div>

            <div class="col-md-2">
                <label class="k3-label" for="status">Tindak Lanjut</label>
                <select name="status" id="status" class="form-select">
                    <option value="">Semua</option>
                    <option value="terbuka" @selected(request('status') === 'terbuka')>Terbuka</option>
                    <option value="selesai" @selected(request('status') === 'selesai')>Selesai</option>
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
                <input type="text" name="q" id="q" class="form-control" value="{{ request('q') }}" placeholder="Lokasi / uraian">
            </div>

            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="k3-btn k3-btn-red flex-grow-1 justify-content-center">
                    <i class="bi bi-funnel"></i> Terapkan
                </button>
                @if ($adaFilter)
                    <a href="{{ route('k3.patrol.index') }}" class="k3-btn k3-btn-outline k3-btn-icon" title="Reset filter">
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
                        <th style="width:110px">Tanggal</th>
                        <th>Lokasi</th>
                        <th>Uraian</th>
                        <th style="width:100px">Hasil</th>
                        <th style="width:120px">Tindak Lanjut</th>
                        <th class="text-center" style="width:70px">Foto</th>
                        <th class="text-center" style="width:150px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($patrols as $p)
                        <tr>
                            <td>{{ $patrols->firstItem() + $loop->index }}</td>
                            <td>{{ $p->tanggal->format('d/m/Y') }}</td>
                            <td class="fw-semibold" style="color:var(--k3-navy)">{{ $p->lokasi }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($p->uraian, 90) }}</td>
                            <td>
                                @if ($p->hasil === 'temuan')
                                    <span class="k3-pill pill-red">Temuan</span>
                                @else
                                    <span class="k3-pill pill-navy">Sesuai</span>
                                @endif
                            </td>
                            <td>
                                @if ($p->hasil === 'temuan')
                                    @if ($p->status_tindak === 'terbuka')
                                        <span class="k3-pill pill-red">Terbuka</span>
                                    @else
                                        <span class="k3-pill pill-black">Selesai</span>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($p->foto)
                                    <a href="{{ asset('storage/' . $p->foto) }}" target="_blank" rel="noopener"
                                       class="k3-btn k3-btn-outline k3-btn-icon" title="Lihat foto">
                                        <i class="bi bi-image"></i>
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    @if ($p->hasil === 'temuan' && $p->status_tindak === 'terbuka')
                                        <form action="{{ route('k3.patrol.selesai', $p) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="k3-btn k3-btn-outline k3-btn-icon" title="Tandai selesai">
                                                <i class="bi bi-check2-circle"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <a href="{{ route('k3.patrol.edit', $p) }}" class="k3-btn k3-btn-outline k3-btn-icon" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <form action="{{ route('k3.patrol.destroy', $p) }}" method="POST"
                                          onsubmit="return confirm('Hapus hasil patrol ini beserta fotonya?')">
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
                                <i class="bi bi-clipboard-check fs-2 d-block mb-2"></i>
                                @if ($adaFilter)
                                    Tidak ada data yang cocok dengan filter.
                                @else
                                    Belum ada hasil patrol. Klik <strong>Tambah Hasil Patrol</strong> untuk mulai.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($patrols->hasPages())
            <div class="mt-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="small text-muted">
                    Menampilkan {{ $patrols->firstItem() }} - {{ $patrols->lastItem() }} dari {{ $patrols->total() }} data
                </div>
                {{ $patrols->links('pagination::bootstrap-5') }}
            </div>
        @endif

    </div>

@endsection