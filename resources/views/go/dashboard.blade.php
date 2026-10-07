@extends('layouts.go')

@section('page-title', 'Dashboard General Affair')
@section('page-subtitle', 'Ringkasan aset & inventaris perusahaan')

@section('content')

<div style="display:flex; justify-content:flex-end; margin-bottom:1.25rem;">
    <a href="{{ route('go.assets.create') }}" class="btn btn-primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;"><path d="M12 5v14M5 12h14"/></svg>
        Tambah Aset
    </a>
</div>

{{-- Ringkasan angka --}}
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
    {{-- Aset per kategori --}}
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

    {{-- Aset terbaru --}}
    <div class="panel">
        <div class="panel-head">
            <h2>Aset Terbaru Ditambahkan</h2>
            <a href="{{ route('go.assets.index') }}" class="panel-link">Lihat Semua →</a>
        </div>

        @if($asetTerbaru->isNotEmpty())
        <table class="data-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Kondisi</th>
                    <th></th>
                </tr>
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