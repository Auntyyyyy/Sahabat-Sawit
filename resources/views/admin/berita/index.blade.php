@extends('layouts.admin')

@section('content')

    <div class="admin-card">

        <div class="admin-card-head">
            <div class="admin-card-head-icon">
                <i class="bi bi-newspaper"></i>
            </div>
            <div class="flex-grow-1">
                <h2 class="admin-card-title">Kelola Berita & Galeri Kegiatan</h2>
                <p class="admin-card-desc">Tambah, ubah, atau hapus berita yang tampil di halaman Media website.</p>
            </div>

            <a href="{{ route('admin.berita.create') }}" class="page-action-btn page-action-btn--primary">
                <i class="bi bi-plus-circle"></i> Tambah Berita
            </a>
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
                        <th style="width: 160px;">Tanggal</th>
                        <th class="text-end" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($beritas as $item)
                        <tr>
                            <td>
                                <img src="{{ $item->gambar ? asset('storage/' . $item->gambar) : 'https://placehold.co/80x60/1F5F3B/F5F1E8?text=—' }}"
                                     alt="{{ $item->judul }}"
                                     style="width: 64px; height: 48px; object-fit: cover; border-radius: 8px;">
                            </td>
                            <td>
                                <span class="fw-semibold">{{ $item->judul }}</span>
                                <div class="text-muted small">{{ Str::limit($item->deskripsi_singkat, 70) }}</div>
                            </td>
                            <td>{{ $item->tanggal->translatedFormat('d F Y') }}</td>
                            <td class="text-end">
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="{{ route('admin.berita.edit', $item) }}" class="page-action-btn">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.berita.destroy', $item) }}" method="POST"
                                          onsubmit="return confirm('Hapus berita ini? Tindakan ini tidak bisa dibatalkan.');">
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
                                    <p class="text-muted mt-2 mb-0">Belum ada berita ditambahkan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($beritas->hasPages())
            <div class="p-3">
                {{ $beritas->links() }}
            </div>
        @endif

    </div>

@endsection