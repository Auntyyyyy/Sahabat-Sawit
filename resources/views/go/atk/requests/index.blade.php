@extends('layouts.go')

@section('page-title', 'Permintaan ATK')
@section('page-subtitle', $requests->total() . ' permintaan tercatat')

@section('content')

<div style="display:flex; justify-content:flex-end; margin-bottom:1.25rem;">
    <a href="{{ route('go.atk-requests.create') }}" class="btn btn-primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;"><path d="M12 5v14M5 12h14"/></svg>
        Permintaan Baru
    </a>
</div>

<div class="panel">
    <form method="GET" action="{{ route('go.atk-requests.index') }}" class="filter-bar">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nomor atau nama pemohon...">

        <select name="status">
            <option value="">Semua Status</option>
            @foreach($statusOptions as $opt)
            <option value="{{ $opt }}" @selected(request('status') === $opt)>{{ $opt }}</option>
            @endforeach
        </select>

        <button type="submit" class="btn btn-secondary">Cari</button>
    </form>

    @if($requests->isEmpty())
        <div class="empty-note">
            @if(request()->anyFilled(['q', 'status']))
                Tidak ada permintaan yang cocok dengan pencarian/filter ini.
            @else
                Belum ada permintaan ATK. Catat permintaan pertama dari karyawan.
            @endif
        </div>
    @else
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nomor</th>
                    <th>Tanggal</th>
                    <th>Pemohon</th>
                    <th>Barang</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $req)
                <tr>
                    <td><a href="{{ route('go.atk-requests.show', $req) }}" class="action-view">{{ $req->nomor }}</a></td>
                    <td>{{ $req->tanggal->translatedFormat('d M Y') }}</td>
                    <td>
                        {{ $req->pemohon_nama }}
                        @if($req->pemohon_divisi)
                            <div style="font-size:.78rem; color: var(--muted);">{{ $req->pemohon_divisi }}</div>
                        @endif
                    </td>
                    <td>{{ $req->items_count }} jenis</td>
                    <td><span class="status-pill {{ $req->statusPillClass() }}">{{ $req->status }}</span></td>
                    <td class="row-actions">
                        <a href="{{ route('go.atk-requests.show', $req) }}" class="action-view">Detail</a>
                        @if($req->status !== 'Diserahkan')
                        <form action="{{ route('go.atk-requests.destroy', $req) }}" method="POST"
                              onsubmit="return confirm('Hapus permintaan ini? Tindakan ini tidak bisa dibatalkan.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-delete">Hapus</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $requests->links('go.pagination') }}
    @endif
</div>

@endsection