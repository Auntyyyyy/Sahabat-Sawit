@extends('admin.layouts.personalia')

@section('page-title', 'Karyawan')
@section('page-subtitle', 'Data lengkap seluruh karyawan perusahaan.')

@section('content')

    @php
        $inisial = fn ($nama) => strtoupper(mb_substr($nama ?: '?', 0, 1));
        $adaFilter = request('search') || request('division') || request('status') || request('gender');
    @endphp

    {{-- ================= KARTU RINGKASAN ================= --}}
    <div class="cards-4">
        <div class="dash-card">
            <div class="dash-card-title">Total karyawan</div>
            <div class="dash-big">{{ $total }}</div>
        </div>
        <div class="dash-card">
            <div class="dash-card-title">Karyawan aktif</div>
            <div class="dash-big">{{ $aktif }}</div>
        </div>
        <div class="dash-card">
            <div class="dash-card-title">Jumlah divisi</div>
            <div class="dash-big">{{ $jumlahDivisi }}</div>
        </div>
        <div class="dash-card">
            <div class="dash-card-title">Rata-rata usia</div>
            <div class="dash-big">{{ number_format($rataUsia, 1, ',', '.') }}</div>
            <div class="dash-sub">tahun</div>
        </div>
    </div>

    {{-- ================= DAFTAR KARYAWAN ================= --}}
    <div class="panel">
        <div class="panel-head">
            <h2>Daftar Karyawan</h2>
            <span class="count-badge">{{ $karyawans->total() }}</span>
        </div>

        <form method="GET" action="{{ route('personalia.karyawan.index') }}" class="filter-bar">
            <input type="text" name="search" placeholder="Cari nama, email, telepon, divisi, jabatan..." value="{{ request('search') }}">

            <select name="division">
                <option value="">Semua Divisi</option>
                @foreach ($divisions as $d)
                    <option value="{{ $d }}" {{ request('division') === $d ? 'selected' : '' }}>{{ $d }}</option>
                @endforeach
            </select>

            <select name="status">
                <option value="">Semua Status</option>
                @foreach ($statuses as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>

            <select name="gender">
                <option value="">Semua Gender</option>
                <option value="L" {{ request('gender') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                <option value="P" {{ request('gender') === 'P' ? 'selected' : '' }}>Perempuan</option>
            </select>

            <button type="submit" class="btn btn-primary">Filter</button>
            @if ($adaFilter)
                <a href="{{ route('personalia.karyawan.index') }}" class="btn btn-secondary">Reset</a>
            @endif
        </form>

        @if ($karyawans->isEmpty())
            <div class="empty-note">Tidak ada karyawan yang cocok dengan pencarian atau filter ini.</div>
        @else
            <div class="table-scroll">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Karyawan</th>
                            <th>Divisi</th>
                            <th>Jabatan</th>
                            <th>Gender</th>
                            <th>Telepon</th>
                            <th>Bergabung</th>
                            <th>Masa Kerja</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($karyawans as $k)
                            <tr>
                                <td>
                                    <div class="person">
                                        <div class="avatar-mini">{{ $inisial($k->name) }}</div>
                                        <div>
                                            <div class="person-name">{{ $k->name }}</div>
                                            <div class="person-sub">{{ $k->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $k->division }}</td>
                                <td>{{ $k->position }}</td>
                                <td>{{ $k->gender === 'L' ? 'Laki-laki' : ($k->gender === 'P' ? 'Perempuan' : '-') }}</td>
                                <td>{{ $k->phone ?: '-' }}</td>
                                <td>{{ $k->join_date ? $k->join_date->translatedFormat('d M Y') : '-' }}</td>
                                <td>{{ $k->join_date ? $k->masa_kerja : '-' }}</td>
                                <td><span class="status-pill {{ $k->status }}">{{ ucfirst($k->status) }}</span></td>
                                <td>
                                    <div class="row-actions">
                                        <a href="{{ route('personalia.karyawan.show', $k) }}" class="action-view">Detail</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="padding: 1.25rem 1.5rem;">
                {{ $karyawans->links() }}
            </div>
        @endif
    </div>

@endsection

@section('extra-style')
    /* ===== Kartu ringkasan ===== */
    .cards-4 { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 1.75rem; }
    .dash-card { background: var(--card); border: 1px solid var(--line); border-radius: 12px; padding: 1.25rem 1.4rem; }
    .dash-card-title { font-size: .82rem; font-weight: 600; color: var(--muted); margin-bottom: .6rem; }
    .dash-big { font-family: 'Poppins', sans-serif; font-size: 2.1rem; font-weight: 700; color: var(--navy); line-height: 1.15; }
    .dash-sub { font-size: .8rem; color: var(--muted); margin-top: .15rem; }

    .count-badge { display: inline-block; min-width: 26px; text-align: center; padding: .2rem .6rem; border-radius: 999px; background: #E4E9F3; color: var(--blue); font-size: .78rem; font-weight: 700; }

    /* ===== Tabel ===== */
    .table-scroll { overflow-x: auto; }
    .table-scroll table.data-table { min-width: 1020px; }
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

    /* Label status tambahan */
    .status-pill.kontrak { background: #E4E9F3; color: var(--blue); }
    .status-pill.trainee { background: #EDE8F5; color: #5B3F8C; }
    .status-pill.resign { background: #FBE7E8; color: var(--red); }
@endsection