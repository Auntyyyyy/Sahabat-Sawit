<header id="main-navbar" class="site-navbar fixed top-0 left-0 w-full z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="navbar-shell flex items-center justify-between h-20 navbar-row">
            <span class="navbar-shell-bg" aria-hidden="true"></span>

            {{-- Logo + nama perusahaan: klik menuju Landing Page (tab yang sama) --}}
            <a href="{{ route('landing') }}" class="navbar-brand flex items-center gap-3">
                <img src="{{ asset('images/logo (2).png') }}" alt="PT. Sahabat Sawit Rokan Sejahtera" class="navbar-logo">
                <span class="navbar-brand-text flex flex-col leading-tight">
                    <span class="brand-title font-heading font-bold text-base md:text-lg">Sahabat Sawit</span>
                    <span class="brand-subtitle font-heading text-[11px] md:text-xs tracking-wide -mt-0.5">Rokan Sejahtera</span>
                </span>
            </a>

            <nav class="hidden lg:flex items-center gap-6 xl:gap-8 font-heading font-medium text-sm">
                <a href="{{ route('home') }}" class="nav-link transition-colors duration-300 {{ request()->routeIs('home') ? 'nav-link-active font-semibold' : '' }}">{{ __('messages.nav_beranda') }}</a>

                <!-- Menu Tentang Kami (Desktop) — dropdown: Profil Perusahaan, Profil Manajemen, Nilai Perusahaan, Sertifikasi & Penghargaan -->
                <div class="relative">
                    <button id="about-menu-btn" type="button"
                            class="about-menu-btn nav-link flex items-center gap-1 transition-colors duration-300 {{ request()->routeIs('about') || request()->routeIs('about.*') ? 'nav-link-active font-semibold' : '' }}"
                            aria-haspopup="true" aria-expanded="false">
                        <span>{{ __('messages.nav_tentang') }}</span>
                        <svg class="w-3.5 h-3.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="about-menu-dropdown"
                         class="hidden absolute left-0 mt-2 w-64 bg-white rounded-brand shadow-lg border border-gray-100 overflow-hidden z-50">
                        <a href="{{ route('about') }}" class="block px-4 py-3 text-sm hover:bg-cream {{ request()->routeIs('about') ? 'text-primary-green font-semibold' : 'text-dark-text' }}">
                            Profil Perusahaan
                        </a>
                        <a href="{{ route('about.struktur') }}" class="block px-4 py-3 text-sm hover:bg-cream border-t border-gray-100 {{ request()->routeIs('about.struktur') ? 'text-primary-green font-semibold' : 'text-dark-text' }}">
                            Profil Manajemen
                        </a>
                        <a href="{{ route('about.nilai') }}" class="block px-4 py-3 text-sm hover:bg-cream border-t border-gray-100 {{ request()->routeIs('about.nilai') ? 'text-primary-green font-semibold' : 'text-dark-text' }}">
                            Nilai Perusahaan
                        </a>
                        <a href="{{ route('about.sertifikasi') }}" class="block px-4 py-3 text-sm hover:bg-cream border-t border-gray-100 {{ request()->routeIs('about.sertifikasi') ? 'text-primary-green font-semibold' : 'text-dark-text' }}">
                            Sertifikasi & Penghargaan
                        </a>
                    </div>
                </div>

                <a href="{{ route('products') }}" class="nav-link px-2 py-1.5 rounded-md transition-colors {{ request()->routeIs('products*') ? 'nav-link-active font-semibold' : '' }}">{{ __('messages.nav_produk') }}</a>
                <a href="{{ route('sustainability') }}" class="nav-link transition-colors duration-300 {{ request()->routeIs('sustainability') ? 'nav-link-active font-semibold' : '' }}">{{ __('messages.nav_keberlanjutan') }}</a>
                <a href="{{ route('contact') }}" class="nav-link transition-colors duration-300 {{ request()->routeIs('contact') ? 'nav-link-active font-semibold' : '' }}">{{ __('messages.nav_kontak') }}</a>

                <!-- Menu Media (Desktop) — dropdown: Berita & Galeri Kegiatan, Masuk Admin -->
                <div class="relative">
                    <button id="media-menu-btn" type="button"
                            class="media-menu-btn nav-link flex items-center gap-1 transition-colors duration-300 {{ request()->routeIs('media.*') || request()->routeIs('admin.login') ? 'nav-link-active font-semibold' : '' }}"
                            aria-haspopup="true" aria-expanded="false">
                        <span>Media</span>
                        <svg class="w-3.5 h-3.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="media-menu-dropdown"
                         class="hidden absolute left-0 mt-2 w-64 bg-white rounded-brand shadow-lg border border-gray-100 overflow-hidden z-50">
                        <a href="{{ route('media.berita') }}" class="block px-4 py-3 text-sm hover:bg-cream {{ request()->routeIs('media.berita') ? 'text-primary-green font-semibold' : 'text-dark-text' }}">
                            Berita & Galeri Kegiatan
                        </a>
                        <a href="{{ route('admin.login') }}" class="block px-4 py-3 text-sm hover:bg-cream border-t border-gray-100 {{ request()->routeIs('admin.login') ? 'text-primary-green font-semibold' : 'text-dark-text' }}">
                            Masuk Admin
                        </a>
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

                <!-- Menu Tentang Kami (Mobile) — accordion: 4 sub-halaman -->
                <div class="border-t border-gray-100 pt-3">
                    <button id="about-accordion-btn" type="button"
                            class="w-full flex items-center justify-between px-2 py-1.5 rounded-md hover:bg-gray-50 hover:text-primary-green transition-colors text-dark-text {{ request()->routeIs('about') || request()->routeIs('about.*') ? 'text-primary-green font-semibold bg-gray-50' : '' }}"
                            aria-expanded="false">
                        <span>{{ __('messages.nav_tentang') }}</span>
                        <svg id="about-accordion-icon" class="w-4 h-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="about-accordion-panel" class="hidden flex flex-col gap-1 mt-1 pl-4">
                        <a href="{{ route('about') }}" class="px-2 py-1.5 rounded-md hover:bg-gray-50 hover:text-primary-green transition-colors {{ request()->routeIs('about') ? 'text-primary-green font-semibold bg-gray-50' : 'text-dark-text' }}">
                            Profil Perusahaan
                        </a>
                        <a href="{{ route('about.struktur') }}" class="px-2 py-1.5 rounded-md hover:bg-gray-50 hover:text-primary-green transition-colors {{ request()->routeIs('about.struktur') ? 'text-primary-green font-semibold bg-gray-50' : 'text-dark-text' }}">
                            Profil Manajemen
                        </a>
                        <a href="{{ route('about.nilai') }}" class="px-2 py-1.5 rounded-md hover:bg-gray-50 hover:text-primary-green transition-colors {{ request()->routeIs('about.nilai') ? 'text-primary-green font-semibold bg-gray-50' : 'text-dark-text' }}">
                            Nilai Perusahaan
                        </a>
                        <a href="{{ route('about.sertifikasi') }}" class="px-2 py-1.5 rounded-md hover:bg-gray-50 hover:text-primary-green transition-colors {{ request()->routeIs('about.sertifikasi') ? 'text-primary-green font-semibold bg-gray-50' : 'text-dark-text' }}">
                            Sertifikasi & Penghargaan
                        </a>
                    </div>
                </div>

                <a href="{{ route('products') }}" class="px-2 py-1.5 rounded-md hover:bg-gray-50 hover:text-primary-green transition-colors text-dark-text {{ request()->routeIs('products*') ? 'text-primary-green font-semibold bg-gray-50' : '' }}">{{ __('messages.nav_produk') }}</a>
                <a href="{{ route('sustainability') }}" class="px-2 py-1.5 rounded-md hover:bg-gray-50 hover:text-primary-green transition-colors text-dark-text {{ request()->routeIs('sustainability') ? 'text-primary-green font-semibold bg-gray-50' : '' }}">{{ __('messages.nav_keberlanjutan') }}</a>
                <a href="{{ route('contact') }}" class="px-2 py-1.5 rounded-md hover:bg-gray-50 hover:text-primary-green transition-colors text-dark-text {{ request()->routeIs('contact') ? 'text-primary-green font-semibold bg-gray-50' : '' }}">{{ __('messages.nav_kontak') }}</a>

                <!-- Menu Media (Mobile) — accordion: Berita & Galeri Kegiatan, Masuk Admin -->
                <div class="border-t border-gray-100 pt-3">
                    <button id="media-accordion-btn" type="button"
                            class="w-full flex items-center justify-between px-2 py-1.5 rounded-md hover:bg-gray-50 hover:text-primary-green transition-colors text-dark-text {{ request()->routeIs('media.*') || request()->routeIs('admin.login') ? 'text-primary-green font-semibold bg-gray-50' : '' }}"
                            aria-expanded="false">
                        <span>Media</span>
                        <svg id="media-accordion-icon" class="w-4 h-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="media-accordion-panel" class="hidden flex flex-col gap-1 mt-1 pl-4">
                        <a href="{{ route('media.berita') }}" class="px-2 py-1.5 rounded-md hover:bg-gray-50 hover:text-primary-green transition-colors {{ request()->routeIs('media.berita') ? 'text-primary-green font-semibold bg-gray-50' : 'text-dark-text' }}">
                            Berita & Galeri Kegiatan
                        </a>
                        <a href="{{ route('admin.login') }}" class="px-2 py-1.5 rounded-md hover:bg-gray-50 hover:text-primary-green transition-colors {{ request()->routeIs('admin.login') ? 'text-primary-green font-semibold bg-gray-50' : 'text-dark-text' }}">
                            Masuk Admin
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
    :root{
        --nv-dark-green:#164A2E;
        --nv-primary-green:#2F6B3F;
        --nv-gold:#C9A227;
        --nv-dark-text:#1F2933;
        --nv-gray-text:#6b7280;
    }

    .site-navbar{
        padding-top: 0;
        transition: padding-top .35s ease;
    }

    .navbar-shell{
        position: relative;
        z-index: 1;
        max-width: 100%;
        margin: 0 auto;
        padding-left: 0;
        padding-right: 0;
        transition: padding .35s ease, max-width .35s ease, height .35s ease;
    }

    .navbar-shell-bg{
        position: absolute;
        inset: 0;
        z-index: -1;
        border-radius: 0;
        background: transparent;
        box-shadow: none;
        isolation: isolate;
        pointer-events: none;
        transition:
            background-color .35s ease,
            box-shadow .35s ease,
            border-radius .35s ease,
            backdrop-filter .35s ease;
    }

    .site-navbar.is-scrolled{
        padding-top: 14px;
    }
    .site-navbar.is-scrolled .navbar-shell{
        max-width: 1100px;
        height: 68px !important;
        padding-left: 24px;
        padding-right: 24px;
    }
    .site-navbar.is-scrolled .navbar-shell-bg{
        background: rgba(255,255,255,0.88);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        box-shadow: 0 12px 30px rgba(22,74,46,0.16);
        border-radius: 9999px;
    }

    .site-navbar .brand-title{ color:#fff; transition: color .3s ease; }
    .site-navbar .brand-subtitle{ color: rgba(255,255,255,0.75); transition: color .3s ease; }
    .site-navbar .nav-link{ color:#fff; }
    .site-navbar .nav-link:hover{ color: var(--nv-gold); }
    .site-navbar .nav-link-active{ color: var(--nv-gold) !important; }
    .site-navbar .lang-switch-btn{
        color:#fff;
        border-color: rgba(255,255,255,0.4);
    }
    .site-navbar .lang-switch-btn:hover{
        border-color: #fff;
    }
    .site-navbar .mobile-menu-btn{ color:#fff; }
    .site-navbar .mobile-menu-btn:hover{ background: rgba(255,255,255,0.15); }

    .site-navbar.is-scrolled .brand-title{ color: var(--nv-dark-green); }
    .site-navbar.is-scrolled .brand-subtitle{ color: var(--nv-gray-text); }
    .site-navbar.is-scrolled .nav-link{ color: var(--nv-dark-text); }
    .site-navbar.is-scrolled .nav-link:hover{ color: var(--nv-primary-green); }
    .site-navbar.is-scrolled .nav-link-active{ color: var(--nv-primary-green) !important; }
    .site-navbar.is-scrolled .lang-switch-btn{
        color: var(--nv-dark-text);
        border-color: #e5e7eb;
    }
    .site-navbar.is-scrolled .lang-switch-btn:hover{
        border-color: var(--nv-primary-green);
        color: var(--nv-primary-green);
    }
    .site-navbar.is-scrolled .mobile-menu-btn{ color: var(--nv-dark-green); }
    .site-navbar.is-scrolled .mobile-menu-btn:hover{ background: rgba(0,0,0,0.05); }

    .about-menu-btn,
    .media-menu-btn{ cursor: pointer; background: none; border: none; padding: 0; font: inherit; }
    .about-menu-btn[aria-expanded="true"] svg,
    .media-menu-btn[aria-expanded="true"] svg{ transform: rotate(180deg); }

    .mobile-menu-panel{
        background:#fff;
        border-radius: 16px;
        box-shadow: 0 12px 30px rgba(0,0,0,0.12);
        margin-top: 8px;
        padding-left: 8px;
        padding-right: 8px;
        max-height: calc(100vh - 100px);
        overflow-y: auto;
    }
    #about-accordion-btn[aria-expanded="true"] #about-accordion-icon,
    #media-accordion-btn[aria-expanded="true"] #media-accordion-icon{ transform: rotate(180deg); }

    body.navbar-force-solid .site-navbar{ padding-top: 14px; }
    body.navbar-force-solid .site-navbar .navbar-shell{
        max-width: 1100px;
        height: 68px !important;
        padding-left: 24px;
        padding-right: 24px;
    }
    body.navbar-force-solid .site-navbar .navbar-shell-bg{
        background: rgba(255,255,255,0.95);
        box-shadow: 0 6px 20px rgba(22,74,46,0.1);
        border-radius: 9999px;
    }
    body.navbar-force-solid .site-navbar .brand-title{ color: var(--nv-dark-green); }
    body.navbar-force-solid .site-navbar .brand-subtitle{ color: var(--nv-gray-text); }
    body.navbar-force-solid .site-navbar .nav-link{ color: var(--nv-dark-text); }
    body.navbar-force-solid .site-navbar .lang-switch-btn{ color: var(--nv-dark-text); border-color:#e5e7eb; }
    body.navbar-force-solid .site-navbar .mobile-menu-btn{ color: var(--nv-dark-green); }
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
        const aboutBtn = document.getElementById('about-menu-btn');
        const aboutDropdown = document.getElementById('about-menu-dropdown');
        const mediaBtn = document.getElementById('media-menu-btn');
        const mediaDropdown = document.getElementById('media-menu-dropdown');

        function closeAllDropdowns() {
            if (langDropdown) { langDropdown.classList.add('hidden'); langBtn?.setAttribute('aria-expanded', false); }
            if (aboutDropdown) { aboutDropdown.classList.add('hidden'); aboutBtn?.setAttribute('aria-expanded', false); }
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

        if (aboutBtn && aboutDropdown) {
            aboutBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                const isHidden = aboutDropdown.classList.contains('hidden');
                closeAllDropdowns();
                aboutDropdown.classList.toggle('hidden', !isHidden);
                aboutBtn.setAttribute('aria-expanded', isHidden);
            });
        }

        if (mediaBtn && mediaDropdown) {
            mediaBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                const isHidden = mediaDropdown.classList.contains('hidden');
                closeAllDropdowns();
                mediaDropdown.classList.toggle('hidden', !isHidden);
                mediaBtn.setAttribute('aria-expanded', isHidden);
            });
        }

        document.addEventListener('click', (e) => {
            const clickedInsideLang = langDropdown && (langDropdown.contains(e.target) || e.target === langBtn);
            const clickedInsideAbout = aboutDropdown && (aboutDropdown.contains(e.target) || e.target === aboutBtn || aboutBtn?.contains(e.target));
            const clickedInsideMedia = mediaDropdown && (mediaDropdown.contains(e.target) || e.target === mediaBtn || mediaBtn?.contains(e.target));
            if (!clickedInsideLang && !clickedInsideAbout && !clickedInsideMedia) {
                closeAllDropdowns();
            }
        });

        const aboutAccBtn = document.getElementById('about-accordion-btn');
        const aboutAccPanel = document.getElementById('about-accordion-panel');
        if (aboutAccBtn && aboutAccPanel) {
            aboutAccBtn.addEventListener('click', () => {
                const isHidden = aboutAccPanel.classList.contains('hidden');
                aboutAccPanel.classList.toggle('hidden');
                aboutAccBtn.setAttribute('aria-expanded', isHidden);
            });
        }

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