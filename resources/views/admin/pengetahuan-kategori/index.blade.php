@extends('layouts.admin')

@section('content')

    {{-- ===== Tambah Kategori ===== --}}
    <div class="admin-card mb-4">

        <div class="admin-card-head">
            <div class="admin-card-head-icon">
                <i class="bi bi-tags"></i>
            </div>
            <div class="flex-grow-1">
                <h2 class="admin-card-title">Kategori Pengetahuan</h2>
                <p class="admin-card-desc">Contoh: Tahukah Anda?, Fakta & Mitos. Kategori ini tampil sebagai label dan filter di halaman Pengetahuan.</p>
            </div>
            <a href="{{ route('admin.pengetahuan.index') }}" class="page-action-btn">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success mx-3 mt-3">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger mx-3 mt-3">{{ session('error') }}</div>
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

        <form action="{{ route('admin.pengetahuan-kategori.store') }}" method="POST" class="p-3">
            @csrf
            <div class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label for="nama" class="form-label fw-semibold">Nama Kategori Baru</label>
                    <input type="text" name="nama" id="nama" class="form-control"
                           value="{{ old('nama') }}" placeholder="Contoh: Sejarah Sawit" required>
                </div>
                <div class="col-md-3">
                    <label for="order" class="form-label fw-semibold">Urutan Tampil</label>
                    <input type="number" name="order" id="order" class="form-control"
                           value="{{ old('order', 0) }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="page-action-btn page-action-btn--primary">
                        <i class="bi bi-plus-circle"></i> Tambah Kategori
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- ===== Daftar Kategori ===== --}}
    <div class="admin-card">
        <div class="table-responsive">
            <table class="admin-table mb-0">
                <thead>
                    <tr>
                        <th>Nama Kategori</th>
                        <th style="width: 130px;">Jumlah Artikel</th>
                        <th style="width: 110px;">Urutan</th>
                        <th class="text-end" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kategoris as $kategori)
                        <tr>
                            <td><span class="fw-semibold">{{ $kategori->nama }}</span></td>
                            <td>{{ $kategori->pengetahuans_count }}</td>
                            <td>{{ $kategori->order }}</td>
                            <td class="text-end">
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="{{ route('admin.pengetahuan-kategori.edit', $kategori) }}" class="page-action-btn">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.pengetahuan-kategori.destroy', $kategori) }}" method="POST"
                                          onsubmit="return confirm('Hapus kategori ini?');">
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
                            <td colspan="4">
                                <div class="text-center py-5">
                                    <i class="bi bi-inbox fs-1 text-muted"></i>
                                    <p class="text-muted mt-2 mb-0">Belum ada kategori.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection