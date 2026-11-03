@extends('layouts.go')

@section('page-title', 'Pengaturan')
@section('page-subtitle', 'Pengaturan Dashboard')

@section('content')

<div class="settings-layout">
    @include('go.settings._menu')

    <div class="panel">
        <div class="panel-head">
            <h2>Pengaturan Dashboard</h2>
        </div>

        <form action="{{ route('go.settings.dashboard.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-field">
                    <label>Batas Perawatan Fasilitas (bulan)</label>
                    <input type="number" min="1" max="60" name="batas_perawatan_bulan" value="{{ old('batas_perawatan_bulan', $batasPerawatan) }}">
                    <small class="field-hint" style="display:block; margin-top:.35rem;">
                        Fasilitas yang belum dirawat lebih lama dari ini muncul di "Perlu Perhatian & Perawatan". Standar: 6 bulan.
                    </small>
                    @error('batas_perawatan_bulan') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label>Peminjaman Mendatang (hari)</label>
                    <input type="number" min="1" max="90" name="hari_mendatang" value="{{ old('hari_mendatang', $hariMendatang) }}">
                    <small class="field-hint" style="display:block; margin-top:.35rem;">
                        Peminjaman disetujui yang mulai dalam rentang ini dihitung di kartu "Mulai ≤ N Hari". Standar: 7 hari.
                    </small>
                    @error('hari_mendatang') <div class="error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
            </div>
        </form>
    </div>
</div>

@endsection