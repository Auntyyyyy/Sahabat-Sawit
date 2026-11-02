@extends('layouts.go')

@section('page-title', 'Edit Fasilitas')
@section('page-subtitle', $facility->kode_fasilitas . ' — ' . $facility->nama)

@section('content')

<div class="panel">
    <div class="panel-head">
        <h2>Edit Fasilitas</h2>
        <a href="{{ route('go.facilities.index') }}" class="panel-link">← Kembali</a>
    </div>

    <form action="{{ route('go.facilities.update', $facility) }}" method="POST" enctype="multipart/form-data">
        @method('PUT')
        @include('go.facilities._form')
    </form>
</div>

@endsection