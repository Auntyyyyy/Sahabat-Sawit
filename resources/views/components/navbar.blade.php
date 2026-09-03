<header id="main-navbar" class="fixed top-0 left-0 w-full bg-white z-50 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">

            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="w-8 h-8 sm:w-9 sm:h-9 text-primary-green">
                    <path fill="currentColor" d="M12 2c-3 3-5 7-5 10.5A5 5 0 0012 18a5 5 0 005-5.5C17 9 15 5 12 2z"/>
                    <path stroke="#D4A017" stroke-width="1.5" d="M12 18v4"/>
                </svg>
                <span class="font-heading font-bold text-lg md:text-xl text-dark-green leading-tight">
                    PT. SSRS
                </span>
            </a>

            <nav class="hidden lg:flex items-center gap-6 xl:gap-8 font-heading font-medium text-sm text-dark-text">
                <a href="{{ route('home') }}" class="hover:text-primary-green transition-colors duration-300 {{ request()->routeIs('home') ? 'text-primary-green font-semibold' : '' }}">Beranda</a>
                <a href="{{ route('about') }}" class="hover:text-primary-green transition-colors duration-300 {{ request()->routeIs('about') ? 'text-primary-green font-semibold' : '' }}">Tentang Kami</a>
                <a href="{{ route('products') }}" class="px-2 py-1.5 rounded-md hover:bg-gray-50 hover:text-primary-green transition-colors {{ request()->routeIs('products*') ? 'text-primary-green font-semibold bg-gray-50' : '' }}">Produk</a>
                <a href="{{ route('sustainability') }}" class="hover:text-primary-green transition-colors duration-300 {{ request()->routeIs('sustainability') ? 'text-primary-green font-semibold' : '' }}">Keberlanjutan</a>
                <a href="{{ route('contact') }}" class="hover:text-primary-green transition-colors duration-300 {{ request()->routeIs('contact') ? 'text-primary-green font-semibold' : '' }}">Kontak</a>
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
                <a href="{{ route('home') }}" class="px-2 py-1.5 rounded-md hover:bg-gray-50 hover:text-primary-green transition-colors {{ request()->routeIs('home') ? 'text-primary-green font-semibold bg-gray-50' : '' }}">Beranda</a>
                <a href="{{ route('about') }}" class="px-2 py-1.5 rounded-md hover:bg-gray-50 hover:text-primary-green transition-colors {{ request()->routeIs('about') ? 'text-primary-green font-semibold bg-gray-50' : '' }}">Tentang Kami</a>
                <a href="{{ route('products') }}" class="px-2 py-1.5 rounded-md hover:bg-gray-50 hover:text-primary-green transition-colors {{ request()->routeIs('products*') ? 'text-primary-green font-semibold bg-gray-50' : '' }}">Produk</a>
                <a href="{{ route('sustainability') }}" class="px-2 py-1.5 rounded-md hover:bg-gray-50 hover:text-primary-green transition-colors {{ request()->routeIs('sustainability') ? 'text-primary-green font-semibold bg-gray-50' : '' }}">Keberlanjutan</a>
                <a href="{{ route('contact') }}" class="px-2 py-1.5 rounded-md hover:bg-gray-50 hover:text-primary-green transition-colors {{ request()->routeIs('contact') ? 'text-primary-green font-semibold bg-gray-50' : '' }}">Kontak</a>
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
    });
</script>