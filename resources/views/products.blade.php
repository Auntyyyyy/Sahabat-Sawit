@extends('layouts.app')

@section('title', 'Produk — PT Sahabat Sawit')

@section('content')

@include('products._hero')

@php
    // Nama kategori yang ramah dibaca (nilai di database: CPO, PKO, Kernel, Turunan). Kategori lain tampil apa adanya.
    $labelKategori = ['CPO' => 'CPO', 'PKO' => 'PKO', 'Kernel' => 'Inti Sawit', 'Turunan' => 'Produk Turunan'];
    $kategoriList  = $products->pluck('category')->filter()->unique()->values();

    // Jenis kelapa sawit: foto, deskripsi, dan tiga ciri singkat (diringkas dari deskripsinya).
    $jenisSawit = [
        ['nama' => 'Tenera',   'foto' => 'tenera.png',   'alt' => 'Kelapa Sawit Jenis Tenera',
         'teks' => 'Hasil persilangan Dura dan Pisifera. Cangkang tipis dengan rendemen minyak tinggi, menjadi jenis paling banyak dibudidayakan secara komersial.',
         'ciri' => ['Cangkang tipis', 'Rendemen minyak tinggi', 'Paling banyak dibudidayakan']],
        ['nama' => 'Dura',     'foto' => 'dura.png',     'alt' => 'Kelapa Sawit Jenis Dura',
         'teks' => 'Memiliki cangkang tebal dan daging buah relatif tipis. Umumnya digunakan sebagai induk dalam program persilangan bibit unggul.',
         'ciri' => ['Cangkang tebal', 'Daging buah tipis', 'Induk persilangan']],
        ['nama' => 'Pisifera', 'foto' => 'pisifera.png', 'alt' => 'Kelapa Sawit Jenis Pisifera',
         'teks' => 'Hampir tidak memiliki cangkang dengan daging buah tebal, namun produktivitas rendah sehingga jarang ditanam secara mandiri.',
         'ciri' => ['Hampir tanpa cangkang', 'Daging buah tebal', 'Produktivitas rendah']],
    ];
@endphp

<style>
    /* Semua section di bawah hero memakai gaya Home (gelap kehijauan). Class berawalan "pr-". */
    @import url('https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,600;12..96,800&display=swap');

    .pr {
        --hijau: #16321f; --hijau-muda: #2f5a3a; --emas: #e3a72f; --krem: #f2f4ec;
        font-family: "Bricolage Grotesque", system-ui, -apple-system, "Segoe UI", sans-serif;
        background: var(--hijau); color: var(--krem); line-height: 1.6;
    }
    .pr *, .pr *::before, .pr *::after { box-sizing: border-box; }
    .pr a { color: inherit; text-decoration: none; }
    .pr a:focus-visible, .pr button:focus-visible { outline: 3px solid var(--krem); outline-offset: 3px; }

    .pr-sec { --gutter: max(clamp(1.25rem, 5vw, 3.5rem), calc((100vw - 80rem) / 2)); padding: clamp(4rem, 10vh, 7rem) var(--gutter); }
    .pr-sec + .pr-sec { border-top: 1px solid rgba(242,244,236,.12); }
    .pr-head { display: flex; align-items: flex-end; justify-content: space-between; flex-wrap: wrap; gap: 1rem; }
    .pr-title { font-size: clamp(2.2rem, 6vw, 4.5rem); font-weight: 800; line-height: 1.02; letter-spacing: -.04em; max-width: 18ch; }
    .pr-label { font-size: .95rem; color: rgba(242,244,236,.6); }
    .pr-lead { margin-top: 1.25rem; max-width: 36rem; color: rgba(242,244,236,.75); }
    .pr-muted { color: rgba(242,244,236,.7); }
    .pr-mt { margin-top: clamp(2rem, 5vh, 3.5rem); }
    .pr-split { display: grid; grid-template-columns: 1fr; gap: 3rem; align-items: center; }
    @media (min-width: 900px) { .pr-split { grid-template-columns: 1fr 1fr; } }
    .pr-link { display: inline-flex; align-items: center; gap: .55rem; font-weight: 600; font-size: .95rem; border-bottom: 2px solid var(--emas); padding-bottom: .2rem; }
    .pr-link:hover { color: var(--emas); }
    .pr-photo { display: block; width: 100%; height: clamp(260px, 34vw, 420px); object-fit: cover; border-radius: .25rem; }

    /* ===== Katalog ===== */
    .pr-cat-head { display: flex; flex-wrap: wrap; align-items: flex-end; justify-content: space-between; gap: 1rem 2rem; }
    .pr-count { margin-bottom: .4rem; font-size: .95rem; font-weight: 600; color: rgba(242,244,236,.65); }
    .pr-count span { color: var(--emas); }

    /* Filter kategori (chip) */
    .pr-filters { margin-top: 2rem; display: flex; flex-wrap: wrap; gap: .6rem; }
    .pr-filter { display: inline-flex; align-items: center; gap: .5rem; padding: .5rem 1.05rem; border: 1px solid rgba(242,244,236,.25); border-radius: 99px; background: transparent; font: inherit; font-weight: 600; font-size: .9rem; line-height: 1.2; color: rgba(242,244,236,.8); cursor: pointer; transition: background .2s, border-color .2s, color .2s; }
    .pr-filter small { font-size: .75rem; font-weight: 700; opacity: .7; }
    .pr-filter:hover { border-color: rgba(242,244,236,.6); color: var(--krem); }
    .pr-filter[aria-pressed="true"] { background: var(--emas); border-color: var(--emas); color: var(--hijau); }

    /* Kartu produk */
    .pr-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(min(100%, 16.5rem), 1fr)); gap: 1.5rem; }
    .pr-card { position: relative; display: flex; flex-direction: column; overflow: hidden; border: 1px solid rgba(242,244,236,.12); border-radius: .5rem; background: rgba(242,244,236,.05); transition: transform .3s, border-color .3s, background .3s; }
    .pr-card[hidden] { display: none; }
    .pr-card-img { position: relative; aspect-ratio: 4 / 3; overflow: hidden; background: linear-gradient(145deg, var(--hijau-muda), #1d4129); }
    .pr-card-img::after { content: ''; position: absolute; inset: 0; background: linear-gradient(0deg, rgba(15,36,23,.55) 0%, transparent 55%); pointer-events: none; }
    .pr-card-img img, .pr-step-img img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform .6s ease; }
    .pr-card-tag { position: absolute; z-index: 1; left: .75rem; top: .75rem; padding: .15rem .7rem; border-radius: 99px; background: rgba(15,36,23,.8); color: var(--emas); font-size: .75rem; font-weight: 700; }
    .pr-card-body { display: flex; flex: 1; flex-direction: column; padding: 1.1rem 1.25rem 1.25rem; }
    .pr-card-name { font-family: 'Archivo', sans-serif; font-weight: 900; font-style: italic; text-transform: uppercase; font-size: 1.15rem; line-height: 1.1; letter-spacing: -.01em; }
    .pr-card-desc { margin-top: .6rem; font-size: .92rem; color: rgba(242,244,236,.7); display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
    .pr-card-go { margin-top: auto; padding-top: 1.1rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem; font-weight: 700; font-size: .9rem; }
    .pr-card-arrow { flex: none; display: flex; align-items: center; justify-content: center; width: 2.25rem; height: 2.25rem; border: 1px solid rgba(242,244,236,.35); border-radius: 50%; transition: background .25s, border-color .25s, color .25s, transform .25s; }
    .pr-card-arrow svg { width: 1rem; height: 1rem; }
    .pr-card:hover { border-color: rgba(227,167,47,.5); background: rgba(242,244,236,.09); }
    .pr-card:hover .pr-card-img img { transform: scale(1.06); }
    .pr-card:hover .pr-card-arrow { background: var(--emas); border-color: var(--emas); color: var(--hijau); }
    @media (hover: hover) and (prefers-reduced-motion: no-preference) {
        .pr-card:hover { transform: translateY(-5px); }
        .pr-card:hover .pr-card-arrow { transform: translateX(3px); }
    }

    /* Kemunculan bertahap saat kartu masuk layar (hanya aktif bila JavaScript jalan) */
    @keyframes prUp { from { opacity: 0; transform: translateY(18px); } }
    .pr-js .pr-card:not(.is-in) { opacity: 0; }
    @media (prefers-reduced-motion: no-preference) { .pr-js .pr-card.is-in { animation: prUp .5s ease backwards; animation-delay: calc(var(--i, 0) * 60ms); } }

    /* Proses: section menempel di layar; scroll ke bawah menggeser kartu ke samping, scroll ke atas normal */
    .pr-hs { --gutter: max(clamp(1.25rem, 5vw, 3.5rem), calc((100vw - 80rem) / 2)); position: relative; border-top: 1px solid rgba(242,244,236,.12); }
    .pr-hs + .pr-sec { border-top: 1px solid rgba(242,244,236,.12); }
    .pr-hs-pin { position: sticky; top: 0; min-height: 100vh; overflow: hidden; display: flex; flex-direction: column; justify-content: center; gap: clamp(1.25rem, 3vh, 2.5rem); padding: 7rem 0 2.5rem; }
    .pr-hs.is-flow .pr-hs-pin { position: static; }
    .pr-hs-head { padding: 0 var(--gutter); }
    .pr-hs-track { display: flex; gap: 1.25rem; width: max-content; padding: 0 var(--gutter); will-change: transform; }
    .pr-hs .pr-step { flex: 0 0 clamp(18rem, 32vw, 30rem); }
    .pr-step-img { aspect-ratio: 4 / 3; overflow: hidden; border-radius: .25rem; background: linear-gradient(145deg, var(--hijau-muda), #1d4129); }
    .pr-hs .pr-step-img { aspect-ratio: auto; height: clamp(13rem, 40vh, 24rem); }
    .pr-step-no { margin-top: 1rem; font-size: .85rem; font-weight: 600; color: var(--emas); }
    .pr-step p { margin-top: .2rem; font-weight: 600; line-height: 1.35; }
    @media (max-height: 760px) { .pr-hs .pr-lead { display: none; } }
    /* Cadangan (kurangi gerakan / kartu muat di layar): baris biasa yang bisa digeser */
    .pr-hs.is-native .pr-hs-pin { position: static; min-height: 0; padding: clamp(4rem, 10vh, 7rem) 0; }
    .pr-hs.is-native .pr-hs-track { width: auto; overflow-x: auto; scrollbar-width: none; }

    /* Mengenal sawit: foto berbingkai emas setinggi kolom teks, poin berupa kartu kecil */
    .pr-clip { overflow-x: clip; }
    .pr-about { display: grid; grid-template-columns: minmax(0, 1fr); gap: 3.5rem; align-items: stretch; }
    @media (min-width: 900px) { .pr-about { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 5rem; } }
    .pr-about-photo { position: relative; isolation: isolate; margin: 0; }
    .pr-about-photo::before { content: ''; position: absolute; z-index: -1; inset: 1rem -1rem -1rem 1rem; border: 2px solid var(--emas); border-radius: .5rem; }
    .pr-about-photo img { display: block; width: 100%; height: clamp(280px, 60vw, 440px); object-fit: cover; border-radius: .5rem; background: linear-gradient(145deg, var(--hijau-muda), #1d4129); }
    @media (min-width: 900px) {
        .pr-about-photo { min-height: 26rem; }
        .pr-about-photo img { position: absolute; inset: 0; height: 100%; }   /* mengikuti tinggi kolom teks */
    }
    .pr-about-cap { position: absolute; left: -.75rem; bottom: 1.75rem; padding: .55rem 1rem; border-radius: .25rem; background: var(--emas); color: var(--hijau); font-weight: 700; font-size: .95rem; box-shadow: 0 14px 30px rgba(0,0,0,.3); }
    .pr-checks { list-style: none; margin: 2rem 0 0; padding: 0; display: grid; grid-template-columns: minmax(0, 1fr); gap: .75rem; }
    @media (min-width: 640px) { .pr-checks { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    .pr-checks li { display: flex; align-items: center; gap: .75rem; padding: .8rem 1rem; border: 1px solid rgba(242,244,236,.14); border-radius: .5rem; background: rgba(242,244,236,.05); font-weight: 600; line-height: 1.3; }
    .pr-check-ico { flex: none; display: flex; align-items: center; justify-content: center; width: 1.75rem; height: 1.75rem; border-radius: 50%; background: rgba(227,167,47,.15); color: var(--emas); }
    .pr-check-ico svg { width: 1rem; height: 1rem; }

    /* Jenis kelapa sawit: kartu berfoto dengan nomor dan ciri singkat */
    .pr-types { display: grid; grid-template-columns: minmax(0, 1fr); gap: 1.5rem; }
    @media (min-width: 900px) { .pr-types { grid-template-columns: repeat(3, minmax(0, 1fr)); } }
    .pr-type { display: flex; flex-direction: column; overflow: hidden; border: 1px solid rgba(242,244,236,.12); border-radius: .5rem; background: rgba(242,244,236,.05); transition: transform .3s, border-color .3s, background .3s; }
    .pr-type-img { position: relative; aspect-ratio: 4 / 3; overflow: hidden; background: #fafaf8; }
    .pr-type-img img { display: block; width: 100%; height: 100%; object-fit: cover; transition: transform .6s ease; }
    .pr-type-no { position: absolute; left: .75rem; top: .75rem; padding: .15rem .7rem; border-radius: 99px; background: rgba(15,36,23,.85); color: var(--emas); font-size: .75rem; font-weight: 700; }
    .pr-type-body { display: flex; flex: 1; flex-direction: column; padding: 1.25rem 1.25rem 1.4rem; }
    .pr-type h3 { font-size: 1.6rem; font-weight: 800; letter-spacing: -.025em; line-height: 1.1; }
    .pr-type p { margin-top: .6rem; color: rgba(242,244,236,.72); }
    .pr-traits { list-style: none; margin: auto 0 0; padding: 1.1rem 0 0; display: flex; flex-wrap: wrap; gap: .4rem; }
    .pr-traits li { padding: .2rem .7rem; border: 1px solid rgba(242,244,236,.25); border-radius: 99px; font-size: .8rem; font-weight: 600; color: rgba(242,244,236,.88); }
    .pr-type:hover { border-color: rgba(227,167,47,.5); background: rgba(242,244,236,.09); }
    .pr-type:hover .pr-type-img img { transform: scale(1.06); }
    @media (hover: hover) and (prefers-reduced-motion: no-preference) { .pr-type:hover { transform: translateY(-5px); } }

    /* Sebaran wilayah: peta utuh (tidak dipotong) + daftar wilayah */
    .pr-geo { display: grid; grid-template-columns: 1fr; gap: 2.5rem; align-items: start; }
    @media (min-width: 900px) {
        .pr-geo { grid-template-columns: minmax(0, 1.05fr) minmax(0, 1fr); gap: 4rem; }
        .pr-map { position: sticky; top: 7rem; }   /* peta ikut menempel saat daftar di kanan digulir */
    }
    .pr-map { margin: 0; }
    .pr-map-frame { padding: clamp(.75rem, 2vw, 1.25rem); border-radius: .25rem; background: #fafaf8; }
    .pr-map-frame img { display: block; width: 100%; height: auto; }
    .pr-map figcaption { margin-top: .75rem; font-size: .85rem; color: rgba(242,244,236,.55); }

    .pr-geo-title { margin-top: .5rem; max-width: 20ch; font-size: clamp(1.6rem, 3.2vw, 2.4rem); font-weight: 800; line-height: 1.08; letter-spacing: -.03em; }
    .pr-regions { margin: 2rem 0 0; padding: 0; list-style: none; }
    .pr-region { display: grid; grid-template-columns: 2.5rem 1fr; gap: .25rem 1rem; padding: 1.1rem 0; border-top: 1px solid rgba(242,244,236,.15); }
    .pr-region:last-child { border-bottom: 1px solid rgba(242,244,236,.15); }
    .pr-region.is-main { margin: .35rem 0; padding: 1.1rem 1rem; border: 0; border-left: 3px solid var(--emas); border-radius: .25rem; background: rgba(242,244,236,.07); }
    .pr-region.is-main + .pr-region { border-top: 0; }
    .pr-rank { font-size: .85rem; font-weight: 600; color: rgba(242,244,236,.5); padding-top: .25rem; }
    .pr-region.is-main .pr-rank { color: var(--emas); }
    .pr-region h4 { font-size: 1.15rem; font-weight: 700; line-height: 1.3; display: flex; align-items: center; flex-wrap: wrap; gap: .5rem .75rem; }
    .pr-badge { font-size: .75rem; font-weight: 600; padding: .1rem .65rem; border-radius: 99px; background: var(--emas); color: var(--hijau); }
    .pr-region p { margin-top: .25rem; font-size: .95rem; color: rgba(242,244,236,.7); }
    .pr-links { margin-top: 2rem; display: flex; flex-wrap: wrap; gap: 1rem 2rem; }
</style>

<div class="pr">

    {{-- 1. KATALOG PRODUK: filter kategori + kartu berdeskripsi --}}
    <section class="pr-sec" id="katalog">
        <div class="pr-cat-head">
            <div>
                <p class="pr-label">Katalog</p>
                <h2 class="pr-title" style="margin-top:.5rem">Produk Unggulan Kami</h2>
                <p class="pr-lead">Berbagai hasil olahan kelapa sawit berkualitas tinggi, diproduksi dengan standar mutu terbaik.</p>
            </div>
            @if($products->isNotEmpty())
                <p class="pr-count"><span id="prCount">{{ $products->count() }}</span> produk</p>
            @endif
        </div>

        @if($kategoriList->count() > 1)
        <div class="pr-filters" role="group" aria-label="Filter kategori produk">
            <button type="button" class="pr-filter" data-pr-filter="*" aria-pressed="true">Semua <small>{{ $products->count() }}</small></button>
            @foreach($kategoriList as $kat)
                <button type="button" class="pr-filter" data-pr-filter="{{ $kat }}" aria-pressed="false">
                    {{ $labelKategori[$kat] ?? $kat }} <small>{{ $products->where('category', $kat)->count() }}</small>
                </button>
            @endforeach
        </div>
        @endif

        <div class="pr-grid pr-mt">
            @forelse($products as $product)
            <a href="{{ route('products.show', $product->slug) }}" class="pr-card" data-pr-card data-cat="{{ $product->category }}" style="--i: {{ $loop->index % 6 }}">
                <div class="pr-card-img">
                    @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" loading="lazy" onerror="this.style.display='none'">
                    @endif
                    @if($product->category)
                        <span class="pr-card-tag">{{ $labelKategori[$product->category] ?? $product->category }}</span>
                    @endif
                </div>
                <div class="pr-card-body">
                    <h3 class="pr-card-name">{{ $product->name }}</h3>
                    @if($product->description)
                        <p class="pr-card-desc">{{ $product->description }}</p>
                    @endif
                    <span class="pr-card-go">
                        Lihat detail
                        <span class="pr-card-arrow" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </span>
                    </span>
                    <span class="sr-only">{{ $product->name }}</span>
                </div>
            </a>
            @empty
            <p class="pr-muted">Belum ada produk yang ditambahkan.</p>
            @endforelse
        </div>
    </section>

    {{-- 2. PROSES: menempel saat scroll turun (kartu bergeser ke samping); saat scroll naik halaman bergerak normal --}}
    <section class="pr-hs" id="prProses">
        <div class="pr-hs-pin">
            <div class="pr-hs-head">
                <p class="pr-label">Dari Kebun ke Produk</p>
                <h2 class="pr-title" style="margin-top:.5rem;font-size:clamp(2rem,4.5vw,3.5rem)">Proses & Produk</h2>
                <p class="pr-lead">Setiap tahap diawasi dengan standar mutu ketat, mulai dari panen hingga produk siap didistribusikan.</p>
            </div>
            <div class="pr-hs-track" id="prProsesTrack">
                @foreach($processSteps as $step)
                <figure class="pr-step" style="margin:0">
                    <div class="pr-step-img">
                        <img src="{{ asset('images/' . $step['image']) }}" alt="{{ $step['alt'] }}" loading="lazy" onerror="this.style.display='none'">
                    </div>
                    <figcaption>
                        <p class="pr-step-no">{{ $step['step'] }}</p>
                        <p>{{ $step['label'] }}</p>
                    </figcaption>
                </figure>
                @endforeach
            </div>
        </div>
    </section>

    {{-- 3. MENGENAL KELAPA SAWIT --}}
    <section class="pr-sec pr-clip">
        <div class="pr-about">
            <figure class="pr-about-photo">
                <img src="{{ asset('images/sawitangkong.jpg') }}" alt="Mengenal Kelapa Sawit" onerror="this.closest('.pr-about-photo').style.display='none'">
                <figcaption class="pr-about-cap"><em>Elaeis guineensis</em></figcaption>
            </figure>

            <div class="pr-about-text">
                <p class="pr-label">Mengenal Kelapa Sawit</p>
                <h2 class="pr-title" style="margin-top:.5rem;font-size:clamp(1.8rem,4vw,3rem)">Sumber Daya Alam dengan Segudang Manfaat</h2>
                <p class="pr-lead">
                    Kelapa sawit (<em>Elaeis guineensis</em>) adalah tanaman perkebunan tropis yang menjadi salah satu komoditas unggulan Indonesia.
                    Setiap tandan buah segar (TBS) diolah menjadi berbagai produk turunan bernilai tinggi, mulai dari minyak goreng,
                    minyak inti sawit (PKO), hingga bahan baku industri pangan dan non-pangan.
                </p>
                <p class="pr-lead" style="margin-top:1rem">
                    Melalui pengelolaan perkebunan yang berkelanjutan, PT Sahabat Sawit berkomitmen menghasilkan produk berkualitas
                    tinggi sekaligus menjaga kelestarian lingkungan dan kesejahteraan masyarakat sekitar.
                </p>
                <ul class="pr-checks">
                    @foreach(['Diolah dari TBS pilihan', 'Standar mutu terjaga', 'Perkebunan berkelanjutan', 'Ramah lingkungan'] as $poin)
                    <li>
                        <span class="pr-check-ico" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <span>{{ $poin }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </section>

    {{-- 4. JENIS & SEBARAN --}}
    <section class="pr-sec">
        <p class="pr-label">Jenis & Sebaran</p>
        <h2 class="pr-title" style="margin-top:.5rem">Jenis Kelapa Sawit dan Daerah Penghasil</h2>

        <div class="pr-types pr-mt">
            @foreach($jenisSawit as $j)
            <article class="pr-type">
                <div class="pr-type-img">
                    <img src="{{ asset('images/' . $j['foto']) }}" alt="{{ $j['alt'] }}" loading="lazy" onerror="this.style.display='none'">
                    <span class="pr-type-no">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="pr-type-body">
                    <h3>{{ $j['nama'] }}</h3>
                    <p>{{ $j['teks'] }}</p>
                    <ul class="pr-traits">
                        @foreach($j['ciri'] as $c)
                            <li>{{ $c }}</li>
                        @endforeach
                    </ul>
                </div>
            </article>
            @endforeach
        </div>

        {{-- Sebaran wilayah: peta utuh di kiri (menempel saat digulir), daftar wilayah di kanan --}}
        <div class="pr-geo pr-mt">
            <figure class="pr-map">
                <div class="pr-map-frame">
                    <img src="{{ asset('images/peta.png') }}" alt="Peta Daerah Penghasil Kelapa Sawit di Indonesia" onerror="this.style.display='none'">
                </div>
                <figcaption>Peta daerah penghasil kelapa sawit di Indonesia</figcaption>
            </figure>

            <div>
                <p class="pr-label">Sebaran Wilayah</p>
                <h3 class="pr-geo-title">Daerah Penghasil Utama di Indonesia</h3>
                <p class="pr-lead">Indonesia merupakan produsen kelapa sawit terbesar di dunia, dengan sebagian besar perkebunan terkonsentrasi di Pulau Sumatera dan Kalimantan.</p>

                <ol class="pr-regions">
                    <li class="pr-region is-main">
                        <span class="pr-rank">01</span>
                        <div>
                            <h4>Riau <span class="pr-badge">Lokasi Kebun Kami</span></h4>
                            <p>Provinsi dengan luas areal sawit terbesar di Indonesia, termasuk lokasi kebun PT. Sahabat Sawit Rokan Sejahtera.</p>
                        </div>
                    </li>
                    <li class="pr-region">
                        <span class="pr-rank">02</span>
                        <div><h4>Sumatera Utara</h4><p>Sentra sawit tertua dengan infrastruktur pengolahan yang matang.</p></div>
                    </li>
                    <li class="pr-region">
                        <span class="pr-rank">03</span>
                        <div><h4>Kalimantan Tengah & Barat</h4><p>Wilayah ekspansi perkebunan sawit terbesar dalam dua dekade terakhir.</p></div>
                    </li>
                    <li class="pr-region">
                        <span class="pr-rank">04</span>
                        <div><h4>Sumatera Selatan & Jambi</h4><p>Kontributor produksi sawit yang terus berkembang di kawasan Sumatera bagian selatan.</p></div>
                    </li>
                </ol>
            </div>
        </div>
    </section>

    {{-- 5. AJAKAN BERTINDAK --}}
    <section class="pr-sec">
        <h2 class="pr-title">Butuh penawaran atau sampel produk?</h2>
        <p class="pr-lead">Tim kami siap membantu kebutuhan Anda. Hubungi kami lewat WhatsApp atau halaman kontak.</p>
        <div class="pr-links">
            <a class="pr-link" href="https://wa.me/628139654581" target="_blank" rel="noopener noreferrer">WhatsApp</a>
            <a class="pr-link" href="{{ route('contact') }}">Halaman Kontak</a>
        </div>
    </section>

</div>

<script>
    // Katalog: kartu muncul bertahap saat masuk layar + filter kategori (jumlah produk ikut diperbarui).
    (function () {
        const root = document.querySelector('.pr');
        const cards = [...document.querySelectorAll('[data-pr-card]')];
        const chips = [...document.querySelectorAll('[data-pr-filter]')];
        const count = document.getElementById('prCount');
        if (!root || !cards.length) return;

        root.classList.add('pr-js');
        const calm = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        if ('IntersectionObserver' in window && !calm) {
            const io = new IntersectionObserver(function (entries) {
                entries.forEach(function (e) {
                    if (e.isIntersecting) { e.target.classList.add('is-in'); io.unobserve(e.target); }
                });
            }, { threshold: 0.12 });
            cards.forEach(function (c) { io.observe(c); });
        } else {
            cards.forEach(function (c) { c.classList.add('is-in'); });
        }

        chips.forEach(function (chip) {
            chip.addEventListener('click', function () {
                const value = chip.dataset.prFilter;
                chips.forEach(function (c) { c.setAttribute('aria-pressed', c === chip ? 'true' : 'false'); });
                let shown = 0;
                cards.forEach(function (card) {
                    const show = value === '*' || card.dataset.cat === value;
                    card.hidden = !show;
                    if (show) shown++;
                });
                if (count) count.textContent = shown;
            });
        });
    })();

    // Proses: saat scroll KE BAWAH section menempel dan kartu bergeser ke samping.
    // Saat scroll KE ATAS pin dilepas: halaman bergerak normal dan kartu tidak ikut mundur.
    (function () {
        const sec = document.getElementById('prProses');
        const track = document.getElementById('prProsesTrack');
        if (!sec || !track) return;
        const pin = sec.querySelector('.pr-hs-pin');
        const reduce = window.matchMedia('(prefers-reduced-motion: reduce)');
        document.documentElement.style.overflowAnchor = 'none';

        let dist = 0, p = 0, expanded = true, native = false, lastY = window.scrollY;

        const secTop = function () { return sec.getBoundingClientRect().top + window.scrollY; };
        const jump = function (y) { window.scrollTo({ top: y, left: 0, behavior: 'instant' }); lastY = y; };
        const draw = function () { track.style.transform = 'translate3d(' + (-dist * p) + 'px,0,0)'; };
        function setExpanded(on) {
            expanded = on;
            sec.classList.toggle('is-flow', !on);
            sec.style.height = on ? (pin.offsetHeight + dist) + 'px' : '';
        }

        function onScroll() {
            if (native) return;
            const y = window.scrollY, dir = y - lastY;
            lastY = y;
            const top = secTop(), vh = window.innerHeight;

            if (expanded) {
                const s = y - top;
                if (dir < 0 && s > 0 && s < dist) {        // mulai naik di area pin: lepas pin
                    setExpanded(false); draw(); jump(top); return;
                }
                if (s <= 0) p = 0;
                else if (s >= dist) p = 1;
                else p = Math.max(p, s / dist);             // turun: kartu hanya maju
                draw();
            } else {
                const rel = top - y;                        // posisi atas section terhadap layar
                if (rel >= vh) { p = 0; draw(); setExpanded(true); }                       // section sudah di luar layar: ulang dari awal
                else if (dir > 0 && rel <= 0) { setExpanded(true); jump(top + p * dist); draw(); }  // turun lagi: lanjut dari posisi terakhir
            }
        }

        function layout() {
            sec.classList.remove('is-native', 'is-flow');
            sec.style.height = '';
            track.style.transform = '';
            dist = Math.max(0, track.scrollWidth - window.innerWidth);
            if (reduce.matches || dist < 8) { native = true; sec.classList.add('is-native'); return; }
            native = false;
            setExpanded(true);
            lastY = window.scrollY;
            onScroll();
        }

        window.addEventListener('scroll', onScroll, { passive: true });
        window.addEventListener('resize', layout);
        window.addEventListener('load', layout);
        layout();
    })();
</script>

@endsection