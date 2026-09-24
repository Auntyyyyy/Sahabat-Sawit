@extends('layouts.admin')

@section('content')

    <div class="admin-card">

        <div class="admin-card-head">
            <div class="admin-card-head-icon">
                <i class="bi bi-plus-circle"></i>
            </div>
            <div class="flex-grow-1">
                <h2 class="admin-card-title">Tambah Kegiatan — {{ $category->title }}</h2>
                <p class="admin-card-desc">Isi detail kegiatan CSR yang akan ditampilkan di kategori "{{ $category->title }}".</p>
            </div>
            <a href="{{ route('admin.csr-categories.edit', $category) }}" class="page-action-btn">
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

        <form action="{{ route('admin.csr-categories.activities.store', $category) }}" method="POST" enctype="multipart/form-data" class="p-3">
            @csrf

            <div class="mb-3">
                <label for="title" class="form-label fw-semibold">Judul Kegiatan</label>
                <input type="text" name="title" id="title" class="form-control"
                       value="{{ old('title') }}" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="date" class="form-label fw-semibold">Tanggal</label>
                    <input type="text" name="date" id="date" class="form-control"
                           value="{{ old('date') }}" placeholder="Contoh: 19 Mei 2026 atau Januari - April 2026" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="location" class="form-label fw-semibold">Lokasi</label>
                    <input type="text" name="location" id="location" class="form-control"
                           value="{{ old('location') }}" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label fw-semibold">Gambar</label>
                <input type="file" name="image" id="image" class="form-control" accept="image/*" required>
                <div class="form-text">Format JPG/PNG, maksimal 5MB.</div>
            </div>

            <div class="mb-3">
                <label for="order" class="form-label fw-semibold">Urutan Tampil</label>
                <input type="number" name="order" id="order" class="form-control" style="max-width: 160px;"
                       value="{{ old('order', 0) }}">
            </div>

            <div class="mb-4">
                <label for="story" class="form-label fw-semibold">Cerita / Deskripsi Kegiatan</label>
                <textarea name="story" id="story" rows="5" class="form-control" required>{{ old('story') }}</textarea>
            </div>

            <button type="submit" class="page-action-btn page-action-btn--primary">
                <i class="bi bi-check-circle"></i> Simpan Kegiatan
            </button>
        </form>

    </div>

@endsection