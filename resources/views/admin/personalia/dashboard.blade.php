@extends('admin.layouts.personalia')

@section('page-title', 'Dashboard Personalia')
@section('page-subtitle', 'Memantau dan mengelola data kehadiran, cuti, izin, dan lembur.')

@section('content')

    <div class="panel">
        <div class="panel-head">
            <h2>Selamat datang, {{ auth()->user()->name }}</h2>
        </div>
        <div class="panel-body">
            <p style="margin: 0; color: var(--muted); font-size: .9rem;">
                Panel Personalia sudah aktif. Ringkasan kehadiran hari ini akan ditampilkan di sini pada langkah berikutnya.
            </p>
        </div>
    </div>

@endsection