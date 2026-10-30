@extends('admin.layouts.personalia')

@section('page-title', 'Cuti')
@section('page-subtitle', 'Rekap dan pemantauan cuti karyawan.')

@section('content')

    @php
        $inisial = fn ($nama) => strtoupper(mb_substr($nama ?: '?', 0, 1));
        $maxBulan = max(collect($perBulan)->max('jumlah') ?? 0, 1);
        $totalJenis = max($perJenis->sum(), 1);
        $adaFilter = request('search') || request('division');
    @endphp

    {{-- ================= JUDUL + PILIH TAHUN ================= --}}
    <div class="dash-head">
        <div>
            <h2 class="dash-greet">Rekap Cuti Tahun {{ $tahun }}</h2>
            <p class="dash-greet-sub">Satu hari cuti dihitung dari satu catatan kehadiran berstatus cuti.</p>
        </div>
        <div class="head-actions">
            <form method="GET" action="{{ route('personalia.cuti.index') }}">
                <select name="tahun" onchange="this.form.submit()" aria-label="Pilih tahun">
                    @foreach ($daftarTahun as $t)
                        <option value="{{ $t }}" {{ $t === $tahun ? 'selected' : '' }}>Tahun {{ $t }}</option>
                    @endforeach
                </select>
            </form>
            <a href="{{ route('personalia.absensi.create', ['status' => 'cuti']) }}" class="btn btn-primary">+ Catat cuti</a>
        </div>
    </div>

    {{-- ================= KARTU RINGKASAN ================= --}}
    <div class="cards-4">
        <div class="dash-card">
            <div class="dash-card-title">Sedang cuti hari ini</div>
            <div class="dash-big">{{ $jumlahSedangCuti }}</div>
            <div class="dash-sub">karyawan</div>
        </div>
        <div class="dash-card">
            <div class="dash-card-title">Hari cuti bulan ini</div>
            <div class="dash-big">{{ $hariBulanIni }}</div>
            <div class="dash-sub">{{ $today->translatedFormat('F Y') }}</div>
        </div>
        <div class="dash-card">
            <div class="dash-card-title">Hari cuti tahun {{ $tahun }}</div>
            <div class="dash-big">{{ $hariTahun }}</div>
            <div class="dash-sub">total hari</div>
        </div>
        <div class="dash-card">
            <div class="dash-card-title">Karyawan yang cuti</div>
            <div class="dash-big">{{ $karyawanCuti }}</div>
            <div class="dash-sub">pada tahun {{ $tahun }}</div>
        </div>
    </div>

    {{-- ================= SEDANG CUTI + CUTI MENDATANG ================= --}}
    <div class="two-col">
        <div class="panel">
            <div class="panel-head">
                <h2>Sedang Cuti Hari Ini</h2>
                <span class="count-badge">{{ $jumlahSedangCuti }}</span>
            </div>
            @forelse ($sedangCuti as $r)
                <div class="list-row">
                    <div class="avatar-mini">{{ $inisial($r->karyawan?->name) }}</div>
                    <div class="list-main">
                        <div class="list-name">{{ $r->karyawan?->name ?? '-' }}</div>
                        <div class="list-sub">{{ $r->karyawan?->division ?? '-' }}</div>
                    </div>
                    <div class="list-side">
                        <span class="status-pill cuti">{{ $r->jenis_cuti ?: 'Cuti' }}</span>
                    </div>
                </div>
            @empty
                <div class="empty-note">Tidak ada karyawan yang cuti hari ini.</div>
            @endforelse
            @if ($jumlahSedangCuti > $batasDaftar)
                <div class="list-more">+ {{ $jumlahSedangCuti - $batasDaftar }} lainnya</div>
            @endif
        </div>

        <div class="panel">
            <div class="panel-head">
                <h2>Cuti {{ $hariKeDepan }} Hari ke Depan</h2>
                <span class="count-badge">{{ $jumlahAkanCuti }}</span>
            </div>
            @forelse ($akanCuti as $r)
                <div class="list-row">
                    <div class="avatar-mini">{{ $inisial($r->karyawan?->name) }}</div>
                    <div class="list-main">
                        <div class="list-name">{{ $r->karyawan?->name ?? '-' }}</div>
                        <div class="list-sub">{{ $r->karyawan?->division ?? '-' }} &middot; {{ $r->jenis_cuti ?: 'Cuti' }}</div>
                    </div>
                    <div class="list-side">
                        <div class="list-strong">{{ $r->tanggal->translatedFormat('d M') }}</div>
                        <div class="list-sub">{{ $r->tanggal->translatedFormat('l') }}</div>
                    </div>
                </div>
            @empty
                <div class="empty-note">Belum ada cuti yang dijadwalkan dalam {{ $hariKeDepan }} hari ke depan.</div>
            @endforelse
            @if ($jumlahAkanCuti > $batasDaftar)
                <div class="list-more">+ {{ $jumlahAkanCuti - $batasDaftar }} lainnya</div>
            @endif
        </div>
    </div>

    {{-- ================= GRAFIK BULANAN + KOMPOSISI JENIS ================= --}}
    <div class="two-col">
        <div class="panel">
            <div class="panel-head"><h2>Hari Cuti per Bulan ({{ $tahun }})</h2></div>
            <div class="panel-body">
                <div class="vbar-chart">
                    @foreach ($perBulan as $b)
                        <div class="vbar-col">
                            <div class="vbar-value">{{ $b['jumlah'] }}</div>
                            <div class="vbar-track">
                                <div class="vbar-fill {{ $tahun === $today->year && $b['bulan'] === $today->month ? 'current' : '' }}" style="height: {{ round($b['jumlah'] / $maxBulan * 100) }}%;"></div>
                            </div>
                            <div class="vbar-label">{{ $b['label'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-head"><h2>Komposisi Jenis Cuti ({{ $tahun }})</h2></div>
            <div class="panel-body">
                @forelse ($perJenis as $jenis => $jumlah)
                    <div class="bar-row">
                        <div class="bar-label">
                            <span>{{ $jenis }}</span>
                            <span>{{ $jumlah }} hari ({{ round($jumlah / $totalJenis * 100) }}%)</span>
                        </div>
                        <div class="bar-track">
                            <div class="bar-fill" style="width: {{ round($jumlah / $totalJenis * 100) }}%;"></div>
                        </div>
                    </div>
                @empty
                    <div class="empty-note" style="padding: 2.5rem 0;">Belum ada cuti tercatat pada tahun {{ $tahun }}.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ================= REKAP PER KARYAWAN ================= --}}
    <div class="panel">
        <div class="panel-head">
            <h2>Rekap Cuti per Karyawan ({{ $tahun }})</h2>
            <span class="count-badge">{{ $rekap->total() }}</span>
        </div>

        <form method="GET" action="{{ route('personalia.cuti.index') }}" class="filter-bar">
            <input type="hidden" name="tahun" value="{{ $tahun }}">
            <input type="text" name="search" placeholder="Cari nama atau jabatan..." value="{{ request('search') }}">
            <select name="division">
                <option value="">Semua Divisi</option>
                @foreach ($divisions as $d)
                    <option value="{{ $d }}" {{ request('division') === $d ? 'selected' : '' }}>{{ $d }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary">Filter</button>
            @if ($adaFilter)
                <a href="{{ route('personalia.cuti.index', ['tahun' => $tahun]) }}" class="btn btn-secondary">Reset</a>
            @endif
        </form>

        @if ($rekap->isEmpty())
            <div class="empty-note">Belum ada data cuti yang cocok pada tahun {{ $tahun }}.</div>
        @else
            <div class="table-scroll">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Karyawan</th>
                            <th>Divisi</th>
                            <th>Total Hari</th>
                            @foreach ($jenisKolom as $label)
                                <th>{{ $label }}</th>
                            @endforeach
                            <th>Cuti Terakhir</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rekap as $r)
                            <tr>
                                <td>
                                    <div class="person">
                                        <div class="avatar-mini">{{ $inisial($r->name) }}</div>
                                        <div>
                                            <div class="person-name">{{ $r->name }}</div>
                                            <div class="person-sub">{{ $r->position }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $r->division }}</td>
                                <td><span class="total-pill">{{ $r->total }} hari</span></td>
                                @foreach ($jenisKolom as $i => $label)
                                    @php $n = (int) $r->{'j' . $i}; @endphp
                                    <td>{{ $n > 0 ? $n : '-' }}</td>
                                @endforeach
                                <td>{{ \Illuminate\Support\Carbon::parse($r->terakhir)->translatedFormat('d M Y') }}</td>
                                <td>
                                    <div class="row-actions">
                                        <a href="{{ route('personalia.karyawan.show', $r->karyawan_id) }}" class="btn-aksi btn-aksi-lihat" title="Lihat detail karyawan">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                            Detail
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="padding: 1.25rem 1.5rem;">
                {{ $rekap->links() }}
            </div>
        @endif
    </div>

@endsection

@section('extra-style')
    /* ===== Judul ===== */
    .dash-head { display: flex; align-items: flex-end; justify-content: space-between; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
    .dash-greet { font-size: 1.5rem; color: var(--navy); }
    .dash-greet-sub { margin: .3rem 0 0; font-size: .88rem; color: var(--muted); }
    .head-actions { display: flex; align-items: center; gap: .75rem; flex-wrap: wrap; }
    .head-actions select { padding: .58rem .85rem; border: 1px solid var(--line); border-radius: 8px; font-size: .85rem; font-family: 'Inter', sans-serif; background: #fff; }

    /* ===== Kartu ringkasan ===== */
    .cards-4 { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 1.75rem; }
    .dash-card { background: var(--card); border: 1px solid var(--line); border-radius: 12px; padding: 1.25rem 1.4rem; }
    .dash-card-title { font-size: .82rem; font-weight: 600; color: var(--muted); margin-bottom: .6rem; }
    .dash-big { font-family: 'Poppins', sans-serif; font-size: 2.1rem; font-weight: 700; color: var(--navy); line-height: 1.15; }
    .dash-sub { font-size: .8rem; color: var(--muted); margin-top: .15rem; }

    .count-badge { display: inline-block; min-width: 26px; text-align: center; padding: .2rem .6rem; border-radius: 999px; background: #E4E9F3; color: var(--blue); font-size: .78rem; font-weight: 700; }

    /* ===== Daftar ===== */
    .list-row { display: flex; align-items: center; gap: .85rem; padding: .8rem 1.5rem; }
    .list-row + .list-row { border-top: 1px solid var(--line); }
    .avatar-mini { width: 34px; height: 34px; border-radius: 50%; background: #E4E9F3; color: var(--blue); display: flex; align-items: center; justify-content: center; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: .82rem; flex-shrink: 0; }
    .list-main { flex: 1; min-width: 0; }
    .list-name { font-size: .88rem; font-weight: 600; color: var(--ink); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .list-sub { font-size: .76rem; color: var(--muted); }
    .list-side { text-align: right; flex-shrink: 0; }
    .list-strong { font-size: .86rem; font-weight: 600; color: var(--navy); }
    .list-more { padding: .75rem 1.5rem; border-top: 1px solid var(--line); font-size: .8rem; color: var(--muted); text-align: center; }

    /* ===== Grafik batang tegak ===== */
    .vbar-chart { display: flex; gap: .5rem; height: 220px; align-items: stretch; }
    .vbar-col { flex: 1; min-width: 0; display: flex; flex-direction: column; align-items: center; gap: .35rem; }
    .vbar-value { font-size: .72rem; font-weight: 600; color: var(--navy); }
    .vbar-track { flex: 1; width: 100%; max-width: 30px; background: #EEF0F3; border-radius: 8px; display: flex; align-items: flex-end; overflow: hidden; }
    .vbar-fill { width: 100%; background: #C3CCDD; border-radius: 8px 8px 0 0; }
    .vbar-fill.current { background: var(--blue); }
    .vbar-label { font-size: .72rem; color: var(--ink); font-weight: 500; }

    /* ===== Tabel rekap ===== */
    .table-scroll { overflow-x: auto; }
    .table-scroll table.data-table { min-width: 960px; }
    .table-scroll table.data-table th,
    .table-scroll table.data-table td { padding-left: 1rem; padding-right: 1rem; white-space: nowrap; }
    .table-scroll table.data-table th:first-child,
    .table-scroll table.data-table td:first-child { padding-left: 1.5rem; }
    .table-scroll table.data-table th:last-child,
    .table-scroll table.data-table td:last-child { padding-right: 1.5rem; }

    .person { display: flex; align-items: center; gap: .75rem; }
    .person-name { font-weight: 600; color: var(--ink); }
    .person-sub { font-size: .78rem; color: var(--muted); }
    .total-pill { display: inline-block; padding: .25rem .65rem; border-radius: 6px; background: #E4E9F3; color: var(--blue); font-size: .78rem; font-weight: 700; }

    /* ===== Tombol aksi ===== */
    table.data-table .row-actions { align-items: center; gap: .5rem; }
    table.data-table .row-actions .btn-aksi {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .4rem .75rem; border-radius: 8px; border: 1px solid transparent;
        font-family: 'Inter', sans-serif; font-size: .78rem; font-weight: 600; line-height: 1;
        cursor: pointer; transition: background .15s, color .15s, border-color .15s;
    }
    table.data-table .row-actions .btn-aksi svg { width: 14px; height: 14px; flex-shrink: 0; }
    table.data-table .row-actions .btn-aksi-lihat { background: #ECEEF2; color: var(--ink); border-color: #DADDE2; }
    table.data-table .row-actions .btn-aksi-lihat:hover { background: var(--blue); color: #fff; border-color: var(--blue); }
@endsection