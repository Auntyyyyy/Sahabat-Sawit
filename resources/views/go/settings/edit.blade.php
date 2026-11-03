@extends('layouts.go')

@section('page-title', 'Pengaturan')
@section('page-subtitle', 'Edit Divisi')

@section('content')

<div class="settings-layout">
    @include('go.settings._menu')

    <div class="panel">
        <div class="panel-head">
            <h2>Edit Divisi</h2>
            <a href="{{ route('go.settings.divisions.index') }}" class="panel-link">← Kembali</a>
        </div>

        <form action="{{ route('go.settings.divisions.update', $division) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-grid" style="grid-template-columns: 1fr;">
                <div class="form-field">
                    <label>Nama Divisi</label>
                    <input type="text" name="nama" value="{{ old('nama', $division->nama) }}" maxlength="100">
                    @error('nama') <div class="error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('go.settings.divisions.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

@endsection