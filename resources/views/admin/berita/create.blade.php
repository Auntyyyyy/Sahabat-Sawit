@extends('layouts.admin')

@section('content')

    <div class="admin-card">

        <div class="admin-card-head">
            <div class="admin-card-head-icon">
                <i class="bi bi-plus-circle"></i>
            </div>
            <div class="flex-grow-1">
                <h2 class="admin-card-title">Tambah Berita</h2>
                <p class="admin-card-desc">Isi detail berita/kegiatan yang akan ditampilkan di halaman Media.</p>
            </div>
            <a href="{{ route('admin.berita.index') }}" class="page-action-btn">
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

        <form id="form-berita" action="{{ route('admin.berita.store') }}" method="POST" enctype="multipart/form-data" class="p-3">
            @csrf

            <div class="mb-3">
                <label for="judul" class="form-label fw-semibold">Judul Berita</label>
                <input type="text" name="judul" id="judul" class="form-control"
                       value="{{ old('judul') }}" placeholder="Contoh: Kunjungan Kerja Dinas Perkebunan Riau" required>
            </div>

            <div class="mb-3">
                <label for="tanggal" class="form-label fw-semibold">Tanggal Kegiatan</label>
                <input type="date" name="tanggal" id="tanggal" class="form-control"
                       value="{{ old('tanggal', date('Y-m-d')) }}" required>
            </div>

            <div class="mb-3">
                <label for="gambar" class="form-label fw-semibold">Gambar</label>
                <input type="file" name="gambar" id="gambar" class="form-control" accept="image/*" required>
                <div class="form-text">Format JPG/PNG, maksimal 5MB. Ini foto utama berita.</div>
            </div>

            <div class="mb-3">
                <label for="deskripsi_singkat" class="form-label fw-semibold">Penjelasan Singkat Kegiatan</label>
                <textarea name="deskripsi_singkat" id="deskripsi_singkat" rows="4" class="form-control"
                          placeholder="Ceritakan singkat kegiatannya di sini...">{{ old('deskripsi_singkat') }}</textarea>
                <div class="form-text">Tampil di kartu berita dan di bagian atas artikel.</div>
            </div>

            {{-- BARU: label kecil (contoh: MoU) --}}
            <div class="mb-3">
                <label for="tag" class="form-label fw-semibold">Label (opsional)</label>
                <input type="text" name="tag" id="tag" class="form-control" style="max-width: 280px;"
                       value="{{ old('tag') }}" maxlength="50" placeholder="Contoh: MoU">
                <div class="form-text">Label kecil yang tampil di bawah ringkasan. Boleh dikosongkan.</div>
            </div>

            {{-- BARU: isi lengkap berita dengan editor teks --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">Isi Berita Lengkap (opsional)</label>
                <div class="berita-editor-wrap">
                    <div id="editor-isi"></div>
                </div>
                <input type="hidden" name="isi" id="isi">
                <div class="form-text">
                    Tulis isi berita di sini. Gunakan ikon gambar di toolbar untuk menyisipkan foto di tengah tulisan (maksimal 5MB per foto).
                </div>
            </div>

            <button type="submit" class="page-action-btn page-action-btn--primary">
                <i class="bi bi-check-circle"></i> Simpan Berita
            </button>
        </form>

    </div>

    {{-- Editor teks (Quill) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css">
    <style>
        .berita-editor-wrap { background: #fff; border-radius: 8px; }
        .berita-editor-wrap .ql-toolbar { border-radius: 8px 8px 0 0; }
        .berita-editor-wrap .ql-container { border-radius: 0 0 8px 8px; font-size: 1rem; }
        .berita-editor-wrap .ql-editor { min-height: 320px; line-height: 1.7; }
        .berita-editor-wrap .ql-editor img { max-width: 100%; height: auto; border-radius: 8px; }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <script>
    (function () {
        const UPLOAD_URL = @json(route('admin.berita.upload-image'));
        const CSRF = @json(csrf_token());

        const quill = new Quill('#editor-isi', {
            theme: 'snow',
            placeholder: 'Tulis isi berita di sini...',
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

        // Isi sebelumnya dikembalikan kalau form gagal divalidasi
        const oldIsi = @json(old('isi'));
        if (oldIsi) {
            quill.setContents(quill.clipboard.convert({ html: oldIsi }), 'silent');
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
        document.getElementById('form-berita').addEventListener('submit', function () {
            const html = quill.root.innerHTML;
            document.getElementById('isi').value = quill.getText().trim() === '' && !quill.root.querySelector('img') ? '' : html;
        });
    })();
    </script>

@endsection