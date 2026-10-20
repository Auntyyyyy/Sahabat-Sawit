@extends('layouts.app')

@section('title', 'Kontak Kami - PT. Sahabat Sawit Rokan Sejahtera')

@section('content')

@php
    // Foto sementara: ganti nama file di sini setelah foto asli terkumpul (file ada di public/images).
    $fotoHero    = 'prosesnunggu.png';
    $fotoPenutup = 'sawitangkong.jpg';

    $lokasi = [
        'kantor-pusat' => [
            'label'  => 'Kantor Pusat',
            'alamat' => 'Jl. Haji Misbah, Komplek Multatuli Indah Blok D No. 36, Kel. Hamdan, Kec. Medan Maimun, Kota Medan, Sumatera Utara',
            'q'      => 'Jl. Haji Misbah, Komplek Multatuli Indah Blok D No. 36, Kel. Hamdan, Kec. Medan Maimun, Kota Medan, Sumatera Utara',
        ],
        'site-operasional' => [
            'label'  => 'Site / Operasional',
            'alamat' => 'Jl. Lintas Medan KM 1, RT/RW 001/001 Kepenghuluan Sungai Meranti, Kec. Tanjung Medan, Kab. Rokan Hilir, Riau 28983',
            'q'      => 'Jl. Lintas Medan KM 1, Kepenghuluan Sungai Meranti, Kec. Tanjung Medan, Kab. Rokan Hilir, Riau 28983',
        ],
    ];

    $faq = [
        ['Apa saja bidang usaha PT. Sahabat Sawit Rokan Sejahtera?',
         'Kami bergerak di bidang perkebunan dan pengolahan kelapa sawit, mulai dari budidaya, panen, hingga distribusi hasil produksi, dengan berpegang pada prinsip pengelolaan yang bertanggung jawab.'],
        ['Di mana lokasi kebun dan operasional perusahaan?',
         'Kantor pusat kami berada di Kota Medan, Sumatera Utara, sedangkan area perkebunan dan operasional utama berlokasi di Kabupaten Rokan Hilir, Provinsi Riau.'],
        ['Bagaimana cara menjalin kerja sama atau kemitraan?',
         'Silakan hubungi tim kami melalui WhatsApp pada tombol di atas. Tim kami akan menindaklanjuti dan menjadwalkan diskusi lebih lanjut sesuai kebutuhan kerja sama Anda.'],
        ['Apakah perusahaan membuka lowongan pekerjaan?',
         'Informasi lowongan yang tersedia akan kami umumkan melalui halaman <a href="' . route('career') . '">Karir</a> di situs ini. Anda juga dapat menanyakan langsung melalui WhatsApp untuk informasi terbaru.'],
        ['Bagaimana komitmen perusahaan terhadap keberlanjutan?',
         'Kami menerapkan praktik perkebunan yang bertanggung jawab dan berkelanjutan, memperhatikan kelestarian lingkungan serta kesejahteraan masyarakat sekitar area operasional.'],
    ];
@endphp

<style>
    /* Halaman Kontak — gaya disamakan dengan Home/Produk/Tentang Kami. Class berawalan "ct-". */
    @import url('https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,600;12..96,800&display=swap');

    .ct {
        --hijau: #16321f; --hijau-muda: #2f5a3a; --emas: #e3a72f; --krem: #f2f4ec;
        --gutter: max(clamp(1.25rem, 5vw, 3.5rem), calc((100vw - 80rem) / 2));
        font-family: "Bricolage Grotesque", system-ui, -apple-system, "Segoe UI", sans-serif;
        background: var(--hijau); color: var(--krem); line-height: 1.6;
    }
    .ct *, .ct *::before, .ct *::after { box-sizing: border-box; }
    .ct a { color: inherit; text-decoration: none; }
    .ct a:focus-visible, .ct button:focus-visible, .ct summary:focus-visible { outline: 3px solid var(--krem); outline-offset: 3px; }

    .ct-hero { position: relative; isolation: isolate; overflow: hidden; display: flex; align-items: flex-end; min-height: min(92vh, 58rem); background: linear-gradient(145deg, var(--hijau-muda), var(--hijau)); }
    .ct-hero::after { content: ''; position: absolute; inset: 0; z-index: -1; background: linear-gradient(90deg, rgba(22,50,31,.92) 0%, rgba(22,50,31,.6) 50%, rgba(22,50,31,.2) 100%), linear-gradient(0deg, rgba(22,50,31,.85) 0%, transparent 45%); }
    .ct-hero-inner { width: 100%; padding: clamp(8rem, 18vh, 11rem) var(--gutter) clamp(3rem, 8vh, 5rem); }
    .ct-bg { position: absolute; inset: 0; z-index: -2; width: 100%; height: 100%; object-fit: cover; }
    .ct-crumb { display: flex; flex-wrap: wrap; align-items: center; gap: .5rem; font-size: .9rem; color: rgba(242,244,236,.6); }
    .ct-crumb a:hover { color: var(--krem); }
    .ct-crumb [aria-current] { color: var(--krem); }
    .ct-title { margin-top: 1.5rem; max-width: 15ch; font-size: clamp(2.4rem, 7vw, 6rem); font-weight: 800; line-height: 1.02; letter-spacing: -.04em; }
    .ct-lead { margin-top: 1.5rem; max-width: 38rem; color: rgba(242,244,236,.75); }
    .ct-links { margin-top: 2.5rem; display: flex; flex-wrap: wrap; gap: 1rem 2rem; }
    .ct-link { display: inline-flex; align-items: center; gap: .55rem; font-weight: 600; font-size: .95rem; border-bottom: 2px solid var(--emas); padding-bottom: .2rem; }
    .ct-link:hover { color: var(--emas); }

    .ct-sec { padding: clamp(4rem, 10vh, 7rem) var(--gutter); }
    .ct-hero + .ct-sec, .ct-sec + .ct-sec { border-top: 1px solid rgba(242,244,236,.12); }
    .ct-label { font-size: .95rem; color: rgba(242,244,236,.6); }
    .ct-h2 { margin-top: .5rem; max-width: 20ch; font-size: clamp(1.8rem, 4vw, 3rem); font-weight: 800; line-height: 1.05; letter-spacing: -.03em; }
    .ct-p { margin-top: 1.25rem; max-width: 40rem; color: rgba(242,244,236,.78); }

    /* Kanal kontak */
    .ct-channels { display: grid; grid-template-columns: 1fr; gap: 2rem 3rem; }
    @media (min-width: 900px) { .ct-channels { grid-template-columns: repeat(3, 1fr); } }
    .ct-channel { display: block; padding-top: 1.25rem; border-top: 1px solid rgba(242,244,236,.2); }
    .ct-channel-label { font-size: .95rem; color: rgba(242,244,236,.6); }
    .ct-channel-value { margin-top: .4rem; font-size: clamp(1.25rem, 2.2vw, 1.7rem); font-weight: 700; letter-spacing: -.02em; line-height: 1.2; overflow-wrap: anywhere; transition: color .25s; }
    .ct-channel-hint { margin-top: .4rem; font-size: .9rem; color: rgba(242,244,236,.65); }
    a.ct-channel:hover .ct-channel-value { color: var(--emas); }

    /* Kanal kontak di section terang: jeda dari latar gelap, nilai kontak jadi titik fokus */
    .ct-light { background: var(--krem); color: var(--hijau); }
    .ct-light .ct-channel { border-top-color: rgba(22,50,31,.25); }
    .ct-light .ct-channel-label { color: rgba(22,50,31,.6); }
    .ct-light .ct-channel-hint { color: rgba(22,50,31,.72); }
    .ct-light .ct-channel-value { font-size: clamp(1.5rem, 3vw, 2.4rem); }
    .ct-light a.ct-channel:hover .ct-channel-value { color: var(--hijau-muda); }

    /* Lokasi di latar paling pekat */
    .ct-deep { background: #0f2417; }

    /* Lokasi */
    .ct-tabs { margin-top: 2rem; display: flex; flex-wrap: wrap; gap: .5rem 1.75rem; }
    .ct-tab { padding: 0 0 .2rem; border: 0; border-bottom: 2px solid transparent; background: none; font: inherit; font-weight: 600; font-size: .95rem; color: rgba(242,244,236,.6); cursor: pointer; transition: color .2s, border-color .2s; }
    .ct-tab:hover { color: var(--krem); }
    .ct-tab[aria-selected="true"] { color: var(--krem); border-bottom-color: var(--emas); }
    .ct-map { margin-top: 2rem; display: grid; grid-template-columns: 1fr; gap: 2rem; align-items: start; }
    @media (min-width: 900px) { .ct-map { grid-template-columns: 2fr 1fr; gap: 3rem; } .ct-map > :first-child { order: 2; } }
    .ct-addr-title { font-size: 1.4rem; font-weight: 700; letter-spacing: -.02em; }
    .ct-addr { margin-top: .75rem; color: rgba(242,244,236,.78); }
    .ct-frame { display: block; width: 100%; height: clamp(320px, 45vw, 480px); border: 0; border-radius: .25rem; }
    .ct-frame[hidden], .ct-panel[hidden] { display: none; }

    /* FAQ (details/summary: tanpa JavaScript); judul menempel di kiri, daftar di kanan */
    .ct-split { display: grid; grid-template-columns: 1fr; gap: 2.5rem; }
    @media (min-width: 900px) { .ct-split { grid-template-columns: 5fr 7fr; gap: 5rem; align-items: start; } .ct-sticky { position: sticky; top: 7rem; } }
    .ct-faq { max-width: 52rem; }
    .ct-faq details { border-top: 1px solid rgba(242,244,236,.15); }
    .ct-faq details:last-child { border-bottom: 1px solid rgba(242,244,236,.15); }
    .ct-faq summary { display: flex; align-items: center; justify-content: space-between; gap: 1.5rem; padding: 1.25rem 0; list-style: none; cursor: pointer; font-size: clamp(1.1rem, 2vw, 1.4rem); font-weight: 700; letter-spacing: -.01em; line-height: 1.25; transition: color .2s; }
    .ct-faq summary::-webkit-details-marker { display: none; }
    .ct-faq summary:hover { color: var(--emas); }
    .ct-faq summary::after { content: '+'; flex: none; width: 2rem; height: 2rem; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(242,244,236,.35); border-radius: 50%; font-size: 1.2rem; font-weight: 400; transition: transform .25s, background .25s, border-color .25s, color .25s; }
    .ct-faq details[open] summary::after { transform: rotate(45deg); background: var(--emas); border-color: var(--emas); color: var(--hijau); }
    .ct-ans { max-width: 42rem; padding: 0 3.5rem 1.5rem 0; color: rgba(242,244,236,.75); }
    .ct-ans a { border-bottom: 1px solid var(--emas); }

    /* Penutup: pita berfoto, rata tengah */
    .ct-band { position: relative; isolation: isolate; overflow: hidden; text-align: center; background: linear-gradient(145deg, var(--hijau-muda), var(--hijau)); }
    .ct-band::after { content: ''; position: absolute; inset: 0; z-index: -1; background: rgba(15,36,23,.8); }
    .ct-band .ct-h2 { max-width: 18ch; font-size: clamp(2.2rem, 6vw, 4.5rem); }
    .ct-band .ct-h2, .ct-band .ct-p { margin-left: auto; margin-right: auto; }
    .ct-band .ct-links { justify-content: center; }
</style>

<div class="ct">

    {{-- HERO berfoto --}}
    <header class="ct-hero">
        <img class="ct-bg" src="{{ asset('images/' . $fotoHero) }}" alt="" onerror="this.style.display='none'">
        <div class="ct-hero-inner">
            <nav class="ct-crumb" aria-label="Breadcrumb">
                <a href="{{ url('/') }}">Beranda</a>
                <span aria-hidden="true">/</span>
                <span aria-current="page">Kontak</span>
            </nav>

            <h1 class="ct-title">Terbuka untuk Kerja Sama & Kemitraan Sawit</h1>
            <p class="ct-lead">
                Kami terbuka untuk menjalin kerja sama, kemitraan, maupun menjawab pertanyaan
                seputar PT. Sahabat Sawit Rokan Sejahtera. Tim kami siap membantu Anda.
            </p>

            <div class="ct-links">
                <a class="ct-link" href="https://wa.me/628139654581" target="_blank" rel="noopener noreferrer">Hubungi Kami lewat WhatsApp</a>
                <a class="ct-link" href="mailto:info@sahabatsawitrokan.co.id">Kirim Email</a>
            </div>
        </div>
    </header>

    {{-- KANAL KONTAK (section terang) --}}
    <section class="ct-sec ct-light">
        <div class="ct-channels">
            <a class="ct-channel" href="https://wa.me/628139654581" target="_blank" rel="noopener noreferrer">
                <p class="ct-channel-label">WhatsApp</p>
                <p class="ct-channel-value">+62 813-9654-581</p>
                <p class="ct-channel-hint">Respon tercepat untuk pertanyaan & kerja sama</p>
            </a>
            <a class="ct-channel" href="mailto:ptsahabatsawitrokansejahtera@gmail.com">
                <p class="ct-channel-label">Email</p>
                <p class="ct-channel-value">ptsahabatsawitrokansejahtera@gmail.com</p>
                <p class="ct-channel-hint">Untuk surat resmi, proposal, dan dokumen kerja sama</p>
            </a>
            <div class="ct-channel">
                <p class="ct-channel-label">Jam Operasional</p>
                <p class="ct-channel-value">08.00 – 17.00 WIB</p>
                <p class="ct-channel-hint">Senin – Jumat</p>
            </div>
        </div>
    </section>

    {{-- LOKASI + PETA --}}
    <section class="ct-sec ct-deep">
        <p class="ct-label">Lokasi kami</p>
        <h2 class="ct-h2">Titik Lokasi di Peta</h2>

        <div class="ct-tabs" role="tablist" aria-label="Pilih lokasi">
            @foreach($lokasi as $key => $l)
                <button type="button" class="ct-tab" role="tab" data-ct-tab="{{ $key }}"
                        aria-selected="{{ $loop->first ? 'true' : 'false' }}">{{ $l['label'] }}</button>
            @endforeach
        </div>

        <div class="ct-map">
            <div>
                @foreach($lokasi as $key => $l)
                <div class="ct-panel" data-ct-panel="{{ $key }}" @unless($loop->first) hidden @endunless>
                    <h3 class="ct-addr-title">{{ $l['label'] }}</h3>
                    <p class="ct-addr">{{ $l['alamat'] }}</p>
                    <div class="ct-links" style="margin-top:1.5rem">
                        <a class="ct-link" href="https://www.google.com/maps?q={{ urlencode($l['q']) }}" target="_blank" rel="noopener noreferrer">Buka di Google Maps</a>
                        <a class="ct-link" href="https://www.google.com/maps/dir/?api=1&destination={{ urlencode($l['q']) }}" target="_blank" rel="noopener noreferrer">Petunjuk arah</a>
                    </div>
                </div>
                @endforeach
            </div>

            <div>
                @foreach($lokasi as $key => $l)
                <iframe class="ct-frame" data-ct-frame="{{ $key }}" @unless($loop->first) hidden @endunless
                        src="https://www.google.com/maps?q={{ urlencode($l['q']) }}&output=embed"
                        loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                        title="Peta Lokasi {{ $l['label'] }} PT. Sahabat Sawit Rokan Sejahtera"></iframe>
                @endforeach
            </div>
        </div>
    </section>

    {{-- FAQ --}}
    <section class="ct-sec">
        <div class="ct-split">
            <div class="ct-sticky">
                <p class="ct-label">FAQ</p>
                <h2 class="ct-h2">Pertanyaan yang Sering Diajukan</h2>
                <p class="ct-p">
                    Beberapa hal yang paling sering ditanyakan seputar perusahaan dan kerja sama dengan kami.
                    Tidak menemukan jawaban yang Anda cari? Hubungi kami langsung via WhatsApp.
                </p>
            </div>

            <div class="ct-faq">
                @foreach($faq as [$tanya, $jawab])
                <details name="faq">
                    <summary>{{ $tanya }}</summary>
                    <p class="ct-ans">{!! $jawab !!}</p>
                </details>
                @endforeach
            </div>
        </div>
    </section>

    {{-- PENUTUP (pita foto) --}}
    <section class="ct-sec ct-band">
        <img class="ct-bg" src="{{ asset('images/' . $fotoPenutup) }}" alt="" loading="lazy" onerror="this.style.display='none'">
        <p class="ct-label">Bersama membangun</p>
        <h2 class="ct-h2">Mari Terhubung Bersama Kami</h2>
        <p class="ct-p">
            Bersama membangun industri kelapa sawit yang produktif, bertanggung jawab,
            dan berkelanjutan bagi masyarakat serta lingkungan di Rokan Hilir, Riau.
        </p>
        <div class="ct-links">
            <a class="ct-link" href="https://wa.me/628139654581" target="_blank" rel="noopener noreferrer">Mulai Percakapan</a>
            <a class="ct-link" href="mailto:ptsahabatsawitrokansejahtera@gmail.com">Kirim Email</a>
        </div>
    </section>

</div>

<script>
    // Ganti lokasi di bagian peta (Kantor Pusat / Site Operasional)
    (function () {
        const tabs = document.querySelectorAll('[data-ct-tab]');
        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                const key = tab.dataset.ctTab;
                tabs.forEach(function (t) { t.setAttribute('aria-selected', t === tab ? 'true' : 'false'); });
                document.querySelectorAll('[data-ct-panel]').forEach(function (p) { p.hidden = p.dataset.ctPanel !== key; });
                document.querySelectorAll('[data-ct-frame]').forEach(function (f) { f.hidden = f.dataset.ctFrame !== key; });
            });
        });
    })();
</script>

@endsection