@extends('layouts.admin')

@section('title', 'Edit Produk')
@section('page-title', 'Edit Produk')

@section('content')

    <div class="admin-card">

        <div class="admin-card-head">
            <div>
                <h2 class="admin-card-title">Edit Produk</h2>
                <p class="admin-card-desc">Perbarui informasi untuk produk "{{ $product->name }}".</p>
            </div>
        </div>

        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @include('admin.products._form', ['submitLabel' => 'Perbarui Produk'])
        </form>

    </div>

@endsection