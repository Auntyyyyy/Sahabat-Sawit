@extends('layouts.app')

@section('title', $product->name . ' — PT Sahabat Sawit')

@section('content')
<section class="relative bg-dark-green text-white py-16 overflow-hidden hero-slideshow-section">
    @if(!empty($product->hero_images))
        <div class="hero-slideshow">
            @foreach($product->hero_images as $index => $img)
            <div class="hero-slide" style="background-image: url('{{ asset('storage/' . $img) }}'); animation-delay: {{ $index * 5 }}s;"></div>
            @endforeach
        </div>
    @endif

    <div class="absolute inset-0 bg-dark-green/70"></div>

    <div class="relative max-w-5xl mx-auto px-6 lg:px-8">
        <a href="{{ route('products') }}" class="inline-flex items-center gap-2 text-light-green text-sm hover:underline">
            &larr; Kembali ke Produk
        </a>
        <h1 class="mt-4 font-heading font-bold text-3xl md:text-4xl">{{ $product->name }}</h1>
    </div>
</section>

<section class="py-16 md:py-20 bg-white relative">
    <div class="max-w-5xl mx-auto px-6 lg:px-8">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
            {{-- Gambar utama produk --}}
            <img src="{{ $product->image ? asset('storage/' . $product->image) : '' }}" alt="{{ $product->name }}"
                 class="rounded-brand shadow-lg w-full h-[380px] object-cover"
                 onerror="this.src='https://placehold.co/600x500/2E7D32/F5F1E8?text={{ urlencode($product->name) }}'">

            <div>
                <h2 class="font-heading font-bold text-xl text-dark-green mb-4">Deskripsi</h2>
                <p class="text-gray-text leading-relaxed text-justify product-detail-desc">{{ $product->detail }}</p>

                @if(!empty($product->manfaat))
                <div class="mt-8">
                    <h3 class="font-heading font-bold text-xl text-dark-green mb-4">Manfaat</h3>
                    <ul class="benefit-list">
                        @foreach($product->manfaat as $manfaat)
                        <li class="benefit-item">
                            <span class="benefit-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </span>
                            <span>{{ $manfaat }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if(!empty($product->spesifikasi))
                <div class="mt-8">
                    <h3 class="font-heading font-bold text-xl text-dark-green mb-4">Spesifikasi</h3>
                    <table class="w-full text-sm">
                        <tbody>
                            @foreach($product->spesifikasi as $key => $val)
                            <tr class="border-b border-gray-100">
                                <td class="py-3 text-gray-text">{{ $key }}</td>
                                <td class="py-3 text-dark-green font-semibold text-right">{{ $val }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>

        <!-- Navigasi Panah Sebelumnya / Selanjutnya -->
        <div class="product-nav mt-14 pt-8 border-t border-gray-100">
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
                    <div class="gallery-item">
                        <img src="{{ asset('storage/' . $item->file_path) }}" alt="{{ $product->name }}"
                             onerror="this.src='https://placehold.co/500x500/2E7D32/F5F1E8?text={{ urlencode($product->name) }}'">
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
@endsection