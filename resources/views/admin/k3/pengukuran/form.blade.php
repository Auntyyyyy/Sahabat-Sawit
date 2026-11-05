@extends('admin.layouts.k3')

@php
    // Form ini dipakai untuk Tambah dan Edit. Bedanya: data sudah ada di database atau belum.
    $isEdit = $pengukuran->exists;

    // Angka dari database selalu bertitik desimal (contoh 85.000). Nol di belakang dibuang agar rapi di kolom isian.
    $angka = function ($v) {
        if ($v === null || $v === '') { return ''; }
        $s = (string) $v;
        return str_contains($s, '.') ? rtrim(rtrim($s, '0'), '.') : $s;
    };
@endphp

@section('title', $isEdit ? 'Edit Pengukuran' : 'Tambah Pengukuran')
@section('page_title', $isEdit ? 'Edit Hasil Pengukuran' : 'Tambah Hasil Pengukuran')

@section('content')

    <div class="k3-card" style="max-width: 820px;">

        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
            <div>
                <h2 class="k3-card-title" style="font-size:1.25rem">{{ $isEdit ? 'Edit Pengukuran' : 'Tambah Pengukuran' }}</h2>
                <p class="k3-card-sub mb-0">Catat satu hasil pengukuran pada satu titik.</p>
            </div>
            <a href="{{ route('k3.pengukuran.index') }}" class="k3-btn k3-btn-outline">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ $isEdit ? route('k3.pengukuran.update', $pengukuran) : route('k3.pengukuran.store') }}" method="POST">
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="k3-label" for="tanggal">Tanggal Pengukuran</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control"
                           value="{{ old('tanggal', $pengukuran->tanggal?->format('Y-m-d')) }}" required>
                </div>

                <div class="col-md-8">
                    <label class="k3-label" for="titik">Titik / Lokasi Pengukuran</label>
                    <input type="text" name="titik" id="titik" class="form-control"
                           value="{{ old('titik', $pengukuran->titik) }}"
                           placeholder="Contoh: Ruang mesin, sisi barat" required>
                    <div class="form-text">Gunakan penulisan titik yang sama setiap kali, supaya jumlah titik di dashboard akurat.</div>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="k3-label" for="parameter">Parameter</label>
                    <input type="text" name="parameter" id="parameter" class="form-control" list="daftarParameter"
                           value="{{ old('parameter', $pengukuran->parameter) }}"
                           placeholder="Pilih atau ketik sendiri" autocomplete="off" required>
                    <datalist id="daftarParameter">
                        @foreach (\App\Models\K3Pengukuran::PARAMETER_SARAN as $nama => $satuan)
                            <option value="{{ $nama }}">
                        @endforeach
                    </datalist>
                </div>

                <div class="col-md-3">
                    <label class="k3-label" for="nilai">Hasil Ukur</label>
                    <input type="number" step="any" name="nilai" id="nilai" class="form-control"
                           value="{{ old('nilai', $angka($pengukuran->nilai)) }}" required>
                </div>

                <div class="col-md-3">
                    <label class="k3-label" for="satuan">Satuan</label>
                    <input type="text" name="satuan" id="satuan" class="form-control" maxlength="20"
                           value="{{ old('satuan', $pengukuran->satuan) }}" placeholder="dB, lux, °C" required>
                    <div class="form-text">Terisi otomatis untuk parameter yang umum.</div>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="k3-label" for="nilai_batas">Nilai Batas (opsional)</label>
                    <input type="number" step="any" name="nilai_batas" id="nilai_batas" class="form-control"
                           value="{{ old('nilai_batas', $angka($pengukuran->nilai_batas)) }}">
                    <div class="form-text">Ikuti standar atau peraturan yang dipakai perusahaan. Kosongkan kalau tidak ada.</div>
                </div>

                <div class="col-md-8">
                    <label class="k3-label" for="arah_batas">Arah Batas</label>
                    <select name="arah_batas" id="arah_batas" class="form-select" required>
                        @foreach (\App\Models\K3Pengukuran::ARAH_BATAS as $kunci => $label)
                            <option value="{{ $kunci }}" @selected(old('arah_batas', $pengukuran->arah_batas) === $kunci)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <div class="form-text">
                        Kebisingan dan debu biasanya memakai batas maksimum. Pencahayaan biasanya memakai batas minimum.
                        Penilaian Memenuhi atau Tidak memenuhi dihitung otomatis dari isian ini.
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="k3-label" for="keterangan">Keterangan (opsional)</label>
                <textarea name="keterangan" id="keterangan" rows="3" maxlength="1000" class="form-control"
                          placeholder="Contoh: alat ukur dan kondisi saat pengukuran">{{ old('keterangan', $pengukuran->keterangan) }}</textarea>
            </div>

            <button type="submit" class="k3-btn k3-btn-red">
                <i class="bi bi-check-circle"></i> {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Pengukuran' }}
            </button>
        </form>

    </div>

@endsection

@push('scripts')
<script>
    // Mengisi satuan otomatis saat parameter umum dipilih, hanya kalau kolom satuan masih kosong.
    // Kalau script ini tidak jalan, satuan tinggal diisi manual dan form tetap berfungsi.
    (function () {
        var saran = @json(\App\Models\K3Pengukuran::PARAMETER_SARAN);
        var parameter = document.getElementById('parameter');
        var satuan = document.getElementById('satuan');
        if (! parameter || ! satuan) { return; }

        function isiSatuan() {
            var nama = parameter.value.trim();
            if (satuan.value.trim() === '' && Object.prototype.hasOwnProperty.call(saran, nama)) {
                satuan.value = saran[nama];
            }
        }

        parameter.addEventListener('change', isiSatuan);
        parameter.addEventListener('blur', isiSatuan);
    })();
</script>
@endpush