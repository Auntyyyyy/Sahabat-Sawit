@extends('admin.layouts.personalia')

@section('page-title', $penilaian->exists ? 'Ubah Penilaian' : 'Tambah Penilaian')
@section('page-subtitle', 'Isi target, realisasi, dan skor kinerja karyawan untuk satu bulan.')

@section('content')

    @php
        $fmtAngka = fn ($v) => rtrim(rtrim(number_format((float) $v, 2, '.', ''), '0'), '.');

        $nilaiKaryawan = old('karyawan_id', $penilaian->karyawan_id ?? $karyawanTerpilih);
        $nilaiPeriode = old('periode', $penilaian->exists ? $penilaian->periode->format('Y-m') : $periodeDefault);
        $nilaiTarget = old('target', $penilaian->exists ? $fmtAngka($penilaian->target) : '');
        $nilaiRealisasi = old('realisasi', $penilaian->exists ? $fmtAngka($penilaian->realisasi) : '');
        $nilaiSkor = old('skor', $penilaian->exists ? number_format((float) $penilaian->skor, 1, '.', '') : '');
    @endphp

    <div class="panel">
        <div class="panel-head">
            <h2>{{ $penilaian->exists ? 'Ubah Penilaian Kinerja' : 'Penilaian Kinerja Baru' }}</h2>
            <a href="{{ route('personalia.performance.index', ['periode' => $nilaiPeriode]) }}" class="panel-link">&larr; Kembali ke Performance</a>
        </div>

        <form method="POST" action="{{ $penilaian->exists ? route('personalia.performance.update', $penilaian) : route('personalia.performance.store') }}">
            @csrf
            @if ($penilaian->exists)
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
                    <label for="periode">Bulan penilaian</label>
                    <input type="month" name="periode" id="periode" value="{{ $nilaiPeriode }}" placeholder="YYYY-MM" required>
                    <div class="form-hint">Satu karyawan hanya punya satu penilaian per bulan.</div>
                    @error('periode') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label for="target">Target</label>
                    <input type="number" name="target" id="target" value="{{ $nilaiTarget }}" min="0" step="any" placeholder="Contoh: 100" required>
                    <div class="form-hint">Satuan bebas: unit, rupiah, atau poin KPI.</div>
                    @error('target') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label for="realisasi">Realisasi</label>
                    <input type="number" name="realisasi" id="realisasi" value="{{ $nilaiRealisasi }}" min="0" step="any" placeholder="Contoh: 92" required>
                    <div class="form-hint">Capaian aktual dengan satuan yang sama seperti target.</div>
                    @error('realisasi') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label for="pencapaian_info">Pencapaian (otomatis)</label>
                    <input type="text" id="pencapaian_info" value="-" readonly>
                    <div class="form-hint" id="kategoriInfo">Realisasi dibagi target, dikali 100.</div>
                </div>

                <div class="form-field">
                    <label for="skor">Skor kinerja (1 sampai 5)</label>
                    <input type="number" name="skor" id="skor" value="{{ $nilaiSkor }}" min="1" max="5" step="0.1" placeholder="Contoh: 4.2" required>
                    <div class="form-hint">Diisi oleh penilai. Satu angka desimal, misalnya 4.5.</div>
                    @error('skor') <div class="error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">{{ $penilaian->exists ? 'Simpan Perubahan' : 'Simpan Penilaian' }}</button>
                <a href="{{ route('personalia.performance.index', ['periode' => $nilaiPeriode]) }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>

@endsection

@section('extra-style')
    .form-hint { margin-top: .35rem; font-size: .76rem; color: var(--muted); }
    .form-field input[readonly] { background: #F1F2F4; color: var(--ink); font-weight: 600; cursor: not-allowed; }
@endsection

@section('extra-script')
    <script>
        (function () {
            var target = document.getElementById('target');
            var realisasi = document.getElementById('realisasi');
            var info = document.getElementById('pencapaian_info');
            var kategori = document.getElementById('kategoriInfo');
            var teksAwal = kategori.textContent;

            // Pratinjau saja; angka resmi dihitung ulang di server saat disimpan
            function hitung() {
                var t = parseFloat(target.value);
                var r = parseFloat(realisasi.value);

                if (!(t > 0) || isNaN(r)) {
                    info.value = '-';
                    kategori.textContent = teksAwal;
                    return;
                }

                var p = r / t * 100;
                info.value = p.toFixed(1).replace('.', ',') + '%';

                var label = p >= 90 ? 'Sangat Baik' : (p >= 75 ? 'Baik' : (p >= 60 ? 'Cukup' : 'Perlu Peningkatan'));
                kategori.textContent = 'Kategori: ' + label;
            }

            target.addEventListener('input', hitung);
            realisasi.addEventListener('input', hitung);
            hitung();
        })();
    </script>
@endsection