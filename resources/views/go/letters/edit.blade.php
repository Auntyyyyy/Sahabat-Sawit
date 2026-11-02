@extends('layouts.go')

@section('page-title', 'Edit Surat')
@section('page-subtitle', $letter->nomor_surat . ' — ' . $letter->perihal)

@section('content')

<div class="panel">
    <div class="panel-head">
        <h2>Edit Surat</h2>
        <a href="{{ route('go.letters.index') }}" class="panel-link">← Kembali</a>
    </div>

    <form action="{{ route('go.letters.update', $letter) }}" method="POST" enctype="multipart/form-data">
        @method('PUT')
        @include('go.letters._form')
    </form>
</div>

@endsection