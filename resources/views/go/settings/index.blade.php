@extends('layouts.go')

@section('page-title', 'Pengaturan')
@section('page-subtitle', 'Master Divisi')

@section('content')

<div class="settings-layout">
    @include('go.settings._menu')

    <div>
        <div class="panel">
            <div class="panel-head">
                <h2>Tambah Divisi</h2>
            </div>

            <form action="{{ route('go.settings.divisions.store') }}" method="POST" class="inline-form">
                @csrf
                <input type="text" name="nama" value="{{ old('nama') }}" placeholder="mis. Keuangan, Produksi, Kebun" maxlength="100">
                <button type="submit" class="btn btn-primary">Tambah</button>
            </form>
            @error('nama') <div class="error" style="color: var(--red); font-size:.78rem; padding:0 1.5rem 1rem; margin-top:-.5rem;">{{ $message }}</div> @enderror

            <div class="field-hint" style="padding:0 1.5rem 1.25rem;">
                Daftar ini jadi saran di form Permintaan ATK dan Peminjaman Fasilitas. Mengubah atau menghapus divisi tidak mengubah data yang sudah tercatat.
            </div>
        </div>

        <div class="panel">
            <div class="panel-head">
                <h2>Daftar Divisi</h2>
                <span class="field-hint">{{ $divisions->count() }} divisi</span>
            </div>

            @if($divisions->isEmpty())
                <div class="empty-note">Belum ada divisi. Tambahkan divisi pertama di atas.</div>
            @else
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nama Divisi</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($divisions as $division)
                        <tr>
                            <td>{{ $division->nama }}</td>
                            <td class="row-actions">
                                <a href="{{ route('go.settings.divisions.edit', $division) }}" class="action-edit">Edit</a>
                                <form action="{{ route('go.settings.divisions.destroy', $division) }}" method="POST"
                                      onsubmit="return confirm('Hapus divisi ini dari daftar saran?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-delete">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>

@endsection