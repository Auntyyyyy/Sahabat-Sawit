@extends('layouts.go')

@section('page-title', 'Peminjaman Baru')
@section('page-subtitle', 'Catat peminjaman mess, ruangan, atau fasilitas lain')

@section('content')

<div class="panel">
    <div class="panel-head">
        <h2>Form Peminjaman Fasilitas</h2>
        <a href="{{ route('go.facility-loans.index') }}" class="panel-link">← Kembali</a>
    </div>

    <form action="{{ route('go.facility-loans.store') }}" method="POST">
        @csrf
        <div class="form-grid">
            <div class="form-field">
                <label>Fasilitas</label>
                <select name="facility_id" id="facilitySelect">
                    <option value="">— Pilih fasilitas —</option>
                    @foreach($facilities as $f)
                    <option value="{{ $f->id }}" data-kapasitas="{{ $f->kapasitas }}"
                        @selected((string) old('facility_id', request('facility_id')) === (string) $f->id)>
                        {{ $f->kode_fasilitas }} — {{ $f->nama }} ({{ $f->kategori }})
                    </option>
                    @endforeach
                </select>
                <small id="kapasitasHint" style="color: var(--muted); font-size:.78rem; display:block; margin-top:.35rem;"></small>
                @error('facility_id') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-field">
                <label>Jumlah Orang</label>
                <input type="number" min="1" name="jumlah_orang" value="{{ old('jumlah_orang') }}" placeholder="mis. 2">
                @error('jumlah_orang') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-field">
                <label>Nama Peminjam</label>
                <input type="text" name="peminjam_nama" value="{{ old('peminjam_nama') }}" placeholder="Tulis nama peminjam">
                @error('peminjam_nama') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-field">
                <label>Divisi</label>
                <input type="text" name="peminjam_divisi" value="{{ old('peminjam_divisi') }}" placeholder="mis. IT, Keuangan, Kebun">
                @error('peminjam_divisi') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-field">
                <label>Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', now()->format('Y-m-d')) }}">
                @error('tanggal_mulai') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-field">
                <label>Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', now()->format('Y-m-d')) }}">
                <small style="color: var(--muted); font-size:.78rem; display:block; margin-top:.35rem;">
                    Tanggal terakhir fasilitas masih digunakan.
                </small>
                @error('tanggal_selesai') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-field full">
                <label>Keperluan</label>
                <input type="text" name="keperluan" value="{{ old('keperluan') }}" placeholder="mis. Menginap untuk dinas luar">
                @error('keperluan') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-field full">
                <label>Catatan</label>
                <textarea name="catatan" rows="2" placeholder="Keterangan tambahan (opsional)">{{ old('catatan') }}</textarea>
                @error('catatan') <div class="error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('go.facility-loans.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Peminjaman</button>
        </div>
    </form>
</div>

@endsection

@section('extra-script')
<script>
    const facilitySelect = document.getElementById('facilitySelect');
    const kapasitasHint = document.getElementById('kapasitasHint');

    function syncKapasitas() {
        const opt = facilitySelect.options[facilitySelect.selectedIndex];
        const kapasitas = opt ? opt.dataset.kapasitas : '';
        kapasitasHint.textContent = kapasitas ? 'Kapasitas fasilitas: ' + kapasitas + ' orang/unit.' : '';
    }

    facilitySelect.addEventListener('change', syncKapasitas);
    syncKapasitas();
</script>
@endsection