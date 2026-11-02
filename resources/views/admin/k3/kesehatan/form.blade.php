@extends('admin.layouts.k3')

@php
    // Form ini dipakai untuk Tambah dan Edit. Bedanya: data sudah ada di database atau belum.
    $isEdit = $kesehatan->exists;
@endphp

@section('title', $isEdit ? 'Edit Pemeriksaan' : 'Tambah Pemeriksaan')
@section('page_title', $isEdit ? 'Edit Pemeriksaan Kesehatan' : 'Tambah Pemeriksaan Kesehatan')

@section('content')

    <div class="k3-card" style="max-width: 820px;">

        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
            <div>
                <h2 class="k3-card-title" style="font-size:1.25rem">{{ $isEdit ? 'Edit Pemeriksaan' : 'Tambah Pemeriksaan' }}</h2>
                <p class="k3-card-sub mb-0">
                    {{ $isEdit ? 'Ubah data pemeriksaan kesehatan ini.' : 'Catat satu kali pemeriksaan kesehatan karyawan.' }}
                </p>
            </div>
            <a href="{{ route('k3.kesehatan.index') }}" class="k3-btn k3-btn-outline">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="alert alert-secondary d-flex gap-2 small" role="note">
            <i class="bi bi-shield-lock"></i>
            <div>
                Data kesehatan bersifat sensitif. Isi <strong>status kelayakan</strong> dan catatan umum saja.
                Jangan menuliskan diagnosis penyakit atau hasil laboratorium di sini.
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

        <form action="{{ $isEdit ? route('k3.kesehatan.update', $kesehatan) : route('k3.kesehatan.store') }}" method="POST">
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="k3-label" for="nama">Nama Karyawan</label>
                    <input type="text" name="nama" id="nama" class="form-control"
                           value="{{ old('nama', $kesehatan->nama) }}" placeholder="Tulis nama lengkap" required>
                    <div class="form-text">Gunakan penulisan nama yang sama setiap kali, supaya riwayatnya menyatu.</div>
                </div>

                <div class="col-md-6">
                    <label class="k3-label" for="jabatan">Jabatan / Bagian</label>
                    <input type="text" name="jabatan" id="jabatan" class="form-control"
                           value="{{ old('jabatan', $kesehatan->jabatan) }}" placeholder="Contoh: Operator Boiler">
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="k3-label" for="tanggal">Tanggal Pemeriksaan</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control"
                           value="{{ old('tanggal', $kesehatan->tanggal?->format('Y-m-d')) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="k3-label" for="jenis">Jenis Pemeriksaan</label>
                    <select name="jenis" id="jenis" class="form-select" required>
                        @foreach (\App\Models\K3Kesehatan::JENIS as $kunci => $label)
                            <option value="{{ $kunci }}" @selected(old('jenis', $kesehatan->jenis) === $kunci)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="k3-label" for="hasil">Status Kelayakan</label>
                    <select name="hasil" id="hasil" class="form-select" required>
                        @foreach (\App\Models\K3Kesehatan::HASIL as $kunci => $label)
                            <option value="{{ $kunci }}" @selected(old('hasil', $kesehatan->hasil) === $kunci)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="k3-label" for="catatan">Catatan Umum (opsional)</label>
                <textarea name="catatan" id="catatan" rows="3" maxlength="1000" class="form-control"
                          placeholder="Contoh: perlu kontrol rutin, hindari kerja di ketinggian sementara">{{ old('catatan', $kesehatan->catatan) }}</textarea>
                <div class="form-text">Keterangan umum saja, tanpa nama penyakit. Maksimal 1000 karakter.</div>
            </div>

            <div class="mb-4">
                <label class="k3-label" for="tanggal_berikutnya">Jadwal Pemeriksaan Berikutnya (opsional)</label>
                <input type="date" name="tanggal_berikutnya" id="tanggal_berikutnya" class="form-control" style="max-width: 260px;"
                       value="{{ old('tanggal_berikutnya', $kesehatan->tanggal_berikutnya?->format('Y-m-d')) }}">
                <div class="form-text">Dipakai untuk penanda "Segera" dan "Lewat" di daftar, serta hitungan jadwal yang mendekati.</div>
            </div>

            <button type="submit" class="k3-btn k3-btn-red">
                <i class="bi bi-check-circle"></i> {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Pemeriksaan' }}
            </button>
        </form>

    </div>

@endsection