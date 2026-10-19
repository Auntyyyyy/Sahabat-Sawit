{{--
    HERO PRODUK bergaya showcase: foto produk berganti di tengah, nama besar di bawah,
    slider berwarna untuk memilih produk. Data: $products dari ProductController@index.
--}}
@php
    $cxColors = ['#2f7d4a', '#e3a72f', '#8dbf44', '#d98a2b', '#3f9a63', '#c9a227'];
@endphp

<style>
    @import url('https://fonts.googleapis.com/css2?family=Archivo:ital,wght@1,800;1,900&display=swap');

    .cx {
        --cx-step: clamp(9rem, 24vw, 22rem);
        --cx-cur: #e3a72f;
        position: relative; overflow: hidden; isolation: isolate;
        min-height: 100vh; min-height: 100svh;
        display: flex; flex-direction: column;
        background: #050805; color: #fff;
    }
    .cx-glow {
        position: absolute; inset: 0; z-index: -1; opacity: 0; transition: opacity .9s ease;
        background: radial-gradient(70% 45% at 50% 105%, var(--c) 0%, transparent 70%);
    }
    .cx-glow.is-active { opacity: .85; }

    /* Panggung foto */
    .cx-stage { position: relative; flex: 1; min-height: 46vh; margin-top: 6rem; touch-action: pan-y; cursor: grab; }
    .cx-stage.is-drag { cursor: grabbing; }
    .cx-item {
        position: absolute; left: 50%; top: 50%; margin: 0;
        height: clamp(15rem, 44vh, 30rem); aspect-ratio: 3 / 4;
        transform: translate(calc(-50% + var(--d) * var(--cx-step)), -50%) rotate(-7deg) scale(calc(1 - var(--a) * .16));
        opacity: calc(1 - var(--a) * .4);
        filter: blur(calc(var(--a) * 2px)) brightness(calc(1 - var(--a) * .2));
        transition: transform .9s cubic-bezier(.2,.8,.2,1), opacity .6s ease, filter .6s ease;
        pointer-events: none; will-change: transform;
    }
    .cx-item img {
        display: block; width: 100%; height: 100%; object-fit: cover;
        border-radius: 1.25rem; box-shadow: 0 30px 60px rgba(0,0,0,.55);
        user-select: none; -webkit-user-drag: none;
    }

    /* Nama, tautan, slider */
    .cx-info { position: relative; z-index: 2; padding: 0 1.25rem 2.5rem; display: flex; flex-direction: column; align-items: center; text-align: center; gap: 1.1rem; }
    .cx-names {
        position: relative; width: min(100%, 12em); height: calc(2 * .98em); overflow: hidden;
        font-family: 'Archivo', sans-serif; font-weight: 900; font-style: italic; text-transform: uppercase;
        font-size: clamp(2rem, 6vw, 4.25rem); line-height: .98; letter-spacing: -.01em;
    }
    .cx-name {
        position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; text-wrap: balance;
        transition: transform .7s cubic-bezier(.2,.8,.2,1), opacity .5s ease;
    }
    .cx-name[data-pos="0"]  { transform: none; opacity: 1; }
    .cx-name[data-pos="-1"] { transform: translateY(-110%); opacity: 0; }
    .cx-name[data-pos="1"]  { transform: translateY(110%); opacity: 0; }

    .cx-link {
        color: #fff; font-weight: 600; font-size: .95rem; text-decoration: none;
        border-bottom: 2px solid var(--cx-cur); padding-bottom: .15rem; transition: color .2s;
    }
    .cx-link:hover { color: var(--cx-cur); }

    .cx-bar { position: relative; width: min(26rem, 72vw); height: 36px; display: flex; align-items: center; cursor: pointer; touch-action: none; }
    .cx-track { position: absolute; left: 0; right: 0; height: 4px; border-radius: 99px; background: linear-gradient(90deg, #2f7d4a, #8dbf44, #e3a72f, #d98a2b); }
    .cx-knob {
        position: absolute; top: 50%; left: 0; width: 22px; height: 22px; margin: -11px 0 0 -11px; border-radius: 50%;
        background: var(--cx-cur); box-shadow: 0 0 0 4px rgba(255,255,255,.18);
        transition: left .6s cubic-bezier(.2,.8,.2,1), background-color .6s ease;
    }
    .cx-bar.is-drag .cx-knob { transition: background-color .6s ease; }
    .cx-bar:focus-visible { outline: 3px solid #fff; outline-offset: 4px; border-radius: 99px; }
    .cx-hint { font-size: .75rem; letter-spacing: .12em; text-transform: uppercase; color: rgba(255,255,255,.6); }

    /* Tombol suara (kiri atas, di bawah navbar) */
    .cx-sound { position: absolute; z-index: 3; top: 6.5rem; left: clamp(1.25rem, 5vw, 3.5rem); display: inline-flex; align-items: center; gap: .6rem; padding: .35rem .5rem; background: none; border: 0; color: rgba(255,255,255,.75); font: 600 .75rem/1 'Archivo', sans-serif; letter-spacing: .12em; cursor: pointer; transition: color .2s; }
    .cx-sound:hover { color: #fff; }
    .cx-sound:focus-visible { outline: 3px solid #fff; outline-offset: 3px; border-radius: 4px; }
    .cx-eq { display: inline-flex; align-items: flex-end; gap: 2px; height: 12px; }
    .cx-eq i { display: block; width: 2px; height: 3px; background: currentColor; border-radius: 1px; }
    .cx-sound[aria-pressed="true"] .cx-eq i { animation: cxEq .9s ease-in-out infinite; }
    .cx-sound[aria-pressed="true"] .cx-eq i:nth-child(2) { animation-delay: .15s; }
    .cx-sound[aria-pressed="true"] .cx-eq i:nth-child(3) { animation-delay: .3s; }
    @keyframes cxEq { 0%, 100% { height: 3px; } 50% { height: 12px; } }

    /* Panah kiri-kanan, sengaja dibuat pudar */
    .cx-arrow { position: absolute; z-index: 20; top: 50%; transform: translateY(-50%); width: 3rem; height: 3rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.25); opacity: .45; cursor: pointer; transition: opacity .25s, background .25s; }
    .cx-arrow:hover { opacity: .95; background: rgba(255,255,255,.12); }
    .cx-arrow:disabled { opacity: .15; cursor: default; background: none; }
    .cx-arrow:focus-visible { outline: 3px solid #fff; outline-offset: 3px; opacity: 1; }
    .cx-arrow svg { width: 1.2rem; height: 1.2rem; }
    .cx-arrow-prev { left: clamp(.75rem, 3vw, 2rem); }
    .cx-arrow-next { right: clamp(.75rem, 3vw, 2rem); }

    @media (max-width: 640px) {
        .cx { --cx-step: 46vw; }
        .cx-arrow { width: 2.5rem; height: 2.5rem; }
        .cx-sound { top: 5.5rem; }
        .cx-item { height: clamp(13rem, 38vh, 22rem); }
    }
    @media (prefers-reduced-motion: reduce) {
        .cx-item, .cx-glow, .cx-name, .cx-knob { transition: none; }
        .cx-eq i { animation: none !important; height: 8px; }
    }
</style>

<section id="prodHero" class="cx" role="region" aria-roledescription="carousel" aria-label="Produk unggulan PT Sahabat Sawit">
    <h1 class="sr-only">Produk PT Sahabat Sawit Rokan Sejahtera</h1>

    @if ($products->count() > 1)
        <button type="button" class="cx-sound" id="cxSound" aria-pressed="true" aria-label="Suara geser: nyala">
            <span id="cxSoundTxt">ON</span><span class="cx-eq" aria-hidden="true"><i></i><i></i><i></i></span>
        </button>
    @endif

    @foreach ($products as $i => $product)
        <div class="cx-glow {{ $i === 0 ? 'is-active' : '' }}" style="--c: {{ $cxColors[$i % count($cxColors)] }}" aria-hidden="true"></div>
    @endforeach

    <div class="cx-stage" id="cxStage">
        @foreach ($products as $i => $product)
            <figure class="cx-item" aria-hidden="true"
                    data-name="{{ $product->name }}"
                    data-url="{{ route('products.show', $product->slug) }}"
                    data-color="{{ $cxColors[$i % count($cxColors)] }}"
                    style="--d: {{ $i }}; --a: {{ min($i, 3) }}; z-index: {{ 10 - $i }}">
                <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/600x800/164A2E/F5F1E8?text=' . urlencode($product->name) }}"
                     alt="" draggable="false" loading="{{ $i < 3 ? 'eager' : 'lazy' }}"
                     onerror="this.onerror=null;this.src='https://placehold.co/600x800/164A2E/F5F1E8?text={{ urlencode($product->name) }}'">
            </figure>
        @endforeach

        @if ($products->count() > 1)
            <button type="button" class="cx-arrow cx-arrow-prev" id="cxPrev" aria-label="Produk sebelumnya">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button type="button" class="cx-arrow cx-arrow-next" id="cxNext" aria-label="Produk berikutnya">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </button>
        @endif
    </div>

    <div class="cx-info">
        @if ($products->isNotEmpty())
            <h2 class="cx-names" id="cxNames">
                @foreach ($products as $i => $product)
                    <span class="cx-name" data-pos="{{ $i === 0 ? 0 : 1 }}">{{ $product->name }}</span>
                @endforeach
            </h2>
            <a class="cx-link" id="cxLink" href="{{ route('products.show', $products->first()->slug) }}">Lihat detail produk</a>

            @if ($products->count() > 1)
                <div class="cx-bar" id="cxBar" role="slider" tabindex="0" aria-label="Pilih produk"
                     aria-valuemin="1" aria-valuemax="{{ $products->count() }}" aria-valuenow="1">
                    <span class="cx-track"></span><span class="cx-knob" id="cxKnob"></span>
                </div>
                <p class="cx-hint">Geser untuk menjelajahi</p>
            @endif
            <p class="sr-only" aria-live="polite" id="cxLive"></p>
        @else
            <h2 class="cx-names"><span class="cx-name" data-pos="0">Hasil Perkebunan Berkualitas</span></h2>
            <a class="cx-link" href="{{ route('contact') }}">Hubungi Kami</a>
        @endif
    </div>
</section>

<script>
(function () {
    const hero = document.getElementById('prodHero');
    const items = [...hero.querySelectorAll('.cx-item')];
    const n = items.length;
    if (n < 1) return;

    const names = [...hero.querySelectorAll('.cx-name')];
    const glows = [...hero.querySelectorAll('.cx-glow')];
    const link = document.getElementById('cxLink');
    const live = document.getElementById('cxLive');
    const bar = document.getElementById('cxBar');
    const knob = document.getElementById('cxKnob');
    const stage = document.getElementById('cxStage');
    let cur = 0, dragging = false, ready = false, soundOn = true, lastSound = 0;
    const prevBtn = document.getElementById('cxPrev');
    const nextBtn = document.getElementById('cxNext');
    const soundBtn = document.getElementById('cxSound');
    const soundTxt = document.getElementById('cxSoundTxt');

    // Suara dibuat langsung oleh browser (Web Audio), tanpa file audio.
    // Baru bisa berbunyi setelah pengguna berinteraksi (geser, klik panah, atau tekan tombol suara).
    let audioCtx = null;
    function getAudio() {
        if (!audioCtx) {
            const AC = window.AudioContext || window.webkitAudioContext;
            if (!AC) return null;
            audioCtx = new AC();
        }
        if (audioCtx.state === 'suspended') audioCtx.resume();
        return audioCtx;
    }
    function whoosh(dir) {
        if (!soundOn) return;
        const now = performance.now();
        if (now - lastSound < 140) return;
        lastSound = now;
        const a = getAudio();
        if (!a) return;
        const t = a.currentTime, dur = .45;

        // desir angin: derau yang disaring, sapuan naik (maju) atau turun (mundur)
        const len = Math.floor(a.sampleRate * dur);
        const buf = a.createBuffer(1, len, a.sampleRate);
        const data = buf.getChannelData(0);
        for (let k = 0; k < len; k++) data[k] = Math.random() * 2 - 1;
        const noise = a.createBufferSource();
        noise.buffer = buf;
        const bp = a.createBiquadFilter();
        bp.type = 'bandpass'; bp.Q.value = 1.2;
        bp.frequency.setValueAtTime(dir > 0 ? 300 : 1800, t);
        bp.frequency.exponentialRampToValueAtTime(dir > 0 ? 1800 : 300, t + dur);
        const g = a.createGain();
        g.gain.setValueAtTime(.0001, t);
        g.gain.exponentialRampToValueAtTime(.18, t + .12);
        g.gain.exponentialRampToValueAtTime(.0001, t + dur);
        noise.connect(bp); bp.connect(g); g.connect(a.destination);
        noise.start(t); noise.stop(t + dur);

        // ketukan halus
        const o = a.createOscillator(), og = a.createGain();
        o.type = 'sine';
        o.frequency.setValueAtTime(dir > 0 ? 620 : 520, t + .02);
        og.gain.setValueAtTime(.0001, t);
        og.gain.exponentialRampToValueAtTime(.08, t + .03);
        og.gain.exponentialRampToValueAtTime(.0001, t + .2);
        o.connect(og); og.connect(a.destination);
        o.start(t); o.stop(t + .22);
    }

    try { if (localStorage.getItem('cxSound') === 'off') soundOn = false; } catch (e) {}
    function paintSound() {
        if (!soundBtn) return;
        soundBtn.setAttribute('aria-pressed', soundOn ? 'true' : 'false');
        soundBtn.setAttribute('aria-label', 'Suara geser: ' + (soundOn ? 'nyala' : 'mati'));
        soundTxt.textContent = soundOn ? 'ON' : 'OFF';
    }
    if (soundBtn) {
        soundBtn.addEventListener('click', function () {
            soundOn = !soundOn;
            try { localStorage.setItem('cxSound', soundOn ? 'on' : 'off'); } catch (e) {}
            paintSound();
            if (soundOn) whoosh(1);
        });
        paintSound();
    }

    function render(i) {
        const prev = cur;
        cur = Math.min(n - 1, Math.max(0, i));
        if (ready && cur !== prev) whoosh(cur > prev ? 1 : -1);
        items.forEach(function (el, k) {
            const d = k - cur;
            el.style.setProperty('--d', d);
            el.style.setProperty('--a', Math.min(Math.abs(d), 3));
            el.style.zIndex = 10 - Math.abs(d);
        });
        names.forEach(function (el, k) { el.dataset.pos = k === cur ? 0 : (k < cur ? -1 : 1); });
        glows.forEach(function (el, k) { el.classList.toggle('is-active', k === cur); });
        hero.style.setProperty('--cx-cur', items[cur].dataset.color);
        link.href = items[cur].dataset.url;
        live.textContent = (cur + 1) + ' dari ' + n + ': ' + items[cur].dataset.name;
        if (prevBtn) { prevBtn.disabled = cur === 0; nextBtn.disabled = cur === n - 1; }
        if (bar) {
            bar.setAttribute('aria-valuenow', cur + 1);
            if (!dragging) knob.style.left = (cur / (n - 1) * 100) + '%';
        }
    }

    if (bar) {
        function fromX(x) {
            const r = bar.getBoundingClientRect();
            const p = Math.min(1, Math.max(0, (x - r.left) / r.width));
            knob.style.left = (p * 100) + '%';
            if (Math.round(p * (n - 1)) !== cur) render(Math.round(p * (n - 1)));
        }
        bar.addEventListener('pointerdown', function (e) { dragging = true; bar.setPointerCapture(e.pointerId); bar.classList.add('is-drag'); fromX(e.clientX); });
        bar.addEventListener('pointermove', function (e) { if (dragging) fromX(e.clientX); });
        function endDrag() { if (!dragging) return; dragging = false; bar.classList.remove('is-drag'); render(cur); }
        bar.addEventListener('pointerup', endDrag);
        bar.addEventListener('pointercancel', endDrag);
        bar.addEventListener('keydown', function (e) {
            if (e.key === 'ArrowRight' || e.key === 'ArrowUp') { e.preventDefault(); render(cur + 1); }
            if (e.key === 'ArrowLeft' || e.key === 'ArrowDown') { e.preventDefault(); render(cur - 1); }
        });
    }

    // Geser foto dengan mouse atau jari
    let sx = null;
    stage.addEventListener('pointerdown', function (e) { if (e.target.closest('.cx-arrow')) return; sx = e.clientX; stage.classList.add('is-drag'); });
    if (prevBtn) {
        prevBtn.addEventListener('click', function () { render(cur - 1); });
        nextBtn.addEventListener('click', function () { render(cur + 1); });
    }
    window.addEventListener('pointerup', function (e) {
        if (sx === null) return;
        const dx = e.clientX - sx; sx = null; stage.classList.remove('is-drag');
        if (Math.abs(dx) > 50) render(cur + (dx < 0 ? 1 : -1));
    });

    render(0);
    ready = true;
})();
</script>