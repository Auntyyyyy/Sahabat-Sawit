@extends('admin.layouts.personalia')

@section('page-title', 'Performance')
@section('page-subtitle', 'Pantau dan kelola penilaian kinerja karyawan per bulan.')

@section('content')

    @php
        $inisial = fn ($nama) => strtoupper(mb_substr($nama ?: '?', 0, 1));
        $fmt = fn ($v) => rtrim(rtrim(number_format((float) $v, 2, ',', '.'), '0'), ',');
        $fmtSkor = fn ($v) => number_format((float) $v, 1, ',', '.');

        $periodeYm = $periode->format('Y-m');
        $adaFilter = request('search') || request('division') || request('kategori');
        $sisaFilter = request()->except(['page', 'periode']);
        $linkBulan = fn ($p) => route('personalia.performance.index', array_merge($sisaFilter, ['periode' => $p->format('Y-m')]));

        // Warna batang pencapaian mengikuti kategori
        $kelasBatang = ['sangat' => 'green', 'baik' => '', 'cukup' => 'amber', 'perlu' => 'red'];
    @endphp

    {{-- ================= JUDUL + TOMBOL ================= --}}
    <div class="dash-head">
        <div>
            <h2 class="dash-greet">Penilaian {{ $periode->translatedFormat('F Y') }}</h2>
            <p class="dash-greet-sub">Pencapaian dihitung dari realisasi dibagi target. Skor 1 sampai 5 diisi oleh penilai.</p>
        </div>
        <a href="{{ route('personalia.performance.create', ['periode' => $periodeYm]) }}" class="btn btn-primary">+ Tambah penilaian</a>
    </div>

    {{-- ================= RINGKASAN ================= --}}
    <div class="tiles">
        <div class="tile">
            <div class="tile-label">Sudah dinilai</div>
            <div class="tile-value">{{ $dinilai }}<small>dari {{ $karyawanAktif }}</small></div>
        </div>
        <div class="tile">
            <div class="tile-label">Rata-rata pencapaian</div>
            <div class="tile-value">{{ $rataPencapaian }}%</div>
            @if ($deltaPencapaian !== null)
                <div class="tile-sub {{ $deltaPencapaian >= 0 ? 'naik' : 'turun' }}">
                    {{ $deltaPencapaian >= 0 ? '▲' : '▼' }} {{ abs($deltaPencapaian) }}% dari bulan lalu
                </div>
            @endif
        </div>
        <div class="tile">
            <div class="tile-label">Rata-rata skor</div>
            <div class="tile-value">{{ $fmtSkor($rataSkor) }}<small>dari 5</small></div>
        </div>
        <div class="tile">
            <div class="tile-label">Target tercapai</div>
            <div class="tile-value">{{ $targetTercapai }}<small>orang</small></div>
            <div class="tile-sub">pencapaian 85% ke atas</div>
        </div>
        <div class="tile">
            <div class="tile-label">Top performer</div>
            <div class="tile-value">{{ $topPerformerCount }}<small>orang</small></div>
            <div class="tile-sub">skor 4,5 ke atas</div>
        </div>
    </div>

    @if ($jumlahBelumDinilai > 0)
        <div class="notice">
            <span><strong>{{ $jumlahBelumDinilai }}</strong> karyawan aktif belum dinilai pada bulan ini.</span>
            <a href="{{ route('personalia.performance.create', ['periode' => $periodeYm]) }}">Nilai sekarang</a>
        </div>
    @endif

    {{-- ================= TOP 5 + BELUM DINILAI ================= --}}
    <div class="two-col">
        <div class="panel">
            <div class="panel-head">
                <h2>Top 5 Performer</h2>
            </div>
            @forelse ($topPerformers as $i => $p)
                <div class="list-row">
                    <div class="rank">{{ $i + 1 }}</div>
                    <div class="avatar-mini">{{ $inisial($p->karyawan?->name) }}</div>
                    <div class="list-main">
                        <div class="list-name">{{ $p->karyawan?->name ?? '-' }}</div>
                        <div class="list-sub">{{ $p->karyawan?->division ?? '-' }}</div>
                    </div>
                    <div class="list-side">
                        <div class="list-strong">&#9733; {{ $fmtSkor($p->skor) }}</div>
                        <div class="list-sub">{{ round($p->pencapaian) }}% pencapaian</div>
                    </div>
                </div>
            @empty
                <div class="empty-note">Belum ada penilaian pada bulan ini.</div>
            @endforelse
        </div>

        <div class="panel">
            <div class="panel-head">
                <h2>Belum Dinilai</h2>
                <span class="count-badge">{{ $jumlahBelumDinilai }}</span>
            </div>
            @forelse ($belumDinilai as $k)
                <div class="list-row">
                    <div class="avatar-mini">{{ $inisial($k->name) }}</div>
                    <div class="list-main">
                        <div class="list-name">{{ $k->name }}</div>
                        <div class="list-sub">{{ $k->division }}</div>
                    </div>
                    <div class="list-side">
                        <a href="{{ route('personalia.performance.create', ['karyawan_id' => $k->id, 'periode' => $periodeYm]) }}" class="action-view">Nilai</a>
                    </div>
                </div>
            @empty
                <div class="empty-note">Semua karyawan aktif sudah dinilai bulan ini.</div>
            @endforelse
            @if ($jumlahBelumDinilai > 8)
                <div class="list-more">+ {{ $jumlahBelumDinilai - 8 }} lainnya</div>
            @endif
        </div>
    </div>

    {{-- ================= TABEL PENILAIAN ================= --}}
    <div class="panel">
        <div class="panel-head">
            <h2>Daftar Penilaian &middot; {{ $periode->translatedFormat('F Y') }}</h2>
            <span class="count-badge">{{ $records->total() }}</span>
        </div>

        <form method="GET" action="{{ route('personalia.performance.index') }}" class="filter-bar">
            <a href="{{ $linkBulan($periode->copy()->subMonth()) }}" class="btn btn-secondary" aria-label="Bulan sebelumnya">&larr;</a>
            <input type="month" name="periode" value="{{ $periodeYm }}" style="flex: 0 0 auto; min-width: 0;">
            <a href="{{ $linkBulan($periode->copy()->addMonth()) }}" class="btn btn-secondary" aria-label="Bulan berikutnya">&rarr;</a>

            <input type="text" name="search" placeholder="Cari nama atau jabatan..." value="{{ request('search') }}">

            <select name="division">
                <option value="">Semua Divisi</option>
                @foreach ($divisions as $d)
                    <option value="{{ $d }}" {{ request('division') === $d ? 'selected' : '' }}>{{ $d }}</option>
                @endforeach
            </select>

            <select name="kategori">
                <option value="">Semua Kategori</option>
                @foreach ($kategoriOptions as $kode => $label)
                    <option value="{{ $kode }}" {{ request('kategori') === $kode ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-primary">Filter</button>
            @if ($adaFilter || $periodeYm !== now()->format('Y-m'))
                <a href="{{ route('personalia.performance.index') }}" class="btn btn-secondary">Bulan ini</a>
            @endif
        </form>

        @if ($records->isEmpty())
            <div class="empty-note">
                Belum ada penilaian yang cocok pada bulan ini.
                <a href="{{ route('personalia.performance.create', ['periode' => $periodeYm]) }}" class="action-view">Tambah penilaian</a>
            </div>
        @else
            <div class="table-scroll">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Karyawan</th>
                            <th>Divisi</th>
                            <th>Target</th>
                            <th>Realisasi</th>
                            <th>Pencapaian</th>
                            <th>Skor</th>
                            <th>Kategori</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($records as $r)
                            @php $kat = $r->kategori; @endphp
                            <tr>
                                <td>
                                    <div class="person">
                                        <div class="avatar-mini">{{ $inisial($r->karyawan?->name) }}</div>
                                        <div>
                                            <div class="person-name">{{ $r->karyawan?->name ?? '-' }}</div>
                                            <div class="person-sub">{{ $r->karyawan?->position ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $r->karyawan?->division ?? '-' }}</td>
                                <td>{{ $fmt($r->target) }}</td>
                                <td>{{ $fmt($r->realisasi) }}</td>
                                <td>
                                    <div class="capai">
                                        <div class="bar-track capai-track">
                                            <div class="bar-fill {{ $kelasBatang[$kat['kode']] }}" style="width: {{ min(100, round($r->pencapaian)) }}%;"></div>
                                        </div>
                                        <span class="capai-num">{{ $fmt($r->pencapaian) }}%</span>
                                    </div>
                                </td>
                                <td><span class="skor">&#9733; {{ $fmtSkor($r->skor) }}</span></td>
                                <td><span class="status-pill kpi-{{ $kat['kode'] }}">{{ $kat['label'] }}</span></td>
                                <td>
                                    <div class="row-actions">
                                        <a href="{{ route('personalia.performance.edit', $r) }}" class="btn-aksi btn-aksi-edit" title="Ubah penilaian ini">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.83 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('personalia.performance.destroy', $r) }}" onsubmit="return confirm('Hapus penilaian kinerja ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-aksi btn-aksi-hapus" title="Hapus penilaian ini">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="padding: 1.25rem 1.5rem;">
                {{ $records->links() }}
            </div>
        @endif
    </div>

@endsection

@section('extra-style')
    /* ===== Judul ===== */
    .dash-head { display: flex; align-items: flex-end; justify-content: space-between; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
    .dash-greet { font-size: 1.5rem; color: var(--navy); }
    .dash-greet-sub { margin: .3rem 0 0; font-size: .88rem; color: var(--muted); }

    /* ===== Ringkasan ===== */
    .tiles { display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
    .tile { background: var(--card); border: 1px solid var(--line); border-radius: 12px; padding: 1rem 1.2rem; }
    .tile-label { font-size: .78rem; font-weight: 600; color: var(--muted); margin-bottom: .4rem; }
    .tile-value { font-family: 'Poppins', sans-serif; font-size: 1.7rem; font-weight: 700; color: var(--navy); line-height: 1.1; }
    .tile-value small { font-family: 'Inter', sans-serif; font-size: .75rem; font-weight: 500; color: var(--muted); margin-left: .35rem; }
    .tile-sub { font-size: .74rem; color: var(--muted); margin-top: .3rem; }
    .tile-sub.naik { color: var(--green); }
    .tile-sub.turun { color: var(--red); }

    .notice { display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; background: #FDF1DF; border: 1px solid #F2DDB8; color: #7A5316; border-radius: 10px; padding: .8rem 1.25rem; font-size: .86rem; margin-bottom: 1.5rem; }
    .notice a { font-weight: 600; color: var(--blue); }

    .count-badge { display: inline-block; min-width: 26px; text-align: center; padding: .2rem .6rem; border-radius: 999px; background: #E4E9F3; color: var(--blue); font-size: .78rem; font-weight: 700; }

    /* ===== Daftar ===== */
    .list-row { display: flex; align-items: center; gap: .85rem; padding: .8rem 1.5rem; }
    .list-row + .list-row { border-top: 1px solid var(--line); }
    .rank { width: 22px; text-align: center; font-family: 'Poppins', sans-serif; font-weight: 700; font-size: .9rem; color: var(--muted); flex-shrink: 0; }
    .avatar-mini { width: 34px; height: 34px; border-radius: 50%; background: #E4E9F3; color: var(--blue); display: flex; align-items: center; justify-content: center; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: .82rem; flex-shrink: 0; }
    .list-main { flex: 1; min-width: 0; }
    .list-name { font-size: .88rem; font-weight: 600; color: var(--ink); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .list-sub { font-size: .76rem; color: var(--muted); }
    .list-side { text-align: right; flex-shrink: 0; }
    .list-strong { font-size: .86rem; font-weight: 600; color: var(--navy); }
    .list-more { padding: .75rem 1.5rem; border-top: 1px solid var(--line); font-size: .8rem; color: var(--muted); text-align: center; }

    /* ===== Tabel ===== */
    .table-scroll { overflow-x: auto; }
    .table-scroll table.data-table { min-width: 1000px; }
    .table-scroll table.data-table th,
    .table-scroll table.data-table td { padding-left: 1rem; padding-right: 1rem; white-space: nowrap; }
    .table-scroll table.data-table th:first-child,
    .table-scroll table.data-table td:first-child { padding-left: 1.5rem; }
    .table-scroll table.data-table th:last-child,
    .table-scroll table.data-table td:last-child { padding-right: 1.5rem; }

    .person { display: flex; align-items: center; gap: .75rem; }
    .person-name { font-weight: 600; color: var(--ink); }
    .person-sub { font-size: .78rem; color: var(--muted); }

    .capai { display: flex; align-items: center; gap: .6rem; }
    .capai-track { width: 90px; height: 6px; }
    .capai-num { font-weight: 600; color: var(--ink); font-size: .84rem; }
    .skor { font-weight: 600; color: var(--navy); }

    .status-pill.kpi-sangat { background: #E4F1EA; color: var(--green); }
    .status-pill.kpi-baik { background: #E4E9F3; color: var(--blue); }
    .status-pill.kpi-cukup { background: #FDF1DF; color: #B9791F; }
    .status-pill.kpi-perlu { background: #FBE7E8; color: var(--red); }

    /* ===== Tombol aksi ===== */
    table.data-table .row-actions { align-items: center; gap: .5rem; }
    table.data-table .row-actions form { margin: 0; }
    table.data-table .row-actions .btn-aksi {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .4rem .75rem; border-radius: 8px; border: 1px solid transparent;
        font-family: 'Inter', sans-serif; font-size: .78rem; font-weight: 600; line-height: 1;
        cursor: pointer; transition: background .15s, color .15s, border-color .15s;
    }
    table.data-table .row-actions .btn-aksi svg { width: 14px; height: 14px; flex-shrink: 0; }
    table.data-table .row-actions .btn-aksi-edit { background: #E4E9F3; color: var(--blue); border-color: #D3DBEA; }
    table.data-table .row-actions .btn-aksi-edit:hover { background: var(--blue); color: #fff; border-color: var(--blue); }
    table.data-table .row-actions .btn-aksi-hapus { background: #FBE7E8; color: var(--red); border-color: #F2CFD1; }
    table.data-table .row-actions .btn-aksi-hapus:hover { background: var(--red); color: #fff; border-color: var(--red); }
@endsection