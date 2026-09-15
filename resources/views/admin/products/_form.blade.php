<div class="form-section">
    <h6 class="form-section-title"><i class="bi bi-info-circle"></i> Informasi Utama</h6>

    <div class="row g-3">
        <div class="col-md-8">
            <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control admin-input @error('name') is-invalid @enderror"
                   value="{{ old('name', $product->name ?? '') }}" placeholder="Contoh: Minyak Kelapa Sawit (CPO)" required>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-4">
            <label class="form-label">Kategori <span class="text-danger">*</span></label>
            <select name="category" class="form-select admin-input @error('category') is-invalid @enderror" required>
                <option value="">Pilih kategori</option>
                @foreach(['CPO' => 'CPO (Crude Palm Oil)', 'PKO' => 'PKO (Palm Kernel Oil)', 'Kernel' => 'Inti Sawit (Kernel)', 'Turunan' => 'Produk Turunan'] as $val => $label)
                    <option value="{{ $val }}" {{ old('category', $product->category ?? '') == $val ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-12">
            <label class="form-label">Deskripsi Singkat (tampil di kartu produk)</label>
            <textarea name="description" rows="3" class="form-control admin-input @error('description') is-invalid @enderror"
                      placeholder="Ringkasan singkat produk untuk kartu di halaman katalog">{{ old('description', $product->description ?? '') }}</textarea>
            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-12">
            <label class="form-label">Deskripsi Lengkap (tampil di halaman detail)</label>
            <textarea name="detail" rows="5" class="form-control admin-input @error('detail') is-invalid @enderror"
                      placeholder="Penjelasan lengkap produk untuk halaman detail">{{ old('detail', $product->detail ?? '') }}</textarea>
            @error('detail') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

<div class="form-section">
    <h6 class="form-section-title"><i class="bi bi-list-check"></i> Manfaat & Spesifikasi</h6>

    <div class="row g-3">
        <div class="col-12">
            <label class="form-label">Manfaat Produk</label>
            <textarea name="manfaat" rows="4" class="form-control admin-input @error('manfaat') is-invalid @enderror"
                      placeholder="Satu manfaat per baris">{{ old('manfaat', isset($product) ? implode("\n", $product->manfaat ?? []) : '') }}</textarea>
            <small class="text-muted">Tulis satu poin manfaat per baris.</small>
            @error('manfaat') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-12">
            <label class="form-label">Spesifikasi Produk</label>
            <div id="spesifikasiWrap" class="d-flex flex-column gap-2">
                @php
                    $spekList = old('spesifikasi_key')
                        ? array_combine(old('spesifikasi_key'), old('spesifikasi_value'))
                        : ($product->spesifikasi ?? []);
                    $spekList = $spekList ?: ['' => ''];
                @endphp
                @foreach($spekList as $k => $v)
                <div class="row g-2 spesifikasi-row">
                    <div class="col-5">
                        <input type="text" name="spesifikasi_key[]" class="form-control admin-input" value="{{ $k }}" placeholder="Nama (mis. Kadar FFA)">
                    </div>
                    <div class="col-5">
                        <input type="text" name="spesifikasi_value[]" class="form-control admin-input" value="{{ $v }}" placeholder="Nilai (mis. Maks 4.2%)">
                    </div>
                    <div class="col-2">
                        <button type="button" class="btn btn-outline-danger w-100" onclick="this.closest('.spesifikasi-row').remove()">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
            <button type="button" class="btn btn-outline-secondary btn-sm mt-2" onclick="addSpesifikasiRow()">
                <i class="bi bi-plus-lg"></i> Tambah Spesifikasi
            </button>
        </div>
    </div>
</div>

<div class="form-section">
    <h6 class="form-section-title"><i class="bi bi-box"></i> Stok</h6>

    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Satuan <span class="text-danger">*</span></label>
            <select name="unit" class="form-select admin-input @error('unit') is-invalid @enderror" required>
                @foreach(['kg' => 'per Kg', 'ton' => 'per Ton', 'liter' => 'per Liter'] as $val => $label)
                    <option value="{{ $val }}" {{ old('unit', $product->unit ?? '') == $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @error('unit') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Stok Tersedia</label>
            <input type="number" min="0" name="stock" class="form-control admin-input @error('stock') is-invalid @enderror"
                   value="{{ old('stock', $product->stock ?? '') }}" placeholder="0">
            @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

@php
    $galleryImage2 = isset($product) ? $product->media->firstWhere('order', 1) : null;
    $galleryImage3 = isset($product) ? $product->media->firstWhere('order', 2) : null;
@endphp

<div class="form-section">
    <h6 class="form-section-title"><i class="bi bi-images"></i> Gambar Produk (3 gambar)</h6>

    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Gambar Utama <span class="text-danger">*</span></label>
            <small class="d-block text-muted mb-1">Tampil di daftar produk (halaman Produk)</small>
            <div class="image-upload-box" data-name="image">
                <input type="file" name="image" class="d-none image-input" accept="image/*">
                <div class="upload-placeholder">
                    <i class="bi bi-cloud-arrow-up"></i>
                    <p class="mb-0 fw-semibold small">Klik untuk unggah</p>
                </div>
                <div class="image-preview-wrap d-none">
                    <img class="image-preview" src="{{ isset($product) && $product->image ? asset('storage/'.$product->image) : '' }}" alt="Preview">
                    <button type="button" class="image-remove-btn"><i class="bi bi-x-lg"></i></button>
                </div>
            </div>
            @error('image') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-4">
            <label class="form-label">Gambar Tambahan 1</label>
            <small class="d-block text-muted mb-1">Tampil di halaman detail produk</small>
            <div class="image-upload-box" data-name="image_2">
                <input type="file" name="image_2" class="d-none image-input" accept="image/*">
                <div class="upload-placeholder">
                    <i class="bi bi-cloud-arrow-up"></i>
                    <p class="mb-0 fw-semibold small">Klik untuk unggah</p>
                </div>
                <div class="image-preview-wrap d-none">
                    <img class="image-preview" src="{{ $galleryImage2 ? asset('storage/'.$galleryImage2->file_path) : '' }}" alt="Preview">
                    <button type="button" class="image-remove-btn"><i class="bi bi-x-lg"></i></button>
                </div>
            </div>
            @error('image_2') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-4">
            <label class="form-label">Gambar Tambahan 2</label>
            <small class="d-block text-muted mb-1">Tampil di halaman detail produk</small>
            <div class="image-upload-box" data-name="image_3">
                <input type="file" name="image_3" class="d-none image-input" accept="image/*">
                <div class="upload-placeholder">
                    <i class="bi bi-cloud-arrow-up"></i>
                    <p class="mb-0 fw-semibold small">Klik untuk unggah</p>
                </div>
                <div class="image-preview-wrap d-none">
                    <img class="image-preview" src="{{ $galleryImage3 ? asset('storage/'.$galleryImage3->file_path) : '' }}" alt="Preview">
                    <button type="button" class="image-remove-btn"><i class="bi bi-x-lg"></i></button>
                </div>
            </div>
            @error('image_3') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

<div class="form-actions">
    <a href="{{ route('admin.products.index') }}" class="btn btn-form-cancel">Batal</a>
    <button type="submit" class="btn btn-form-submit">
        <i class="bi bi-check-lg"></i> {{ $submitLabel ?? 'Simpan' }}
    </button>
</div>

@once
    @push('scripts')
    <script>
        document.querySelectorAll('.image-upload-box').forEach(function (box) {
            const input = box.querySelector('.image-input');
            const placeholder = box.querySelector('.upload-placeholder');
            const previewWrap = box.querySelector('.image-preview-wrap');
            const previewImg = box.querySelector('.image-preview');
            const removeBtn = box.querySelector('.image-remove-btn');

            if (previewImg && previewImg.getAttribute('src')) {
                placeholder.classList.add('d-none');
                previewWrap.classList.remove('d-none');
            }

            placeholder.addEventListener('click', () => input.click());

            input.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = function (ev) {
                    previewImg.src = ev.target.result;
                    placeholder.classList.add('d-none');
                    previewWrap.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            });

            removeBtn.addEventListener('click', function () {
                input.value = '';
                previewImg.src = '';
                placeholder.classList.remove('d-none');
                previewWrap.classList.add('d-none');
            });
        });

        function addSpesifikasiRow() {
            const wrap = document.getElementById('spesifikasiWrap');
            const row = document.createElement('div');
            row.className = 'row g-2 spesifikasi-row';
            row.innerHTML = `
                <div class="col-5">
                    <input type="text" name="spesifikasi_key[]" class="form-control admin-input" placeholder="Nama (mis. Kadar FFA)">
                </div>
                <div class="col-5">
                    <input type="text" name="spesifikasi_value[]" class="form-control admin-input" placeholder="Nilai (mis. Maks 4.2%)">
                </div>
                <div class="col-2">
                    <button type="button" class="btn btn-outline-danger w-100" onclick="this.closest('.spesifikasi-row').remove()">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            `;
            wrap.appendChild(row);
        }
    </script>
    @endpush
@endonce