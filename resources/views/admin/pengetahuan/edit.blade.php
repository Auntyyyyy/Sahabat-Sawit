@extends('layouts.admin')

@section('content')

    <div class="admin-card">

        <div class="admin-card-head">
            <div class="admin-card-head-icon">
                <i class="bi bi-pencil-square"></i>
            </div>
            <div class="flex-grow-1">
                <h2 class="admin-card-title">Edit Pengetahuan</h2>
                <p class="admin-card-desc">Ubah konten "{{ $pengetahuan->judul }}".</p>
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

        <form action="{{ route('admin.pengetahuan.update', $pengetahuan) }}" method="POST" enctype="multipart/form-data" class="p-3">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="judul" class="form-label fw-semibold">Judul</label>
                <input type="text" name="judul" id="judul" class="form-control"
                       value="{{ old('judul', $pengetahuan->judul) }}" required>
            </div>

            {{-- BARU: pilihan kategori --}}
            <div class="mb-3">
                <label for="kategori_id" class="form-label fw-semibold">Kategori</label>
                <select name="kategori_id" id="kategori_id" class="form-select" style="max-width: 320px;" required>
                    <option value="">— Pilih kategori —</option>
                    @foreach ($kategoris as $kategori)
                        <option value="{{ $kategori->id }}"
                            {{ old('kategori_id', $pengetahuan->kategori_id) == $kategori->id ? 'selected' : '' }}>
                            {{ $kategori->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Gambar Saat Ini</label>
                @if($pengetahuan->gambar)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $pengetahuan->gambar) }}" alt="{{ $pengetahuan->judul }}"
                         style="width: 180px; height: 120px; object-fit: cover; border-radius: 10px;">
                </div>
                @else
                <p class="text-muted small mb-2">Belum ada gambar.</p>
                @endif
                <label for="gambar" class="form-label">Ganti/Tambah Gambar (opsional)</label>
                <input type="file" name="gambar" id="gambar" class="form-control" accept="image/*">
                <div class="form-text">Biarkan kosong kalau tidak ingin mengganti gambar. Maksimal 5MB.</div>
            </div>

            <div class="mb-3">
                <label for="order" class="form-label fw-semibold">Urutan Tampil</label>
                <input type="number" name="order" id="order" class="form-control" style="max-width: 160px;"
                       value="{{ old('order', $pengetahuan->order) }}">
            </div>

            <div class="mb-4">
                <label for="ringkasan" class="form-label fw-semibold">Penjelasan Singkat</label>
                <textarea name="ringkasan" id="ringkasan" rows="5" class="form-control">{{ old('ringkasan', $pengetahuan->ringkasan) }}</textarea>
            </div>

            <button type="submit" class="page-action-btn page-action-btn--primary">
                <i class="bi bi-check-circle"></i> Simpan Perubahan
            </button>
        </form>

    </div>

@endsection