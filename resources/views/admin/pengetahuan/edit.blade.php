@extends('layouts.admin')

@section('content')

    <div class="admin-card">

        <div class="admin-card-head">
            <div class="admin-card-head-icon">
                <i class="bi bi-pencil-square"></i>
            </div>
            <div class="flex-grow-1">
                <h2 class="admin-card-title">Edit Pengetahuan</h2>
                <p class="admin-card-desc">Ubah konten "{{ $pengetahuan->judul }}".</p>
            </div>
            <a href="{{ route('admin.pengetahuan.index') }}" class="page-action-btn">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger mx-3 mt-3">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="form-pengetahuan" action="{{ route('admin.pengetahuan.update', $pengetahuan) }}" method="POST" enctype="multipart/form-data" class="p-3">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="judul" class="form-label fw-semibold">Judul</label>
                <input type="text" name="judul" id="judul" class="form-control"
                       value="{{ old('judul', $pengetahuan->judul) }}" required>
            </div>

            {{-- BARU: pilihan kategori --}}
            <div class="mb-3">
                <label for="kategori_id" class="form-label fw-semibold">Kategori</label>
                <select name="kategori_id" id="kategori_id" class="form-select" style="max-width: 320px;" required>
                    <option value="">— Pilih kategori —</option>
                    @foreach ($kategoris as $kategori)
                        <option value="{{ $kategori->id }}"
                            {{ old('kategori_id', $pengetahuan->kategori_id) == $kategori->id ? 'selected' : '' }}>
                            {{ $kategori->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Gambar Saat Ini</label>
                @if($pengetahuan->gambar)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $pengetahuan->gambar) }}" alt="{{ $pengetahuan->judul }}"
                         style="width: 180px; height: 120px; object-fit: cover; border-radius: 10px;">
                </div>
                @else
                <p class="text-muted small mb-2">Belum ada gambar.</p>
                @endif
                <label for="gambar" class="form-label">Ganti/Tambah Gambar (opsional)</label>
                <input type="file" name="gambar" id="gambar" class="form-control" accept="image/*">
                <div class="form-text">Biarkan kosong kalau tidak ingin mengganti gambar. Maksimal 5MB.</div>
            </div>

            <div class="mb-3">
                <label for="order" class="form-label fw-semibold">Urutan Tampil</label>
                <input type="number" name="order" id="order" class="form-control" style="max-width: 160px;"
                       value="{{ old('order', $pengetahuan->order) }}">
            </div>

            {{-- DIUBAH: penjelasan memakai editor teks (sebelumnya textarea biasa) --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">Penjelasan Singkat</label>
                <div class="berita-editor-wrap">
                    <div id="editor-ringkasan"></div>
                </div>
                <input type="hidden" name="ringkasan" id="ringkasan">
                <div class="form-text">
                    Tulis penjelasannya di sini. Gunakan ikon gambar di toolbar untuk menyisipkan foto di tengah tulisan (maksimal 5MB per foto).
                </div>
            </div>

            <button type="submit" class="page-action-btn page-action-btn--primary">
                <i class="bi bi-check-circle"></i> Simpan Perubahan
            </button>
        </form>

    </div>

    {{-- Editor teks (Quill) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css">
    <style>
        .berita-editor-wrap { background: #fff; border-radius: 8px; }
        .berita-editor-wrap .ql-toolbar { border-radius: 8px 8px 0 0; }
        .berita-editor-wrap .ql-container { border-radius: 0 0 8px 8px; font-size: 1rem; }
        .berita-editor-wrap .ql-editor { min-height: 220px; line-height: 1.7; }
        .berita-editor-wrap .ql-editor img { max-width: 100%; height: auto; border-radius: 8px; }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <script>
    (function () {
        const UPLOAD_URL = @json(route('admin.pengetahuan.upload-image'));
        const CSRF = @json(csrf_token());

        const quill = new Quill('#editor-ringkasan', {
            theme: 'snow',
            placeholder: 'Tulis penjelasannya di sini...',
            modules: {
                toolbar: {
                    container: [
                        [{ header: [2, 3, false] }],
                        ['bold', 'italic', 'underline'],
                        [{ list: 'ordered' }, { list: 'bullet' }],
                        ['link', 'image'],
                        ['clean'],
                    ],
                    handlers: { image: imageHandler },
                },
            },
        });

        // Isi yang sudah ada dimuat ke editor (atau isi terakhir kalau form gagal divalidasi)
        const initialHtml = @json(old('ringkasan', $pengetahuan->ringkasan_html));
        if (initialHtml) {
            quill.setContents(quill.clipboard.convert({ html: initialHtml }), 'silent');
        }

        // Unggah foto ke server, lalu sisipkan di posisi kursor
        function imageHandler() {
            const input = document.createElement('input');
            input.type = 'file';
            input.accept = 'image/*';

            input.onchange = async function () {
                const file = input.files[0];
                if (!file) return;

                if (file.size > 5 * 1024 * 1024) {
                    alert('Ukuran foto maksimal 5MB.');
                    return;
                }

                const formData = new FormData();
                formData.append('image', file);

                try {
                    const res = await fetch(UPLOAD_URL, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                        body: formData,
                    });
                    if (!res.ok) throw new Error('Upload gagal');

                    const data = await res.json();
                    const range = quill.getSelection(true);
                    quill.insertEmbed(range.index, 'image', data.url, 'user');
                    quill.setSelection(range.index + 1, 0, 'silent');
                } catch (e) {
                    alert('Gagal mengunggah foto. Coba lagi.');
                }
            };

            input.click();
        }

        // Sebelum dikirim, isi editor dipindahkan ke kolom tersembunyi
        document.getElementById('form-pengetahuan').addEventListener('submit', function () {
            const kosong = quill.getText().trim() === '' && !quill.root.querySelector('img');
            document.getElementById('ringkasan').value = kosong ? '' : quill.root.innerHTML;
        });
    })();
    </script>

@endsection