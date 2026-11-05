@extends('admin.layouts.k3')

@php
    // Form ini dipakai untuk Tambah dan Edit. Bedanya: data sudah ada di database atau belum.
    $isEdit = $program->exists;
@endphp

@section('title', $isEdit ? 'Edit Program' : 'Tambah Program')
@section('page_title', $isEdit ? 'Edit Program K3L' : 'Tambah Program K3L')

@section('content')

    <div class="k3-card" style="max-width: 820px;">

        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
            <div>
                <h2 class="k3-card-title" style="font-size:1.25rem">{{ $isEdit ? 'Edit Program' : 'Tambah Program' }}</h2>
                <p class="k3-card-sub mb-0">
                    {{ $isEdit ? 'Ubah data program kerja ini.' : 'Catat satu program kerja K3L.' }}
                </p>
            </div>
            <a href="{{ route('k3.program.index') }}" class="k3-btn k3-btn-outline">
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

        <form action="{{ $isEdit ? route('k3.program.update', $program) : route('k3.program.store') }}" method="POST">
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif

            <div class="mb-3">
                <label class="k3-label" for="nama">Nama Program</label>
                <input type="text" name="nama" id="nama" class="form-control"
                       value="{{ old('nama', $program->nama) }}"
                       placeholder="Contoh: Pelatihan tanggap darurat dan simulasi kebakaran" required>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <label class="k3-label" for="tahun">Tahun</label>
                    <input type="number" name="tahun" id="tahun" class="form-control" min="2000" max="2100"
                           value="{{ old('tahun', $program->tahun) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="k3-label" for="kategori">Kategori</label>
                    <select name="kategori" id="kategori" class="form-select" required>
                        <option value="">Pilih kategori</option>
                        @foreach (\App\Models\K3Program::KATEGORI as $kunci => $label)
                            <option value="{{ $kunci }}" @selected(old('kategori', $program->kategori) === $kunci)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-5">
                    <label class="k3-label" for="pic">Penanggung Jawab (PIC)</label>
                    <input type="text" name="pic" id="pic" class="form-control"
                           value="{{ old('pic', $program->pic) }}" placeholder="Contoh: Tim K3">
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="k3-label" for="target_tanggal">Target Selesai</label>
                    <input type="date" name="target_tanggal" id="target_tanggal" class="form-control"
                           value="{{ old('target_tanggal', $program->target_tanggal?->format('Y-m-d')) }}">
                    <div class="form-text">Program yang belum selesai setelah tanggal ini ditandai Terlambat.</div>
                </div>

                <div class="col-md-4">
                    <label class="k3-label" for="status">Status</label>
                    <select name="status" id="status" class="form-select" required>
                        @foreach (\App\Models\K3Program::STATUS as $kunci => $label)
                            <option value="{{ $kunci }}" @selected(old('status', $program->status) === $kunci)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <div class="form-text">Hanya program berstatus Selesai yang dihitung di progres dashboard.</div>
                </div>

                {{-- Hanya relevan kalau status Selesai. Disembunyikan otomatis untuk status lain. --}}
                <div class="col-md-4" id="blokSelesai">
                    <label class="k3-label" for="tanggal_selesai">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control"
                           value="{{ old('tanggal_selesai', $program->tanggal_selesai?->format('Y-m-d')) }}">
                    <div class="form-text">Kalau dikosongkan, dipakai tanggal hari ini.</div>
                </div>
            </div>

            <div class="mb-4">
                <label class="k3-label" for="keterangan">Keterangan (opsional)</label>
                <textarea name="keterangan" id="keterangan" rows="3" maxlength="1000" class="form-control"
                          placeholder="Catatan atau rincian program">{{ old('keterangan', $program->keterangan) }}</textarea>
            </div>

            <button type="submit" class="k3-btn k3-btn-red">
                <i class="bi bi-check-circle"></i> {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Program' }}
            </button>
        </form>

    </div>

@endsection

@push('scripts')
<script>
    // Tampilkan kolom Tanggal Selesai hanya kalau status "Selesai".
    // Kalau script ini tidak jalan, kolom tetap tampil dan form tetap berfungsi
    // (server mengosongkan tanggal selesai untuk status lain).
    (function () {
        var status = document.getElementById('status');
        var blok = document.getElementById('blokSelesai');
        if (! status || ! blok) { return; }

        function sinkron() {
            blok.style.display = status.value === 'selesai' ? '' : 'none';
        }

        status.addEventListener('change', sinkron);
        sinkron();
    })();
</script>
@endpush