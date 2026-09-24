@extends('layouts.app')

@section('title', 'Profil Manajemen — PT Sahabat Sawit')

@section('content')

{{-- Header band gelap: halaman ini tidak punya foto hero seperti Profil
     Perusahaan, jadi dibuat band solid supaya navbar (transparan di atas
     hero gelap) tetap terbaca begitu halaman dibuka, sekaligus jadi rumah
     untuk breadcrumb, judul, dan subnav. --}}
<section class="relative bg-dark-green text-white pt-32 pb-10 md:pt-36 md:pb-14 overflow-hidden">
    <div class="absolute inset-0 opacity-[0.4]" style="background-image: radial-gradient(rgba(255,255,255,0.06) 1px, transparent 1px); background-size: 22px 22px;"></div>

    <div class="relative max-w-5xl mx-auto px-6 lg:px-8 text-center">
        <div class="flex items-center justify-center gap-2 text-sm text-white/60 mb-4">
            <a href="{{ url('/') }}" class="hover:text-white transition-colors">Beranda</a>
            <span>/</span>
            <a href="{{ route('about') }}" class="hover:text-white transition-colors">Tentang Kami</a>
            <span>/</span>
            <span class="text-white/90">Profil Manajemen</span>
        </div>

        <span class="block font-heading font-semibold text-light-green uppercase text-sm tracking-wide mb-2">Tentang Kami</span>
        <h1 class="font-heading font-bold text-2xl md:text-4xl mb-8">Profil Manajemen</h1>

        @include('about._subnav')
    </div>
</section>

{{-- ============================= --}}
{{-- TIM KAMI — STRUKTUR ORGANISASI (dikelompokkan per level, hierarki visual) --}}
{{-- ============================= --}}
@php
    $orgLevelLabels = [
        1 => 'Pimpinan Tertinggi',
        2 => 'Direksi',
        3 => 'Manajer',
        4 => 'Kepala & Supervisor',
        5 => 'Officer & Staf',
    ];

    // Ukuran kartu mengecil seiring turunnya tingkatan — inilah yang membuat
    // hierarki terasa, bukan cuma daftar foto datar.
    $orgLevelCardSize = [
        1 => 'w-60 sm:w-72',
        2 => 'w-52 sm:w-64',
        3 => 'w-44 sm:w-52',
        4 => 'w-36 sm:w-44',
        5 => 'w-32 sm:w-40',
    ];

    $orgLevels = collect($organisasi)->groupBy('level')->sortKeys();
@endphp

<section id="struktur-organisasi" class="relative py-20 lg:py-24 bg-cream overflow-hidden">

    <div class="absolute inset-0 pointer-events-none"
         style="background-image: radial-gradient(rgba(31,95,59,0.08) 1px, transparent 1px); background-size: 24px 24px;"></div>

    <div class="relative max-w-6xl mx-auto px-6 lg:px-8">

        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="font-heading font-semibold text-secondary-green uppercase text-sm tracking-widest">Tim Kami</span>
            <h2 class="mt-3 font-heading font-bold text-3xl md:text-4xl text-dark-green">Struktur Organisasi</h2>
            <p class="mt-4 text-gray-text leading-relaxed">
                Dipimpin oleh tim profesional yang berpengalaman di industri kelapa sawit,
                berkomitmen menjalankan perusahaan secara transparan dan bertanggung jawab.
            </p>
        </div>

        @foreach($orgLevels as $level => $anggota)
        <div class="{{ $loop->last ? '' : 'mb-14' }}">

            {{-- Garis penghubung tipis antar level, kecuali sebelum level pertama --}}
            @if(!$loop->first)
            <div class="flex justify-center mb-10">
                <span class="w-px h-12 bg-dark-green/15"></span>
            </div>
            @endif

            {{-- Label level --}}
            <div class="text-center mb-8">
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-dark-green text-white font-heading font-semibold text-xs uppercase tracking-widest">
                    {{ $orgLevelLabels[$level] ?? 'Level ' . $level }}
                </span>
            </div>

            <div class="flex flex-wrap justify-center gap-6 md:gap-8">
                @foreach($anggota as $o)
                <div class="group {{ $orgLevelCardSize[$level] ?? 'w-36 sm:w-44' }}">
                    <div class="relative aspect-[3/4] rounded-brand overflow-hidden shadow-md group-hover:shadow-2xl group-hover:-translate-y-1.5 transition-all duration-300">
                        <img src="{{ asset('images/organisasi/' . $o['photo']) }}"
                             alt="{{ $o['nama'] }} — {{ $o['jabatan'] }}"
                             class="w-full h-full object-cover"
                             onerror="this.onerror=null;this.src='https://placehold.co/400x520/1F5F3B/F5F1E8?text={{ urlencode($o['nama']) }}'">
                        <div class="absolute inset-0 bg-gradient-to-t from-dark-green/90 via-dark-green/15 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-3 md:p-4">
                            <p class="font-heading font-bold text-white leading-snug {{ $level <= 2 ? 'text-base md:text-lg' : 'text-xs md:text-sm' }}">
                                {{ $o['nama'] }}
                            </p>
                            <p class="mt-0.5 text-[11px] md:text-xs text-white/75 leading-snug">
                                {{ $o['jabatan'] }}
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</section>
@endsection