@extends('layouts.admin')

@section('content')

    <div class="admin-card">

        <div class="admin-card-head">
            <div class="admin-card-head-icon">
                <i class="bi bi-plus-circle"></i>
            </div>
            <div class="flex-grow-1">
                <h2 class="admin-card-title">Tambah Pengetahuan</h2>
                <p class="admin-card-desc">Tulis satu fakta/edukasi singkat seputar kelapa sawit.</p>
            </div>
            <a href="{{ route('admin.pengetahuan.index') }}" class="page-action-btn">
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

        <form action="{{ route('admin.pengetahuan.store') }}" method="POST" enctype="multipart/form-data" class="p-3">
            @csrf

            <div class="mb-3">
                <label for="judul" class="form-label fw-semibold">Judul</label>
                <input type="text" name="judul" id="judul" class="form-control"
                       value="{{ old('judul') }}" placeholder="Contoh: Tahukah Anda, 1 Ton TBS Bisa Hasilkan Berapa Liter CPO?" required>
            </div>

            <div class="mb-3">
                <label for="gambar" class="form-label fw-semibold">Gambar (opsional)</label>
                <input type="file" name="gambar" id="gambar" class="form-control" accept="image/*">
                <div class="form-text">Format JPG/PNG, maksimal 5MB. Boleh dikosongkan.</div>
            </div>

            <div class="mb-3">
                <label for="order" class="form-label fw-semibold">Urutan Tampil</label>
                <input type="number" name="order" id="order" class="form-control" style="max-width: 160px;"
                       value="{{ old('order', 0) }}">
            </div>

            <div class="mb-4">
                <label for="ringkasan" class="form-label fw-semibold">Penjelasan Singkat</label>
                <textarea name="ringkasan" id="ringkasan" rows="5" class="form-control"
                          placeholder="Tulis penjelasannya di sini, singkat saja...">{{ old('ringkasan') }}</textarea>
            </div>

            <button type="submit" class="page-action-btn page-action-btn--primary">
                <i class="bi bi-check-circle"></i> Simpan
            </button>
        </form>

    </div>

@endsection