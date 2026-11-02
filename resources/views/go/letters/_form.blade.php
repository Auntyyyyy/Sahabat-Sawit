@csrf

<div class="form-grid">
    <div class="form-field">
        <label>Arah Surat</label>
        <select name="arah" id="arah">
            @foreach($arahOptions as $opt)
            <option value="{{ $opt }}" @selected(old('arah', $letter->arah ?: 'Masuk') === $opt)>Surat {{ $opt }}</option>
            @endforeach
        </select>
        @error('arah') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="form-field">
        <label>Jenis Surat</label>
        <select name="jenis">
            <option value="">— Pilih jenis surat —</option>
            @foreach($jenisOptions as $opt)
            <option value="{{ $opt }}" @selected(old('jenis', $letter->jenis) === $opt)>{{ $opt }}</option>
            @endforeach
        </select>
        @error('jenis') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="form-field">
        <label>Nomor Surat</label>
        <input type="text" name="nomor_surat" value="{{ old('nomor_surat', $letter->nomor_surat) }}" placeholder="mis. 012/GA/XI/2026">
        @error('nomor_surat') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="form-field">
        <label>Tanggal Surat</label>
        <input type="date" name="tanggal_surat" value="{{ old('tanggal_surat', optional($letter->tanggal_surat)->format('Y-m-d')) }}">
        @error('tanggal_surat') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="form-field" id="fieldDiterima">
        <label>Tanggal Diterima</label>
        <input type="date" name="tanggal_diterima" value="{{ old('tanggal_diterima', optional($letter->tanggal_diterima)->format('Y-m-d')) }}">
        @error('tanggal_diterima') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="form-field">
        <label id="labelPihak">Pengirim</label>
        <input type="text" name="pengirim_tujuan" value="{{ old('pengirim_tujuan', $letter->pengirim_tujuan) }}" placeholder="Nama instansi / pihak / divisi">
        @error('pengirim_tujuan') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="form-field">
        <label>Sifat Surat</label>
        <select name="sifat">
            @foreach($sifatOptions as $opt)
            <option value="{{ $opt }}" @selected(old('sifat', $letter->sifat ?: 'Biasa') === $opt)>{{ $opt }}</option>
            @endforeach
        </select>
        @error('sifat') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="form-field">
        <label>Status</label>
        <select name="status">
            @foreach($statusOptions as $opt)
            <option value="{{ $opt }}" @selected(old('status', $letter->status ?: 'Baru') === $opt)>{{ $opt }}</option>
            @endforeach
        </select>
        @error('status') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="form-field full">
        <label>Perihal</label>
        <input type="text" name="perihal" value="{{ old('perihal', $letter->perihal) }}" placeholder="mis. Permohonan penggantian perangkat kantor">
        @error('perihal') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="form-field full">
        <label>Lampiran Surat</label>
        @if($letter->lampiran)
            <div style="font-size:.84rem; margin-bottom:.6rem;">
                Lampiran saat ini:
                <a href="{{ route('go.letters.lampiran', $letter) }}" target="_blank" rel="noopener" class="panel-link">{{ $letter->lampiran_nama }}</a>
            </div>
        @endif
        <input type="file" name="lampiran" accept=".pdf,.doc,.docx">
        <small style="color: var(--muted); font-size:.78rem; display:block; margin-top:.35rem;">
            Format PDF, DOC, atau DOCX, maksimal 5MB. Upload file baru untuk mengganti yang lama.
        </small>
        @error('lampiran') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="form-field full">
        <label>Keterangan Tambahan</label>
        <textarea name="keterangan" rows="3" placeholder="Catatan tambahan, mis. tindak lanjut atau nomor agenda.">{{ old('keterangan', $letter->keterangan) }}</textarea>
        @error('keterangan') <div class="error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="form-actions">
    <a href="{{ route('go.letters.index') }}" class="btn btn-secondary">Batal</a>
    <button type="submit" class="btn btn-primary">Simpan</button>
</div>

<script>
    (function () {
        const arah = document.getElementById('arah');
        const labelPihak = document.getElementById('labelPihak');
        const fieldDiterima = document.getElementById('fieldDiterima');

        function sync() {
            const masuk = arah.value !== 'Keluar';
            labelPihak.textContent = masuk ? 'Pengirim' : 'Tujuan';
            fieldDiterima.style.display = masuk ? '' : 'none';
        }

        arah.addEventListener('change', sync);
        sync();
    })();
</script>