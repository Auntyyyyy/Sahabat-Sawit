@extends('layouts.app')

@section('title', 'Berita & Galeri Kegiatan — PT Sahabat Sawit')

@section('content')

{{-- Hero: pola sama dengan halaman Pengetahuan — satu foto latar penuh +
     gradasi gelap + icon badge di atas judul. Foto latar otomatis diambil dari
     berita terbaru yang punya gambar. --}}
@php
    $heroPhoto = $beritas->first(fn ($b) => $b->gambar);
@endphp

<section class="relative min-h-[50vh] flex items-center bg-dark-green text-white overflow-hidden">

    <div class="absolute inset-0">
        @if($heroPhoto)
        <img src="{{ asset('storage/' . $heroPhoto->gambar) }}"
             alt=""
             class="w-full h-full object-cover"
             onerror="this.style.display='none'">
        @endif
    </div>
    <div class="absolute inset-0 bg-gradient-to-b from-dark-green/90 via-dark-green/80 to-dark-green/95"></div>

    <div class="relative w-full max-w-5xl mx-auto px-6 lg:px-8 text-center pt-32 pb-16">
        <div class="flex items-center justify-center gap-2 text-sm text-white/60 mb-5">
            <a href="{{ url('/') }}" class="hover:text-white transition-colors">Beranda</a>
            <span>/</span>
            <span class="text-white/90">Berita & Galeri Kegiatan</span>
        </div>

        <!-- Icon badge -->
        <div class="flex justify-center mb-4">
            <div class="sustain-icon-badge">
                <i class="bi bi-newspaper text-xl"></i>
            </div>
        </div>

        <span class="block font-heading font-semibold text-light-green uppercase text-sm tracking-wide">Media</span>
        <h1 class="mt-3 font-heading font-bold text-3xl md:text-5xl">Berita & Galeri Kegiatan</h1>
        <p class="mt-4 text-white/75 max-w-2xl mx-auto leading-relaxed">
            Dokumentasi keseharian PT. Sahabat Sawit Rokan Sejahtera — mulai dari kunjungan kerja,
            rapat internal, kegiatan di mess karyawan, hingga momen kebersamaan lainnya.
        </p>

        {{-- Badge dekoratif: menandakan ragam jenis kegiatan, bukan filter --}}
        <div class="flex flex-wrap justify-center gap-2.5 mt-7">
            <span class="berita-hero-badge"><i class="bi bi-people"></i> Kunjungan</span>
            <span class="berita-hero-badge"><i class="bi bi-chat-square-text"></i> Rapat & Diskusi</span>
            <span class="berita-hero-badge"><i class="bi bi-house-heart"></i> Kegiatan Mess</span>
            <span class="berita-hero-badge"><i class="bi bi-camera"></i> Momen Kebersamaan</span>
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
</style>

<section class="py-16 lg:py-20 bg-white">
    <div class="max-w-6xl mx-auto px-6 lg:px-8">

        @if($beritas->isEmpty())
            <div class="text-center py-16">
                <i class="bi bi-images fs-1 text-muted d-block mb-3"></i>
                <p class="text-gray-text">Belum ada dokumentasi kegiatan yang dipublikasikan.</p>
            </div>
        @else
            {{-- Semua berita tampil rata dalam satu grid, tanpa sorotan "terbaru" --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 scroll-reveal-group">
                @foreach($beritas as $item)
                <a href="{{ route('media.berita.show', $item->slug) }}"
                   class="berita-card group block rounded-brand overflow-hidden bg-white border border-dark-green/10 shadow-sm hover:shadow-xl transition-shadow duration-300 scroll-reveal">
                    <div class="aspect-[4/3] overflow-hidden bg-cream">
                        <img src="{{ $item->gambar ? asset('storage/' . $item->gambar) : 'https://placehold.co/600x450/1F5F3B/F5F1E8?text=' . urlencode($item->judul) }}"
                             alt="{{ $item->judul }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                             onerror="this.onerror=null;this.src='https://placehold.co/600x450/1F5F3B/F5F1E8?text={{ urlencode($item->judul) }}'">
                    </div>
                    <div class="p-5">
                        {{-- DIUBAH: tanggal + jumlah dilihat dalam satu baris --}}
                        <div style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:.25rem .75rem;">
                            <span class="inline-flex items-center gap-1.5 text-xs font-heading font-semibold text-primary-green uppercase tracking-wide">
                                <i class="bi bi-calendar3"></i> {{ $item->tanggal->translatedFormat('d F Y') }}
                            </span>
                            <span class="inline-flex items-center gap-1.5 text-xs text-gray-text">
                                <i class="bi bi-eye"></i> {{ number_format($item->views ?? 0, 0, ',', '.') }}x dilihat
                            </span>
                        </div>
                        <h3 class="mt-2 font-heading font-bold text-dark-green leading-snug group-hover:text-primary-green transition-colors line-clamp-2">
                            {{ $item->judul }}
                        </h3>
                        <p class="mt-2 text-sm text-gray-text leading-relaxed line-clamp-3">
                            {{ $item->deskripsi_singkat }}
                        </p>
                    </div>
                </a>
                @endforeach
            </div>

            <div class="mt-14">
                {{ $beritas->links() }}
            </div>
        @endif

    </div>
</section>
@endsection