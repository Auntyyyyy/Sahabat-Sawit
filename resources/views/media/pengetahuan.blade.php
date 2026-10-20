@extends('layouts.app')

@section('title', 'Pengetahuan Kelapa Sawit — PT Sahabat Sawit')

@section('content')

<style>
    /* SahabatPedia (daftar pengetahuan) — daftar baris editorial agar berbeda dari grid foto di Berita. Class berawalan "pg-". */
    @import url('https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,600;12..96,800&display=swap');

    .pg {
        --hijau: #16321f; --hijau-muda: #2f5a3a; --emas: #e3a72f; --krem: #f2f4ec;
        --gutter: max(clamp(1.25rem, 5vw, 3.5rem), calc((100vw - 80rem) / 2));
        font-family: "Bricolage Grotesque", system-ui, -apple-system, "Segoe UI", sans-serif;
        background: var(--hijau); color: var(--krem); line-height: 1.6;
    }
    .pg *, .pg *::before, .pg *::after { box-sizing: border-box; }
    .pg a { color: inherit; text-decoration: none; }
    .pg a:focus-visible { outline: 3px solid var(--krem); outline-offset: 3px; }

    /* Hero berfoto */
    .pg-hero { position: relative; isolation: isolate; overflow: hidden; display: flex; align-items: flex-end; min-height: min(64vh, 40rem); background: linear-gradient(145deg, var(--hijau-muda), var(--hijau)); }
    .pg-hero::after { content: ''; position: absolute; inset: 0; z-index: -1; background: linear-gradient(90deg, rgba(22,50,31,.92) 0%, rgba(22,50,31,.65) 55%, rgba(22,50,31,.35) 100%), linear-gradient(0deg, rgba(22,50,31,.85) 0%, transparent 45%); }
    .pg-bg { position: absolute; inset: 0; z-index: -2; width: 100%; height: 100%; object-fit: cover; }
    .pg-hero-inner { width: 100%; padding: clamp(8rem, 18vh, 11rem) var(--gutter) clamp(3rem, 8vh, 4.5rem); }
    .pg-crumb { display: flex; flex-wrap: wrap; align-items: center; gap: .5rem; font-size: .9rem; color: rgba(242,244,236,.6); }
    .pg-crumb a:hover { color: var(--krem); }
    .pg-crumb [aria-current] { color: var(--krem); }
    .pg-label { margin-top: 1.5rem; font-size: .95rem; color: rgba(242,244,236,.6); }
    .pg-title { margin-top: .5rem; font-size: clamp(2.8rem, 9vw, 7rem); font-weight: 800; line-height: 1; letter-spacing: -.045em; }
    .pg-lead { margin-top: 1.5rem; max-width: 40rem; color: rgba(242,244,236,.78); }

    /* Daftar baris */
    .pg-sec { padding: clamp(3rem, 8vh, 5rem) var(--gutter) clamp(4rem, 10vh, 7rem); }
    .pg-count { font-size: .95rem; color: rgba(242,244,236,.6); }
    .pg-list { margin-top: 1.5rem; }
    .pg-row { display: grid; grid-template-columns: 1fr; gap: 1.25rem; padding: 2rem 0; border-top: 1px solid rgba(242,244,236,.15); }
    .pg-row:last-child { border-bottom: 1px solid rgba(242,244,236,.15); }
    @media (min-width: 800px) {
        .pg-row { grid-template-columns: 18rem 1fr; gap: 2.5rem; align-items: center; }
        .pg-row:nth-child(even) { grid-template-columns: 1fr 18rem; }   /* selang-seling: foto kiri, lalu kanan */
        .pg-row:nth-child(even) .pg-thumb { order: 2; }
    }
    .pg-thumb { aspect-ratio: 3 / 2; overflow: hidden; border-radius: .25rem; background: linear-gradient(145deg, var(--hijau-muda), #1d4129); }
    .pg-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform .6s ease; }
    .pg-row:hover .pg-thumb img { transform: scale(1.05); }
    .pg-cat { font-size: .9rem; font-weight: 600; color: var(--emas); }
    .pg-row h2 { margin-top: .4rem; max-width: 26ch; font-size: clamp(1.4rem, 2.8vw, 2.3rem); font-weight: 800; letter-spacing: -.03em; line-height: 1.12; transition: color .25s; }
    .pg-row:hover h2 { color: var(--emas); }
    .pg-excerpt { margin-top: .75rem; max-width: 38rem; color: rgba(242,244,236,.72); display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
    .pg-more { display: inline-block; margin-top: 1rem; font-size: .9rem; font-weight: 600; border-bottom: 2px solid var(--emas); padding-bottom: .15rem; }
    .pg-empty { padding: 3rem 1rem; text-align: center; color: rgba(242,244,236,.6); border: 1px solid rgba(242,244,236,.15); border-radius: .25rem; }

    /* Halaman berikutnya (menggantikan $pengetahuans->links() agar serasi dengan tema gelap) */
    .pg-pager { margin-top: clamp(3rem, 8vh, 5rem); display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; }
    .pg-pager a { font-weight: 600; font-size: .95rem; border-bottom: 2px solid var(--emas); padding-bottom: .2rem; }
    .pg-pager a:hover { color: var(--emas); }
    .pg-pager .is-off { font-size: .95rem; color: rgba(242,244,236,.35); }
    .pg-pager-info { font-size: .9rem; color: rgba(242,244,236,.6); }
</style>

<div class="pg">

    {{-- HERO --}}
    <header class="pg-hero">
        {{-- TODO: ganti foto dengan foto asli (suasana perkebunan, pabrik, atau kegiatan edukasi) --}}
        <img class="pg-bg" src="{{ asset('images/sawitangkong.jpg') }}" alt="" onerror="this.style.display='none'">
        <div class="pg-hero-inner">
            <nav class="pg-crumb" aria-label="Breadcrumb">
                <a href="{{ url('/') }}">Beranda</a>
                <span aria-hidden="true">/</span>
                <span aria-current="page">Pengetahuan</span>
            </nav>
            <p class="pg-label">Media</p>
            <h1 class="pg-title">SahabatPedia</h1>
            <p class="pg-lead">
                Ruang informasi yang menghadirkan pengetahuan, fakta menarik, berita, dan wawasan seputar kelapa sawit.
            </p>
        </div>
    </header>

    {{-- DAFTAR ARTIKEL --}}
    <section class="pg-sec">
        @if($pengetahuans->isEmpty())
            <p class="pg-empty">Belum ada konten pengetahuan yang dipublikasikan.</p>
        @else
            <p class="pg-count">{{ number_format($pengetahuans->total(), 0, ',', '.') }} artikel</p>

            <div class="pg-list">
                @foreach($pengetahuans as $item)
                <a href="{{ route('media.pengetahuan.show', $item->slug) }}" class="pg-row">
                    <div class="pg-thumb">
                        @if($item->gambar)
                            <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->judul }}" loading="lazy" onerror="this.style.display='none'">
                        @endif
                    </div>
                    <div>
                        <p class="pg-cat">{{ $item->kategoriData?->nama ?? 'Pengetahuan' }}</p>
                        <h2>{{ $item->judul }}</h2>
                        <p class="pg-excerpt">{{ $item->ringkasan_teks }}</p>
                        <span class="pg-more">Baca selengkapnya</span>
                    </div>
                </a>
                @endforeach
            </div>

            @if($pengetahuans->hasPages())
            <nav class="pg-pager" aria-label="Halaman pengetahuan">
                @if($pengetahuans->onFirstPage())
                    <span class="is-off">Sebelumnya</span>
                @else
                    <a href="{{ $pengetahuans->previousPageUrl() }}" rel="prev">Sebelumnya</a>
                @endif

                <span class="pg-pager-info">Halaman {{ $pengetahuans->currentPage() }} dari {{ $pengetahuans->lastPage() }}</span>

                @if($pengetahuans->hasMorePages())
                    <a href="{{ $pengetahuans->nextPageUrl() }}" rel="next">Selanjutnya</a>
                @else
                    <span class="is-off">Selanjutnya</span>
                @endif
            </nav>
            @endif
        @endif
    </section>

</div>
@endsection