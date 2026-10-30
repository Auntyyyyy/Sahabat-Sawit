@extends('admin.layouts.personalia')

@section('page-title', 'Dashboard Personalia')
@section('page-subtitle', now()->translatedFormat('l, d F Y'))

@section('content')

    @php
        // Format jam: 3.50 -> "3,5", 2.00 -> "2"
        $fmtJam = fn ($v) => rtrim(rtrim(number_format((float) $v, 2, ',', '.'), '0'), ',');
        $inisial = fn ($nama) => strtoupper(mb_substr($nama ?: '?', 0, 1));
        $namaDepan = \Illuminate\Support\Str::before(auth()->user()->name, ' ');
        $maxLembur = max($lemburPerDivisi->map(fn ($v) => (float) $v)->max() ?? 0, 1);
    @endphp

    {{-- ================= SAPAAN ================= --}}
    <div class="dash-head">
        <div>
            <h2 class="dash-greet">{{ $salam }}, {{ $namaDepan }}</h2>
            <p class="dash-greet-sub">Berikut pantauan kehadiran karyawan hari ini.</p>
        </div>
        <a href="{{ route('personalia.absensi.create') }}" class="btn btn-primary">+ Catat kehadiran</a>
    </div>

    {{-- ================= KARTU RINGKASAN ================= --}}
    <div class="cards-3">
        <div class="dash-card">
            <div class="dash-card-title">Karyawan aktif</div>
            <div class="dash-big">{{ $karyawanAktif }}</div>
            @forelse ($perStatus as $status => $jumlah)
                <div class="split-row tone-{{ $loop->iteration }}">
                    <span>{{ ucfirst($status) }}</span>
                    <strong>{{ $jumlah }}</strong>
                </div>
            @empty
                <div class="dash-sub" style="margin-top:.75rem;">Belum ada data karyawan.</div>
            @endforelse
        </div>

        <div class="dash-card">
            <div class="dash-card-title">Kehadiran hari ini</div>
            <div class="dash-big">{{ $tingkatKehadiran }}%</div>
            <div class="dash-sub">{{ $hadir }} hadir dari {{ $dicatat }} yang tercatat</div>

            <div class="seg-bar" role="img" aria-label="Komposisi kehadiran hari ini">
                @foreach ($segmen as $s)
                    @if ($s['jumlah'] > 0)
                        <span class="seg {{ $s['class'] }}" style="width: {{ $s['persen'] }}%;"></span>
                    @endif
                @endforeach
            </div>

            <div class="seg-legend">
                @foreach ($segmen as $s)
                    <span><i class="seg-dot {{ $s['class'] }}"></i>{{ $s['label'] }} ({{ $s['jumlah'] }})</span>
                @endforeach
            </div>
        </div>

        <div class="dash-card">
            <div class="dash-card-title">Terlambat dan lembur hari ini</div>
            <div class="duo">
                <div>
                    <div class="dash-big dash-big-sm">{{ $jumlahTerlambat }}</div>
                    <div class="dash-sub">karyawan terlambat</div>
                </div>
                <div>
                    <div class="dash-big dash-big-sm">{{ $fmtJam($totalJamLemburHariIni) }}</div>
                    <div class="dash-sub">jam lembur ({{ $jumlahLembur }} orang)</div>
                </div>
            </div>
            <div class="split-row tone-1" style="margin-top:1rem;">
                <span>Izin dan cuti</span>
                <strong>{{ $jumlahIzinCuti }}</strong>
            </div>
            <div class="split-row tone-3">
                <span>Belum dicatat</span>
                <strong>{{ $jumlahBelumDicatat }}</strong>
            </div>
        </div>
    </div>

    {{-- ================= GRAFIK ================= --}}
    <div class="two-col">
        <div class="panel">
            <div class="panel-head"><h2>Tingkat Kehadiran 6 Bulan Terakhir</h2></div>
            <div class="panel-body">
                <div class="vbar-chart">
                    @foreach ($tren as $t)
                        <div class="vbar-col">
                            <div class="vbar-value">{{ $t['persen'] }}%</div>
                            <div class="vbar-track">
                                <div class="vbar-fill {{ $loop->last ? 'current' : '' }}" style="height: {{ $t['persen'] }}%;"></div>
                            </div>
                            <div class="vbar-label">{{ $t['label'] }}</div>
                            <div class="vbar-sub">{{ $fmtJam($t['lembur']) }} jam</div>
                        </div>
                    @endforeach
                </div>
                <div class="chart-note">Angka di bawah bulan adalah total jam lembur.</div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-head"><h2>Lembur per Divisi (Bulan Ini)</h2></div>
            <div class="panel-body">
                @forelse ($lemburPerDivisi as $divisi => $jam)
                    <div class="bar-row">
                        <div class="bar-label">
                            <span>{{ $divisi }}</span>
                            <span>{{ $fmtJam($jam) }} jam</span>
                        </div>
                        <div class="bar-track">
                            <div class="bar-fill" style="width: {{ round(((float) $jam) / $maxLembur * 100) }}%;"></div>
                        </div>
                    </div>
                @empty
                    <div class="empty-note" style="padding: 2.5rem 0;">Belum ada lembur tercatat bulan ini.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ================= DAFTAR: TERLAMBAT + IZIN/CUTI ================= --}}
    <div class="two-col">
        <div class="panel">
            <div class="panel-head">
                <h2>Terlambat Hari Ini</h2>
                <span class="count-badge">{{ $jumlahTerlambat }}</span>
            </div>
            @forelse ($terlambat as $r)
                <div class="list-row">
                    <div class="avatar-mini">{{ $inisial($r->karyawan?->name) }}</div>
                    <div class="list-main">
                        <div class="list-name">{{ $r->karyawan?->name ?? '-' }}</div>
                        <div class="list-sub">{{ $r->karyawan?->division ?? '-' }}</div>
                    </div>
                    <div class="list-side">
                        <div class="list-strong">{{ $r->menit_terlambat }} menit</div>
                        <div class="list-sub">masuk {{ $r->jam_masuk ? substr($r->jam_masuk, 0, 5) : '-' }}</div>
                    </div>
                </div>
            @empty
                <div class="empty-note">Tidak ada yang terlambat hari ini.</div>
            @endforelse
            @if ($jumlahTerlambat > $batasDaftar)
                <div class="list-more">+ {{ $jumlahTerlambat - $batasDaftar }} lainnya</div>
            @endif
        </div>

        <div class="panel">
            <div class="panel-head">
                <h2>Izin dan Cuti Hari Ini</h2>
                <span class="count-badge">{{ $jumlahIzinCuti }}</span>
            </div>
            @forelse ($izinCuti as $r)
                <div class="list-row">
                    <div class="avatar-mini">{{ $inisial($r->karyawan?->name) }}</div>
                    <div class="list-main">
                        <div class="list-name">{{ $r->karyawan?->name ?? '-' }}</div>
                        <div class="list-sub">{{ $r->karyawan?->division ?? '-' }}</div>
                    </div>
                    <div class="list-side">
                        <span class="status-pill {{ $r->status }}">
                            {{ $r->status === 'cuti' ? ($r->jenis_cuti ?: 'Cuti') : ($r->jenis_izin ?: 'Izin') }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="empty-note">Tidak ada izin atau cuti hari ini.</div>
            @endforelse
            @if ($jumlahIzinCuti > $batasDaftar)
                <div class="list-more">+ {{ $jumlahIzinCuti - $batasDaftar }} lainnya</div>
            @endif
        </div>
    </div>

    {{-- ================= DAFTAR: LEMBUR + BELUM DICATAT ================= --}}
    <div class="two-col">
        <div class="panel">
            <div class="panel-head">
                <h2>Lembur Hari Ini</h2>
                <span class="count-badge">{{ $jumlahLembur }}</span>
            </div>
            @forelse ($lembur as $r)
                <div class="list-row">
                    <div class="avatar-mini">{{ $inisial($r->karyawan?->name) }}</div>
                    <div class="list-main">
                        <div class="list-name">{{ $r->karyawan?->name ?? '-' }}</div>
                        <div class="list-sub">{{ $r->karyawan?->division ?? '-' }}</div>
                    </div>
                    <div class="list-side">
                        <div class="list-strong">{{ $fmtJam($r->jam_lembur) }} jam</div>
                    </div>
                </div>
            @empty
                <div class="empty-note">Tidak ada lembur hari ini.</div>
            @endforelse
            @if ($jumlahLembur > $batasDaftar)
                <div class="list-more">+ {{ $jumlahLembur - $batasDaftar }} lainnya</div>
            @endif
        </div>

        <div class="panel">
            <div class="panel-head">
                <h2>Belum Dicatat Hari Ini</h2>
                <span class="count-badge">{{ $jumlahBelumDicatat }}</span>
            </div>
            @forelse ($belumDicatat as $k)
                <div class="list-row">
                    <div class="avatar-mini">{{ $inisial($k->name) }}</div>
                    <div class="list-main">
                        <div class="list-name">{{ $k->name }}</div>
                        <div class="list-sub">{{ $k->division }}</div>
                    </div>
                    <div class="list-side">
                        <a href="{{ route('personalia.absensi.create', ['karyawan_id' => $k->id]) }}" class="action-view">Catat</a>
                    </div>
                </div>
            @empty
                <div class="empty-note">Semua karyawan aktif sudah dicatat hari ini.</div>
            @endforelse
            @if ($jumlahBelumDicatat > $batasDaftar)
                <div class="list-more">+ {{ $jumlahBelumDicatat - $batasDaftar }} lainnya</div>
            @endif
        </div>
    </div>

@endsection

@section('extra-style')
    /* ===== Sapaan ===== */
    .dash-head { display: flex; align-items: flex-end; justify-content: space-between; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
    .dash-greet { font-size: 1.5rem; color: var(--navy); }
    .dash-greet-sub { margin: .3rem 0 0; font-size: .88rem; color: var(--muted); }

    /* ===== Kartu ringkasan ===== */
    .cards-3 { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.5rem; margin-bottom: 1.75rem; }
    .dash-card { background: var(--card); border: 1px solid var(--line); border-radius: 12px; padding: 1.25rem 1.4rem; }
    .dash-card-title { font-size: .82rem; font-weight: 600; color: var(--muted); margin-bottom: .6rem; }
    .dash-big { font-family: 'Poppins', sans-serif; font-size: 2.1rem; font-weight: 700; color: var(--navy); line-height: 1.15; }
    .dash-big-sm { font-size: 1.7rem; }
    .dash-sub { font-size: .8rem; color: var(--muted); margin-top: .15rem; }
    .duo { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }

    .split-row { display: flex; align-items: center; justify-content: space-between; padding: .55rem .85rem; border-radius: 8px; margin-top: .5rem; font-size: .85rem; color: var(--ink); }
    .split-row strong { font-family: 'Poppins', sans-serif; color: var(--navy); }
    .split-row.tone-1 { background: #E4E9F3; }
    .split-row.tone-2 { background: #ECEEF2; }
    .split-row.tone-3 { background: #FDF1DF; }

    /* ===== Batang komposisi kehadiran ===== */
    .seg-bar { display: flex; gap: 2px; height: 10px; border-radius: 6px; overflow: hidden; background: #EEF0F3; margin-top: 1rem; }
    .seg-bar .seg { display: block; height: 100%; }
    .seg-legend { display: flex; flex-wrap: wrap; gap: .4rem .9rem; margin-top: .85rem; font-size: .76rem; color: var(--muted); }
    .seg-dot { display: inline-block; width: 8px; height: 8px; border-radius: 999px; margin-right: .35rem; }
    .seg-hadir { background: var(--blue); }
    .seg-izin { background: #8A94A6; }
    .seg-cuti { background: var(--amber); }
    .seg-alpha { background: var(--red); }
    .seg-belum { background: #D3D7DE; }

    /* ===== Grafik batang tegak ===== */
    .vbar-chart { display: flex; gap: .9rem; height: 220px; align-items: stretch; }
    .vbar-col { flex: 1; min-width: 0; display: flex; flex-direction: column; align-items: center; gap: .35rem; }
    .vbar-value { font-size: .75rem; font-weight: 600; color: var(--navy); }
    .vbar-track { flex: 1; width: 100%; max-width: 46px; background: #EEF0F3; border-radius: 8px; display: flex; align-items: flex-end; overflow: hidden; }
    .vbar-fill { width: 100%; background: #C3CCDD; border-radius: 8px 8px 0 0; }
    .vbar-fill.current { background: var(--blue); }
    .vbar-label { font-size: .78rem; color: var(--ink); font-weight: 500; }
    .vbar-sub { font-size: .7rem; color: var(--muted); }
    .chart-note { margin-top: 1rem; font-size: .75rem; color: var(--muted); }

    /* ===== Daftar ===== */
    .count-badge { display: inline-block; min-width: 26px; text-align: center; padding: .2rem .6rem; border-radius: 999px; background: #E4E9F3; color: var(--blue); font-size: .78rem; font-weight: 700; }
    .list-row { display: flex; align-items: center; gap: .85rem; padding: .8rem 1.5rem; }
    .list-row + .list-row { border-top: 1px solid var(--line); }
    .avatar-mini { width: 34px; height: 34px; border-radius: 50%; background: #E4E9F3; color: var(--blue); display: flex; align-items: center; justify-content: center; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: .82rem; flex-shrink: 0; }
    .list-main { flex: 1; min-width: 0; }
    .list-name { font-size: .88rem; font-weight: 600; color: var(--ink); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .list-sub { font-size: .76rem; color: var(--muted); }
    .list-side { text-align: right; flex-shrink: 0; }
    .list-strong { font-size: .86rem; font-weight: 600; color: var(--navy); }
    .list-more { padding: .75rem 1.5rem; border-top: 1px solid var(--line); font-size: .8rem; color: var(--muted); text-align: center; }
@endsection