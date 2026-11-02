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
        @if ($karyawan->photo)
            <img class="profile-avatar profile-avatar-img" src="{{ asset('storage/' . $karyawan->photo) }}" alt="Foto {{ $karyawan->name }}">
        @else
            <div class="profile-avatar">{{ $inisial($karyawan->name) }}</div>
        @endif
        <div class="profile-main">
            <h2>{{ $karyawan->name }}</h2>
            <p>{{ $karyawan->position }} &middot; {{ $karyawan->division }}</p>
        </div>
        <span class="status-pill {{ $karyawan->status }}">{{ ucfirst($karyawan->status) }}</span>
        <div class="profile-actions">
            <a href="{{ route('personalia.karyawan.edit', $karyawan) }}" class="btn-aksi btn-aksi-edit">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.83 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                Edit
            </a>
            <form method="POST" action="{{ route('personalia.karyawan.destroy', $karyawan) }}" onsubmit="return confirm('Hapus karyawan ini? Seluruh catatan kehadiran dan penilaian kinerjanya juga ikut terhapus permanen. Kalau karyawan sudah keluar, lebih baik ubah statusnya menjadi Resign.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-aksi btn-aksi-hapus">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                    Hapus
                </button>
            </form>
        </div>
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
    .profile-avatar-img { object-fit: cover; }
    .profile-actions { display: flex; align-items: center; gap: .5rem; }
    .profile-actions form { margin: 0; }
    .btn-aksi {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .45rem .85rem; border-radius: 8px; border: 1px solid transparent;
        font-family: 'Inter', sans-serif; font-size: .8rem; font-weight: 600; line-height: 1;
        cursor: pointer; transition: background .15s, color .15s, border-color .15s;
    }
    .btn-aksi svg { width: 14px; height: 14px; flex-shrink: 0; }
    .btn-aksi-edit { background: #E4E9F3; color: var(--blue); border-color: #D3DBEA; }
    .btn-aksi-edit:hover { background: var(--blue); color: #fff; border-color: var(--blue); }
    .btn-aksi-hapus { background: #FBE7E8; color: var(--red); border-color: #F2CFD1; }
    .btn-aksi-hapus:hover { background: var(--red); color: #fff; border-color: var(--red); }
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