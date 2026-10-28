@extends('layouts.app')

@section('title', $pengetahuan->judul . ' — PT Sahabat Sawit')

@section('content')

@php
    $kategori  = $pengetahuan->kategoriData?->nama ?? 'Pengetahuan';
    $foto      = $pengetahuan->gambar ? asset('storage/' . $pengetahuan->gambar) : null;
    // Perkiraan waktu baca (±200 kata per menit)
    $menitBaca = max(1, (int) ceil(str_word_count(strip_tags((string) $pengetahuan->ringkasan_html)) / 200));
@endphp

<style>
    /* Detail pengetahuan — hero tipografi, foto utama menumpuk, artikel terang dengan daftar isi otomatis. Class berawalan "pk-". */
    @import url('https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,600;12..96,800&display=swap');

    .pk {
        --hijau: #16321f; --hijau-muda: #2f5a3a; --emas: #e3a72f; --krem: #f2f4ec;
        --gutter: max(clamp(1.25rem, 5vw, 3.5rem), calc((100vw - 80rem) / 2));
        font-family: "Bricolage Grotesque", system-ui, -apple-system, "Segoe UI", sans-serif;
        background: var(--hijau); color: var(--krem); line-height: 1.6;
    }
    .pk *, .pk *::before, .pk *::after { box-sizing: border-box; }
    .pk a { color: inherit; text-decoration: none; }
    .pk a:focus-visible { outline: 3px solid var(--hijau-muda); outline-offset: 3px; }

    /* Hero tipografi */
    .pk-hero { padding: clamp(8rem, 18vh, 11rem) var(--gutter) clamp(3rem, 8vh, 5rem); }
    .pk-hero.has-photo { padding-bottom: clamp(9rem, 15vw, 13rem); }   /* ruang untuk foto yang menumpuk */
    .pk-crumb { display: flex; flex-wrap: wrap; align-items: center; gap: .5rem; font-size: .9rem; color: rgba(242,244,236,.6); }
    .pk-crumb a:hover { color: var(--krem); }
    .pk-crumb [aria-current] { color: var(--krem); }
    .pk-meta { margin-top: 1.75rem; display: flex; flex-wrap: wrap; align-items: center; gap: .35rem 1.5rem; font-size: .95rem; color: rgba(242,244,236,.7); }
    .pk-cat { font-weight: 600; color: var(--emas); }
    .pk-title { margin-top: 1.25rem; max-width: 22ch; font-size: clamp(2rem, 5.5vw, 4.5rem); font-weight: 800; line-height: 1.05; letter-spacing: -.035em; }

    /* Artikel (latar terang) */
    .pk-article { position: relative; z-index: 1; display: flow-root; background: var(--krem); color: var(--hijau); padding: 0 var(--gutter) clamp(4rem, 10vh, 7rem); }
    .pk-article:not(:has(.pk-photo-wrap)) { padding-top: clamp(3rem, 8vh, 5rem); }
    .pk-photo-wrap { position: relative; margin: clamp(-9rem, -11vw, -5rem) 0 0; }
    .pk-photo { display: block; width: 100%; aspect-ratio: 16 / 9; max-height: 38rem; object-fit: cover; border-radius: .25rem; background: linear-gradient(145deg, var(--hijau-muda), var(--hijau)); box-shadow: 0 30px 60px rgba(15,36,23,.35); }

    .pk-grid { margin-top: clamp(2.5rem, 6vh, 4rem); display: grid; grid-template-columns: minmax(0, 1fr); gap: 2rem; }
    .pk-grid > article { width: 100%; max-width: 44rem; margin: 0 auto; }
    .pk-toc { display: none; }
    @media (min-width: 1000px) {
        .pk-grid.has-toc { grid-template-columns: 15rem minmax(0, 44rem); gap: 4rem; justify-content: center; }
        .pk-grid.has-toc > article { margin: 0; }
        .pk-grid.has-toc .pk-toc { display: block; }
        .pk-toc-inner { position: sticky; top: 7rem; }
    }
    .pk-toc-label { font-size: .85rem; color: rgba(22,50,31,.6); }
    .pk-toc ol { list-style: none; margin: .75rem 0 0; padding: 0; }
    .pk-toc a { display: block; padding: .35rem 0 .35rem .9rem; border-left: 2px solid rgba(22,50,31,.15); font-size: .9rem; line-height: 1.35; color: rgba(22,50,31,.65); transition: color .2s, border-color .2s; }
    .pk-toc a.is-sub { padding-left: 1.7rem; }
    .pk-toc a:hover { color: var(--hijau); }
    .pk-toc a.is-active { border-left-color: var(--emas); color: var(--hijau); font-weight: 600; }

    .pk-prose { font-size: 1.1rem; line-height: 1.8; color: rgba(22,50,31,.9); overflow-wrap: anywhere; }
    .pk-prose > * + * { margin-top: 1.25rem; }
    /* Paragraf rata kiri-kanan (justify) */
    .pk-prose p { text-align: justify; }
    /* Perataan dari editor Quill (bila admin memilihnya) — menimpa justify bawaan di atas */
    .pk-prose .ql-align-left    { text-align: left; }
    .pk-prose .ql-align-center  { text-align: center; }
    .pk-prose .ql-align-right   { text-align: right; }
    .pk-prose .ql-align-justify { text-align: justify; }
    .pk-prose h2, .pk-prose h3 { scroll-margin-top: 7rem; color: var(--hijau); }
    .pk-prose h2 { margin-top: 2.5rem; font-size: clamp(1.6rem, 3vw, 2.2rem); font-weight: 800; letter-spacing: -.03em; line-height: 1.15; }
    .pk-prose h3 { margin-top: 2rem; font-size: 1.35rem; font-weight: 700; letter-spacing: -.02em; line-height: 1.25; }
    .pk-prose > :first-child { margin-top: 0; }
    .pk-prose a { color: var(--hijau-muda); text-decoration: underline; text-decoration-color: var(--emas); text-underline-offset: 3px; }
    .pk-prose ul, .pk-prose ol { padding-left: 1.4rem; }
    .pk-prose li + li { margin-top: .4rem; }
    .pk-prose img, .pk-prose video, .pk-prose iframe { max-width: 100%; height: auto; border-radius: .25rem; }
    .pk-prose figure { margin: 2rem 0; }
    .pk-prose figcaption { margin-top: .5rem; font-size: .9rem; color: rgba(22,50,31,.65); }
    .pk-prose blockquote { margin: 2rem 0; padding: .25rem 0 .25rem 1.25rem; border-left: 3px solid var(--emas); font-size: 1.25rem; font-weight: 600; line-height: 1.5; }
    .pk-prose table { display: block; max-width: 100%; overflow-x: auto; border-collapse: collapse; font-size: .95rem; }
    .pk-prose th, .pk-prose td { padding: .6rem .9rem; border-bottom: 1px solid rgba(22,50,31,.2); text-align: left; }
    .pk-prose hr { border: 0; border-top: 1px solid rgba(22,50,31,.2); }
    .pk-back { display: inline-flex; margin-top: 3rem; font-weight: 600; font-size: .95rem; border-bottom: 2px solid var(--emas); padding-bottom: .2rem; }
    .pk-back:hover { color: var(--hijau-muda); }

    /* Pengetahuan lainnya (kembali ke latar gelap) */
    .pk-more { padding: clamp(4rem, 10vh, 7rem) var(--gutter); }
    .pk-more-head { display: flex; flex-wrap: wrap; align-items: flex-end; justify-content: space-between; gap: 1rem; }
    .pk-label { font-size: .95rem; color: rgba(242,244,236,.6); }
    .pk-h2 { margin-top: .5rem; font-size: clamp(1.8rem, 4vw, 3rem); font-weight: 800; line-height: 1.05; letter-spacing: -.03em; }
    .pk-all { margin-bottom: .6rem; font-weight: 600; font-size: .95rem; border-bottom: 2px solid var(--emas); padding-bottom: .2rem; white-space: nowrap; }
    .pk-all:hover { color: var(--emas); }
    .pk-cards { margin-top: clamp(2rem, 5vh, 3.5rem); display: grid; grid-template-columns: 1fr; gap: 2.5rem 1.5rem; }
    @media (min-width: 700px)  { .pk-cards { grid-template-columns: repeat(2, 1fr); } }
    @media (min-width: 1024px) { .pk-cards { grid-template-columns: repeat(3, 1fr); } }
    .pk-card { display: block; }
    .pk-card-img { aspect-ratio: 3 / 2; overflow: hidden; border-radius: .25rem; background: linear-gradient(145deg, var(--hijau-muda), #1d4129); }
    .pk-card-img img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform .6s ease; }
    .pk-card:hover .pk-card-img img { transform: scale(1.04); }
    .pk-card-cat { margin-top: 1rem; font-size: .85rem; font-weight: 600; color: var(--emas); }
    .pk-card h3 { margin-top: .35rem; font-size: 1.25rem; font-weight: 700; letter-spacing: -.02em; line-height: 1.25; transition: color .25s; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .pk-card:hover h3 { color: var(--emas); }
    .pk-card-text { margin-top: .5rem; font-size: .95rem; color: rgba(242,244,236,.7); display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
</style>

<div class="pk">

    {{-- HERO tipografi --}}
    <header class="pk-hero {{ $foto ? 'has-photo' : '' }}">
        <nav class="pk-crumb" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Beranda</a>
            <span aria-hidden="true">/</span>
            <a href="{{ route('media.pengetahuan') }}">Pengetahuan</a>
            <span aria-hidden="true">/</span>
            <span aria-current="page">{{ \Illuminate\Support\Str::limit($pengetahuan->judul, 48) }}</span>
        </nav>

        <div class="pk-meta">
            <span class="pk-cat">{{ $kategori }}</span>
            <span>{{ $menitBaca }} menit baca</span>
        </div>
        <h1 class="pk-title">{{ $pengetahuan->judul }}</h1>
    </header>

    {{-- ARTIKEL (latar terang) --}}
    <section class="pk-article">
        @if($foto)
        <div class="pk-photo-wrap">
            <img class="pk-photo" src="{{ $foto }}" alt="{{ $pengetahuan->judul }}" onerror="this.style.display='none'">
        </div>
        @endif

        <div class="pk-grid" id="pkGrid">
            {{-- Daftar isi: dibuat otomatis dari judul bagian (h2/h3) di dalam artikel; tersembunyi bila tidak ada --}}
            <aside class="pk-toc" id="pkToc" aria-label="Daftar isi">
                <div class="pk-toc-inner">
                    <p class="pk-toc-label">Daftar isi</p>
                    <ol id="pkTocList"></ol>
                </div>
            </aside>

            <article>
                {{-- Penjelasan dari editor (sudah dibersihkan saat disimpan) --}}
                <div class="pk-prose" id="pkProse">
                    {!! $pengetahuan->ringkasan_html !!}
                </div>

                <a class="pk-back" href="{{ route('media.pengetahuan') }}">Kembali ke Pengetahuan</a>
            </article>
        </div>
    </section>

    {{-- PENGETAHUAN LAINNYA --}}
    @if($pengetahuanLain->isNotEmpty())
    <section class="pk-more">
        <div class="pk-more-head">
            <div>
                <p class="pk-label">Media</p>
                <h2 class="pk-h2">Pengetahuan Lainnya</h2>
            </div>
            <a class="pk-all" href="{{ route('media.pengetahuan') }}">Lihat Semua Pengetahuan</a>
        </div>

        <div class="pk-cards">
            @foreach($pengetahuanLain as $item)
            <a class="pk-card" href="{{ route('media.pengetahuan.show', $item->slug) }}">
                <div class="pk-card-img">
                    @if($item->gambar)
                        <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->judul }}" loading="lazy" onerror="this.style.display='none'">
                    @endif
                </div>
                <p class="pk-card-cat">{{ $item->kategoriData?->nama ?? 'Pengetahuan' }}</p>
                <h3>{{ $item->judul }}</h3>
                <p class="pk-card-text">{{ $item->ringkasan_teks }}</p>
            </a>
            @endforeach
        </div>
    </section>
    @endif

</div>

<script>
    // Daftar isi otomatis dari h2/h3 di dalam artikel; bagian yang sedang dibaca ditandai.
    (function () {
        const prose = document.getElementById('pkProse');
        const grid = document.getElementById('pkGrid');
        const list = document.getElementById('pkTocList');
        if (!prose || !grid || !list) return;

        const heads = [...prose.querySelectorAll('h2, h3')];
        if (heads.length < 2) return;

        const used = new Set();
        function slug(text) {
            return text.toLowerCase().trim().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '') || 'bagian';
        }

        const links = heads.map(function (h) {
            const base = h.id || slug(h.textContent);
            let id = base, n = 1;
            while (used.has(id)) { id = base + '-' + (++n); }
            used.add(id);
            h.id = id;

            const li = document.createElement('li');
            const a = document.createElement('a');
            a.href = '#' + id;
            a.textContent = h.textContent;
            if (h.tagName === 'H3') a.className = 'is-sub';
            li.appendChild(a);
            list.appendChild(li);
            return a;
        });

        grid.classList.add('has-toc');

        if (!('IntersectionObserver' in window)) return;
        const io = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) return;
                const idx = heads.indexOf(entry.target);
                links.forEach(function (a, k) { a.classList.toggle('is-active', k === idx); });
            });
        }, { rootMargin: '-15% 0px -75% 0px' });
        heads.forEach(function (h) { io.observe(h); });
    })();
</script>
@endsection