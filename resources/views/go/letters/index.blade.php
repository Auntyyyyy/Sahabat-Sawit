@extends('layouts.go')

@section('page-title', 'Surat-Menyurat')
@section('page-subtitle', $letters->total() . ' surat tercatat')

@section('content')

<div style="display:flex; justify-content:flex-end; margin-bottom:1.25rem;">
    <a href="{{ route('go.letters.create') }}" class="btn btn-primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;"><path d="M12 5v14M5 12h14"/></svg>
        Catat Surat
    </a>
</div>

<div class="panel">
    <form method="GET" action="{{ route('go.letters.index') }}" class="filter-bar">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nomor surat, perihal, atau pengirim/tujuan...">

        <select name="arah">
            <option value="">Semua Arah</option>
            @foreach($arahOptions as $opt)
            <option value="{{ $opt }}" @selected(request('arah') === $opt)>Surat {{ $opt }}</option>
            @endforeach
        </select>

        <select name="jenis">
            <option value="">Semua Jenis</option>
            @foreach($jenisOptions as $opt)
            <option value="{{ $opt }}" @selected(request('jenis') === $opt)>{{ $opt }}</option>
            @endforeach
        </select>

        <select name="status">
            <option value="">Semua Status</option>
            @foreach($statusOptions as $opt)
            <option value="{{ $opt }}" @selected(request('status') === $opt)>{{ $opt }}</option>
            @endforeach
        </select>

        <button type="submit" class="btn btn-secondary">Cari</button>
    </form>

    @if($letters->isEmpty())
        <div class="empty-note">
            @if(request()->anyFilled(['q', 'arah', 'jenis', 'status']))
                Tidak ada surat yang cocok dengan pencarian/filter ini.
            @else
                Belum ada surat yang tercatat. Mulai catat surat pertama.
            @endif
        </div>
    @else
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nomor Surat</th>
                        <th>Perihal</th>
                        <th>Arah</th>
                        <th>Jenis</th>
                        <th>Tanggal</th>
                        <th>Sifat</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($letters as $letter)
                    <tr>
                        <td style="white-space:nowrap;">{{ $letter->nomor_surat }}</td>
                        <td style="min-width:220px;">
                            <div style="font-weight:500;">
                                {{ $letter->perihal }}
                                @if($letter->lampiran)
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px;vertical-align:-2px;color:var(--muted);"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
                                @endif
                            </div>
                            <div style="font-size:.78rem; color:var(--muted); margin-top:.15rem;">{{ $letter->pihakLabel() }}: {{ $letter->pengirim_tujuan }}</div>
                        </td>
                        <td><span class="status-pill {{ $letter->arahBadgeClass() }}">{{ $letter->arah }}</span></td>
                        <td>{{ $letter->jenis }}</td>
                        <td style="white-space:nowrap;">{{ $letter->tanggal_surat->locale('id')->translatedFormat('d M Y') }}</td>
                        <td><span class="status-pill {{ $letter->sifatBadgeClass() }}">{{ $letter->sifat }}</span></td>
                        <td><span class="status-pill {{ $letter->statusBadgeClass() }}">{{ $letter->status }}</span></td>
                        <td class="row-actions">
                            <a href="{{ route('go.letters.show', $letter) }}" class="action-view">Detail</a>
                            <a href="{{ route('go.letters.edit', $letter) }}" class="action-edit">Edit</a>
                            <form action="{{ route('go.letters.destroy', $letter) }}" method="POST"
                                  onsubmit="return confirm('Hapus surat ini? Tindakan ini tidak bisa dibatalkan.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-delete">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $letters->links('go.pagination') }}
    @endif
</div>

@endsection