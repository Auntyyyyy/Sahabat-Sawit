@extends($layout)

@section('page-title', 'Dokumen')
@section('page-subtitle', $kelola ? 'Kelola dokumen karyawan dan dokumen perusahaan.' : 'Dokumen karyawan dan dokumen perusahaan (hanya lihat dan unduh).')

@section('content')

    @php
        $adaFilter = request('search') || request('jenis') || request('kategori') || request('status');

        // Warna label ekstensi file
        $warnaEkstensi = function ($ext) {
            return match (true) {
                $ext === 'PDF' => 'ext-pdf',
                in_array($ext, ['DOC', 'DOCX']) => 'ext-word',
                in_array($ext, ['XLS', 'XLSX']) => 'ext-excel',
                in_array($ext, ['JPG', 'JPEG', 'PNG']) => 'ext-gambar',
                default => 'ext-lain',
            };
        };

        $kelasStatus = ['berlaku' => 'aktif', 'segera' => 'cuti', 'kedaluwarsa' => 'nonaktif', 'tanpa' => 'draft'];
    @endphp

    {{-- ================= RINGKASAN ================= --}}
    <div class="tiles">
        <div class="tile">
            <div class="tile-label">Total dokumen</div>
            <div class="tile-value">{{ $ringkasan['total'] }}</div>
        </div>
        <div class="tile">
            <div class="tile-label">Dokumen perusahaan</div>
            <div class="tile-value">{{ $ringkasan['perusahaan'] }}</div>
        </div>
        <div class="tile">
            <div class="tile-label">Dokumen karyawan</div>
            <div class="tile-value">{{ $ringkasan['karyawan'] }}</div>
        </div>
        <div class="tile">
            <div class="tile-label">Segera berakhir</div>
            <div class="tile-value">{{ $ringkasan['segera'] }}</div>
            <div class="tile-sub">dalam {{ $hariPeringatan }} hari</div>
        </div>
        <div class="tile">
            <div class="tile-label">Kedaluwarsa</div>
            <div class="tile-value">{{ $ringkasan['kedaluwarsa'] }}</div>
        </div>
    </div>

    @if ($ringkasan['segera'] + $ringkasan['kedaluwarsa'] > 0)
        <div class="notice">
            <span>
                Ada <strong>{{ $ringkasan['segera'] }}</strong> dokumen yang segera berakhir
                dan <strong>{{ $ringkasan['kedaluwarsa'] }}</strong> yang sudah kedaluwarsa.
            </span>
            <a href="{{ route($prefix . '.index', ['status' => $ringkasan['kedaluwarsa'] > 0 ? 'kedaluwarsa' : 'segera']) }}">Lihat</a>
        </div>
    @endif

    {{-- ================= DAFTAR DOKUMEN ================= --}}
    <div class="panel">
        <div class="panel-head">
            <h2>Daftar Dokumen <span class="count-badge">{{ $dokumens->total() }}</span></h2>
            @if ($kelola)
                <a href="{{ route('personalia.dokumen.create') }}" class="btn btn-primary">+ Unggah dokumen</a>
            @endif
        </div>

        <form method="GET" action="{{ route($prefix . '.index') }}" class="filter-bar">
            <input type="text" name="search" placeholder="Cari judul, nama file, atau nama karyawan..." value="{{ request('search') }}">

            <select name="jenis">
                <option value="">Semua Jenis</option>
                <option value="perusahaan" {{ request('jenis') === 'perusahaan' ? 'selected' : '' }}>Dokumen perusahaan</option>
                <option value="karyawan" {{ request('jenis') === 'karyawan' ? 'selected' : '' }}>Dokumen karyawan</option>
            </select>

            <select name="kategori">
                <option value="">Semua Kategori</option>
                @foreach ($semuaKategori as $k)
                    <option value="{{ $k }}" {{ request('kategori') === $k ? 'selected' : '' }}>{{ $k }}</option>
                @endforeach
            </select>

            <select name="status">
                <option value="">Semua Masa Berlaku</option>
                <option value="segera" {{ request('status') === 'segera' ? 'selected' : '' }}>Segera berakhir</option>
                <option value="kedaluwarsa" {{ request('status') === 'kedaluwarsa' ? 'selected' : '' }}>Kedaluwarsa</option>
            </select>

            <button type="submit" class="btn btn-primary">Filter</button>
            @if ($adaFilter)
                <a href="{{ route($prefix . '.index') }}" class="btn btn-secondary">Reset</a>
            @endif
        </form>

        @if ($dokumens->isEmpty())
            <div class="empty-note">
                Belum ada dokumen yang cocok.
                @if ($kelola)
                    <a href="{{ route('personalia.dokumen.create') }}" class="action-view">Unggah dokumen</a>
                @endif
            </div>
        @else
            <div class="table-scroll">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Dokumen</th>
                            <th>Pemilik</th>
                            <th>Kategori</th>
                            <th>Masa Berlaku</th>
                            <th>Diunggah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dokumens as $d)
                            @php $st = $d->status_berlaku; @endphp
                            <tr>
                                <td>
                                    <div class="dok">
                                        <span class="ext {{ $warnaEkstensi($d->ekstensi) }}">{{ $d->ekstensi }}</span>
                                        <div>
                                            <div class="dok-judul">{{ $d->judul }}</div>
                                            <div class="dok-sub">{{ $d->nama_asli }} &middot; {{ $d->ukuran_terbaca }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if ($d->is_perusahaan)
                                        <span class="pemilik-perusahaan">Perusahaan</span>
                                    @else
                                        <div class="dok-judul">{{ $d->karyawan?->name ?? '-' }}</div>
                                        <div class="dok-sub">{{ $d->karyawan?->division ?? '-' }}</div>
                                    @endif
                                </td>
                                <td><span class="kat-pill">{{ $d->kategori }}</span></td>
                                <td>
                                    <span class="status-pill {{ $kelasStatus[$st['kode']] }}">{{ $st['label'] }}</span>
                                    @if ($d->tanggal_kedaluwarsa)
                                        <div class="dok-sub" style="margin-top:.25rem;">{{ $d->tanggal_kedaluwarsa->translatedFormat('d M Y') }}</div>
                                    @endif
                                </td>
                                <td>
                                    <div>{{ $d->created_at->translatedFormat('d M Y') }}</div>
                                    <div class="dok-sub">{{ $d->pengunggah?->name ?? '-' }}</div>
                                </td>
                                <td>
                                    <div class="row-actions">
                                        @if ($d->bisa_dilihat)
                                            <a href="{{ route($prefix . '.berkas', [$d, 'lihat' => 1]) }}" target="_blank" rel="noopener" class="btn-aksi btn-aksi-lihat" title="Buka di tab baru">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                                Lihat
                                            </a>
                                        @endif

                                        <a href="{{ route($prefix . '.berkas', $d) }}" class="btn-aksi btn-aksi-unduh" title="Unduh file">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="M7 10l5 5 5-5"/><path d="M12 15V3"/></svg>
                                            Unduh
                                        </a>

                                        @if ($kelola)
                                            <a href="{{ route('personalia.dokumen.edit', $d) }}" class="btn-aksi btn-aksi-edit" title="Ubah dokumen">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.83 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('personalia.dokumen.destroy', $d) }}" onsubmit="return confirm('Hapus dokumen ini? File-nya ikut terhapus permanen.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-aksi btn-aksi-hapus" title="Hapus dokumen">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="padding: 1.25rem 1.5rem;">
                {{ $dokumens->links() }}
            </div>
        @endif
    </div>

@endsection

@section('extra-style')
    /* ===== Ringkasan ===== */
    .tiles { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
    .tile { background: var(--card); border: 1px solid var(--line); border-radius: 12px; padding: 1rem 1.2rem; }
    .tile-label { font-size: .78rem; font-weight: 600; color: var(--muted); margin-bottom: .4rem; }
    .tile-value { font-family: 'Poppins', sans-serif; font-size: 1.7rem; font-weight: 700; color: var(--navy); line-height: 1.1; }
    .tile-sub { font-size: .74rem; color: var(--muted); margin-top: .3rem; }

    .notice { display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; background: #FDF1DF; border: 1px solid #F2DDB8; color: #7A5316; border-radius: 10px; padding: .8rem 1.25rem; font-size: .86rem; margin-bottom: 1.5rem; }
    .notice a { font-weight: 600; color: var(--blue); }

    .count-badge { display: inline-block; min-width: 26px; text-align: center; padding: .2rem .6rem; border-radius: 999px; background: #E4E9F3; color: var(--blue); font-size: .78rem; font-weight: 700; margin-left: .35rem; vertical-align: middle; }

    /* ===== Tabel ===== */
    .table-scroll { overflow-x: auto; }
    .table-scroll table.data-table { min-width: 1020px; }
    .table-scroll table.data-table th,
    .table-scroll table.data-table td { padding-left: 1rem; padding-right: 1rem; white-space: nowrap; vertical-align: middle; }
    .table-scroll table.data-table th:first-child,
    .table-scroll table.data-table td:first-child { padding-left: 1.5rem; }
    .table-scroll table.data-table th:last-child,
    .table-scroll table.data-table td:last-child { padding-right: 1.5rem; }

    .dok { display: flex; align-items: center; gap: .8rem; }
    .dok-judul { font-weight: 600; color: var(--ink); }
    .dok-sub { font-size: .76rem; color: var(--muted); }
    .ext { display: inline-flex; align-items: center; justify-content: center; width: 42px; height: 36px; border-radius: 8px; font-size: .68rem; font-weight: 700; letter-spacing: .02em; flex-shrink: 0; }
    .ext-pdf { background: #FBE7E8; color: var(--red); }
    .ext-word { background: #E4E9F3; color: var(--blue); }
    .ext-excel { background: #E4F1EA; color: var(--green); }
    .ext-gambar { background: #FDF1DF; color: #B9791F; }
    .ext-lain { background: #ECEEF2; color: var(--muted); }

    .pemilik-perusahaan { display: inline-block; padding: .25rem .65rem; border-radius: 6px; background: #14161B; color: #fff; font-size: .76rem; font-weight: 600; }
    .kat-pill { display: inline-block; padding: .25rem .65rem; border-radius: 6px; background: #ECEEF2; color: var(--ink); font-size: .76rem; font-weight: 600; }
    .status-pill.draft { background: #ECEEF2; color: var(--muted); }

    /* ===== Tombol aksi ===== */
    table.data-table .row-actions { align-items: center; gap: .5rem; }
    table.data-table .row-actions form { margin: 0; }
    table.data-table .row-actions .btn-aksi {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .4rem .75rem; border-radius: 8px; border: 1px solid transparent;
        font-family: 'Inter', sans-serif; font-size: .78rem; font-weight: 600; line-height: 1;
        cursor: pointer; transition: background .15s, color .15s, border-color .15s;
    }
    table.data-table .row-actions .btn-aksi svg { width: 14px; height: 14px; flex-shrink: 0; }
    table.data-table .row-actions .btn-aksi-lihat { background: #ECEEF2; color: var(--ink); border-color: #DADDE2; }
    table.data-table .row-actions .btn-aksi-lihat:hover { background: var(--navy); color: #fff; border-color: var(--navy); }
    table.data-table .row-actions .btn-aksi-unduh { background: #fff; color: var(--ink); border-color: var(--line); }
    table.data-table .row-actions .btn-aksi-unduh:hover { background: #F1F2F4; border-color: #C5CAD3; }
    table.data-table .row-actions .btn-aksi-edit { background: #E4E9F3; color: var(--blue); border-color: #D3DBEA; }
    table.data-table .row-actions .btn-aksi-edit:hover { background: var(--blue); color: #fff; border-color: var(--blue); }
    table.data-table .row-actions .btn-aksi-hapus { background: #FBE7E8; color: var(--red); border-color: #F2CFD1; }
    table.data-table .row-actions .btn-aksi-hapus:hover { background: var(--red); color: #fff; border-color: var(--red); }
@endsection