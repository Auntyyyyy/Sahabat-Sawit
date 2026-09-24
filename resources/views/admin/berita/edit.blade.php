@extends('layouts.admin')

@section('content')

    <div class="admin-card">

        <div class="admin-card-head">
            <div class="admin-card-head-icon">
                <i class="bi bi-pencil-square"></i>
            </div>
            <div class="flex-grow-1">
                <h2 class="admin-card-title">Edit Berita</h2>
                <p class="admin-card-desc">Ubah detail berita/kegiatan ini.</p>
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

        <form action="{{ route('admin.berita.update', $berita) }}" method="POST" enctype="multipart/form-data" class="p-3">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="judul" class="form-label fw-semibold">Judul Berita</label>
                <input type="text" name="judul" id="judul" class="form-control"
                       value="{{ old('judul', $berita->judul) }}" required>
            </div>

            <div class="mb-3">
                <label for="tanggal" class="form-label fw-semibold">Tanggal Kegiatan</label>
                <input type="date" name="tanggal" id="tanggal" class="form-control"
                       value="{{ old('tanggal', $berita->tanggal->format('Y-m-d')) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Gambar Saat Ini</label>
                <div class="mb-2">
                    <img src="{{ $berita->gambar ? asset('storage/' . $berita->gambar) : 'https://placehold.co/300x200/1F5F3B/F5F1E8?text=—' }}"
                         alt="{{ $berita->judul }}"
                         style="width: 180px; height: 120px; object-fit: cover; border-radius: 10px;">
                </div>
                <label for="gambar" class="form-label">Ganti Gambar (opsional)</label>
                <input type="file" name="gambar" id="gambar" class="form-control" accept="image/*">
                <div class="form-text">Biarkan kosong kalau tidak ingin mengganti gambar. Maksimal 5MB.</div>
            </div>

            <div class="mb-4">
                <label for="deskripsi_singkat" class="form-label fw-semibold">Penjelasan Singkat Kegiatan</label>
                <textarea name="deskripsi_singkat" id="deskripsi_singkat" rows="5" class="form-control">{{ old('deskripsi_singkat', $berita->deskripsi_singkat) }}</textarea>
            </div>

            <button type="submit" class="page-action-btn page-action-btn--primary">
                <i class="bi bi-check-circle"></i> Simpan Perubahan
            </button>
        </form>

    </div>

@endsection