@extends('admin.layouts.hr')

@section('page-title', 'Detail Karyawan')
@section('page-subtitle', $karyawan->name)

@section('content')

    <div class="panel">
        <div class="panel-head">
            <h2>Informasi Karyawan</h2>
            <a href="{{ route('hr.karyawan.index') }}" class="panel-link">&larr; Kembali ke Daftar</a>
        </div>

        <dl class="detail-grid">
            <dt>Nama Lengkap</dt>
            <dd>{{ $karyawan->name }}</dd>

            <dt>Email</dt>
            <dd>{{ $karyawan->email }}</dd>

            <dt>No. Telepon</dt>
            <dd>{{ $karyawan->phone ?: '-' }}</dd>

            <dt>Jenis Kelamin</dt>
            <dd>{{ $karyawan->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</dd>

            <dt>Tanggal Lahir</dt>
            <dd>{{ $karyawan->birth_date ? $karyawan->birth_date->translatedFormat('d F Y') . ' (' . $karyawan->birth_date->age . ' tahun)' : '-' }}</dd>

            <dt>Divisi</dt>
            <dd>{{ $karyawan->division }}</dd>

            <dt>Jabatan</dt>
            <dd>{{ $karyawan->position }}</dd>

            <dt>Tanggal Bergabung</dt>
            <dd>{{ $karyawan->join_date->translatedFormat('d F Y') }}</dd>

            <dt>Masa Kerja</dt>
            <dd>{{ $karyawan->masa_kerja }}</dd>

            <dt>Status</dt>
            <dd><span class="status-pill {{ $karyawan->status }}">{{ ucfirst($karyawan->status) }}</span></dd>

            <dt>Alamat</dt>
            <dd>{{ $karyawan->address ?: '-' }}</dd>
        </dl>

        <div class="form-actions">
            <a href="{{ route('hr.karyawan.edit', $karyawan) }}" class="btn btn-primary">Edit Data</a>
            <form method="POST" action="{{ route('hr.karyawan.destroy', $karyawan) }}" onsubmit="return confirm('Hapus data {{ $karyawan->name }}?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-secondary" style="color: var(--red); border-color: var(--red);">Hapus</button>
            </form>
        </div>
    </div>

@endsection