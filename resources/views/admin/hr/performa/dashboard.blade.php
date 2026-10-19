@extends('admin.layouts.hr')
@section('page-title', 'Dashboard Performa')
@section('page-subtitle', 'Memantau kinerja karyawan dan pencapaian target.')

@section('content')

    @if ($karyawanDievaluasi === 0)
        <div class="empty-note" style="background:#fff; border:1px solid var(--line); border-radius:12px; margin-bottom:1.5rem;">
            Belum ada data penilaian kinerja untuk bulan ini.
        </div>
    @endif

    {{-- ================= KARTU STATISTIK (5 Kolom) ================= --}}
    <div class="stat-cards-row stat-cards-row-5">
        <div class="stat-card">
            <div class="stat-card-label">Total Karyawan</div>
            <div class="stat-card-value">{{ $totalKaryawan }}</div>
            <div class="stat-card-sub">orang</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-label">Karyawan Dievaluasi</div>
            <div class="stat-card-value">{{ $karyawanDievaluasi }}</div>
            <div class="stat-card-sub">({{ $persenEvaluasi }}%)</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-label">Rata-Rata Pencapaian</div>
            <div class="stat-card-value">{{ $rataPencapaian }}%</div>
            <div class="stat-card-delta {{ $deltaPencapaian >= 0 ? 'up' : 'down' }}">
                {{ $deltaPencapaian >= 0 ? '▲' : '▼' }} {{ abs($deltaPencapaian) }}% vs bulan lalu
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card-label">Target Tercapai</div>
            <div class="stat-card-value">{{ $targetTercapaiPersen }}%</div>
            <div class="stat-card-sub">({{ $targetTercapaiOrang }} orang)</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-label">Top Performer</div>
            <div class="stat-card-value">{{ $topPerformerCount }}</div>
            <div class="stat-card-sub">orang</div>
        </div>
    </div>

    {{-- ================= PENCAPAIAN TARGET (Line) + PER DIVISI (Bar) ================= --}}
    <div class="two-col">
        <div class="panel">
            <div class="panel-head"><h2>Pencapaian Target (%)</h2></div>
            <div class="panel-body">
                @php
                    $w = 560; $h = 190; $padL = 36; $padR = 16; $padT = 16; $padB = 28;
                    $values = array_values($pencapaianTrend);
                    $labels = array_keys($pencapaianTrend);
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

        <div class="panel">
            <div class="panel-head"><h2>Pencapaian Target per Divisi</h2></div>
            <div class="panel-body">
                @forelse ($pencapaianPerDivisi as $divisi => $val)
                    <div class="bar-row">
                        <div class="bar-label">
                            <span>{{ $divisi }}</span>
                            <span>{{ $val }}%</span>
                        </div>
                        <div class="bar-track">
                            <div class="bar-fill" style="width: {{ min(100, $val) }}%;"></div>
                        </div>
                    </div>
                @empty
                    <p style="color:var(--muted); font-size:.85rem;">Belum ada data target per divisi.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ================= PENCAPAIAN KPI (Donut) + RATA-RATA SKOR KINERJA (Bintang) ================= --}}
    <div class="two-col">
        <div class="panel">
            <div class="panel-head"><h2>Pencapaian KPI</h2></div>
            <div class="panel-body donut-panel-body">
                <div class="donut-chart" style="background: conic-gradient({{ $kpiDonutCss }});"></div>
                <div class="donut-legend">
                    @foreach ($kpiList as $label => $data)
                        <div class="donut-legend-item">
                            <span class="donut-legend-dot" style="background: {{ $data['color'] }};"></span>
                            <span class="donut-legend-label">{{ $label }}</span>
                            <span class="donut-legend-value">{{ $data['persen'] }}%</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-head"><h2>Rata-Rata Skor Kinerja</h2></div>
            <div class="panel-body" style="text-align: center; padding: 2rem 1.5rem;">
                <div style="font-family: 'Poppins', sans-serif; font-size: 3rem; font-weight: 700; color: var(--navy); margin-bottom: 0.5rem;">
                    {{ $rataSkorKinerja }} <span style="font-size: 1.5rem; color: var(--muted);">/ 5</span>
                </div>
                <div class="star-row">
                    @for ($s = 0; $s < 5; $s++)
                        @php $isi = max(0, min(1, $rataSkorAngka - $s)) * 100; @endphp
                        <span class="star">★<span class="star-fill" style="width: {{ $isi }}%;">★</span></span>
                    @endfor
                </div>
                <p style="color: var(--muted); font-size: 0.85rem; margin-top: 1rem;">Berdasarkan penilaian kinerja periode ini</p>
            </div>
        </div>
    </div>

    {{-- ================= TOP 5 PERFORMER + TREN SKOR KINERJA ================= --}}
    <div class="two-col">
        <div class="panel">
            <div class="panel-head"><h2>Top 5 Performer</h2></div>
            <div class="panel-body" style="padding: 0.75rem;">
                <table class="data-table" style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--line); text-align: left; color: var(--muted);">
                            <th style="padding: 0.5rem;">Nama</th>
                            <th style="padding: 0.5rem;">Divisi</th>
                            <th style="padding: 0.5rem;">Skor</th>
                            <th style="padding: 0.5rem;">Pencapaian</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($topPerformers as $row)
                            <tr style="border-bottom: 1px solid #f3f6f9;">
                                <td style="padding: 0.6rem 0.5rem; font-weight: 600; color: var(--navy);">{{ $row['nama'] }}</td>
                                <td style="padding: 0.6rem 0.5rem; color: var(--muted);">{{ $row['divisi'] }}</td>
                                <td style="padding: 0.6rem 0.5rem; font-weight: 600;">{{ $row['skor'] }}</td>
                                <td style="padding: 0.6rem 0.5rem;">
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        <div style="flex: 1; background: #f3f6f9; height: 6px; border-radius: 3px; overflow: hidden;">
                                            <div style="width: {{ min(100, $row['pencapaian']) }}%; background: var(--blue); height: 100%;"></div>
                                        </div>
                                        <span style="font-size: 0.75rem; font-weight: 600;">{{ $row['pencapaian'] }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="padding: 1.25rem 0.5rem; text-align: center; color: var(--muted);">Belum ada data penilaian bulan ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="panel">
            <div class="panel-head"><h2>Tren Skor Kinerja</h2></div>
            <div class="panel-body">
                <div class="simple-bar-chart">
                    @foreach ($trenSkorTrend as $label => $skor)
                        <div class="simple-bar-col">
                            <div class="simple-bar-track">
                                <div class="simple-bar-fill" style="height: {{ round(($skor / 5) * 100) }}%;"></div>
                            </div>
                            <div class="simple-bar-label">{{ $label }}</div>
                            <div class="simple-bar-value">{{ number_format($skor, 1, ',', '.') }}</div>
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
    .stat-card-sub { font-size: .78rem; color: var(--muted); margin-top: .3rem; }
    .stat-card-delta { font-size: .78rem; margin-top: .3rem; font-weight: 600; }
    .stat-card-delta.up { color: var(--green); }
    .stat-card-delta.down { color: var(--red); }

    @media (max-width: 1200px) { .stat-cards-row-5 { grid-template-columns: repeat(3, 1fr); } }
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

    /* ===== Bintang rata-rata skor ===== */
    .star-row { display: flex; justify-content: center; gap: .25rem; font-size: 1.8rem; }
    .star { position: relative; color: #E5E7EB; line-height: 1; }
    .star-fill { position: absolute; left: 0; top: 0; overflow: hidden; white-space: nowrap; color: #F59E0B; }

    /* ===== Simple vertical bar ===== */
    .simple-bar-chart { display: flex; align-items: flex-end; justify-content: space-between; gap: .75rem; height: 190px; }
    .simple-bar-col { flex: 1; display: flex; flex-direction: column; align-items: center; height: 100%; }
    .simple-bar-track { width: 100%; max-width: 40px; height: 100%; display: flex; align-items: flex-end; background: #F3F6F9; border-radius: 4px 4px 0 0; overflow: hidden; }
    .simple-bar-fill { width: 100%; background: var(--blue-light); }
    .simple-bar-label { margin-top: .5rem; font-size: .75rem; color: var(--muted); }
    .simple-bar-value { font-size: .72rem; font-weight: 600; color: var(--navy); }
@endsection

@endsection