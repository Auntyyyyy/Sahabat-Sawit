@extends('admin.layouts.personalia')

@section('page-title', $kehadiran->exists ? 'Ubah Kehadiran' : 'Catat Kehadiran')
@section('page-subtitle', 'Isi catatan kehadiran, izin, cuti, atau lembur karyawan.')

@section('content')

    @php
        $fmtWaktu = fn ($v) => $v ? substr($v, 0, 5) : '';
        $fmtAngka = fn ($v) => rtrim(rtrim(number_format((float) $v, 2, '.', ''), '0'), '.');

        $nilaiKaryawan = old('karyawan_id', $kehadiran->karyawan_id ?? $karyawanTerpilih);
        $nilaiTanggal = old('tanggal', $kehadiran->exists ? $kehadiran->tanggal->format('Y-m-d') : $tanggalDefault);
        $nilaiStatus = old('status', $kehadiran->status ?? 'hadir');
        $nilaiMasuk = old('jam_masuk', $fmtWaktu($kehadiran->jam_masuk));
        $nilaiKeluar = old('jam_keluar', $fmtWaktu($kehadiran->jam_keluar));
        $nilaiLembur = old('jam_lembur', $kehadiran->exists ? $fmtAngka($kehadiran->jam_lembur) : '0');
        $nilaiIzin = old('jenis_izin', $kehadiran->jenis_izin);
        $nilaiCuti = old('jenis_cuti', $kehadiran->jenis_cuti);
    @endphp

    <div class="panel">
        <div class="panel-head">
            <h2>{{ $kehadiran->exists ? 'Ubah Catatan Kehadiran' : 'Catatan Kehadiran Baru' }}</h2>
            <a href="{{ route('personalia.absensi.index', ['tanggal' => $nilaiTanggal]) }}" class="panel-link">&larr; Kembali ke Absensi</a>
        </div>

        <form method="POST" action="{{ $kehadiran->exists ? route('personalia.absensi.update', $kehadiran) : route('personalia.absensi.store') }}">
            @csrf
            @if ($kehadiran->exists)
                @method('PUT')
            @endif

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
                    <label for="tanggal">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" value="{{ $nilaiTanggal }}" required>
                    @error('tanggal') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field full">
                    <label for="status">Status kehadiran</label>
                    <select name="status" id="status" required>
                        @foreach ($statusOptions as $kode => $label)
                            <option value="{{ $kode }}" {{ $nilaiStatus === $kode ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('status') <div class="error">{{ $message }}</div> @enderror
                </div>

                {{-- ===== Khusus status Hadir ===== --}}
                <div class="form-field" data-show="hadir">
                    <label for="jam_masuk">Jam masuk</label>
                    <input type="time" name="jam_masuk" id="jam_masuk" value="{{ $nilaiMasuk }}">
                    <div class="form-hint">Jam masuk resmi {{ $jamMasukResmi }}. Menit terlambat dihitung otomatis.</div>
                    @error('jam_masuk') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field" data-show="hadir">
                    <label for="jam_keluar">Jam keluar</label>
                    <input type="time" name="jam_keluar" id="jam_keluar" value="{{ $nilaiKeluar }}">
                    @error('jam_keluar') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field" data-show="hadir">
                    <label for="menit_terlambat_info">Menit terlambat (otomatis)</label>
                    <input type="text" id="menit_terlambat_info" value="{{ (int) ($kehadiran->menit_terlambat ?? 0) }} menit" readonly>
                </div>

                <div class="form-field" data-show="hadir">
                    <label for="jam_lembur">Jam lembur</label>
                    <input type="number" name="jam_lembur" id="jam_lembur" value="{{ $nilaiLembur }}" min="0" max="24" step="0.25">
                    <div class="form-hint">Isi 0 kalau tidak lembur. Pecahan ditulis dengan titik, misalnya 1.5.</div>
                    @error('jam_lembur') <div class="error">{{ $message }}</div> @enderror
                </div>

                {{-- ===== Khusus status Izin ===== --}}
                <div class="form-field full" data-show="izin" hidden>
                    <label for="jenis_izin">Jenis izin</label>
                    <select name="jenis_izin" id="jenis_izin">
                        <option value="">Pilih jenis izin...</option>
                        @foreach ($jenisIzin as $j)
                            <option value="{{ $j }}" {{ $nilaiIzin === $j ? 'selected' : '' }}>{{ $j }}</option>
                        @endforeach
                    </select>
                    @error('jenis_izin') <div class="error">{{ $message }}</div> @enderror
                </div>

                {{-- ===== Khusus status Cuti ===== --}}
                <div class="form-field full" data-show="cuti" hidden>
                    <label for="jenis_cuti">Jenis cuti</label>
                    <select name="jenis_cuti" id="jenis_cuti">
                        <option value="">Pilih jenis cuti...</option>
                        @foreach ($jenisCuti as $j)
                            <option value="{{ $j }}" {{ $nilaiCuti === $j ? 'selected' : '' }}>{{ $j }}</option>
                        @endforeach
                    </select>
                    @error('jenis_cuti') <div class="error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">{{ $kehadiran->exists ? 'Simpan Perubahan' : 'Simpan Catatan' }}</button>
                <a href="{{ route('personalia.absensi.index', ['tanggal' => $nilaiTanggal]) }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>

@endsection

@section('extra-style')
    .form-hint { margin-top: .35rem; font-size: .76rem; color: var(--muted); }
    .form-field input[readonly] { background: #F1F2F4; color: var(--muted); cursor: not-allowed; }
    .form-field[hidden] { display: none; }
@endsection

@section('extra-script')
    <script>
        (function () {
            var resmi = '{{ $jamMasukResmi }}';
            var status = document.getElementById('status');
            var masuk = document.getElementById('jam_masuk');
            var info = document.getElementById('menit_terlambat_info');
            var groups = document.querySelectorAll('[data-show]');

            function toMinutes(t) {
                if (!t) return null;
                var p = t.split(':');
                return parseInt(p[0], 10) * 60 + parseInt(p[1], 10);
            }

            // Hitung ulang pratinjau menit terlambat (angka resmi tetap dihitung server saat disimpan)
            function hitung() {
                var m = toMinutes(masuk.value);
                var r = toMinutes(resmi);
                var telat = (status.value === 'hadir' && m !== null) ? Math.max(0, m - r) : 0;
                info.value = telat + ' menit';
            }

            // Tampilkan hanya kolom yang sesuai status; kolom tersembunyi dinonaktifkan agar tidak ikut terkirim
            function sinkron() {
                groups.forEach(function (g) {
                    var tampil = g.getAttribute('data-show').split(' ').indexOf(status.value) !== -1;
                    g.hidden = !tampil;
                    g.querySelectorAll('input, select').forEach(function (el) {
                        if (el.id !== 'menit_terlambat_info') el.disabled = !tampil;
                    });
                });
                hitung();
            }

            status.addEventListener('change', sinkron);
            masuk.addEventListener('input', hitung);
            sinkron();
        })();
    </script>
@endsection