@extends('layouts.go')

@section('page-title', 'Catat Surat')
@section('page-subtitle', 'Catat surat masuk atau surat keluar')

@section('content')

<div class="panel">
    <div class="panel-head">
        <h2>Catat Surat Baru</h2>
        <a href="{{ route('go.letters.index') }}" class="panel-link">← Kembali</a>
    </div>

    <form action="{{ route('go.letters.store') }}" method="POST" enctype="multipart/form-data">
        @include('go.letters._form')
    </form>
</div>

@endsection