@extends('admin.layouts.personalia')

@section('page-title', 'Absensi')
@section('page-subtitle', 'Pencatatan dan rekap kehadiran karyawan.')

@section('content')

    @php
        $inisial = fn ($nama) => strtoupper(mb_substr($nama ?: '?', 0, 1));
        $fmtJam = fn ($v) => rtrim(rtrim(number_format((float) $v, 2, ',', '.'), '0'), ',');
        $tglStr = $tanggal->format('Y-m-d');
        $adaFilter = request('search') || request('division') || request('status');
        $sisaFilter = request()->except(['page', 'tanggal']);
        $linkTanggal = fn ($t) => route('personalia.absensi.index', array_merge($sisaFilter, ['tanggal' => $t->format('Y-m-d')]));
    @endphp

    {{-- ================= RINGKASAN TANGGAL TERPILIH ================= --}}
    <div class="tiles">
        <div class="tile">
            <div class="tile-label">Hadir</div>
            <div class="tile-value">{{ $ringkasan['hadir'] }}</div>
        </div>
        <div class="tile">
            <div class="tile-label">Izin</div>
            <div class="tile-value">{{ $ringkasan['izin'] }}</div>
        </div>
        <div class="tile">
            <div class="tile-label">Cuti</div>
            <div class="tile-value">{{ $ringkasan['cuti'] }}</div>
        </div>
        <div class="tile">
            <div class="tile-label">Alpha</div>
            <div class="tile-value">{{ $ringkasan['alpha'] }}</div>
        </div>
        <div class="tile">
            <div class="tile-label">Terlambat</div>
            <div class="tile-value">{{ $ringkasan['terlambat'] }}</div>
        </div>
        <div class="tile">
            <div class="tile-label">Lembur</div>
            <div class="tile-value">{{ $fmtJam($ringkasan['lembur']) }}<small>jam</small></div>
        </div>
    </div>

    @if ($belumDicatat > 0)
        <div class="notice">
            <span><strong>{{ $belumDicatat }}</strong> karyawan aktif belum dicatat pada tanggal ini.</span>
            <a href="{{ route('personalia.absensi.create', ['tanggal' => $tglStr]) }}">Catat sekarang</a>
        </div>
    @endif

    {{-- ================= DAFTAR CATATAN ================= --}}
    <div class="panel">
        <div class="panel-head">
            <h2>Catatan Kehadiran &middot; {{ $tanggal->translatedFormat('l, d F Y') }}</h2>
            <a href="{{ route('personalia.absensi.create', ['tanggal' => $tglStr]) }}" class="btn btn-primary">+ Catat kehadiran</a>
        </div>

        <form method="GET" action="{{ route('personalia.absensi.index') }}" class="filter-bar">
            <a href="{{ $linkTanggal($tanggal->copy()->subDay()) }}" class="btn btn-secondary" aria-label="Hari sebelumnya">&larr;</a>
            <input type="date" name="tanggal" value="{{ $tglStr }}" style="flex: 0 0 auto; min-width: 0;">
            <a href="{{ $linkTanggal($tanggal->copy()->addDay()) }}" class="btn btn-secondary" aria-label="Hari berikutnya">&rarr;</a>

            <input type="text" name="search" placeholder="Cari nama atau jabatan..." value="{{ request('search') }}">

            <select name="division">
                <option value="">Semua Divisi</option>
                @foreach ($divisions as $d)
                    <option value="{{ $d }}" {{ request('division') === $d ? 'selected' : '' }}>{{ $d }}</option>
                @endforeach
            </select>

            <select name="status">
                <option value="">Semua Status</option>
                @foreach ($statusOptions as $kode => $label)
                    <option value="{{ $kode }}" {{ request('status') === $kode ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-primary">Filter</button>
            @if ($adaFilter || ! $tanggal->isToday())
                <a href="{{ route('personalia.absensi.index') }}" class="btn btn-secondary">Hari ini</a>
            @endif
        </form>

        @if ($records->isEmpty())
            <div class="empty-note">
                Belum ada catatan kehadiran yang cocok pada tanggal ini.
                <a href="{{ route('personalia.absensi.create', ['tanggal' => $tglStr]) }}" class="action-view">Catat sekarang</a>
            </div>
        @else
            <div class="table-scroll">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Karyawan</th>
                            <th>Divisi</th>
                            <th>Status</th>
                            <th>Masuk</th>
                            <th>Keluar</th>
                            <th>Terlambat</th>
                            <th>Lembur</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($records as $r)
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
                                <td><span class="status-pill {{ $r->status }}">{{ ucfirst($r->status) }}</span></td>
                                <td>{{ $r->jam_masuk ? substr($r->jam_masuk, 0, 5) : '-' }}</td>
                                <td>{{ $r->jam_keluar ? substr($r->jam_keluar, 0, 5) : '-' }}</td>
                                <td>
                                    @if ($r->menit_terlambat > 0)
                                        <span class="late">{{ $r->menit_terlambat }} menit</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ (float) $r->jam_lembur > 0 ? $fmtJam($r->jam_lembur) . ' jam' : '-' }}</td>
                                <td>
                                    @if ($r->status === 'cuti')
                                        {{ $r->jenis_cuti ?: '-' }}
                                    @elseif ($r->status === 'izin')
                                        {{ $r->jenis_izin ?: '-' }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <div class="row-actions">
                                        <a href="{{ route('personalia.absensi.edit', $r) }}" class="btn-aksi btn-aksi-edit" title="Ubah catatan ini">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.83 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('personalia.absensi.destroy', $r) }}" onsubmit="return confirm('Hapus catatan kehadiran ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-aksi btn-aksi-hapus" title="Hapus catatan ini">
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
    /* ===== Ringkasan ===== */
    .tiles { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
    .tile { background: var(--card); border: 1px solid var(--line); border-radius: 12px; padding: 1rem 1.2rem; }
    .tile-label { font-size: .78rem; font-weight: 600; color: var(--muted); margin-bottom: .4rem; }
    .tile-value { font-family: 'Poppins', sans-serif; font-size: 1.7rem; font-weight: 700; color: var(--navy); line-height: 1.1; }
    .tile-value small { font-family: 'Inter', sans-serif; font-size: .75rem; font-weight: 500; color: var(--muted); margin-left: .3rem; }

    .notice { display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; background: #FDF1DF; border: 1px solid #F2DDB8; color: #7A5316; border-radius: 10px; padding: .8rem 1.25rem; font-size: .86rem; margin-bottom: 1.5rem; }
    .notice a { font-weight: 600; color: var(--blue); }

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
    .avatar-mini { width: 34px; height: 34px; border-radius: 50%; background: #E4E9F3; color: var(--blue); display: flex; align-items: center; justify-content: center; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: .82rem; flex-shrink: 0; }
    .late { color: var(--red); font-weight: 600; }

    /* ===== Tombol aksi (Edit dan Hapus) ===== */
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
    table.data-table .row-actions .btn-aksi:focus-visible { outline: 2px solid var(--blue); outline-offset: 2px; }
@endsection