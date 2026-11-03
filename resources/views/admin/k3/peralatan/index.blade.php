@extends('admin.layouts.k3')

@section('title', 'Peralatan')
@section('page_title', 'Peralatan Keselamatan')

@php
    $adaFilter = request('kategori') || request('kondisi') || request('status') || request('q');

    // Warna label
    $kelasKategori = ['APD' => 'pill-navy', 'APAR' => 'pill-red', 'P3K' => 'pill-black'];
    $kelasKondisi  = ['baik' => 'pill-navy', 'perlu_perbaikan' => 'pill-black', 'rusak' => 'pill-red'];

    // Perbandingan tanggal memakai teks Y-m-d supaya tidak terpengaruh zona waktu (batas 90 hari, sama dengan dashboard)
    $hariStr  = now('Asia/Jakarta')->toDateString();
    $batasStr = now('Asia/Jakarta')->addDays(\App\Models\K3Peralatan::BATAS_REFILL_HARI)->toDateString();
@endphp

@section('content')

    @if (session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        </div>
    @endif

    {{-- ===== RINGKASAN PER KATEGORI ===== --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <a href="{{ route('k3.peralatan.index', ['kategori' => 'APD']) }}" class="k3-card d-block text-decoration-none" style="color:inherit">
                <div class="d-flex align-items-center gap-3">
                    <div class="k3-kpi-icon is-navy"><i class="bi bi-shield-fill-check"></i></div>
                    <div>
                        <div class="k3-kpi-label">Alat Pelindung Diri</div>
                        <div class="fs-5 fw-bold" style="color:var(--k3-navy)">{{ $ringkasan['apd_jenis'] }} <span class="fs-6 fw-normal text-muted">jenis</span></div>
                        <div class="small text-muted">Stok {{ $ringkasan['apd_stok'] }} unit</div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="{{ route('k3.peralatan.index', ['kategori' => 'APAR']) }}" class="k3-card d-block text-decoration-none" style="color:inherit">
                <div class="d-flex align-items-center gap-3">
                    <div class="k3-kpi-icon is-red"><i class="bi bi-fire"></i></div>
                    <div>
                        <div class="k3-kpi-label">Proteksi Kebakaran (APAR)</div>
                        <div class="fs-5 fw-bold" style="color:var(--k3-navy)">{{ $ringkasan['apar_total'] }} <span class="fs-6 fw-normal text-muted">tabung</span></div>
                        <div class="small" style="color:var(--k3-red)"><strong>{{ $ringkasan['apar_refill'] }}</strong> perlu refill</div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="{{ route('k3.peralatan.index', ['kategori' => 'P3K']) }}" class="k3-card d-block text-decoration-none" style="color:inherit">
                <div class="d-flex align-items-center gap-3">
                    <div class="k3-kpi-icon is-black"><i class="bi bi-bandaid-fill"></i></div>
                    <div>
                        <div class="k3-kpi-label">Kotak &amp; Ruang P3K</div>
                        <div class="fs-5 fw-bold" style="color:var(--k3-navy)">{{ $ringkasan['p3k_kotak'] }} <span class="fs-6 fw-normal text-muted">kotak</span></div>
                        <div class="small text-muted">{{ $ringkasan['p3k_area'] }} area</div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="k3-card">

        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
            <div>
                <h2 class="k3-card-title" style="font-size:1.25rem">Daftar Peralatan</h2>
                <p class="k3-card-sub mb-0">APD, APAR, dan P3K. Peralatan yang paling cepat kedaluwarsa tampil paling atas.</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('k3.peralatan.create', ['kategori' => 'APAR']) }}" class="k3-btn k3-btn-red"><i class="bi bi-plus-lg"></i> APAR</a>
                <a href="{{ route('k3.peralatan.create', ['kategori' => 'APD']) }}" class="k3-btn k3-btn-red"><i class="bi bi-plus-lg"></i> APD</a>
                <a href="{{ route('k3.peralatan.create', ['kategori' => 'P3K']) }}" class="k3-btn k3-btn-red"><i class="bi bi-plus-lg"></i> P3K</a>
            </div>
        </div>

        {{-- ===== FILTER ===== --}}
        <form method="GET" action="{{ route('k3.peralatan.index') }}" class="row g-3 align-items-end mb-4">
            <div class="col-md-2">
                <label class="k3-label" for="kategori">Kategori</label>
                <select name="kategori" id="kategori" class="form-select">
                    <option value="">Semua</option>
                    @foreach (\App\Models\K3Peralatan::KATEGORI as $kunci => $label)
                        <option value="{{ $kunci }}" @selected(request('kategori') === $kunci)>{{ $kunci }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label class="k3-label" for="kondisi">Kondisi</label>
                <select name="kondisi" id="kondisi" class="form-select">
                    <option value="">Semua</option>
                    @foreach (\App\Models\K3Peralatan::KONDISI as $kunci => $label)
                        <option value="{{ $kunci }}" @selected(request('kondisi') === $kunci)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="k3-label" for="status">Kedaluwarsa</label>
                <select name="status" id="status" class="form-select">
                    <option value="">Semua</option>
                    <option value="kedaluwarsa" @selected(request('status') === 'kedaluwarsa')>Lewat atau dalam 90 hari</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="k3-label" for="q">Cari</label>
                <input type="text" name="q" id="q" class="form-control" value="{{ request('q') }}" placeholder="Nama / kode / lokasi">
            </div>

            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="k3-btn k3-btn-red flex-grow-1 justify-content-center">
                    <i class="bi bi-funnel"></i> Terapkan
                </button>
                @if ($adaFilter)
                    <a href="{{ route('k3.peralatan.index') }}" class="k3-btn k3-btn-outline k3-btn-icon" title="Reset filter">
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
                        <th style="width:90px">Kategori</th>
                        <th>Nama</th>
                        <th style="width:110px">Kode</th>
                        <th>Lokasi</th>
                        <th style="width:80px" class="text-center">Jumlah</th>
                        <th>Detail</th>
                        <th style="width:170px">Kedaluwarsa</th>
                        <th style="width:130px">Kondisi</th>
                        <th class="text-center" style="width:100px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($peralatans as $p)
                        @php
                            $exp = $p->tanggal_kedaluwarsa?->toDateString();
                            $lewat = $exp && $exp < $hariStr;
                            $dekat = $exp && ! $lewat && $exp <= $batasStr;
                            $detail = $p->kategori === 'APAR'
                                ? trim(($p->jenis_media ?: '') . ($p->jenis_media && $p->ukuran ? ' · ' : '') . ($p->ukuran ?: ''))
                                : ($p->ukuran ?: '');
                        @endphp
                        <tr>
                            <td>{{ $peralatans->firstItem() + $loop->index }}</td>
                            <td><span class="k3-pill {{ $kelasKategori[$p->kategori] ?? 'pill-navy' }}">{{ $p->kategori }}</span></td>
                            <td class="fw-semibold" style="color:var(--k3-navy)">{{ $p->nama }}</td>
                            <td>{{ $p->kode ?: '-' }}</td>
                            <td>{{ $p->lokasi ?: '-' }}</td>
                            <td class="text-center">{{ $p->jumlah }}</td>
                            <td>{{ $detail !== '' ? $detail : '-' }}</td>
                            <td>
                                @if ($exp)
                                    {{ $p->tanggal_kedaluwarsa->format('d/m/Y') }}
                                    @if ($lewat)
                                        <span class="k3-pill pill-red ms-1">Lewat</span>
                                    @elseif ($dekat)
                                        <span class="k3-pill pill-red ms-1">Segera</span>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td><span class="k3-pill {{ $kelasKondisi[$p->kondisi] ?? 'pill-navy' }}">{{ $p->kondisi_label }}</span></td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('k3.peralatan.edit', $p) }}" class="k3-btn k3-btn-outline k3-btn-icon" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <form action="{{ route('k3.peralatan.destroy', $p) }}" method="POST"
                                          onsubmit="return confirm('Hapus data peralatan ini?')">
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
                            <td colspan="10" class="text-center text-muted py-5">
                                <i class="bi bi-tools fs-2 d-block mb-2"></i>
                                @if ($adaFilter)
                                    Tidak ada data yang cocok dengan filter.
                                @else
                                    Belum ada data peralatan. Klik <strong>+ APAR</strong>, <strong>+ APD</strong>, atau <strong>+ P3K</strong> untuk mulai.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($peralatans->hasPages())
            <div class="mt-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="small text-muted">
                    Menampilkan {{ $peralatans->firstItem() }} - {{ $peralatans->lastItem() }} dari {{ $peralatans->total() }} data
                </div>
                {{ $peralatans->links('pagination::bootstrap-5') }}
            </div>
        @endif

    </div>

@endsection