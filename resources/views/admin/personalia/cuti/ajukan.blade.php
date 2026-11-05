@extends('admin.layouts.personalia')

@section('page-title', 'Ajukan Cuti')
@section('page-subtitle', 'Cuti sakit dan cuti 1 hari langsung disetujui, selain itu menunggu persetujuan HR.')

@section('content')

    @php
        $nilaiKaryawan = old('karyawan_id', $karyawanTerpilih);
        $nilaiJenis = old('jenis_cuti');
        $nilaiMulai = old('tanggal_mulai', $today->format('Y-m-d'));
        $nilaiSelesai = old('tanggal_selesai', $nilaiMulai);
    @endphp

    <div class="panel">
        <div class="panel-head">
            <h2>Pengajuan Cuti Baru</h2>
            <a href="{{ route('personalia.cuti.index') }}" class="panel-link">&larr; Kembali ke Cuti</a>
        </div>

        <form method="POST" action="{{ route('personalia.cuti.store') }}">
            @csrf

            <div class="form-grid">
                <div class="form-field">
                    <label for="karyawan_id">Karyawan</label>
                    <select name="karyawan_id" id="karyawan_id" required>
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
                    <label for="jenis_cuti">Jenis cuti</label>
                    <select name="jenis_cuti" id="jenis_cuti" required>
                        <option value="">Pilih jenis cuti...</option>
                        @foreach ($jenisCuti as $j)
                            <option value="{{ $j }}" {{ $nilaiJenis === $j ? 'selected' : '' }}>{{ $j }}</option>
                        @endforeach
                    </select>
                    @error('jenis_cuti') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label for="tanggal_mulai">Tanggal mulai</label>
                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ $nilaiMulai }}" required>
                    @error('tanggal_mulai') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label for="tanggal_selesai">Tanggal selesai</label>
                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ $nilaiSelesai }}" required>
                    @error('tanggal_selesai') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field full">
                    <div id="preview" class="preview" hidden></div>
                </div>

                <div class="form-field full">
                    <label for="keterangan">Keterangan (opsional)</label>
                    <textarea name="keterangan" id="keterangan" rows="3" maxlength="500">{{ old('keterangan') }}</textarea>
                    @error('keterangan') <div class="error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Ajukan Cuti</button>
                <a href="{{ route('personalia.cuti.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>

@endsection

@section('extra-style')
    .preview { padding: .8rem 1rem; border-radius: 10px; font-size: .86rem; background: #E4E9F3; color: var(--blue); }
    .preview.otomatis { background: #E4F1EA; color: var(--green); }
    .preview.menunggu { background: #FDF1DF; color: #7A5316; }
    .preview.kosong { background: #FBE7E8; color: var(--red); }
    .preview[hidden] { display: none; }
@endsection

@section('extra-script')
    <script>
        (function () {
            var hariKerja = @json($hariKerja);
            var jenisOtomatis = @json($jenisOtomatis);
            var batasHari = {{ (int) $batasHariOtomatis }};

            var jenis = document.getElementById('jenis_cuti');
            var mulai = document.getElementById('tanggal_mulai');
            var selesai = document.getElementById('tanggal_selesai');
            var preview = document.getElementById('preview');

            function baca(nilai) {
                return nilai ? new Date(nilai + 'T00:00:00') : null;
            }

            // Perkiraan saja; angka resmi dihitung server saat disimpan.
            function hitung() {
                var a = baca(mulai.value);
                var b = baca(selesai.value);

                if (!a || !b || b < a) {
                    preview.hidden = true;
                    return;
                }

                var n = 0;
                for (var d = new Date(a); d <= b; d.setDate(d.getDate() + 1)) {
                    var iso = d.getDay() === 0 ? 7 : d.getDay();
                    if (hariKerja.indexOf(iso) !== -1) n++;
                }

                preview.hidden = false;
                preview.className = 'preview';

                if (n === 0) {
                    preview.classList.add('kosong');
                    preview.textContent = 'Rentang ini tidak memuat hari kerja, jadi tidak bisa diajukan.';
                    return;
                }

                var otomatis = jenisOtomatis.indexOf(jenis.value) !== -1 || n <= batasHari;
                preview.classList.add(otomatis ? 'otomatis' : 'menunggu');
                preview.textContent = n + ' hari kerja. ' + (otomatis
                    ? 'Langsung disetujui dan tercatat di kehadiran.'
                    : 'Menunggu persetujuan HR.');
            }

            mulai.addEventListener('change', function () {
                var a = baca(mulai.value);
                var b = baca(selesai.value);
                if (a && (!b || b < a)) selesai.value = mulai.value;
                hitung();
            });
            selesai.addEventListener('change', hitung);
            jenis.addEventListener('change', hitung);
            hitung();
        })();
    </script>
@endsection