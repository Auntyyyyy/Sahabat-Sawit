@extends('admin.layouts.hr')

@section('page-title', 'Data Karyawan')
@section('page-subtitle', 'Statistik dan daftar seluruh karyawan')

@section('content')

{{-- ================= KARTU STATISTIK ================= --}}
    <div class="stat-cards-row">
        <div class="stat-card">
            <div class="stat-card-icon">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div class="stat-card-label">Total Karyawan</div>
            <div class="stat-card-value">{{ $total }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-icon">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><polyline points="17 11 19 13 23 9"/></svg>
            </div>
            <div class="stat-card-label">Karyawan Aktif</div>
            <div class="stat-card-value">{{ $perStatus['aktif'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-icon">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
            </div>
            <div class="stat-card-label">Karyawan Kontrak</div>
            <div class="stat-card-value">{{ $perStatus['kontrak'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-icon">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
            </div>
            <div class="stat-card-label">Karyawan Trainee</div>
            <div class="stat-card-value">{{ $perStatus['trainee'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-icon">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </div>
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
            <div class="empty-note">Belum ada data karyawan yang cocok. Data karyawan ditambahkan oleh Personalia.</div>
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