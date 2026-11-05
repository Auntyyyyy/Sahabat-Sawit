@extends('layouts.app')

@section('title', 'PT Sahabat Sawit Rokan Sejahtera')

@section('content')

    {{-- 1. HERO (komponen x-hero: sapaan, panel statistik 3D, video latar) --}}
    <x-hero :stats="$stats" />

@php
    // Sertifikasi untuk strip kepercayaan (file ada di public/images, sama dengan yang dipakai footer).
    $sertifikat = [
        ['file' => 'halal.jpeg', 'nama' => 'Halal'],
        ['file' => 'ispo.jpeg',  'nama' => 'ISPO'],
        ['file' => 'tsi.jpeg',   'nama' => 'TSI'],
        ['file' => 'kan.jpeg',   'nama' => 'KAN'],
    ];

    // Nama kategori produk yang ramah dibaca (untuk teaser produk).
    $labelKategori = ['CPO' => 'CPO', 'PKO' => 'PKO', 'Kernel' => 'Inti Sawit', 'Turunan' => 'Produk Turunan'];
@endphp

<style>
    /* Gaya Home disamakan dengan landing page. Semua class berawalan "hm-" supaya tidak bentrok. */
    @import url('https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,600;12..96,800&display=swap');

    .hm {
        --hijau: #16321f; --hijau-muda: #2f5a3a; --emas: #e3a72f; --krem: #f2f4ec;
        font-family: "Bricolage Grotesque", system-ui, -apple-system, "Segoe UI", sans-serif;
        background: var(--hijau);
        color: var(--krem);
        line-height: 1.6;
    }
    .hm *, .hm *::before, .hm *::after { box-sizing: border-box; }
    .hm a { color: inherit; text-decoration: none; }
    .hm a:focus-visible { outline: 3px solid var(--emas); outline-offset: 3px; }

    .hm-sec { padding: clamp(4rem, 10vh, 7rem) clamp(1.25rem, 5vw, 3.5rem); }
    .hm-sec + .hm-sec { border-top: 1px solid rgba(242,244,236,.12); }
    .hm-pad { padding: clamp(3rem, 8vh, 5rem) clamp(1.25rem, 5vw, 3.5rem); }
    .hm-inner { max-width: 80rem; margin: 0 auto; }

    .hm-split { display: grid; grid-template-columns: 1fr; gap: 1.5rem; }
    .hm-grid2 { display: grid; grid-template-columns: 1fr; gap: 3rem; align-items: center; }
    @media (min-width: 900px) {
        .hm-split { grid-template-columns: 1fr 3fr; gap: 3rem; }
        .hm-grid2 { grid-template-columns: 1fr 1fr; }
    }

    .hm-label { font-size: .95rem; color: rgba(242,244,236,.6); }
    .hm-title {
        font-size: clamp(2.2rem, 6.5vw, 5rem);
        font-weight: 800;
        line-height: 1.02;
        letter-spacing: -.04em;
        max-width: 18ch;
    }
    .hm-text {
        font-size: clamp(1.5rem, 3.2vw, 2.6rem);
        font-weight: 600;
        line-height: 1.25;
        letter-spacing: -.02em;
        max-width: 30em;
    }
    /* Kata menyala bertahap saat di-scroll (diatur JS di bawah) */
    .hm-text.is-split .w { color: rgba(242,244,236,.25); transition: color .35s ease; }
    .hm-text.is-split .w.on { color: var(--krem); }
    .hm-body { margin-top: 1.5rem; max-width: 36rem; color: rgba(242,244,236,.75); }

    .hm-link {
        display: inline-flex; align-items: center; gap: .55rem;
        margin-top: 2.5rem;
        font-weight: 600; font-size: .95rem;
        border-bottom: 2px solid var(--emas);
        padding-bottom: .2rem;
        white-space: nowrap;
    }
    .hm-link:hover { color: var(--emas); }
    .hm-link svg { width: 16px; height: 16px; }

    /* Strip sertifikasi (section terang tepat di bawah hero) */
    .hm-trust { background: var(--krem); color: var(--hijau); scroll-margin-top: 4rem; }
    .hm-trust-grid { display: grid; grid-template-columns: minmax(0, 1fr); gap: 1.75rem; align-items: center; }
    @media (min-width: 900px) { .hm-trust-grid { grid-template-columns: minmax(0, 1fr) minmax(0, 1.4fr); gap: 3rem; } }
    .hm-trust .hm-label { color: rgba(22,50,31,.6); }
    .hm-trust h2 { margin-top: .35rem; font-size: clamp(1.5rem, 2.6vw, 2.2rem); font-weight: 800; line-height: 1.1; letter-spacing: -.03em; }
    .hm-trust .hm-link { margin-top: 1rem; }
    .hm-trust .hm-link:hover { color: var(--hijau-muda); }
    .hm-logos { list-style: none; margin: 0; padding: 0; display: flex; flex-wrap: wrap; gap: .9rem; }
    .hm-logo { display: flex; align-items: center; justify-content: center; width: 6.5rem; height: 5rem; padding: .6rem; border: 1px solid rgba(22,50,31,.14); border-radius: .5rem; background: #fff; transition: border-color .25s, box-shadow .25s; }
    .hm-logo img { max-width: 100%; max-height: 100%; object-fit: contain; display: block; }
    .hm-logo:hover { border-color: var(--hijau-muda); box-shadow: 0 12px 24px rgba(22,50,31,.12); }

    /* Keunggulan: section menempel di layar, scroll ke bawah menggeser kartu ke samping */
    .hm-hscroll {
        --gutter: max(clamp(1.25rem, 5vw, 3.5rem), calc((100vw - 80rem) / 2));
        position: relative;
        border-top: 1px solid rgba(242,244,236,.12);
    }
    .hm-hscroll + .hm-sec { border-top: 1px solid rgba(242,244,236,.12); }
    .hm-hscroll-pin {
        position: sticky; top: 0;
        height: 100vh;
        overflow: hidden;
        display: flex; flex-direction: column; justify-content: center;
        gap: clamp(1.5rem, 4vh, 3rem);
        padding: 7rem 0 2.5rem; /* ruang untuk navbar melayang */
    }
    .hm-hscroll-head { padding: 0 var(--gutter); }
    .hm-hscroll-track {
        display: flex; gap: 1.25rem;
        width: max-content;
        padding: 0 var(--gutter);
        will-change: transform;
    }
    .hm-adv {
        flex: 0 0 clamp(16rem, 24vw, 21rem);
        aspect-ratio: 1 / 1;
        display: flex; flex-direction: column; justify-content: space-between; gap: 1rem;
        padding: 1.5rem;
        border: 1px solid rgba(242,244,236,.18);
        border-radius: .5rem;
        background: linear-gradient(160deg, rgba(242,244,236,.08), rgba(242,244,236,.02));
        transition: border-color .25s, transform .25s ease-out;
    }
    .hm-adv-no { align-self: flex-start; display: inline-flex; align-items: center; justify-content: center; width: 2.75rem; height: 2.75rem; border-radius: .35rem; background: rgba(227,167,47,.14); border: 1px solid rgba(227,167,47,.4); color: var(--emas); font-size: 1rem; font-weight: 800; }
    .hm-adv h3 { font-size: clamp(1.4rem, 2.2vw, 1.9rem); font-weight: 700; letter-spacing: -.02em; line-height: 1.15; transition: color .25s; }
    .hm-adv p { margin-top: .6rem; font-size: .95rem; color: rgba(242,244,236,.7); }
    .hm-adv:hover { border-color: var(--emas); }
    .hm-adv:hover h3 { color: var(--emas); }
    /* Cadangan: tanpa pin (kurangi gerakan / kartu muat di layar) → baris biasa yang bisa digeser */
    .hm-hscroll.is-native .hm-hscroll-pin { position: static; height: auto; overflow: visible; padding: clamp(4rem, 10vh, 7rem) 0; }
    .hm-hscroll.is-native .hm-hscroll-track { width: auto; overflow-x: auto; }

    /* Produk unggulan (teaser) */
    .hm-prods { margin-top: clamp(2rem, 5vh, 3.5rem); display: grid; grid-template-columns: repeat(auto-fill, minmax(min(100%, 16rem), 1fr)); gap: 1.5rem; }
    .hm-prod { display: flex; flex-direction: column; overflow: hidden; border: 1px solid rgba(242,244,236,.12); border-radius: .5rem; background: rgba(242,244,236,.05); transition: border-color .3s, background .3s, transform .25s ease-out; }
    .hm-prod-img { position: relative; aspect-ratio: 4 / 3; overflow: hidden; background: linear-gradient(145deg, var(--hijau-muda), #1d4129); }
    .hm-prod-img img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform .6s ease; }
    .hm-prod-tag { position: absolute; left: .75rem; top: .75rem; padding: .15rem .7rem; border-radius: 99px; background: rgba(15,36,23,.82); color: var(--emas); font-size: .75rem; font-weight: 700; }
    .hm-prod-body { display: flex; flex: 1; flex-direction: column; padding: 1.1rem 1.25rem 1.25rem; }
    .hm-prod-body h3 { font-size: 1.2rem; font-weight: 800; letter-spacing: -.02em; line-height: 1.2; }
    .hm-prod-body p { margin-top: .5rem; font-size: .92rem; color: rgba(242,244,236,.7); display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
    .hm-prod-body .hm-more { align-self: flex-start; margin-top: auto; }
    .hm-prod-body p + .hm-more, .hm-prod-body h3 + .hm-more { margin-top: 1rem; }
    .hm-prod:hover { border-color: rgba(227,167,47,.5); background: rgba(242,244,236,.09); }
    .hm-prod:hover .hm-prod-img img { transform: scale(1.06); }
    .hm-prod:hover .hm-more { color: var(--emas); }

    /* Perkebunan */
    .hm-stats { margin: 2rem 0 0; display: grid; grid-template-columns: 1fr 1fr; gap: .9rem; }
    .hm-stats > div { padding: 1rem 1.1rem; border: 1px solid rgba(242,244,236,.14); border-radius: .5rem; background: rgba(242,244,236,.05); }
    .hm-stats dt { font-size: .85rem; color: rgba(242,244,236,.6); }
    .hm-stats dd { margin: .15rem 0 0; font-size: 1.35rem; font-weight: 700; letter-spacing: -.01em; }
    .hm-note { margin-top: 1rem; font-size: .75rem; color: rgba(242,244,236,.45); }
    .hm-map { position: relative; isolation: isolate; border-radius: .5rem; }
    .hm-map::before { content: ''; position: absolute; z-index: -1; inset: .9rem -.9rem -.9rem .9rem; border: 2px solid var(--emas); border-radius: .5rem; }
    .hm-map iframe { display: block; width: 100%; height: 380px; border: 0; border-radius: .5rem; }

    /* Berita & Pengetahuan */
    .hm-head { display: flex; align-items: flex-end; justify-content: space-between; flex-wrap: wrap; gap: 1rem; }
    .hm-head .hm-link { margin-top: 0; margin-bottom: .6rem; }
    .hm-cards { margin-top: clamp(2rem, 5vh, 3.5rem); display: grid; grid-template-columns: 1fr; gap: 2.5rem 1.5rem; }
    @media (min-width: 900px) { .hm-cards { grid-template-columns: 2fr 1fr 1fr; } }
    .hm-card { display: block; }
    .hm-card:only-child { grid-column: 1 / -1; }
    .hm-card:first-child h3 { font-size: clamp(1.3rem, 2vw, 1.75rem); }
    .hm-card-img { aspect-ratio: 3 / 2; overflow: hidden; border-radius: .25rem; background: linear-gradient(145deg, var(--hijau-muda), #1d4129); }
    .hm-card-img img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform .6s ease; }
    .hm-card:hover .hm-card-img img { transform: scale(1.04); }
    .hm-meta { margin-top: 1rem; display: flex; flex-wrap: wrap; justify-content: space-between; gap: .25rem .75rem; font-size: .85rem; color: rgba(242,244,236,.6); }
    .hm-card h3 { margin-top: .35rem; font-size: 1.25rem; font-weight: 600; line-height: 1.3; }
    .hm-card p { margin-top: .5rem; font-size: .95rem; color: rgba(242,244,236,.7); }
    .hm-clamp { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .hm-more { display: inline-block; margin-top: 1rem; font-size: .9rem; font-weight: 600; border-bottom: 2px solid var(--emas); padding-bottom: .15rem; }
    .hm-card:hover .hm-more { color: var(--emas); }
    .hm-empty { margin-top: clamp(2rem, 5vh, 3.5rem); padding: 2.5rem 1rem; text-align: center; color: rgba(242,244,236,.6); border: 1px solid rgba(242,244,236,.15); border-radius: .25rem; }

    /* Ajakan penutup */
    .hm-cta { background: #0f2417; }
    .hm-cta-grid { display: grid; grid-template-columns: minmax(0, 1fr); gap: 2rem; align-items: center; }
    @media (min-width: 900px) { .hm-cta-grid { grid-template-columns: minmax(0, 1.4fr) minmax(0, 1fr); gap: 4rem; } }
    .hm-cta .hm-body { margin-top: 1rem; }
    .hm-cta-btns { display: flex; flex-wrap: wrap; gap: .9rem; }
    @media (min-width: 900px) { .hm-cta-btns { justify-content: flex-end; } }
    .hm-btn { display: inline-flex; align-items: center; padding: .9rem 1.7rem; border: 2px solid rgba(242,244,236,.6); border-radius: .25rem; font-weight: 700; font-size: .95rem; transition: background .2s, border-color .2s, color .2s; }
    .hm-btn:hover { background: var(--krem); border-color: var(--krem); color: var(--hijau); }
    .hm-btn--gold { background: var(--emas); border-color: var(--emas); color: var(--hijau); }
    .hm-btn--gold:hover { background: #f0b84a; border-color: #f0b84a; }

    /* Efek miring 3D saat kursor di atas kartu (diatur JS; hanya di perangkat dengan mouse) */
    [data-tilt] { transform-style: preserve-3d; will-change: transform; }

    /* Kemunculan bertahap saat masuk layar (aktif bila JavaScript jalan) */
    @keyframes hmUp { from { opacity: 0; transform: translateY(18px); } }
    .hm-js [data-rv]:not(.is-in) { opacity: 0; }
    @media (prefers-reduced-motion: no-preference) { .hm-js [data-rv].is-in { animation: hmUp .55s ease backwards; animation-delay: calc(var(--i, 0) * 70ms); } }
</style>

<div class="hm">

    {{-- 2. SERTIFIKASI (strip kepercayaan, section terang) --}}
    <section class="hm-trust hm-pad" id="hmLanjut">
        <div class="hm-inner hm-trust-grid">
            <div>
                <p class="hm-label">Standar &amp; Sertifikasi</p>
                <h2>Dipercaya dan diakui secara resmi</h2>
                <a href="{{ route('about.sertifikasi') }}" class="hm-link">
                    Lihat Semua Sertifikasi
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
            <ul class="hm-logos">
                @foreach($sertifikat as $c)
                <li class="hm-logo" data-rv style="--i: {{ $loop->index }}">
                    <img src="{{ asset('images/' . $c['file']) }}" alt="Sertifikat {{ $c['nama'] }}" loading="lazy" onerror="this.style.display='none'">
                </li>
                @endforeach
            </ul>
        </div>
    </section>

    {{-- 3. TENTANG KAMI --}}
    <section class="hm-sec">
        <div class="hm-inner hm-split">
            <p class="hm-label">Tentang Kami</p>
            <div>
                <p class="hm-text" data-glow>
                    PT. Sahabat Sawit adalah perusahaan yang bergerak di bidang perkebunan dan industri kelapa sawit, berlokasi di Kabupaten Rokan Hilir, Provinsi Riau. Kami berkomitmen mengelola perkebunan secara profesional, produktif, dan bertanggung jawab, dengan tetap menjaga kelestarian lingkungan serta memberikan manfaat nyata bagi masyarakat sekitar.
                </p>
                <p class="hm-body">
                    Dengan dukungan tim yang berpengalaman dan standar operasional yang terukur, kami terus bertumbuh menjadi mitra terpercaya dalam industri kelapa sawit di Riau.
                </p>
                <a href="{{ route('about') }}" class="hm-link">
                    Selengkapnya
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
        </div>
    </section>

    {{-- 4. KEUNGGULAN --}}
    <section class="hm-hscroll" id="hmKeunggulan">
        <div class="hm-hscroll-pin">
            <div class="hm-hscroll-head">
                <h2 class="hm-title">Mengapa Memilih SSRS?</h2>
            </div>
            <div class="hm-hscroll-track" id="hmTrack">
                @foreach($advantages as $adv)
                <article class="hm-adv" data-tilt="7">
                    <span class="hm-adv-no" aria-hidden="true">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                    <div>
                        <h3>{{ $adv['title'] }}</h3>
                        <p>{{ $adv['desc'] }}</p>
                    </div>
                </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- 5. PRODUK UNGGULAN (muncul bila controller mengirim $featuredProducts) --}}
    @if(isset($featuredProducts) && $featuredProducts->isNotEmpty())
    <section class="hm-sec">
        <div class="hm-inner">
            <div class="hm-head">
                <h2 class="hm-title">Produk Unggulan</h2>
                <a href="{{ route('products') }}" class="hm-link">
                    Lihat Semua Produk
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>

            <div class="hm-prods">
                @foreach($featuredProducts as $product)
                <a href="{{ route('products.show', $product->slug) }}" class="hm-prod" data-tilt="5" data-rv style="--i: {{ $loop->index }}">
                    <div class="hm-prod-img">
                        @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" loading="lazy" onerror="this.style.display='none'">
                        @endif
                        @if($product->category)
                            <span class="hm-prod-tag">{{ $labelKategori[$product->category] ?? $product->category }}</span>
                        @endif
                    </div>
                    <div class="hm-prod-body">
                        <h3>{{ $product->name }}</h3>
                        @if($product->description)<p>{{ $product->description }}</p>@endif
                        <span class="hm-more">Lihat detail</span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- 6. PERKEBUNAN --}}
    <section class="hm-sec">
        <div class="hm-inner hm-grid2">
            <div>
                <h2 class="hm-title">Bertumbuh bersama dari sumber terbaik</h2>
                <p class="hm-body">
                    Bahan baku berkualitas berasal dari kebun yang dikelola secara mandiri serta kemitraan dengan petani kelapa sawit. Bersama para mitra, PT. Sahabat Sawit Rokan Sejahtera membangun rantai pasok yang konsisten, berkelanjutan, dan memberikan manfaat bagi masyarakat sekitar.
                </p>

                <dl class="hm-stats">
                    <div><dt>Lokasi</dt><dd>Rokan Hilir, Riau</dd></div>
                    <div><dt>Luas Area</dt><dd>11.392+ (Ha)</dd></div>
                    <div><dt>Kemitraan</dt><dd>9348,891(Ha)</dd></div>
                    <div><dt>Diusahakan sendiri</dt><dd>2043,75(Ha)</dd></div>
                </dl>
                <p class="hm-note">*SK: 903/MENLHK/SETJEN/PLA.2/12/2016 RIAU.</p>

                <a href="{{ route('plantation') }}" class="hm-link">
                    Lihat Detail Perkebunan
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>

            <div class="hm-map">
                <iframe src="https://www.google.com/maps?q=Rokan+Hilir,+Riau&output=embed"
                        allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                        title="Lokasi Perkebunan PT Sahabat Sawit di Rokan Hilir, Riau"></iframe>
            </div>
        </div>
    </section>

    {{-- 7. BERITA & GALERI KEGIATAN --}}
    <section class="hm-sec">
        <div class="hm-inner">
            <div class="hm-head">
                <h2 class="hm-title">Berita & Galeri Kegiatan</h2>
                <a href="{{ route('media.berita') }}" class="hm-link">
                    Lihat Semua Berita
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>

            @if(isset($latestBerita) && $latestBerita->isNotEmpty())
            <div class="hm-cards">
                @foreach($latestBerita as $item)
                <a href="{{ route('media.berita.show', $item->slug) }}" class="hm-card" data-rv style="--i: {{ $loop->index }}">
                    <div class="hm-card-img">
                        <img src="{{ $item->gambar ? asset('storage/' . $item->gambar) : 'https://placehold.co/600x400/1F5F3B/F5F1E8?text=' . urlencode($item->judul) }}"
                             alt="{{ $item->judul }}" loading="lazy"
                             onerror="this.onerror=null;this.src='https://placehold.co/600x400/1F5F3B/F5F1E8?text={{ urlencode($item->judul) }}'">
                    </div>
                    <div class="hm-meta">
                        <span>{{ $item->tanggal->translatedFormat('d F Y') }}</span>
                        <span>{{ number_format($item->views ?? 0, 0, ',', '.') }}x dilihat</span>
                    </div>
                    <h3 class="hm-clamp">{{ $item->judul }}</h3>
                    <span class="hm-more">Baca selengkapnya</span>
                </a>
                @endforeach
            </div>
            @else
            <p class="hm-empty">Belum ada berita yang dipublikasikan.</p>
            @endif
        </div>
    </section>

    {{-- 8. PENGETAHUAN (SahabatPedia) --}}
    <section class="hm-sec">
        <div class="hm-inner">
            <div class="hm-head">
                <h2 class="hm-title">SahabatPedia</h2>
                <a href="{{ route('media.pengetahuan') }}" class="hm-link">
                    Lihat Semua Pengetahuan
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>

            @if(isset($latestPengetahuan) && $latestPengetahuan->isNotEmpty())
            <div class="hm-cards">
                @foreach($latestPengetahuan as $item)
                <a href="{{ route('media.pengetahuan.show', $item->slug) }}" class="hm-card" data-rv style="--i: {{ $loop->index }}">
                    <div class="hm-card-img">
                        <img src="{{ $item->gambar ? asset('storage/' . $item->gambar) : 'https://placehold.co/600x400/2F6B3F/F5F1E8?text=' . urlencode($item->judul) }}"
                             alt="{{ $item->judul }}" loading="lazy"
                             onerror="this.onerror=null;this.src='https://placehold.co/600x400/2F6B3F/F5F1E8?text={{ urlencode($item->judul) }}'">
                    </div>
                    <div class="hm-meta"><span>Tahukah Anda?</span></div>
                    <h3 class="hm-clamp">{{ $item->judul }}</h3>
                    <span class="hm-more">Baca selengkapnya</span>
                </a>
                @endforeach
            </div>
            @else
            <p class="hm-empty">Belum ada konten pengetahuan yang dipublikasikan.</p>
            @endif
        </div>
    </section>

    {{-- 9. AJAKAN PENUTUP --}}
    <section class="hm-sec hm-cta">
        <div class="hm-inner hm-cta-grid">
            <div>
                <h2 class="hm-title" style="max-width:20ch">Mari bermitra dengan kami</h2>
                <p class="hm-body">Tim kami siap membantu kebutuhan Anda, baik untuk informasi produk, penawaran, maupun kerja sama.</p>
            </div>
            <div class="hm-cta-btns">
                <a class="hm-btn hm-btn--gold" href="https://wa.me/628139654581" target="_blank" rel="noopener noreferrer">Hubungi via WhatsApp</a>
                <a class="hm-btn" href="{{ route('contact') }}">Halaman Kontak</a>
            </div>
        </div>
    </section>

</div>

<script>
    // Paragraf Tentang Kami: kata menyala bertahap saat di-scroll (dilewati bila "kurangi gerakan").
    (function () {
        const el = document.querySelector('[data-glow]');
        if (!el || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

        const words = el.textContent.trim().split(/\s+/);
        el.textContent = '';
        words.forEach(function (word) {
            const span = document.createElement('span');
            span.className = 'w';
            span.textContent = word;
            el.appendChild(span);
            el.appendChild(document.createTextNode(' '));
        });
        el.classList.add('is-split');
        const spans = el.querySelectorAll('.w');

        function update() {
            const vh = window.innerHeight;
            const r = el.getBoundingClientRect();
            const progress = Math.min(1, Math.max(0, (vh * 0.8 - r.top) / (vh * 0.45 + r.height)));
            const lit = Math.round(progress * spans.length);
            spans.forEach(function (s, i) { s.classList.toggle('on', i < lit); });
        }
        window.addEventListener('scroll', update, { passive: true });
        window.addEventListener('resize', update);
        update();
    })();
</script>

<script>
    // Keunggulan: selama section menempel di layar, scroll ke bawah menggeser kartu ke kiri.
    (function () {
        const sec = document.getElementById('hmKeunggulan');
        const track = document.getElementById('hmTrack');
        if (!sec || !track) return;
        const reduce = window.matchMedia('(prefers-reduced-motion: reduce)');
        let dist = 0;

        function update() {
            if (sec.classList.contains('is-native')) return;
            const range = sec.offsetHeight - window.innerHeight;
            const p = range > 0 ? Math.min(1, Math.max(0, -sec.getBoundingClientRect().top / range)) : 0;
            track.style.transform = 'translate3d(' + (-dist * p) + 'px,0,0)';
        }

        function layout() {
            sec.classList.remove('is-native');
            track.style.transform = '';
            sec.style.height = '';
            dist = Math.max(0, track.scrollWidth - window.innerWidth);
            if (reduce.matches || dist < 8) {
                sec.classList.add('is-native');
                return;
            }
            sec.style.height = (window.innerHeight + dist) + 'px';
            update();
        }

        window.addEventListener('scroll', update, { passive: true });
        window.addEventListener('resize', layout);
        window.addEventListener('load', layout);
        layout();
    })();
</script>

<script>
    // Efek 3D (kartu miring mengikuti kursor) + kemunculan bertahap saat masuk layar.
    (function () {
        const root = document.querySelector('.hm');
        if (!root) return;
        const calm = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        // Kemunculan bertahap
        const items = [...document.querySelectorAll('[data-rv]')];
        if (items.length) {
            root.classList.add('hm-js');
            if ('IntersectionObserver' in window && !calm) {
                const io = new IntersectionObserver(function (entries) {
                    entries.forEach(function (e) {
                        if (e.isIntersecting) { e.target.classList.add('is-in'); io.unobserve(e.target); }
                    });
                }, { threshold: 0.12 });
                items.forEach(function (el) { io.observe(el); });
            } else {
                items.forEach(function (el) { el.classList.add('is-in'); });
            }
        }

        // Miring 3D: hanya di perangkat dengan mouse dan bila gerakan tidak dikurangi
        if (calm || !window.matchMedia('(hover: hover) and (pointer: fine)').matches) return;
        document.querySelectorAll('[data-tilt]').forEach(function (el) {
            const max = Number(el.dataset.tilt) || 6;
            el.addEventListener('pointermove', function (e) {
                const r = el.getBoundingClientRect();
                const x = (e.clientX - r.left) / r.width - 0.5;
                const y = (e.clientY - r.top) / r.height - 0.5;
                el.style.transform = 'perspective(900px) rotateX(' + (-y * max) + 'deg) rotateY(' + (x * max) + 'deg) translateZ(0)';
            });
            el.addEventListener('pointerleave', function () { el.style.transform = ''; });
        });
    })();
</script>

@endsection