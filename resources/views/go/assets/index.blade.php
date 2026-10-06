@extends('layouts.admin')

@section('title', 'Kelola Aset')

@section('content')

@if(session('success'))
<div class="admin-alert--success">
    <i class="bi bi-check-circle-fill"></i>
    <span>{{ session('success') }}</span>
</div>
@endif

<div class="admin-breadcrumb">
    <a href="{{ route('go.dashboard') }}">Dashboard GA</a>
    <i class="bi bi-chevron-right"></i>
    <span>Aset</span>
</div>

<div class="admin-card pages-card">
    <div class="admin-card-head">
        <div class="d-flex align-items-center gap-3">
            <div class="admin-card-head-icon"><i class="bi bi-box-seam"></i></div>
            <div>
                <h1 class="admin-card-title">
                    Kelola Aset
                    <span class="admin-count-badge">{{ $assets->total() }}</span>
                </h1>
                <p class="admin-card-desc">Daftar seluruh aset/inventaris yang tercatat.</p>
            </div>
        </div>
        <a href="{{ route('go.assets.create') }}" class="btn-ssrs-primary">
            <i class="bi bi-plus-lg"></i> Tambah Aset
        </a>
    </div>

    {{-- Filter & pencarian --}}
    <form method="GET" action="{{ route('go.assets.index') }}" class="row g-3 align-items-end mb-4 px-4">
        <div class="col-sm-5">
            <label class="form-label small text-muted">Cari</label>
            <input type="text" name="q" value="{{ request('q') }}"
                   class="form-control admin-input" placeholder="Cari kode, nama, atau lokasi aset...">
        </div>
        <div class="col-sm-3">
            <label class="form-label small text-muted">Kategori</label>
            <select name="kategori" class="form-select admin-input">
                <option value="">Semua Kategori</option>
                @foreach($kategoriOptions as $opt)
                <option value="{{ $opt }}" @selected(request('kategori') === $opt)>{{ $opt }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-3">
            <label class="form-label small text-muted">Kondisi</label>
            <select name="kondisi" class="form-select admin-input">
                <option value="">Semua Kondisi</option>
                @foreach($kondisiOptions as $opt)
                <option value="{{ $opt }}" @selected(request('kondisi') === $opt)>{{ $opt }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-1">
            <button type="submit" class="btn-ssrs-primary w-100 justify-content-center">
                <i class="bi bi-search"></i>
            </button>
        </div>
    </form>

    @if($assets->isEmpty())
        <div class="admin-empty">
            <i class="bi bi-inbox admin-empty-icon"></i>
            <p>
                @if(request()->anyFilled(['q', 'kategori', 'kondisi']))
                    Tidak ada aset yang cocok dengan pencarian/filter ini.
                @else
                    Belum ada aset yang tercatat. <strong>Mulai tambahkan aset pertama kamu.</strong>
                @endif
            </p>
        </div>
    @else
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Aset</th>
                        <th>Kategori</th>
                        <th>Lokasi</th>
                        <th>Kondisi</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($assets as $asset)
                    <tr>
                        <td class="admin-table-name">{{ $asset->kode_aset }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="admin-table-thumb">
                                    @if($asset->foto)
                                        <img src="{{ asset('storage/' . $asset->foto) }}" alt="{{ $asset->nama }}">
                                    @else
                                        <span class="admin-table-thumb-empty">N/A</span>
                                    @endif
                                </div>
                                <span>{{ $asset->nama }}</span>
                            </div>
                        </td>
                        <td>{{ $asset->kategori }}</td>
                        <td>{{ $asset->lokasi ?: '—' }}</td>
                        <td><span class="admin-status {{ $asset->kondisiBadgeClass() }}">{{ $asset->kondisi }}</span></td>
                        <td><span class="admin-badge">{{ $asset->status }}</span></td>
                        <td>
                            <div class="admin-table-actions">
                                <a href="{{ route('go.assets.edit', $asset) }}" class="admin-action-btn">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('go.assets.destroy', $asset) }}" method="POST"
                                      onsubmit="return confirm('Hapus aset {{ $asset->nama }}? Tindakan ini tidak bisa dibatalkan.');">
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

        <div class="admin-pagination">
            {{ $assets->links() }}
        </div>
    @endif
</div>

@endsection