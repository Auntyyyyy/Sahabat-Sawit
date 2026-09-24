@extends('layouts.admin')

@section('content')

    {{-- ===== Form Edit Kategori ===== --}}
    <div class="admin-card mb-4">

        <div class="admin-card-head">
            <div class="admin-card-head-icon">
                <i class="bi bi-pencil-square"></i>
            </div>
            <div class="flex-grow-1">
                <h2 class="admin-card-title">Edit Kategori: {{ $category->title }}</h2>
                <p class="admin-card-desc">Ubah detail kategori CSR ini.</p>
            </div>
            <a href="{{ route('admin.csr-categories.index') }}" class="page-action-btn">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success mx-3 mt-3">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger mx-3 mt-3">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.csr-categories.update', $category) }}" method="POST" class="p-3">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="title" class="form-label fw-semibold">Nama Kategori</label>
                <input type="text" name="title" id="title" class="form-control"
                       value="{{ old('title', $category->title) }}" required>
            </div>

            <div class="mb-3">
                <label for="icon" class="form-label fw-semibold">Ikon (emoji)</label>
                <input type="text" name="icon" id="icon" class="form-control" style="max-width: 120px;"
                       value="{{ old('icon', $category->icon) }}">
            </div>

            <div class="mb-3">
                <label for="order" class="form-label fw-semibold">Urutan Tampil</label>
                <input type="number" name="order" id="order" class="form-control" style="max-width: 160px;"
                       value="{{ old('order', $category->order) }}">
            </div>

            <div class="mb-4">
                <label for="description" class="form-label fw-semibold">Deskripsi Kategori</label>
                <textarea name="description" id="description" rows="4" class="form-control">{{ old('description', $category->description) }}</textarea>
            </div>

            <button type="submit" class="page-action-btn page-action-btn--primary">
                <i class="bi bi-check-circle"></i> Simpan Perubahan
            </button>
        </form>
    </div>

    {{-- ===== Daftar Kegiatan di Kategori Ini ===== --}}
    <div class="admin-card">

        <div class="admin-card-head">
            <div class="admin-card-head-icon">
                <i class="bi bi-images"></i>
            </div>
            <div class="flex-grow-1">
                <h2 class="admin-card-title">Kegiatan dalam Kategori Ini</h2>
                <p class="admin-card-desc">Setiap kegiatan tampil sebagai satu kartu di halaman Keberlanjutan.</p>
            </div>
            <a href="{{ route('admin.csr-categories.activities.create', $category) }}" class="page-action-btn page-action-btn--primary">
                <i class="bi bi-plus-circle"></i> Tambah Kegiatan
            </a>
        </div>

        <div class="table-responsive">
            <table class="admin-table mb-0">
                <thead>
                    <tr>
                        <th style="width: 90px;">Gambar</th>
                        <th>Kegiatan</th>
                        <th style="width: 160px;">Tanggal</th>
                        <th style="width: 180px;">Lokasi</th>
                        <th class="text-end" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($category->activities as $activity)
                        <tr>
                            <td>
                                <img src="{{ $activity->image ? asset('storage/' . $activity->image) : 'https://placehold.co/80x60/164A2E/F5F1E8?text=—' }}"
                                     alt="{{ $activity->title }}"
                                     style="width: 64px; height: 48px; object-fit: cover; border-radius: 8px;">
                            </td>
                            <td>
                                <span class="fw-semibold">{{ $activity->title }}</span>
                                <div class="text-muted small">{{ Str::limit($activity->story, 70) }}</div>
                            </td>
                            <td>{{ $activity->date }}</td>
                            <td>{{ $activity->location }}</td>
                            <td class="text-end">
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="{{ route('admin.csr-categories.activities.edit', $activity) }}" class="page-action-btn">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.csr-categories.activities.destroy', $activity) }}" method="POST"
                                          onsubmit="return confirm('Hapus kegiatan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="page-action-btn text-danger">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="text-center py-5">
                                    <i class="bi bi-inbox fs-1 text-muted"></i>
                                    <p class="text-muted mt-2 mb-0">Belum ada kegiatan di kategori ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

@endsection