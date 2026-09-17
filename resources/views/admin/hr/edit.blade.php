@extends('admin.layouts.hr')

@section('page-title', 'Edit Karyawan')
@section('page-subtitle', $karyawan->name)

@section('content')

    <div class="panel">
        <form method="POST" action="{{ route('hr.karyawan.update', $karyawan) }}">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-field">
                    <label for="name">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $karyawan->name) }}" required>
                    @error('name') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $karyawan->email) }}" required>
                    @error('email') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label for="phone">No. HP</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $karyawan->phone) }}">
                    @error('phone') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label for="gender">Jenis Kelamin</label>
                    <select id="gender" name="gender" required>
                        <option value="L" {{ old('gender', $karyawan->gender) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('gender', $karyawan->gender) === 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('gender') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label for="birth_date">Tanggal Lahir</label>
                    <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date', optional($karyawan->birth_date)->format('Y-m-d')) }}">
                    @error('birth_date') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label for="division">Divisi</label>
                    <input type="text" id="division" name="division" value="{{ old('division', $karyawan->division) }}" required>
                    @error('division') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label for="position">Jabatan</label>
                    <input type="text" id="position" name="position" value="{{ old('position', $karyawan->position) }}" required>
                    @error('position') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label for="join_date">Tanggal Bergabung</label>
                    <input type="date" id="join_date" name="join_date" value="{{ old('join_date', optional($karyawan->join_date)->format('Y-m-d')) }}" required>
                    @error('join_date') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label for="status">Status</label>
                    <select id="status" name="status" required>
                        <option value="aktif" {{ old('status', $karyawan->status) === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="cuti" {{ old('status', $karyawan->status) === 'cuti' ? 'selected' : '' }}>Cuti</option>
                        <option value="nonaktif" {{ old('status', $karyawan->status) === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    @error('status') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field full">
                    <label for="address">Alamat</label>
                    <textarea id="address" name="address" rows="3">{{ old('address', $karyawan->address) }}</textarea>
                    @error('address') <div class="error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('hr.karyawan.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>

@endsection
