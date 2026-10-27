@extends('admin.layouts.k3')

@php
    // Form ini dipakai untuk Tambah dan Edit. Bedanya: dokumen yang sudah ada di database atau belum.
    $isEdit = $dokumen->exists;
@endphp

@section('title', $isEdit ? 'Edit Dokumen' : 'Tambah Dokumen')
@section('page_title', $isEdit ? 'Edit Dokumen Keselamatan' : 'Tambah Dokumen Keselamatan')

@section('content')

    <div class="k3-card" style="max-width: 820px;">

        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
            <div>
                <h2 class="k3-card-title" style="font-size:1.25rem">{{ $isEdit ? 'Edit Dokumen' : 'Tambah Dokumen' }}</h2>
                <p class="k3-card-sub mb-0">
                    {{ $isEdit ? 'Ubah detail dokumen keselamatan ini.' : 'Isi detail dokumen lalu unggah file PDF-nya.' }}
                </p>
            </div>
            <a href="{{ route('k3.dokumen.index') }}" class="k3-btn k3-btn-outline">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ $isEdit ? route('k3.dokumen.update', $dokumen) : route('k3.dokumen.store') }}"
              method="POST" enctype="multipart/form-data">
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif

            <div class="mb-3">
                <label class="k3-label" for="judul">Judul Dokumen</label>
                <input type="text" name="judul" id="judul" class="form-control"
                       value="{{ old('judul', $dokumen->judul) }}"
                       placeholder="Contoh: Rapat P2K3 Oktober 2026" required>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="k3-label" for="kategori">Kategori</label>
                    <select name="kategori" id="kategori" class="form-select" required>
                        <option value="">Pilih kategori</option>
                        @foreach (\App\Models\K3Dokumen::KATEGORI as $kat)
                            <option value="{{ $kat }}" @selected(old('kategori', $dokumen->kategori) === $kat)>{{ $kat }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="k3-label" for="tanggal">Tanggal Dokumen</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control"
                           value="{{ old('tanggal', $dokumen->tanggal?->format('Y-m-d')) }}" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="k3-label" for="status">Status</label>
                <select name="status" id="status" class="form-select" style="max-width: 240px;" required>
                    <option value="aktif" @selected(old('status', $dokumen->status) === 'aktif')>Aktif</option>
                    <option value="arsip" @selected(old('status', $dokumen->status) === 'arsip')>Arsip</option>
                </select>
                <div class="form-text">Dokumen berstatus Aktif dihitung di dashboard K3.</div>
            </div>

            <div class="mb-4">
                <label class="k3-label" for="file">File PDF {{ $isEdit ? '(opsional)' : '' }}</label>

                @if ($isEdit && $dokumen->file)
                    <div class="mb-2">
                        <a href="{{ asset('storage/' . $dokumen->file) }}" target="_blank" rel="noopener" class="k3-pill pill-navy text-decoration-none">
                            <i class="bi bi-file-earmark-pdf"></i> Lihat file saat ini
                        </a>
                    </div>
                @endif

                <input type="file" name="file" id="file" class="form-control" accept="application/pdf"
                       {{ $isEdit ? '' : 'required' }}>
                <div class="form-text">
                    Format PDF, maksimal 10 MB.
                    @if ($isEdit) Biarkan kosong kalau tidak ingin mengganti file. @endif
                </div>
            </div>

            <button type="submit" class="k3-btn k3-btn-red">
                <i class="bi bi-check-circle"></i> {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Dokumen' }}
            </button>
        </form>

    </div>

@endsection