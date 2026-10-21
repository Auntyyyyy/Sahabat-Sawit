@extends('layouts.app')

@section('title', 'Perkebunan Kami — PT Sahabat Sawit')

@section('content')

@php
    $fotoHero = 'sawit.jpeg';   // foto hero (file ada di public/images)
    $peta     = 'bahanbaku.png';
    $mitra    = collect($mitra_photos)->values();
@endphp

<style>
    /* Perkebunan — hero berfoto, angka sebagai titik fokus, peta selebar layar, dan galeri kemitraan. Class berawalan "pl-". */
    @import url('https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,600;12..96,800&display=swap');

    .pl {
        --hijau: #16321f; --hijau-muda: #2f5a3a; --emas: #e3a72f; --krem: #f2f4ec;
        --gutter: max(clamp(1.25rem, 5vw, 3.5rem), calc((100vw - 80rem) / 2));
        font-family: "Bricolage Grotesque", system-ui, -apple-system, "Segoe UI", sans-serif;
        background: var(--hijau); color: var(--krem); line-height: 1.6;
    }
    .pl *, .pl *::before, .pl *::after { box-sizing: border-box; }
    .pl a { color: inherit; text-decoration: none; }
    .pl a:focus-visible, .pl button:focus-visible { outline: 3px solid var(--krem); outline-offset: 3px; }

    /* Hero berfoto */
    .pl-hero { position: relative; isolation: isolate; overflow: hidden; display: flex; align-items: flex-end; min-height: min(92vh, 58rem); background: linear-gradient(145deg, var(--hijau-muda), var(--hijau)); }
    .pl-hero::after { content: ''; position: absolute; inset: 0; z-index: -1; background: linear-gradient(90deg, rgba(22,50,31,.9) 0%, rgba(22,50,31,.55) 50%, rgba(22,50,31,.15) 100%), linear-gradient(0deg, rgba(22,50,31,.85) 0%, transparent 45%); }
    .pl-bg { position: absolute; inset: 0; z-index: -2; width: 100%; height: 100%; object-fit: cover; }
    .pl-hero-inner { width: 100%; padding: clamp(8rem, 18vh, 11rem) var(--gutter) clamp(3rem, 8vh, 5rem); }
    .pl-crumb { display: flex; flex-wrap: wrap; align-items: center; gap: .5rem; font-size: .9rem; color: rgba(242,244,236,.6); }
    .pl-crumb a:hover { color: var(--krem); }
    .pl-crumb [aria-current] { color: var(--krem); }
    .pl-title { margin-top: 1.5rem; max-width: 12ch; font-size: clamp(2.8rem, 9vw, 7.5rem); font-weight: 800; line-height: .98; letter-spacing: -.045em; }
    .pl-lead { margin-top: 1.5rem; max-width: 34rem; font-size: 1.1rem; color: rgba(242,244,236,.8); }

    /* Section */
    .pl-sec { padding: clamp(4rem, 10vh, 7rem) var(--gutter); }
    .pl-label { font-size: .95rem; color: rgba(242,244,236,.6); }
    .pl-h2 { margin-top: .5rem; max-width: 18ch; font-size: clamp(1.8rem, 4vw, 3rem); font-weight: 800; line-height: 1.05; letter-spacing: -.03em; }
    .pl-p { margin-top: 1.25rem; max-width: 34rem; color: rgba(242,244,236,.78); }

    /* Angka: satu angka raksasa sebagai titik fokus (section terang) */
    .pl-light { background: var(--krem); color: var(--hijau); }
    .pl-light .pl-label { color: rgba(22,50,31,.6); }
    .pl-stats { display: grid; grid-template-columns: 1fr; gap: 3rem; align-items: end; }
    @media (min-width: 900px) { .pl-stats { grid-template-columns: 7fr 5fr; gap: 5rem; } }
    .pl-bigval { margin-top: .5rem; font-size: clamp(2.4rem, 6vw, 5.5rem); font-weight: 800; line-height: 1; letter-spacing: -.05em; overflow-wrap: anywhere; }
    .pl-facts { margin: 0; }
    .pl-fact { padding: 1.1rem 0; border-top: 1px solid rgba(22,50,31,.2); }
    .pl-fact:last-child { border-bottom: 1px solid rgba(22,50,31,.2); }
    .pl-fact dt { font-size: .95rem; color: rgba(22,50,31,.6); }
    .pl-fact dd { margin: .2rem 0 0; font-size: clamp(1.3rem, 2.4vw, 1.9rem); font-weight: 700; letter-spacing: -.02em; line-height: 1.2; }

    /* Peta: selebar layar di latar paling pekat */
    .pl-deep { background: #0f2417; }
    .pl-map-btn { display: block; width: 100%; margin-top: 2rem; padding: 0; border: 0; background: none; cursor: zoom-in; }
    .pl-map { display: block; width: 100%; height: auto; border-radius: .25rem; background: #fff; }
    .pl-note { margin-top: 1rem; font-size: .85rem; color: rgba(242,244,236,.55); }

    /* Galeri kemitraan */
    .pl-car { display: grid; grid-template-columns: 1fr; gap: 2.5rem; align-items: center; }
    @media (max-width: 899px) { .pl-view { order: -1; } }
    @media (min-width: 900px) { .pl-car { grid-template-columns: 5fr 7fr; gap: 4rem; } }
    .pl-cap { min-height: 8rem; margin-top: 2rem; transition: opacity .15s; }
    .pl-cap.is-fading { opacity: 0; }
    .pl-cap h3 { font-size: clamp(1.4rem, 2.6vw, 2rem); font-weight: 800; letter-spacing: -.02em; line-height: 1.15; }
    .pl-cap p { margin-top: .75rem; color: rgba(242,244,236,.75); }
    .pl-ctrl { margin-top: 1.5rem; display: flex; align-items: center; gap: 1rem; }
    .pl-count { min-width: 4.5rem; font-size: .9rem; font-weight: 600; color: rgba(242,244,236,.7); }
    .pl-prog { flex: 1; max-width: 12rem; height: 2px; background: rgba(242,244,236,.2); }
    .pl-prog span { display: block; width: 0; height: 100%; background: var(--emas); transition: width .5s ease; }
    .pl-btn { width: 44px; height: 44px; border-radius: 50%; border: 1px solid rgba(242,244,236,.4); background: transparent; color: var(--krem); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: background .2s, border-color .2s, color .2s; }
    .pl-btn:hover { background: var(--emas); border-color: var(--emas); color: var(--hijau); }
    .pl-btn svg { width: 20px; height: 20px; }
    .pl-btns { display: flex; gap: .6rem; margin-left: auto; }
    .pl-view { position: relative; overflow: hidden; aspect-ratio: 4 / 3; border-radius: .25rem; background: linear-gradient(145deg, var(--hijau-muda), #1d4129); touch-action: pan-y; }
    .pl-track { display: flex; height: 100%; transition: transform .5s cubic-bezier(.2,.8,.2,1); }
    .pl-slide { flex: 0 0 100%; height: 100%; }
    .pl-slide button { display: block; width: 100%; height: 100%; padding: 0; border: 0; background: none; cursor: zoom-in; }
    .pl-slide img { width: 100%; height: 100%; object-fit: cover; display: block; }

    /* Lightbox */
    .pl-lightbox { position: fixed; inset: 0; z-index: 100; display: flex; align-items: center; justify-content: center; padding: 2rem; background: rgba(5,8,5,.92); }
    .pl-lightbox[hidden] { display: none; }
    .pl-lightbox img { max-width: min(94vw, 70rem); max-height: 88vh; object-fit: contain; border-radius: .25rem; }
    .pl-lightbox-close { position: absolute; top: 1rem; right: 1.25rem; width: 44px; height: 44px; border: 1px solid rgba(242,244,236,.4); border-radius: 50%; background: transparent; color: var(--krem); font-size: 1.6rem; line-height: 1; cursor: pointer; }
    .pl-lightbox-close:hover { background: var(--emas); border-color: var(--emas); color: var(--hijau); }

    @media (prefers-reduced-motion: reduce) { .pl-track, .pl-prog span, .pl-cap { transition: none; } }
</style>

<div class="pl">

    {{-- HERO berfoto --}}
    <header class="pl-hero">
        <img class="pl-bg" src="{{ asset('images/' . $fotoHero) }}" alt="" onerror="this.style.display='none'">
        <div class="pl-hero-inner">
            <nav class="pl-crumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Beranda</a>
                <span aria-hidden="true">/</span>
                <span aria-current="page">Perkebunan Kami</span>
            </nav>
            <h1 class="pl-title">Dari Bumi Rokan Hilir</h1>
            <p class="pl-lead">Bertumbuh bersama dari sumber terbaik.</p>
        </div>
    </header>

    {{-- ANGKA: luas area jadi titik fokus (section terang) --}}
    <section class="pl-sec pl-light">
        <div class="pl-stats">
            <div>
                <p class="pl-label">Luas area</p>
                <p class="pl-bigval">{{ $stats['luas_area'] }}</p>
            </div>
            <dl class="pl-facts">
                <div class="pl-fact"><dt>Lokasi</dt><dd>{{ $stats['lokasi'] }}</dd></div>
                <div class="pl-fact"><dt>Kemitraan</dt><dd>{{ $stats['kemitraan'] }}</dd></div>
                <div class="pl-fact"><dt>Diusahakan sendiri</dt><dd>{{ $stats['diusahakan_sendiri'] }}</dd></div>
            </dl>
        </div>
    </section>

    {{-- PETA selebar layar --}}
    <section class="pl-sec pl-deep">
        <p class="pl-label">Peta lokasi</p>
        <h2 class="pl-h2">Sebaran Lahan Kami</h2>
        <button type="button" class="pl-map-btn" data-pl-zoom="{{ asset('images/' . $peta) }}"
                data-alt="Peta Lokasi Perkebunan PT Sahabat Sawit di Rokan Hilir, Riau" aria-label="Perbesar peta lokasi">
            <img class="pl-map" src="{{ asset('images/' . $peta) }}"
                 alt="Peta Lokasi Perkebunan PT Sahabat Sawit di Rokan Hilir, Riau" loading="lazy"
                 onerror="this.style.display='none'">
        </button>
        <p class="pl-note">Klik peta untuk memperbesar.</p>
        <p class="pl-note">*SK : 903/MENLHK/SETJEN/PLA.2/12/2016 RIAU.</p>
    </section>

    {{-- JEJAK KEMITRAAN --}}
    @if($mitra->isNotEmpty())
    <section class="pl-sec" id="jejak-kemitraan">
        <div class="pl-car">
            <div>
                <p class="pl-label">Kemitraan</p>
                <h2 class="pl-h2">Jejak Kemitraan</h2>
                <p class="pl-p">Mengabadikan perjalanan, kerja keras, dan kebersamaan bersama para petani mitra.</p>

                <div class="pl-cap" id="plCap" aria-live="polite">
                    <h3 id="plCapTitle">{{ $mitra[0]['title'] ?? '' }}</h3>
                    <p id="plCapDesc">{{ $mitra[0]['desc'] ?? '' }}</p>
                </div>

                @if($mitra->count() > 1)
                <div class="pl-ctrl">
                    <span class="pl-count" id="plCount">01 / {{ str_pad($mitra->count(), 2, '0', STR_PAD_LEFT) }}</span>
                    <span class="pl-prog" aria-hidden="true"><span id="plProg"></span></span>
                    <div class="pl-btns">
                        <button type="button" class="pl-btn" data-pl-nav="-1" aria-label="Foto sebelumnya">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <button type="button" class="pl-btn" data-pl-nav="1" aria-label="Foto berikutnya">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </div>
                @endif
            </div>

            <div class="pl-view" id="plView">
                <div class="pl-track" id="plTrack">
                    @foreach($mitra as $photo)
                    <div class="pl-slide">
                        <button type="button" data-pl-zoom="{{ asset('images/' . $photo['src']) }}"
                                data-alt="{{ $photo['title'] ?? 'Perkebunan mitra PT Sahabat Sawit' }}"
                                aria-label="Perbesar foto: {{ $photo['title'] ?? 'Perkebunan mitra' }}">
                            <img src="{{ asset('images/' . $photo['src']) }}" alt="{{ $photo['title'] ?? 'Perkebunan mitra PT Sahabat Sawit' }}" loading="{{ $loop->first ? 'eager' : 'lazy' }}" onerror="this.style.display='none'">
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Lightbox --}}
    <div class="pl-lightbox" id="plLightbox" role="dialog" aria-modal="true" aria-label="Foto diperbesar" hidden>
        <button type="button" class="pl-lightbox-close" id="plLightboxClose" aria-label="Tutup">&times;</button>
        <img id="plLightboxImg" src="" alt="">
    </div>

</div>

@php
    $mitraCaptions = $mitra->map(fn ($p) => ['title' => $p['title'] ?? '', 'desc' => $p['desc'] ?? ''])->values();
@endphp

<script>
    (function () {
        const track = document.getElementById('plTrack');
        if (track) {
        const captions = @json($mitraCaptions);
        const n = captions.length;
        const cap = document.getElementById('plCap');
        const capTitle = document.getElementById('plCapTitle');
        const capDesc = document.getElementById('plCapDesc');
        const count = document.getElementById('plCount');
        const prog = document.getElementById('plProg');
        const view = document.getElementById('plView');
        let cur = 0;

        const pad = function (x) { return String(x).padStart(2, '0'); };

        function render(first) {
            track.style.transform = 'translateX(-' + (cur * 100) + '%)';
            if (count) count.textContent = pad(cur + 1) + ' / ' + pad(n);
            if (prog) prog.style.width = ((cur + 1) / n * 100) + '%';
            if (first) return;
            cap.classList.add('is-fading');
            setTimeout(function () {
                capTitle.textContent = captions[cur].title;
                capDesc.textContent = captions[cur].desc;
                cap.classList.remove('is-fading');
            }, 150);
        }

        function go(dir) { cur = (cur + dir + n) % n; render(); }

        document.querySelectorAll('[data-pl-nav]').forEach(function (b) {
            b.addEventListener('click', function () { go(Number(b.dataset.plNav)); });
        });

        // Geser dengan jari atau mouse
        let sx = null;
        view.addEventListener('pointerdown', function (e) { sx = e.clientX; });
        window.addEventListener('pointerup', function (e) {
            if (sx === null) return;
            const dx = e.clientX - sx; sx = null;
            if (n > 1 && Math.abs(dx) > 50) go(dx < 0 ? 1 : -1);
        });

        render(true);
        }

        // Lightbox
        const box = document.getElementById('plLightbox');
        const img = document.getElementById('plLightboxImg');
        const closeBtn = document.getElementById('plLightboxClose');
        let trigger = null;
        function open(src, alt, from) { img.src = src; img.alt = alt; box.hidden = false; document.body.style.overflow = 'hidden'; trigger = from; closeBtn.focus(); }
        function close() { box.hidden = true; img.src = ''; document.body.style.overflow = ''; if (trigger) trigger.focus(); }
        document.querySelectorAll('[data-pl-zoom]').forEach(function (b) {
            b.addEventListener('click', function () { open(b.dataset.plZoom, b.dataset.alt, b); });
        });
        box.addEventListener('click', function (e) { if (e.target !== img) close(); });
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !box.hidden) close(); });
    })();
</script>
@endsection