@props(['stats' => []])

<section class="relative min-h-[85vh] lg:min-h-[95vh] flex items-center bg-dark-green overflow-hidden">
    <!-- Background Image & Overlay -->
    <div class="absolute inset-0">
        <img src="{{ asset('images/hero-perkebunan.jpg') }}"
             alt="Perkebunan kelapa sawit PT Sahabat Sawit di pagi hari, Rokan Hilir, Riau"
             class="w-full h-full object-cover"
             onerror="this.style.opacity='0'">
        <div class="absolute inset-0 bg-gradient-to-b from-dark-green/90 via-dark-green/70 to-dark-green/95 lg:bg-gradient-to-r lg:from-dark-green/95 lg:via-dark-green/75 lg:to-transparent"></div>
    </div>

    <!-- Main Content Container -->
    <div class="relative w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20 lg:py-28 text-center lg:text-left">
        <div class="max-w-2xl mx-auto lg:mx-0">
            <!-- Badge -->
            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-gold/15 border border-gold/40 text-gold font-heading text-xs sm:text-sm font-semibold tracking-wide uppercase">
                PT. SAHABAT SAWIT ROKAN SEJAHTERA
            </span>

            <!-- Heading -->
            <h1 class="mt-4 sm:mt-6 font-heading font-extrabold text-3xl sm:text-5xl lg:text-6xl leading-tight text-white tracking-tight">
                Bertumbuh Bersama, <span class="text-light-green block sm:inline">Berkelanjutan</span> untuk Masa Depan
            </h1>

            <!-- Subtitle -->
            <p class="mt-4 sm:mt-6 text-sm sm:text-base md:text-lg text-white/80 leading-relaxed max-w-xl mx-auto lg:mx-0">
                PT Sahabat Sawit Rokan Sejahtera berkomitmen untuk mengembangkan industri kelapa sawit yang produktif, bertanggung jawab, dan berkelanjutan bagi masyarakat serta lingkungan di Rokan Hilir, Riau.
            </p>

            <!-- Call to Action Buttons -->
            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-3.5 sm:gap-4">
                <a href="{{ route('about') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3.5 rounded-brand bg-primary-green text-white font-heading font-semibold hover:bg-light-green hover:shadow-lg transition-all duration-300">
                    Tentang Kami
                </a>
                <a href="{{ route('contact') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3.5 rounded-brand border-2 border-white/80 text-white font-heading font-semibold hover:bg-white hover:text-dark-green hover:border-white transition-all duration-300">
                    Hubungi Kami
                </a>
            </div>
        </div>

        <!-- Section Statistik -->
        @if(count($stats))
        <div data-counter-section class="mt-12 sm:mt-16 grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6 max-w-4xl mx-auto lg:mx-0">
            @foreach($stats as $stat)
            <div class="bg-white/10 backdrop-blur-md rounded-brand p-4 sm:p-5 text-center lg:text-left border border-white/10 hover:border-white/20 transition-all duration-300">
                <p data-counter="{{ $stat['value'] }}" class="font-heading font-bold text-2xl sm:text-3xl md:text-4xl text-gold">
                    {{ $stat['value'] }}
                </p>
                <p class="mt-1 text-xs sm:text-sm text-white/70 font-medium">
                    {{ $stat['label'] }}
                </p>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>