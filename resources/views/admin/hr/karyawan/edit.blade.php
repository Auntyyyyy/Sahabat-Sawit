@extends('admin.layouts.hr')

@section('page-title', 'Edit Karyawan')
@section('page-subtitle', $karyawan->name)

@section('content')

    <div class="panel">
        <div class="panel-head">
            <h2>Edit Data Karyawan</h2>
            <a href="{{ route('hr.karyawan.index') }}" class="panel-link">&larr; Kembali ke Daftar</a>
        </div>

        <form method="POST" action="{{ route('hr.karyawan.update', $karyawan) }}">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-field">
                    <label for="name">Nama Lengkap</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $karyawan->name) }}" required>
                    @error('name') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $karyawan->email) }}" required>
                    @error('email') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label for="phone">No. Telepon</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $karyawan->phone) }}">
                    @error('phone') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label for="gender">Jenis Kelamin</label>
                    <select name="gender" id="gender" required>
                        <option value="">Pilih...</option>
                        <option value="L" {{ old('gender', $karyawan->gender) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('gender', $karyawan->gender) === 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('gender') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label for="birth_date">Tanggal Lahir</label>
                    <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', optional($karyawan->birth_date)->format('Y-m-d')) }}">
                    @error('birth_date') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label for="division">Divisi</label>
                    <input type="text" name="division" id="division" value="{{ old('division', $karyawan->division) }}" required>
                    @error('division') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label for="position">Jabatan</label>
                    <input type="text" name="position" id="position" value="{{ old('position', $karyawan->position) }}" required>
                    @error('position') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label for="join_date">Tanggal Bergabung</label>
                    <input type="date" name="join_date" id="join_date" value="{{ old('join_date', optional($karyawan->join_date)->format('Y-m-d')) }}" required>
                    @error('join_date') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field">
                    <label for="status">Status</label>
                    <select name="status" id="status" required>
                        <option value="">Pilih...</option>
                        @foreach (['aktif' => 'Aktif', 'kontrak' => 'Kontrak', 'trainee' => 'Trainee', 'resign' => 'Resign'] as $value => $text)
                            <option value="{{ $value }}" {{ old('status', $karyawan->status) === $value ? 'selected' : '' }}>{{ $text }}</option>
                        @endforeach
                    </select>
                    @error('status') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-field full">
                    <label for="address">Alamat</label>
                    <textarea name="address" id="address" rows="3">{{ old('address', $karyawan->address) }}</textarea>
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