@extends('layouts.admin')

@section('content')

    <div class="admin-card">

        <div class="admin-card-head">
            <div class="admin-card-head-icon">
                <i class="bi bi-plus-circle"></i>
            </div>
            <div class="flex-grow-1">
                <h2 class="admin-card-title">Tambah Berita</h2>
                <p class="admin-card-desc">Isi detail berita/kegiatan yang akan ditampilkan di halaman Media.</p>
            </div>
            <a href="{{ route('admin.berita.index') }}" class="page-action-btn">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger mx-3 mt-3">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.berita.store') }}" method="POST" enctype="multipart/form-data" class="p-3">
            @csrf

            <div class="mb-3">
                <label for="judul" class="form-label fw-semibold">Judul Berita</label>
                <input type="text" name="judul" id="judul" class="form-control"
                       value="{{ old('judul') }}" placeholder="Contoh: Kunjungan Kerja Dinas Perkebunan Riau" required>
            </div>

            <div class="mb-3">
                <label for="tanggal" class="form-label fw-semibold">Tanggal Kegiatan</label>
                <input type="date" name="tanggal" id="tanggal" class="form-control"
                       value="{{ old('tanggal', date('Y-m-d')) }}" required>
            </div>

            <div class="mb-3">
                <label for="gambar" class="form-label fw-semibold">Gambar</label>
                <input type="file" name="gambar" id="gambar" class="form-control" accept="image/*" required>
                <div class="form-text">Format JPG/PNG, maksimal 5MB.</div>
            </div>

            <div class="mb-4">
                <label for="deskripsi_singkat" class="form-label fw-semibold">Penjelasan Singkat Kegiatan</label>
                <textarea name="deskripsi_singkat" id="deskripsi_singkat" rows="5" class="form-control"
                          placeholder="Ceritakan singkat kegiatannya di sini...">{{ old('deskripsi_singkat') }}</textarea>
            </div>

            <button type="submit" class="page-action-btn page-action-btn--primary">
                <i class="bi bi-check-circle"></i> Simpan Berita
            </button>
        </form>

    </div>

@endsection