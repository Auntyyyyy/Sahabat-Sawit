<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PT Sahabat Sawit</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,600;12..96,800&display=swap" rel="stylesheet">
    <style>
        :root {
            --hijau: #16321f;
            --hijau-muda: #2f5a3a;
            --emas: #e3a72f;
            --krem: #f2f4ec;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; }
        body {
            font-family: "Bricolage Grotesque", system-ui, -apple-system, "Segoe UI", sans-serif;
            background: var(--hijau);
            color: var(--krem);
            line-height: 1.6;
        }

        /* ===== Bar atas: logo + tombol Masuk ke Website ===== */
        .top-bar {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 30;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1.25rem clamp(1.25rem, 5vw, 3rem);
        }
        .top-brand {
            display: flex;
            align-items: center;
            gap: .65rem;
            color: var(--krem);
            text-decoration: none;
        }
        .top-brand img { height: 36px; width: auto; display: block; }
        .top-brand .brand-text { display: flex; flex-direction: column; line-height: 1.15; }
        .top-brand strong { font-weight: 700; font-size: .92rem; }
        .top-brand span { font-size: .72rem; opacity: .75; }

        .btn-masuk {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            background: var(--emas);
            color: var(--hijau);
            font-weight: 700;
            font-size: .92rem;
            padding: .65rem 1.4rem;
            border-radius: .5rem;
            text-decoration: none;
            white-space: nowrap;
            box-shadow: 0 6px 18px rgba(0,0,0,.25);
            transition: background .2s, transform .15s;
        }
        .btn-masuk:hover { background: #f0b944; }
        .btn-masuk:active { transform: scale(.97); }

        /* ===== Carousel ===== */
        .carousel {
            position: relative;
            height: 100vh;
            min-height: 560px;
            overflow: hidden;
        }
        .slide {
            position: absolute;
            inset: 0;
            opacity: 0;
            visibility: hidden;
            transition: opacity .7s ease;
        }
        .slide.is-active { opacity: 1; visibility: visible; z-index: 1; }
        .slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .slide::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(0deg, rgba(22,50,31,.92) 0%, rgba(22,50,31,.55) 32%, rgba(22,50,31,.15) 58%, rgba(22,50,31,.45) 100%);
        }

        /* ===== Kartu teks per slide ===== */
        .slide-card {
            position: absolute;
            left: clamp(1.25rem, 5vw, 3.5rem);
            right: clamp(1.25rem, 5vw, 3.5rem);
            bottom: clamp(1.5rem, 6vh, 3.5rem);
            z-index: 2;
            max-width: 40rem;
        }
        .slide-eyebrow {
            display: inline-block;
            font-size: .8rem;
            font-weight: 700;
            letter-spacing: .04em;
            text-transform: uppercase;
            color: var(--emas);
            margin-bottom: .6rem;
        }
        .slide-title {
            font-size: clamp(1.6rem, 4.2vw, 2.75rem);
            font-weight: 800;
            line-height: 1.12;
            letter-spacing: -.02em;
            margin-bottom: .75rem;
        }
        .slide-desc {
            font-size: 1rem;
            color: #d8e0d4;
            max-width: 34rem;
            margin-bottom: 1.4rem;
        }
        .slide-link {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            color: var(--krem);
            font-weight: 700;
            font-size: .92rem;
            text-decoration: none;
            border-bottom: 2px solid var(--emas);
            padding-bottom: .15rem;
        }
        .slide-link:hover { color: var(--emas); }
        .slide-link svg { width: 16px; height: 16px; }

        /* ===== Navigasi panah ===== */
        .carousel-nav {
            position: absolute;
            right: clamp(1.25rem, 5vw, 3.5rem);
            bottom: clamp(1.5rem, 6vh, 3.5rem);
            z-index: 3;
            display: flex;
            align-items: center;
            gap: .75rem;
        }
        .carousel-count {
            font-size: .82rem;
            color: var(--krem);
            opacity: .8;
            min-width: 2.6rem;
            text-align: center;
        }
        .nav-btn {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: 1px solid rgba(242,244,236,.4);
            background: rgba(22,50,31,.45);
            color: var(--krem);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background .2s, border-color .2s;
        }
        .nav-btn:hover { background: var(--emas); color: var(--hijau); border-color: var(--emas); }
        .nav-btn svg { width: 20px; height: 20px; }

        @media (max-width: 720px) {
            .top-brand .brand-text { display: none; }
            .slide-card { max-width: 100%; }
            .carousel-nav { position: static; margin-top: 1.25rem; justify-content: flex-end; padding: 0 clamp(1.25rem, 5vw, 3.5rem) 1.5rem; }
        }

        a:focus-visible, button:focus-visible { outline: 3px solid var(--krem); outline-offset: 3px; }
        @media (prefers-reduced-motion: reduce) {
            .slide { transition: none; }
        }
    </style>
</head>
<body>
    @include('components.splash-screen')

    {{--
        Slide diambil langsung dari data Berita dan Pengetahuan (tabel/model, bukan teks manual).
        Begitu ada Berita/Pengetahuan baru, otomatis ikut tampil di sini — tidak perlu ubah file ini lagi.

        Asumsi nama kolom: title, slug, excerpt, image.
        Kalau nama kolom di tabel Anda beda (misalnya "judul" bukan "title", atau "gambar" bukan
        "image"), tinggal sesuaikan nama properti ($item->title dst) di bawah ini.
    --}}
    @php
        $beritaItems = \App\Models\Berita::latest()->take(2)->get();
        $pengetahuanItems = \App\Models\Pengetahuan::latest()->take(3)->get();

        $slides = collect();

        foreach ($beritaItems as $item) {
            $slides->push([
                'eyebrow' => 'Berita',
                'title'   => $item->title ?? $item->judul ?? '',
                'desc'    => \Illuminate\Support\Str::limit(strip_tags($item->excerpt ?? $item->ringkasan ?? $item->content ?? ''), 140),
                'image'   => $item->image ?? $item->thumbnail ?? $item->gambar ?? null,
                'link'    => route('media.berita.show', $item->slug),
            ]);
        }

        foreach ($pengetahuanItems as $item) {
            $slides->push([
                'eyebrow' => 'Pengetahuan',
                'title'   => $item->title ?? $item->judul ?? '',
                'desc'    => \Illuminate\Support\Str::limit(strip_tags($item->excerpt ?? $item->ringkasan ?? $item->content ?? ''), 140),
                'image'   => $item->image ?? $item->thumbnail ?? $item->gambar ?? null,
                'link'    => route('media.pengetahuan.show', $item->slug),
            ]);
        }

        $slides = $slides->values()->all();

        // Kalau Berita dan Pengetahuan masih kosong semua, tampilkan satu slide penjaga
        // supaya halaman tidak kosong melompong, bukan error.
        if (empty($slides)) {
            $slides = [[
                'eyebrow' => 'Berita & Pengetahuan',
                'title'   => 'Konten Segera Hadir',
                'desc'    => 'Berita dan pengetahuan terbaru akan tampil di sini begitu ditambahkan.',
                'image'   => null,
                'link'    => route('media.berita'),
            ]];
        }
    @endphp

    <header class="top-bar">
        <a href="{{ route('landing') }}" class="top-brand">
            <img src="{{ asset('images/logoaja.png') }}" alt="Logo PT. Sahabat Sawit Rokan Sejahtera">
            <span class="brand-text">
                <strong>Sahabat Sawit</strong>
                <span>Rokan Sejahtera</span>
            </span>
        </a>

        <a class="btn-masuk" href="{{ route('home') }}">Masuk ke Website</a>
    </header>

    <main class="carousel" id="carousel">
        @foreach ($slides as $i => $s)
            <div class="slide {{ $i === 0 ? 'is-active' : '' }}" data-slide="{{ $i }}">
                <img src="{{ asset($s['image']) }}" alt="{{ $s['title'] }}"
                     onerror="this.src='https://placehold.co/1600x900/16321f/f2f4ec?text=PT+Sahabat+Sawit'">
                <div class="slide-card">
                    <span class="slide-eyebrow">{{ $s['eyebrow'] }}</span>
                    <h1 class="slide-title">{{ $s['title'] }}</h1>
                    <p class="slide-desc">{{ $s['desc'] }}</p>
                    <a class="slide-link" href="{{ $s['link'] }}">
                        Baca selengkapnya
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </div>
        @endforeach

        <div class="carousel-nav">
            <span class="carousel-count"><span id="slide-current">1</span> / {{ count($slides) }}</span>
            <button type="button" class="nav-btn" id="prev-slide" aria-label="Slide sebelumnya">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button type="button" class="nav-btn" id="next-slide" aria-label="Slide berikutnya">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>
    </main>

    <script>
        (function () {
            const slides = Array.from(document.querySelectorAll('#carousel .slide'));
            const counter = document.getElementById('slide-current');
            let current = 0;

            function goTo(index) {
                slides[current].classList.remove('is-active');
                current = (index + slides.length) % slides.length;
                slides[current].classList.add('is-active');
                counter.textContent = current + 1;
            }

            document.getElementById('next-slide').addEventListener('click', () => goTo(current + 1));
            document.getElementById('prev-slide').addEventListener('click', () => goTo(current - 1));

            document.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowRight') goTo(current + 1);
                if (e.key === 'ArrowLeft') goTo(current - 1);
            });
        })();
    </script>
</body>
</html>