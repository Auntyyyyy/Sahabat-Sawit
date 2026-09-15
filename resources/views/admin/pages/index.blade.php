@extends('layouts.admin')

@section('content')

    @php
        $totalPages = count($pageKeys);
        $filledPages = count($pages);
        $progressPercent = $totalPages > 0 ? round(($filledPages / $totalPages) * 100) : 0;
    @endphp

    <div class="admin-card pages-card">

        <div class="admin-card-head">
            <div class="admin-card-head-icon">
                <i class="bi bi-file-earmark-richtext"></i>
            </div>
            <div class="flex-grow-1">
                <h2 class="admin-card-title">Kelola Konten Halaman Statis</h2>
                <p class="admin-card-desc">Isi dan kelola konten yang tampil pada halaman-halaman statis website.</p>
            </div>

            <div class="pages-progress">
                <div class="pages-progress__label">
                    <span>{{ $filledPages }} / {{ $totalPages }} terisi</span>
                </div>
                <div class="pages-progress__bar">
                    <div class="pages-progress__fill" style="width: {{ $progressPercent }}%"></div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="admin-table mb-0">
                <thead>
                    <tr>
                        <th>Halaman</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pageKeys as $key => $label)
                        <tr>
                            <td>
                                <div class="page-name">
                                    <i class="bi bi-file-earmark-text page-name__icon"></i>
                                    <span class="fw-semibold">{{ $label }}</span>
                                </div>
                            </td>
                            <td>
                                @if (isset($pages[$key]))
                                    <span class="badge page-badge page-badge--filled">
                                        <i class="bi bi-check-circle-fill"></i> Sudah diisi
                                    </span>
                                @else
                                    <span class="badge page-badge page-badge--empty">
                                        <i class="bi bi-dash-circle"></i> Belum diisi
                                    </span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.pages.edit', $key) }}" class="page-action-btn {{ isset($pages[$key]) ? '' : 'page-action-btn--primary' }}">
                                    <i class="bi {{ isset($pages[$key]) ? 'bi-pencil-square' : 'bi-plus-circle' }}"></i>
                                    {{ isset($pages[$key]) ? 'Edit' : 'Isi Konten' }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">
                                <div class="text-center py-5">
                                    <i class="bi bi-inbox fs-1 text-muted"></i>
                                    <p class="text-muted mt-2 mb-0">Belum ada halaman terdaftar.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

@endsection