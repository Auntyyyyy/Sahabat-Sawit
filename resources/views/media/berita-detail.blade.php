@extends('layouts.app')

@section('title', $berita->judul . ' — PT Sahabat Sawit')

@section('content')
<section class="relative bg-dark-green text-white overflow-hidden hero-detail-fullscreen">
    <div class="absolute inset-0 bg-dark-green/70"></div>

    <div class="relative max-w-4xl mx-auto px-6 lg:px-8 w-full">
        <nav aria-label="Breadcrumb" class="flex mb-5">
            <div class="breadcrumb-pill">
                <a href="{{ route('home') }}" class="breadcrumb-link" aria-label="Home">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 9.75L12 3l9 6.75V21a.75.75 0 01-.75.75H15a.75.75 0 01-.75-.75v-4.5a.75.75 0 00-.75-.75h-3a.75.75 0 00-.75.75V21a.75.75 0 01-.75.75H3.75A.75.75 0 013 21V9.75z"/>
                    </svg>
                </a>
                <svg class="breadcrumb-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <a href="{{ route('media.berita') }}" class="breadcrumb-link-text">Berita</a>
                <svg class="breadcrumb-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="breadcrumb-current text-light-green">{{ $berita->judul }}</span>
            </div>
        </nav>

        <span class="font-heading font-semibold text-light-green uppercase text-sm tracking-wide">
            {{ $berita->tanggal->translatedFormat('d F Y') }}
        </span>
        <h1 class="mt-2 font-heading font-bold text-3xl md:text-4xl">{{ $berita->judul }}</h1>
    </div>
</section>

<section class="py-14 md:py-16 bg-white">
    <div class="max-w-4xl mx-auto px-6 lg:px-8">

        <img src="{{ $berita->gambar ? asset('storage/' . $berita->gambar) : 'https://placehold.co/900x550/1F5F3B/F5F1E8?text=' . urlencode($berita->judul) }}"
             alt="{{ $berita->judul }}"
             class="w-full h-[320px] md:h-[420px] object-cover rounded-brand shadow-lg"
             onerror="this.onerror=null;this.src='https://placehold.co/900x550/1F5F3B/F5F1E8?text={{ urlencode($berita->judul) }}'">

        <div class="mt-8">
            <p class="text-gray-text leading-relaxed text-justify text-base md:text-lg">
                {{ $berita->deskripsi_singkat }}
            </p>
        </div>

        <div class="mt-12 pt-8 border-t border-gray-100">
            <a href="{{ route('media.berita') }}" class="inline-flex items-center gap-2 text-primary-green font-heading font-semibold hover:text-dark-green transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Berita & Galeri Kegiatan
            </a>
        </div>

    </div>
</section>
@endsection