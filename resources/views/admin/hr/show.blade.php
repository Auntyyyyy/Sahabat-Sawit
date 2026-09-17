@extends('admin.layouts.hr')

@section('page-title', 'Detail Karyawan')
@section('page-subtitle', $karyawan->name)

@section('content')

    <div class="panel">
        <div class="panel-head">
            <h2>{{ $karyawan->name }}</h2>
            <div style="display:flex; gap:.6rem;">
                <a href="{{ route('hr.karyawan.edit', $karyawan) }}" class="btn btn-secondary">Edit</a>
                <a href="{{ route('hr.karyawan.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>

        <dl class="detail-grid">
            <dt>Email</dt>
            <dd>{{ $karyawan->email }}</dd>

            <dt>No. HP</dt>
            <dd>{{ $karyawan->phone ?: '-' }}</dd>

            <dt>Jenis Kelamin</dt>
            <dd>{{ $karyawan->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</dd>

            <dt>Usia</dt>
            <dd>{{ $karyawan->usia !== null ? $karyawan->usia . ' tahun' : '-' }}</dd>

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
    </div>

@endsection