@extends('layouts.go')

@section('page-title', 'Tambah Barang ATK')
@section('page-subtitle', 'Daftarkan barang baru ke stok ATK')

@section('content')

<div class="panel">
    <div class="panel-head">
        <h2>Tambah Barang Baru</h2>
        <a href="{{ route('go.atk-items.index') }}" class="panel-link">← Kembali</a>
    </div>

    <form action="{{ route('go.atk-items.store') }}" method="POST">
        @include('go.atk.items._form')
    </form>
</div>

@endsection