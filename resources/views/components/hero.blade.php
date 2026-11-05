@props(['stats' => []])

@php
    $isEn = app()->getLocale() === 'en';
@endphp

<style>
    /* Hero beranda — video latar berparalaks, sapaan sesuai waktu, panel statistik 3D. Class berawalan "hr-". */
    @import url('https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,600;12..96,800&display=swap');

    .hr {
        --hijau: #16321f; --hijau-muda: #2f5a3a; --emas: #e3a72f; --krem: #f2f4ec;
        --gutter: max(clamp(1.25rem, 5vw, 3.5rem), calc((100vw - 80rem) / 2));
        position: relative; isolation: isolate; overflow: hidden;
        display: flex; align-items: center;
        min-height: max(36rem, min(100vh, 60rem));
        background: linear-gradient(145deg, var(--hijau-muda), #0f2417);
        color: var(--krem);
        font-family: "Bricolage Grotesque", system-ui, -apple-system, "Segoe UI", sans-serif;
        line-height: 1.6;
    }
    .hr *, .hr *::before, .hr *::after { box-sizing: border-box; }
    .hr a { color: inherit; text-decoration: none; }
    .hr a:focus-visible { outline: 3px solid var(--emas); outline-offset: 3px; }

    /* Lapisan latar: video (bergeser pelan saat digulir), bayangan gelap di kiri, pudar ke hijau di bawah */
    .hr-bg { position: absolute; z-index: -3; left: 0; right: 0; top: -24%; bottom: 0; will-change: transform; }
    .hr-bg video { display: block; width: 100%; height: 100%; object-fit: cover; }
    .hr::before { content: ''; position: absolute; inset: 0; z-index: -2; background: linear-gradient(90deg, rgba(15,36,23,.94) 0%, rgba(15,36,23,.72) 48%, rgba(15,36,23,.35) 100%), linear-gradient(180deg, rgba(15,36,23,.55) 0%, transparent 30%); }
    .hr::after { content: ''; position: absolute; left: 0; right: 0; bottom: 0; height: 9rem; z-index: -1; background: linear-gradient(0deg, var(--hijau) 0%, transparent 100%); }

    .hr-inner { width: 100%; display: grid; grid-template-columns: minmax(0, 1fr); gap: 3rem; align-items: center; padding: clamp(7.5rem, 16vh, 10rem) var(--gutter) clamp(5rem, 11vh, 7rem); }
    @media (min-width: 1024px) { .hr-inner { grid-template-columns: minmax(0, 1.15fr) minmax(0, 1fr); gap: 4rem; } }

    /* Teks sapaan */
    .hr-greet { display: inline-flex; align-items: center; gap: .75rem; font-size: .95rem; font-weight: 600; color: rgba(242,244,236,.88); }
    .hr-greet::before { content: ''; width: 2.25rem; height: 2px; background: var(--emas); }
    .hr-eyebrow { display: inline-block; margin-top: 1rem; padding: .3rem .95rem; border: 1px solid rgba(227,167,47,.5); border-radius: 99px; background: rgba(227,167,47,.12); color: var(--emas); font-size: .78rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; }
    .hr-title { margin-top: 1.25rem; max-width: 15ch; font-size: clamp(2.5rem, 6.2vw, 5.25rem); font-weight: 800; line-height: 1.02; letter-spacing: -.04em; }
    .hr-title em { font-style: normal; color: var(--emas); background: linear-gradient(var(--emas), var(--emas)) 0 100% / 100% .09em no-repeat; }
    .hr-lead { margin-top: 1.5rem; max-width: 34rem; color: rgba(242,244,236,.8); }
    .hr-cta { margin-top: 2rem; display: flex; flex-wrap: wrap; gap: .9rem; }
    .hr-btn { display: inline-flex; align-items: center; justify-content: center; padding: .95rem 1.8rem; border: 2px solid transparent; border-radius: .25rem; font-weight: 700; font-size: .95rem; transition: background .2s, border-color .2s, color .2s, transform .2s; }
    .hr-btn--gold { background: var(--emas); border-color: var(--emas); color: var(--hijau); }
    .hr-btn--gold:hover { background: #f0b84a; border-color: #f0b84a; }
    .hr-btn--ghost { border-color: rgba(242,244,236,.7); color: var(--krem); }
    .hr-btn--ghost:hover { background: var(--krem); border-color: var(--krem); color: var(--hijau); }
    @media (hover: hover) and (prefers-reduced-motion: no-preference) { .hr-btn:hover { transform: translateY(-2px); } }

    /* Panel statistik 3D: kartu kaca bertingkat kedalaman, miring mengikuti kursor (datar di layar kecil) */
    .hr-stage { perspective: 1100px; }
    .hr-deck { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1rem; transform-style: preserve-3d; transform: rotateX(var(--rx, 0deg)) rotateY(var(--ry, 0deg)); transition: transform .25s ease-out; }
    .hr-stat { padding: 1.3rem 1.25rem; border: 1px solid rgba(242,244,236,.2); border-radius: .5rem; background: linear-gradient(145deg, rgba(242,244,236,.16), rgba(242,244,236,.05)); -webkit-backdrop-filter: blur(10px); backdrop-filter: blur(10px); box-shadow: 0 24px 40px rgba(0,0,0,.3); }
    .hr-stat-val { font-size: clamp(2rem, 4vw, 3.25rem); font-weight: 800; line-height: 1; letter-spacing: -.04em; color: var(--emas); }
    .hr-stat-label { margin-top: .6rem; font-size: .9rem; font-weight: 600; color: rgba(242,244,236,.78); }
    @media (min-width: 1024px) {
        .hr-deck { --rx: 8deg; --ry: -14deg; gap: 1.25rem; }
        .hr-stat { transform: translateZ(var(--z, 0px)); padding: 1.6rem 1.5rem; }
        .hr-stat:nth-child(even) { margin-top: 1.5rem; }   /* sedikit berundak agar terasa berlapis */
    }

    /* Penanda gulir */
    .hr-scroll { position: absolute; left: 50%; bottom: 1.5rem; transform: translateX(-50%); display: flex; flex-direction: column; align-items: center; gap: .5rem; font-size: .72rem; font-weight: 600; letter-spacing: .14em; text-transform: uppercase; color: rgba(242,244,236,.7); }
    .hr-scroll::after { content: ''; width: 1px; height: 2.5rem; background: linear-gradient(var(--emas), transparent); }
    .hr-scroll:hover { color: var(--emas); }
    @media (max-height: 640px), (max-width: 700px) { .hr-scroll { display: none; } }

    /* Kemunculan teks bertahap */
    @keyframes hrRise { from { opacity: 0; transform: translateY(22px); } }
    @keyframes hrLine { 0%, 100% { opacity: .35; } 50% { opacity: 1; } }
    @media (prefers-reduced-motion: no-preference) {
        .hr-rise { animation: hrRise .8s cubic-bezier(.2, .7, .2, 1) backwards; animation-delay: var(--d, 0s); }
        .hr-scroll::after { animation: hrLine 2s ease-in-out infinite; }
    }
</style>

<section class="hr" aria-label="{{ $isEn ? 'Welcome' : 'Sambutan' }}">

    {{-- Video latar (poster = gambar, bukan file video) --}}
    <div class="hr-bg" aria-hidden="true">
        <video autoplay muted loop playsinline poster="{{ asset('images/herotentang.png') }}" onerror="this.style.display='none'">
            <source src="{{ asset('images/profil.mp4') }}" type="video/mp4">
        </video>
    </div>

    <div class="hr-inner">

        <div>
            {{-- Sapaan: disesuaikan jam perangkat pengunjung oleh skrip di bawah --}}
            <p class="hr-greet hr-rise" id="hrGreet" data-lang="{{ $isEn ? 'en' : 'id' }}">{{ $isEn ? 'Welcome' : 'Selamat datang' }}</p>
            <span class="hr-eyebrow hr-rise" style="--d:.1s">PT. Sahabat Sawit Rokan Sejahtera</span>

            <h1 class="hr-title hr-rise" style="--d:.2s">
                Bertumbuh Bersama, <em>Berkelanjutan</em> untuk Masa Depan
            </h1>

            <p class="hr-lead hr-rise" style="--d:.35s">
                PT. Sahabat Sawit Rokan Sejahtera berkomitmen untuk mengembangkan industri kelapa sawit yang produktif, bertanggung jawab, dan berkelanjutan bagi masyarakat serta lingkungan di Rokan Hilir, Riau.
            </p>

            <div class="hr-cta hr-rise" style="--d:.5s">
                <a href="{{ route('about') }}" class="hr-btn hr-btn--gold">Tentang Kami</a>
                <a href="{{ route('contact') }}" class="hr-btn hr-btn--ghost">Hubungi Kami</a>
            </div>
        </div>

        {{-- Statistik: atribut data-counter tetap dipakai skrip penghitung yang sudah ada --}}
        @if(count($stats))
        <div class="hr-stage hr-rise" style="--d:.6s" data-counter-section>
            <div class="hr-deck" id="hrDeck">
                @foreach($stats as $stat)
                <div class="hr-stat" style="--z: {{ [30, 70, 50, 90][$loop->index % 4] }}px">
                    <p class="hr-stat-val" data-counter="{{ $stat['value'] }}">{{ $stat['value'] }}</p>
                    <p class="hr-stat-label">{{ $stat['label'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>

    <a class="hr-scroll" href="#hmLanjut" aria-label="{{ $isEn ? 'Scroll down' : 'Gulir ke bawah' }}">{{ $isEn ? 'Scroll' : 'Gulir' }}</a>
</section>

<script>
    (function () {
        const hero = document.querySelector('.hr');
        if (!hero) return;

        // Sapaan sesuai jam di perangkat pengunjung
        const greet = document.getElementById('hrGreet');
        if (greet) {
            const h = new Date().getHours();
            if (greet.dataset.lang === 'en') {
                greet.textContent = (h >= 4 && h < 12 ? 'Good morning' : h >= 12 && h < 18 ? 'Good afternoon' : 'Good evening') + ', welcome';
            } else {
                greet.textContent = (h >= 4 && h < 11 ? 'Selamat pagi' : h >= 11 && h < 15 ? 'Selamat siang' : h >= 15 && h < 18 ? 'Selamat sore' : 'Selamat malam') + ', selamat datang';
            }
        }

        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

        // Parallax: video bergeser lebih lambat dari halaman saat digulir
        const bg = hero.querySelector('.hr-bg');
        let ticking = false;
        function parallax() {
            const y = window.scrollY;
            if (bg && y < hero.offsetHeight) bg.style.transform = 'translate3d(0,' + (y * 0.22) + 'px,0)';
            ticking = false;
        }
        window.addEventListener('scroll', function () {
            if (!ticking) { ticking = true; requestAnimationFrame(parallax); }
        }, { passive: true });

        // Panel statistik 3D: miring mengikuti kursor (hanya di desktop dengan mouse)
        const deck = document.getElementById('hrDeck');
        const wide = window.matchMedia('(min-width: 1024px) and (hover: hover) and (pointer: fine)');
        if (deck) {
            hero.addEventListener('pointermove', function (e) {
                if (!wide.matches) return;
                const r = hero.getBoundingClientRect();
                const x = (e.clientX - r.left) / r.width - 0.5;
                const y = (e.clientY - r.top) / r.height - 0.5;
                deck.style.setProperty('--ry', (-14 + x * 12) + 'deg');
                deck.style.setProperty('--rx', (8 - y * 10) + 'deg');
            });
            hero.addEventListener('pointerleave', function () {
                deck.style.removeProperty('--ry');
                deck.style.removeProperty('--rx');
            });
        }
    })();
</script>