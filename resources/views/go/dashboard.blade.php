@extends('layouts.go')

@section('page-title', 'Dashboard General Affair')
@section('page-subtitle', 'Ringkasan aset, ATK, dan permintaan')

@section('content')

<div style="display:flex; justify-content:flex-end; gap:.6rem; margin-bottom:1.25rem; flex-wrap:wrap;">
    <a href="{{ route('go.atk-requests.create') }}" class="btn btn-secondary">Permintaan ATK Baru</a>
    <a href="{{ route('go.assets.create') }}" class="btn btn-primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;"><path d="M12 5v14M5 12h14"/></svg>
        Tambah Aset
    </a>
</div>

{{-- ============ ATK & PERMINTAAN ============ --}}
<h2 style="font-size:1rem; color: var(--rose-dark); margin:0 0 .8rem;">ATK & Permintaan</h2>

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
                    <td><a href="{{ route('go.atk-requests.show', $req) }}" class="action-view">{{ $req->nomor }}</a></td>
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

{{-- ============ ASET ============ --}}
<h2 style="font-size:1rem; color: var(--rose-dark); margin:0 0 .8rem;">Aset / Inventaris</h2>

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