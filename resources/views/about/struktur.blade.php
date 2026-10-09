@extends('layouts.app')

@section('title', 'Profil Manajemen — PT Sahabat Sawit')

@section('content')

{{-- Hero: disamakan dengan hero Profil Perusahaan (foto latar penuh + overlay gradasi
     + badge + subnav). Yang berbeda hanya judul besarnya dan breadcrumb-nya. --}}
<section class="relative min-h-screen flex items-center bg-dark-green text-white bg-cover bg-center bg-no-repeat hero-section" style="background-image: url('{{ asset('images/herotentang.png') }}');">
    <!-- Overlay Gradient -->
    <div class="absolute inset-0 hero-overlay"></div>

    <div class="relative w-full max-w-4xl mx-auto px-6 lg:px-8 text-center" style="padding-top:7rem;padding-bottom:6rem;">

        <!-- Breadcrumb -->
        <div class="hero-fade hero-delay-1 flex items-center justify-center gap-2 text-sm text-white/60">
            <a href="{{ url('/') }}" class="hover:text-white transition-colors">Beranda</a>
            <span>/</span>
            <a href="{{ route('about') }}" class="hover:text-white transition-colors">Tentang Kami</a>
            <span>/</span>
            <span class="text-white/90">Profil Manajemen</span>
        </div>

        <!-- Label + Judul -->
        <span class="hero-fade hero-delay-2 block mt-6 font-heading font-semibold text-light-green uppercase text-sm tracking-wide">Tentang Kami</span>

        <h1 class="hero-fade hero-delay-3 mt-4 font-heading font-bold text-3xl md:text-5xl" style="line-height:1.2;text-wrap:balance;">
            Profil Manajemen
        </h1>

        <p class="hero-fade hero-delay-4 mt-6 text-white/75 max-w-2xl mx-auto leading-relaxed" style="text-wrap:balance;">
Mengenal Jajaran Manajemen yang Menjadi Penggerak Perusahaan        </p>

        <!-- Badge Statistik -->
        <div class="hero-fade hero-delay-5 flex flex-wrap justify-center gap-3 mt-8">
            <span class="hero-badge">Beroperasi sejak 2023</span>
            <span class="hero-badge">Kabupaten Rokan Hilir</span>
            <span class="hero-badge">Standar ISPO & RSPO</span>
        </div>

        <!-- Sub-navigasi "Tentang Kami" -->
        <div class="hero-fade hero-delay-6 mt-10">
            @include('about._subnav')
        </div>
    </div>
</section>

{{-- ============================= --}}
{{-- TIM KAMI — STRUKTUR ORGANISASI (dikelompokkan per level, tanpa foto) --}}
{{-- ============================= --}}
@php
    $orgLevelLabels = [
        1 => 'Pimpinan Tertinggi',
        2 => 'Direksi',
        3 => 'Manajer',
        4 => 'Kepala & Supervisor',
        5 => 'Officer & Staf',
    ];

    // Semua kartu dibuat seragam — ukuran persis seperti level President Director.
    // Hierarki tetap terbaca lewat label level & urutan tampil, bukan lagi dari ukuran kartu.
    $orgCardSize = 'w-64 sm:w-72';
    $orgTextSize = ['nama' => 'text-lg md:text-xl', 'jabatan' => 'text-xs md:text-sm'];
    $orgIconSize = 'w-16 h-16';

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

            <div class="flex flex-wrap justify-center gap-5 md:gap-6">
                @foreach($anggota as $o)
                <div class="org-name-card {{ $orgCardSize }}">
                    <div class="org-name-icon {{ $orgIconSize }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                    </div>
                    <p class="org-name-text {{ $orgTextSize['nama'] }}">
                        {{ $o['nama'] }}
                    </p>
                    <p class="org-name-jabatan {{ $orgTextSize['jabatan'] }}">
                        {{ $o['jabatan'] }}
                    </p>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</section>

<style>
    .org-name-card{
        display:flex;
        flex-direction:column;
        align-items:center;
        text-align:center;
        background:#ffffff;
        border-radius:1rem;
        padding:1.5rem 1rem 1.25rem;
        box-shadow:0 6px 18px rgba(22,74,46,0.08);
        border-top:3px solid var(--secondary-green);
        transition:transform .25s ease, box-shadow .25s ease, border-color .25s ease;
    }
    .org-name-card:hover{
        transform:translateY(-4px);
        box-shadow:0 14px 30px rgba(22,74,46,0.16);
        border-top-color: var(--dark-green);
    }
    .org-name-icon{
        display:flex;
        align-items:center;
        justify-content:center;
        border-radius:9999px;
        background: rgba(47,107,63,0.1);
        color: var(--secondary-green);
        margin-bottom:.85rem;
    }
    .org-name-icon svg{ width:55%; height:55%; }
    .org-name-text{
        font-family: var(--font-heading, inherit);
        font-weight:700;
        color: var(--dark-green);
        line-height:1.3;
    }
    .org-name-jabatan{
        margin-top:.3rem;
        color:#8A6A45;
        font-weight:600;
        text-transform:uppercase;
        letter-spacing:.03em;
        line-height:1.4;
    }
</style>
@endsection