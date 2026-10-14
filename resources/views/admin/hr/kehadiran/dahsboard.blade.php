@extends('admin.layouts.hr')

@section('page-title', 'Dashboard Kehadiran')
@section('page-subtitle', 'Memantau aktivitas kehadiran karyawan setiap hari')

@section('content')

    {{-- ================= KARTU STATISTIK ================= --}}
    <div class="stat-cards-row stat-cards-row-5">
        <div class="stat-card">
            <div class="stat-card-label">Tingkat Kehadiran</div>
            <div class="stat-card-value">{{ $tingkatKehadiranHariIni }}%</div>
            <div class="stat-card-delta {{ $deltaTingkatKehadiran >= 0 ? 'up' : 'down' }}">
                {{ $deltaTingkatKehadiran >= 0 ? '▲' : '▼' }} {{ abs($deltaTingkatKehadiran) }}% vs bulan lalu
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card-label">Karyawan Hadir</div>
            <div class="stat-card-value">{{ $hadirHariIni }}</div>
            <div class="stat-card-sub">dari {{ $totalKaryawan }} karyawan</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-label">Terlambat Hari Ini</div>
            <div class="stat-card-value">{{ $terlambatHariIni }}</div>
            <div class="stat-card-sub">({{ $persenTerlambat }}%)</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-label">Izin Hari Ini</div>
            <div class="stat-card-value">{{ $izinHariIni }}</div>
            <div class="stat-card-sub">({{ $persenIzin }}%)</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-label">Cuti Hari Ini</div>
            <div class="stat-card-value">{{ $cutiHariIni }}</div>
            <div class="stat-card-sub">({{ $persenCuti }}%)</div>
        </div>
    </div>

    {{-- ================= TINGKAT KEHADIRAN (line) + KETERLAMBATAN (combo) ================= --}}
    <div class="two-col">
        <div class="panel">
            <div class="panel-head"><h2>Tingkat Kehadiran &mdash; 6 Bulan Terakhir</h2></div>
            <div class="panel-body">
                @php
                    $w = 560; $h = 190; $padL = 36; $padR = 16; $padT = 16; $padB = 28;
                    $values = array_values($tingkatKehadiranTrend);
                    $labels = array_keys($tingkatKehadiranTrend);
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
            <div class="panel-head"><h2>Keterlambatan &mdash; 6 Bulan Terakhir</h2></div>
            <div class="panel-body">
                @php
                    $w2 = 560; $h2 = 190; $padL2 = 30; $padR2 = 30; $padT2 = 16; $padB2 = 28;
                    $jumlahV = array_values($jumlahTerlambatTrend);
                    $rataV = array_values($rataTerlambatTrend);
                    $labels2 = array_keys($jumlahTerlambatTrend);
                    $n2 = max(count($jumlahV), 1);
                    $maxRaw = max(array_merge($jumlahV, $rataV, [10]));
                    $max2 = (int) (ceil($maxRaw / 10) * 10);
                    $slot = ($w2 - $padL2 - $padR2) / $n2;
                    $barWidth = $slot * 0.45;
                    $linePts = [];
                @endphp
                <svg viewBox="0 0 {{ $w2 }} {{ $h2 }}" class="line-chart">
                    @foreach ([0, $max2 / 2, $max2] as $g)
                        @php $gy2 = $padT2 + ($h2 - $padT2 - $padB2) * (1 - $g / $max2); @endphp
                        <line x1="{{ $padL2 }}" y1="{{ $gy2 }}" x2="{{ $w2 - $padR2 }}" y2="{{ $gy2 }}" class="chart-grid" />
                        <text x="2" y="{{ $gy2 + 4 }}" class="chart-axis-label">{{ round($g) }}</text>
                    @endforeach

                    @foreach ($jumlahV as $i => $v)
                        @php
                            $barH = ($h2 - $padT2 - $padB2) * ($v / $max2);
                            $barX = $padL2 + $i * $slot + ($slot - $barWidth) / 2;
                            $barY = $h2 - $padB2 - $barH;

                            $lineX = $padL2 + $i * $slot + $slot / 2;
                            $lineY = $padT2 + ($h2 - $padT2 - $padB2) * (1 - $rataV[$i] / $max2);
                            $linePts[] = "{$lineX},{$lineY}";
                        @endphp
                        <rect x="{{ $barX }}" y="{{ $barY }}" width="{{ $barWidth }}" height="{{ $barH }}" class="chart-bar" />
                        <text x="{{ $lineX }}" y="{{ $h2 - 6 }}" class="chart-axis-label chart-axis-label-center">{{ $labels2[$i] }}</text>
                    @endforeach

                    <polyline points="{{ implode(' ', $linePts) }}" class="chart-line chart-line-amber" />
                    @foreach ($linePts as $p)
                        @php [$lx, $ly] = explode(',', $p); @endphp
                        <circle cx="{{ $lx }}" cy="{{ $ly }}" r="3.5" class="chart-dot chart-dot-amber" />
                    @endforeach
                </svg>
                <div class="chart-legend">
                    <span><i class="legend-dot teal"></i> Jumlah Terlambat</span>
                    <span><i class="legend-dot amber"></i> Rata-rata (menit)</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= JENIS CUTI (donut) + IZIN (bar) ================= --}}
    <div class="two-col">
        <div class="panel">
            <div class="panel-head"><h2>Jenis Cuti (Bulan Ini)</h2></div>
            <div class="panel-body donut-panel-body">
                <div class="donut-chart" style="background: conic-gradient({{ $jenisCutiDonutCss }});"></div>
                <div class="donut-legend">
                    @forelse ($jenisCutiBulanIni as $jenis => $jumlah)
                        <div class="donut-legend-item">
                            <span class="donut-legend-dot" style="background: {{ $jenisCutiColors[$jenis] }};"></span>
                            <span class="donut-legend-label">{{ $jenis }}</span>
                            <span class="donut-legend-value">{{ $jumlah }} ({{ $totalCutiBulanIni > 0 ? round($jumlah / $totalCutiBulanIni * 100) : 0 }}%)</span>
                        </div>
                    @empty
                        <p style="color:var(--muted); font-size:.85rem;">Belum ada data cuti bulan ini.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-head"><h2>Izin (Bulan Ini)</h2></div>
            <div class="panel-body">
                @forelse ($izinBulanIni as $jenis => $jumlah)
                    <div class="bar-row">
                        <div class="bar-label">
                            <span>{{ $jenis }}</span>
                            <span>{{ $jumlah }} ({{ $totalIzinBulanIni > 0 ? round($jumlah / $totalIzinBulanIni * 100) : 0 }}%)</span>
                        </div>
                        <div class="bar-track">
                            <div class="bar-fill" style="width: {{ $totalIzinBulanIni > 0 ? round($jumlah / $totalIzinBulanIni * 100) : 0 }}%;"></div>
                        </div>
                    </div>
                @empty
                    <p style="color:var(--muted); font-size:.85rem;">Belum ada data izin bulan ini.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ================= LEMBUR 6 BULAN + LEMBUR PER DIVISI ================= --}}
    <div class="two-col">
        <div class="panel">
            <div class="panel-head"><h2>Lembur (Jam) &mdash; 6 Bulan Terakhir</h2></div>
            <div class="panel-body">
                <div class="simple-bar-chart">
                    @php $maxLembur = max(array_merge(array_values($lemburTrend), [1])); @endphp
                    @foreach ($lemburTrend as $label => $jam)
                        <div class="simple-bar-col">
                            <div class="simple-bar-track">
                                <div class="simple-bar-fill" style="height: {{ round($jam / $maxLembur * 100) }}%;"></div>
                            </div>
                            <div class="simple-bar-label">{{ $label }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-head"><h2>Lembur Berdasarkan Divisi (Bulan Ini)</h2></div>
            <div class="panel-body">
                @forelse ($lemburPerDivisi as $divisi => $jam)
                    @php $maxDivisi = $lemburPerDivisi->max() ?: 1; @endphp
                    <div class="bar-row">
                        <div class="bar-label">
                            <span>{{ $divisi }}</span>
                            <span>{{ rtrim(rtrim(number_format($jam, 1, ',', '.'), '0'), ',') }} jam</span>
                        </div>
                        <div class="bar-track">
                            <div class="bar-fill" style="width: {{ round($jam / $maxDivisi * 100) }}%;"></div>
                        </div>
                    </div>
                @empty
                    <p style="color:var(--muted); font-size:.85rem;">Belum ada data lembur bulan ini.</p>
                @endforelse
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
    .chart-line-amber { stroke: var(--amber); }
    .chart-dot { fill: var(--blue); }
    .chart-dot-amber { fill: var(--amber); }
    .chart-bar { fill: var(--blue-light); rx: 2; }
    .chart-value-label { font-size: 10px; font-weight: 700; fill: var(--navy); text-anchor: middle; font-family: 'Poppins', sans-serif; }

    .chart-legend { display: flex; gap: 1.25rem; margin-top: .75rem; font-size: .8rem; color: var(--ink); }
    .legend-dot { display: inline-block; width: 10px; height: 10px; border-radius: 999px; margin-right: .35rem; }
    .legend-dot.teal { background: var(--blue-light); }
    .legend-dot.amber { background: var(--amber); }

    /* ===== Donut ===== */
    .donut-panel-body { display: flex; align-items: center; gap: 2rem; flex-wrap: wrap; }
    .donut-chart { width: 150px; height: 150px; border-radius: 50%; flex-shrink: 0; position: relative; }
    .donut-chart::after { content: ''; position: absolute; inset: 22%; background: #fff; border-radius: 50%; }
    .donut-legend { display: flex; flex-direction: column; gap: .6rem; flex: 1; min-width: 160px; }
    .donut-legend-item { display: flex; align-items: center; gap: .5rem; font-size: .85rem; }
    .donut-legend-dot { width: 10px; height: 10px; border-radius: 999px; flex-shrink: 0; }
    .donut-legend-label { flex: 1; color: var(--ink); }
    .donut-legend-value { font-weight: 600; color: var(--navy); font-size: .8rem; }

    /* ===== Simple vertical bar (Lembur 6 bulan) ===== */
    .simple-bar-chart { display: flex; align-items: flex-end; justify-content: space-between; gap: .75rem; height: 170px; }
    .simple-bar-col { flex: 1; display: flex; flex-direction: column; align-items: center; height: 100%; }
    .simple-bar-track { width: 100%; max-width: 40px; height: 100%; display: flex; align-items: flex-end; background: #F3F6F9; border-radius: 4px 4px 0 0; overflow: hidden; }
    .simple-bar-fill { width: 100%; background: var(--blue-light); }
    .simple-bar-label { margin-top: .5rem; font-size: .75rem; color: var(--muted); }
@endsection

@endsection