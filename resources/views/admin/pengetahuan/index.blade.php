@extends('layouts.admin')

@section('content')

    <div class="admin-card">

        <div class="admin-card-head">
            <div class="admin-card-head-icon">
                <i class="bi bi-lightbulb"></i>
            </div>
            <div class="flex-grow-1">
                <h2 class="admin-card-title">Kelola Pengetahuan</h2>
                <p class="admin-card-desc">Fakta & edukasi seputar kelapa sawit yang tampil di menu Media.</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                {{-- BARU: tombol ke halaman kategori --}}
                <a href="{{ route('admin.pengetahuan-kategori.index') }}" class="page-action-btn">
                    <i class="bi bi-tags"></i> Kelola Kategori
                </a>
                <a href="{{ route('admin.pengetahuan.create') }}" class="page-action-btn page-action-btn--primary">
                    <i class="bi bi-plus-circle"></i> Tambah Pengetahuan
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success mx-3 mt-3">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="admin-table mb-0">
                <thead>
                    <tr>
                        <th style="width: 90px;">Gambar</th>
                        <th>Judul</th>
                        {{-- BARU: kolom kategori --}}
                        <th style="width: 170px;">Kategori</th>
                        <th class="text-end" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pengetahuans as $item)
                        <tr>
                            <td>
                                <img src="{{ $item->gambar ? asset('storage/' . $item->gambar) : 'https://placehold.co/80x60/2F6B3F/F5F1E8?text=—' }}"
                                     alt="{{ $item->judul }}"
                                     style="width: 64px; height: 48px; object-fit: cover; border-radius: 8px;">
                            </td>
                            <td>
                                <span class="fw-semibold">{{ $item->judul }}</span>
                                <div class="text-muted small">{{ Str::limit($item->ringkasan, 80) }}</div>
                            </td>
                            <td>{{ $item->kategori?->nama ?? '—' }}</td>
                            <td class="text-end">
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="{{ route('admin.pengetahuan.edit', $item) }}" class="page-action-btn">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.pengetahuan.destroy', $item) }}" method="POST"
                                          onsubmit="return confirm('Hapus konten pengetahuan ini?');">
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
                                    <p class="text-muted mt-2 mb-0">Belum ada konten pengetahuan ditambahkan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pengetahuans->hasPages())
            <div class="p-3">
                {{ $pengetahuans->links() }}
            </div>
        @endif

    </div>

@endsection