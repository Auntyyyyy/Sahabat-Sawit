@csrf

<div class="form-grid">
    <div class="form-field">
        <label>Kode Aset</label>
        <input type="text" name="kode_aset" value="{{ old('kode_aset', $asset->kode_aset) }}" placeholder="mis. AST-0001">
        @error('kode_aset') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="form-field">
        <label>Nama Aset</label>
        <input type="text" name="nama" value="{{ old('nama', $asset->nama) }}" placeholder="mis. Laptop Dell Latitude 5420">
        @error('nama') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="form-field">
        <label>Kategori</label>
        <select name="kategori">
            <option value="">— Pilih kategori —</option>
            @foreach($kategoriOptions as $opt)
            <option value="{{ $opt }}" @selected(old('kategori', $asset->kategori) === $opt)>{{ $opt }}</option>
            @endforeach
        </select>
        @error('kategori') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="form-field">
        <label>Lokasi</label>
        <input type="text" name="lokasi" value="{{ old('lokasi', $asset->lokasi) }}" placeholder="mis. Kantor Medan / Site Rokan Hilir">
    </div>

    <div class="form-field">
        <label>Penanggung Jawab</label>
        <input type="text" name="penanggung_jawab" value="{{ old('penanggung_jawab', $asset->penanggung_jawab) }}" placeholder="Nama atau divisi pemegang aset">
    </div>

    <div class="form-field">
        <label>Kondisi</label>
        <select name="kondisi">
            @foreach($kondisiOptions as $opt)
            <option value="{{ $opt }}" @selected(old('kondisi', $asset->kondisi ?: 'Baik') === $opt)>{{ $opt }}</option>
            @endforeach
        </select>
        @error('kondisi') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="form-field">
        <label>Status Pemakaian</label>
        <select name="status">
            @foreach($statusOptions as $opt)
            <option value="{{ $opt }}" @selected(old('status', $asset->status ?: 'Digunakan') === $opt)>{{ $opt }}</option>
            @endforeach
        </select>
        @error('status') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="form-field">
        <label>Tanggal Perolehan</label>
        <input type="date" name="tanggal_perolehan" value="{{ old('tanggal_perolehan', optional($asset->tanggal_perolehan)->format('Y-m-d')) }}">
    </div>

    <div class="form-field">
        <label>Nilai Perolehan (Rp)</label>
        <input type="number" step="0.01" min="0" name="nilai_perolehan" value="{{ old('nilai_perolehan', $asset->nilai_perolehan) }}" placeholder="0">
    </div>

    <div class="form-field">
        <label>Foto Aset</label>
        @if($asset->foto)
            <img src="{{ asset('storage/' . $asset->foto) }}" alt="{{ $asset->nama }}" style="width:100%; max-width:220px; border-radius:8px; border:1px solid var(--line); margin-bottom:.6rem; display:block;">
        @endif
        <input type="file" name="foto" accept="image/*">
        <small style="color: var(--muted); font-size:.78rem; display:block; margin-top:.35rem;">
            Format JPG/PNG, maksimal 2MB. Upload foto baru untuk mengganti yang lama.
        </small>
        @error('foto') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="form-field full">
        <label>Keterangan Tambahan</label>
        <textarea name="keterangan" rows="3" placeholder="Catatan tambahan, mis. riwayat perbaikan, nomor seri, dsb.">{{ old('keterangan', $asset->keterangan) }}</textarea>
    </div>
</div>

<div class="form-actions">
    <a href="{{ route('go.assets.index') }}" class="btn btn-secondary">Batal</a>
    <button type="submit" class="btn btn-primary">Simpan</button>
</div>