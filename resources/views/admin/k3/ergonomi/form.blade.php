@extends('admin.layouts.k3')

@php
    // Form ini dipakai untuk Tambah dan Edit. Bedanya: data sudah ada di database atau belum.
    $isEdit = $ergonomi->exists;
@endphp

@section('title', $isEdit ? 'Edit Penilaian Ergonomi' : 'Tambah Penilaian Ergonomi')
@section('page_title', $isEdit ? 'Edit Penilaian Ergonomi' : 'Tambah Penilaian Ergonomi')

@section('content')

    <div class="k3-card" style="max-width: 860px;">

        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
            <div>
                <h2 class="k3-card-title" style="font-size:1.25rem">{{ $isEdit ? 'Edit Penilaian' : 'Tambah Penilaian' }}</h2>
                <p class="k3-card-sub mb-0">Catat satu penilaian risiko ergonomi untuk satu pekerjaan.</p>
            </div>
            <a href="{{ route('k3.ergonomi.index') }}" class="k3-btn k3-btn-outline">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="alert alert-secondary d-flex gap-2 small" role="note">
            <i class="bi bi-shield-lock"></i>
            <div>
                Catat <strong>pekerjaan, bagian, dan lokasi</strong> saja. Jangan menuliskan nama karyawan
                atau keluhan kesehatan pribadi di sini.
            </div>
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

        <form action="{{ $isEdit ? route('k3.ergonomi.update', $ergonomi) : route('k3.ergonomi.store') }}" method="POST">
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="k3-label" for="tanggal">Tanggal Penilaian</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control"
                           value="{{ old('tanggal', $ergonomi->tanggal?->format('Y-m-d')) }}" required>
                </div>

                <div class="col-md-8">
                    <label class="k3-label" for="pekerjaan">Pekerjaan / Aktivitas yang Dinilai</label>
                    <input type="text" name="pekerjaan" id="pekerjaan" class="form-control"
                           value="{{ old('pekerjaan', $ergonomi->pekerjaan) }}"
                           placeholder="Contoh: Mengangkat tandan buah segar ke truk" required>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="k3-label" for="bagian">Bagian / Unit Kerja (opsional)</label>
                    <input type="text" name="bagian" id="bagian" class="form-control"
                           value="{{ old('bagian', $ergonomi->bagian) }}" placeholder="Contoh: Panen">
                </div>

                <div class="col-md-6">
                    <label class="k3-label" for="lokasi">Lokasi (opsional)</label>
                    <input type="text" name="lokasi" id="lokasi" class="form-control"
                           value="{{ old('lokasi', $ergonomi->lokasi) }}" placeholder="Contoh: Afdeling 1">
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="k3-label" for="metode">Metode Penilaian</label>
                    <select name="metode" id="metode" class="form-select" required>
                        @foreach (\App\Models\K3Ergonomi::METODE as $kunci => $label)
                            <option value="{{ $kunci }}" @selected(old('metode', $ergonomi->metode) === $kunci)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="k3-label" for="skor">Skor Akhir (opsional)</label>
                    <input type="number" name="skor" id="skor" class="form-control" min="0" max="100"
                           value="{{ old('skor', $ergonomi->skor) }}">
                </div>

                <div class="col-md-5">
                    <label class="k3-label" for="tingkat_risiko">Tingkat Risiko</label>
                    <select name="tingkat_risiko" id="tingkat_risiko" class="form-select" required>
                        <option value="">Pilih tingkat risiko</option>
                        @foreach (\App\Models\K3Ergonomi::TINGKAT as $kunci => $label)
                            <option value="{{ $kunci }}" @selected(old('tingkat_risiko', $ergonomi->tingkat_risiko) === $kunci)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-text mb-3" style="margin-top:-4px">
                Contoh acuan umum skor akhir REBA: 1 = dapat diabaikan (rendah), 2-3 = rendah, 4-7 = sedang, 8-10 = tinggi, 11-15 = sangat tinggi.
                Sesuaikan dengan standar yang dipakai perusahaan, dan pilih tingkat risikonya sendiri.
            </div>

            <div class="mb-3">
                <label class="k3-label" for="rekomendasi">Rekomendasi Perbaikan (opsional)</label>
                <textarea name="rekomendasi" id="rekomendasi" rows="3" maxlength="2000" class="form-control"
                          placeholder="Contoh: sediakan alat bantu angkat, atur rotasi kerja">{{ old('rekomendasi', $ergonomi->rekomendasi) }}</textarea>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="k3-label" for="pic">Penanggung Jawab (PIC)</label>
                    <input type="text" name="pic" id="pic" class="form-control"
                           value="{{ old('pic', $ergonomi->pic) }}" placeholder="Contoh: Tim K3">
                </div>

                <div class="col-md-4">
                    <label class="k3-label" for="target_tanggal">Target Selesai</label>
                    <input type="date" name="target_tanggal" id="target_tanggal" class="form-control"
                           value="{{ old('target_tanggal', $ergonomi->target_tanggal?->format('Y-m-d')) }}">
                </div>

                <div class="col-md-4">
                    <label class="k3-label" for="status_tindak">Status Tindak Lanjut</label>
                    <select name="status_tindak" id="status_tindak" class="form-select">
                        @foreach (\App\Models\K3Ergonomi::STATUS_TINDAK as $kunci => $label)
                            <option value="{{ $kunci }}" @selected(old('status_tindak', $ergonomi->status_tindak) === $kunci)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Hanya relevan kalau status Selesai. Disembunyikan otomatis untuk status lain. --}}
            <div class="row g-3 mb-3" id="blokSelesai">
                <div class="col-md-4">
                    <label class="k3-label" for="tanggal_selesai">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control"
                           value="{{ old('tanggal_selesai', $ergonomi->tanggal_selesai?->format('Y-m-d')) }}">
                    <div class="form-text">Kalau dikosongkan, dipakai tanggal hari ini.</div>
                </div>
            </div>

            <div class="mb-4">
                <label class="k3-label" for="keterangan">Catatan Penilai (opsional)</label>
                <textarea name="keterangan" id="keterangan" rows="3" maxlength="1000" class="form-control"
                          placeholder="Catatan tambahan">{{ old('keterangan', $ergonomi->keterangan) }}</textarea>
            </div>

            <button type="submit" class="k3-btn k3-btn-red">
                <i class="bi bi-check-circle"></i> {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Penilaian' }}
            </button>
        </form>

    </div>

@endsection

@push('scripts')
<script>
    // Tampilkan kolom Tanggal Selesai hanya kalau status "Selesai".
    // Kalau script ini tidak jalan, kolom tetap tampil dan form tetap berfungsi
    // (server mengosongkan tanggal selesai untuk status lain).
    (function () {
        var status = document.getElementById('status_tindak');
        var blok = document.getElementById('blokSelesai');
        if (! status || ! blok) { return; }

        function sinkron() {
            blok.style.display = status.value === 'selesai' ? '' : 'none';
        }

        status.addEventListener('change', sinkron);
        sinkron();
    })();
</script>
@endpush