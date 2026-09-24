@extends('layouts.app')

@section('title', 'Berita & Galeri Kegiatan — PT Sahabat Sawit')

@section('content')
<section class="berita-hero relative min-h-[55vh] flex items-center bg-dark-green text-white overflow-hidden">
    {{-- Pola titik halus, konsisten dengan section Struktur Organisasi --}}
    <div class="absolute inset-0 pointer-events-none"
         style="background-image: radial-gradient(rgba(255,255,255,0.06) 1px, transparent 1px); background-size: 26px 26px;"></div>
    <div class="absolute inset-0 hero-overlay"></div>

    <div class="relative w-full max-w-6xl mx-auto px-6 lg:px-8 py-20">
        <div class="grid grid-cols-1 lg:grid-cols-[1.15fr_0.85fr] gap-12 items-center">

            {{-- Teks --}}
            <div>
                <div class="flex items-center gap-2 text-sm text-white/60 mb-4">
                    <a href="{{ url('/') }}" class="hover:text-white transition-colors">Beranda</a>
                    <span>/</span>
                    <span class="text-white/90">Berita & Galeri Kegiatan</span>
                </div>

                <span class="block font-heading font-semibold text-light-green uppercase text-sm tracking-wide">Media</span>
                <h1 class="mt-3 font-heading font-bold text-3xl md:text-5xl leading-tight">Berita & Galeri Kegiatan</h1>
                <p class="mt-4 text-white/75 max-w-xl leading-relaxed">
                    Dokumentasi keseharian PT. Sahabat Sawit Rokan Sejahtera — mulai dari kunjungan kerja,
                    rapat internal, kegiatan di mess karyawan, hingga momen kebersamaan lainnya.
                </p>

                {{-- Badge dekoratif: menandakan ragam jenis kegiatan, bukan filter --}}
                <div class="flex flex-wrap gap-2.5 mt-7">
                    <span class="berita-hero-badge"><i class="bi bi-people"></i> Kunjungan</span>
                    <span class="berita-hero-badge"><i class="bi bi-chat-square-text"></i> Rapat & Diskusi</span>
                    <span class="berita-hero-badge"><i class="bi bi-house-heart"></i> Kegiatan Mess</span>
                    <span class="berita-hero-badge"><i class="bi bi-camera"></i> Momen Kebersamaan</span>
                </div>
            </div>

            {{-- Kolase foto terbaru — otomatis dari berita yang sudah ada gambarnya --}}
            @php $collage = $beritas->filter(fn($b) => $b->gambar)->take(4); @endphp
            @if($collage->isNotEmpty())
            <div class="berita-hero-collage grid grid-cols-2 gap-3">
                @foreach($collage as $i => $photo)
                <div class="berita-hero-collage-item rounded-brand overflow-hidden shadow-xl {{ $i === 0 ? 'col-span-2 aspect-[16/9]' : 'aspect-square' }}">
                    <img src="{{ asset('storage/' . $photo->gambar) }}" alt="{{ $photo->judul }}" class="w-full h-full object-cover">
                </div>
                @endforeach
            </div>
            @endif

        </div>
    </div>
</section>

<style>
    .berita-hero-badge{
        display:inline-flex; align-items:center; gap:.4rem;
        padding:.4rem .85rem;
        border-radius:9999px;
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.18);
        font-size:.8rem;
        font-weight:500;
        color: rgba(255,255,255,0.85);
    }
    .berita-hero-collage-item{ background: rgba(255,255,255,0.05); }
</style>

<section class="py-16 lg:py-20 bg-white">
    <div class="max-w-6xl mx-auto px-6 lg:px-8">

        @if($beritas->isEmpty())
            <div class="text-center py-16">
                <i class="bi bi-images fs-1 text-muted d-block mb-3"></i>
                <p class="text-gray-text">Belum ada dokumentasi kegiatan yang dipublikasikan.</p>
            </div>
        @else
            @php
                // Item pertama (terbaru) ditampilkan besar sebagai sorotan,
                // sisanya jadi grid kartu biasa di bawahnya.
                $featured = $beritas->first();
                $rest = $beritas->skip(1);
            @endphp

            <!-- ===== Sorotan Terbaru ===== -->
            <a href="{{ route('media.berita.show', $featured->slug) }}"
               class="berita-featured group grid grid-cols-1 lg:grid-cols-2 gap-0 rounded-brand overflow-hidden bg-white shadow-lg hover:shadow-2xl transition-shadow duration-300 mb-14">
                <div class="berita-featured-img aspect-[16/10] lg:aspect-auto overflow-hidden bg-cream">
                    <img src="{{ $featured->gambar ? asset('storage/' . $featured->gambar) : 'https://placehold.co/900x600/1F5F3B/F5F1E8?text=' . urlencode($featured->judul) }}"
                         alt="{{ $featured->judul }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                         onerror="this.onerror=null;this.src='https://placehold.co/900x600/1F5F3B/F5F1E8?text={{ urlencode($featured->judul) }}'">
                </div>
                <div class="flex flex-col justify-center p-8 lg:p-10">
                    <span class="inline-flex items-center gap-2 text-xs font-heading font-semibold text-primary-green uppercase tracking-wide">
                        <span class="w-2 h-2 rounded-full bg-primary-green"></span>
                        Terbaru &middot; {{ $featured->tanggal->translatedFormat('d F Y') }}
                    </span>
                    <h2 class="mt-4 font-heading font-bold text-2xl md:text-3xl text-dark-green leading-snug group-hover:text-primary-green transition-colors">
                        {{ $featured->judul }}
                    </h2>
                    <p class="mt-4 text-gray-text leading-relaxed line-clamp-3">
                        {{ $featured->deskripsi_singkat }}
                    </p>
                    <span class="mt-6 inline-flex items-center gap-2 font-heading font-semibold text-dark-green group-hover:gap-3 transition-all">
                        Baca Selengkapnya
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </span>
                </div>
            </a>

            @if($rest->isNotEmpty())
            <!-- ===== Grid Lainnya ===== -->
            <div class="flex items-center justify-between mb-8">
                <h3 class="font-heading font-bold text-xl text-dark-green">Dokumentasi Lainnya</h3>
                <span class="hidden sm:block h-px flex-1 bg-gray-100 ml-6"></span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($rest as $item)
                <a href="{{ route('media.berita.show', $item->slug) }}" class="berita-card group block rounded-brand overflow-hidden bg-white shadow-md hover:shadow-xl transition-shadow duration-300">
                    <div class="aspect-[4/3] overflow-hidden bg-cream">
                        <img src="{{ $item->gambar ? asset('storage/' . $item->gambar) : 'https://placehold.co/600x450/1F5F3B/F5F1E8?text=' . urlencode($item->judul) }}"
                             alt="{{ $item->judul }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                             onerror="this.onerror=null;this.src='https://placehold.co/600x450/1F5F3B/F5F1E8?text={{ urlencode($item->judul) }}'">
                    </div>
                    <div class="p-5">
                        <span class="text-xs font-heading font-semibold text-primary-green uppercase tracking-wide">
                            {{ $item->tanggal->translatedFormat('d F Y') }}
                        </span>
                        <h3 class="mt-2 font-heading font-bold text-dark-green leading-snug group-hover:text-primary-green transition-colors line-clamp-2">
                            {{ $item->judul }}
                        </h3>
                        <p class="mt-2 text-sm text-gray-text leading-relaxed line-clamp-2">
                            {{ $item->deskripsi_singkat }}
                        </p>
                        <span class="mt-4 inline-flex items-center gap-1.5 text-sm font-heading font-semibold text-dark-green group-hover:gap-2.5 transition-all">
                            Baca Selengkapnya
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </span>
                    </div>
                </a>
                @endforeach
            </div>
            @endif

            <div class="mt-14">
                {{ $beritas->links() }}
            </div>
        @endif

    </div>
</section>
@endsection