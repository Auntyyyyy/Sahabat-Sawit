@extends('layouts.app')

@section('title', 'PT Sahabat Sawit Rokan Sejahtera')

@section('content')

    {{-- 1. HERO (komponen lama dibiarkan) --}}
    <x-hero :stats="$stats" />

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
    .hm a:focus-visible { outline: 3px solid var(--krem); outline-offset: 3px; }

    .hm-sec { padding: clamp(4rem, 10vh, 7rem) clamp(1.25rem, 5vw, 3.5rem); }
    .hm-sec + .hm-sec { border-top: 1px solid rgba(242,244,236,.12); }
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
        border-radius: .25rem;
        transition: border-color .25s;
    }
    .hm-adv h3 { font-size: clamp(1.4rem, 2.2vw, 1.9rem); font-weight: 700; letter-spacing: -.02em; line-height: 1.15; transition: color .25s; }
    .hm-adv p { font-size: .95rem; color: rgba(242,244,236,.7); }
    .hm-adv:hover { border-color: var(--emas); }
    .hm-adv:hover h3 { color: var(--emas); }
    /* Cadangan: tanpa pin (kurangi gerakan / kartu muat di layar) → baris biasa yang bisa digeser */
    .hm-hscroll.is-native .hm-hscroll-pin { position: static; height: auto; overflow: visible; padding: clamp(4rem, 10vh, 7rem) 0; }
    .hm-hscroll.is-native .hm-hscroll-track { width: auto; overflow-x: auto; }

    /* Perkebunan */
    .hm-stats { margin: 2rem 0 0; display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
    .hm-stats dt { font-size: .85rem; color: rgba(242,244,236,.6); }
    .hm-stats dd { margin: .15rem 0 0; font-size: 1.35rem; font-weight: 700; letter-spacing: -.01em; }
    .hm-note { margin-top: 1rem; font-size: .75rem; color: rgba(242,244,236,.45); }
    .hm-map { border-radius: .25rem; overflow: hidden; }
    .hm-map iframe { display: block; width: 100%; height: 380px; border: 0; }

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
</style>

<div class="hm">

    {{-- 2. TENTANG KAMI --}}
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

    {{-- 3. KEUNGGULAN --}}
    <section class="hm-hscroll" id="hmKeunggulan">
        <div class="hm-hscroll-pin">
            <div class="hm-hscroll-head">
                <h2 class="hm-title">Mengapa Memilih SSRS?</h2>
            </div>
            <div class="hm-hscroll-track" id="hmTrack">
                @foreach($advantages as $adv)
                <article class="hm-adv">
                    <h3>{{ $adv['title'] }}</h3>
                    <p>{{ $adv['desc'] }}</p>
                </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- 4. PERKEBUNAN --}}
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
                    <div><dt>Diusahan sendiri</dt><dd>2043,75(Ha)</dd></div>
                </dl>
                <p class="hm-note">*SK : SK : 903/MENLHK/SETJEN/PLA.2/12/2016 RIAU.</p>

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

    {{-- 5. BERITA & GALERI KEGIATAN --}}
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
                <a href="{{ route('media.berita.show', $item->slug) }}" class="hm-card">
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

    {{-- 6. PENGETAHUAN (SahabatPedia) --}}
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
                <a href="{{ route('media.pengetahuan.show', $item->slug) }}" class="hm-card">
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

@endsection