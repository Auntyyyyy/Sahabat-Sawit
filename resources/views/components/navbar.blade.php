<header id="main-navbar" class="site-navbar fixed top-0 left-0 w-full z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="navbar-shell flex items-center justify-between h-20 navbar-row">
            <span class="navbar-shell-bg" aria-hidden="true"></span>

            <a href="{{ route('home') }}" class="navbar-brand flex items-center gap-3">
                <img src="{{ asset('images/logo (2).png') }}" alt="PT. Sahabat Sawit Rokan Sejahtera" class="navbar-logo">
                <span class="navbar-brand-text flex flex-col leading-tight">
                    <span class="brand-title font-heading font-bold text-base md:text-lg">Sahabat Sawit</span>
                    <span class="brand-subtitle font-heading text-[11px] md:text-xs tracking-wide -mt-0.5">Rokan Sejahtera</span>
                </span>
            </a>

            <nav class="hidden lg:flex items-center gap-6 xl:gap-8 font-heading font-medium text-sm">
                <a href="{{ route('home') }}" class="nav-link transition-colors duration-300 {{ request()->routeIs('home') ? 'nav-link-active font-semibold' : '' }}">{{ __('messages.nav_beranda') }}</a>
                <a href="{{ route('about') }}" class="nav-link transition-colors duration-300 {{ request()->routeIs('about') ? 'nav-link-active font-semibold' : '' }}">{{ __('messages.nav_tentang') }}</a>
                <a href="{{ route('products') }}" class="nav-link px-2 py-1.5 rounded-md transition-colors {{ request()->routeIs('products*') ? 'nav-link-active font-semibold' : '' }}">{{ __('messages.nav_produk') }}</a>
                <a href="{{ route('sustainability') }}" class="nav-link transition-colors duration-300 {{ request()->routeIs('sustainability') ? 'nav-link-active font-semibold' : '' }}">{{ __('messages.nav_keberlanjutan') }}</a>
                <a href="{{ route('career') }}" class="nav-link transition-colors duration-300 {{ request()->routeIs('career*') ? 'nav-link-active font-semibold' : '' }}">{{ app()->getLocale() == 'en' ? 'Careers' : 'Karir' }}</a>
                <a href="{{ route('contact') }}" class="nav-link transition-colors duration-300 {{ request()->routeIs('contact') ? 'nav-link-active font-semibold' : '' }}">{{ __('messages.nav_kontak') }}</a>

                <!-- Menu Media (Desktop) — dropdown: Berita & Galeri Kegiatan, Pengetahuan -->
                <!-- Terbuka otomatis saat kursor diarahkan / disentuh, tidak perlu klik panah -->
                <div id="media-menu-wrap" class="relative">
                    <button id="media-menu-btn" type="button"
                            class="media-menu-btn nav-link flex items-center gap-1 transition-colors duration-300 {{ request()->routeIs('media.*') ? 'nav-link-active font-semibold' : '' }}"
                            aria-haspopup="true" aria-expanded="false">
                        <span>Media</span>
                        <svg class="w-3.5 h-3.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    {{-- pt-2 (bukan mt-2) supaya tidak ada celah kosong antara tombol dan menu,
                         jadi dropdown tidak menutup saat kursor bergerak ke bawah --}}
                    <div id="media-menu-dropdown" class="hidden absolute left-0 top-full pt-2 w-64 z-50">
                        <div class="bg-white rounded-brand shadow-lg border border-gray-100 overflow-hidden">
                            <a href="{{ route('media.berita') }}" class="block px-4 py-3 text-sm hover:bg-cream {{ request()->routeIs('media.berita') || request()->routeIs('media.berita.show') ? 'text-primary-green font-semibold' : 'text-dark-text' }}">
                                Berita & Galeri Kegiatan
                            </a>
                            <a href="{{ route('media.pengetahuan') }}" class="block px-4 py-3 text-sm hover:bg-cream border-t border-gray-100 {{ request()->routeIs('media.pengetahuan') || request()->routeIs('media.pengetahuan.show') ? 'text-primary-green font-semibold' : 'text-dark-text' }}">
                                Pengetahuan
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Language Switcher (Desktop) -->
                <div class="relative">
                    <button id="lang-switch-btn" type="button"
                            class="lang-switch-btn flex items-center gap-1.5 px-3 py-1.5 rounded-full border transition-colors text-sm font-heading font-medium"
                            aria-haspopup="true" aria-expanded="false">
                        <span>{{ strtoupper(app()->getLocale()) }}</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="lang-switch-dropdown"
                         class="hidden absolute right-0 mt-2 w-32 bg-white rounded-brand shadow-lg border border-gray-100 overflow-hidden z-50">
                        <a href="{{ route('lang.switch', 'id') }}" class="block px-4 py-2.5 text-sm hover:bg-cream {{ app()->getLocale() == 'id' ? 'text-primary-green font-semibold' : 'text-dark-text' }}">Indonesia</a>
                        <a href="{{ route('lang.switch', 'en') }}" class="block px-4 py-2.5 text-sm hover:bg-cream {{ app()->getLocale() == 'en' ? 'text-primary-green font-semibold' : 'text-dark-text' }}">English</a>
                        {{-- Bahasa ke-3 ditambahkan di sini setelah kode & labelnya dikonfirmasi --}}
                    </div>
                </div>
            </nav>

            <button id="mobile-menu-btn" class="mobile-menu-btn lg:hidden focus:outline-none p-2 rounded-md transition-colors" aria-label="Buka menu navigasi" aria-expanded="false">
                <svg id="menu-icon-open" class="w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg id="menu-icon-close" class="hidden w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div id="mobile-menu" class="mobile-menu-panel hidden lg:hidden py-4 font-heading font-medium text-sm">
            <div class="flex flex-col gap-3">
                <a href="{{ route('home') }}" class="px-2 py-1.5 rounded-md hover:bg-gray-50 hover:text-primary-green transition-colors text-dark-text {{ request()->routeIs('home') ? 'text-primary-green font-semibold bg-gray-50' : '' }}">{{ __('messages.nav_beranda') }}</a>
                <a href="{{ route('about') }}" class="px-2 py-1.5 rounded-md hover:bg-gray-50 hover:text-primary-green transition-colors text-dark-text {{ request()->routeIs('about') ? 'text-primary-green font-semibold bg-gray-50' : '' }}">{{ __('messages.nav_tentang') }}</a>
                <a href="{{ route('products') }}" class="px-2 py-1.5 rounded-md hover:bg-gray-50 hover:text-primary-green transition-colors text-dark-text {{ request()->routeIs('products*') ? 'text-primary-green font-semibold bg-gray-50' : '' }}">{{ __('messages.nav_produk') }}</a>
                <a href="{{ route('sustainability') }}" class="px-2 py-1.5 rounded-md hover:bg-gray-50 hover:text-primary-green transition-colors text-dark-text {{ request()->routeIs('sustainability') ? 'text-primary-green font-semibold bg-gray-50' : '' }}">{{ __('messages.nav_keberlanjutan') }}</a>
                 <a href="{{ route('career') }}" class="px-2 py-1.5 rounded-md hover:bg-gray-50 hover:text-primary-green transition-colors text-dark-text {{ request()->routeIs('career*') ? 'text-primary-green font-semibold bg-gray-50' : '' }}">{{ app()->getLocale() == 'en' ? 'Careers' : 'Karir' }}</a>
                <a href="{{ route('contact') }}" class="px-2 py-1.5 rounded-md hover:bg-gray-50 hover:text-primary-green transition-colors text-dark-text {{ request()->routeIs('contact') ? 'text-primary-green font-semibold bg-gray-50' : '' }}">{{ __('messages.nav_kontak') }}</a>

                <!-- Menu Media (Mobile) — accordion: Berita & Galeri Kegiatan, Pengetahuan -->
                <!-- Seluruh baris "Media" bisa disentuh untuk membuka/menutup submenu -->
                <div class="border-t border-gray-100 pt-3">
                    <button id="media-accordion-btn" type="button"
                            class="w-full flex items-center justify-between px-2 py-1.5 rounded-md hover:bg-gray-50 hover:text-primary-green transition-colors text-dark-text {{ request()->routeIs('media.*') ? 'text-primary-green font-semibold bg-gray-50' : '' }}"
                            aria-expanded="false">
                        <span>Media</span>
                        <svg id="media-accordion-icon" class="w-4 h-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="media-accordion-panel" class="hidden flex flex-col gap-1 mt-1 pl-4">
                        <a href="{{ route('media.berita') }}" class="px-2 py-1.5 rounded-md hover:bg-gray-50 hover:text-primary-green transition-colors {{ request()->routeIs('media.berita') || request()->routeIs('media.berita.show') ? 'text-primary-green font-semibold bg-gray-50' : 'text-dark-text' }}">
                            Berita & Galeri Kegiatan
                        </a>
                        <a href="{{ route('media.pengetahuan') }}" class="px-2 py-1.5 rounded-md hover:bg-gray-50 hover:text-primary-green transition-colors {{ request()->routeIs('media.pengetahuan') || request()->routeIs('media.pengetahuan.show') ? 'text-primary-green font-semibold bg-gray-50' : 'text-dark-text' }}">
                            Pengetahuan
                        </a>
                    </div>
                </div>

                <!-- Language Switcher (Mobile) -->
                <div class="px-2 py-1.5 border-t border-gray-100 mt-2 pt-3">
                    <p class="text-xs text-gray-text uppercase tracking-wide mb-2">{{ __('messages.nav_bahasa') }}</p>
                    <div class="flex gap-2">
                        <a href="{{ route('lang.switch', 'id') }}" class="px-3 py-1.5 rounded-full text-xs font-semibold {{ app()->getLocale() == 'id' ? 'bg-primary-green text-white' : 'bg-gray-100 text-dark-text' }}">ID</a>
                        <a href="{{ route('lang.switch', 'en') }}" class="px-3 py-1.5 rounded-full text-xs font-semibold {{ app()->getLocale() == 'en' ? 'bg-primary-green text-white' : 'bg-gray-100 text-dark-text' }}">EN</a>
                        {{-- Bahasa ke-3 di sini --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

{{-- Tidak ada spacer h-20 lagi: navbar sengaja overlay di atas hero. --}}
{{-- Untuk halaman TANPA hero gelap di bagian atas, tambahkan class ini di <body> --}}
{{-- <body class="navbar-force-solid"> — supaya navbar langsung solid & tidak transparan. --}}

<style>
    /* Navbar — gaya tegas dengan latar putih: bar putih solid + garis emas saat digulir, sudut tegas, huruf tebal.
       Hanya gaya yang diubah; komponen (HTML) dan skrip tidak berubah. */
    @import url('https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,600;12..96,800&display=swap');

    :root{
        --nv-dark-green:#164A2E;
        --nv-primary-green:#2F6B3F;
        --nv-gold:#C9A227;
        --nv-dark-text:#1F2933;
        --nv-gray-text:#6b7280;

        --nv-hijau:#16321f;
        --nv-hijau-muda:#2f5a3a;
        --nv-emas:#e3a72f;
        --nv-krem:#f2f4ec;
    }

    /* Huruf */
    .site-navbar,
    .site-navbar .font-heading,
    .site-navbar a,
    .site-navbar button{
        font-family: "Bricolage Grotesque", system-ui, -apple-system, "Segoe UI", sans-serif;
    }

    /* Warna teks bar: terang di atas hero (transparan), gelap setelah digulir / pada halaman navbar-force-solid (bar putih) */
    .site-navbar{
        --nv-txt: var(--nv-krem);
        --nv-txt-hover: var(--nv-emas);
        --nv-txt-active: var(--nv-emas);
        --nv-brand: #fff;
        --nv-brand-sub: rgba(255,255,255,.75);
        --nv-line: rgba(242,244,236,.55);
    }
    .site-navbar.is-scrolled,
    body.navbar-force-solid .site-navbar{
        --nv-txt: var(--nv-hijau);
        --nv-txt-hover: var(--nv-hijau-muda);
        --nv-txt-active: var(--nv-hijau);
        --nv-brand: var(--nv-hijau);
        --nv-brand-sub: var(--nv-gray-text);
        --nv-line: rgba(22,50,31,.35);
    }

    /* Bar: transparan di atas hero (dengan bayangan gelap tipis agar teks terbaca), putih solid + garis emas saat digulir */
    .site-navbar{
        padding-top: 0;
        background: transparent;
        border-bottom: 3px solid transparent;
        transition: background-color .3s ease, border-color .3s ease;
    }
    .site-navbar::before{
        content: '';
        position: absolute;
        left: 0; right: 0; top: 0;
        height: 8rem;
        z-index: -1;
        pointer-events: none;
        background: linear-gradient(180deg, rgba(15,36,23,.78) 0%, rgba(15,36,23,0) 100%);
        transition: opacity .3s ease;
    }
    .site-navbar.is-scrolled,
    body.navbar-force-solid .site-navbar{
        background: #fff;
        border-bottom-color: var(--nv-emas);
    }
    .site-navbar.is-scrolled::before,
    body.navbar-force-solid .site-navbar::before{
        opacity: 0;
    }

    .navbar-shell{
        position: relative;
        z-index: 1;
        max-width: 100%;
        margin: 0 auto;
        padding-left: 0;
        padding-right: 0;
        transition: height .3s ease;
    }
    .site-navbar .navbar-shell-bg{ display: none; }
    .site-navbar.is-scrolled .navbar-shell,
    body.navbar-force-solid .site-navbar .navbar-shell{
        height: 68px !important;
    }

    /* Merek */
    .site-navbar .brand-title{ color: var(--nv-brand); font-weight: 800; letter-spacing: -.01em; }
    .site-navbar .brand-subtitle{ color: var(--nv-brand-sub); font-weight: 600; letter-spacing: .04em; }

    /* Tautan menu: huruf tebal, garis emas tebal muncul saat disorot / aktif */
    .site-navbar .nav-link{
        position: relative;
        padding: .5rem 0;
        border-radius: 0;
        color: var(--nv-txt);
        font-weight: 700;
        font-size: .92rem;
    }
    .site-navbar .nav-link::after{
        content: '';
        position: absolute;
        left: 0; right: 0; bottom: -.1rem;
        height: 3px;
        background: var(--nv-emas);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform .25s ease;
    }
    .site-navbar .nav-link:hover{ color: var(--nv-txt-hover); }
    .site-navbar .nav-link-active{ color: var(--nv-txt-active) !important; }
    .site-navbar .nav-link:hover::after,
    .site-navbar .nav-link-active::after,
    .site-navbar .media-menu-btn[aria-expanded="true"]::after{
        transform: scaleX(1);
    }

    .media-menu-btn{ cursor: pointer; background: none; border: none; font: inherit; }
    .media-menu-btn[aria-expanded="true"] svg{ transform: rotate(180deg); }
    .site-navbar .media-menu-btn[aria-expanded="true"]{ color: var(--nv-txt-active); }

    /* Tombol bahasa: kotak bergaris tebal */
    .site-navbar .lang-switch-btn{
        padding: .35rem .8rem;
        border: 2px solid var(--nv-line);
        border-radius: 0;
        background: transparent;
        color: var(--nv-txt);
        font-weight: 700;
    }
    .site-navbar .lang-switch-btn:hover,
    .site-navbar .lang-switch-btn[aria-expanded="true"]{
        background: var(--nv-emas);
        border-color: var(--nv-emas);
        color: var(--nv-hijau);
    }

    /* Dropdown (Media & bahasa): panel putih, garis emas di atas, sudut tegas */
    .site-navbar #media-menu-dropdown > div,
    .site-navbar #lang-switch-dropdown{
        background: #fff;
        border: 0;
        border-top: 3px solid var(--nv-emas);
        border-radius: 0;
        box-shadow: 0 24px 48px rgba(0,0,0,.22);
    }
    .site-navbar #media-menu-dropdown a,
    .site-navbar #lang-switch-dropdown a{
        color: var(--nv-hijau);
        font-weight: 600;
        border-color: rgba(22,50,31,.1);
        transition: background-color .2s ease, box-shadow .2s ease, color .2s ease;
    }
    .site-navbar #media-menu-dropdown a:hover,
    .site-navbar #lang-switch-dropdown a:hover{
        background: var(--nv-krem);
        box-shadow: inset 3px 0 0 var(--nv-emas);
        color: var(--nv-hijau);
    }
    .site-navbar #media-menu-dropdown a.font-semibold,
    .site-navbar #lang-switch-dropdown a.font-semibold{
        color: var(--nv-hijau);
        font-weight: 800;
        box-shadow: inset 3px 0 0 var(--nv-emas);
    }

    /* Tombol menu (mobile): kotak bergaris */
    .site-navbar .mobile-menu-btn{
        color: var(--nv-txt);
        border: 2px solid var(--nv-line);
        border-radius: 0;
    }
    .site-navbar .mobile-menu-btn:hover{
        background: var(--nv-emas);
        border-color: var(--nv-emas);
        color: var(--nv-hijau);
    }

    /* Panel menu mobile: putih, garis emas, teks hijau tua tebal */
    .site-navbar .mobile-menu-panel{
        background: #fff;
        border-radius: 0;
        border-top: 3px solid var(--nv-emas);
        box-shadow: 0 24px 48px rgba(0,0,0,.22);
        margin-top: 0;
        padding-left: 1rem;
        padding-right: 1rem;
    }
    /* Setelah digulir, bar sudah putih: pemisah dengan panel cukup garis tipis */
    .site-navbar.is-scrolled .mobile-menu-panel,
    body.navbar-force-solid .site-navbar .mobile-menu-panel{
        border-top: 1px solid rgba(22,50,31,.12);
        box-shadow: none;
    }
    .site-navbar .mobile-menu-panel a:not(.rounded-full),
    .site-navbar .mobile-menu-panel #media-accordion-btn{
        padding: .65rem .75rem;
        border-radius: 0;
        color: var(--nv-hijau);
        font-weight: 700;
        background: transparent;
        transition: background-color .2s ease, box-shadow .2s ease, color .2s ease;
    }
    .site-navbar .mobile-menu-panel a:not(.rounded-full):hover,
    .site-navbar .mobile-menu-panel #media-accordion-btn:hover{
        background: var(--nv-krem);
        box-shadow: inset 3px 0 0 var(--nv-emas);
        color: var(--nv-hijau);
    }
    .site-navbar .mobile-menu-panel a.font-semibold:not(.rounded-full),
    .site-navbar .mobile-menu-panel #media-accordion-btn.font-semibold{
        background: var(--nv-krem);
        box-shadow: inset 3px 0 0 var(--nv-emas);
        color: var(--nv-hijau);
        font-weight: 800;
    }
    .site-navbar .mobile-menu-panel .border-t{ border-color: rgba(22,50,31,.12); }
    .site-navbar .mobile-menu-panel p{ color: rgba(22,50,31,.55); }
    .site-navbar .mobile-menu-panel a.rounded-full{
        border: 2px solid rgba(22,50,31,.3);
        border-radius: 0;
        background: transparent;
        color: var(--nv-hijau);
        font-weight: 700;
    }
    .site-navbar .mobile-menu-panel a.rounded-full.bg-primary-green{
        background: var(--nv-hijau);
        border-color: var(--nv-hijau);
        color: var(--nv-krem);
    }
    #media-accordion-btn[aria-expanded="true"] #media-accordion-icon{ transform: rotate(180deg); }

    /* Fokus keyboard yang jelas */
    .site-navbar a:focus-visible,
    .site-navbar button:focus-visible{
        outline: 3px solid var(--nv-emas);
        outline-offset: 3px;
    }

    @media (prefers-reduced-motion: reduce){
        .site-navbar, .site-navbar::before, .site-navbar .nav-link::after, .navbar-shell{ transition: none !important; }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        const iconOpen = document.getElementById('menu-icon-open');
        const iconClose = document.getElementById('menu-icon-close');
        const navbar = document.getElementById('main-navbar');
        const forceSolid = document.body.classList.contains('navbar-force-solid');

        btn.addEventListener('click', () => {
            const isHidden = menu.classList.contains('hidden');
            menu.classList.toggle('hidden');
            iconOpen.classList.toggle('hidden');
            iconClose.classList.toggle('hidden');
            btn.setAttribute('aria-expanded', isHidden);
        });

        const SCROLL_THRESHOLD = 40;
        function onScroll() {
            if (forceSolid) return;
            if (window.scrollY > SCROLL_THRESHOLD) {
                navbar.classList.add('is-scrolled');
            } else {
                navbar.classList.remove('is-scrolled');
            }
        }
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();

        const langBtn = document.getElementById('lang-switch-btn');
        const langDropdown = document.getElementById('lang-switch-dropdown');
        const mediaWrap = document.getElementById('media-menu-wrap');
        const mediaBtn = document.getElementById('media-menu-btn');
        const mediaDropdown = document.getElementById('media-menu-dropdown');

        function closeAllDropdowns() {
            if (langDropdown) { langDropdown.classList.add('hidden'); langBtn?.setAttribute('aria-expanded', false); }
            if (mediaDropdown) { mediaDropdown.classList.add('hidden'); mediaBtn?.setAttribute('aria-expanded', false); }
        }

        if (langBtn && langDropdown) {
            langBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                const isHidden = langDropdown.classList.contains('hidden');
                closeAllDropdowns();
                langDropdown.classList.toggle('hidden', !isHidden);
                langBtn.setAttribute('aria-expanded', isHidden);
            });
        }

        /* ===== Menu Media (Desktop): terbuka otomatis saat kursor diarahkan ===== */
        if (mediaWrap && mediaBtn && mediaDropdown) {
            // true di laptop/PC (ada mouse); false di layar sentuh seperti tablet
            const canHover = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
            let mediaCloseTimer = null;

            function openMedia() {
                clearTimeout(mediaCloseTimer);
                if (langDropdown) { langDropdown.classList.add('hidden'); langBtn?.setAttribute('aria-expanded', false); }
                mediaDropdown.classList.remove('hidden');
                mediaBtn.setAttribute('aria-expanded', true);
            }

            function closeMedia(delay = 0) {
                clearTimeout(mediaCloseTimer);
                mediaCloseTimer = setTimeout(() => {
                    mediaDropdown.classList.add('hidden');
                    mediaBtn.setAttribute('aria-expanded', false);
                }, delay);
            }

            if (canHover) {
                // Kursor masuk area "Media" -> langsung terbuka.
                // Kursor keluar -> tutup setelah jeda singkat (150 ms) supaya tidak berkedip.
                mediaWrap.addEventListener('mouseenter', openMedia);
                mediaWrap.addEventListener('mouseleave', () => closeMedia(150));
            }

            mediaBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                if (canHover) {
                    // Di perangkat dengan mouse: menu sudah terbuka lewat hover,
                    // klik tidak boleh menutupnya lagi.
                    openMedia();
                } else {
                    // Di layar sentuh (tablet): sentuh sekali untuk buka, sentuh lagi untuk tutup.
                    mediaDropdown.classList.contains('hidden') ? openMedia() : closeMedia(0);
                }
            });

            // Tombol Esc menutup menu
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') closeMedia(0);
            });
        }

        document.addEventListener('click', (e) => {
            const clickedInsideLang = langDropdown && (langDropdown.contains(e.target) || e.target === langBtn);
            const clickedInsideMedia = mediaDropdown && (mediaDropdown.contains(e.target) || e.target === mediaBtn || mediaBtn?.contains(e.target));
            if (!clickedInsideLang && !clickedInsideMedia) {
                closeAllDropdowns();
            }
        });

        const mediaAccBtn = document.getElementById('media-accordion-btn');
        const mediaAccPanel = document.getElementById('media-accordion-panel');
        if (mediaAccBtn && mediaAccPanel) {
            mediaAccBtn.addEventListener('click', () => {
                const isHidden = mediaAccPanel.classList.contains('hidden');
                mediaAccPanel.classList.toggle('hidden');
                mediaAccBtn.setAttribute('aria-expanded', isHidden);
            });
        }
    });
</script>