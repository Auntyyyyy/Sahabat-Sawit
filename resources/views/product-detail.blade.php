@extends('layouts.app')

@section('title', $product->name . ' — PT Sahabat Sawit')

@section('content')

@php
    // Foto tetap untuk hero & penutup (ganti nama file di sini; file ada di public/images).
    $fotoHero = 'sawittangan.jpg';
    $fotoCta  = 'sawitangkong.jpg';

    $gambarUtama   = $product->image ? asset('storage/' . $product->image) : null;
    $mediaGambar   = $product->media->firstWhere('type', 'image');
    $gambarManfaat = $mediaGambar ? asset('storage/' . $mediaGambar->file_path) : $gambarUtama;
@endphp

<style>
    /* Detail produk — gaya disamakan dengan halaman lain (gelap kehijauan, suasana bergantian). Class berawalan "pd-". */
    @import url('https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,600;12..96,800&display=swap');

    .pd {
        --hijau: #16321f; --hijau-muda: #2f5a3a; --emas: #e3a72f; --krem: #f2f4ec;
        --gutter: max(clamp(1.25rem, 5vw, 3.5rem), calc((100vw - 80rem) / 2));
        font-family: "Bricolage Grotesque", system-ui, -apple-system, "Segoe UI", sans-serif;
        background: var(--hijau); color: var(--krem); line-height: 1.6;
    }
    .pd *, .pd *::before, .pd *::after { box-sizing: border-box; }
    .pd a { color: inherit; text-decoration: none; }
    .pd a:focus-visible, .pd button:focus-visible { outline: 3px solid var(--krem); outline-offset: 3px; }

    /* Hero berfoto */
    .pd-hero { position: relative; isolation: isolate; overflow: hidden; display: flex; align-items: flex-end; min-height: min(88vh, 54rem); background: linear-gradient(145deg, var(--hijau-muda), var(--hijau)); }
    .pd-hero::after { content: ''; position: absolute; inset: 0; z-index: -1; background: linear-gradient(90deg, rgba(22,50,31,.92) 0%, rgba(22,50,31,.6) 50%, rgba(22,50,31,.2) 100%), linear-gradient(0deg, rgba(22,50,31,.85) 0%, transparent 45%); }
    .pd-bg { position: absolute; inset: 0; z-index: -2; width: 100%; height: 100%; object-fit: cover; }
    .pd-hero-inner { width: 100%; padding: clamp(8rem, 18vh, 11rem) var(--gutter) clamp(3rem, 8vh, 5rem); }
    .pd-crumb { display: flex; flex-wrap: wrap; align-items: center; gap: .5rem; font-size: .9rem; color: rgba(242,244,236,.6); }
    .pd-crumb a:hover { color: var(--krem); }
    .pd-crumb [aria-current] { color: var(--krem); }
    .pd-title { margin-top: 1.5rem; max-width: 14ch; font-size: clamp(2.6rem, 8vw, 6.5rem); font-weight: 800; line-height: 1.02; letter-spacing: -.04em; }

    /* Section */
    .pd-sec { padding: clamp(4rem, 10vh, 7rem) var(--gutter); }
    .pd-hero + .pd-sec, .pd-sec + .pd-sec { border-top: 1px solid rgba(242,244,236,.12); }
    .pd-label { font-size: .95rem; color: rgba(242,244,236,.6); }
    .pd-h2 { margin-top: .5rem; max-width: 20ch; font-size: clamp(1.8rem, 4vw, 3rem); font-weight: 800; line-height: 1.05; letter-spacing: -.03em; }
    .pd-split { display: grid; grid-template-columns: 1fr; gap: 3rem; align-items: center; }
    @media (min-width: 900px) { .pd-split { grid-template-columns: 1fr 1fr; gap: 5rem; } .pd-split-top { align-items: start; } .pd-sticky { position: sticky; top: 7rem; } }
    .pd-link { display: inline-flex; align-items: center; gap: .55rem; margin-top: 2rem; font-weight: 600; font-size: .95rem; border-bottom: 2px solid var(--emas); padding-bottom: .2rem; }
    .pd-link:hover { color: var(--emas); }

    /* Detail produk */
    .pd-cat { display: inline-block; margin-top: 1.25rem; padding: .2rem .85rem; border: 1px solid rgba(242,244,236,.35); border-radius: 99px; font-size: .85rem; color: rgba(242,244,236,.85); }
    .pd-detail { margin-top: 1.5rem; max-width: 40rem; font-size: 1.05rem; color: rgba(242,244,236,.8); text-align: justify; text-justify: inter-word; }
    .pd-zoombtn { position: relative; display: block; width: 100%; padding: 0; border: 0; background: none; cursor: zoom-in; }
    .pd-photo { display: block; width: 100%; height: clamp(300px, 42vw, 520px); object-fit: cover; border-radius: .25rem; background: linear-gradient(145deg, var(--hijau-muda), var(--hijau)); }
    .pd-zoomhint { position: absolute; right: 1rem; bottom: 1rem; width: 2.5rem; height: 2.5rem; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(242,244,236,.5); border-radius: 50%; background: rgba(22,50,31,.6); transition: background .2s, color .2s; }
    .pd-zoombtn:hover .pd-zoomhint { background: var(--emas); border-color: var(--emas); color: var(--hijau); }
    .pd-zoomhint svg { width: 1.1rem; height: 1.1rem; }

    /* Manfaat (section terang) */
    .pd-light { background: var(--krem); color: var(--hijau); }
    .pd-light .pd-label { color: rgba(22,50,31,.6); }
    .pd-benefits { list-style: none; margin: 2rem 0 0; padding: 0; }
    .pd-benefit { display: grid; grid-template-columns: 2rem 1fr; gap: 1rem; padding: 1.1rem 0; border-top: 1px solid rgba(22,50,31,.2); font-size: 1.05rem; color: rgba(22,50,31,.85); }
    .pd-benefit:last-child { border-bottom: 1px solid rgba(22,50,31,.2); }
    .pd-benefit svg { width: 1.2rem; height: 1.2rem; margin-top: .3rem; color: var(--hijau-muda); }

    /* Spesifikasi (latar paling pekat) */
    .pd-deep { background: #0f2417; }
    .pd-specs { margin: 0; }
    .pd-spec { display: grid; grid-template-columns: 1fr; gap: .25rem 2rem; padding: 1.1rem 0; border-top: 1px solid rgba(242,244,236,.2); }
    .pd-spec:last-child { border-bottom: 1px solid rgba(242,244,236,.2); }
    .pd-spec dt { font-size: .95rem; color: rgba(242,244,236,.6); }
    .pd-spec dd { margin: 0; font-size: clamp(1.15rem, 2vw, 1.5rem); font-weight: 700; letter-spacing: -.01em; line-height: 1.3; }
    @media (min-width: 640px) { .pd-spec { grid-template-columns: 14rem 1fr; align-items: baseline; } }

    /* Galeri */
    .pd-gallery { margin-top: clamp(2rem, 5vh, 3.5rem); display: grid; grid-template-columns: 1fr; gap: 1rem; }
    @media (min-width: 640px)  { .pd-gallery { grid-template-columns: repeat(2, 1fr); } }
    @media (min-width: 1024px) { .pd-gallery { grid-template-columns: repeat(3, 1fr); } }
    .pd-gitem { display: block; width: 100%; padding: 0; border: 0; aspect-ratio: 4 / 3; overflow: hidden; border-radius: .25rem; background: linear-gradient(145deg, var(--hijau-muda), #1d4129); cursor: zoom-in; }
    .pd-gitem img, .pd-gitem video { width: 100%; height: 100%; object-fit: cover; display: block; }
    .pd-gitem img { transition: transform .6s ease; }
    .pd-gitem:hover img { transform: scale(1.04); }
    .pd-gitem.is-video { cursor: default; }

    /* Sebelumnya / selanjutnya */
    .pd-nav { display: grid; grid-template-columns: 1fr; gap: 0 3rem; }
    @media (min-width: 700px) { .pd-nav { grid-template-columns: 1fr 1fr; } }
    .pd-nav a { display: block; padding: 1.5rem 0; border-top: 1px solid rgba(242,244,236,.2); }
    .pd-nav-next { text-align: right; }
    .pd-nav small { display: block; font-size: .9rem; color: rgba(242,244,236,.6); }
    .pd-nav b { display: block; margin-top: .3rem; font-size: clamp(1.3rem, 2.6vw, 2.2rem); font-weight: 800; letter-spacing: -.03em; line-height: 1.1; transition: color .25s; }
    .pd-nav a:hover b { color: var(--emas); }

    /* Penutup: pita berfoto rata tengah */
    .pd-band { position: relative; isolation: isolate; overflow: hidden; text-align: center; background: linear-gradient(145deg, var(--hijau-muda), var(--hijau)); }
    .pd-band::after { content: ''; position: absolute; inset: 0; z-index: -1; background: rgba(15,36,23,.8); }
    .pd-band .pd-h2 { max-width: 18ch; margin-left: auto; margin-right: auto; font-size: clamp(2.2rem, 6vw, 4.5rem); }
    .pd-band-p { margin: 1.25rem auto 0; max-width: 36rem; color: rgba(242,244,236,.8); }

    /* Lightbox */
    .pd-lightbox { position: fixed; inset: 0; z-index: 100; display: flex; align-items: center; justify-content: center; padding: 2rem; background: rgba(5,8,5,.92); }
    .pd-lightbox[hidden] { display: none; }
    .pd-lightbox img { max-width: min(94vw, 70rem); max-height: 88vh; object-fit: contain; border-radius: .25rem; }
    .pd-lightbox-close { position: absolute; top: 1rem; right: 1.25rem; width: 44px; height: 44px; border: 1px solid rgba(242,244,236,.4); border-radius: 50%; background: transparent; color: var(--krem); font-size: 1.6rem; line-height: 1; cursor: pointer; }
    .pd-lightbox-close:hover { background: var(--emas); border-color: var(--emas); color: var(--hijau); }
</style>

<div class="pd">

    {{-- HERO berfoto: foto tetap yang sama untuk semua produk --}}
    <header class="pd-hero">
        <img class="pd-bg" src="{{ asset('images/' . $fotoHero) }}" alt="" onerror="this.style.display='none'">
        <div class="pd-hero-inner">
            <nav class="pd-crumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Beranda</a>
                <span aria-hidden="true">/</span>
                <a href="{{ route('products') }}">Produk</a>
                <span aria-hidden="true">/</span>
                <span aria-current="page">{{ $product->name }}</span>
            </nav>
            <p class="pd-label" style="margin-top:1.5rem">Produk kami</p>
            <h1 class="pd-title" style="margin-top:.5rem">{{ $product->name }}</h1>
        </div>
    </header>

    {{-- DETAIL PRODUK --}}
    <section class="pd-sec">
        <div class="pd-split">
            <div>
                <h2 class="pd-h2">Detail Produk</h2>
                @if(!empty($product->category))
                    <span class="pd-cat">{{ $product->category }}</span>
                @endif
                <p class="pd-detail">{{ $product->detail }}</p>
            </div>

            @if($gambarUtama)
            <button type="button" class="pd-zoombtn" data-pd-zoom="{{ $gambarUtama }}" data-alt="{{ $product->name }}" aria-label="Perbesar foto {{ $product->name }}">
                <img class="pd-photo" src="{{ $gambarUtama }}" alt="{{ $product->name }}" onerror="this.style.display='none'">
                <span class="pd-zoomhint" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16zM11 8v6M8 11h6"/></svg>
                </span>
            </button>
            @endif
        </div>
    </section>

    {{-- MANFAAT (section terang sebagai jeda) --}}
    @if(!empty($product->manfaat))
    <section class="pd-sec pd-light">
        <div class="pd-split">
            @if($gambarManfaat)
                <img class="pd-photo" src="{{ $gambarManfaat }}" alt="Manfaat {{ $product->name }}" loading="lazy" onerror="this.style.display='none'">
            @endif
            <div>
                <p class="pd-label">Keunggulan</p>
                <h2 class="pd-h2">Manfaat</h2>
                <ul class="pd-benefits">
                    @foreach($product->manfaat as $manfaat)
                    <li class="pd-benefit">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ $manfaat }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </section>
    @endif

    {{-- SPESIFIKASI (latar paling pekat) --}}
    @if(!empty($product->spesifikasi))
    <section class="pd-sec pd-deep">
        <div class="pd-split pd-split-top">
            <div class="pd-sticky">
                <p class="pd-label">Data teknis</p>
                <h2 class="pd-h2">Spesifikasi</h2>
            </div>
            <dl class="pd-specs">
                @foreach($product->spesifikasi as $key => $val)
                <div class="pd-spec">
                    <dt>{{ $key }}</dt>
                    <dd>{{ $val }}</dd>
                </div>
                @endforeach
            </dl>
        </div>
    </section>
    @endif

    {{-- GALERI FOTO & VIDEO (dari tabel product_media, diinput admin) --}}
    @if($product->media->isNotEmpty())
    <section class="pd-sec">
        <p class="pd-label">Dokumentasi</p>
        <h2 class="pd-h2">Galeri {{ $product->name }}</h2>
        <div class="pd-gallery">
            @foreach($product->media as $item)
                @if($item->type === 'image')
                <button type="button" class="pd-gitem" data-pd-zoom="{{ asset('storage/' . $item->file_path) }}" data-alt="{{ $product->name }}" aria-label="Perbesar foto galeri {{ $product->name }}">
                    <img src="{{ asset('storage/' . $item->file_path) }}" alt="" loading="lazy" onerror="this.style.display='none'">
                </button>
                @elseif($item->type === 'video')
                <div class="pd-gitem is-video">
                    <video controls preload="metadata">
                        <source src="{{ asset('storage/' . $item->file_path) }}" type="video/mp4">
                        Browser Anda tidak mendukung pemutaran video.
                    </video>
                </div>
                @endif
            @endforeach
        </div>
    </section>
    @endif

    {{-- PRODUK SEBELUMNYA / SELANJUTNYA --}}
    <section class="pd-sec">
        <nav class="pd-nav" aria-label="Produk lainnya">
            <a href="{{ route('products.show', $prevProduct->slug) }}">
                <small>Sebelumnya</small>
                <b>{{ $prevProduct->name }}</b>
            </a>
            <a class="pd-nav-next" href="{{ route('products.show', $nextProduct->slug) }}">
                <small>Selanjutnya</small>
                <b>{{ $nextProduct->name }}</b>
            </a>
        </nav>
    </section>

    {{-- PENUTUP: ajakan kontak untuk produk ini --}}
    <section class="pd-sec pd-band">
        <img class="pd-bg" src="{{ asset('images/' . $fotoCta) }}" alt="" loading="lazy" onerror="this.style.display='none'">
        <h2 class="pd-h2">Tertarik dengan {{ $product->name }}?</h2>
        <p class="pd-band-p">Hubungi tim kami untuk informasi penawaran harga, kapasitas pengiriman, dan kerja sama.</p>
        <a class="pd-link" href="{{ route('contact') }}">Hubungi Kami</a>
    </section>

    {{-- Lightbox: klik foto untuk memperbesar; tutup dengan tombol, klik latar, atau Escape --}}
    <div class="pd-lightbox" id="pdLightbox" role="dialog" aria-modal="true" aria-label="Foto diperbesar" hidden>
        <button type="button" class="pd-lightbox-close" id="pdLightboxClose" aria-label="Tutup">&times;</button>
        <img id="pdLightboxImg" src="" alt="">
    </div>

</div>

<script>
    (function () {
        const box = document.getElementById('pdLightbox');
        const img = document.getElementById('pdLightboxImg');
        const closeBtn = document.getElementById('pdLightboxClose');
        let trigger = null;

        function open(src, alt, from) {
            img.src = src; img.alt = alt;
            box.hidden = false;
            document.body.style.overflow = 'hidden';
            trigger = from;
            closeBtn.focus();
        }
        function close() {
            box.hidden = true; img.src = '';
            document.body.style.overflow = '';
            if (trigger) trigger.focus();
        }

        document.querySelectorAll('[data-pd-zoom]').forEach(function (b) {
            b.addEventListener('click', function () { open(b.dataset.pdZoom, b.dataset.alt, b); });
        });
        box.addEventListener('click', function (e) { if (e.target !== img) close(); });
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !box.hidden) close(); });
    })();
</script>

@endsection