@csrf

<div class="form-grid">
    <div class="form-field">
        <label>Kode Fasilitas</label>
        <input type="text" name="kode_fasilitas" value="{{ old('kode_fasilitas', $facility->kode_fasilitas) }}" placeholder="mis. FSL-0001">
        @error('kode_fasilitas') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="form-field">
        <label>Nama Fasilitas</label>
        <input type="text" name="nama" value="{{ old('nama', $facility->nama) }}" placeholder="mis. Ruang Rapat Lantai 2">
        @error('nama') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="form-field">
        <label>Kategori</label>
        <select name="kategori">
            <option value="">— Pilih kategori —</option>
            @foreach($kategoriOptions as $opt)
            <option value="{{ $opt }}" @selected(old('kategori', $facility->kategori) === $opt)>{{ $opt }}</option>
            @endforeach
        </select>
        @error('kategori') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="form-field">
        <label>Lokasi</label>
        <input type="text" name="lokasi" value="{{ old('lokasi', $facility->lokasi) }}" placeholder="mis. Kantor Pusat Lt. 2 / Site Rokan Hilir">
        @error('lokasi') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="form-field">
        <label>Penanggung Jawab / Pengguna</label>
        <input type="text" name="penanggung_jawab" value="{{ old('penanggung_jawab', $facility->penanggung_jawab) }}" placeholder="Nama atau divisi penanggung jawab">
        @error('penanggung_jawab') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="form-field">
        <label>Kondisi</label>
        <select name="kondisi">
            @foreach($kondisiOptions as $opt)
            <option value="{{ $opt }}" @selected(old('kondisi', $facility->kondisi ?: 'Baik') === $opt)>{{ $opt }}</option>
            @endforeach
        </select>
        @error('kondisi') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="form-field">
        <label>Kapasitas</label>
        <input type="number" min="0" name="kapasitas" value="{{ old('kapasitas', $facility->kapasitas) }}" placeholder="mis. 20 (orang / penumpang / unit)">
        @error('kapasitas') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="form-field">
        <label>Tanggal Perawatan Terakhir</label>
        <input type="date" name="tanggal_perawatan_terakhir" value="{{ old('tanggal_perawatan_terakhir', optional($facility->tanggal_perawatan_terakhir)->format('Y-m-d')) }}">
        @error('tanggal_perawatan_terakhir') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="form-field full">
        <label>Foto Fasilitas</label>
        @if($facility->foto)
            <img src="{{ asset('storage/' . $facility->foto) }}" alt="{{ $facility->nama }}" style="width:100%; max-width:220px; border-radius:8px; border:1px solid var(--line); margin-bottom:.6rem; display:block;">
        @endif
        <input type="file" name="foto" accept="image/*">
        <small style="color: var(--muted); font-size:.78rem; display:block; margin-top:.35rem;">
            Format JPG/PNG, maksimal 2MB. Upload foto baru untuk mengganti yang lama.
        </small>
        @error('foto') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="form-field full">
        <label>Keterangan Tambahan</label>
        <textarea name="keterangan" rows="3" placeholder="Catatan tambahan, mis. perlengkapan yang tersedia, aturan pemakaian, dsb.">{{ old('keterangan', $facility->keterangan) }}</textarea>
        @error('keterangan') <div class="error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="form-actions">
    <a href="{{ route('go.facilities.index') }}" class="btn btn-secondary">Batal</a>
    <button type="submit" class="btn btn-primary">Simpan</button>
</div>