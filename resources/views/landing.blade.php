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
        .halaman {
            position: relative;
            min-height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: clamp(1.5rem, 6vw, 5rem);
            overflow: hidden;
        }
        /* Pelepah sawit: garis-garis dekoratif di sisi kanan */
        .pelepah {
            position: absolute;
            right: -8%;
            bottom: -10%;
            width: min(70vw, 720px);
            opacity: .35;
            pointer-events: none;
        }
        .isi { position: relative; max-width: 40rem; }
        .merek {
            font-size: 1rem;
            font-weight: 600;
            color: var(--emas);
            margin-bottom: 1.25rem;
        }
        h1 {
            font-size: clamp(2.75rem, 9vw, 6rem);
            font-weight: 800;
            line-height: 1;
            letter-spacing: -0.03em;
            margin-bottom: 1.5rem;
        }
        .deskripsi {
            font-size: 1.125rem;
            max-width: 32rem;
            color: #cdd8cc;
            margin-bottom: 2.25rem;
        }
        .tombol {
            display: inline-block;
            background: var(--emas);
            color: var(--hijau);
            font-weight: 600;
            font-size: 1.0625rem;
            padding: .9rem 1.75rem;
            border-radius: .5rem;
            text-decoration: none;
        }
        .tombol:hover { background: #f0b944; }
        .tautan {
            margin-top: 2.5rem;
            display: flex;
            flex-wrap: wrap;
            gap: .5rem 1.75rem;
        }
        .tautan a {
            color: var(--krem);
            text-decoration: underline;
            text-underline-offset: 4px;
            text-decoration-color: var(--hijau-muda);
        }
        .tautan a:hover { text-decoration-color: var(--emas); }
        a:focus-visible { outline: 3px solid var(--krem); outline-offset: 3px; }
    </style>
</head>
<body>
    <main class="halaman">
        <svg class="pelepah" viewBox="0 0 400 400" fill="none" stroke="#2f5a3a" stroke-width="3" stroke-linecap="round" aria-hidden="true">
            <path d="M380 380 C 300 250, 220 130, 40 30"/>
            <path d="M330 320 L 250 330 M300 280 L 210 285 M270 240 L 180 235 M240 200 L 150 185 M205 160 L 120 135 M165 120 L 95 90"/>
            <path d="M330 320 L 350 240 M300 280 L 305 195 M270 240 L 260 155 M240 200 L 220 120 M205 160 L 175 85 M165 120 L 130 55"/>
        </svg>

        <div class="isi">
            <p class="merek">Selamat datang di</p>
            <h1>PT. Sahabat Sawit Rokan Sejahtera</h1>
            <p class="deskripsi">
                Perkebunan dan pengolahan kelapa sawit yang dikelola dengan tanggung jawab
                kepada lingkungan dan masyarakat sekitar.
            </p>

            {{-- Tombol utama: masuk ke beranda website --}}
            <a class="tombol" href="{{ route('home') }}">Masuk ke website</a>

            <nav class="tautan" aria-label="Pintasan">
                <a href="{{ route('about') }}">Tentang kami</a>
                <a href="{{ route('products') }}">Produk</a>
                <a href="{{ route('contact') }}">Kontak</a>
            </nav>
        </div>
    </main>
</body>
</html>