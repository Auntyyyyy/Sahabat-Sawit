@extends('layouts.admin')
@section('title', 'Edit Produk')
@section('page-title', 'Edit Produk')

@section('content')

    <div class="admin-breadcrumb">
        <a href="{{ route('admin.products.index') }}">Produk</a>
        <i class="bi bi-chevron-right"></i>
        <span>Edit Produk</span>
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
                <h2 class="admin-card-title">Edit Produk</h2>
                <p class="admin-card-desc">Perbarui informasi produk "{{ $product->name }}".</p>
            </div>
        </div>

        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="product-form">
            @csrf
            @method('PUT')
            @include('admin.products._form', ['submitLabel' => 'Update Produk'])
        </form>

    </div>

@endsection