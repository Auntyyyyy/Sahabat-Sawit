@extends('admin.layouts.hr')

@section('page-title', 'Tambah Karyawan')
@section('page-subtitle', 'Isi data karyawan baru')

@section('content')

    <div class="panel">
        <form method="POST" action="{{ route('hr.karyawan.store') }}">
            @csrf

            <div class="form-grid">
                <div class="form-field">
                    <label for="name">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                    @error('email') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label for="phone">No. HP</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}">
                    @error('phone') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label for="gender">Jenis Kelamin</label>
                    <select id="gender" name="gender" required>
                        <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Pilih</option>
                        <option value="L" {{ old('gender') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('gender') === 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('gender') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label for="birth_date">Tanggal Lahir</label>
                    <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date') }}">
                    @error('birth_date') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label for="division">Divisi</label>
                    <input type="text" id="division" name="division" value="{{ old('division') }}" placeholder="mis. Kebun, Pabrik, Keuangan" required>
                    @error('division') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label for="position">Jabatan</label>
                    <input type="text" id="position" name="position" value="{{ old('position') }}" required>
                    @error('position') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label for="join_date">Tanggal Bergabung</label>
                    <input type="date" id="join_date" name="join_date" value="{{ old('join_date') }}" required>
                    @error('join_date') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label for="status">Status</label>
                    <select id="status" name="status" required>
                        <option value="aktif" {{ old('status', 'aktif') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="cuti" {{ old('status') === 'cuti' ? 'selected' : '' }}>Cuti</option>
                        <option value="nonaktif" {{ old('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    @error('status') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field full">
                    <label for="address">Alamat</label>
                    <textarea id="address" name="address" rows="3">{{ old('address') }}</textarea>
                    @error('address') <div class="error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('hr.karyawan.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>

@endsection
