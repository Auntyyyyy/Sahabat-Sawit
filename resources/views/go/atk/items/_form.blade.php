@csrf
@php $isEdit = $atkItem->exists; @endphp

<div class="form-grid">
    <div class="form-field">
        <label>Kode Barang</label>
        <input type="text" name="kode" value="{{ old('kode', $atkItem->kode) }}" placeholder="mis. ATK-001">
        @error('kode') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="form-field">
        <label>Nama Barang</label>
        <input type="text" name="nama" value="{{ old('nama', $atkItem->nama) }}" placeholder="mis. Kertas HVS A4 80gr">
        @error('nama') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="form-field">
        <label>Kategori</label>
        <select name="kategori">
            <option value="">— Pilih kategori —</option>
            @foreach($kategoriOptions as $opt)
            <option value="{{ $opt }}" @selected(old('kategori', $atkItem->kategori) === $opt)>{{ $opt }}</option>
            @endforeach
        </select>
        @error('kategori') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="form-field">
        <label>Satuan</label>
        <select name="satuan">
            @foreach($satuanOptions as $opt)
            <option value="{{ $opt }}" @selected(old('satuan', $atkItem->satuan) === $opt)>{{ $opt }}</option>
            @endforeach
        </select>
        @error('satuan') <div class="error">{{ $message }}</div> @enderror
    </div>

    @if($isEdit)
    <div class="form-field">
        <label>Stok Saat Ini</label>
        <input type="text" value="{{ $atkItem->stok }} {{ $atkItem->satuan }}" disabled>
        <small style="color: var(--muted); font-size:.78rem; display:block; margin-top:.35rem;">
            Stok berubah lewat menu Barang Masuk dan permintaan yang diserahkan.
        </small>
    </div>
    @else
    <div class="form-field">
        <label>Stok Awal</label>
        <input type="number" min="0" name="stok" value="{{ old('stok', $atkItem->stok) }}">
        <small style="color: var(--muted); font-size:.78rem; display:block; margin-top:.35rem;">
            Jumlah yang ada sekarang. Setelah ini, tambah stok lewat Barang Masuk.
        </small>
        @error('stok') <div class="error">{{ $message }}</div> @enderror
    </div>
    @endif

    <div class="form-field">
        <label>Batas Stok Minimum</label>
        <input type="number" min="0" name="stok_minimum" value="{{ old('stok_minimum', $atkItem->stok_minimum) }}">
        <small style="color: var(--muted); font-size:.78rem; display:block; margin-top:.35rem;">
            Barang ditandai "Menipis" kalau stoknya sama dengan atau di bawah angka ini.
        </small>
        @error('stok_minimum') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="form-field full">
        <label>Keterangan</label>
        <textarea name="keterangan" rows="3" placeholder="Catatan tambahan (opsional)">{{ old('keterangan', $atkItem->keterangan) }}</textarea>
    </div>
</div>

<div class="form-actions">
    <a href="{{ route('go.atk-items.index') }}" class="btn btn-secondary">Batal</a>
    <button type="submit" class="btn btn-primary">Simpan</button>
</div>