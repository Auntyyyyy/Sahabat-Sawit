@extends('layouts.admin')
@section('title', 'Tambah Produk')
@section('page-title', 'Tambah Produk')

@section('content')

    <div class="admin-breadcrumb">
        <a href="{{ route('admin.products.index') }}">Produk</a>
        <i class="bi bi-chevron-right"></i>
        <span>Tambah Produk</span>
    </div>

    @if ($errors->any())
        <div class="admin-alert admin-alert--error">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div>
                <strong>Periksa kembali isian kamu:</strong>
                <ul class="mb-0 mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="admin-card product-form-card">

        <div class="admin-card-head">
            <div class="admin-card-head-icon">
                <i class="bi bi-box-seam"></i>
            </div>
            <div>
                <h2 class="admin-card-title">Tambah Produk Baru</h2>
                <p class="admin-card-desc">Lengkapi informasi produk yang akan ditampilkan di halaman Produk.</p>
            </div>
        </div>

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="product-form">
            @csrf
            @include('admin.products._form', ['submitLabel' => 'Simpan Produk'])
        </form>

    </div>

@endsection