@extends('layouts.go')

@section('page-title', 'Tambah Fasilitas')
@section('page-subtitle', 'Catat fasilitas baru')

@section('content')

<div class="panel">
    <div class="panel-head">
        <h2>Tambah Fasilitas Baru</h2>
        <a href="{{ route('go.facilities.index') }}" class="panel-link">← Kembali</a>
    </div>

    <form action="{{ route('go.facilities.store') }}" method="POST" enctype="multipart/form-data">
        @include('go.facilities._form')
    </form>
</div>

@endsection