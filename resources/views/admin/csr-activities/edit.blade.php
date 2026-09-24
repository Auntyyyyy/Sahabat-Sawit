@extends('layouts.admin')

@section('content')

    <div class="admin-card">

        <div class="admin-card-head">
            <div class="admin-card-head-icon">
                <i class="bi bi-pencil-square"></i>
            </div>
            <div class="flex-grow-1">
                <h2 class="admin-card-title">Edit Kegiatan</h2>
                <p class="admin-card-desc">Ubah detail kegiatan "{{ $activity->title }}".</p>
            </div>
            <a href="{{ route('admin.csr-categories.edit', $activity->csr_category_id) }}" class="page-action-btn">
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

        <form action="{{ route('admin.csr-categories.activities.update', $activity) }}" method="POST" enctype="multipart/form-data" class="p-3">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="title" class="form-label fw-semibold">Judul Kegiatan</label>
                <input type="text" name="title" id="title" class="form-control"
                       value="{{ old('title', $activity->title) }}" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="date" class="form-label fw-semibold">Tanggal</label>
                    <input type="text" name="date" id="date" class="form-control"
                           value="{{ old('date', $activity->date) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="location" class="form-label fw-semibold">Lokasi</label>
                    <input type="text" name="location" id="location" class="form-control"
                           value="{{ old('location', $activity->location) }}" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Gambar Saat Ini</label>
                <div class="mb-2">
                    <img src="{{ $activity->image ? asset('storage/' . $activity->image) : 'https://placehold.co/300x200/164A2E/F5F1E8?text=—' }}"
                         alt="{{ $activity->title }}"
                         style="width: 180px; height: 120px; object-fit: cover; border-radius: 10px;">
                </div>
                <label for="image" class="form-label">Ganti Gambar (opsional)</label>
                <input type="file" name="image" id="image" class="form-control" accept="image/*">
                <div class="form-text">Biarkan kosong kalau tidak ingin mengganti gambar. Maksimal 5MB.</div>
            </div>

            <div class="mb-3">
                <label for="order" class="form-label fw-semibold">Urutan Tampil</label>
                <input type="number" name="order" id="order" class="form-control" style="max-width: 160px;"
                       value="{{ old('order', $activity->order) }}">
            </div>

            <div class="mb-4">
                <label for="story" class="form-label fw-semibold">Cerita / Deskripsi Kegiatan</label>
                <textarea name="story" id="story" rows="5" class="form-control">{{ old('story', $activity->story) }}</textarea>
            </div>

            <button type="submit" class="page-action-btn page-action-btn--primary">
                <i class="bi bi-check-circle"></i> Simpan Perubahan
            </button>
        </form>

    </div>

@endsection