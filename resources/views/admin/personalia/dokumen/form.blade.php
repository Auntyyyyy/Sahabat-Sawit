@extends('admin.layouts.personalia')

@section('page-title', $dokumen->exists ? 'Ubah Dokumen' : 'Unggah Dokumen')
@section('page-subtitle', 'Dokumen yang diunggah di sini juga bisa dilihat oleh HR.')

@section('content')

    @php
        $nilaiJenis = old('jenis', $jenisAwal);
        $nilaiKaryawan = old('karyawan_id', $dokumen->karyawan_id ?? $karyawanTerpilih);
        $nilaiJudul = old('judul', $dokumen->judul);
        $nilaiKategori = old('kategori', $dokumen->kategori);
        $nilaiKedaluwarsa = old('tanggal_kedaluwarsa', $dokumen->tanggal_kedaluwarsa ? $dokumen->tanggal_kedaluwarsa->format('Y-m-d') : '');
        $nilaiKeterangan = old('keterangan', $dokumen->keterangan);
    @endphp

    <div class="panel">
        <div class="panel-head">
            <h2>{{ $dokumen->exists ? 'Ubah Dokumen' : 'Dokumen Baru' }}</h2>
            <a href="{{ route('personalia.dokumen.index') }}" class="panel-link">&larr; Kembali ke Dokumen</a>
        </div>

        <form method="POST" enctype="multipart/form-data"
              action="{{ $dokumen->exists ? route('personalia.dokumen.update', $dokumen) : route('personalia.dokumen.store') }}">
            @csrf
            @if ($dokumen->exists)
                @method('PUT')
            @endif

            <div class="form-grid">
                {{-- ===== Jenis dokumen ===== --}}
                <div class="form-field full">
                    <label>Jenis dokumen</label>
                    <div class="pilihan-jenis">
                        <label class="pilihan" for="jenis_perusahaan">
                            <input type="radio" name="jenis" id="jenis_perusahaan" value="perusahaan" {{ $nilaiJenis === 'perusahaan' ? 'checked' : '' }}>
                            <span>
                                <strong>Dokumen perusahaan</strong>
                                <small>SOP, kebijakan, formulir, dan sejenisnya</small>
                            </span>
                        </label>
                        <label class="pilihan" for="jenis_karyawan">
                            <input type="radio" name="jenis" id="jenis_karyawan" value="karyawan" {{ $nilaiJenis === 'karyawan' ? 'checked' : '' }}>
                            <span>
                                <strong>Dokumen karyawan</strong>
                                <small>KTP, ijazah, sertifikat, surat, dan sejenisnya</small>
                            </span>
                        </label>
                    </div>
                    @error('jenis') <div class="error">{{ $message }}</div> @enderror
                </div>

                {{-- ===== Pemilik (hanya untuk dokumen karyawan) ===== --}}
                <div class="form-field full" id="blokKaryawan" hidden>
                    <label for="karyawan_id">Karyawan pemilik dokumen</label>
                    <select name="karyawan_id" id="karyawan_id">
                        <option value="">Pilih karyawan...</option>
                        @foreach ($karyawans as $k)
                            <option value="{{ $k->id }}" {{ (string) $nilaiKaryawan === (string) $k->id ? 'selected' : '' }}>
                                {{ $k->name }} &mdash; {{ $k->division }}
                            </option>
                        @endforeach
                    </select>
                    @error('karyawan_id') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label for="judul">Judul dokumen</label>
                    <input type="text" name="judul" id="judul" value="{{ $nilaiJudul }}" maxlength="255" placeholder="Contoh: SOP Pengajuan Cuti" required>
                    @error('judul') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label for="kategori">Kategori</label>
                    <select name="kategori" id="kategori" required data-awal="{{ $nilaiKategori }}"></select>
                    @error('kategori') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label for="tanggal_kedaluwarsa">Tanggal kedaluwarsa</label>
                    <input type="date" name="tanggal_kedaluwarsa" id="tanggal_kedaluwarsa" value="{{ $nilaiKedaluwarsa }}">
                    <div class="form-hint">Opsional. Isi kalau dokumen punya masa berlaku. Dokumen yang hampir berakhir ditandai di daftar.</div>
                    @error('tanggal_kedaluwarsa') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label for="berkas">{{ $dokumen->exists ? 'Ganti file (opsional)' : 'File dokumen' }}</label>
                    <input type="file" name="berkas" id="berkas" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png" {{ $dokumen->exists ? '' : 'required' }}>
                    <div class="form-hint">
                        PDF, Word, Excel, JPG, atau PNG. Maksimal 10 MB.
                        @if ($dokumen->exists)
                            File saat ini: <strong>{{ $dokumen->nama_asli }}</strong> ({{ $dokumen->ukuran_terbaca }}).
                        @endif
                    </div>
                    @error('berkas') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field full">
                    <label for="keterangan">Keterangan</label>
                    <textarea name="keterangan" id="keterangan" rows="3" maxlength="1000" placeholder="Opsional">{{ $nilaiKeterangan }}</textarea>
                    @error('keterangan') <div class="error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">{{ $dokumen->exists ? 'Simpan Perubahan' : 'Unggah Dokumen' }}</button>
                <a href="{{ route('personalia.dokumen.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>

@endsection

@section('extra-style')
    .form-hint { margin-top: .35rem; font-size: .76rem; color: var(--muted); }
    .form-field textarea { resize: vertical; line-height: 1.55; }
    .form-field[hidden] { display: none; }
    .form-field input[type="file"] { padding: .5rem .6rem; }

    /* ===== Pilihan jenis dokumen ===== */
    .pilihan-jenis { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: .75rem; }
    .form-field label.pilihan { display: flex; align-items: flex-start; gap: .7rem; margin: 0; padding: .8rem 1rem; border: 1px solid var(--line); border-radius: 10px; background: #fff; cursor: pointer; font-weight: 500; transition: border-color .15s, background .15s; }
    .form-field label.pilihan:hover { border-color: #C5CAD3; }
    .form-field label.pilihan:has(input:checked) { border-color: var(--blue); background: #E4E9F3; }
    .form-field .pilihan input[type="radio"] { width: 16px; height: 16px; padding: 0; margin-top: .2rem; accent-color: var(--blue); box-shadow: none; flex-shrink: 0; }
    .pilihan strong { display: block; font-size: .88rem; color: var(--navy); }
    .pilihan small { display: block; font-size: .76rem; color: var(--muted); font-weight: 400; margin-top: .15rem; }
@endsection

@section('extra-script')
    <script>
        (function () {
            var kategoriKaryawan = @json($kategoriKaryawan);
            var kategoriPerusahaan = @json($kategoriPerusahaan);

            var radios = document.querySelectorAll('input[name="jenis"]');
            var blokKaryawan = document.getElementById('blokKaryawan');
            var selectKaryawan = document.getElementById('karyawan_id');
            var selectKategori = document.getElementById('kategori');
            var kategoriAwal = selectKategori.getAttribute('data-awal');

            function jenisTerpilih() {
                var dipilih = document.querySelector('input[name="jenis"]:checked');
                return dipilih ? dipilih.value : 'perusahaan';
            }

            // Isi ulang pilihan kategori sesuai jenis dokumen
            function isiKategori(pilihan, pertahankan) {
                selectKategori.innerHTML = '';

                var kosong = document.createElement('option');
                kosong.value = '';
                kosong.textContent = 'Pilih kategori...';
                selectKategori.appendChild(kosong);

                pilihan.forEach(function (nama) {
                    var opsi = document.createElement('option');
                    opsi.value = nama;
                    opsi.textContent = nama;
                    if (nama === pertahankan) opsi.selected = true;
                    selectKategori.appendChild(opsi);
                });
            }

            function sinkron(awal) {
                var jenis = jenisTerpilih();
                var untukKaryawan = jenis === 'karyawan';

                blokKaryawan.hidden = !untukKaryawan;
                selectKaryawan.disabled = !untukKaryawan;
                selectKaryawan.required = untukKaryawan;

                var dulu = awal ? kategoriAwal : selectKategori.value;
                isiKategori(untukKaryawan ? kategoriKaryawan : kategoriPerusahaan, dulu);
            }

            radios.forEach(function (r) {
                r.addEventListener('change', function () { sinkron(false); });
            });

            sinkron(true);
        })();
    </script>
@endsection