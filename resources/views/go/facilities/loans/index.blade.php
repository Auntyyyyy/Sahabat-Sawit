@extends('layouts.go')

@section('page-title', 'Peminjaman Fasilitas')
@section('page-subtitle', $loans->total() . ' peminjaman tercatat')

@section('content')

<div style="display:flex; justify-content:flex-end; margin-bottom:1.25rem;">
    <a href="{{ route('go.facility-loans.create') }}" class="btn btn-primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;"><path d="M12 5v14M5 12h14"/></svg>
        Peminjaman Baru
    </a>
</div>

<div class="panel">
    <form method="GET" action="{{ route('go.facility-loans.index') }}" class="filter-bar">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nomor, peminjam, atau fasilitas...">

        <select name="facility_id">
            <option value="">Semua Fasilitas</option>
            @foreach($facilities as $f)
            <option value="{{ $f->id }}" @selected((string) request('facility_id') === (string) $f->id)>{{ $f->nama }}</option>
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

    @if($loans->isEmpty())
        <div class="empty-note">
            @if(request()->anyFilled(['q', 'status', 'facility_id']))
                Tidak ada peminjaman yang cocok dengan pencarian/filter ini.
            @else
                Belum ada peminjaman fasilitas. Catat peminjaman pertama.
            @endif
        </div>
    @else
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nomor</th>
                        <th>Fasilitas</th>
                        <th>Peminjam</th>
                        <th>Periode</th>
                        <th>Orang</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($loans as $loan)
                    <tr>
                        <td style="white-space:nowrap;"><a href="{{ route('go.facility-loans.show', $loan) }}" class="action-view">{{ $loan->nomor }}</a></td>
                        <td>{{ $loan->facility?->nama ?? '—' }}</td>
                        <td>
                            {{ $loan->peminjam_nama }}
                            <div style="font-size:.78rem; color: var(--muted);">{{ $loan->peminjam_divisi }}</div>
                        </td>
                        <td style="white-space:nowrap;">
                            {{ $loan->tanggal_mulai->translatedFormat('d M Y') }} – {{ $loan->tanggal_selesai->translatedFormat('d M Y') }}
                            <div style="font-size:.78rem; color: var(--muted);">{{ $loan->durasiHari() }} hari</div>
                        </td>
                        <td>{{ $loan->jumlah_orang ?? '—' }}</td>
                        <td><span class="status-pill {{ $loan->statusPillClass() }}">{{ $loan->status }}</span></td>
                        <td class="row-actions">
                            <a href="{{ route('go.facility-loans.show', $loan) }}" class="action-view">Detail</a>
                            @if($loan->status !== 'Sedang Digunakan')
                            <form action="{{ route('go.facility-loans.destroy', $loan) }}" method="POST"
                                  onsubmit="return confirm('Hapus peminjaman ini? Tindakan ini tidak bisa dibatalkan.');">
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
        </div>

        {{ $loans->links('go.pagination') }}
    @endif
</div>

@endsection