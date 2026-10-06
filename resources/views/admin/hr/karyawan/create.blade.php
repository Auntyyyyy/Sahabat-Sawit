@extends('admin.layouts.hr')

@section('page-title', 'Tambah Karyawan')
@section('page-subtitle', 'Isi data karyawan baru')

@section('content')

    <div class="panel">
        <div class="panel-head">
            <h2>Form Karyawan Baru</h2>
            <a href="{{ route('hr.karyawan.index') }}" class="panel-link">&larr; Kembali ke Daftar</a>
        </div>

        <form method="POST" action="{{ route('hr.karyawan.store') }}">
            @csrf

            <div class="form-grid">
                <div class="form-field">
                    <label for="name">Nama Lengkap</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required>
                    @error('name') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required>
                    @error('email') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label for="phone">No. Telepon</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}">
                    @error('phone') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label for="gender">Jenis Kelamin</label>
                    <select name="gender" id="gender" required>
                        <option value="">Pilih...</option>
                        <option value="L" {{ old('gender') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('gender') === 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('gender') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label for="birth_date">Tanggal Lahir</label>
                    <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}">
                    @error('birth_date') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label for="division">Divisi</label>
                    <input type="text" name="division" id="division" value="{{ old('division') }}" placeholder="Contoh: Marketing" required>
                    @error('division') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label for="position">Jabatan</label>
                    <input type="text" name="position" id="position" value="{{ old('position') }}" placeholder="Contoh: Staff Marketing" required>
                    @error('position') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label for="join_date">Tanggal Bergabung</label>
                    <input type="date" name="join_date" id="join_date" value="{{ old('join_date') }}" required>
                    @error('join_date') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label for="status">Status</label>
                    <select name="status" id="status" required>
                        <option value="">Pilih...</option>
                        <option value="aktif" {{ old('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="kontrak" {{ old('status') === 'kontrak' ? 'selected' : '' }}>Kontrak</option>
                        <option value="trainee" {{ old('status') === 'trainee' ? 'selected' : '' }}>Trainee</option>
                        <option value="resign" {{ old('status') === 'resign' ? 'selected' : '' }}>Resign</option>
                    </select>
                    @error('status') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field full">
                    <label for="address">Alamat</label>
                    <textarea name="address" id="address" rows="3">{{ old('address') }}</textarea>
                    @error('address') <div class="error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Simpan Karyawan</button>
                <a href="{{ route('hr.karyawan.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>

@endsection