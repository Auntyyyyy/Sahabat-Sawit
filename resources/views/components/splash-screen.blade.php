<!-- resources/views/components/splash-screen.blade.php -->
<div id="splash-screen" class="fixed inset-0 z-[9999] flex flex-col items-center justify-center overflow-hidden transition-all duration-700"
     style="background: linear-gradient(135deg, #164A2E 0%, #2F6B3F 60%, #6B8E23 100%);">

    <!-- Daun sawit dekoratif (animasi jatuh perlahan) -->
    <div class="pointer-events-none absolute inset-0 opacity-30">
        <svg class="leaf leaf-1" width="40" height="40" viewBox="0 0 24 24" fill="#F5F1E8">
            <path d="M12 2C7 6 4 11 4 15c0 4 3.5 7 8 7s8-3 8-7c0-4-3-9-8-13z"/>
        </svg>
        <svg class="leaf leaf-2" width="30" height="30" viewBox="0 0 24 24" fill="#F5F1E8">
            <path d="M12 2C7 6 4 11 4 15c0 4 3.5 7 8 7s8-3 8-7c0-4-3-9-8-13z"/>
        </svg>
        <svg class="leaf leaf-3" width="50" height="50" viewBox="0 0 24 24" fill="#F5F1E8">
            <path d="M12 2C7 6 4 11 4 15c0 4 3.5 7 8 7s8-3 8-7c0-4-3-9-8-13z"/>
        </svg>
        <svg class="leaf leaf-4" width="35" height="35" viewBox="0 0 24 24" fill="#F5F1E8">
            <path d="M12 2C7 6 4 11 4 15c0 4 3.5 7 8 7s8-3 8-7c0-4-3-9-8-13z"/>
        </svg>
    </div>

    <!-- Konten utama -->
    <div class="relative z-10 flex flex-col items-center text-center px-6 splash-content">
        <img src="{{ asset('images/logo.png') }}" alt="Logo PT. Sahabat Sawit Rokan Sejahtera"
             class="w-20 h-20 md:w-24 md:h-24 mb-5 drop-shadow-lg splash-logo" />

        <h1 class="font-['Poppins'] text-lg md:text-2xl font-semibold text-[#F5F1E8] tracking-wide">
            Selamat Datang di
        </h1>
        <h2 class="font-['Poppins'] text-xl md:text-3xl font-bold mt-1" style="color:#F5F1E8;">
            PT. Sahabat Sawit <span style="color:#F5F1E8; opacity:.85;">Rokan Sejahtera</span>
        </h2>

        <div class="mt-4 h-[2px] w-16 rounded-full" style="background:#F5F1E8; opacity:.6;"></div>

        <p class="font-['Inter'] text-sm md:text-base mt-4 text-[#F5F1E8] opacity-90 italic">
            "Tumbuh Bersama, Membangun Keberlanjutan"
        </p>

        <!-- Loading indicator -->
        <div class="mt-8 flex gap-2">
            <span class="dot"></span>
            <span class="dot"></span>
            <span class="dot"></span>
        </div>
    </div>
</div>

<style>
    /* Animasi daun jatuh */
    .leaf {
        position: absolute;
        top: -60px;
        animation: fall linear infinite;
    }
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

    /* Animasi masuk untuk logo & teks */
    .splash-logo {
        animation: fadeInDown 1s ease both;
    }
    .splash-content h1,
    .splash-content h2,
    .splash-content p {
        animation: fadeInUp 1s ease both;
    }
    .splash-content h2 { animation-delay: .15s; }
    .splash-content p  { animation-delay: .3s; }

    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-15px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(15px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* Loading dots */
    .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #F5F1E8;
        opacity: .5;
        animation: bounce 1.2s infinite ease-in-out;
    }
    .dot:nth-child(2) { animation-delay: .2s; }
    .dot:nth-child(3) { animation-delay: .4s; }

    @keyframes bounce {
        0%, 80%, 100% { transform: scale(0.6); opacity: .4; }
        40% { transform: scale(1); opacity: 1; }
    }

    /* State tersembunyi */
    #splash-screen.splash-hide {
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
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
                }, 700); // sesuai durasi transition-all
            }, 2000); // lama tampil splash: 2 detik
        });
    })();
</script>