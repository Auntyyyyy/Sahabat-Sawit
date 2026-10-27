@extends('layouts.go')

@section('page-title', 'Barang ATK')
@section('page-subtitle', $items->total() . ' jenis barang terdaftar')

@section('content')

<div style="display:flex; justify-content:flex-end; gap:.6rem; margin-bottom:1.25rem; flex-wrap:wrap;">
    <a href="{{ route('go.atk-stock-ins.create') }}" class="btn btn-secondary">Catat Barang Masuk</a>
    <a href="{{ route('go.atk-items.create') }}" class="btn btn-primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;"><path d="M12 5v14M5 12h14"/></svg>
        Tambah Barang
    </a>
</div>

<div class="panel">
    <form method="GET" action="{{ route('go.atk-items.index') }}" class="filter-bar">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari kode atau nama barang...">

        <select name="kategori">
            <option value="">Semua Kategori</option>
            @foreach($kategoriOptions as $opt)
            <option value="{{ $opt }}" @selected(request('kategori') === $opt)>{{ $opt }}</option>
            @endforeach
        </select>

        <select name="stok">
            <option value="">Semua Stok</option>
            <option value="menipis" @selected(request('stok') === 'menipis')>Menipis / Habis</option>
        </select>

        <button type="submit" class="btn btn-secondary">Cari</button>
    </form>

    @if($items->isEmpty())
        <div class="empty-note">
            @if(request()->anyFilled(['q', 'kategori', 'stok']))
                Tidak ada barang yang cocok dengan pencarian/filter ini.
            @else
                Belum ada barang ATK. Mulai dengan menambahkan barang pertama.
            @endif
        </div>
    @else
        <table class="data-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                    <th>Minimum</th>
                    <th>Kondisi Stok</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td>{{ $item->kode }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->kategori ?: '—' }}</td>
                    <td><strong>{{ $item->stok }}</strong> {{ $item->satuan }}</td>
                    <td>{{ $item->stok_minimum }}</td>
                    <td><span class="status-pill {{ $item->stokPillClass() }}">{{ $item->stokLabel() }}</span></td>
                    <td class="row-actions">
                        <a href="{{ route('go.atk-stock-ins.create', ['item' => $item->id]) }}" class="action-view">Masuk</a>
                        <a href="{{ route('go.atk-items.edit', $item) }}" class="action-edit">Edit</a>
                        <form action="{{ route('go.atk-items.destroy', $item) }}" method="POST"
                              onsubmit="return confirm('Hapus barang ini? Tindakan ini tidak bisa dibatalkan.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-delete">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $items->links('go.pagination') }}
    @endif
</div>

@endsection