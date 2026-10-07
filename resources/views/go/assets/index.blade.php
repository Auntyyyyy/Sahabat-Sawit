@extends('layouts.go')

@section('page-title', 'Kelola Aset')
@section('page-subtitle', $assets->total() . ' aset tercatat')

@section('content')

<div style="display:flex; justify-content:flex-end; margin-bottom:1.25rem;">
    <a href="{{ route('go.assets.create') }}" class="btn btn-primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;"><path d="M12 5v14M5 12h14"/></svg>
        Tambah Aset
    </a>
</div>

<div class="panel">
    <form method="GET" action="{{ route('go.assets.index') }}" class="filter-bar">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari kode, nama, atau lokasi aset...">

        <select name="kategori">
            <option value="">Semua Kategori</option>
            @foreach($kategoriOptions as $opt)
            <option value="{{ $opt }}" @selected(request('kategori') === $opt)>{{ $opt }}</option>
            @endforeach
        </select>

        <select name="kondisi">
            <option value="">Semua Kondisi</option>
            @foreach($kondisiOptions as $opt)
            <option value="{{ $opt }}" @selected(request('kondisi') === $opt)>{{ $opt }}</option>
            @endforeach
        </select>

        <button type="submit" class="btn btn-secondary">Cari</button>
    </form>

    @if($assets->isEmpty())
        <div class="empty-note">
            @if(request()->anyFilled(['q', 'kategori', 'kondisi']))
                Tidak ada aset yang cocok dengan pencarian/filter ini.
            @else
                Belum ada aset yang tercatat. Mulai tambahkan aset pertama kamu.
            @endif
        </div>
    @else
        <table class="data-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Aset</th>
                    <th>Kategori</th>
                    <th>Lokasi</th>
                    <th>Kondisi</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($assets as $asset)
                <tr>
                    <td>{{ $asset->kode_aset }}</td>
                    <td>{{ $asset->nama }}</td>
                    <td>{{ $asset->kategori }}</td>
                    <td>{{ $asset->lokasi ?: '—' }}</td>
                    <td><span class="status-pill {{ $asset->kondisiBadgeClass() }}">{{ $asset->kondisi }}</span></td>
                    <td>{{ $asset->status }}</td>
                    <td class="row-actions">
                        <a href="{{ route('go.assets.edit', $asset) }}" class="action-edit">Edit</a>
                        <form action="{{ route('go.assets.destroy', $asset) }}" method="POST"
                              onsubmit="return confirm('Hapus aset {{ $asset->nama }}? Tindakan ini tidak bisa dibatalkan.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-delete">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="padding: 1.25rem 1.5rem;">
            {{ $assets->links() }}
        </div>
    @endif
</div>

@endsection