@extends('layouts.app')

@section('title', $product->name . ' — PT Sahabat Sawit')

@section('content')
<section class="relative bg-dark-green text-white overflow-hidden hero-slideshow-section hero-detail-fullscreen">
    @if(!empty($product->hero_images))
        <div class="hero-slideshow">
            @foreach($product->hero_images as $index => $img)
            <div class="hero-slide" style="background-image: url('{{ asset('storage/' . $img) }}'); animation-delay: {{ $index * 5 }}s;"></div>
            @endforeach
        </div>
    @endif

    <div class="absolute inset-0 bg-dark-green/70"></div>

    <div class="relative max-w-5xl mx-auto px-6 lg:px-8 w-full">
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
                <a href="{{ route('products') }}" class="breadcrumb-link-text">Produk</a>
                <svg class="breadcrumb-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="breadcrumb-current text-light-green">{{ $product->name }}</span>
            </div>
        </nav>

        <span class="font-heading font-semibold text-light-green uppercase text-sm tracking-wide">Produk Kami</span>
        <h1 class="mt-2 font-heading font-bold text-3xl md:text-4xl">{{ $product->name }}</h1>
    </div>
</section>

<section class="py-16 md:py-20 bg-white relative">
    <div class="max-w-6xl mx-auto px-6 lg:px-8">

        {{-- ===== Bagian 1: Badge + Deskripsi (teks kiri, gambar kanan) — mengikuti referensi ===== --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            <div>
                <h2 class="font-heading font-bold text-2xl md:text-3xl text-dark-green mb-5">Detail Produk</h2>

                @if(!empty($product->category))
                <span class="inline-block mb-5 px-4 py-1.5 rounded-full border border-secondary-green text-secondary-green font-heading font-semibold text-sm">
                    {{ $product->category }}
                </span>
                @endif

                <p class="text-gray-text leading-relaxed text-justify text-base md:text-lg product-detail-desc">
                    {{ $product->detail }}
                </p>
            </div>

            <div class="detail-image-frame" onclick="productOpenLightbox(this.querySelector('img').src, '{{ $product->name }}')">
                <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/700x600/2E7D32/F5F1E8?text=' . urlencode($product->name) }}"
                     alt="{{ $product->name }}"
                     class="rounded-2xl shadow-xl w-full h-[420px] md:h-[480px] object-cover"
                     onerror="this.onerror=null;this.src='https://placehold.co/700x600/2E7D32/F5F1E8?text={{ urlencode($product->name) }}'">
                <span class="detail-image-zoom" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16zM11 8v6M8 11h6" />
                    </svg>
                </span>
            </div>
        </div>

        {{-- ===== Bagian 2: Manfaat — layout alternating, gambar kiri / teks kanan ala referensi ===== --}}
        @if(!empty($product->manfaat))
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center mt-20 md:mt-24">
            <div class="order-2 lg:order-1">
                <img src="{{ $product->media->first() ? asset('storage/' . $product->media->first()->file_path) : ($product->image ? asset('storage/' . $product->image) : 'https://placehold.co/700x600/2E7D32/F5F1E8?text=' . urlencode($product->name)) }}"
                     alt="Manfaat {{ $product->name }}"
                     class="rounded-2xl shadow-xl w-full h-[380px] md:h-[440px] object-cover"
                     onerror="this.onerror=null;this.src='https://placehold.co/700x600/2E7D32/F5F1E8?text={{ urlencode($product->name) }}'">
            </div>

            <div class="order-1 lg:order-2">
                <h3 class="font-heading font-bold text-xl md:text-2xl text-dark-green mb-6">Manfaat</h3>
                <div class="flex flex-col gap-4">
                    @foreach($product->manfaat as $manfaat)
                    <div class="flex items-start gap-3">
                        <span class="flex-shrink-0 w-6 h-6 rounded-full bg-secondary-green/10 text-secondary-green flex items-center justify-center mt-0.5">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                        <p class="text-gray-text leading-relaxed">{{ $manfaat }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- ===== Bagian 3: Spesifikasi — tabel bersih, full width ===== --}}
        @if(!empty($product->spesifikasi))
        <div class="mt-20 md:mt-24">
            <h3 class="font-heading font-bold text-xl md:text-2xl text-dark-green mb-6 text-center">Spesifikasi</h3>
            <div class="spec-table max-w-3xl mx-auto">
                @foreach($product->spesifikasi as $key => $val)
                <div class="spec-row">
                    <span class="spec-label">{{ $key }}</span>
                    <span class="spec-value">{{ $val }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- CTA — ajakan kontak untuk produk ini -->
        <div class="product-cta mt-20">
            <div class="product-cta-text">
                <h3 class="font-heading font-bold text-xl md:text-2xl text-dark-green">
                    Tertarik dengan {{ $product->name }}?
                </h3>
                <p class="mt-2 text-gray-text">
                    Hubungi tim kami untuk informasi penawaran harga, kapasitas pengiriman, dan kerja sama.
                </p>
            </div>
            <a href="{{ route('contact') }}" class="product-cta-btn">
                Hubungi Kami
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </a>
        </div>

        <!-- Navigasi Panah Sebelumnya / Selanjutnya -->
        <div class="product-nav mt-10 pt-8 border-t border-gray-100">
            <a href="{{ route('products.show', $prevProduct->slug) }}" class="product-nav-btn product-nav-prev group">
                <span class="product-nav-arrow">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                </span>
                <span class="text-left">
                    <span class="product-nav-label">Sebelumnya</span>
                    <span class="product-nav-name">{{ $prevProduct->name }}</span>
                </span>
            </a>

            <a href="{{ route('products.show', $nextProduct->slug) }}" class="product-nav-btn product-nav-next group">
                <span class="text-right">
                    <span class="product-nav-label">Selanjutnya</span>
                    <span class="product-nav-name">{{ $nextProduct->name }}</span>
                </span>
                <span class="product-nav-arrow">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </span>
            </a>
        </div>

        <!-- Galeri Foto & Video Produk Ini (dari tabel product_media, diinput admin) -->
        @if($product->media->isNotEmpty())
        <div class="mt-14 pt-8 border-t border-gray-100">
            <h3 class="font-heading font-bold text-xl text-dark-green mb-6">Galeri {{ $product->name }}</h3>
            <div class="product-gallery-grid">
                @foreach($product->media as $item)

                    @if($item->type === 'image')
                    <div class="gallery-item" onclick="productOpenLightbox('{{ asset('storage/' . $item->file_path) }}', '{{ $product->name }}')">
                        <img src="{{ asset('storage/' . $item->file_path) }}" alt="{{ $product->name }}"
                             onerror="this.onerror=null;this.src='https://placehold.co/500x500/2E7D32/F5F1E8?text={{ urlencode($product->name) }}'">
                    </div>

                    @elseif($item->type === 'video')
                    <div class="gallery-item gallery-video">
                        <video controls preload="metadata">
                            <source src="{{ asset('storage/' . $item->file_path) }}" type="video/mp4">
                            Browser Anda tidak mendukung pemutaran video.
                        </video>
                    </div>
                    @endif

                @endforeach
            </div>
        </div>
        @endif

    </div>
</section>

<!-- Lightbox untuk gambar utama & galeri produk -->
<div id="productLightbox" class="product-lightbox" onclick="productCloseLightbox()">
    <button type="button" class="product-lightbox-close" onclick="productCloseLightbox()" aria-label="Tutup">&times;</button>
    <img id="productLightboxImg" src="" alt="" class="product-lightbox-img" onclick="event.stopPropagation()">
</div>

<script>
(function () {
    window.productOpenLightbox = function (src, alt) {
        const lightbox = document.getElementById('productLightbox');
        const img = document.getElementById('productLightboxImg');
        img.src = src;
        img.alt = alt;
        lightbox.classList.add('is-open');
        document.body.style.overflow = 'hidden';
    };

    window.productCloseLightbox = function () {
        document.getElementById('productLightbox').classList.remove('is-open');
        document.body.style.overflow = '';
    };

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            productCloseLightbox();
        }
    });
})();
</script>
@endsection