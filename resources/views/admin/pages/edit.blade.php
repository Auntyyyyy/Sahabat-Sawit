@extends('layouts.admin')

@section('title', 'Edit ' . ($page->title ?? $pageKey))
@section('page-title', 'Edit Konten Halaman')

@section('content')

    <div class="admin-card">

        <div class="admin-card-head">
            <div class="admin-card-head-icon">
                <i class="bi bi-file-earmark-richtext"></i>
            </div>
            <div class="flex-grow-1">
                <h2 class="admin-card-title">Edit Konten Halaman: {{ $page->title ?? $pageKey }}</h2>
                <p class="admin-card-desc">Perbarui judul, isi konten, dan gambar untuk halaman "{{ $pageKey }}".</p>
            </div>
            <a href="{{ route('admin.pages.index') }}" class="page-action-btn">
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

        <form action="{{ route('admin.pages.update', $pageKey) }}" method="POST" enctype="multipart/form-data" class="p-3">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="title" class="form-label fw-semibold">Judul Halaman</label>
                <input type="text" name="title" id="title" class="form-control"
                       value="{{ old('title', $page->title) }}" required>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label fw-semibold">Gambar</label>

                @if($page->image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $page->image) }}" alt="{{ $page->title }}"
                             style="width: 180px; height: 120px; object-fit: cover; border-radius: 10px;">
                    </div>
                    <label for="image" class="form-label">Ganti Gambar (opsional)</label>
                @endif

                <input type="file" name="image" id="image" class="form-control" accept="image/*">
                <div class="form-text">
                    {{ $page->image ? 'Biarkan kosong kalau tidak ingin mengganti gambar.' : 'Belum ada gambar untuk halaman ini.' }}
                    Format JPG/PNG, maksimal 2MB.
                </div>
            </div>

            <div class="mb-4">
                <label for="content" class="form-label fw-semibold">Isi Konten</label>
                <textarea name="content" id="content" rows="10" class="form-control"
                          placeholder="Tulis isi konten halaman di sini...">{{ old('content', $page->content) }}</textarea>
            </div>

            <button type="submit" class="page-action-btn page-action-btn--primary">
                <i class="bi bi-check-circle"></i> Simpan Perubahan
            </button>
        </form>

    </div>

@endsection