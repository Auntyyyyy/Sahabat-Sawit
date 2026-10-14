@extends('layouts.app')

@section('title', 'Produk — PT Sahabat Sawit')

@section('content')

{{-- =====================================================================
     HERO PRODUK
     CSS-nya mandiri di bawah (awalan "prodhero-"), tidak memakai class
     hero-product-slider / hero-slide-* yang lama di style.css.
     ===================================================================== --}}
<style>
    .prodhero{
        --prodhero-dur: 6500ms;
        position: relative;
        overflow: hidden;
        isolation: isolate;
        min-height: clamp(620px, 86vh, 800px);
        background: var(--dark-green, #123524);
        color: #fff;
    }

    /* ---------- Slide (crossfade) ---------- */
    .prodhero-slides{ position:absolute; inset:0; }
    .prodhero-slide{
        position:absolute; inset:0;
        opacity:0; visibility:hidden; z-index:0;
        transition: opacity .9s ease, visibility 0s linear .9s;
    }
    .prodhero-slide.is-active{
        opacity:1; visibility:visible; z-index:1;
        transition: opacity .9s ease, visibility 0s;
    }

    /* Zoom pelan pada foto slide aktif; direset setelah slide selesai memudar */
    .prodhero-img{
        position:absolute; inset:0; width:100%; height:100%;
        object-fit:cover;
        transform: scale(1);
        transition: transform 0s linear .9s;
    }
    .prodhero-slide.is-active .prodhero-img{
        transform: scale(1.07);
        transition: transform 9s ease-out;
    }

    /* Gradasi: gelap di kiri (teks), bawah (kontrol), dan atas (navbar/breadcrumb) */
    .prodhero-shade{
        position:absolute; inset:0;
        background:
            linear-gradient(90deg,  rgb(var(--dark-green-rgb, 18 53 36) / .92) 0%, rgb(var(--dark-green-rgb, 18 53 36) / .74) 42%, rgb(var(--dark-green-rgb, 18 53 36) / .28) 80%),
            linear-gradient(0deg,   rgb(var(--dark-green-rgb, 18 53 36) / .85) 0%, rgb(var(--dark-green-rgb, 18 53 36) / 0) 38%),
            linear-gradient(180deg, rgb(var(--dark-green-rgb, 18 53 36) / .55) 0%, rgb(var(--dark-green-rgb, 18 53 36) / 0) 26%);
    }
    .prodhero-pattern{
        position:absolute; inset:0; opacity:.5; pointer-events:none;
        background-image: radial-gradient(rgba(255,255,255,.06) 1px, transparent 1px);
        background-size: 22px 22px;
    }

    /* ---------- Isi slide ---------- */
    .prodhero-inner{
        position:relative; height:100%;
        max-width:80rem; margin:0 auto;
        padding: 0 1.5rem 7.5rem;
        display:flex; align-items:flex-end;
    }
    @media (min-width:1024px){ .prodhero-inner{ padding-left:2rem; padding-right:2rem; } }
    .prodhero-content{ max-width:38rem; }

    .prodhero-eyebrow{
        display:inline-flex; align-items:center; gap:.5rem;
        padding:.35rem .85rem; border-radius:999px;
        background:rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.2);
        font-size:.75rem; font-weight:600; letter-spacing:.08em; text-transform:uppercase;
        color: var(--light-green, #8DBF44);
    }
    .prodhero-eyebrow::before{
        content:''; width:.4rem; height:.4rem; border-radius:50%; background:currentColor;
    }
    .prodhero-title{
        margin-top:1rem;
        font-family: var(--font-heading, inherit);
        font-weight:700;
        font-size: clamp(2rem, 4.8vw, 3.6rem);
        line-height:1.1; letter-spacing:-.01em;
    }
    .prodhero-desc{
        margin-top:1rem;
        color: rgba(255,255,255,.82);
        line-height:1.7; font-size:1rem;
        display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden;
    }
    .prodhero-chips{
        margin:1.25rem 0 0; padding:0; list-style:none;
        display:flex; flex-wrap:wrap; gap:.5rem;
    }
    .prodhero-chip{
        display:inline-flex; align-items:center; gap:.45rem;
        padding:.4rem .8rem; border-radius:999px;
        background:rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.2);
        font-size:.8rem; font-weight:500; color:rgba(255,255,255,.9);
        backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px);
    }
    .prodhero-chip i{ color: var(--gold, #D4A017); }

    .prodhero-actions{ margin-top:1.75rem; display:flex; flex-wrap:wrap; gap:.75rem; }
    .prodhero-btn{
        display:inline-flex; align-items:center; gap:.6rem;
        padding:.85rem 1.6rem; border-radius:999px;
        font-weight:600; font-size:.9rem; text-decoration:none;
        transition: background .2s ease, transform .2s ease, border-color .2s ease, color .2s ease;
    }
    .prodhero-btn svg{ width:1rem; height:1rem; }
    .prodhero-btn:hover{ transform: translateY(-2px); }
    .prodhero-btn-primary{ background: var(--gold, #D4A017); color: var(--dark-green, #123524); }
    .prodhero-btn-primary:hover{ background: var(--light-green, #8DBF44); color: var(--dark-green, #123524); }
    .prodhero-btn-ghost{ border:1.5px solid rgba(255,255,255,.5); color:#fff; }
    .prodhero-btn-ghost:hover{ background:rgba(255,255,255,.12); border-color:#fff; color:#fff; }

    /* Teks slide aktif masuk bertahap (fade + naik) */
    .prodhero-slide.is-active .prodhero-anim{
        animation: prodheroUp .8s cubic-bezier(.22,1,.36,1) both;
        animation-delay: calc(var(--i, 0) * 90ms + 200ms);
    }
    @keyframes prodheroUp{
        from{ opacity:0; transform: translateY(18px); }
        to{   opacity:1; transform: none; }
    }

    /* ---------- Breadcrumb (statis, tidak ikut bergeser) ---------- */
    .prodhero-crumb{ position:absolute; z-index:5; top:0; left:0; right:0; padding-top:7rem; }
    .prodhero-crumb-inner{
        max-width:80rem; margin:0 auto; padding:0 1.5rem;
        display:flex; align-items:center; gap:.5rem;
        font-size:.85rem; color:rgba(255,255,255,.65);
    }
    @media (min-width:1024px){ .prodhero-crumb-inner{ padding-left:2rem; padding-right:2rem; } }
    .prodhero-crumb a{ color:inherit; text-decoration:none; transition: color .2s ease; }
    .prodhero-crumb a:hover{ color:#fff; }
    .prodhero-crumb .is-current{ color:rgba(255,255,255,.95); }

    /* ---------- Kontrol: nomor, progress, jeda, panah ---------- */
    .prodhero-controls{ position:absolute; z-index:5; left:0; right:0; bottom:0; padding-bottom:2rem; }
    .prodhero-controls-inner{
        max-width:80rem; margin:0 auto; padding:0 1.5rem;
        display:flex; align-items:center; gap:1.5rem;
    }
    @media (min-width:1024px){ .prodhero-controls-inner{ padding-left:2rem; padding-right:2rem; } }

    .prodhero-count{
        display:flex; align-items:baseline; gap:.4rem;
        font-family: var(--font-heading, inherit); font-weight:700;
    }
    .prodhero-count b{ font-size:2rem; line-height:1; }
    .prodhero-count span{ font-size:.9rem; opacity:.6; }

    .prodhero-segs{ display:flex; gap:.5rem; flex:1; max-width:26rem; }
    .prodhero-seg{ flex:1; padding:.6rem 0; background:none; border:0; cursor:pointer; }
    .prodhero-seg-track{
        display:block; height:3px; border-radius:99px;
        background:rgba(255,255,255,.28); overflow:hidden;
    }
    .prodhero-seg-fill{ display:block; height:100%; width:0; background: var(--gold, #D4A017); }
    .prodhero-seg.is-done .prodhero-seg-fill{ width:100%; }
    .prodhero-seg.is-active .prodhero-seg-fill{
        animation: prodheroFill var(--prodhero-dur) linear forwards;
    }
    @keyframes prodheroFill{ from{ width:0; } to{ width:100%; } }

    /* Jeda saat di-hover atau saat tombol jeda ditekan */
    .prodhero:hover .prodhero-seg-fill,
    .prodhero.is-paused .prodhero-seg-fill{ animation-play-state: paused; }

    .prodhero-right{ margin-left:auto; display:flex; align-items:center; gap:.6rem; }
    .prodhero-arrow, .prodhero-toggle{
        width:2.75rem; height:2.75rem; border-radius:50%;
        display:flex; align-items:center; justify-content:center;
        border:1.5px solid rgba(255,255,255,.5); background:rgba(255,255,255,.06);
        color:#fff; cursor:pointer;
        transition: background .2s ease, border-color .2s ease;
    }
    .prodhero-arrow:hover, .prodhero-toggle:hover{ background:rgba(255,255,255,.18); border-color:#fff; }
    .prodhero-arrow svg{ width:1.1rem; height:1.1rem; }
    .prodhero-toggle{ width:2.25rem; height:2.25rem; border-color:rgba(255,255,255,.3); }

    /* ---------- Mobile ---------- */
    @media (max-width:640px){
        .prodhero{ min-height:640px; }
        .prodhero-inner{ padding-bottom:6.5rem; }
        .prodhero-desc{ -webkit-line-clamp:2; font-size:.95rem; }
        .prodhero-chips{ display:none; }
        .prodhero-arrow{ display:none; }
        .prodhero-controls-inner{ gap:1rem; }
    }

    /* ---------- Hormati pengaturan "kurangi gerakan" ---------- */
    @media (prefers-reduced-motion: reduce){
        .prodhero-slide, .prodhero-slide.is-active{ transition:none; }
        .prodhero-img, .prodhero-slide.is-active .prodhero-img{ transition:none; transform:none; }
        .prodhero-slide.is-active .prodhero-anim{ animation:none; }
        .prodhero-seg.is-active .prodhero-seg-fill{ animation:none; width:100%; }
    }
</style>

<section id="prodHero" class="prodhero" role="region" aria-roledescription="carousel" aria-label="Produk unggulan PT Sahabat Sawit">

    <h1 class="sr-only">Produk PT Sahabat Sawit Rokan Sejahtera</h1>

    <nav class="prodhero-crumb" aria-label="Breadcrumb">
        <div class="prodhero-crumb-inner">
            <a href="{{ url('/') }}">Beranda</a>
            <span aria-hidden="true">/</span>
            <span class="is-current" aria-current="page">Produk</span>
        </div>
    </nav>

    <div class="prodhero-slides">
        @forelse($products as $index => $product)
        @php
            $heroDesc = \Illuminate\Support\Str::limit(trim(html_entity_decode(strip_tags((string) $product->description))), 220);
            $heroChips = array_filter([
                ['bi-tree',      'Bahan baku',    $product->raw_material],
                ['bi-box-seam',  'Bentuk produk', $product->product_form],
                ['bi-gear-wide-connected', 'Penggunaan', $product->usage],
            ], fn ($c) => !empty($c[2]));
        @endphp
        <article class="prodhero-slide {{ $index === 0 ? 'is-active' : '' }}"
                 role="group" aria-roledescription="slide"
                 aria-label="{{ $index + 1 }} dari {{ $products->count() }}"
                 @if($index !== 0) inert aria-hidden="true" @endif>

            <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/1600x900/164A2E/F5F1E8?text=' . urlencode($product->name) }}"
                 alt=""
                 class="prodhero-img"
                 loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                 decoding="async"
                 {{ $index === 0 ? 'fetchpriority=high' : '' }}
                 onerror="this.onerror=null;this.src='https://placehold.co/1600x900/164A2E/F5F1E8?text={{ urlencode($product->name) }}'">
            <div class="prodhero-shade"></div>

            <div class="prodhero-inner">
                <div class="prodhero-content">
                    <span class="prodhero-eyebrow prodhero-anim" style="--i:0">{{ $product->category ?: 'Produk Kami' }}</span>

                    <h2 class="prodhero-title prodhero-anim" style="--i:1">{{ $product->name }}</h2>

                    @if($heroDesc !== '')
                    <p class="prodhero-desc prodhero-anim" style="--i:2">{{ $heroDesc }}</p>
                    @endif

                    @if(count($heroChips))
                    <ul class="prodhero-chips prodhero-anim" style="--i:3">
                        @foreach($heroChips as $chip)
                        <li class="prodhero-chip" title="{{ $chip[1] }}">
                            <i class="bi {{ $chip[0] }}"></i> {{ $chip[2] }}
                        </li>
                        @endforeach
                    </ul>
                    @endif

                    <div class="prodhero-actions prodhero-anim" style="--i:4">
                        <a href="{{ route('products.show', $product->slug) }}" class="prodhero-btn prodhero-btn-primary">
                            Lihat Detail Produk
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                        <a href="{{ route('contact') }}" class="prodhero-btn prodhero-btn-ghost">Hubungi Kami</a>
                    </div>
                </div>
            </div>
        </article>
        @empty
        <article class="prodhero-slide is-active" role="group" aria-roledescription="slide" aria-label="1 dari 1">
            <div class="prodhero-pattern"></div>
            <div class="prodhero-inner">
                <div class="prodhero-content">
                    <span class="prodhero-eyebrow prodhero-anim" style="--i:0">Produk Kami</span>
                    <h2 class="prodhero-title prodhero-anim" style="--i:1">Hasil Perkebunan Berkualitas</h2>
                    <p class="prodhero-desc prodhero-anim" style="--i:2">
                        Produk unggulan dari perkebunan kelapa sawit PT Sahabat Sawit di Rokan Hilir, Riau.
                    </p>
                    <div class="prodhero-actions prodhero-anim" style="--i:3">
                        <a href="{{ route('contact') }}" class="prodhero-btn prodhero-btn-primary">Hubungi Kami</a>
                    </div>
                </div>
            </div>
        </article>
        @endforelse
    </div>

    @if($products->count() > 1)
    <div class="prodhero-controls">
        <div class="prodhero-controls-inner">
            <div class="prodhero-count" aria-hidden="true">
                <b id="prodheroCur">01</b><span>/ {{ sprintf('%02d', $products->count()) }}</span>
            </div>

            <div class="prodhero-segs">
                @foreach($products as $index => $product)
                <button type="button"
                        class="prodhero-seg {{ $index === 0 ? 'is-active' : '' }}"
                        aria-label="Tampilkan produk {{ $product->name }}"
                        aria-current="{{ $index === 0 ? 'true' : 'false' }}">
                    <span class="prodhero-seg-track"><span class="prodhero-seg-fill"></span></span>
                </button>
                @endforeach
            </div>

            <div class="prodhero-right">
                <button type="button" class="prodhero-toggle" aria-label="Jeda slide otomatis" aria-pressed="false">
                    <i class="bi bi-pause-fill"></i>
                </button>
                <button type="button" class="prodhero-arrow" data-prodhero="prev" aria-label="Produk sebelumnya">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <button type="button" class="prodhero-arrow" data-prodhero="next" aria-label="Produk berikutnya">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    @endif
</section>

<script>
(function () {
    const hero = document.getElementById('prodHero');
    if (!hero) return;

    const slides = [...hero.querySelectorAll('.prodhero-slide')];
    const segs   = [...hero.querySelectorAll('.prodhero-seg')];
    const cur    = document.getElementById('prodheroCur');
    const total  = slides.length;
    if (total < 2) return;

    let current = 0;

    function go(i) {
        i = (i + total) % total;
        if (i === current) return;
        current = i;

        slides.forEach(function (s, idx) {
            const on = idx === current;
            s.classList.toggle('is-active', on);
            s.setAttribute('aria-hidden', on ? 'false' : 'true');
            s.inert = !on;
        });
        segs.forEach(function (s, idx) {
            s.classList.toggle('is-active', idx === current);
            s.classList.toggle('is-done', idx < current);
            s.setAttribute('aria-current', idx === current ? 'true' : 'false');
        });
        if (cur) cur.textContent = String(current + 1).padStart(2, '0');
    }

    // Autoplay digerakkan oleh animasi progress: begitu bar penuh, pindah slide.
    // Otomatis berhenti saat di-hover / dijeda, dan tidak jalan kalau pengguna
    // memilih "kurangi gerakan" (animasi dimatikan lewat CSS).
    hero.addEventListener('animationend', function (e) {
        if (e.animationName === 'prodheroFill' && e.target.closest('.prodhero-seg.is-active')) {
            go(current + 1);
        }
    });

    segs.forEach(function (s, i) { s.addEventListener('click', function () { go(i); }); });

    hero.querySelector('[data-prodhero="prev"]').addEventListener('click', function () { go(current - 1); });
    hero.querySelector('[data-prodhero="next"]').addEventListener('click', function () { go(current + 1); });

    const toggle = hero.querySelector('.prodhero-toggle');
    toggle.addEventListener('click', function () {
        const paused = hero.classList.toggle('is-paused');
        toggle.setAttribute('aria-pressed', paused ? 'true' : 'false');
        toggle.setAttribute('aria-label', paused ? 'Putar slide otomatis' : 'Jeda slide otomatis');
        toggle.querySelector('i').className = paused ? 'bi bi-play-fill' : 'bi bi-pause-fill';
    });

    // Swipe di layar sentuh
    let sx = 0, sy = 0;
    hero.addEventListener('touchstart', function (e) {
        const t = e.changedTouches[0]; sx = t.clientX; sy = t.clientY;
    }, { passive: true });
    hero.addEventListener('touchend', function (e) {
        const t = e.changedTouches[0];
        const dx = t.clientX - sx, dy = t.clientY - sy;
        if (Math.abs(dx) > 50 && Math.abs(dx) > Math.abs(dy) * 1.5) go(current + (dx < 0 ? 1 : -1));
    }, { passive: true });

    // Keyboard (saat fokus berada di dalam hero)
    hero.addEventListener('keydown', function (e) {
        if (e.key === 'ArrowRight') go(current + 1);
        if (e.key === 'ArrowLeft')  go(current - 1);
    });
})();
</script>

<section class="py-16 bg-cream">
    <div class="max-w-6xl mx-auto px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
            <div>
                <img src="{{ asset('images/sawitangkong.jpg') }}" alt="Mengenal Kelapa Sawit"
                     class="rounded-brand shadow-lg w-full h-80 object-cover"
                     onerror="this.src='https://placehold.co/600x400/2E7D32/F5F1E8?text=Kelapa+Sawit'">
            </div>
            <div>
                <span class="font-heading font-semibold text-secondary-green uppercase text-sm tracking-wide">Mengenal Kelapa Sawit</span>
                <h2 class="mt-3 font-heading font-bold text-2xl md:text-4xl text-dark-green">
                    Sumber Daya Alam dengan Segudang Manfaat
                </h2>
                <p class="mt-4 text-gray-text leading-relaxed text-justify">
                    Kelapa sawit (<em>Elaeis guineensis</em>) adalah tanaman perkebunan tropis yang menjadi salah satu komoditas unggulan Indonesia.
                    Setiap tandan buah segar (TBS) diolah menjadi berbagai produk turunan bernilai tinggi, mulai dari minyak goreng,
                    minyak inti sawit (PKO), hingga bahan baku industri pangan dan non-pangan.
                </p>
                <p class="mt-4 text-gray-text leading-relaxed text-justify">
                    Melalui pengelolaan perkebunan yang berkelanjutan, PT Sahabat Sawit berkomitmen menghasilkan produk berkualitas
                    tinggi sekaligus menjaga kelestarian lingkungan dan kesejahteraan masyarakat sekitar.
                </p>

                <div class="cert-badges">
                    <div class="cert-badge">
                        <img src="{{ asset('images/ispo.png') }}" alt="Sertifikasi ISPO"
                            onerror="this.src='https://placehold.co/60x60/164A2E/F5F1E8?text=ISPO'">
                        <div>
                            <span class="cert-badge-title">Tersertifikasi ISPO</span>
                            <span class="cert-badge-desc">Indonesian Sustainable Palm Oil</span>
                        </div>
                    </div>

                    <div class="cert-badge">
                        <img src="{{ asset('images/halal.png') }}" alt="Sertifikasi Halal"
                            onerror="this.src='https://placehold.co/60x60/164A2E/F5F1E8?text=Halal'">
                        <div>
                            <span class="cert-badge-title">Tersertifikasi Halal</span>
                            <span class="cert-badge-desc">BPJPH - Kementerian Agama RI</span>
                        </div>
                    </div>
                </div>

                <div class="feature-grid">
                    <div class="feature-item">
                        <span class="feature-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                        <p>Diolah dari TBS pilihan</p>
                    </div>
                    <div class="feature-item">
                        <span class="feature-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                        <p>Standar mutu terjaga</p>
                    </div>
                    <div class="feature-item">
                        <span class="feature-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                        <p>Perkebunan berkelanjutan</p>
                    </div>
                    <div class="feature-item">
                        <span class="feature-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                        <p>Ramah lingkungan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Jarak section disamakan: sebelumnya py-5, sekarang py-16 seperti section lain --}}
<section class="py-16 bg-white">
    <div class="max-w-6xl mx-auto px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto">
            <span class="font-heading font-semibold text-secondary-green uppercase text-sm tracking-wide">Jenis & Sebaran</span>
            <h2 class="mt-3 font-heading font-bold text-2xl md:text-4xl text-dark-green">
                Jenis Kelapa Sawit dan Daerah Penghasil
            </h2>
        </div>

        <div class="mt-12 grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="palm-type-card">
                <div class="palm-type-img-wrap">
                    <img src="{{ asset('images/tenera.png') }}" alt="Kelapa Sawit Jenis Tenera"
                         class="palm-type-img"
                         onerror="this.src='https://placehold.co/300x300/6B8E23/F5F1E8?text=Tenera'">
                </div>
                <h3 class="palm-type-title">Tenera</h3>
                <p class="palm-type-desc">
                    Hasil persilangan Dura dan Pisifera. Cangkang tipis dengan rendemen minyak tinggi,
                    menjadi jenis paling banyak dibudidayakan secara komersial.
                </p>
            </div>
            <div class="palm-type-card">
                <div class="palm-type-img-wrap">
                    <img src="{{ asset('images/dura.png') }}" alt="Kelapa Sawit Jenis Dura"
                         class="palm-type-img"
                         onerror="this.src='https://placehold.co/300x300/6B8E23/F5F1E8?text=Dura'">
                </div>
                <h3 class="palm-type-title">Dura</h3>
                <p class="palm-type-desc">
                    Memiliki cangkang tebal dan daging buah relatif tipis. Umumnya digunakan sebagai
                    induk dalam program persilangan bibit unggul.
                </p>
            </div>
            <div class="palm-type-card">
                <div class="palm-type-img-wrap">
                    <img src="{{ asset('images/pisifera.png') }}" alt="Kelapa Sawit Jenis Pisifera"
                         class="palm-type-img"
                         onerror="this.src='https://placehold.co/300x300/6B8E23/F5F1E8?text=Pisifera'">
                </div>
                <h3 class="palm-type-title">Pisifera</h3>
                <p class="palm-type-desc">
                    Hampir tidak memiliki cangkang dengan daging buah tebal, namun produktivitas rendah
                    sehingga jarang ditanam secara mandiri.
                </p>
            </div>
        </div>

        <div class="mt-16 grid grid-cols-1 lg:grid-cols-2 gap-10 items-start">
            <div class="region-map-wrap">
                <img src="{{ asset('images/sebaranid.png') }}" alt="Peta Daerah Penghasil Kelapa Sawit di Indonesia"
                     class="rounded-brand shadow-lg w-full h-80 lg:h-[30rem] object-cover"
                     onerror="this.src='https://placehold.co/600x400/6B8E23/F5F1E8?text=Peta+Sebaran+Sawit'">
            </div>
            <div>
                <span class="font-heading font-semibold text-secondary-green uppercase text-sm tracking-wide">Sebaran Wilayah</span>
                <h3 class="mt-3 font-heading font-bold text-xl md:text-2xl text-dark-green">
                    Daerah Penghasil Utama di Indonesia
                </h3>
                <p class="mt-3 text-gray-text leading-relaxed text-justify">
                    Indonesia merupakan produsen kelapa sawit terbesar di dunia, dengan sebagian besar perkebunan
                    terkonsentrasi di Pulau Sumatera dan Kalimantan.
                </p>

                <div class="region-grid">
                    <div class="region-card region-card-highlight">
                        <span class="region-rank">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                            </svg>
                        </span>
                        <div class="region-content">
                            <div class="region-name-row">
                                <h4 class="region-name">Riau</h4>
                                <span class="region-badge">Lokasi Kebun Kami</span>
                            </div>
                            <p class="region-note text-justify">Provinsi dengan luas areal sawit terbesar di Indonesia, termasuk lokasi kebun PT. Sahabat Sawit Rokan Sejahtera.</p>
                        </div>
                    </div>

                    <div class="region-card">
                        <span class="region-rank">02</span>
                        <div class="region-content">
                            <h4 class="region-name">Sumatera Utara</h4>
                            <p class="region-note text-justify">Sentra sawit tertua dengan infrastruktur pengolahan yang matang.</p>
                        </div>
                    </div>

                    <div class="region-card">
                        <span class="region-rank">03</span>
                        <div class="region-content">
                            <h4 class="region-name">Kalimantan Tengah & Barat</h4>
                            <p class="region-note text-justify">Wilayah ekspansi perkebunan sawit terbesar dalam dua dekade terakhir.</p>
                        </div>
                    </div>

                    <div class="region-card">
                        <span class="region-rank">04</span>
                        <div class="region-content">
                            <h4 class="region-name">Sumatera Selatan & Jambi</h4>
                            <p class="region-note text-justify">Kontributor produksi sawit yang terus berkembang di kawasan Sumatera bagian selatan.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-16 bg-body-bg">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="font-heading font-semibold text-secondary-green uppercase text-sm tracking-wide">Dari Kebun ke Produk</span>
            <h2 class="mt-3 font-heading font-bold text-2xl md:text-4xl text-dark-green">
                Proses & Produk
            </h2>
            <p class="mt-4 text-gray-text leading-relaxed text-justify max-w-2xl mx-auto">
                Setiap tahap diawasi dengan standar mutu ketat, mulai dari panen hingga produk siap didistribusikan.
            </p>
        </div>

        <div class="process-slider-wrap">
            <button type="button" class="process-arrow process-arrow-left" onclick="scrollProcessGallery(-1)" aria-label="Sebelumnya">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <div class="process-gallery" id="processGallery">
                @foreach($processSteps as $step)
                <figure class="process-item">
                    <img src="{{ asset('images/' . $step['image']) }}" alt="{{ $step['alt'] }}"
                         onerror="this.src='https://placehold.co/400x300/164A2E/F5F1E8?text={{ urlencode($step['label']) }}'">
                    <figcaption>
                        <span class="process-step">{{ $step['step'] }}</span>
                        <span class="process-label">{{ $step['label'] }}</span>
                    </figcaption>
                </figure>
                @endforeach
            </div>

            <button type="button" class="process-arrow process-arrow-right" onclick="scrollProcessGallery(1)" aria-label="Selanjutnya">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </div>
</section>

<script>
    function scrollProcessGallery(direction) {
        const gallery = document.getElementById('processGallery');
        gallery.scrollBy({ left: direction * gallery.clientWidth, behavior: 'smooth' });
    }
</script>

{{-- Jarak section disamakan: sebelumnya py-5, sekarang py-16 seperti section lain --}}
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-14">
            <span class="font-heading font-semibold text-secondary-green uppercase text-sm tracking-wide">Katalog Produk</span>
            <h2 class="mt-3 font-heading font-bold text-2xl md:text-4xl text-dark-green">
                Produk Unggulan Kami
            </h2>
            <p class="mt-4 text-gray-text leading-relaxed text-justify max-w-2xl mx-auto">
                Berbagai hasil olahan kelapa sawit berkualitas tinggi, diproduksi dengan standar mutu terbaik.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-8">
            @forelse($products as $product)
            <a href="{{ route('products.show', $product->slug) }}"
               class="product-card rounded-brand overflow-hidden shadow-lg group block">
                <div class="product-img-wrap">
                    <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/500x400/2E7D32/F5F1E8?text=' . urlencode($product->name) }}" alt="{{ $product->name }}"
                         class="product-img"
                         loading="lazy"
                         onerror="this.onerror=null;this.src='https://placehold.co/500x400/2E7D32/F5F1E8?text={{ urlencode($product->name) }}'">
                </div>
                <div class="p-6 bg-cream product-card-body">
                    <div>
                        <h3 class="font-heading font-semibold text-lg text-dark-green">{{ $product->name }}</h3>
                        {{-- Rata kiri (sebelumnya text-justify, bikin jarak antar kata melebar di kartu sempit) --}}
                        <p class="mt-3 text-sm text-gray-text leading-relaxed product-desc">
                            {{ $product->description }}
                        </p>
                    </div>
                    <span class="product-card-link">
                        Lihat Detail
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </span>
                </div>
            </a>
            @empty
            <p class="col-span-full text-center text-gray-text">Belum ada produk yang ditambahkan.</p>
            @endforelse
        </div>
    </div>
</section>
@endsection