@extends('admin.layouts.k3')

@php
    // Form ini dipakai untuk Tambah dan Edit. Bedanya: data sudah ada di database atau belum.
    $isEdit = $peralatan->exists;
@endphp

@section('title', $isEdit ? 'Edit Peralatan' : 'Tambah Peralatan')
@section('page_title', $isEdit ? 'Edit Peralatan' : 'Tambah Peralatan')

@section('content')

    <div class="k3-card" style="max-width: 860px;">

        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
            <div>
                <h2 class="k3-card-title" style="font-size:1.25rem">{{ $isEdit ? 'Edit Peralatan' : 'Tambah Peralatan' }}</h2>
                <p class="k3-card-sub mb-0">Pilih kategori dulu, isian di bawahnya menyesuaikan.</p>
            </div>
            <a href="{{ route('k3.peralatan.index') }}" class="k3-btn k3-btn-outline">
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

        <form action="{{ $isEdit ? route('k3.peralatan.update', $peralatan) : route('k3.peralatan.store') }}" method="POST">
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif

            <div class="mb-3">
                <label class="k3-label" for="kategori">Kategori</label>
                <select name="kategori" id="kategori" class="form-select" style="max-width: 360px;" required>
                    @foreach (\App\Models\K3Peralatan::KATEGORI as $kunci => $label)
                        <option value="{{ $kunci }}" @selected(old('kategori', $peralatan->kategori) === $kunci)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-8">
                    <label class="k3-label" for="nama">Nama</label>
                    <input type="text" name="nama" id="nama" class="form-control"
                           value="{{ old('nama', $peralatan->nama) }}"
                           placeholder="Contoh: Helm safety / Kotak P3K Kantor">
                    <div class="form-text" id="bantuanNama">Wajib untuk APD dan P3K. Untuk APAR boleh dikosongkan, nama disusun otomatis dari jenis media dan ukuran.</div>
                </div>

                <div class="col-md-4">
                    <label class="k3-label" for="kode">Kode / Nomor Seri (opsional)</label>
                    <input type="text" name="kode" id="kode" class="form-control"
                           value="{{ old('kode', $peralatan->kode) }}" placeholder="Contoh: APAR-001">
                    <div class="form-text">Tidak boleh kembar dalam satu kategori.</div>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-8">
                    <label class="k3-label" for="lokasi">Lokasi</label>
                    <input type="text" name="lokasi" id="lokasi" class="form-control"
                           value="{{ old('lokasi', $peralatan->lokasi) }}" placeholder="Contoh: Pabrik, area boiler">
                    <div class="form-text" id="bantuanLokasi">Wajib untuk APAR dan P3K. Lokasi P3K dihitung sebagai area di dashboard.</div>
                </div>

                <div class="col-md-4" id="blokJumlah">
                    <label class="k3-label" for="jumlah" id="labelJumlah">Jumlah</label>
                    <input type="number" name="jumlah" id="jumlah" class="form-control" min="0"
                           value="{{ old('jumlah', $peralatan->jumlah) }}">
                    <div class="form-text" id="bantuanJumlah">APD: jumlah stok. P3K: jumlah item standar di dalam kotak.</div>
                </div>
            </div>

            {{-- Khusus APAR --}}
            <div id="blokApar">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="k3-label" for="jenis_media">Jenis Media APAR</label>
                        <input type="text" name="jenis_media" id="jenis_media" class="form-control" list="daftarMedia"
                               value="{{ old('jenis_media', $peralatan->jenis_media) }}" placeholder="Pilih atau ketik sendiri">
                        <datalist id="daftarMedia">
                            @foreach (\App\Models\K3Peralatan::MEDIA_APAR as $media)
                                <option value="{{ $media }}">
                            @endforeach
                        </datalist>
                    </div>

                    <div class="col-md-6">
                        <label class="k3-label" for="tanggal_isi_terakhir">Tanggal Isi Ulang Terakhir</label>
                        <input type="date" name="tanggal_isi_terakhir" id="tanggal_isi_terakhir" class="form-control"
                               value="{{ old('tanggal_isi_terakhir', $peralatan->tanggal_isi_terakhir?->format('Y-m-d')) }}">
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="k3-label" for="ukuran">Ukuran (opsional)</label>
                    <input type="text" name="ukuran" id="ukuran" class="form-control"
                           value="{{ old('ukuran', $peralatan->ukuran) }}" placeholder="Contoh: 6 kg, atau ukuran sepatu 42">
                </div>

                <div class="col-md-6">
                    <label class="k3-label" for="tanggal_kedaluwarsa">Tanggal Kedaluwarsa</label>
                    <input type="date" name="tanggal_kedaluwarsa" id="tanggal_kedaluwarsa" class="form-control"
                           value="{{ old('tanggal_kedaluwarsa', $peralatan->tanggal_kedaluwarsa?->format('Y-m-d')) }}">
                    <div class="form-text">Wajib untuk APAR. APAR yang kedaluwarsa dalam 90 hari dihitung "perlu refill" di dashboard.</div>
                </div>
            </div>

            <div class="mb-3">
                <label class="k3-label" for="kondisi">Kondisi</label>
                <select name="kondisi" id="kondisi" class="form-select" style="max-width: 260px;" required>
                    @foreach (\App\Models\K3Peralatan::KONDISI as $kunci => $label)
                        <option value="{{ $kunci }}" @selected(old('kondisi', $peralatan->kondisi) === $kunci)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="k3-label" for="keterangan">Keterangan (opsional)</label>
                <textarea name="keterangan" id="keterangan" rows="3" maxlength="1000" class="form-control"
                          placeholder="Catatan tambahan">{{ old('keterangan', $peralatan->keterangan) }}</textarea>
            </div>

            <button type="submit" class="k3-btn k3-btn-red">
                <i class="bi bi-check-circle"></i> {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Peralatan' }}
            </button>
        </form>

    </div>

@endsection

@push('scripts')
<script>
    // Menyesuaikan isian dengan kategori yang dipilih.
    // Kalau script ini tidak jalan, semua isian tetap tampil dan form tetap berfungsi
    // (server memastikan data APAR selalu berjumlah 1 dan mengosongkan kolom yang tidak relevan).
    (function () {
        var kategori = document.getElementById('kategori');
        var blokApar = document.getElementById('blokApar');
        var blokJumlah = document.getElementById('blokJumlah');
        var labelJumlah = document.getElementById('labelJumlah');
        var bantuanJumlah = document.getElementById('bantuanJumlah');
        if (! kategori) { return; }

        function sinkron() {
            var k = kategori.value;

            blokApar.style.display = k === 'APAR' ? '' : 'none';
            blokJumlah.style.display = k === 'APAR' ? 'none' : '';

            if (k === 'APD') {
                labelJumlah.textContent = 'Jumlah Stok';
                bantuanJumlah.textContent = 'Jumlah unit APD yang tersedia sekarang.';
            } else if (k === 'P3K') {
                labelJumlah.textContent = 'Jumlah Item Standar';
                bantuanJumlah.textContent = 'Jumlah item standar di dalam kotak atau ruang P3K ini.';
            }
        }

        kategori.addEventListener('change', sinkron);
        sinkron();
    })();
</script>
@endpush