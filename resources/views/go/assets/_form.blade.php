@csrf

<div class="row g-4">
    <div class="col-md-6">
        <div class="form-section">
            <p class="form-section-title"><i class="bi bi-info-circle"></i> Informasi Dasar</p>

            <div class="mb-3">
                <label class="form-label">Kode Aset</label>
                <input type="text" name="kode_aset" value="{{ old('kode_aset', $asset->kode_aset) }}"
                       class="form-control admin-input @error('kode_aset') is-invalid @enderror"
                       placeholder="mis. AST-0001">
                @error('kode_aset') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Nama Aset</label>
                <input type="text" name="nama" value="{{ old('nama', $asset->nama) }}"
                       class="form-control admin-input @error('nama') is-invalid @enderror"
                       placeholder="mis. Laptop Dell Latitude 5420">
                @error('nama') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Kategori</label>
                <select name="kategori" class="form-select admin-input @error('kategori') is-invalid @enderror">
                    <option value="">— Pilih kategori —</option>
                    @foreach($kategoriOptions as $opt)
                    <option value="{{ $opt }}" @selected(old('kategori', $asset->kategori) === $opt)>{{ $opt }}</option>
                    @endforeach
                </select>
                @error('kategori') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Lokasi</label>
                <input type="text" name="lokasi" value="{{ old('lokasi', $asset->lokasi) }}"
                       class="form-control admin-input" placeholder="mis. Kantor Medan / Site Rokan Hilir">
            </div>

            <div class="mb-3">
                <label class="form-label">Penanggung Jawab</label>
                <input type="text" name="penanggung_jawab" value="{{ old('penanggung_jawab', $asset->penanggung_jawab) }}"
                       class="form-control admin-input" placeholder="Nama atau divisi pemegang aset">
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-section">
            <p class="form-section-title"><i class="bi bi-clipboard-check"></i> Status & Kondisi</p>

            <div class="mb-3">
                <label class="form-label">Kondisi</label>
                <select name="kondisi" class="form-select admin-input @error('kondisi') is-invalid @enderror">
                    @foreach($kondisiOptions as $opt)
                    <option value="{{ $opt }}" @selected(old('kondisi', $asset->kondisi ?: 'Baik') === $opt)>{{ $opt }}</option>
                    @endforeach
                </select>
                @error('kondisi') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Status Pemakaian</label>
                <select name="status" class="form-select admin-input @error('status') is-invalid @enderror">
                    @foreach($statusOptions as $opt)
                    <option value="{{ $opt }}" @selected(old('status', $asset->status ?: 'Digunakan') === $opt)>{{ $opt }}</option>
                    @endforeach
                </select>
                @error('status') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Tanggal Perolehan</label>
                <input type="date" name="tanggal_perolehan"
                       value="{{ old('tanggal_perolehan', optional($asset->tanggal_perolehan)->format('Y-m-d')) }}"
                       class="form-control admin-input">
            </div>

            <div class="mb-3">
                <label class="form-label">Nilai Perolehan (Rp)</label>
                <input type="number" step="0.01" min="0" name="nilai_perolehan"
                       value="{{ old('nilai_perolehan', $asset->nilai_perolehan) }}"
                       class="form-control admin-input" placeholder="0">
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-section">
            <p class="form-section-title"><i class="bi bi-image"></i> Foto Aset</p>

            <div class="image-upload-box mb-2">
                @if($asset->foto)
                    <div class="image-preview-wrap">
                        <img src="{{ asset('storage/' . $asset->foto) }}" alt="{{ $asset->nama }}">
                    </div>
                @else
                    <label for="foto-input" class="upload-placeholder mb-0 w-100">
                        <i class="bi bi-cloud-upload"></i>
                        <span class="d-block">Klik untuk unggah foto (opsional)</span>
                    </label>
                @endif
            </div>
            <input id="foto-input" type="file" name="foto" accept="image/*" class="form-control admin-input @error('foto') is-invalid @enderror">
            <small class="text-muted">Format JPG/PNG, maksimal 2MB. Upload foto baru untuk mengganti yang lama.</small>
            @error('foto') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="form-section">
            <p class="form-section-title"><i class="bi bi-card-text"></i> Keterangan Tambahan</p>
            <textarea name="keterangan" rows="3" class="form-control admin-input"
                      placeholder="Catatan tambahan, mis. riwayat perbaikan, nomor seri, dsb.">{{ old('keterangan', $asset->keterangan) }}</textarea>
        </div>
    </div>
</div>

<div class="form-actions">
    <a href="{{ route('go.assets.index') }}" class="btn-form-cancel">Batal</a>
    <button type="submit" class="btn-form-submit">
        <i class="bi bi-check-lg"></i> Simpan
    </button>
</div>