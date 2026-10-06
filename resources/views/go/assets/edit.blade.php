@extends('layouts.admin')

@section('title', 'Edit Aset')

@section('content')

<div class="admin-breadcrumb">
    <a href="{{ route('go.dashboard') }}">Dashboard GA</a>
    <i class="bi bi-chevron-right"></i>
    <a href="{{ route('go.assets.index') }}">Aset</a>
    <i class="bi bi-chevron-right"></i>
    <span>Edit — {{ $asset->nama }}</span>
</div>

<div class="admin-card">
    <div class="admin-card-head">
        <div class="d-flex align-items-center gap-3">
            <div class="admin-card-head-icon"><i class="bi bi-pencil-square"></i></div>
            <div>
                <h1 class="admin-card-title">Edit Aset</h1>
                <p class="admin-card-desc">{{ $asset->kode_aset }} — {{ $asset->nama }}</p>
            </div>
        </div>
    </div>

    <form action="{{ route('go.assets.update', $asset) }}" method="POST" enctype="multipart/form-data">
        @method('PUT')
        @include('go.assets._form')
    </form>
</div>

@endsection