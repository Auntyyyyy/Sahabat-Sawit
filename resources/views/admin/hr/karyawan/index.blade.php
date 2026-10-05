@extends('admin.layouts.hr')

@section('page-title', 'Data Karyawan')
@section('page-subtitle', 'Statistik dan daftar seluruh karyawan')

@section('content')

    {{-- ================= KARTU STATISTIK ================= --}}
    <div class="stat-cards-row">
        <div class="stat-card">
            <div class="stat-card-icon"><i class="bi bi-people-fill"></i></div>
            <div class="stat-card-label">Total Karyawan</div>
            <div class="stat-card-value">{{ $total }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-icon"><i class="bi bi-person-check-fill"></i></div>
            <div class="stat-card-label">Karyawan Aktif</div>
            <div class="stat-card-value">{{ $perStatus['aktif'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-icon"><i class="bi bi-file-earmark-person-fill"></i></div>
            <div class="stat-card-label">Karyawan Kontrak</div>
            <div class="stat-card-value">{{ $perStatus['kontrak'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-icon"><i class="bi bi-mortarboard-fill"></i></div>
            <div class="stat-card-label">Karyawan Trainee</div>
            <div class="stat-card-value">{{ $perStatus['trainee'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-icon"><i class="bi bi-calendar3"></i></div>
            <div class="stat-card-label">Rata-rata Usia</div>
            <div class="stat-card-value">{{ $avgAge }}</div>
        </div>
    </div>

    {{-- ================= DONUT DIVISI + STATUS ================= --}}
    <div class="two-col">
        <div class="panel">
            <div class="panel-head"><h2>Karyawan Berdasarkan Divisi</h2></div>
            <div class="panel-body donut-panel-body">
                <div class="donut-chart" style="background: conic-gradient({{ $perDivisiDonutCss }});"></div>
                <div class="donut-legend">
                    @forelse ($perDivisi as $divisi => $jumlah)
                        <div class="donut-legend-item">
                            <span class="donut-legend-dot" style="background: {{ $perDivisiColors[$divisi] }};"></span>
                            <span class="donut-legend-label">{{ $divisi }}</span>
                            <span class="donut-legend-value">{{ $total > 0 ? round($jumlah / $total * 100) : 0 }}%</span>
                        </div>
                    @empty
                        <p style="color:var(--muted); font-size:.85rem;">Belum ada data.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-head"><h2>Status Karyawan</h2></div>
            <div class="panel-body">
                <div class="bar-row">
                    <div class="bar-label"><span>Aktif</span><span>{{ $perStatus['aktif'] }} ({{ $total > 0 ? round($perStatus['aktif'] / $total * 100) : 0 }}%)</span></div>
                    <div class="bar-track"><div class="bar-fill green" style="width: {{ $total > 0 ? round($perStatus['aktif'] / $total * 100) : 0 }}%;"></div></div>
                </div>
                <div class="bar-row">
                    <div class="bar-label"><span>Kontrak</span><span>{{ $perStatus['kontrak'] }} ({{ $total > 0 ? round($perStatus['kontrak'] / $total * 100) : 0 }}%)</span></div>
                    <div class="bar-track"><div class="bar-fill amber" style="width: {{ $total > 0 ? round($perStatus['kontrak'] / $total * 100) : 0 }}%;"></div></div>
                </div>
                <div class="bar-row">
                    <div class="bar-label"><span>Trainee</span><span>{{ $perStatus['trainee'] }} ({{ $total > 0 ? round($perStatus['trainee'] / $total * 100) : 0 }}%)</span></div>
                    <div class="bar-track"><div class="bar-fill" style="width: {{ $total > 0 ? round($perStatus['trainee'] / $total * 100) : 0 }}%;"></div></div>
                </div>
                <div class="bar-row">
                    <div class="bar-label"><span>Resign</span><span>{{ $perStatus['resign'] }} ({{ $total > 0 ? round($perStatus['resign'] / $total * 100) : 0 }}%)</span></div>
                    <div class="bar-track"><div class="bar-fill red" style="width: {{ $total > 0 ? round($perStatus['resign'] / $total * 100) : 0 }}%;"></div></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= MASA KERJA + GENDER x USIA ================= --}}
    <div class="two-col">
        <div class="panel">
            <div class="panel-head"><h2>Karyawan Berdasarkan Masa Kerja</h2></div>
            <div class="panel-body">
                @foreach ($masaKerjaGroups as $label => $jumlah)
                    <div class="bar-row">
                        <div class="bar-label"><span>{{ $label }}</span><span>{{ $jumlah }} ({{ $total > 0 ? round($jumlah / $total * 100) : 0 }}%)</span></div>
                        <div class="bar-track"><div class="bar-fill" style="width: {{ $total > 0 ? round($jumlah / $total * 100) : 0 }}%;"></div></div>
                    </div>
                @endforeach
                <div class="panel-foot-note">Rata-rata masa kerja: {{ $avgMasaKerjaLabel }}</div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-head"><h2>Karyawan Berdasarkan Gender & Usia</h2></div>
            <div class="panel-body">
                <div class="stacked-bar-legend">
                    <span><i class="legend-dot male"></i> Laki-laki ({{ $perGender['L'] }})</span>
                    <span><i class="legend-dot female"></i> Perempuan ({{ $perGender['P'] }})</span>
                </div>
                <div class="stacked-bar-chart">
                    @foreach ($genderUsia as $label => $v)
                        @php
                            $heightL = $genderUsiaMax > 0 ? round($v['L'] / $genderUsiaMax * 100) : 0;
                            $heightP = $genderUsiaMax > 0 ? round($v['P'] / $genderUsiaMax * 100) : 0;
                        @endphp
                        <div class="stacked-bar-col">
                            <div class="stacked-bar-stack">
                                <div class="stacked-bar-seg female" style="height: {{ $heightP }}%;" title="Perempuan: {{ $v['P'] }}"></div>
                                <div class="stacked-bar-seg male" style="height: {{ $heightL }}%;" title="Laki-laki: {{ $v['L'] }}"></div>
                            </div>
                            <div class="stacked-bar-label">{{ $label }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- ================= DAFTAR KARYAWAN ================= --}}
    <div class="panel">
        <div class="panel-head">
            <h2>Daftar Karyawan</h2>
            <a href="{{ route('hr.karyawan.create') }}" class="btn btn-primary">+ Tambah Karyawan</a>
        </div>

        <form method="GET" action="{{ route('hr.karyawan.index') }}" class="filter-bar">
            <input type="text" name="search" placeholder="Cari nama, email, divisi..." value="{{ request('search') }}">
            <select name="division">
                <option value="">Semua Divisi</option>
                @foreach ($divisions as $d)
                    <option value="{{ $d }}" {{ request('division') === $d ? 'selected' : '' }}>{{ $d }}</option>
                @endforeach
            </select>
            <select name="status">
                <option value="">Semua Status</option>
                <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="kontrak" {{ request('status') === 'kontrak' ? 'selected' : '' }}>Kontrak</option>
                <option value="trainee" {{ request('status') === 'trainee' ? 'selected' : '' }}>Trainee</option>
                <option value="resign" {{ request('status') === 'resign' ? 'selected' : '' }}>Resign</option>
            </select>
            <button type="submit" class="btn btn-secondary">Filter</button>
        </form>

        @if ($karyawans->isEmpty())
            <div class="empty-note">Belum ada data karyawan yang cocok. Klik "Tambah Karyawan" untuk menambahkan.</div>
        @else
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Divisi</th>
                        <th>Jabatan</th>
                        <th>Masa Kerja</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($karyawans as $k)
                        <tr>
                            <td>
                                <div style="font-weight:600;">{{ $k->name }}</div>
                                <div style="color:var(--muted); font-size:.8rem;">{{ $k->email }}</div>
                            </td>
                            <td>{{ $k->division }}</td>
                            <td>{{ $k->position }}</td>
                            <td>{{ $k->masa_kerja }}</td>
                            <td><span class="status-pill {{ $k->status }}">{{ ucfirst($k->status) }}</span></td>
                            <td>
                                <div class="row-actions">
                                    <a href="{{ route('hr.karyawan.show', $k) }}" class="action-view">Detail</a>
                                    <a href="{{ route('hr.karyawan.edit', $k) }}" class="action-edit">Edit</a>
                                    <form method="POST" action="{{ route('hr.karyawan.destroy', $k) }}" onsubmit="return confirm('Hapus data {{ $k->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-delete">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="padding: 1.25rem 1.5rem;">
                {{ $karyawans->links() }}
            </div>
        @endif
    </div>
@endsection

@section('extra-style')
    .stat-cards-row { display: grid; grid-template-columns: repeat(5, 1fr); gap: 1rem; margin-bottom: 1.5rem; }
    .stat-card { background: #fff; border-radius: 12px; padding: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,.08); border: 1px solid #eef0f4; }
    .stat-card-icon { width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 8px; background: #eef2ff; color: #1e3a8a; font-size: 1rem; margin-bottom: .75rem; }
    .stat-card-label { font-size: .75rem; text-transform: uppercase; letter-spacing: .03em; color: var(--muted); margin-bottom: .25rem; }
    .stat-card-value { font-size: 1.75rem; font-weight: 700; color: var(--navy); }
    @media (max-width: 1100px) { .stat-cards-row { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 640px) { .stat-cards-row { grid-template-columns: repeat(2, 1fr); } }

    .donut-panel-body { display: flex; align-items: center; gap: 2rem; flex-wrap: wrap; }
    .donut-chart { width: 160px; height: 160px; border-radius: 50%; flex-shrink: 0; position: relative; }
    .donut-chart::after { content: ''; position: absolute; inset: 22%; background: #fff; border-radius: 50%; }
    .donut-legend { display: flex; flex-direction: column; gap: .6rem; flex: 1; min-width: 160px; }
    .donut-legend-item { display: flex; align-items: center; gap: .5rem; font-size: .875rem; }
    .donut-legend-dot { width: 10px; height: 10px; border-radius: 999px; flex-shrink: 0; }
    .donut-legend-label { flex: 1; color: var(--ink); }
    .donut-legend-value { font-weight: 600; color: var(--navy); }

    .panel-foot-note { margin-top: .75rem; padding-top: .75rem; border-top: 1px dashed var(--line); font-size: .8rem; color: var(--muted); }

    .stacked-bar-legend { display: flex; gap: 1.25rem; font-size: .8rem; color: var(--ink); margin-bottom: 1.25rem; }
    .stacked-bar-legend .legend-dot { display: inline-block; width: 10px; height: 10px; border-radius: 999px; margin-right: .35rem; }
    .legend-dot.male { background: #0f766e; }
    .legend-dot.female { background: #f59e0b; }
    .stacked-bar-chart { display: flex; align-items: flex-end; justify-content: space-between; gap: .75rem; height: 180px; padding-top: .5rem; }
    .stacked-bar-col { flex: 1; display: flex; flex-direction: column; align-items: center; height: 100%; }
    .stacked-bar-stack { width: 100%; max-width: 48px; height: 100%; display: flex; flex-direction: column; justify-content: flex-end; border-radius: 4px 4px 0 0; overflow: hidden; background: #f3f4f6; }
    .stacked-bar-seg { width: 100%; transition: height .3s ease; }
    .stacked-bar-seg.male { background: #0f766e; }
    .stacked-bar-seg.female { background: #f59e0b; }
    .stacked-bar-label { margin-top: .5rem; font-size: .75rem; color: var(--muted); text-align: center; }
@endsection