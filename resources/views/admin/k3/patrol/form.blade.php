@extends('admin.layouts.k3')

@php
    // Form ini dipakai untuk Tambah dan Edit. Bedanya: data sudah ada di database atau belum.
    $isEdit = $patrol->exists;
@endphp

@section('title', $isEdit ? 'Edit Hasil Patrol' : 'Tambah Hasil Patrol')
@section('page_title', $isEdit ? 'Edit Hasil Patrol' : 'Tambah Hasil Patrol')

@section('content')

    <div class="k3-card" style="max-width: 820px;">

        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
            <div>
                <h2 class="k3-card-title" style="font-size:1.25rem">{{ $isEdit ? 'Edit Hasil Patrol' : 'Tambah Hasil Patrol' }}</h2>
                <p class="k3-card-sub mb-0">
                    {{ $isEdit ? 'Ubah catatan hasil inspeksi ini.' : 'Catat satu item hasil inspeksi keselamatan.' }}
                </p>
            </div>
            <a href="{{ route('k3.patrol.index') }}" class="k3-btn k3-btn-outline">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ $isEdit ? route('k3.patrol.update', $patrol) : route('k3.patrol.store') }}"
              method="POST" enctype="multipart/form-data">
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="k3-label" for="tanggal">Tanggal Patrol</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control"
                           value="{{ old('tanggal', $patrol->tanggal?->format('Y-m-d')) }}" required>
                </div>

                <div class="col-md-8">
                    <label class="k3-label" for="lokasi">Lokasi</label>
                    <input type="text" name="lokasi" id="lokasi" class="form-control"
                           value="{{ old('lokasi', $patrol->lokasi) }}"
                           placeholder="Contoh: Pabrik Kelapa Sawit, area boiler" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="k3-label" for="hasil">Hasil Inspeksi</label>
                <select name="hasil" id="hasil" class="form-select" style="max-width: 260px;" required>
                    <option value="sesuai" @selected(old('hasil', $patrol->hasil) === 'sesuai')>Sesuai (kondisi aman)</option>
                    <option value="temuan" @selected(old('hasil', $patrol->hasil) === 'temuan')>Temuan (perlu perbaikan)</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="k3-label" for="uraian">Uraian</label>
                <textarea name="uraian" id="uraian" rows="4" class="form-control"
                          placeholder="Apa yang diperiksa atau ditemukan..." required>{{ old('uraian', $patrol->uraian) }}</textarea>
            </div>

            {{-- Bagian ini hanya relevan untuk temuan. Disembunyikan otomatis kalau hasilnya "Sesuai". --}}
            <div id="blokTindak">
                <div class="mb-3">
                    <label class="k3-label" for="status_tindak">Status Tindak Lanjut</label>
                    <select name="status_tindak" id="status_tindak" class="form-select" style="max-width: 260px;">
                        <option value="terbuka" @selected(old('status_tindak', $isEdit ? $patrol->status_tindak : 'terbuka') === 'terbuka')>Terbuka (belum selesai)</option>
                        <option value="selesai" @selected(old('status_tindak', $isEdit ? $patrol->status_tindak : 'terbuka') === 'selesai')>Selesai</option>
                    </select>
                    <div class="form-text">Temuan yang masih Terbuka dihitung di dashboard sebagai "Temuan Belum Selesai".</div>
                </div>

                <div class="mb-3">
                    <label class="k3-label" for="tindakan">Tindakan Perbaikan</label>
                    <textarea name="tindakan" id="tindakan" rows="3" class="form-control"
                              placeholder="Tindakan yang dilakukan atau direncanakan (opsional)">{{ old('tindakan', $patrol->tindakan) }}</textarea>
                </div>
            </div>

            <div class="mb-4">
                <label class="k3-label" for="foto">Foto Bukti (opsional)</label>

                @if ($isEdit && $patrol->foto)
                    <div class="mb-2">
                        <a href="{{ asset('storage/' . $patrol->foto) }}" target="_blank" rel="noopener" class="k3-pill pill-navy text-decoration-none">
                            <i class="bi bi-image"></i> Lihat foto saat ini
                        </a>
                    </div>
                @endif

                <input type="file" name="foto" id="foto" class="form-control" accept="image/*">
                <div class="form-text">
                    Format gambar (JPG/PNG), maksimal 5 MB.
                    @if ($isEdit) Biarkan kosong kalau tidak ingin mengganti foto. @endif
                </div>
            </div>

            <button type="submit" class="k3-btn k3-btn-red">
                <i class="bi bi-check-circle"></i> {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Hasil Patrol' }}
            </button>
        </form>

    </div>

@endsection

@push('scripts')
<script>
    // Sembunyikan bagian tindak lanjut kalau hasilnya "Sesuai".
    // Kalau script ini tidak jalan, bagian itu tetap tampil dan form tetap berfungsi.
    (function () {
        var hasil = document.getElementById('hasil');
        var blok = document.getElementById('blokTindak');
        if (! hasil || ! blok) { return; }

        function sinkron() {
            blok.style.display = hasil.value === 'temuan' ? '' : 'none';
        }

        hasil.addEventListener('change', sinkron);
        sinkron();
    })();
</script>
@endpush