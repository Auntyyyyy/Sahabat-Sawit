<!-- resources/views/components/splash-screen.blade.php -->
<div id="splash-screen" class="splash-screen">

    <!-- Daun sawit dekoratif (animasi jatuh perlahan) -->
    <div class="splash-leaves" aria-hidden="true">
        <svg class="leaf leaf-1" width="40" height="40" viewBox="0 0 24 24" fill="#f2f4ec">
            <path d="M12 2C7 6 4 11 4 15c0 4 3.5 7 8 7s8-3 8-7c0-4-3-9-8-13z"/>
        </svg>
        <svg class="leaf leaf-2" width="30" height="30" viewBox="0 0 24 24" fill="#f2f4ec">
            <path d="M12 2C7 6 4 11 4 15c0 4 3.5 7 8 7s8-3 8-7c0-4-3-9-8-13z"/>
        </svg>
        <svg class="leaf leaf-3" width="50" height="50" viewBox="0 0 24 24" fill="#f2f4ec">
            <path d="M12 2C7 6 4 11 4 15c0 4 3.5 7 8 7s8-3 8-7c0-4-3-9-8-13z"/>
        </svg>
        <svg class="leaf leaf-4" width="35" height="35" viewBox="0 0 24 24" fill="#f2f4ec">
            <path d="M12 2C7 6 4 11 4 15c0 4 3.5 7 8 7s8-3 8-7c0-4-3-9-8-13z"/>
        </svg>
    </div>

    <div class="splash-content">

        <!-- Maskot + sapaan -->
        <div class="mascot-wrap">
            <img src="{{ asset('images/maskot.png') }}" alt="Maskot PT. Sahabat Sawit Rokan Sejahtera" class="mascot-img">
            <span class="wave-hand" aria-hidden="true">👋</span>
            <div class="greet-bubble">Halo!</div>
        </div>

        <p class="splash-eyebrow">Selamat datang di</p>
        <h1 class="splash-title">PT. Sahabat Sawit <span>Rokan Sejahtera</span></h1>

        <div class="splash-divider"></div>

        <p class="splash-tagline">&ldquo;Tumbuh Bersama, Membangun Keberlanjutan&rdquo;</p>

        <div class="splash-dots">
            <span class="dot"></span>
            <span class="dot"></span>
            <span class="dot"></span>
        </div>
    </div>
</div>

<style>
    :root {
        --hijau: #16321f;
        --hijau-muda: #2f5a3a;
        --emas: #e3a72f;
        --krem: #f2f4ec;
    }

    .splash-screen {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        text-align: center;
        padding: 1.5rem;
        background: linear-gradient(135deg, var(--hijau) 0%, var(--hijau-muda) 65%, var(--hijau) 100%);
        font-family: "Bricolage Grotesque", system-ui, -apple-system, "Segoe UI", sans-serif;
        color: var(--krem);
        transition: opacity .7s ease, visibility .7s ease;
    }

    .splash-screen.splash-hide {
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
    }

    /* Daun jatuh */
    .splash-leaves { position: absolute; inset: 0; opacity: .3; pointer-events: none; }
    .leaf { position: absolute; top: -60px; animation: fall linear infinite; }
    .leaf-1 { left: 10%; animation-duration: 9s; animation-delay: 0s; }
    .leaf-2 { left: 30%; animation-duration: 12s; animation-delay: 1.5s; }
    .leaf-3 { left: 65%; animation-duration: 10s; animation-delay: 3s; }
    .leaf-4 { left: 85%; animation-duration: 8s; animation-delay: 2s; }

    @keyframes fall {
        0%   { transform: translateY(-60px) rotate(0deg); opacity: 0; }
        10%  { opacity: 1; }
        90%  { opacity: 1; }
        100% { transform: translateY(110vh) rotate(360deg); opacity: 0; }
    }

    .splash-content { position: relative; z-index: 1; max-width: 32rem; }

    /* Maskot */
    .mascot-wrap {
        position: relative;
        display: inline-flex;
        margin: 0 auto 1rem;
        animation: mascotPopIn .6s cubic-bezier(.34,1.56,.64,1) both;
    }
    .mascot-img {
        width: 9rem;
        height: 9rem;
        object-fit: contain;
        display: block;
        filter: drop-shadow(0 10px 18px rgba(0,0,0,.35));
        animation: mascotFloat 2.6s ease-in-out .6s infinite;
    }
    @keyframes mascotPopIn {
        from { opacity: 0; transform: scale(.6) translateY(20px); }
        to   { opacity: 1; transform: scale(1) translateY(0); }
    }
    @keyframes mascotFloat {
        0%, 100% { transform: translateY(0); }
        50%      { transform: translateY(-8px); }
    }

    .wave-hand {
        position: absolute;
        top: 8%;
        right: -4px;
        font-size: 2rem;
        line-height: 1;
        transform-origin: 70% 70%;
        opacity: 0;
        animation: waveIn .3s ease .9s both, waveHand 1s ease-in-out 1.2s 3;
    }
    @keyframes waveIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes waveHand {
        0%, 100% { transform: rotate(0deg); }
        25%      { transform: rotate(20deg); }
        50%      { transform: rotate(-12deg); }
        75%      { transform: rotate(16deg); }
    }

    .greet-bubble {
        position: absolute;
        top: -14px;
        left: 50%;
        transform: translateX(-50%) scale(.7);
        background: var(--krem);
        color: var(--hijau);
        font-weight: 700;
        font-size: .8rem;
        padding: .3rem .75rem;
        border-radius: 999px;
        white-space: nowrap;
        opacity: 0;
        box-shadow: 0 6px 14px rgba(0,0,0,.25);
        animation: bubbleIn .35s ease 1s both;
    }
    .greet-bubble::after {
        content: '';
        position: absolute;
        bottom: -5px;
        left: 50%;
        transform: translateX(-50%) rotate(45deg);
        width: 10px; height: 10px;
        background: var(--krem);
    }
    @keyframes bubbleIn {
        from { opacity: 0; transform: translateX(-50%) scale(.7) translateY(6px); }
        to   { opacity: 1; transform: translateX(-50%) scale(1) translateY(0); }
    }

    /* Teks, mengikuti gaya landing page */
    .splash-eyebrow {
        font-size: 1rem;
        font-weight: 600;
        color: var(--emas);
        margin: 0 0 .5rem;
        animation: fadeInUp 1s ease both;
    }
    .splash-title {
        font-size: clamp(1.6rem, 5vw, 2.4rem);
        font-weight: 800;
        line-height: 1.15;
        letter-spacing: -.02em;
        margin: 0;
        animation: fadeInUp 1s ease .15s both;
    }
    .splash-title span { opacity: .85; }
    .splash-divider {
        width: 4rem;
        height: 2px;
        background: var(--krem);
        opacity: .6;
        border-radius: 999px;
        margin: 1.25rem auto;
    }
    .splash-tagline {
        font-size: 1rem;
        color: #cdd8cc;
        font-style: italic;
        margin: 0;
        animation: fadeInUp 1s ease .3s both;
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(15px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* Loading dots */
    .splash-dots { margin-top: 2rem; display: flex; justify-content: center; gap: .5rem; }
    .dot {
        width: 8px; height: 8px; border-radius: 50%;
        background: var(--krem);
        opacity: .5;
        animation: bounce 1.2s infinite ease-in-out;
    }
    .dot:nth-child(2) { animation-delay: .2s; }
    .dot:nth-child(3) { animation-delay: .4s; }
    @keyframes bounce {
        0%, 80%, 100% { transform: scale(.6); opacity: .4; }
        40% { transform: scale(1); opacity: 1; }
    }

    @media (prefers-reduced-motion: reduce) {
        .leaf, .mascot-wrap, .mascot-img, .wave-hand, .greet-bubble,
        .splash-eyebrow, .splash-title, .splash-tagline, .dot {
            animation: none !important;
        }
    }
</style>

<script>
    (function () {
        const splash = document.getElementById('splash-screen');
        if (!splash) return;

        // Hanya tampil sekali per sesi browser (hapus blok ini jika ingin tampil setiap kali)
        const alreadyShown = sessionStorage.getItem('splashShown');
        if (alreadyShown) {
            splash.remove();
            return;
        }

        window.addEventListener('load', function () {
            setTimeout(function () {
                splash.classList.add('splash-hide');
                sessionStorage.setItem('splashShown', 'true');
                setTimeout(function () {
                    splash.remove();
                }, 700);
            }, 2600);
        });
    })();
</script>