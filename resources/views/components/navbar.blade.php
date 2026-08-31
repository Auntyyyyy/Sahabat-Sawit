<header id="main-navbar" class="fixed top-0 left-0 w-full bg-white z-50 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">

            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="w-9 h-9 text-primary-green">
                    <path fill="currentColor" d="M12 2c-3 3-5 7-5 10.5A5 5 0 0012 18a5 5 0 005-5.5C17 9 15 5 12 2z"/>
                    <path stroke="#D4A017" stroke-width="1.5" d="M12 18v4"/>
                </svg>
                <span class="font-heading font-bold text-lg md:text-xl text-dark-green leading-tight">
                    SSRS
                </span>
            </a>

            <!-- Menu Desktop -->
            <nav class="hidden lg:flex items-center gap-8 font-heading font-medium text-sm text-dark-text">
                <a href="{{ route('home') }}" class="hover:text-primary-green transition-colors duration-300 {{ request()->routeIs('home') ? 'text-primary-green' : '' }}">Beranda</a>
                <a href="{{ route('about') }}" class="hover:text-primary-green transition-colors duration-300 {{ request()->routeIs('about') ? 'text-primary-green' : '' }}">Tentang Kami</a>
                <a href="{{ route('plantation') }}" class="hover:text-primary-green transition-colors duration-300 {{ request()->routeIs('plantation') ? 'text-primary-green' : '' }}">Perkebunan</a>
                <a href="{{ route('products') }}" class="hover:text-primary-green transition-colors duration-300 {{ request()->routeIs('products') ? 'text-primary-green' : '' }}">Produk</a>
                <a href="{{ route('sustainability') }}" class="hover:text-primary-green transition-colors duration-300 {{ request()->routeIs('sustainability') ? 'text-primary-green' : '' }}">Keberlanjutan</a>
                <a href="{{ route('news') }}" class="hover:text-primary-green transition-colors duration-300 {{ request()->routeIs('news*') ? 'text-primary-green' : '' }}">Berita</a>
                <a href="{{ route('career') }}" class="hover:text-primary-green transition-colors duration-300 {{ request()->routeIs('career') ? 'text-primary-green' : '' }}">Karier</a>
                <a href="{{ route('contact') }}" class="hover:text-primary-green transition-colors duration-300 {{ request()->routeIs('contact') ? 'text-primary-green' : '' }}">Kontak</a>
            </nav>

            <!-- CTA Button -->
            <a href="{{ route('contact') }}" class="hidden lg:inline-flex items-center justify-center px-6 py-2.5 rounded-brand bg-primary-green text-white font-heading font-semibold text-sm hover:bg-dark-green transition-all duration-300">
                Hubungi Kami
            </a>

            <!-- Mobile Hamburger -->
            <button id="mobile-menu-btn" class="lg:hidden text-dark-green focus:outline-none" aria-label="Buka menu navigasi">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden lg:hidden pb-6 font-heading font-medium text-sm">
            <div class="flex flex-col gap-4">
                <a href="{{ route('home') }}" class="hover:text-primary-green transition-colors">Beranda</a>
                <a href="{{ route('about') }}" class="hover:text-primary-green transition-colors">Tentang Kami</a>
                <a href="{{ route('plantation') }}" class="hover:text-primary-green transition-colors">Perkebunan</a>
                <a href="{{ route('products') }}" class="hover:text-primary-green transition-colors">Produk</a>
                <a href="{{ route('sustainability') }}" class="hover:text-primary-green transition-colors">Keberlanjutan</a>
                <a href="{{ route('news') }}" class="hover:text-primary-green transition-colors">Berita</a>
                <a href="{{ route('career') }}" class="hover:text-primary-green transition-colors">Karier</a>
                <a href="{{ route('contact') }}" class="hover:text-primary-green transition-colors">Kontak</a>
                <a href="{{ route('contact') }}" class="inline-flex items-center justify-center px-6 py-2.5 rounded-brand bg-primary-green text-white font-semibold text-center">
                    Hubungi Kami
                </a>
            </div>
        </div>
    </div>
</header>

<!-- Spacer agar konten tidak tertutup navbar fixed -->
<div class="h-20"></div>
