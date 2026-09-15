<header id="main-navbar" class="fixed top-0 left-0 w-full bg-white z-50 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">

            <a href="{{ route('home') }}" class="navbar-brand flex items-center gap-3">
    <img src="{{ asset('images/logo.png') }}" alt="PT. Sahabat Sawit Rokan Sejahtera" class="navbar-logo">
    <span class="navbar-brand-text flex flex-col leading-tight">
        <span class="font-heading font-bold text-base md:text-lg text-dark-green">Sahabat Sawit</span>
        <span class="font-heading text-[11px] md:text-xs text-gray-text tracking-wide -mt-0.5">Rokan Sejahtera</span>
    </span>
</a>

            <nav class="hidden lg:flex items-center gap-6 xl:gap-8 font-heading font-medium text-sm text-dark-text">
                <a href="{{ route('home') }}" class="hover:text-primary-green transition-colors duration-300 {{ request()->routeIs('home') ? 'text-primary-green font-semibold' : '' }}">{{ __('messages.nav_beranda') }}</a>
                <a href="{{ route('about') }}" class="hover:text-primary-green transition-colors duration-300 {{ request()->routeIs('about') ? 'text-primary-green font-semibold' : '' }}">{{ __('messages.nav_tentang') }}</a>
                <a href="{{ route('products') }}" class="px-2 py-1.5 rounded-md hover:bg-gray-50 hover:text-primary-green transition-colors {{ request()->routeIs('products*') ? 'text-primary-green font-semibold bg-gray-50' : '' }}">{{ __('messages.nav_produk') }}</a>
                <a href="{{ route('sustainability') }}" class="hover:text-primary-green transition-colors duration-300 {{ request()->routeIs('sustainability') ? 'text-primary-green font-semibold' : '' }}">{{ __('messages.nav_keberlanjutan') }}</a>
                <a href="{{ route('contact') }}" class="hover:text-primary-green transition-colors duration-300 {{ request()->routeIs('contact') ? 'text-primary-green font-semibold' : '' }}">{{ __('messages.nav_kontak') }}</a>

                <!-- Language Switcher (Desktop) -->
                <div class="relative">
                    <button id="lang-switch-btn" type="button"
                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-full border border-gray-200 hover:border-primary-green transition-colors text-sm font-heading font-medium"
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

                <!-- Tombol Masuk Admin (Desktop) -->
                <a href="{{ route('admin.login') }}"
                   class="flex items-center gap-1.5 px-3 py-1.5 rounded-full border border-gray-200 hover:border-primary-green hover:text-primary-green transition-colors text-sm font-heading font-medium text-dark-text"
                   title="Masuk Admin">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 12h9m0 0l-3-3m3 3l-3 3" />
                    </svg>
                    <span>Admin</span>
                </a>
            </nav>

            <button id="mobile-menu-btn" class="lg:hidden text-dark-green focus:outline-none p-2 rounded-md hover:bg-gray-100 transition-colors" aria-label="Buka menu navigasi" aria-expanded="false">
                <svg id="menu-icon-open" class="w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg id="menu-icon-close" class="hidden w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div id="mobile-menu" class="hidden lg:hidden border-t border-gray-100 py-4 font-heading font-medium text-sm">
            <div class="flex flex-col gap-3">
                <a href="{{ route('home') }}" class="px-2 py-1.5 rounded-md hover:bg-gray-50 hover:text-primary-green transition-colors {{ request()->routeIs('home') ? 'text-primary-green font-semibold bg-gray-50' : '' }}">{{ __('messages.nav_beranda') }}</a>
                <a href="{{ route('about') }}" class="px-2 py-1.5 rounded-md hover:bg-gray-50 hover:text-primary-green transition-colors {{ request()->routeIs('about') ? 'text-primary-green font-semibold bg-gray-50' : '' }}">{{ __('messages.nav_tentang') }}</a>
                <a href="{{ route('products') }}" class="px-2 py-1.5 rounded-md hover:bg-gray-50 hover:text-primary-green transition-colors {{ request()->routeIs('products*') ? 'text-primary-green font-semibold bg-gray-50' : '' }}">{{ __('messages.nav_produk') }}</a>
                <a href="{{ route('sustainability') }}" class="px-2 py-1.5 rounded-md hover:bg-gray-50 hover:text-primary-green transition-colors {{ request()->routeIs('sustainability') ? 'text-primary-green font-semibold bg-gray-50' : '' }}">{{ __('messages.nav_keberlanjutan') }}</a>
                <a href="{{ route('contact') }}" class="px-2 py-1.5 rounded-md hover:bg-gray-50 hover:text-primary-green transition-colors {{ request()->routeIs('contact') ? 'text-primary-green font-semibold bg-gray-50' : '' }}">{{ __('messages.nav_kontak') }}</a>

                <!-- Language Switcher (Mobile) -->
                <div class="px-2 py-1.5 border-t border-gray-100 mt-2 pt-3">
                    <p class="text-xs text-gray-text uppercase tracking-wide mb-2">{{ __('messages.nav_bahasa') }}</p>
                    <div class="flex gap-2">
                        <a href="{{ route('lang.switch', 'id') }}" class="px-3 py-1.5 rounded-full text-xs font-semibold {{ app()->getLocale() == 'id' ? 'bg-primary-green text-white' : 'bg-gray-100 text-dark-text' }}">ID</a>
                        <a href="{{ route('lang.switch', 'en') }}" class="px-3 py-1.5 rounded-full text-xs font-semibold {{ app()->getLocale() == 'en' ? 'bg-primary-green text-white' : 'bg-gray-100 text-dark-text' }}">EN</a>
                        {{-- Bahasa ke-3 di sini --}}
                    </div>
                </div>

                <!-- Tombol Masuk Admin (Mobile) -->
                <a href="{{ route('admin.login') }}" class="flex items-center gap-2 px-2 py-1.5 rounded-md hover:bg-gray-50 hover:text-primary-green transition-colors border-t border-gray-100 mt-2 pt-3">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 12h9m0 0l-3-3m3 3l-3 3" />
                    </svg>
                    <span>Masuk Admin</span>
                </a>
            </div>
        </div>
    </div>
</header>

<div class="h-20"></div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        const iconOpen = document.getElementById('menu-icon-open');
        const iconClose = document.getElementById('menu-icon-close');
        const navbar = document.getElementById('main-navbar');

        // Toggle Mobile Menu
        btn.addEventListener('click', () => {
            const isHidden = menu.classList.contains('hidden');
            menu.classList.toggle('hidden');
            iconOpen.classList.toggle('hidden');
            iconClose.classList.toggle('hidden');
            btn.setAttribute('aria-expanded', isHidden);
        });

        // Efek Bayangan saat Halaman Di-scroll
        window.addEventListener('scroll', () => {
            if (window.scrollY > 10) {
                navbar.classList.add('shadow-md');
            } else {
                navbar.classList.remove('shadow-md');
            }
        });

        // Toggle Language Dropdown (Desktop)
        const langBtn = document.getElementById('lang-switch-btn');
        const langDropdown = document.getElementById('lang-switch-dropdown');
        if (langBtn && langDropdown) {
            langBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                const isHidden = langDropdown.classList.contains('hidden');
                langDropdown.classList.toggle('hidden');
                langBtn.setAttribute('aria-expanded', isHidden);
            });
            // Tutup dropdown saat klik di luar area dropdown
            document.addEventListener('click', (e) => {
                if (!langDropdown.classList.contains('hidden') && !langDropdown.contains(e.target) && e.target !== langBtn) {
                    langDropdown.classList.add('hidden');
                    langBtn.setAttribute('aria-expanded', false);
                }
            });
        }
    });
</script>