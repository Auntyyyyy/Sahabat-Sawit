@extends('layouts.admin')

@section('content')

    <div class="admin-card">

        <div class="admin-card-head">
            <div class="admin-card-head-icon">
                <i class="bi bi-heart"></i>
            </div>
            <div class="flex-grow-1">
                <h2 class="admin-card-title">Kelola Kategori & Kegiatan CSR</h2>
                <p class="admin-card-desc">Kategori program CSR yang tampil di halaman Keberlanjutan, beserta daftar kegiatannya.</p>
            </div>
            <a href="{{ route('admin.csr-categories.create') }}" class="page-action-btn page-action-btn--primary">
                <i class="bi bi-plus-circle"></i> Tambah Kategori
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success mx-3 mt-3">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="admin-table mb-0">
                <thead>
                    <tr>
                        <th style="width: 60px;">Ikon</th>
                        <th>Kategori</th>
                        <th style="width: 140px;">Jumlah Kegiatan</th>
                        <th class="text-end" style="width: 160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td class="fs-4">{{ $category->icon }}</td>
                            <td>
                                <span class="fw-semibold">{{ $category->title }}</span>
                                <div class="text-muted small">{{ Str::limit($category->description, 80) }}</div>
                            </td>
                            <td>{{ $category->activities_count }} kegiatan</td>
                            <td class="text-end">
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="{{ route('admin.csr-categories.edit', $category) }}" class="page-action-btn">
                                        <i class="bi bi-pencil-square"></i> Kelola
                                    </a>
                                    <form action="{{ route('admin.csr-categories.destroy', $category) }}" method="POST"
                                          onsubmit="return confirm('Hapus kategori ini beserta semua kegiatan di dalamnya? Tindakan ini tidak bisa dibatalkan.');">
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
                                    <p class="text-muted mt-2 mb-0">Belum ada kategori CSR ditambahkan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

@endsection