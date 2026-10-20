@extends('layouts.app')

@section('title', 'Berita & Galeri Kegiatan — PT Sahabat Sawit')

@section('content')

{{-- Foto hero otomatis diambil dari berita terbaru yang punya gambar --}}
@php
    $heroPhoto = $beritas->first(fn ($b) => $b->gambar);
@endphp

<style>
    /* Daftar berita — gaya disamakan dengan halaman lain (gelap kehijauan). Class berawalan "bt-". */
    @import url('https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,600;12..96,800&display=swap');

    .bt {
        --hijau: #16321f; --hijau-muda: #2f5a3a; --emas: #e3a72f; --krem: #f2f4ec;
        --gutter: max(clamp(1.25rem, 5vw, 3.5rem), calc((100vw - 80rem) / 2));
        font-family: "Bricolage Grotesque", system-ui, -apple-system, "Segoe UI", sans-serif;
        background: var(--hijau); color: var(--krem); line-height: 1.6;
    }
    .bt *, .bt *::before, .bt *::after { box-sizing: border-box; }
    .bt a { color: inherit; text-decoration: none; }
    .bt a:focus-visible { outline: 3px solid var(--krem); outline-offset: 3px; }

    /* Hero berfoto (lebih pendek dari halaman lain karena ini halaman daftar) */
    .bt-hero { position: relative; isolation: isolate; overflow: hidden; display: flex; align-items: flex-end; min-height: min(64vh, 40rem); background: linear-gradient(145deg, var(--hijau-muda), var(--hijau)); }
    .bt-hero::after { content: ''; position: absolute; inset: 0; z-index: -1; background: linear-gradient(90deg, rgba(22,50,31,.92) 0%, rgba(22,50,31,.65) 55%, rgba(22,50,31,.35) 100%), linear-gradient(0deg, rgba(22,50,31,.85) 0%, transparent 45%); }
    .bt-bg { position: absolute; inset: 0; z-index: -2; width: 100%; height: 100%; object-fit: cover; }
    .bt-hero-inner { width: 100%; padding: clamp(8rem, 18vh, 11rem) var(--gutter) clamp(3rem, 8vh, 4.5rem); }
    .bt-crumb { display: flex; flex-wrap: wrap; align-items: center; gap: .5rem; font-size: .9rem; color: rgba(242,244,236,.6); }
    .bt-crumb a:hover { color: var(--krem); }
    .bt-crumb [aria-current] { color: var(--krem); }
    .bt-label { margin-top: 1.5rem; font-size: .95rem; color: rgba(242,244,236,.6); }
    .bt-title { margin-top: .5rem; max-width: 14ch; font-size: clamp(2.6rem, 8vw, 6rem); font-weight: 800; line-height: 1.02; letter-spacing: -.04em; }
    .bt-lead { margin-top: 1.5rem; max-width: 40rem; color: rgba(242,244,236,.78); }

    /* Daftar */
    .bt-sec { padding: clamp(3rem, 8vh, 5rem) var(--gutter) clamp(4rem, 10vh, 7rem); }
    .bt-grid { display: grid; grid-template-columns: 1fr; gap: 3rem 1.5rem; }
    @media (min-width: 700px)  { .bt-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (min-width: 1024px) {
        .bt-grid { grid-template-columns: repeat(3, 1fr); }
        .bt-card:nth-child(3n+2) { margin-top: 3rem; }   /* kolom tengah turun sedikit: memecah garis datar */
    }
    .bt-card { display: block; }
    .bt-img { aspect-ratio: 3 / 2; overflow: hidden; border-radius: .25rem; background: linear-gradient(145deg, var(--hijau-muda), #1d4129); }
    .bt-img img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform .6s ease; }
    .bt-card:hover .bt-img img { transform: scale(1.04); }
    .bt-meta { margin-top: 1rem; display: flex; flex-wrap: wrap; justify-content: space-between; gap: .25rem .75rem; font-size: .85rem; color: rgba(242,244,236,.6); }
    .bt-card h2 { margin-top: .35rem; font-size: 1.35rem; font-weight: 700; letter-spacing: -.02em; line-height: 1.25; transition: color .25s; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .bt-card:hover h2 { color: var(--emas); }
    .bt-excerpt { margin-top: .5rem; font-size: .95rem; color: rgba(242,244,236,.7); display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
    .bt-more { display: inline-block; margin-top: 1rem; font-size: .9rem; font-weight: 600; border-bottom: 2px solid var(--emas); padding-bottom: .15rem; }
    .bt-empty { padding: 3rem 1rem; text-align: center; color: rgba(242,244,236,.6); border: 1px solid rgba(242,244,236,.15); border-radius: .25rem; }

    /* Halaman berikutnya (menggantikan $beritas->links() agar serasi dengan tema gelap) */
    .bt-pager { margin-top: clamp(3rem, 8vh, 5rem); padding-top: 1.5rem; border-top: 1px solid rgba(242,244,236,.2); display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; }
    .bt-pager a { font-weight: 600; font-size: .95rem; border-bottom: 2px solid var(--emas); padding-bottom: .2rem; }
    .bt-pager a:hover { color: var(--emas); }
    .bt-pager .is-off { font-size: .95rem; color: rgba(242,244,236,.35); }
    .bt-pager-info { font-size: .9rem; color: rgba(242,244,236,.6); }
</style>

<div class="bt">

    {{-- HERO --}}
    <header class="bt-hero">
        @if($heroPhoto)
            <img class="bt-bg" src="{{ asset('storage/' . $heroPhoto->gambar) }}" alt="" onerror="this.style.display='none'">
        @endif
        <div class="bt-hero-inner">
            <nav class="bt-crumb" aria-label="Breadcrumb">
                <a href="{{ url('/') }}">Beranda</a>
                <span aria-hidden="true">/</span>
                <span aria-current="page">Berita & Galeri Kegiatan</span>
            </nav>
            <p class="bt-label">Media</p>
            <h1 class="bt-title">Berita & Galeri Kegiatan</h1>
            <p class="bt-lead">
                Dokumentasi keseharian PT. Sahabat Sawit Rokan Sejahtera — mulai dari kunjungan kerja,
                rapat internal, kegiatan di mess karyawan, hingga momen kebersamaan lainnya.
            </p>
        </div>
    </header>

    {{-- DAFTAR BERITA: semua tampil setara, tanpa sorotan "terbaru" --}}
    <section class="bt-sec">
        @if($beritas->isEmpty())
            <p class="bt-empty">Belum ada dokumentasi kegiatan yang dipublikasikan.</p>
        @else
            <div class="bt-grid">
                @foreach($beritas as $item)
                <a href="{{ route('media.berita.show', $item->slug) }}" class="bt-card">
                    <div class="bt-img">
                        @if($item->gambar)
                            <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->judul }}" loading="lazy" onerror="this.style.display='none'">
                        @endif
                    </div>
                    <div class="bt-meta">
                        <span>{{ $item->tanggal->translatedFormat('d F Y') }}</span>
                        <span>{{ number_format($item->views ?? 0, 0, ',', '.') }}x dilihat</span>
                    </div>
                    <h2>{{ $item->judul }}</h2>
                    <p class="bt-excerpt">{{ $item->deskripsi_singkat }}</p>
                    <span class="bt-more">Baca selengkapnya</span>
                </a>
                @endforeach
            </div>

            @if($beritas->hasPages())
            <nav class="bt-pager" aria-label="Halaman berita">
                @if($beritas->onFirstPage())
                    <span class="is-off">Sebelumnya</span>
                @else
                    <a href="{{ $beritas->previousPageUrl() }}" rel="prev">Sebelumnya</a>
                @endif

                <span class="bt-pager-info">Halaman {{ $beritas->currentPage() }} dari {{ $beritas->lastPage() }}</span>

                @if($beritas->hasMorePages())
                    <a href="{{ $beritas->nextPageUrl() }}" rel="next">Selanjutnya</a>
                @else
                    <span class="is-off">Selanjutnya</span>
                @endif
            </nav>
            @endif
        @endif
    </section>

</div>
@endsection