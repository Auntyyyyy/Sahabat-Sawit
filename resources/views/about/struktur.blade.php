@extends('layouts.app')

@section('title', 'Profil Manajemen — PT Sahabat Sawit')

@section('content')
@include('about._style')

{{-- Struktur organisasi dikelompokkan per level (tanpa foto). Hierarki terbaca dari urutan dan label level. --}}
@php
    $orgLevelLabels = [
        1 => 'Pimpinan Tertinggi',
        2 => 'Direksi',
        3 => 'Manajer',
        4 => 'Kepala & Supervisor',
        5 => 'Officer & Staf',
    ];

    $orgLevels = collect($organisasi)->groupBy('level')->sortKeys();
@endphp

<style>
    .ab-levels { margin-top: clamp(2.5rem, 6vh, 4rem); }
    .ab-level { display: grid; grid-template-columns: 1fr; gap: 1.25rem; padding: 2rem 0; border-top: 1px solid rgba(242,244,236,.15); }
    .ab-level:last-child { border-bottom: 1px solid rgba(242,244,236,.15); }
    @media (min-width: 900px) { .ab-level { grid-template-columns: 1fr 3fr; gap: 3rem; } }
    .ab-level-name { font-size: .95rem; font-weight: 600; color: rgba(242,244,236,.75); }
    .ab-people { list-style: none; margin: 0; padding: 0; display: grid; grid-template-columns: repeat(auto-fill, minmax(15rem, 1fr)); gap: 1.75rem 2rem; }
    .ab-person-name { font-size: 1.3rem; font-weight: 700; letter-spacing: -.02em; line-height: 1.2; }
    .ab-person-role { margin-top: .25rem; font-size: .9rem; color: rgba(242,244,236,.65); }
</style>

<div class="ab">

    @include('about._hero', [
        'title' => 'Profil Manajemen',
        'lead'  => 'Mengenal Jajaran Manajemen yang Menjadi Penggerak Perusahaan',
        'crumb' => 'Profil Manajemen',
    ])

    <section class="ab-sec" id="struktur-organisasi">
        <p class="ab-label">Tim kami</p>
        <h2 class="ab-h2">Struktur Organisasi</h2>
        <p class="ab-p">
            Dipimpin oleh tim profesional yang berpengalaman di industri kelapa sawit,
            berkomitmen menjalankan perusahaan secara transparan dan bertanggung jawab.
        </p>

        <div class="ab-levels">
            @foreach($orgLevels as $level => $anggota)
            <div class="ab-level">
                <h3 class="ab-level-name">{{ $orgLevelLabels[$level] ?? 'Level ' . $level }}</h3>
                <ul class="ab-people">
                    @foreach($anggota as $o)
                    <li>
                        <p class="ab-person-name">{{ $o['nama'] }}</p>
                        <p class="ab-person-role">{{ $o['jabatan'] }}</p>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endforeach
        </div>
    </section>

</div>
@endsection