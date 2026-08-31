@props(['stats' => []])

<section class="relative min-h-[85vh] lg:min-h-[95vh] flex items-center bg-dark-green overflow-hidden">
    <!-- Background image -->
    <div class="absolute inset-0">
        <img src="{{ asset('images/hero-perkebunan.jpg') }}"
             alt="Perkebunan kelapa sawit PT Sahabat Sawit di pagi hari, Rokan Hilir, Riau"
             class="w-full h-full object-cover"
             onerror="this.style.display='none'">
        <div class="absolute inset-0 bg-gradient-to-b from-dark-green/80 via-dark-green/60 to-dark-green/90"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-6 lg:px-8 py-24 text-center lg:text-left">
        <div class="max-w-2xl mx-auto lg:mx-0">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-gold/15 border border-gold/40 text-gold font-heading text-xs md:text-sm font-semibold tracking-wide uppercase">
                🌴 Perkebunan Kelapa Sawit Berkelanjutan
            </span>

            <h1 class="mt-6 font-heading font-extrabold text-3xl md:text-5xl lg:text-6xl leading-tight text-white">
                Bertumbuh Bersama, <span class="text-light-green">Berkelanjutan</span> untuk Masa Depan
            </h1>

            <p class="mt-6 text-base md:text-lg text-white/80 leading-relaxed">
                PT Sahabat Sawit berkomitmen untuk mengembangkan industri kelapa sawit yang produktif, bertanggung jawab, dan berkelanjutan bagi masyarakat serta lingkungan di Rokan Hilir, Riau.
            </p>

            <div class="mt-8 flex flex-col sm:flex-row items-center lg:items-start justify-center lg:justify-start gap-4">
                <a href="{{ route('about') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3.5 rounded-brand bg-primary-green text-white font-heading font-semibold hover:bg-light-green transition-all duration-300">
                    Tentang Kami
                </a>
                <a href="{{ route('contact') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3.5 rounded-brand border-2 border-white text-white font-heading font-semibold hover:bg-white hover:text-dark-green transition-all duration-300">
                    Hubungi Kami
                </a>
            </div>
        </div>

        <!-- Statistik singkat -->
        @if(count($stats))
        <div data-counter-section class="mt-16 grid grid-cols-2 md:grid-cols-4 gap-6 max-w-4xl mx-auto lg:mx-0">
            @foreach($stats as $stat)
            <div class="bg-white/10 backdrop-blur-sm rounded-brand p-5 text-center lg:text-left border border-white/10">
                <p data-counter="{{ $stat['value'] }}" class="font-heading font-bold text-2xl md:text-3xl text-gold">{{ $stat['value'] }}</p>
                <p class="mt-1 text-xs md:text-sm text-white/70">{{ $stat['label'] }}</p>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>
