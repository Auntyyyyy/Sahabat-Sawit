@extends('layouts.admin')

@section('content')

    <div class="admin-card">

        <div class="admin-card-head">
            <div class="admin-card-head-icon">
                <i class="bi bi-pencil-square"></i>
            </div>
            <div class="flex-grow-1">
                <h2 class="admin-card-title">Edit Kategori</h2>
                <p class="admin-card-desc">Ubah nama atau urutan kategori "{{ $kategori->nama }}".</p>
            </div>
            <a href="{{ route('admin.pengetahuan-kategori.index') }}" class="page-action-btn">
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

        <form action="{{ route('admin.pengetahuan-kategori.update', $kategori) }}" method="POST" class="p-3">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="nama" class="form-label fw-semibold">Nama Kategori</label>
                <input type="text" name="nama" id="nama" class="form-control"
                       value="{{ old('nama', $kategori->nama) }}" required>
            </div>

            <div class="mb-4">
                <label for="order" class="form-label fw-semibold">Urutan Tampil</label>
                <input type="number" name="order" id="order" class="form-control" style="max-width: 160px;"
                       value="{{ old('order', $kategori->order) }}">
            </div>

            <button type="submit" class="page-action-btn page-action-btn--primary">
                <i class="bi bi-check-circle"></i> Simpan Perubahan
            </button>
        </form>

    </div>

@endsection