@extends('admin.layouts.hr')

@section('page-title', 'Cuti & Izin')
@section('page-subtitle', 'Persetujuan cuti dan pemantauan izin karyawan')

@section('content')

    @php
        $inisial = fn ($nama) => strtoupper(mb_substr($nama ?: '?', 0, 1));
        $tgl = fn ($d) => $d->copy()->locale('id')->translatedFormat('d M Y');
        $rentang = fn ($p) => $p->tanggal_mulai->isSameDay($p->tanggal_selesai)
            ? $tgl($p->tanggal_mulai)
            : $tgl($p->tanggal_mulai) . ' - ' . $tgl($p->tanggal_selesai);
    @endphp

    @if ($errors->any())
        <div class="alert-error">{{ $errors->first() }}</div>
    @endif

    {{-- ================= KARTU STATISTIK ================= --}}
    <div class="stat-cards-row stat-cards-row-4">
        <div class="stat-card">
            <div class="stat-card-label">Menunggu Persetujuan</div>
            <div class="stat-card-value {{ $menunggu->isNotEmpty() ? 'attention' : '' }}">{{ $menunggu->count() }}</div>
            <div class="stat-card-sub">pengajuan</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-label">Sedang Cuti</div>
            <div class="stat-card-value">{{ $sedangCuti->count() }}</div>
            <div class="stat-card-sub">karyawan hari ini</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-label">Izin Hari Ini</div>
            <div class="stat-card-value">{{ $izinHariIni->count() }}</div>
            <div class="stat-card-sub">karyawan</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-label">Hari Cuti Bulan Ini</div>
            <div class="stat-card-value">{{ $hariCutiBulanIni }}</div>
            <div class="stat-card-sub">{{ $today->copy()->locale('id')->translatedFormat('F Y') }}</div>
        </div>
    </div>

    {{-- ================= MENUNGGU PERSETUJUAN ================= --}}
    <div class="panel">
        <div class="panel-head">
            <h2>Menunggu Persetujuan</h2>
            <span class="count-badge">{{ $menunggu->count() }}</span>
        </div>

        @if ($menunggu->isEmpty())
            <div class="empty-note">Tidak ada pengajuan cuti yang menunggu keputusan. Cuti sakit dan cuti 1 hari disetujui otomatis.</div>
        @else
            <div class="table-scroll">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Karyawan</th>
                            <th>Cuti</th>
                            <th>Keterangan</th>
                            <th>Cuti tahun ini</th>
                            <th>Diajukan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($menunggu as $p)
                            <tr data-nama="{{ $p->karyawan?->name }}"
                                data-divisi="{{ $p->karyawan?->division }}"
                                data-jenis="{{ $p->jenis_cuti }}"
                                data-rentang="{{ $rentang($p) }}"
                                data-hari="{{ $p->jumlah_hari }}"
                                data-tahun="{{ (int) ($cutiTahunIni[$p->karyawan_id] ?? 0) }}"
                                data-ket="{{ $p->keterangan }}"
                                data-url-setuju="{{ route('hr.cuti.setujui', $p) }}"
                                data-url-tolak="{{ route('hr.cuti.tolak', $p) }}">
                                <td>
                                    <div class="person">
                                        <div class="avatar-mini">{{ $inisial($p->karyawan?->name) }}</div>
                                        <div>
                                            <div class="person-name">{{ $p->karyawan?->name ?? '-' }}</div>
                                            <div class="person-sub">{{ $p->karyawan?->division ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="person-name">{{ $p->jenis_cuti }}</div>
                                    <div class="person-sub">{{ $rentang($p) }} ({{ $p->jumlah_hari }} hari)</div>
                                </td>
                                <td class="col-ket">{{ $p->keterangan ?: '-' }}</td>
                                <td>{{ (int) ($cutiTahunIni[$p->karyawan_id] ?? 0) }} hari</td>
                                <td>{{ $p->created_at->copy()->locale('id')->translatedFormat('d M Y, H:i') }}</td>
                                <td>
                                    <div class="row-actions">
                                        <button type="button" class="btn-aksi btn-aksi-setuju" data-aksi="setuju">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                                            Setujui
                                        </button>
                                        <button type="button" class="btn-aksi btn-aksi-tolak" data-aksi="tolak">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                            Tolak
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- ================= SEDANG CUTI + IZIN HARI INI ================= --}}
    <div class="two-col">
        <div class="panel">
            <div class="panel-head">
                <h2>Sedang Cuti Hari Ini</h2>
                <span class="count-badge">{{ $sedangCuti->count() }}</span>
            </div>
            @forelse ($sedangCuti as $r)
                <div class="list-row">
                    <div class="avatar-mini">{{ $inisial($r->karyawan?->name) }}</div>
                    <div class="list-main">
                        <div class="list-name">{{ $r->karyawan?->name ?? '-' }}</div>
                        <div class="list-sub">{{ $r->karyawan?->division ?? '-' }}</div>
                    </div>
                    <div class="list-side"><span class="status-pill cuti">{{ $r->jenis_cuti ?: 'Cuti' }}</span></div>
                </div>
            @empty
                <div class="empty-note">Tidak ada karyawan yang cuti hari ini.</div>
            @endforelse
        </div>

        <div class="panel">
            <div class="panel-head">
                <h2>Izin Hari Ini</h2>
                <span class="count-badge">{{ $izinHariIni->count() }}</span>
            </div>
            @forelse ($izinHariIni as $r)
                <div class="list-row">
                    <div class="avatar-mini">{{ $inisial($r->karyawan?->name) }}</div>
                    <div class="list-main">
                        <div class="list-name">{{ $r->karyawan?->name ?? '-' }}</div>
                        <div class="list-sub">{{ $r->karyawan?->division ?? '-' }}</div>
                    </div>
                    <div class="list-side"><span class="pill-izin">{{ $r->jenis_izin ?: 'Izin' }}</span></div>
                </div>
            @empty
                <div class="empty-note">Tidak ada karyawan yang izin hari ini.</div>
            @endforelse
        </div>
    </div>

    {{-- ================= RIWAYAT KEPUTUSAN ================= --}}
    <div class="panel">
        <div class="panel-head">
            <h2>Riwayat Keputusan</h2>
            <span class="count-badge">{{ $riwayat->total() }}</span>
        </div>

        @if ($riwayat->isEmpty())
            <div class="empty-note">Belum ada pengajuan cuti yang diputuskan.</div>
        @else
            <div class="table-scroll">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Karyawan</th>
                            <th>Cuti</th>
                            <th>Status</th>
                            <th>Diputuskan oleh</th>
                            <th>Tanggal keputusan</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($riwayat as $p)
                            <tr>
                                <td>
                                    <div class="person">
                                        <div class="avatar-mini">{{ $inisial($p->karyawan?->name) }}</div>
                                        <div>
                                            <div class="person-name">{{ $p->karyawan?->name ?? '-' }}</div>
                                            <div class="person-sub">{{ $p->karyawan?->division ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="person-name">{{ $p->jenis_cuti }}</div>
                                    <div class="person-sub">{{ $rentang($p) }} ({{ $p->jumlah_hari }} hari)</div>
                                </td>
                                <td>
                                    @if ($p->status === \App\Models\PengajuanCuti::DISETUJUI)
                                        <span class="pill-ok">Disetujui</span>
                                    @else
                                        <span class="pill-no">Ditolak</span>
                                    @endif
                                </td>
                                <td>{{ $p->otomatis ? 'Otomatis' : ($p->pemutus?->name ?? '-') }}</td>
                                <td>{{ $p->diputuskan_pada ? $p->diputuskan_pada->copy()->locale('id')->translatedFormat('d M Y, H:i') : '-' }}</td>
                                <td class="col-ket">{{ $p->status === \App\Models\PengajuanCuti::DITOLAK ? ($p->alasan_tolak ?: '-') : '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="padding: 1.25rem 1.5rem;">
                {{ $riwayat->links() }}
            </div>
        @endif
    </div>

    {{-- ================= DIALOG KEPUTUSAN (setujui / tolak) ================= --}}
    <dialog id="dialogKeputusan" class="dialog" aria-labelledby="dlgJudul">
        <form method="POST" id="formKeputusan" action="">
            @csrf

            <div class="dlg-head">
                <span class="dlg-icon dlg-icon-setuju" id="ikonSetuju">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                </span>
                <span class="dlg-icon dlg-icon-tolak" id="ikonTolak" hidden>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </span>
                <div>
                    <h3 id="dlgJudul"></h3>
                    <p class="dlg-sub" id="dlgSub"></p>
                </div>
            </div>

            <div class="ringkas">
                <div class="ringkas-orang">
                    <div class="avatar-mini" data-f="inisial"></div>
                    <div>
                        <div class="person-name" data-f="nama"></div>
                        <div class="person-sub" data-f="divisi"></div>
                    </div>
                </div>
                <dl class="ringkas-grid">
                    <div><dt>Jenis cuti</dt><dd data-f="jenis"></dd></div>
                    <div><dt>Lama</dt><dd data-f="lama"></dd></div>
                    <div><dt>Tanggal</dt><dd data-f="rentang"></dd></div>
                    <div><dt>Cuti tahun ini</dt><dd data-f="tahun"></dd></div>
                </dl>
                <div class="ringkas-ket">
                    <span class="ringkas-ket-label">Keterangan pemohon</span>
                    <span data-f="ket"></span>
                </div>
            </div>

            {{-- Mode setujui --}}
            <div class="info-setuju" id="blokSetuju">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                <span id="infoSetuju"></span>
            </div>

            {{-- Mode tolak --}}
            <div class="blok-tolak" id="blokTolak" hidden>
                <label for="alasan_tolak">Alasan penolakan</label>
                <div class="chips" role="group" aria-label="Alasan cepat">
                    <button type="button" class="chip" data-teks="Jadwal kerja sedang padat">Jadwal kerja padat</button>
                    <button type="button" class="chip" data-teks="Kuota cuti sudah habis">Kuota cuti habis</button>
                    <button type="button" class="chip" data-teks="Terlalu banyak karyawan yang cuti di waktu yang sama">Terlalu banyak yang cuti</button>
                    <button type="button" class="chip" data-teks="Perlu dokumen pendukung">Perlu dokumen</button>
                </div>
                <textarea name="alasan_tolak" id="alasan_tolak" rows="3" maxlength="500" placeholder="Tulis alasan, atau pilih salah satu di atas..."></textarea>
                <div class="hitung" id="hitungAlasan">0/500</div>
            </div>

            <div class="dlg-actions">
                <button type="button" class="btn btn-secondary" id="batalKeputusan">Batal</button>
                <button type="submit" class="btn btn-success" id="kirimKeputusan">Ya, Setujui</button>
            </div>
        </form>
    </dialog>

@endsection

@section('extra-style')
    .stat-cards-row-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 1.75rem; }
    .stat-card { background: #fff; border: 1px solid var(--line); border-radius: 12px; padding: 1.15rem 1.25rem; }
    .stat-card-label { font-size: .74rem; color: var(--muted); text-transform: uppercase; letter-spacing: .03em; margin-bottom: .5rem; }
    .stat-card-value { font-family: 'Poppins', sans-serif; font-size: 1.6rem; font-weight: 700; color: var(--navy); }
    .stat-card-value.attention { color: var(--amber); }
    .stat-card-sub { font-size: .78rem; color: var(--muted); margin-top: .3rem; }
    @media (max-width: 1200px) { .stat-cards-row-4 { grid-template-columns: repeat(2, 1fr); } }

    .alert-error { background: #FBE7E8; border: 1px solid #F2CFD1; color: var(--red); border-radius: 10px; padding: .85rem 1.25rem; font-size: .85rem; margin-bottom: 1.5rem; }
    .count-badge { display: inline-block; min-width: 26px; text-align: center; padding: .2rem .6rem; border-radius: 999px; background: #E4E9F3; color: var(--blue); font-size: .78rem; font-weight: 700; }

    /* ===== Tabel ===== */
    .table-scroll { overflow-x: auto; }
    .table-scroll table.data-table { min-width: 900px; }
    .table-scroll table.data-table th,
    .table-scroll table.data-table td { padding-left: 1rem; padding-right: 1rem; vertical-align: middle; }
    .table-scroll table.data-table th:first-child,
    .table-scroll table.data-table td:first-child { padding-left: 1.5rem; }
    .table-scroll table.data-table th:last-child,
    .table-scroll table.data-table td:last-child { padding-right: 1.5rem; }
    .col-ket { max-width: 240px; font-size: .84rem; color: var(--ink); }

    .person { display: flex; align-items: center; gap: .75rem; }
    .person-name { font-weight: 600; color: var(--ink); }
    .person-sub { font-size: .78rem; color: var(--muted); }
    .avatar-mini { width: 34px; height: 34px; border-radius: 50%; background: #E4E9F3; color: var(--blue); display: flex; align-items: center; justify-content: center; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: .82rem; flex-shrink: 0; }

    /* ===== Tombol aksi ===== */
    table.data-table .row-actions { align-items: center; gap: .5rem; }
    table.data-table .row-actions .btn-aksi {
        display: inline-flex; align-items: center; gap: .4rem; padding: .45rem .85rem; border-radius: 8px; border: 1px solid transparent;
        font-family: 'Inter', sans-serif; font-size: .78rem; font-weight: 600; line-height: 1; cursor: pointer;
        transition: background .15s, color .15s, border-color .15s;
    }
    table.data-table .row-actions .btn-aksi svg { width: 13px; height: 13px; flex-shrink: 0; }
    table.data-table .row-actions .btn-aksi-setuju { background: #E4F1EA; color: var(--green); border-color: #BFE3CD; }
    table.data-table .row-actions .btn-aksi-setuju:hover { background: var(--green); color: #fff; border-color: var(--green); }
    table.data-table .row-actions .btn-aksi-tolak { background: #FBE7E8; color: var(--red); border-color: #F2CFD1; }
    table.data-table .row-actions .btn-aksi-tolak:hover { background: var(--red); color: #fff; border-color: var(--red); }
    table.data-table .row-actions .btn-aksi:focus-visible { outline: 2px solid var(--blue); outline-offset: 2px; }

    /* ===== Daftar ===== */
    .list-row { display: flex; align-items: center; gap: .85rem; padding: .8rem 1.5rem; }
    .list-row + .list-row { border-top: 1px solid var(--line); }
    .list-main { flex: 1; min-width: 0; }
    .list-name { font-size: .88rem; font-weight: 600; color: var(--ink); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .list-sub { font-size: .76rem; color: var(--muted); }
    .list-side { text-align: right; flex-shrink: 0; }

    .pill-ok, .pill-no, .pill-izin { display: inline-block; padding: .25rem .65rem; border-radius: 6px; font-size: .76rem; font-weight: 600; }
    .pill-ok { background: #E4F1EA; color: var(--green); }
    .pill-no { background: #FBE7E8; color: var(--red); }
    .pill-izin { background: #E4E9F3; color: var(--blue); }

    .btn-success { background: var(--green); color: #fff; }
    .btn-success:hover { background: #17603B; }
    .btn-danger { background: var(--red); color: #fff; }
    .btn-danger:hover { background: #8F2D2D; }
    .btn:disabled { opacity: .6; cursor: not-allowed; }
    .btn:focus-visible { outline: 2px solid var(--blue); outline-offset: 2px; }

    /* ===== Dialog keputusan ===== */
    .dialog { border: none; border-radius: 16px; padding: 0; width: min(520px, 94vw); box-shadow: 0 24px 60px rgba(15, 42, 74, .3); color: var(--ink); }
    .dialog::backdrop { background: rgba(15, 42, 74, .5); }
    .dialog form { padding: 1.5rem; }
    .dlg-head { display: flex; align-items: center; gap: .9rem; margin-bottom: 1.1rem; }
    .dlg-head h3 { font-size: 1.1rem; color: var(--navy); }
    .dlg-sub { margin: .15rem 0 0; font-size: .82rem; color: var(--muted); }
    .dlg-icon { width: 44px; height: 44px; border-radius: 50%; flex-shrink: 0; display: flex; align-items: center; justify-content: center; }
    .dlg-icon svg { width: 20px; height: 20px; }
    .dlg-icon-setuju { background: #E4F1EA; color: var(--green); }
    .dlg-icon-tolak { background: #FBE7E8; color: var(--red); }
    .dlg-icon[hidden] { display: none; }

    .ringkas { background: var(--bg); border: 1px solid var(--line); border-radius: 12px; padding: 1rem 1.1rem; }
    .ringkas-orang { display: flex; align-items: center; gap: .75rem; padding-bottom: .85rem; border-bottom: 1px solid var(--line); }
    .ringkas-grid { display: grid; grid-template-columns: 1fr 1fr; gap: .8rem 1rem; margin: .85rem 0 0; }
    .ringkas-grid dt { font-size: .74rem; color: var(--muted); margin-bottom: .1rem; }
    .ringkas-grid dd { margin: 0; font-size: .88rem; font-weight: 600; color: var(--ink); }
    .ringkas-ket { display: flex; flex-direction: column; gap: .15rem; margin-top: .85rem; padding-top: .85rem; border-top: 1px solid var(--line); font-size: .85rem; }
    .ringkas-ket-label { font-size: .74rem; color: var(--muted); }

    .info-setuju { display: flex; align-items: flex-start; gap: .6rem; margin-top: 1rem; padding: .8rem 1rem; border-radius: 10px; background: #E4F1EA; color: var(--green); font-size: .84rem; }
    .info-setuju svg { width: 18px; height: 18px; flex-shrink: 0; margin-top: .05rem; }
    .info-setuju[hidden], .blok-tolak[hidden] { display: none; }

    .blok-tolak { margin-top: 1rem; }
    .blok-tolak label { display: block; font-size: .82rem; font-weight: 600; color: var(--navy); margin-bottom: .45rem; }
    .blok-tolak textarea { width: 100%; padding: .65rem .85rem; border: 1px solid var(--line); border-radius: 8px; font-size: .88rem; font-family: 'Inter', sans-serif; resize: vertical; }
    .blok-tolak textarea:focus { outline: none; border-color: var(--blue); box-shadow: 0 0 0 3px rgba(30,91,148,.12); }
    .chips { display: flex; flex-wrap: wrap; gap: .4rem; margin-bottom: .6rem; }
    .chip { border: 1px solid var(--line); background: #fff; color: var(--ink); border-radius: 999px; padding: .3rem .75rem; font-family: 'Inter', sans-serif; font-size: .76rem; font-weight: 500; cursor: pointer; transition: background .15s, border-color .15s; }
    .chip:hover { background: #F3F6F9; border-color: var(--blue); }
    .chip:focus-visible { outline: 2px solid var(--blue); outline-offset: 2px; }
    .hitung { margin-top: .3rem; font-size: .72rem; color: var(--muted); text-align: right; }

    .dlg-actions { display: flex; justify-content: flex-end; gap: .6rem; margin-top: 1.25rem; }

    @media (max-width: 480px) { .ringkas-grid { grid-template-columns: 1fr; } }
@endsection

@section('extra-script')
    <script>
        (function () {
            var dialog = document.getElementById('dialogKeputusan');
            var form = document.getElementById('formKeputusan');
            var judul = document.getElementById('dlgJudul');
            var sub = document.getElementById('dlgSub');
            var ikonSetuju = document.getElementById('ikonSetuju');
            var ikonTolak = document.getElementById('ikonTolak');
            var blokSetuju = document.getElementById('blokSetuju');
            var blokTolak = document.getElementById('blokTolak');
            var infoSetuju = document.getElementById('infoSetuju');
            var alasan = document.getElementById('alasan_tolak');
            var hitung = document.getElementById('hitungAlasan');
            var kirim = document.getElementById('kirimKeputusan');

            function isiRingkasan(d) {
                var nilai = {
                    inisial: (d.nama || '?').charAt(0).toUpperCase(),
                    nama: d.nama || '-',
                    divisi: d.divisi || '-',
                    jenis: d.jenis,
                    lama: d.hari + ' hari kerja',
                    rentang: d.rentang,
                    tahun: d.tahun + ' hari',
                    ket: d.ket || 'Tidak ada keterangan'
                };
                dialog.querySelectorAll('[data-f]').forEach(function (el) {
                    el.textContent = nilai[el.getAttribute('data-f')];
                });
            }

            function perbaruiHitung() {
                hitung.textContent = alasan.value.length + '/500';
            }

            function buka(aksi, baris) {
                var d = baris.dataset;
                var setuju = aksi === 'setuju';

                isiRingkasan(d);
                form.action = setuju ? d.urlSetuju : d.urlTolak;

                judul.textContent = setuju ? 'Setujui pengajuan cuti' : 'Tolak pengajuan cuti';
                sub.textContent = setuju
                    ? 'Periksa ringkasan di bawah sebelum memutuskan.'
                    : 'Alasan penolakan akan terlihat oleh Personalia.';

                ikonSetuju.hidden = !setuju;
                ikonTolak.hidden = setuju;
                blokSetuju.hidden = !setuju;
                blokTolak.hidden = setuju;

                infoSetuju.textContent = 'Setelah disetujui, ' + d.hari + ' hari cuti langsung tercatat di kehadiran ' + (d.nama || 'karyawan') + '.';

                // Kolom alasan hanya ikut terkirim saat menolak
                alasan.disabled = setuju;
                alasan.required = !setuju;
                alasan.value = '';
                perbaruiHitung();

                kirim.textContent = setuju ? 'Ya, Setujui' : 'Tolak Pengajuan';
                kirim.className = 'btn ' + (setuju ? 'btn-success' : 'btn-danger');
                kirim.disabled = false;

                dialog.showModal();
                (setuju ? kirim : alasan).focus();
            }

            document.querySelectorAll('[data-aksi]').forEach(function (tombol) {
                tombol.addEventListener('click', function () {
                    buka(tombol.getAttribute('data-aksi'), tombol.closest('tr'));
                });
            });

            document.querySelectorAll('.chip').forEach(function (chip) {
                chip.addEventListener('click', function () {
                    var teks = chip.getAttribute('data-teks');
                    var sekarang = alasan.value.trim();
                    alasan.value = sekarang ? sekarang.replace(/[.]$/, '') + '. ' + teks : teks;
                    perbaruiHitung();
                    alasan.focus();
                });
            });

            alasan.addEventListener('input', perbaruiHitung);

            document.getElementById('batalKeputusan').addEventListener('click', function () {
                dialog.close();
            });

            // Klik di luar kotak menutup dialog
            dialog.addEventListener('click', function (e) {
                if (e.target === dialog) dialog.close();
            });

            // Cegah klik ganda saat mengirim
            form.addEventListener('submit', function () {
                kirim.disabled = true;
                kirim.textContent = 'Memproses...';
            });
        })();
    </script>
@endsection