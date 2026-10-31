@extends('admin.layouts.personalia')

@section('page-title', $lowongan->exists ? 'Ubah Lowongan' : 'Tambah Lowongan')
@section('page-subtitle', 'Lowongan yang aktif akan tampil di halaman Karir website.')

@section('content')

    @php
        $nilaiJudul = old('judul', $lowongan->judul);
        $nilaiLokasi = old('lokasi', $lowongan->lokasi);
        $nilaiDeskripsi = old('deskripsi', $lowongan->deskripsi);
        $nilaiBatas = old('batas_waktu', $lowongan->batas_waktu ? $lowongan->batas_waktu->format('Y-m-d') : '');
        $nilaiOrder = old('order', $lowongan->exists ? $lowongan->order : 0);
        // Lowongan baru dicentang tayang secara default; setelah gagal validasi ikuti isian sebelumnya
        $nilaiAktif = old('_token') ? (bool) old('is_active') : ($lowongan->exists ? $lowongan->is_active : true);
    @endphp

    <div class="panel">
        <div class="panel-head">
            <h2>{{ $lowongan->exists ? 'Ubah Lowongan' : 'Lowongan Baru' }}</h2>
            <a href="{{ route('personalia.rekrutmen.index') }}" class="panel-link">&larr; Kembali ke Rekrutmen</a>
        </div>

        <form method="POST" action="{{ $lowongan->exists ? route('personalia.rekrutmen.update', $lowongan) : route('personalia.rekrutmen.store') }}">
            @csrf
            @if ($lowongan->exists)
                @method('PUT')
            @endif

            <div class="form-grid">
                <div class="form-field full">
                    <label for="judul">Judul lowongan</label>
                    <input type="text" name="judul" id="judul" value="{{ $nilaiJudul }}" placeholder="Contoh: Staf Administrasi" maxlength="255" required>
                    @error('judul') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label for="lokasi">Lokasi penempatan</label>
                    <input type="text" name="lokasi" id="lokasi" value="{{ $nilaiLokasi }}" placeholder="Contoh: Kantor Pusat, Medan" maxlength="255" required>
                    @error('lokasi') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label for="batas_waktu">Batas lamaran</label>
                    <input type="date" name="batas_waktu" id="batas_waktu" value="{{ $nilaiBatas }}" @unless ($lowongan->exists) min="{{ today()->format('Y-m-d') }}" @endunless>
                    <div class="form-hint">Kosongkan kalau tidak ada batas. Setelah tanggal ini lowongan otomatis hilang dari website.</div>
                    @error('batas_waktu') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field full">
                    <label for="deskripsi">Kualifikasi dan deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" rows="9" placeholder="Minimal pendidikan D3 di bidang terkait.&#10;Memiliki pengalaman kerja minimal 1 tahun.&#10;Menguasai Microsoft Office." required>{{ $nilaiDeskripsi }}</textarea>
                    <div class="form-hint">Tulis <strong>satu poin per baris</strong>. Di website, tiap baris tampil sebagai satu butir daftar. <span id="hitungPoin"></span></div>
                    @error('deskripsi') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label for="order">Urutan tampil</label>
                    <input type="number" name="order" id="order" value="{{ $nilaiOrder }}" min="0" max="9999" step="1">
                    <div class="form-hint">Angka kecil tampil lebih dulu. Kalau sama, yang terbaru di atas.</div>
                    @error('order') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label>Status tayang</label>
                    <label class="switch-row" for="is_active">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ $nilaiAktif ? 'checked' : '' }}>
                        <span>Tayangkan di halaman Karir</span>
                    </label>
                    <div class="form-hint">Hilangkan centang untuk menyimpan sebagai draf (tidak tampil di website).</div>
                    @error('is_active') <div class="error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">{{ $lowongan->exists ? 'Simpan Perubahan' : 'Simpan Lowongan' }}</button>
                <a href="{{ route('personalia.rekrutmen.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>

@endsection

@section('extra-style')
    .form-hint { margin-top: .35rem; font-size: .76rem; color: var(--muted); }
    .form-field textarea { resize: vertical; line-height: 1.55; }
    .form-field label.switch-row { display: flex; align-items: center; gap: .6rem; padding: .62rem .85rem; border: 1px solid var(--line); border-radius: 8px; background: #fff; font-size: .88rem; font-weight: 500; color: var(--ink); cursor: pointer; margin-bottom: 0; }
    .form-field .switch-row input[type="checkbox"] { width: 18px; height: 18px; padding: 0; border: 0; box-shadow: none; accent-color: var(--blue); cursor: pointer; flex-shrink: 0; }
@endsection

@section('extra-script')
    <script>
        (function () {
            var area = document.getElementById('deskripsi');
            var info = document.getElementById('hitungPoin');

            function hitung() {
                var baris = area.value.split(/\r?\n/).filter(function (b) { return b.trim() !== ''; });
                info.textContent = baris.length > 0 ? 'Terdeteksi ' + baris.length + ' poin.' : '';
            }

            area.addEventListener('input', hitung);
            hitung();
        })();
    </script>
@endsection