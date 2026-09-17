@extends('admin.layouts.hr')

@section('page-title', 'Dashboard HR')

@section('content')

    <div class="summary-row">
        <div class="summary-item">
            <div class="label">Total Karyawan</div>
            <div class="value">--</div>
            <div class="delta">Data belum terhubung</div>
        </div>
        <div class="summary-item">
            <div class="label">Hadir Hari Ini</div>
            <div class="value">--</div>
        </div>
        <div class="summary-item">
            <div class="label">Izin / Cuti</div>
            <div class="value attention">--</div>
        </div>
        <div class="summary-item">
            <div class="label">Tidak Hadir</div>
            <div class="value attention">--</div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <h2>Absensi Hari Ini</h2>
            <a href="#" class="panel-link">Lihat semua</a>
        </div>
        <div class="empty-note">
            Belum ada data absensi yang terhubung. Tabel ini akan menampilkan daftar kehadiran staff secara real-time setelah fitur absensi dibuat.
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <h2>Karyawan Terbaru</h2>
            <a href="{{ route('hr.karyawan.index') }}" class="panel-link">Kelola data karyawan</a>
        </div>
        <div class="empty-note">
            Belum ada data karyawan. Tabel ini akan menampilkan nama, jabatan, dan status karyawan setelah modul Data Karyawan dibuat.
        </div>
    </div>

@endsection