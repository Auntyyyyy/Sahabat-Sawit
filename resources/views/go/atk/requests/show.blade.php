@extends('layouts.go')

@section('page-title', 'Permintaan ' . $atkRequest->nomor)
@section('page-subtitle', $atkRequest->pemohon_nama . ' · ' . $atkRequest->tanggal->translatedFormat('d F Y'))

@section('content')

@php
    $actionLabels = [
        'Disetujui'  => 'Setujui',
        'Ditolak'    => 'Tolak',
        'Diserahkan' => 'Tandai Diserahkan',
        'Dibatalkan' => 'Batalkan & Kembalikan Stok',
    ];
    $needConfirm = ['Ditolak' => 'Tolak permintaan ini?', 'Dibatalkan' => 'Batalkan penyerahan? Stok barang akan dikembalikan.'];
    $nextStatuses = $atkRequest->allowedTransitions();
@endphp

<div class="panel">
    <div class="panel-head">
        <h2>Detail Permintaan</h2>
        <a href="{{ route('go.atk-requests.index') }}" class="panel-link">← Kembali</a>
    </div>
    <dl class="detail-grid">
        <dt>Nomor</dt><dd>{{ $atkRequest->nomor }}</dd>
        <dt>Tanggal</dt><dd>{{ $atkRequest->tanggal->translatedFormat('d F Y') }}</dd>
        <dt>Pemohon</dt><dd>{{ $atkRequest->pemohon_nama }}</dd>
        <dt>Divisi</dt><dd>{{ $atkRequest->pemohon_divisi ?: '—' }}</dd>
        <dt>Status</dt><dd><span class="status-pill {{ $atkRequest->statusPillClass() }}">{{ $atkRequest->status }}</span></dd>
        <dt>Catatan</dt><dd>{{ $atkRequest->catatan ?: '—' }}</dd>
    </dl>
</div>

<div class="panel">
    <div class="panel-head">
        <h2>Barang yang Diminta</h2>
    </div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Barang</th>
                <th>Jumlah Diminta</th>
                <th>Stok Saat Ini</th>
            </tr>
        </thead>
        <tbody>
            @foreach($atkRequest->items as $row)
            @php
                $kurang = in_array($atkRequest->status, ['Diajukan', 'Disetujui'], true)
                    && $row->item && $row->item->stok < $row->jumlah;
            @endphp
            <tr>
                <td>{{ $row->item?->kode }}</td>
                <td>{{ $row->item?->nama }}</td>
                <td><strong>{{ $row->jumlah }}</strong> {{ $row->item?->satuan }}</td>
                <td style="{{ $kurang ? 'color: var(--red); font-weight:600;' : '' }}">
                    {{ $row->item?->stok }} {{ $row->item?->satuan }}
                    @if($kurang) · kurang @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="panel">
    <div class="panel-head">
        <h2>Ubah Status</h2>
    </div>
    <div class="panel-body">
        @if(empty($nextStatuses))
            <p style="margin:0; color: var(--muted); font-size:.88rem;">
                Permintaan ini sudah berstatus akhir ({{ $atkRequest->status }}), tidak ada perubahan lanjutan.
            </p>
        @else
            <div style="display:flex; gap:.6rem; flex-wrap:wrap;">
                @foreach($nextStatuses as $next)
                <form action="{{ route('go.atk-requests.status', $atkRequest) }}" method="POST"
                      @if(isset($needConfirm[$next])) onsubmit="return confirm('{{ $needConfirm[$next] }}');" @endif>
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="{{ $next }}">
                    <button type="submit" class="btn {{ in_array($next, ['Disetujui', 'Diserahkan']) ? 'btn-primary' : 'btn-secondary' }}">
                        {{ $actionLabels[$next] ?? $next }}
                    </button>
                </form>
                @endforeach
            </div>
            @if(in_array('Diserahkan', $nextStatuses))
            <p style="margin:.9rem 0 0; color: var(--muted); font-size:.8rem;">
                Saat ditandai Diserahkan, stok barang di atas otomatis berkurang.
            </p>
            @endif
        @endif
    </div>
</div>

@endsection