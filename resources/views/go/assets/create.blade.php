@extends('layouts.admin')

@section('title', 'Tambah Aset')

@section('content')

<div class="admin-breadcrumb">
    <a href="{{ route('go.dashboard') }}">Dashboard GA</a>
    <i class="bi bi-chevron-right"></i>
    <a href="{{ route('go.assets.index') }}">Aset</a>
    <i class="bi bi-chevron-right"></i>
    <span>Tambah Aset</span>
</div>

<div class="admin-card">
    <div class="admin-card-head">
        <div class="d-flex align-items-center gap-3">
            <div class="admin-card-head-icon"><i class="bi bi-box-seam"></i></div>
            <div>
                <h1 class="admin-card-title">Tambah Aset Baru</h1>
                <p class="admin-card-desc">Catat aset baru ke dalam inventaris General Affair.</p>
            </div>
        </div>
    </div>

    <form action="{{ route('go.assets.store') }}" method="POST" enctype="multipart/form-data">
        @include('go.assets._form')
    </form>
</div>

@endsection