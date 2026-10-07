@extends('layouts.go')

@section('page-title', 'Edit Aset')
@section('page-subtitle', $asset->kode_aset . ' — ' . $asset->nama)

@section('content')

<div class="panel">
    <div class="panel-head">
        <h2>Edit Aset</h2>
        <a href="{{ route('go.assets.index') }}" class="panel-link">← Kembali</a>
    </div>

    <form action="{{ route('go.assets.update', $asset) }}" method="POST" enctype="multipart/form-data">
        @method('PUT')
        @include('go.assets._form')
    </form>
</div>

@endsection