@extends('admin.layouts.personalia')

@section('page-title', 'Rekrutmen')
@section('page-subtitle', 'Kelola lowongan yang tampil di halaman Karir website.')

@section('content')

    @php
        $adaFilter = request('search') || request('status');

        // Status tayang mengikuti aturan di halaman Karir: aktif dan belum lewat batas waktu
        $statusLowongan = function ($l) {
            if (! $l->is_active) {
                return ['kelas' => 'draft', 'label' => 'Nonaktif'];
            }
            if ($l->batas_waktu && $l->batas_waktu->lt(today())) {
                return ['kelas' => 'cuti', 'label' => 'Kedaluwarsa'];
            }
            return ['kelas' => 'aktif', 'label' => 'Tayang'];
        };
    @endphp

    {{-- ================= RINGKASAN ================= --}}
    <div class="tiles">
        <div class="tile">
            <div class="tile-label">Total lowongan</div>
            <div class="tile-value">{{ $ringkasan['total'] }}</div>
        </div>
        <div class="tile">
            <div class="tile-label">Tayang di website</div>
            <div class="tile-value">{{ $ringkasan['tayang'] }}</div>
        </div>
        <div class="tile">
            <div class="tile-label">Nonaktif</div>
            <div class="tile-value">{{ $ringkasan['nonaktif'] }}</div>
        </div>
        <div class="tile">
            <div class="tile-label">Kedaluwarsa</div>
            <div class="tile-value">{{ $ringkasan['kedaluwarsa'] }}</div>
        </div>
    </div>

    <div class="notice">
        <span>Lowongan berstatus <strong>Tayang</strong> otomatis muncul di halaman Karir website.</span>
        <a href="{{ route('career') }}" target="_blank" rel="noopener">Buka halaman Karir &nearr;</a>
    </div>

    {{-- ================= DAFTAR LOWONGAN ================= --}}
    <div class="panel">
        <div class="panel-head">
            <h2>Daftar Lowongan</h2>
            <a href="{{ route('personalia.rekrutmen.create') }}" class="btn btn-primary">+ Tambah lowongan</a>
        </div>

        <form method="GET" action="{{ route('personalia.rekrutmen.index') }}" class="filter-bar">
            <input type="text" name="search" placeholder="Cari judul atau lokasi..." value="{{ request('search') }}">
            <select name="status">
                <option value="">Semua Status</option>
                <option value="tayang" {{ request('status') === 'tayang' ? 'selected' : '' }}>Tayang</option>
                <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                <option value="kedaluwarsa" {{ request('status') === 'kedaluwarsa' ? 'selected' : '' }}>Kedaluwarsa</option>
            </select>
            <button type="submit" class="btn btn-primary">Filter</button>
            @if ($adaFilter)
                <a href="{{ route('personalia.rekrutmen.index') }}" class="btn btn-secondary">Reset</a>
            @endif
        </form>

        @if ($lowongans->isEmpty())
            <div class="empty-note">
                Belum ada lowongan yang cocok.
                <a href="{{ route('personalia.rekrutmen.create') }}" class="action-view">Tambah lowongan</a>
            </div>
        @else
            <div class="table-scroll">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Lowongan</th>
                            <th>Lokasi</th>
                            <th>Batas Waktu</th>
                            <th>Status</th>
                            <th>Urutan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lowongans as $l)
                            @php $st = $statusLowongan($l); @endphp
                            <tr>
                                <td>
                                    <div class="person-name">{{ $l->judul }}</div>
                                    <div class="person-sub">{{ count($l->daftar_deskripsi) }} poin kualifikasi &middot; /karier/{{ $l->slug }}</div>
                                </td>
                                <td>{{ $l->lokasi }}</td>
                                <td>{{ $l->batas_waktu ? $l->batas_waktu->translatedFormat('d M Y') : 'Tanpa batas' }}</td>
                                <td><span class="status-pill {{ $st['kelas'] }}">{{ $st['label'] }}</span></td>
                                <td>{{ $l->order }}</td>
                                <td>
                                    <div class="row-actions">
                                        @if ($st['label'] === 'Tayang' && \Illuminate\Support\Facades\Route::has('career.show'))
                                            <a href="{{ route('career.show', $l->slug) }}" target="_blank" rel="noopener" class="btn-aksi btn-aksi-lihat" title="Lihat di website">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                                Lihat
                                            </a>
                                        @endif

                                        <a href="{{ route('personalia.rekrutmen.edit', $l) }}" class="btn-aksi btn-aksi-edit" title="Ubah lowongan ini">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.83 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                                            Edit
                                        </a>

                                        <form method="POST" action="{{ route('personalia.rekrutmen.status', $l) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn-aksi btn-aksi-status" title="{{ $l->is_active ? 'Sembunyikan dari website' : 'Tayangkan di website' }}">
                                                {{ $l->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('personalia.rekrutmen.destroy', $l) }}" onsubmit="return confirm('Hapus lowongan ini? Lowongan akan hilang dari website.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-aksi btn-aksi-hapus" title="Hapus lowongan ini">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="padding: 1.25rem 1.5rem;">
                {{ $lowongans->links() }}
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

    .notice { display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; background: #E4E9F3; border: 1px solid #D3DBEA; color: var(--navy); border-radius: 10px; padding: .8rem 1.25rem; font-size: .86rem; margin-bottom: 1.5rem; }
    .notice a { font-weight: 600; color: var(--blue); }

    /* ===== Tabel ===== */
    .table-scroll { overflow-x: auto; }
    .table-scroll table.data-table { min-width: 980px; }
    .table-scroll table.data-table th,
    .table-scroll table.data-table td { padding-left: 1rem; padding-right: 1rem; white-space: nowrap; }
    .table-scroll table.data-table th:first-child,
    .table-scroll table.data-table td:first-child { padding-left: 1.5rem; }
    .table-scroll table.data-table th:last-child,
    .table-scroll table.data-table td:last-child { padding-right: 1.5rem; }

    .person-name { font-weight: 600; color: var(--ink); }
    .person-sub { font-size: .78rem; color: var(--muted); }
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
    table.data-table .row-actions .btn-aksi-edit { background: #E4E9F3; color: var(--blue); border-color: #D3DBEA; }
    table.data-table .row-actions .btn-aksi-edit:hover { background: var(--blue); color: #fff; border-color: var(--blue); }
    table.data-table .row-actions .btn-aksi-status { background: #fff; color: var(--ink); border-color: var(--line); }
    table.data-table .row-actions .btn-aksi-status:hover { background: #F1F2F4; border-color: #C5CAD3; }
    table.data-table .row-actions .btn-aksi-hapus { background: #FBE7E8; color: var(--red); border-color: #F2CFD1; }
    table.data-table .row-actions .btn-aksi-hapus:hover { background: var(--red); color: #fff; border-color: var(--red); }
@endsection