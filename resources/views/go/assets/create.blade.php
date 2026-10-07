@extends('layouts.go')

@section('page-title', 'Tambah Aset')
@section('page-subtitle', 'Catat aset baru ke dalam inventaris')

@section('content')

<div class="panel">
    <div class="panel-head">
        <h2>Tambah Aset Baru</h2>
        <a href="{{ route('go.assets.index') }}" class="panel-link">← Kembali</a>
    </div>

    <form action="{{ route('go.assets.store') }}" method="POST" enctype="multipart/form-data">
        @include('go.assets._form')
    </form>
</div>

@endsection