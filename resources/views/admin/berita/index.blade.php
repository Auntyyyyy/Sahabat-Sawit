@extends('layouts.admin')

@section('title', 'Kelola Berita')

@section('content')

<div class="admin-breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <i class="bi bi-chevron-right"></i>
    <span>Berita & Galeri Kegiatan</span>
</div>

@if(session('success'))
<div class="admin-alert--success">
    <i class="bi bi-check-circle-fill"></i>
    <span>{{ session('success') }}</span>
</div>
@endif

<div class="admin-card">

    <div class="admin-card-head">
        <div class="d-flex align-items-center gap-3">
            <div class="admin-card-head-icon">
                <i class="bi bi-newspaper"></i>
            </div>
            <div>
                <h2 class="admin-card-title">
                    Kelola Berita & Galeri Kegiatan
                    <span class="admin-count-badge">{{ $beritas->total() }}</span>
                </h2>
                <p class="admin-card-desc">Tambah, ubah, atau hapus berita yang tampil di halaman Media website.</p>
            </div>
        </div>

        <a href="{{ route('admin.berita.create') }}" class="btn-ssrs-primary">
            <i class="bi bi-plus-lg"></i> Tambah Berita
        </a>
    </div>

    @if($beritas->isEmpty())
        <div class="admin-empty">
            <i class="bi bi-inbox admin-empty-icon"></i>
            <p>Belum ada berita ditambahkan. <strong>Mulai tulis berita pertama.</strong></p>
        </div>
    @else
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 90px;">Gambar</th>
                        <th>Judul</th>
                        <th style="width: 170px;">Tanggal</th>
                        <th class="text-end" style="width: 190px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($beritas as $item)
                    <tr>
                        <td>
                            <div class="admin-table-thumb" style="width: 64px; height: 48px;">
                                <img src="{{ $item->gambar ? asset('storage/' . $item->gambar) : 'https://placehold.co/80x60/1F5F3B/F5F1E8?text=—' }}"
                                     alt="{{ $item->judul }}"
                                     onerror="this.onerror=null;this.src='https://placehold.co/80x60/1F5F3B/F5F1E8?text=—'">
                            </div>
                        </td>
                        <td>
                            <span class="admin-table-name">{{ $item->judul }}</span>
                            <div class="text-muted small mt-1">{{ Str::limit($item->deskripsi_singkat, 90) }}</div>
                        </td>
                        <td>
                            <span class="text-muted small">
                                <i class="bi bi-calendar3 me-1"></i>{{ $item->tanggal->translatedFormat('d F Y') }}
                            </span>
                        </td>
                        <td>
                            <div class="admin-table-actions">
                                <a href="{{ route('admin.berita.edit', $item) }}" class="admin-action-btn">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('admin.berita.destroy', $item) }}" method="POST"
                                      onsubmit="return confirm('Hapus berita ini? Tindakan ini tidak bisa dibatalkan.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="admin-action-btn admin-action-btn-danger">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($beritas->hasPages())
        <div class="admin-pagination">
            {{ $beritas->links('pagination::bootstrap-5') }}
        </div>
        @endif
    @endif

</div>

@endsection