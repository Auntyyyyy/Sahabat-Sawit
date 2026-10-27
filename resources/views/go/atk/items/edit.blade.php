@extends('layouts.go')

@section('page-title', 'Edit Barang ATK')
@section('page-subtitle', $atkItem->kode . ' — ' . $atkItem->nama)

@section('content')

<div class="panel">
    <div class="panel-head">
        <h2>Edit Barang</h2>
        <a href="{{ route('go.atk-items.index') }}" class="panel-link">← Kembali</a>
    </div>

    <form action="{{ route('go.atk-items.update', $atkItem) }}" method="POST">
        @method('PUT')
        @include('go.atk.items._form')
    </form>
</div>

@endsection