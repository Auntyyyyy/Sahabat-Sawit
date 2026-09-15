@extends('layouts.admin')

@section('title', 'Kelola Produk')
@section('page-title', 'Kelola Produk')

@section('content')

    <div class="admin-card">

        <div class="admin-card-head">
            <div>
                <h2 class="admin-card-title">
                    Daftar Produk
                    <span class="admin-count-badge">{{ $products->total() }}</span>
                </h2>
                <p class="admin-card-desc">Kelola produk yang ditampilkan pada halaman Produk website.</p>
            </div>
            <a href="{{ route('admin.products.create') }}" class="btn btn-ssrs-primary">
                <i class="bi bi-plus-lg"></i> Tambah Produk
            </a>
        </div>

        @if ($products->isEmpty())

            <div class="admin-empty">
                <i class="bi bi-box-seam admin-empty-icon"></i>
                <p>Belum ada produk. Klik <strong>Tambah Produk</strong> untuk menambahkan produk pertama.</p>
            </div>

        @else

            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Urutan</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>
                                    <div class="admin-table-thumb">
                                        @if ($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                                        @else
                                            <span class="admin-table-thumb-empty">N/A</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="admin-table-name">{{ $product->name }}</span>
                                </td>
                                <td>
                                    <span class="admin-badge">{{ $product->category ?: '—' }}</span>
                                </td>
                                <td>{{ $product->order }}</td>
                                <td>
                                    @if ($product->is_active)
                                        <span class="admin-status admin-status-active">Aktif</span>
                                    @else
                                        <span class="admin-status admin-status-inactive">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="admin-table-actions">
                                        <a href="{{ route('admin.products.edit', $product) }}" class="admin-action-btn">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>

                                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                              onsubmit="return confirm('Hapus produk {{ $product->name }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="admin-action-btn admin-action-btn-danger">
                                                <i class="bi bi-trash3"></i> Hapus
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
                {{ $products->links() }}
            </div>

        @endif

    </div>

@endsection