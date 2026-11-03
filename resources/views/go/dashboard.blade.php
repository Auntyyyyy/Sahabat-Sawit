@extends('layouts.go')

@section('page-title', 'Dashboard General Affair')
@section('page-subtitle', 'Ringkasan ATK, fasilitas, surat, dan aset')

@section('extra-style')
.section-title { font-size: 1rem; color: var(--rose-dark); margin: 0 0 .8rem; }

.link-strong { color: var(--rose-dark); font-weight: 600; }
.link-strong:hover { color: var(--pink); }
.cell-sub { font-size: .78rem; color: var(--muted); margin-top: .15rem; }

.action-cards { display: grid; grid-template-columns: repeat(4, 1fr); gap: .9rem; margin-bottom: 1.75rem; }
.action-card {
    display: flex; align-items: center; gap: .9rem;
    padding: 1.1rem 1.25rem; background: var(--card);
    border: 1px solid var(--line); border-radius: 12px; color: var(--ink);
    transition: border-color .15s, box-shadow .15s, transform .15s;
}
.action-card:hover { border-color: var(--pink-light); box-shadow: 0 6px 18px rgba(122, 31, 75, .08); transform: translateY(-1px); }
.action-card .ac-count {
    font-family: 'Poppins', sans-serif; font-size: 1.6rem; font-weight: 700;
    color: var(--rose-dark); min-width: 2.2rem; line-height: 1;
}
.action-card .ac-label { font-size: .84rem; font-weight: 600; }
.action-card .ac-hint { font-size: .74rem; color: var(--muted); margin-top: .15rem; }
.action-card.has-task { border-color: #F1D9AE; background: #FFFBF3; }
.action-card.has-task .ac-count { color: #B9791F; }

@media (max-width: 960px) { .action-cards { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 520px) { .action-cards { grid-template-columns: 1fr; } }
@endsection

@section('content')

<div style="display:flex; justify-content:flex-end; gap:.6rem; margin-bottom:1.25rem; flex-wrap:wrap;">
    <a href="{{ route('go.atk-requests.create') }}" class="btn btn-secondary">Permintaan ATK Baru</a>
    <a href="{{ route('go.facility-loans.create') }}" class="btn btn-secondary">Peminjaman Baru</a>
    <a href="{{ route('go.letters.create') }}" class="btn btn-secondary">Catat Surat</a>
    <a href="{{ route('go.assets.create') }}" class="btn btn-primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;"><path d="M12 5v14M5 12h14"/></svg>
        Tambah Aset
    </a>
</div>

{{-- ============ PERLU TINDAKAN ============ --}}
<h2 class="section-title">Perlu Tindakan</h2>

<div class="action-cards">
    <a href="{{ route('go.atk-requests.index', ['status' => 'Diajukan']) }}" class="action-card {{ $atkDiajukan > 0 ? 'has-task' : '' }}">
        <div class="ac-count">{{ $atkDiajukan }}</div>
        <div>
            <div class="ac-label">Permintaan ATK</div>
            <div class="ac-hint">Menunggu persetujuan</div>
        </div>
    </a>

    <a href="{{ route('go.facility-loans.index', ['status' => 'Diajukan']) }}" class="action-card {{ $fasMenunggu > 0 ? 'has-task' : '' }}">
        <div class="ac-count">{{ $fasMenunggu }}</div>
        <div>
            <div class="ac-label">Peminjaman Fasilitas</div>
            <div class="ac-hint">Menunggu persetujuan</div>
        </div>
    </a>

    <a href="{{ route('go.letters.index', ['status' => 'Baru']) }}" class="action-card {{ $suratBaru > 0 ? 'has-task' : '' }}">
        <div class="ac-count">{{ $suratBaru }}</div>
        <div>
            <div class="ac-label">Surat Baru</div>
            <div class="ac-hint">Belum diproses</div>
        </div>
    </a>

    <a href="{{ route('go.atk-items.index', ['stok' => 'menipis']) }}" class="action-card {{ $atkMenipis > 0 ? 'has-task' : '' }}">
        <div class="ac-count">{{ $atkMenipis }}</div>
        <div>
            <div class="ac-label">Stok ATK Menipis</div>
            <div class="ac-hint">Perlu restok</div>
        </div>
    </a>
</div>

{{-- ============ ATK & PERMINTAAN ============ --}}
<h2 class="section-title">ATK & Permintaan</h2>

<div class="summary-row">
    <div class="summary-item">
        <p class="label">Permintaan Menunggu</p>
        <p class="value {{ $atkMenunggu > 0 ? 'attention' : '' }}">{{ $atkMenunggu }}</p>
    </div>
    <div class="summary-item">
        <p class="label">Permintaan Bulan Ini</p>
        <p class="value">{{ $atkBulanIni }}</p>
    </div>
    <div class="summary-item">
        <p class="label">Jenis Barang ATK</p>
        <p class="value">{{ $atkJenis }}</p>
    </div>
    <div class="summary-item">
        <p class="label">Stok Menipis / Habis</p>
        <p class="value {{ $atkMenipis > 0 ? 'attention' : '' }}">{{ $atkMenipis }}</p>
    </div>
</div>

<div class="two-col">
    <div class="panel">
        <div class="panel-head">
            <h2>Permintaan Menunggu</h2>
            <a href="{{ route('go.atk-requests.index') }}" class="panel-link">Lihat Semua →</a>
        </div>

        @if($permintaanMenunggu->isNotEmpty())
        <table class="data-table">
            <thead>
                <tr><th>Nomor</th><th>Pemohon</th><th>Status</th></tr>
            </thead>
            <tbody>
                @foreach($permintaanMenunggu as $req)
                <tr>
                    <td><a href="{{ route('go.atk-requests.show', $req) }}" class="link-strong">{{ $req->nomor }}</a></td>
                    <td>{{ $req->pemohon_nama }}</td>
                    <td><span class="status-pill {{ $req->statusPillClass() }}">{{ $req->status }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-note">Tidak ada permintaan yang menunggu.</div>
        @endif
    </div>

    <div class="panel">
        <div class="panel-head">
            <h2>Stok Menipis / Habis</h2>
            <a href="{{ route('go.atk-items.index', ['stok' => 'menipis']) }}" class="panel-link">Lihat Semua →</a>
        </div>

        @if($stokMenipisList->isNotEmpty())
        <table class="data-table">
            <thead>
                <tr><th>Barang</th><th>Stok</th><th>Kondisi</th></tr>
            </thead>
            <tbody>
                @foreach($stokMenipisList as $item)
                <tr>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->stok }} {{ $item->satuan }}</td>
                    <td><span class="status-pill {{ $item->stokPillClass() }}">{{ $item->stokLabel() }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-note">Semua stok masih aman.</div>
        @endif
    </div>
</div>

{{-- ============ FASILITAS & PEMINJAMAN ============ --}}
<h2 class="section-title">Fasilitas & Peminjaman</h2>

<div class="summary-row">
    <div class="summary-item">
        <p class="label">Sedang Digunakan</p>
        <p class="value">{{ $fasSedangDigunakan }}</p>
    </div>
    <div class="summary-item">
        <p class="label">Menunggu Persetujuan</p>
        <p class="value {{ $fasMenunggu > 0 ? 'attention' : '' }}">{{ $fasMenunggu }}</p>
    </div>
    <div class="summary-item">
        <p class="label">Mulai ≤ {{ $hariMendatang }} Hari</p>
        <p class="value">{{ $fasMendatang }}</p>
    </div>
    <div class="summary-item">
        <p class="label">Perlu Perhatian / Perawatan</p>
        <p class="value {{ $fasPerluPerhatian > 0 ? 'attention' : '' }}">{{ $fasPerluPerhatian }}</p>
    </div>
</div>

<div class="two-col">
    <div class="panel">
        <div class="panel-head">
            <h2>Peminjaman Berjalan & Mendatang</h2>
            <a href="{{ route('go.facility-loans.index') }}" class="panel-link">Lihat Semua →</a>
        </div>

        @if($peminjamanAktif->isNotEmpty())
        <table class="data-table">
            <thead>
                <tr><th>Fasilitas</th><th>Periode</th><th>Status</th></tr>
            </thead>
            <tbody>
                @foreach($peminjamanAktif as $loan)
                <tr>
                    <td>
                        <a href="{{ route('go.facility-loans.show', $loan) }}" class="link-strong">{{ $loan->facility?->nama ?? '—' }}</a>
                        <div class="cell-sub">{{ $loan->peminjam_nama }} · {{ $loan->peminjam_divisi }}</div>
                    </td>
                    <td style="white-space:nowrap;">
                        {{ $loan->tanggal_mulai->translatedFormat('d M') }} – {{ $loan->tanggal_selesai->translatedFormat('d M') }}
                    </td>
                    <td><span class="status-pill {{ $loan->statusPillClass() }}">{{ $loan->status }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-note">Tidak ada peminjaman yang berjalan atau akan datang.</div>
        @endif
    </div>

    <div class="panel">
        <div class="panel-head">
            <h2>Perlu Perhatian & Perawatan</h2>
            <a href="{{ route('go.facilities.index') }}" class="panel-link">Lihat Semua →</a>
        </div>

        @if($fasilitasPerhatian->isNotEmpty())
        <table class="data-table">
            <thead>
                <tr><th>Fasilitas</th><th>Keterangan</th></tr>
            </thead>
            <tbody>
                @foreach($fasilitasPerhatian as $fasilitas)
                <tr>
                    <td>
                        <a href="{{ route('go.facilities.show', $fasilitas) }}" class="link-strong">{{ $fasilitas->nama }}</a>
                        <div class="cell-sub">{{ $fasilitas->lokasi ?: $fasilitas->kategori }}</div>
                    </td>
                    <td>
                        @if($fasilitas->kondisi !== 'Baik')
                            <span class="status-pill {{ $fasilitas->kondisiBadgeClass() }}">{{ $fasilitas->kondisi }}</span>
                        @else
                            <span class="status-pill cuti">Perlu perawatan</span>
                        @endif
                        <div class="cell-sub">
                            @if($fasilitas->tanggal_perawatan_terakhir)
                                Terakhir dirawat {{ $fasilitas->tanggal_perawatan_terakhir->locale('id')->diffForHumans() }}
                            @else
                                Belum pernah dirawat
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-note">Semua fasilitas dalam kondisi baik dan perawatannya terkini.</div>
        @endif
    </div>
</div>

{{-- ============ SURAT-MENYURAT ============ --}}
<h2 class="section-title">Surat-Menyurat</h2>

<div class="summary-row">
    <div class="summary-item">
        <p class="label">Surat Bulan Ini</p>
        <p class="value">{{ $suratBulanIni }}</p>
    </div>
    <div class="summary-item">
        <p class="label">Masuk Bulan Ini</p>
        <p class="value">{{ $suratMasuk }}</p>
    </div>
    <div class="summary-item">
        <p class="label">Keluar Bulan Ini</p>
        <p class="value">{{ $suratKeluar }}</p>
    </div>
    <div class="summary-item">
        <p class="label">Belum Diproses</p>
        <p class="value {{ $suratBaru > 0 ? 'attention' : '' }}">{{ $suratBaru }}</p>
    </div>
</div>

<div class="panel">
    <div class="panel-head">
        <h2>Surat Terbaru</h2>
        <a href="{{ route('go.letters.index') }}" class="panel-link">Lihat Semua →</a>
    </div>

    @if($suratTerbaru->isNotEmpty())
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr><th>Perihal</th><th>Arah</th><th>Jenis</th><th>Tanggal</th><th>Status</th></tr>
            </thead>
            <tbody>
                @foreach($suratTerbaru as $letter)
                <tr>
                    <td style="min-width:220px;">
                        <a href="{{ route('go.letters.show', $letter) }}" class="link-strong">{{ $letter->perihal }}</a>
                        <div class="cell-sub">{{ $letter->nomor_surat }} · {{ $letter->pihakLabel() }}: {{ $letter->pengirim_tujuan }}</div>
                    </td>
                    <td><span class="status-pill {{ $letter->arahBadgeClass() }}">{{ $letter->arah }}</span></td>
                    <td>{{ $letter->jenis }}</td>
                    <td style="white-space:nowrap;">{{ $letter->tanggal_surat->translatedFormat('d M Y') }}</td>
                    <td><span class="status-pill {{ $letter->statusBadgeClass() }}">{{ $letter->status }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="empty-note">Belum ada surat yang tercatat.</div>
    @endif
</div>

{{-- ============ ASET ============ --}}
<h2 class="section-title">Aset / Inventaris</h2>

<div class="summary-row">
    <div class="summary-item">
        <p class="label">Total Aset</p>
        <p class="value">{{ $totalAset }}</p>
    </div>
    <div class="summary-item">
        <p class="label">Kondisi Baik</p>
        <p class="value">{{ $asetKondisiBaik }}</p>
    </div>
    <div class="summary-item">
        <p class="label">Perlu Perhatian</p>
        <p class="value attention">{{ $asetPerluPerhatian }}</p>
    </div>
    <div class="summary-item">
        <p class="label">Total Nilai Aset</p>
        <p class="value">Rp {{ number_format($totalNilaiAset, 0, ',', '.') }}</p>
    </div>
</div>

<div class="two-col">
    <div class="panel">
        <div class="panel-head">
            <h2>Aset per Kategori</h2>
        </div>
        <div class="panel-body">
            @forelse($asetPerKategori as $row)
            <div class="bar-row">
                <div class="bar-label">
                    <span>{{ $row->kategori }}</span>
                    <span>{{ $row->total }}</span>
                </div>
                <div class="bar-track">
                    <div class="bar-fill" style="width: {{ $totalAset > 0 ? round($row->total / $totalAset * 100) : 0 }}%;"></div>
                </div>
            </div>
            @empty
            <div class="empty-note">Belum ada data aset.</div>
            @endforelse
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <h2>Aset Terbaru Ditambahkan</h2>
            <a href="{{ route('go.assets.index') }}" class="panel-link">Lihat Semua →</a>
        </div>

        @if($asetTerbaru->isNotEmpty())
        <table class="data-table">
            <thead>
                <tr><th>Kode</th><th>Nama</th><th>Kondisi</th><th></th></tr>
            </thead>
            <tbody>
                @foreach($asetTerbaru as $asset)
                <tr>
                    <td>{{ $asset->kode_aset }}</td>
                    <td>{{ $asset->nama }}</td>
                    <td><span class="status-pill {{ $asset->kondisiBadgeClass() }}">{{ $asset->kondisi }}</span></td>
                    <td class="row-actions">
                        <a href="{{ route('go.assets.edit', $asset) }}" class="action-edit">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-note">Belum ada aset yang tercatat. Mulai tambahkan aset pertama kamu.</div>
        @endif
    </div>
</div>

@endsection