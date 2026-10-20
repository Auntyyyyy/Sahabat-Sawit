@extends('layouts.app')

@section('title', $berita->judul . ' — PT Sahabat Sawit')

@section('content')

@php
    $shareUrl  = route('media.berita.show', $berita->slug);
    $shareText = $berita->judul;
    $foto      = $berita->gambar ? asset('storage/' . $berita->gambar) : null;
    // Perkiraan waktu baca dari isi berita (±200 kata per menit)
    $menitBaca = $berita->isi ? max(1, (int) ceil(str_word_count(strip_tags($berita->isi)) / 200)) : null;
@endphp

<style>
    /* Detail berita — hero tipografi + foto utama yang menumpuk, artikel di latar terang untuk kenyamanan membaca. Class berawalan "bd-". */
    @import url('https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,600;12..96,800&display=swap');

    .bd {
        --hijau: #16321f; --hijau-muda: #2f5a3a; --emas: #e3a72f; --krem: #f2f4ec;
        --gutter: max(clamp(1.25rem, 5vw, 3.5rem), calc((100vw - 80rem) / 2));
        font-family: "Bricolage Grotesque", system-ui, -apple-system, "Segoe UI", sans-serif;
        background: var(--hijau); color: var(--krem); line-height: 1.6;
    }
    .bd *, .bd *::before, .bd *::after { box-sizing: border-box; }
    .bd a { color: inherit; text-decoration: none; }
    .bd a:focus-visible, .bd button:focus-visible { outline: 3px solid var(--hijau-muda); outline-offset: 3px; }

    /* Hero tipografi */
    .bd-hero { padding: clamp(8rem, 18vh, 11rem) var(--gutter) clamp(3rem, 8vh, 5rem); }
    .bd-hero.has-photo { padding-bottom: clamp(9rem, 15vw, 13rem); }   /* ruang untuk foto yang menumpuk */
    .bd-crumb { display: flex; flex-wrap: wrap; align-items: center; gap: .5rem; font-size: .9rem; color: rgba(242,244,236,.6); }
    .bd-crumb a:hover { color: var(--krem); }
    .bd-crumb [aria-current] { color: var(--krem); }
    .bd-meta { margin-top: 1.75rem; display: flex; flex-wrap: wrap; align-items: center; gap: .35rem 1.5rem; font-size: .95rem; color: rgba(242,244,236,.7); }
    .bd-tag { display: inline-block; margin-top: 1.25rem; padding: .2rem .85rem; border: 1px solid rgba(242,244,236,.4); border-radius: 99px; font-size: .85rem; color: rgba(242,244,236,.9); }
    .bd-title { margin-top: 1.25rem; max-width: 22ch; font-size: clamp(2rem, 5.5vw, 4.5rem); font-weight: 800; line-height: 1.05; letter-spacing: -.035em; }

    /* Artikel (latar terang) */
    .bd-article { position: relative; z-index: 1; display: flow-root; background: var(--krem); color: var(--hijau); padding: 0 var(--gutter) clamp(4rem, 10vh, 7rem); }
    .bd-photo-wrap { position: relative; margin: clamp(-9rem, -11vw, -5rem) 0 0; }
    .bd-photo { display: block; width: 100%; aspect-ratio: 16 / 9; max-height: 38rem; object-fit: cover; border-radius: .25rem; background: linear-gradient(145deg, var(--hijau-muda), var(--hijau)); box-shadow: 0 30px 60px rgba(15,36,23,.35); }
    .bd-article:not(:has(.bd-photo-wrap)) { padding-top: clamp(3rem, 8vh, 5rem); }

    .bd-grid { margin-top: clamp(2.5rem, 6vh, 4rem); display: grid; grid-template-columns: minmax(0, 1fr); gap: 2rem; justify-content: center; }
    @media (min-width: 1000px) { .bd-grid { grid-template-columns: 4rem minmax(0, 44rem); gap: 4rem; } }
    .bd-share { order: 2; }
    @media (min-width: 1000px) { .bd-share { order: 0; } .bd-share-inner { position: sticky; top: 7rem; } }
    .bd-share-label { font-size: .85rem; color: rgba(22,50,31,.6); }
    .bd-share-row { margin-top: .75rem; display: flex; flex-wrap: wrap; gap: .6rem; }
    @media (min-width: 1000px) { .bd-share-row { flex-direction: column; } }
    .bd-share-btn { width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(22,50,31,.3); border-radius: 50%; background: transparent; color: var(--hijau); font-size: 1.05rem; cursor: pointer; transition: background .2s, border-color .2s, color .2s; }
    .bd-share-btn:hover { background: var(--hijau); border-color: var(--hijau); color: var(--krem); }

    .bd-lead { font-size: clamp(1.25rem, 2.2vw, 1.6rem); font-weight: 600; line-height: 1.4; letter-spacing: -.01em; }
    .bd-prose { margin-top: 2rem; padding-top: 2rem; border-top: 1px solid rgba(22,50,31,.2); font-size: 1.1rem; line-height: 1.8; color: rgba(22,50,31,.9); overflow-wrap: anywhere; }
    .bd-prose > * + * { margin-top: 1.25rem; }
    .bd-prose h2 { margin-top: 2.5rem; font-size: clamp(1.6rem, 3vw, 2.2rem); font-weight: 800; letter-spacing: -.03em; line-height: 1.15; color: var(--hijau); }
    .bd-prose h3 { margin-top: 2rem; font-size: 1.35rem; font-weight: 700; letter-spacing: -.02em; line-height: 1.25; color: var(--hijau); }
    .bd-prose a { color: var(--hijau-muda); text-decoration: underline; text-decoration-color: var(--emas); text-underline-offset: 3px; }
    .bd-prose ul, .bd-prose ol { padding-left: 1.4rem; }
    .bd-prose li + li { margin-top: .4rem; }
    .bd-prose img, .bd-prose video, .bd-prose iframe { max-width: 100%; height: auto; border-radius: .25rem; }
    .bd-prose figure { margin: 2rem 0; }
    .bd-prose figcaption { margin-top: .5rem; font-size: .9rem; color: rgba(22,50,31,.65); }
    .bd-prose blockquote { margin: 2rem 0; padding: .25rem 0 .25rem 1.25rem; border-left: 3px solid var(--emas); font-size: 1.25rem; font-weight: 600; line-height: 1.5; }
    .bd-prose table { display: block; max-width: 100%; overflow-x: auto; border-collapse: collapse; font-size: .95rem; }
    .bd-prose th, .bd-prose td { padding: .6rem .9rem; border-bottom: 1px solid rgba(22,50,31,.2); text-align: left; }
    .bd-prose hr { border: 0; border-top: 1px solid rgba(22,50,31,.2); }
    .bd-back { display: inline-flex; margin-top: 3rem; font-weight: 600; font-size: .95rem; border-bottom: 2px solid var(--emas); padding-bottom: .2rem; }
    .bd-back:hover { color: var(--hijau-muda); }

    /* Berita lainnya (kembali ke latar gelap) */
    .bd-more { padding: clamp(4rem, 10vh, 7rem) var(--gutter); }
    .bd-more-head { display: flex; flex-wrap: wrap; align-items: flex-end; justify-content: space-between; gap: 1rem; }
    .bd-label { font-size: .95rem; color: rgba(242,244,236,.6); }
    .bd-h2 { margin-top: .5rem; font-size: clamp(1.8rem, 4vw, 3rem); font-weight: 800; line-height: 1.05; letter-spacing: -.03em; }
    .bd-all { margin-bottom: .6rem; font-weight: 600; font-size: .95rem; border-bottom: 2px solid var(--emas); padding-bottom: .2rem; white-space: nowrap; }
    .bd-all:hover { color: var(--emas); }
    .bd-cards { margin-top: clamp(2rem, 5vh, 3.5rem); display: grid; grid-template-columns: 1fr; gap: 2.5rem 1.5rem; }
    @media (min-width: 700px)  { .bd-cards { grid-template-columns: repeat(2, 1fr); } }
    @media (min-width: 1024px) { .bd-cards { grid-template-columns: repeat(3, 1fr); } }
    .bd-card { display: block; }
    .bd-card-img { aspect-ratio: 3 / 2; overflow: hidden; border-radius: .25rem; background: linear-gradient(145deg, var(--hijau-muda), #1d4129); }
    .bd-card-img img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform .6s ease; }
    .bd-card:hover .bd-card-img img { transform: scale(1.04); }
    .bd-card-date { margin-top: 1rem; font-size: .85rem; color: rgba(242,244,236,.6); }
    .bd-card h3 { margin-top: .35rem; font-size: 1.25rem; font-weight: 700; letter-spacing: -.02em; line-height: 1.25; transition: color .25s; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .bd-card:hover h3 { color: var(--emas); }
    .bd-card p { margin-top: .5rem; font-size: .95rem; color: rgba(242,244,236,.7); display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
</style>

<div class="bd">

    {{-- HERO tipografi --}}
    <header class="bd-hero {{ $foto ? 'has-photo' : '' }}">
        <nav class="bd-crumb" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Beranda</a>
            <span aria-hidden="true">/</span>
            <a href="{{ route('media.berita') }}">Berita</a>
            <span aria-hidden="true">/</span>
            <span aria-current="page">{{ \Illuminate\Support\Str::limit($berita->judul, 48) }}</span>
        </nav>

        <div class="bd-meta">
            <span>{{ $berita->tanggal->translatedFormat('d F Y') }}</span>
            <span>{{ number_format($berita->views ?? 0, 0, ',', '.') }}x dilihat</span>
            @if($menitBaca)<span>{{ $menitBaca }} menit baca</span>@endif
        </div>

        @if($berita->tag)
            <span class="bd-tag">{{ $berita->tag }}</span>
        @endif
        <h1 class="bd-title">{{ $berita->judul }}</h1>
    </header>

    {{-- ARTIKEL (latar terang) --}}
    <section class="bd-article">
        @if($foto)
        <div class="bd-photo-wrap">
            <img class="bd-photo" src="{{ $foto }}" alt="{{ $berita->judul }}" onerror="this.style.display='none'">
        </div>
        @endif

        <div class="bd-grid">
            <aside class="bd-share" aria-label="Bagikan berita">
                <div class="bd-share-inner">
                    <p class="bd-share-label">Bagikan</p>
                    <div class="bd-share-row">
                        <a class="bd-share-btn" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}" target="_blank" rel="noopener" aria-label="Bagikan ke Facebook"><i class="bi bi-facebook"></i></a>
                        <a class="bd-share-btn" href="https://twitter.com/intent/tweet?url={{ urlencode($shareUrl) }}&text={{ urlencode($shareText) }}" target="_blank" rel="noopener" aria-label="Bagikan ke X">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <a class="bd-share-btn" href="https://wa.me/?text={{ urlencode($shareText . ' ' . $shareUrl) }}" target="_blank" rel="noopener" aria-label="Bagikan ke WhatsApp"><i class="bi bi-whatsapp"></i></a>
                        <button type="button" class="bd-share-btn" id="btn-copy-link" data-url="{{ $shareUrl }}" aria-label="Salin tautan"><i class="bi bi-copy"></i></button>
                    </div>
                    <span class="sr-only" role="status" id="copy-status"></span>
                </div>
            </aside>

            <article>
                <p class="bd-lead">{{ $berita->deskripsi_singkat }}</p>

                {{-- Isi lengkap dari editor (sudah dibersihkan saat disimpan) --}}
                @if($berita->isi)
                <div class="bd-prose">
                    {!! $berita->isi !!}
                </div>
                @endif

                <a class="bd-back" href="{{ route('media.berita') }}">Kembali ke Berita & Galeri Kegiatan</a>
            </article>
        </div>
    </section>

    {{-- BERITA LAINNYA --}}
    @if($beritaLain->isNotEmpty())
    <section class="bd-more">
        <div class="bd-more-head">
            <div>
                <p class="bd-label">Media</p>
                <h2 class="bd-h2">Berita Lainnya</h2>
            </div>
            <a class="bd-all" href="{{ route('media.berita') }}">Lihat Semua Berita</a>
        </div>

        <div class="bd-cards">
            @foreach($beritaLain as $item)
            <a class="bd-card" href="{{ route('media.berita.show', $item->slug) }}">
                <div class="bd-card-img">
                    @if($item->gambar)
                        <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->judul }}" loading="lazy" onerror="this.style.display='none'">
                    @endif
                </div>
                <p class="bd-card-date">{{ $item->tanggal->translatedFormat('d F Y') }}</p>
                <h3>{{ $item->judul }}</h3>
                <p>{{ $item->deskripsi_singkat }}</p>
            </a>
            @endforeach
        </div>
    </section>
    @endif

</div>

{{-- Tombol salin tautan --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('btn-copy-link');
    if (!btn) return;

    const icon = btn.querySelector('i');
    const status = document.getElementById('copy-status');

    function done() {
        icon.className = 'bi bi-check2';
        status.textContent = 'Tautan disalin';
        setTimeout(function () { icon.className = 'bi bi-copy'; status.textContent = ''; }, 2000);
    }

    btn.addEventListener('click', function () {
        const url = btn.dataset.url;

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(url).then(done);
            return;
        }

        // Cadangan untuk halaman yang dibuka lewat http biasa
        const temp = document.createElement('textarea');
        temp.value = url;
        temp.style.position = 'fixed';
        temp.style.opacity = '0';
        document.body.appendChild(temp);
        temp.select();
        try { document.execCommand('copy'); done(); } catch (e) {}
        document.body.removeChild(temp);
    });
});
</script>
@endsection