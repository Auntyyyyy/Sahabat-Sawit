@extends('admin.layouts.personalia')

@section('page-title', $karyawan->exists ? 'Ubah Karyawan' : 'Tambah Karyawan')
@section('page-subtitle', 'Data karyawan yang disimpan di sini langsung tampil di dashboard HR.')

@section('content')

    @php
        $nilaiNama = old('name', $karyawan->name);
        $nilaiEmail = old('email', $karyawan->email);
        $nilaiTelepon = old('phone', $karyawan->phone);
        $nilaiGender = old('gender', $karyawan->gender);
        $nilaiLahir = old('birth_date', $karyawan->birth_date ? $karyawan->birth_date->format('Y-m-d') : '');
        $nilaiDivisi = old('division', $karyawan->division);
        $nilaiJabatan = old('position', $karyawan->position);
        $nilaiBergabung = old('join_date', $karyawan->join_date ? $karyawan->join_date->format('Y-m-d') : today()->format('Y-m-d'));
        $nilaiStatus = old('status', $karyawan->status ?: 'aktif');
        $nilaiAlamat = old('address', $karyawan->address);
        $fotoSekarang = $karyawan->photo ? asset('storage/' . $karyawan->photo) : null;
        $inisial = strtoupper(mb_substr($nilaiNama ?: '?', 0, 1));
    @endphp

    <div class="panel">
        <div class="panel-head">
            <h2>{{ $karyawan->exists ? 'Ubah Data Karyawan' : 'Karyawan Baru' }}</h2>
            <a href="{{ $karyawan->exists ? route('personalia.karyawan.show', $karyawan) : route('personalia.karyawan.index') }}" class="panel-link">&larr; Kembali</a>
        </div>

        <form method="POST" enctype="multipart/form-data"
              action="{{ $karyawan->exists ? route('personalia.karyawan.update', $karyawan) : route('personalia.karyawan.store') }}">
            @csrf
            @if ($karyawan->exists)
                @method('PUT')
            @endif

            {{-- ===== Foto ===== --}}
            <div class="foto-row">
                <div class="foto-box">
                    <img id="fotoPratinjau" src="{{ $fotoSekarang }}" alt="Foto karyawan" {{ $fotoSekarang ? '' : 'hidden' }}>
                    <span id="fotoInisial" {{ $fotoSekarang ? 'hidden' : '' }}>{{ $inisial }}</span>
                </div>
                <div class="foto-input">
                    <label for="photo">Foto karyawan</label>
                    <input type="file" name="photo" id="photo" accept="image/jpeg,image/png,image/webp">
                    <div class="form-hint">Format JPG, PNG, atau WEBP. Ukuran maksimal 2 MB. Boleh dikosongkan.</div>
                    @if ($fotoSekarang)
                        <label class="check-row" for="hapus_foto">
                            <input type="checkbox" name="hapus_foto" id="hapus_foto" value="1" {{ old('hapus_foto') ? 'checked' : '' }}>
                            <span>Hapus foto saat ini</span>
                        </label>
                    @endif
                    @error('photo') <div class="error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-grid">
                <div class="form-field">
                    <label for="name">Nama lengkap</label>
                    <input type="text" name="name" id="name" value="{{ $nilaiNama }}" maxlength="255" required>
                    @error('name') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" value="{{ $nilaiEmail }}" maxlength="255" required>
                    @error('email') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label for="phone">No. telepon</label>
                    <input type="text" name="phone" id="phone" value="{{ $nilaiTelepon }}" maxlength="20">
                    @error('phone') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label for="gender">Jenis kelamin</label>
                    <select name="gender" id="gender" required>
                        <option value="">Pilih...</option>
                        <option value="L" {{ $nilaiGender === 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ $nilaiGender === 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('gender') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label for="birth_date">Tanggal lahir</label>
                    <input type="date" name="birth_date" id="birth_date" value="{{ $nilaiLahir }}" max="{{ today()->format('Y-m-d') }}">
                    @error('birth_date') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label for="join_date">Tanggal bergabung</label>
                    <input type="date" name="join_date" id="join_date" value="{{ $nilaiBergabung }}" required>
                    @error('join_date') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label for="division">Divisi</label>
                    <input type="text" name="division" id="division" value="{{ $nilaiDivisi }}" list="daftarDivisi" maxlength="100" placeholder="Pilih atau ketik divisi" required>
                    <datalist id="daftarDivisi">
                        @foreach ($divisions as $d)
                            <option value="{{ $d }}"></option>
                        @endforeach
                    </datalist>
                    <div class="form-hint">Pilih dari saran supaya nama divisi sama persis dengan yang sudah ada.</div>
                    @error('division') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label for="position">Jabatan</label>
                    <input type="text" name="position" id="position" value="{{ $nilaiJabatan }}" maxlength="100" required>
                    @error('position') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field full">
                    <label for="status">Status</label>
                    <select name="status" id="status" required>
                        @foreach ($statusOptions as $kode => $label)
                            <option value="{{ $kode }}" {{ $nilaiStatus === $kode ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('status') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field full">
                    <label for="address">Alamat</label>
                    <textarea name="address" id="address" rows="3">{{ $nilaiAlamat }}</textarea>
                    @error('address') <div class="error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">{{ $karyawan->exists ? 'Simpan Perubahan' : 'Simpan Karyawan' }}</button>
                <a href="{{ $karyawan->exists ? route('personalia.karyawan.show', $karyawan) : route('personalia.karyawan.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>

@endsection

@section('extra-style')
    .form-hint { margin-top: .35rem; font-size: .76rem; color: var(--muted); }
    .form-field textarea { resize: vertical; line-height: 1.55; }

    /* ===== Foto ===== */
    .foto-row { display: flex; align-items: center; gap: 1.25rem; padding: 1.5rem 1.5rem 0; flex-wrap: wrap; }
    .foto-box { width: 96px; height: 96px; border-radius: 50%; overflow: hidden; background: #E4E9F3; color: var(--blue); display: flex; align-items: center; justify-content: center; font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 2rem; flex-shrink: 0; border: 1px solid var(--line); }
    .foto-box img { width: 100%; height: 100%; object-fit: cover; }
    .foto-box [hidden] { display: none; }
    .foto-input { flex: 1; min-width: 240px; }
    .foto-input > label { display: block; font-size: .82rem; font-weight: 600; color: var(--navy); margin-bottom: .4rem; }
    .foto-input input[type="file"] { width: 100%; padding: .5rem .6rem; border: 1px solid var(--line); border-radius: 8px; font-size: .85rem; font-family: 'Inter', sans-serif; background: #fff; }
    .foto-input .error { color: var(--red); font-size: .78rem; margin-top: .35rem; }
    .check-row { display: flex; align-items: center; gap: .5rem; margin-top: .6rem; font-size: .84rem; font-weight: 500; color: var(--ink); cursor: pointer; }
    .check-row input[type="checkbox"] { width: 16px; height: 16px; accent-color: var(--red); cursor: pointer; }
@endsection

@section('extra-script')
    <script>
        (function () {
            var input = document.getElementById('photo');
            var gambar = document.getElementById('fotoPratinjau');
            var inisial = document.getElementById('fotoInisial');
            var nama = document.getElementById('name');
            var hapus = document.getElementById('hapus_foto');

            // Pratinjau foto yang baru dipilih
            input.addEventListener('change', function () {
                var berkas = input.files[0];
                if (!berkas) return;
                gambar.src = URL.createObjectURL(berkas);
                gambar.hidden = false;
                inisial.hidden = true;
                if (hapus) hapus.checked = false;
            });

            // Inisial ikut berubah saat nama diketik (hanya tampil kalau belum ada foto)
            nama.addEventListener('input', function () {
                inisial.textContent = (nama.value.trim().charAt(0) || '?').toUpperCase();
            });
        })();
    </script>
@endsection