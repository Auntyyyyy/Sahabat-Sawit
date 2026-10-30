@extends('admin.layouts.personalia')

@section('page-title', 'Detail Karyawan')
@section('page-subtitle', $karyawan->name)

@section('content')

    @php
        $inisial = fn ($nama) => strtoupper(mb_substr($nama ?: '?', 0, 1));
        $fmtJam = fn ($v) => rtrim(rtrim(number_format((float) $v, 2, ',', '.'), '0'), ',');
        $gender = $karyawan->gender === 'L' ? 'Laki-laki' : ($karyawan->gender === 'P' ? 'Perempuan' : '-');
    @endphp

    <a href="{{ route('personalia.karyawan.index') }}" class="back-link">&larr; Kembali ke daftar karyawan</a>

    {{-- ================= KARTU PROFIL ================= --}}
    <div class="profile-card">
        <div class="profile-avatar">{{ $inisial($karyawan->name) }}</div>
        <div class="profile-main">
            <h2>{{ $karyawan->name }}</h2>
            <p>{{ $karyawan->position }} &middot; {{ $karyawan->division }}</p>
        </div>
        <span class="status-pill {{ $karyawan->status }}">{{ ucfirst($karyawan->status) }}</span>
    </div>

    {{-- ================= DATA PRIBADI + KEPEGAWAIAN ================= --}}
    <div class="two-col">
        <div class="panel">
            <div class="panel-head"><h2>Data Pribadi</h2></div>
            <dl class="detail-grid">
                <dt>Nama lengkap</dt>
                <dd>{{ $karyawan->name }}</dd>

                <dt>Email</dt>
                <dd>{{ $karyawan->email }}</dd>

                <dt>No. telepon</dt>
                <dd>{{ $karyawan->phone ?: '-' }}</dd>

                <dt>Jenis kelamin</dt>
                <dd>{{ $gender }}</dd>

                <dt>Tanggal lahir</dt>
                <dd>
                    @if ($karyawan->birth_date)
                        {{ $karyawan->birth_date->translatedFormat('d F Y') }} ({{ $karyawan->usia }} tahun)
                    @else
                        -
                    @endif
                </dd>

                <dt>Alamat</dt>
                <dd>{{ $karyawan->address ?: '-' }}</dd>
            </dl>
        </div>

        <div class="panel">
            <div class="panel-head"><h2>Data Kepegawaian</h2></div>
            <dl class="detail-grid">
                <dt>ID karyawan</dt>
                <dd>#{{ str_pad($karyawan->id, 4, '0', STR_PAD_LEFT) }}</dd>

                <dt>Divisi</dt>
                <dd>{{ $karyawan->division }}</dd>

                <dt>Jabatan</dt>
                <dd>{{ $karyawan->position }}</dd>

                <dt>Tanggal bergabung</dt>
                <dd>{{ $karyawan->join_date ? $karyawan->join_date->translatedFormat('d F Y') : '-' }}</dd>

                <dt>Masa kerja</dt>
                <dd>{{ $karyawan->join_date ? $karyawan->masa_kerja : '-' }}</dd>

                <dt>Status</dt>
                <dd><span class="status-pill {{ $karyawan->status }}">{{ ucfirst($karyawan->status) }}</span></dd>
            </dl>
        </div>
    </div>

    {{-- ================= RINGKASAN KEHADIRAN BULAN INI ================= --}}
    <div class="panel">
        <div class="panel-head">
            <h2>Ringkasan Kehadiran {{ $today->translatedFormat('F Y') }}</h2>
        </div>
        <div class="panel-body">
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
                    <div class="tile-value">{{ $ringkasan['terlambat'] }}<small>kali</small></div>
                    <div class="tile-sub">{{ $ringkasan['menit_terlambat'] }} menit total</div>
                </div>
                <div class="tile">
                    <div class="tile-label">Lembur</div>
                    <div class="tile-value">{{ $fmtJam($ringkasan['lembur']) }}<small>jam</small></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= RIWAYAT KEHADIRAN ================= --}}
    <div class="panel">
        <div class="panel-head">
            <h2>Riwayat Kehadiran Terakhir</h2>
        </div>

        @if ($riwayat->isEmpty())
            <div class="empty-note">Belum ada catatan kehadiran untuk karyawan ini.</div>
        @else
            <div class="table-scroll">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Masuk</th>
                            <th>Keluar</th>
                            <th>Terlambat</th>
                            <th>Lembur</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($riwayat as $r)
                            <tr>
                                <td>{{ $r->tanggal->translatedFormat('D, d M Y') }}</td>
                                <td><span class="status-pill {{ $r->status }}">{{ ucfirst($r->status) }}</span></td>
                                <td>{{ $r->jam_masuk ? substr($r->jam_masuk, 0, 5) : '-' }}</td>
                                <td>{{ $r->jam_keluar ? substr($r->jam_keluar, 0, 5) : '-' }}</td>
                                <td>{{ $r->menit_terlambat > 0 ? $r->menit_terlambat . ' menit' : '-' }}</td>
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
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

@endsection

@section('extra-style')
    .back-link { display: inline-block; margin-bottom: 1.25rem; font-size: .85rem; font-weight: 600; color: var(--blue); }
    .back-link:hover { color: var(--navy); }

    /* ===== Kartu profil ===== */
    .profile-card { display: flex; align-items: center; gap: 1.1rem; background: var(--card); border: 1px solid var(--line); border-radius: 12px; padding: 1.4rem 1.6rem; margin-bottom: 1.75rem; flex-wrap: wrap; }
    .profile-avatar { width: 64px; height: 64px; border-radius: 50%; background: var(--blue); color: #fff; display: flex; align-items: center; justify-content: center; font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 1.5rem; flex-shrink: 0; }
    .profile-main { flex: 1; min-width: 200px; }
    .profile-main h2 { font-size: 1.3rem; color: var(--navy); }
    .profile-main p { margin: .25rem 0 0; font-size: .9rem; color: var(--muted); }

    /* ===== Ringkasan kehadiran ===== */
    .tiles { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 1rem; }
    .tile { border: 1px solid var(--line); border-radius: 10px; padding: .9rem 1rem; background: #FAFAFB; }
    .tile-label { font-size: .76rem; font-weight: 600; color: var(--muted); margin-bottom: .35rem; }
    .tile-value { font-family: 'Poppins', sans-serif; font-size: 1.5rem; font-weight: 700; color: var(--navy); line-height: 1.1; }
    .tile-value small { font-family: 'Inter', sans-serif; font-size: .75rem; font-weight: 500; color: var(--muted); margin-left: .3rem; }
    .tile-sub { font-size: .74rem; color: var(--muted); margin-top: .25rem; }

    /* ===== Tabel ===== */
    .table-scroll { overflow-x: auto; }
    .table-scroll table.data-table { min-width: 760px; }
    .table-scroll table.data-table th,
    .table-scroll table.data-table td { white-space: nowrap; }

    /* Label status tambahan */
    .status-pill.kontrak { background: #E4E9F3; color: var(--blue); }
    .status-pill.trainee { background: #EDE8F5; color: #5B3F8C; }
    .status-pill.resign { background: #FBE7E8; color: var(--red); }
@endsection