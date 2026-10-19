@extends('admin.layouts.hr')

@section('page-title', 'Dashboard HR')
@section('page-subtitle', 'Memantau data karyawan dan statistik kehadiran.')

@section('content')

    {{-- ================= KARTU STATISTIK (5 Kolom) ================= --}}
    <div class="stat-cards-row stat-cards-row-5">
        <div class="stat-card">
            <div class="stat-card-label">Total Karyawan</div>
            <div class="stat-card-value">{{ $totalKaryawan }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-label">Karyawan Aktif</div>
            <div class="stat-card-value">{{ $karyawanAktif }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-label">Karyawan Kontrak</div>
            <div class="stat-card-value">{{ $karyawanKontrak }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-label">Tingkat Kehadiran</div>
            <div class="stat-card-value">{{ $tingkatKehadiran }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-label">Turnover Rate</div>
            <div class="stat-card-value">{{ $turnoverRate }}</div>
        </div>
    </div>

    {{-- ================= DIVISI (Donut) + TINGKAT KEHADIRAN (Line) ================= --}}
    <div class="two-col">
        <div class="panel">
            <div class="panel-head"><h2>Karyawan Berdasarkan Divisi</h2></div>
            <div class="panel-body donut-panel-body">
                <div class="donut-chart" style="background: conic-gradient({{ $divisiDonutCss }});"></div>
                <div class="donut-legend">
                    @forelse ($divisiList as $divisi => $data)
                        <div class="donut-legend-item">
                            <span class="donut-legend-dot" style="background: {{ $data['color'] }};"></span>
                            <span class="donut-legend-label">{{ $divisi }}</span>
                            <span class="donut-legend-value">{{ $data['persen'] }}%</span>
                        </div>
                    @empty
                        <p style="color:var(--muted); font-size:.85rem;">Belum ada data karyawan.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-head"><h2>Tingkat Kehadiran (6 Bulan Terakhir)</h2></div>
            <div class="panel-body">
                @php
                    $w = 560; $h = 190; $padL = 36; $padR = 16; $padT = 16; $padB = 28;
                    $values = array_values($kehadiranTrend);
                    $labels = array_keys($kehadiranTrend);
                    $n = max(count($values), 1);
                    $stepX = $n > 1 ? ($w - $padL - $padR) / ($n - 1) : 0;
                    $maxV = 100;
                    $pts = [];
                    foreach ($values as $i => $v) {
                        $x = $padL + $i * $stepX;
                        $y = $padT + ($h - $padT - $padB) * (1 - min($v, $maxV) / $maxV);
                        $pts[] = ['x' => $x, 'y' => $y, 'v' => $v];
                    }
                    $polyline = implode(' ', array_map(fn($p) => "{$p['x']},{$p['y']}", $pts));
                @endphp
                <svg viewBox="0 0 {{ $w }} {{ $h }}" class="line-chart">
                    @foreach ([0, 25, 50, 75, 100] as $g)
                        @php $gy = $padT + ($h - $padT - $padB) * (1 - $g / 100); @endphp
                        <line x1="{{ $padL }}" y1="{{ $gy }}" x2="{{ $w - $padR }}" y2="{{ $gy }}" class="chart-grid" />
                        <text x="2" y="{{ $gy + 4 }}" class="chart-axis-label">{{ $g }}%</text>
                    @endforeach

                    <polyline points="{{ $polyline }}" class="chart-line" />

                    @foreach ($pts as $i => $p)
                        <circle cx="{{ $p['x'] }}" cy="{{ $p['y'] }}" r="4" class="chart-dot" />
                        <text x="{{ $p['x'] }}" y="{{ $h - 6 }}" class="chart-axis-label chart-axis-label-center">{{ $labels[$i] }}</text>
                        @if ($i === count($pts) - 1)
                            <text x="{{ $p['x'] }}" y="{{ $p['y'] - 10 }}" class="chart-value-label">{{ $p['v'] }}%</text>
                        @endif
                    @endforeach
                </svg>
            </div>
        </div>
    </div>

    {{-- ================= JENIS KELAMIN + MASA KERJA + USIA KARYAWAN ================= --}}
    <div class="three-col" style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem; margin-top: 1.5rem;">

        {{-- Jenis Kelamin --}}
        <div class="panel">
            <div class="panel-head"><h2>Jenis Kelamin</h2></div>
            <div class="panel-body" style="display: flex; justify-content: space-around; align-items: center; text-align: center; padding: 1.5rem 1rem;">
                <div>
                    <div style="font-size: 2.2rem; margin-bottom: 0.25rem;">👨‍💼</div>
                    <div style="font-size: 1.2rem; font-weight: 700; color: var(--navy);">{{ $persenLaki }}</div>
                    <div style="font-size: 0.8rem; color: var(--muted);">Laki-laki</div>
                </div>
                <div style="border-left: 1px solid var(--line); height: 60px;"></div>
                <div>
                    <div style="font-size: 2.2rem; margin-bottom: 0.25rem;">👩‍💼</div>
                    <div style="font-size: 1.2rem; font-weight: 700; color: var(--navy);">{{ $persenPerempuan }}</div>
                    <div style="font-size: 0.8rem; color: var(--muted);">Perempuan</div>
                </div>
            </div>
        </div>

        {{-- Masa Kerja --}}
        <div class="panel">
            <div class="panel-head"><h2>Masa Kerja</h2></div>
            <div class="panel-body" style="padding: 0.75rem 1rem;">
                @foreach ($masaKerja as $label => $val)
                    <div class="bar-row" style="margin-bottom: 0.5rem;">
                        <div class="bar-label" style="font-size: 0.75rem;">
                            <span>{{ $label }}</span>
                            <span>{{ $val }}%</span>
                        </div>
                        <div class="bar-track" style="height: 6px;">
                            <div class="bar-fill" style="width: {{ $val }}%;"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Usia Karyawan --}}
        <div class="panel">
            <div class="panel-head"><h2>Usia Karyawan</h2></div>
            <div class="panel-body">
                <div class="simple-bar-chart" style="height: 130px;">
                    @php $maxUsia = max(array_merge(array_values($usiaList), [1])); @endphp
                    @foreach ($usiaList as $range => $jumlah)
                        <div class="simple-bar-col">
                            <div class="simple-bar-track">
                                <div class="simple-bar-fill" style="height: {{ round(($jumlah / $maxUsia) * 100) }}%;"></div>
                            </div>
                            <div class="simple-bar-label" style="font-size: 0.65rem;">{{ $range }} ({{ $jumlah }})</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

@section('extra-style')
    .stat-cards-row-5 { display: grid; grid-template-columns: repeat(5, 1fr); gap: 1rem; margin-bottom: 1.75rem; }
    .stat-card { background: #fff; border: 1px solid var(--line); border-radius: 12px; padding: 1.15rem 1.25rem; }
    .stat-card-label { font-size: .74rem; color: var(--muted); text-transform: uppercase; letter-spacing: .03em; margin-bottom: .5rem; }
    .stat-card-value { font-family: 'Poppins', sans-serif; font-size: 1.6rem; font-weight: 700; color: var(--navy); }

    @media (max-width: 1200px) {
        .stat-cards-row-5 { grid-template-columns: repeat(3, 1fr); }
        .three-col { grid-template-columns: 1fr !important; }
    }
    @media (max-width: 640px) { .stat-cards-row-5 { grid-template-columns: repeat(2, 1fr); } }

    /* ===== SVG charts ===== */
    .line-chart { width: 100%; height: auto; overflow: visible; }
    .chart-grid { stroke: #EEF3F8; stroke-width: 1; }
    .chart-axis-label { font-size: 9px; fill: var(--muted); font-family: 'Inter', sans-serif; }
    .chart-axis-label-center { text-anchor: middle; }
    .chart-line { fill: none; stroke: var(--blue); stroke-width: 2.5; }
    .chart-dot { fill: var(--blue); }
    .chart-value-label { font-size: 10px; font-weight: 700; fill: var(--navy); text-anchor: middle; font-family: 'Poppins', sans-serif; }

    /* ===== Donut ===== */
    .donut-panel-body { display: flex; align-items: center; gap: 2rem; flex-wrap: wrap; }
    .donut-chart { width: 150px; height: 150px; border-radius: 50%; flex-shrink: 0; position: relative; }
    .donut-chart::after { content: ''; position: absolute; inset: 22%; background: #fff; border-radius: 50%; }
    .donut-legend { display: flex; flex-direction: column; gap: .6rem; flex: 1; min-width: 160px; }
    .donut-legend-item { display: flex; align-items: center; gap: .5rem; font-size: .85rem; }
    .donut-legend-dot { width: 10px; height: 10px; border-radius: 999px; flex-shrink: 0; }
    .donut-legend-label { flex: 1; color: var(--ink); }
    .donut-legend-value { font-weight: 600; color: var(--navy); font-size: .8rem; }

    /* ===== Simple vertical bar ===== */
    .simple-bar-chart { display: flex; align-items: flex-end; justify-content: space-between; gap: .75rem; height: 170px; }
    .simple-bar-col { flex: 1; display: flex; flex-direction: column; align-items: center; height: 100%; }
    .simple-bar-track { width: 100%; max-width: 40px; height: 100%; display: flex; align-items: flex-end; background: #F3F6F9; border-radius: 4px 4px 0 0; overflow: hidden; }
    .simple-bar-fill { width: 100%; background: var(--blue-light); }
    .simple-bar-label { margin-top: .5rem; font-size: .75rem; color: var(--muted); text-align: center; }
@endsection

@endsection