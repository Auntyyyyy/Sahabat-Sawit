@extends('layouts.app')

@section('title', 'Perkebunan Kami — PT Sahabat Sawit')

@section('content')
<section class="relative bg-dark-green text-white py-16 lg:py-20 bg-cover bg-center"
         style="background-image: url('{{ asset('images/sawit.jpeg') }}');">
    <div class="absolute inset-0 bg-dark-green/80"></div>

    <div class="relative max-w-5xl mx-auto px-6 lg:px-8 text-center">
    <div class="hero-top-row">
        <nav aria-label="Breadcrumb" class="breadcrumb-pill">
            <a href="{{ route('home') }}" class="breadcrumb-link" aria-label="Home">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 9.75L12 3l9 6.75V21a.75.75 0 01-.75.75H15a.75.75 0 01-.75-.75v-4.5a.75.75 0 00-.75-.75h-3a.75.75 0 00-.75.75V21a.75.75 0 01-.75.75H3.75A.75.75 0 013 21V9.75z"/>
                </svg>
            </a>
            <svg class="breadcrumb-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="breadcrumb-current text-light-green">Perkebunan Kami</span>
        </nav>
    </div>
    <span class="font-heading font-semibold text-light-green uppercase text-sm tracking-wide">Perkebunan Kami</span>
    <h1 class="mt-3 font-heading font-bold text-3xl md:text-5xl">DARI BUMI ROKAN HILIR</h1>
    <p class="mt-4 text-white/75 max-w-2xl mx-auto leading-relaxed">
        Bertumbuh bersama dari sumber terbaik.
    </p>
</div>
</section>

<section class="py-16 lg:py-20 bg-white">
    <div class="max-w-6xl mx-auto px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center mb-14">
            <div class="bg-cream rounded-brand p-6">
                <p class="text-xs uppercase text-gray-text tracking-wide">Lokasi</p>
                <p class="mt-2 font-heading font-bold text-dark-green">{{ $stats['lokasi'] }}</p>
            </div>
            <div class="bg-cream rounded-brand p-6">
                <p class="text-xs uppercase text-gray-text tracking-wide">Luas Area</p>
                <p class="mt-2 font-heading font-bold text-dark-green">{{ $stats['luas_area'] }}</p>
            </div>
            <div class="bg-cream rounded-brand p-6">
                <p class="text-xs uppercase text-gray-text tracking-wide">Kemitraan</p>
                <p class="mt-2 font-heading font-bold text-dark-green">{{ $stats['kemitraan'] }}</p>
            </div>
            <div class="bg-cream rounded-brand p-6">
                <p class="text-xs uppercase text-gray-text tracking-wide">Diusahakan Sendiri</p>
                <p class="mt-2 font-heading font-bold text-dark-green">{{ $stats['diusahakan_sendiri'] }}</p>
            </div>
        </div>

        <div class="map-frame shadow-2xl">
            <img src="{{ asset('images/bahanbaku.png') }}"
                alt="Peta Lokasi Perkebunan PT Sahabat Sawit di Rokan Hilir, Riau"
                class="map-frame-img"
                onerror="this.src='https://placehold.co/1200x450/6B8E23/F5F1E8?text=Peta+Lokasi+Perkebunan'">
        </div>
        <p class="mt-4 text-xs text-gray-text">*SK : 903/MENLHK/SETJEN/PLA.2/12/2016 RIAU.</p>
    </div>
</section>

<section class="py-16 lg:py-20 bg-cream">
    <div class="max-w-5xl mx-auto px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-14">
            <span class="font-heading font-semibold text-primary-green uppercase text-sm tracking-wide">Kemitraan</span>
            <h2 class="mt-3 font-heading font-bold text-2xl md:text-3xl text-dark-green">JEJAK KEMITRAAN</h2>
            <p class="mt-4 text-gray-text leading-relaxed">
                Mengabadikan perjalanan, kerja keras, dan kebersamaan bersama para petani mitra.                    </p>
        </div>

        <div class="mitra-carousel" id="mitraCarousel">
            <div class="mitra-carousel-track" id="mitraTrack">
                @foreach($mitra_photos as $photo)
                <div class="mitra-slide">
                    <img src="{{ asset('images/' . $photo['src']) }}"
                         alt="{{ $photo['title'] ?? 'Perkebunan mitra PT Sahabat Sawit' }}"
                         class="mitra-slide-img cursor-zoom-in"
                         onclick="mitraOpenLightbox(this.src, this.alt)"
                         onerror="this.src='https://placehold.co/900x600/6B8E23/F5F1E8?text=Kebun+Mitra'">
                </div>
                @endforeach
            </div>

            @if(count($mitra_photos) > 1)
            <button type="button" class="mitra-nav mitra-nav-prev" onclick="mitraGoTo(-1)" aria-label="Sebelumnya">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            <button type="button" class="mitra-nav mitra-nav-next" onclick="mitraGoTo(1)" aria-label="Berikutnya">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            @endif
        </div>

        <!-- Judul & keterangan yang berubah mengikuti gambar aktif -->
        <div class="mitra-caption" id="mitraCaption">
            <h3 class="mitra-caption-title" id="mitraCaptionTitle">
                {{ $mitra_photos[0]['title'] ?? '' }}
            </h3>
            <p class="mitra-caption-desc" id="mitraCaptionDesc">
                {{ $mitra_photos[0]['desc'] ?? '' }}
            </p>
        </div>

        @if(count($mitra_photos) > 1)
        <div class="mitra-dots" id="mitraDots">
            @foreach($mitra_photos as $i => $photo)
            <button type="button" class="mitra-dot" onclick="mitraGoToIndex({{ $i }})" aria-label="Foto {{ $i + 1 }}"></button>
            @endforeach
        </div>
        @endif
    </div>
</section>

<!-- Lightbox untuk gambar mitra yang diklik -->
<div id="mitraLightbox" class="mitra-lightbox" onclick="mitraCloseLightbox()">
    <button type="button" class="mitra-lightbox-close" onclick="mitraCloseLightbox()" aria-label="Tutup">&times;</button>
    <img id="mitraLightboxImg" src="" alt="" class="mitra-lightbox-img" onclick="event.stopPropagation()">
</div>

@php
$mitraCaptions = array_map(fn($p) => [
    'title' => $p['title'] ?? '',
    'desc'  => $p['desc'] ?? '',
], $mitra_photos);
@endphp

<style>
.mitra-lightbox {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.85);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    padding: 1.5rem;
}
.mitra-lightbox.is-open { display: flex; }
.mitra-lightbox-img {
    max-width: 100%;
    max-height: 90vh;
    border-radius: 8px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.5);
}
.mitra-lightbox-close {
    position: absolute;
    top: 1.5rem;
    right: 1.5rem;
    color: white;
    font-size: 2rem;
    line-height: 1;
    background: none;
    border: none;
    cursor: pointer;
}
.cursor-zoom-in { cursor: zoom-in; }
</style>

<script>
(function () {
    const carousel = document.getElementById('mitraCarousel');
    const track = document.getElementById('mitraTrack');
    const dotsWrap = document.getElementById('mitraDots');
    const captionTitle = document.getElementById('mitraCaptionTitle');
    const captionDesc = document.getElementById('mitraCaptionDesc');
    const captionWrap = document.getElementById('mitraCaption');
    if (!track) return;

    // Data judul & deskripsi tiap slide, dikirim dari Blade
    const captions = @json($mitraCaptions);

    const slideCount = track.children.length;
    let current = 0;

    function updateCaption() {
        if (!captionWrap || !captions[current]) return;
        captionWrap.classList.add('is-fading');
        setTimeout(() => {
            captionTitle.textContent = captions[current].title;
            captionDesc.textContent = captions[current].desc;
            captionWrap.classList.remove('is-fading');
        }, 150);
    }

    function render() {
        track.style.transform = `translateX(-${current * 100}%)`;
        if (dotsWrap) {
            [...dotsWrap.children].forEach((dot, i) => {
                dot.classList.toggle('is-active', i === current);
            });
        }
        updateCaption();
    }

    window.mitraGoTo = function (dir) {
        current = (current + dir + slideCount) % slideCount;
        render();
    };

    window.mitraGoToIndex = function (i) {
        current = i;
        render();
    };

    render();

    // --- Dukungan swipe/touch untuk HP ---
    let startX = 0;
    let deltaX = 0;
    let isDragging = false;
    const swipeThreshold = 40;

    carousel.addEventListener('touchstart', function (e) {
        if (slideCount <= 1) return;
        startX = e.touches[0].clientX;
        isDragging = true;
        track.style.transition = 'none';
    }, { passive: true });

    carousel.addEventListener('touchmove', function (e) {
        if (!isDragging) return;
        deltaX = e.touches[0].clientX - startX;
        const percent = (deltaX / carousel.offsetWidth) * 100;
        track.style.transform = `translateX(calc(-${current * 100}% + ${percent}%))`;
    }, { passive: true });

    carousel.addEventListener('touchend', function () {
        if (!isDragging) return;
        isDragging = false;
        track.style.transition = 'transform 0.4s ease';

        if (Math.abs(deltaX) > swipeThreshold) {
            if (deltaX < 0) {
                mitraGoTo(1);
            } else {
                mitraGoTo(-1);
            }
        } else {
            render();
        }
        deltaX = 0;
    });

    // --- Lightbox untuk gambar mitra ---
    window.mitraOpenLightbox = function (src, alt) {
        const lightbox = document.getElementById('mitraLightbox');
        const img = document.getElementById('mitraLightboxImg');
        img.src = src;
        img.alt = alt;
        lightbox.classList.add('is-open');
        document.body.style.overflow = 'hidden';
    };

    window.mitraCloseLightbox = function () {
        document.getElementById('mitraLightbox').classList.remove('is-open');
        document.body.style.overflow = '';
    };

    // Tutup lightbox dengan tombol Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            mitraCloseLightbox();
        }
    });
})();
</script>
@endsection