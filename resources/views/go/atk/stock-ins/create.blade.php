@extends('layouts.go')

@section('page-title', 'Catat Barang Masuk')
@section('page-subtitle', 'Tambah stok setelah pembelian atau penerimaan barang')

@section('content')

<div class="panel">
    <div class="panel-head">
        <h2>Barang Masuk</h2>
        <a href="{{ route('go.atk-stock-ins.index') }}" class="panel-link">← Kembali</a>
    </div>

    <form action="{{ route('go.atk-stock-ins.store') }}" method="POST">
        @csrf
        <div class="form-grid">
            <div class="form-field full">
                <label>Barang</label>
                <select name="atk_item_id">
                    <option value="">— Pilih barang —</option>
                    @foreach($items as $item)
                    <option value="{{ $item->id }}" @selected((string) old('atk_item_id', $selectedItem) === (string) $item->id)>
                        {{ $item->kode }} — {{ $item->nama }} (stok {{ $item->stok }} {{ $item->satuan }})
                    </option>
                    @endforeach
                </select>
                @error('atk_item_id') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-field">
                <label>Jumlah Masuk</label>
                <input type="number" min="1" name="jumlah" value="{{ old('jumlah') }}" placeholder="0">
                @error('jumlah') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-field">
                <label>Tanggal</label>
                <input type="date" name="tanggal" value="{{ old('tanggal', now()->format('Y-m-d')) }}">
                @error('tanggal') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-field full">
                <label>Catatan</label>
                <input type="text" name="catatan" value="{{ old('catatan') }}" placeholder="mis. Toko Sinar Jaya, nota 0123 (opsional)">
                @error('catatan') <div class="error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('go.atk-stock-ins.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>

@endsection