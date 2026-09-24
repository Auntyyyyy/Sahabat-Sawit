@extends('layouts.admin')

@section('content')

    <div class="admin-card">

        <div class="admin-card-head">
            <div class="admin-card-head-icon">
                <i class="bi bi-plus-circle"></i>
            </div>
            <div class="flex-grow-1">
                <h2 class="admin-card-title">Tambah Kategori CSR</h2>
                <p class="admin-card-desc">Buat kategori baru, lalu tambahkan kegiatan-kegiatannya setelah tersimpan.</p>
            </div>
            <a href="{{ route('admin.csr-categories.index') }}" class="page-action-btn">
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

        <form action="{{ route('admin.csr-categories.store') }}" method="POST" class="p-3">
            @csrf

            <div class="mb-3">
                <label for="title" class="form-label fw-semibold">Nama Kategori</label>
                <input type="text" name="title" id="title" class="form-control"
                       value="{{ old('title') }}" placeholder="Contoh: Kesehatan" required>
            </div>

            <div class="mb-3">
                <label for="icon" class="form-label fw-semibold">Ikon (emoji)</label>
                <input type="text" name="icon" id="icon" class="form-control" style="max-width: 120px;"
                       value="{{ old('icon') }}" placeholder="🏥">
                <div class="form-text">Tempel emoji apa saja, contoh: 🏥 🎓 🌾 🌱 🏗️</div>
            </div>

            <div class="mb-3">
                <label for="order" class="form-label fw-semibold">Urutan Tampil</label>
                <input type="number" name="order" id="order" class="form-control" style="max-width: 160px;"
                       value="{{ old('order', 0) }}">
                <div class="form-text">Angka lebih kecil tampil lebih dulu.</div>
            </div>

            <div class="mb-4">
                <label for="description" class="form-label fw-semibold">Deskripsi Kategori</label>
                <textarea name="description" id="description" rows="4" class="form-control"
                          placeholder="Jelaskan singkat fokus program kategori ini...">{{ old('description') }}</textarea>
            </div>

            <button type="submit" class="page-action-btn page-action-btn--primary">
                <i class="bi bi-check-circle"></i> Simpan Kategori
            </button>
        </form>

    </div>

@endsection