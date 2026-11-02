@extends('layouts.go')

@section('page-title', 'Kelola Fasilitas')
@section('page-subtitle', $facilities->total() . ' fasilitas tercatat')

@section('content')

<div style="display:flex; justify-content:flex-end; margin-bottom:1.25rem;">
    <a href="{{ route('go.facilities.create') }}" class="btn btn-primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;"><path d="M12 5v14M5 12h14"/></svg>
        Tambah Fasilitas
    </a>
</div>

<div class="panel">
    <form method="GET" action="{{ route('go.facilities.index') }}" class="filter-bar">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari kode, nama, atau lokasi fasilitas...">

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

    @if($facilities->isEmpty())
        <div class="empty-note">
            @if(request()->anyFilled(['q', 'kategori', 'kondisi']))
                Tidak ada fasilitas yang cocok dengan pencarian/filter ini.
            @else
                Belum ada fasilitas yang tercatat. Mulai tambahkan fasilitas pertama.
            @endif
        </div>
    @else
        <table class="data-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Fasilitas</th>
                    <th>Kategori</th>
                    <th>Lokasi</th>
                    <th>Penanggung Jawab</th>
                    <th>Kapasitas</th>
                    <th>Kondisi</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($facilities as $facility)
                <tr>
                    <td>{{ $facility->kode_fasilitas }}</td>
                    <td>{{ $facility->nama }}</td>
                    <td>{{ $facility->kategori }}</td>
                    <td>{{ $facility->lokasi ?: '—' }}</td>
                    <td>{{ $facility->penanggung_jawab ?: '—' }}</td>
                    <td>{{ $facility->kapasitas ?? '—' }}</td>
                    <td><span class="status-pill {{ $facility->kondisiBadgeClass() }}">{{ $facility->kondisi }}</span></td>
                    <td class="row-actions">
                        <a href="{{ route('go.facilities.show', $facility) }}" class="action-view">Detail</a>
                        <a href="{{ route('go.facilities.edit', $facility) }}" class="action-edit">Edit</a>
                        <form action="{{ route('go.facilities.destroy', $facility) }}" method="POST"
                              onsubmit="return confirm('Hapus fasilitas {{ $facility->nama }}? Tindakan ini tidak bisa dibatalkan.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-delete">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $facilities->links('go.pagination') }}
    @endif
</div>

@endsection