@extends('layouts.app')

@section('title', $pengetahuan->judul . ' — PT Sahabat Sawit')

@section('content')
<section class="relative bg-dark-green text-white overflow-hidden hero-detail-fullscreen">

    {{-- Background hero: pakai foto artikel sendiri kalau ada, supaya tiap
         artikel punya identitas visual masing-masing — bukan hijau polos
         berulang di semua artikel. --}}
    @if($pengetahuan->gambar)
        <div class="absolute inset-0">
            <img src="{{ asset('storage/' . $pengetahuan->gambar) }}"
                 alt=""
                 class="w-full h-full object-cover"
                 onerror="this.onerror=null;this.src='https://placehold.co/1600x900/2F6B3F/F5F1E8?text={{ urlencode($pengetahuan->judul) }}'">
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-dark-green via-dark-green/85 to-dark-green/35"></div>
    @else
        {{-- Fallback kalau artikel belum punya gambar: pola titik halus,
             senada dengan header band di halaman Tentang Kami --}}
        <div class="absolute inset-0 pointer-events-none opacity-[0.4]" style="background-image: radial-gradient(rgba(255,255,255,0.06) 1px, transparent 1px); background-size: 22px 22px;"></div>
        <div class="absolute inset-0 bg-dark-green/70"></div>
    @endif

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
                <a href="{{ route('media.pengetahuan') }}" class="breadcrumb-link-text">Pengetahuan</a>
                <svg class="breadcrumb-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="breadcrumb-current text-light-green">{{ $pengetahuan->judul }}</span>
            </div>
        </nav>

        <span class="font-heading font-semibold text-light-green uppercase text-sm tracking-wide">
            <i class="bi bi-lightbulb"></i> Tahukah Anda?
        </span>
        <h1 class="mt-2 font-heading font-bold text-3xl md:text-4xl">{{ $pengetahuan->judul }}</h1>
    </div>
</section>

<section class="py-14 md:py-16 bg-white">
    <div class="max-w-4xl mx-auto px-6 lg:px-8">

        @if($pengetahuan->gambar)
        <img src="{{ asset('storage/' . $pengetahuan->gambar) }}"
             alt="{{ $pengetahuan->judul }}"
             class="w-full h-[320px] md:h-[420px] object-cover rounded-brand shadow-lg"
             onerror="this.onerror=null;this.src='https://placehold.co/900x550/2F6B3F/F5F1E8?text={{ urlencode($pengetahuan->judul) }}'">
        @endif

        <div class="mt-8">
            <p class="text-gray-text leading-relaxed text-justify text-base md:text-lg">
                {{ $pengetahuan->ringkasan }}
            </p>
        </div>

        <div class="mt-12 pt-8 border-t border-gray-100">
            <a href="{{ route('media.pengetahuan') }}" class="inline-flex items-center gap-2 text-primary-green font-heading font-semibold hover:text-dark-green transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Pengetahuan
            </a>
        </div>

    </div>
</section>
@endsection