@extends('layouts.app')

@section('title', 'Sertifikasi & Penghargaan — PT Sahabat Sawit')

@section('content')
@include('about._style')

<style>
    /* Sertifikasi & penghargaan — sertifikat (section terang, kartu seperti kertas) dan penghargaan (section gelap, kartu beraksen emas). Class berawalan "sf-". */
    .sf-sec { --sf-gutter: max(clamp(1.25rem, 5vw, 3.5rem), calc((100vw - 80rem) / 2)); padding: clamp(4rem, 10vh, 7rem) var(--sf-gutter); }
    .sf-light { background: #f2f4ec; color: #16321f; }
    .sf-dark { border-top: 1px solid rgba(242,244,236,.12); }

    .sf-label { font-size: .95rem; color: rgba(242,244,236,.6); }
    .sf-light .sf-label { color: rgba(22,50,31,.6); }
    .sf-head { display: flex; flex-wrap: wrap; align-items: baseline; gap: .5rem 1.25rem; }
    .sf-h2 { margin-top: .5rem; font-size: clamp(1.8rem, 4vw, 3rem); font-weight: 800; line-height: 1.05; letter-spacing: -.03em; }
    .sf-count { font-size: .95rem; font-weight: 600; color: rgba(242,244,236,.55); }
    .sf-light .sf-count { color: rgba(22,50,31,.55); }
    .sf-p { margin-top: 1.25rem; max-width: 40rem; color: rgba(242,244,236,.78); }
    .sf-light .sf-p { color: rgba(22,50,31,.8); }

    /* Sertifikat: kartu putih seperti lembar kertas */
    .sf-certs { list-style: none; margin: clamp(2.5rem, 6vh, 4rem) 0 0; padding: 0; display: grid; grid-template-columns: minmax(0, 1fr); gap: 1.5rem; }
    @media (min-width: 640px)  { .sf-certs { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    @media (min-width: 1024px) { .sf-certs { grid-template-columns: repeat(4, minmax(0, 1fr)); } }
    .sf-cert { overflow: hidden; border: 1px solid rgba(22,50,31,.12); border-radius: .5rem; background: #fff; box-shadow: 0 1px 2px rgba(22,50,31,.06); transition: transform .3s, box-shadow .3s, border-color .3s; }
    .sf-tile { position: relative; display: flex; align-items: center; justify-content: center; width: 100%; aspect-ratio: 4 / 3; padding: 1.5rem; border: 0; border-bottom: 1px solid rgba(22,50,31,.08); background: #fafaf6; cursor: zoom-in; }
    .sf-tile img { max-width: 100%; max-height: 100%; object-fit: contain; display: block; transition: transform .5s ease; }
    .sf-year { position: absolute; top: .75rem; right: .75rem; padding: .15rem .7rem; border-radius: 99px; background: #16321f; color: #f2f4ec; font-size: .75rem; font-weight: 600; }
    .sf-zoom { position: absolute; right: .75rem; bottom: .75rem; width: 2.25rem; height: 2.25rem; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: #16321f; color: #f2f4ec; opacity: 0; transform: scale(.85); transition: opacity .25s, transform .25s; }
    .sf-zoom svg { width: 1rem; height: 1rem; }
    .sf-meta { padding: 1.1rem 1.25rem 1.35rem; border-top: 3px solid #e3a72f; margin-top: -1px; }
    .sf-cert-name { font-size: 1.2rem; font-weight: 800; letter-spacing: -.02em; line-height: 1.2; color: #16321f; }
    .sf-cert-desc { margin-top: .35rem; font-size: .9rem; line-height: 1.45; color: rgba(22,50,31,.68); }

    /* Penghargaan: kartu gelap beraksen emas */
    .sf-awards { list-style: none; margin: clamp(2.5rem, 6vh, 4rem) 0 0; padding: 0; display: grid; grid-template-columns: minmax(0, 1fr); gap: 1rem; }
    @media (min-width: 700px)  { .sf-awards { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    @media (min-width: 1100px) { .sf-awards { grid-template-columns: repeat(3, minmax(0, 1fr)); } }
    .sf-award { position: relative; padding: 1.75rem 1.5rem 2rem; border: 1px solid rgba(242,244,236,.14); border-radius: .5rem; background: rgba(242,244,236,.05); transition: transform .25s, border-color .25s, background .25s; }
    .sf-award::before { content: ''; position: absolute; left: 1.5rem; top: -1px; width: 2.5rem; height: 3px; background: #e3a72f; }
    .sf-award-top { display: flex; align-items: center; justify-content: space-between; gap: 1rem; }
    .sf-ico { display: flex; align-items: center; justify-content: center; width: 3rem; height: 3rem; border-radius: .5rem; background: rgba(227,167,47,.12); border: 1px solid rgba(227,167,47,.35); color: #e3a72f; }
    .sf-ico svg { width: 1.5rem; height: 1.5rem; }
    .sf-award-year { font-size: clamp(2rem, 4vw, 2.8rem); font-weight: 800; line-height: 1; letter-spacing: -.04em; color: #f2f4ec; }
    .sf-award h3 { margin-top: 1.4rem; font-size: clamp(1.2rem, 2vw, 1.5rem); font-weight: 700; letter-spacing: -.02em; line-height: 1.2; }
    .sf-award-desc { margin-top: .6rem; font-size: .95rem; color: rgba(242,244,236,.7); }

    @media (hover: hover) and (prefers-reduced-motion: no-preference) {
        .sf-cert:hover { transform: translateY(-5px); border-color: rgba(47,90,58,.4); box-shadow: 0 18px 36px rgba(22,50,31,.14); }
        .sf-cert:hover .sf-tile img { transform: scale(1.05); }
        .sf-cert:hover .sf-zoom { opacity: 1; transform: scale(1); }
        .sf-award:hover { transform: translateY(-4px); background: rgba(242,244,236,.09); border-color: rgba(227,167,47,.55); }
    }
    .sf-tile:focus-visible .sf-zoom { opacity: 1; transform: scale(1); }

    /* Lightbox */
    .sf-lightbox { position: fixed; inset: 0; z-index: 100; display: flex; align-items: center; justify-content: center; padding: 2rem; background: rgba(5,8,5,.92); }
    .sf-lightbox[hidden] { display: none; }
    .sf-lightbox img { max-width: min(92vw, 60rem); max-height: 88vh; object-fit: contain; border-radius: .25rem; background: #f2f4ec; }
    .sf-lightbox-close { position: absolute; top: 1rem; right: 1.25rem; width: 44px; height: 44px; border: 1px solid rgba(242,244,236,.4); border-radius: 50%; background: transparent; color: #f2f4ec; font-size: 1.6rem; line-height: 1; cursor: pointer; }
    .sf-lightbox-close:hover { background: #e3a72f; border-color: #e3a72f; color: #16321f; }
</style>

<div class="ab">

    @include('about._hero', [
        'title' => 'Sertifikasi & Penghargaan',
        'lead'  => 'Bentuk pengakuan atas komitmen perusahaan dalam menerapkan standar, menjaga kualitas, dan menjalankan praktik usaha yang bertanggung jawab.',
        'crumb' => 'Sertifikasi & Penghargaan',
    ])

    {{-- SERTIFIKASI (section terang, kartu seperti lembar kertas) --}}
    <section class="sf-sec sf-light">
        <p class="sf-label">Legalitas</p>
        <div class="sf-head">
            <h2 class="sf-h2">Sertifikasi</h2>
            <span class="sf-count">{{ count($sertifikasi) }} sertifikasi</span>
        </div>
        <p class="sf-p">
            Komitmen kami terhadap standar mutu, keamanan, dan keberlanjutan dibuktikan melalui sertifikasi resmi berikut.
        </p>

        <ul class="sf-certs">
            @foreach($sertifikasi as $item)
            <li class="sf-cert">
                <button type="button" class="sf-tile"
                        data-cert="{{ asset('images/' . $item['photo']) }}" data-alt="{{ $item['nama'] }}"
                        aria-label="Perbesar {{ $item['nama'] }}">
                    <img src="{{ asset('images/' . $item['photo']) }}" alt="{{ $item['nama'] }}" loading="lazy" onerror="this.style.display='none'">
                    @if(!empty($item['tahun']))
                        <span class="sf-year">{{ $item['tahun'] }}</span>
                    @endif
                    <span class="sf-zoom" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16zM11 8v6M8 11h6"/></svg>
                    </span>
                </button>
                <div class="sf-meta">
                    <p class="sf-cert-name">{{ $item['nama'] }}</p>
                    @if(!empty($item['deskripsi']))
                        <p class="sf-cert-desc">{{ $item['deskripsi'] }}</p>
                    @endif
                </div>
            </li>
            @endforeach
        </ul>
    </section>

    {{-- PENGHARGAAN (section gelap, kartu beraksen emas) --}}
    <section class="sf-sec sf-dark">
        <p class="sf-label">Apresiasi</p>
        <div class="sf-head">
            <h2 class="sf-h2">Penghargaan</h2>
            <span class="sf-count">{{ count($penghargaan) }} penghargaan</span>
        </div>
        <p class="sf-p">
            Berbagai apresiasi yang kami terima atas kinerja, kualitas layanan, dan kontribusi perusahaan.
        </p>

        <ul class="sf-awards">
            @foreach($penghargaan as $item)
            <li class="sf-award">
                <div class="sf-award-top">
                    <span class="sf-ico" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 15a6 6 0 100-12 6 6 0 000 12zM8.5 14.2L7 21l5-3 5 3-1.5-6.8"/></svg>
                    </span>
                    <span class="sf-award-year">{{ $item['tahun'] ?? '' }}</span>
                </div>
                <h3>{{ $item['nama'] }}</h3>
                <p class="sf-award-desc">{{ $item['deskripsi'] ?? '' }}</p>
            </li>
            @endforeach
        </ul>
    </section>

    {{-- Lightbox: klik sertifikat untuk memperbesar; tutup dengan tombol, klik latar, atau Escape --}}
    <div class="sf-lightbox" id="sfLightbox" role="dialog" aria-modal="true" aria-label="Sertifikat diperbesar" hidden>
        <button type="button" class="sf-lightbox-close" id="sfLightboxClose" aria-label="Tutup">&times;</button>
        <img id="sfLightboxImg" src="" alt="">
    </div>

</div>

<script>
    (function () {
        const box = document.getElementById('sfLightbox');
        const img = document.getElementById('sfLightboxImg');
        const closeBtn = document.getElementById('sfLightboxClose');
        let trigger = null;

        function open(src, alt, from) {
            img.src = src; img.alt = alt;
            box.hidden = false;
            document.body.style.overflow = 'hidden';   // cegah scroll di belakang
            trigger = from;
            closeBtn.focus();
        }
        function close() {
            box.hidden = true; img.src = '';
            document.body.style.overflow = '';
            if (trigger) trigger.focus();
        }

        document.querySelectorAll('[data-cert]').forEach(function (b) {
            b.addEventListener('click', function () { open(b.dataset.cert, b.dataset.alt, b); });
        });
        box.addEventListener('click', function (e) { if (e.target !== img) close(); });
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !box.hidden) close(); });
    })();
</script>
@endsection