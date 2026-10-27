@extends('layouts.go')

@section('page-title', 'Barang Masuk')
@section('page-subtitle', 'Riwayat penambahan stok ATK')

@section('content')

<div style="display:flex; justify-content:flex-end; margin-bottom:1.25rem;">
    <a href="{{ route('go.atk-stock-ins.create') }}" class="btn btn-primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;"><path d="M12 5v14M5 12h14"/></svg>
        Catat Barang Masuk
    </a>
</div>

<div class="panel">
    @if($stockIns->isEmpty())
        <div class="empty-note">Belum ada barang masuk yang dicatat.</div>
    @else
        <table class="data-table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Barang</th>
                    <th>Jumlah Masuk</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stockIns as $row)
                <tr>
                    <td>{{ $row->tanggal->translatedFormat('d M Y') }}</td>
                    <td>{{ $row->item?->kode }} — {{ $row->item?->nama }}</td>
                    <td><strong>+{{ $row->jumlah }}</strong> {{ $row->item?->satuan }}</td>
                    <td>{{ $row->catatan ?: '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $stockIns->links('go.pagination') }}
    @endif
</div>

@endsection