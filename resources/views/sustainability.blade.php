@extends('layouts.app')

@section('title', 'Keberlanjutan — PT Sahabat Sawit')

@section('content')

@php
    // GANTI ARRAY DI BAWAH INI dengan foto-foto asli kamu nanti.
    // Sekarang masih pakai 'herotentang.png' berulang sebagai placeholder.
    $heroMarqueeImages = [
        'herotentang.png', 'herotentang.png', 'herotentang.png',
        'herotentang.png', 'herotentang.png', 'herotentang.png',
        'herotentang.png', 'herotentang.png', 'herotentang.png',
    ];
    $heroMarqueeCols = array_chunk($heroMarqueeImages, ceil(count($heroMarqueeImages) / 3));
@endphp

<section class="relative min-h-screen flex items-center overflow-hidden hero-marquee-section text-white">

    <!-- Grid Foto Bergerak -->
    <div class="hero-marquee-wrap">
        <div class="hero-marquee-grid">
            @foreach($heroMarqueeCols as $colIndex => $colImages)
            <div class="hero-marquee-col {{ $colIndex % 2 === 0 ? 'marquee-up' : 'marquee-down' }}">
                {{-- Diulang 2x supaya loop-nya mulus tanpa jeda --}}
                @for ($rep = 0; $rep < 2; $rep++)
                    @foreach($colImages as $img)
                    <div class="hero-marquee-card">
                        <img src="{{ asset('images/' . $img) }}"
                             alt="Galeri PT Sahabat Sawit"
                             onerror="this.src='https://placehold.co/400x300/1F5F3B/F5F1E8?text=Foto'">
                    </div>
                    @endforeach
                @endfor
            </div>
            @endforeach
        </div>
    </div>

    <!-- Overlay -->
    <div class="absolute inset-0 hero-marquee-overlay"></div>

    <!-- Konten Teks -->
    <div class="relative w-full max-w-5xl mx-auto px-6 lg:px-8 text-center z-10">

        <!-- Icon aksen -->
        <div class="sustain-fade sustain-delay-1 flex justify-center mb-4">
            <div class="sustain-icon-badge">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c-1.5 4-4 6-4 10a4 4 0 008 0c0-4-2.5-6-4-10z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-6"/>
                </svg>
            </div>
        </div>

        <span class="sustain-fade sustain-delay-2 block font-heading font-semibold text-light-green uppercase text-sm tracking-wide">Keberlanjutan</span>
        <h1 class="sustain-fade sustain-delay-3 mt-3 font-heading font-bold text-3xl md:text-5xl">Komitmen untuk Keberlanjutan</h1>
        <p class="sustain-fade sustain-delay-4 mt-4 text-white/75 max-w-2xl mx-auto leading-relaxed">
            Menjaga keseimbangan antara produktivitas, lingkungan, dan kesejahteraan masyarakat.
        </p>

        <!-- Highlight angka singkat -->
        <div class="sustain-fade sustain-delay-5 flex flex-wrap justify-center gap-4 mt-8">
            @foreach($statistics as $stat)
            <div class="sustain-stat-chip">
                <span class="sustain-stat-value">{{ $stat['value'] }}</span>
                <span class="sustain-stat-label">{{ $stat['label'] }}</span>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================= --}}
{{-- TIGA PILAR KEBERLANJUTAN --}}
{{-- ============================= --}}
<section class="py-20 lg:py-24 bg-cream">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">

        <div class="text-center max-w-2xl mx-auto mb-14">
            <span class="font-heading font-semibold text-secondary-green uppercase text-sm tracking-widest">Fondasi Kami</span>
            <h2 class="mt-3 font-heading font-bold text-3xl md:text-4xl text-dark-green">Pilar Keberlanjutan</h2>
            <p class="mt-4 text-gray-text leading-relaxed">
                Setiap keputusan operasional kami berpijak pada tiga pilar utama ini, demi keberlanjutan
                usaha dan manfaat jangka panjang bagi lingkungan serta masyarakat sekitar.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
            @foreach($pillars as $pillar)
            <div class="group relative bg-white rounded-brand border border-dark-green/10 p-8 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 overflow-hidden">

                <span class="block font-heading font-extrabold text-3xl text-secondary-green/40 group-hover:text-secondary-green transition-colors duration-300 mb-4">
                    {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                </span>

                <h3 class="relative font-heading font-bold text-lg text-dark-green mb-2.5">{{ $pillar['title'] }}</h3>
                <p class="relative text-sm text-gray-text leading-relaxed">{{ $pillar['desc'] }}</p>

                <span class="absolute bottom-0 left-0 w-0 group-hover:w-full h-1 bg-secondary-green transition-all duration-500"></span>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================= --}}
{{-- KEBERLANJUTAN DALAM ANGKA --}}
{{-- ============================= --}}
<section data-counter-section class="relative py-20 lg:py-24 bg-dark-green overflow-hidden">
    <div class="absolute inset-0 opacity-[0.05]" style="background-image: radial-gradient(circle at 20% 20%, #ffffff, transparent 45%), radial-gradient(circle at 85% 75%, #ffffff, transparent 40%);"></div>

    <div class="relative max-w-6xl mx-auto px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-14">
            <span class="font-heading font-semibold text-light-green uppercase text-sm tracking-widest">Pencapaian Kami</span>
            <h2 class="mt-3 font-heading font-bold text-3xl md:text-4xl text-white">Keberlanjutan dalam Angka</h2>
            <p class="mt-4 text-white/70 leading-relaxed">
                Sejumlah pencapaian yang mencerminkan konsistensi kami dalam menjalankan praktik berkelanjutan.
            </p>
        </div>

        <div class="flex flex-wrap justify-center gap-4 md:gap-6">
            @foreach($statistics as $stat)
            <div class="w-[47%] sm:w-56 md:w-60 bg-white/10 border border-white/15 rounded-brand p-6 md:p-8 text-center backdrop-blur-sm hover:bg-white/15 hover:-translate-y-1 transition-all duration-300">
                <p data-counter="{{ $stat['value'] }}" class="font-heading font-extrabold text-3xl md:text-5xl text-gold">0</p>
                <p class="mt-3 text-xs md:text-base text-white/70 leading-snug">{{ $stat['label'] }}</p>
            </div>
            @endforeach
        </div>

        <p class="text-center mt-8 text-xs text-white/40">*Angka statistik dapat diperbarui melalui data perusahaan.</p>
    </div>
</section>

{{-- ============================= --}}
{{-- PROGRAM CSR (sekarang dari database, dikelola dari admin) --}}
{{-- ============================= --}}
<section class="pt-16 pb-20 lg:pt-20 lg:pb-24 bg-cream">
    <div class="max-w-6xl mx-auto px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto">
            <span class="font-heading font-semibold text-secondary-green uppercase text-sm tracking-widest">Program CSR</span>
            <h2 class="mt-3 font-heading font-bold text-2xl md:text-4xl text-dark-green">
                Kontribusi Kami untuk Masyarakat
            </h2>
            <p class="mt-4 text-gray-text leading-relaxed">
                Pilar-pilar utama program tanggung jawab sosial perusahaan yang kami jalankan secara berkelanjutan.
            </p>
        </div>

        @if($csrCategories->isNotEmpty())

        <!-- Kartu Kategori CSR -->
        <div class="mt-12 bg-white rounded-brand border border-dark-green/10 shadow-sm p-3 md:p-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3" id="csr-tabs">
                @foreach($csrCategories as $index => $category)
                <button type="button"
                        class="csr-tab-btn {{ $index === 0 ? 'active' : '' }}"
                        data-target="csr-panel-{{ $index }}"
                        onclick="switchCsrTab({{ $index }})">
                    <span class="csr-tab-icon">{{ $category->icon }}</span>
                    <span class="csr-tab-title">{{ $category->title }}</span>
                </button>
                @endforeach
            </div>
        </div>

        <!-- Deskripsi & Galeri Kegiatan per Kategori, dengan navigasi kategori di sisi kiri-kanan -->
        <div class="csr-carousel-wrap">
            <button type="button" class="csr-nav-arrow csr-nav-prev" onclick="csrNavCategory(-1)" aria-label="Program CSR Sebelumnya">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </button>

            <div class="csr-carousel-content">
                @foreach($csrCategories as $index => $category)
                <div id="csr-panel-{{ $index }}" class="csr-panel {{ $index === 0 ? 'active' : '' }}">
                    <div class="csr-panel-desc">
                        <h3 class="font-heading font-bold text-xl text-dark-green">{{ $category->title }}</h3>
                        <p class="mt-2 text-gray-text leading-relaxed">{{ $category->description }}</p>
                    </div>

                    @if($category->activities->isNotEmpty())
                    <div class="csr-gallery csr-gallery-{{ $category->activities->count() }}">
                        @foreach($category->activities as $activity)
                        <div class="csr-timeline-card">
                            <img src="{{ $activity->image ? asset('storage/' . $activity->image) : 'https://placehold.co/500x300/164A2E/F5F1E8?text=Foto+Kegiatan' }}"
                                 alt="{{ $activity->title }}"
                                 class="csr-timeline-img"
                                 onerror="this.onerror=null;this.src='https://placehold.co/500x300/164A2E/F5F1E8?text=Foto+Kegiatan'">
                            <div class="csr-timeline-body">
                                <div class="csr-timeline-meta">
                                    <span class="csr-meta-date">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        {{ $activity->date }}
                                    </span>
                                    <span class="csr-meta-location">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        {{ $activity->location }}
                                    </span>
                                </div>
                                <h4 class="csr-timeline-title">{{ $activity->title }}</h4>
                                <p class="csr-timeline-story">{{ $activity->story }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-gray-text text-sm mt-6">Belum ada kegiatan untuk kategori ini.</p>
                    @endif
                </div>
                @endforeach
            </div>

            <button type="button" class="csr-nav-arrow csr-nav-next" onclick="csrNavCategory(1)" aria-label="Program CSR Selanjutnya">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>

        <!-- Progress Dots -->
        <div class="csr-dots" id="csr-dots">
            @foreach($csrCategories as $index => $category)
            <button type="button"
                    class="csr-dot {{ $index === 0 ? 'active' : '' }}"
                    data-dot-index="{{ $index }}"
                    onclick="switchCsrTab({{ $index }})"
                    aria-label="Ke kategori {{ $category->title }}">
            </button>
            @endforeach
        </div>

        @else
        <div class="text-center py-16">
            <p class="text-gray-text">Belum ada kategori CSR yang ditambahkan.</p>
        </div>
        @endif

        <!-- CTA penutup -->
        <div class="mt-16 text-center">
            <p class="font-body text-dark-text text-base md:text-lg font-medium mb-5">
                Ingin tahu lebih jauh tentang komitmen keberlanjutan kami atau berdiskusi soal kerja sama program CSR?
            </p>
            <a href="{{ url('/kontak') }}"
               class="inline-flex items-center gap-2.5 bg-secondary-green hover:bg-dark-green text-white font-heading font-semibold text-sm md:text-base px-7 py-3.5 rounded-full shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">
                <span>Hubungi Tim Kami</span>
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<script>
const csrTotalCategories = {{ count($csrCategories) }};
let csrCurrentCategory = 0;

function switchCsrTab(index) {
    document.querySelectorAll('.csr-tab-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.csr-panel').forEach(panel => panel.classList.remove('active'));
    document.querySelectorAll('.csr-dot').forEach(dot => dot.classList.remove('active'));

    document.querySelectorAll('.csr-tab-btn')[index].classList.add('active');
    document.getElementById(`csr-panel-${index}`).classList.add('active');
    const dot = document.querySelector(`.csr-dot[data-dot-index="${index}"]`);
    if (dot) dot.classList.add('active');

    csrCurrentCategory = index;
}

function csrNavCategory(direction) {
    let next = csrCurrentCategory + direction;
    if (next < 0) next = csrTotalCategories - 1;
    if (next >= csrTotalCategories) next = 0;
    switchCsrTab(next);
}

// Animasi angka counter saat section masuk viewport
document.addEventListener('DOMContentLoaded', function () {
    const counterSection = document.querySelector('[data-counter-section]');
    if (!counterSection) return;

    const counters = counterSection.querySelectorAll('[data-counter]');
    let hasAnimated = false;

    function animateCounters() {
        counters.forEach(el => {
            const rawValue = el.getAttribute('data-counter');
            const numericMatch = rawValue.match(/[\d.,]+/);
            if (!numericMatch) return;

            const suffix = rawValue.replace(numericMatch[0], '');
            const target = parseFloat(numericMatch[0].replace(/,/g, ''));
            const duration = 1500;
            const startTime = performance.now();

            function tick(now) {
                const progress = Math.min((now - startTime) / duration, 1);
                const eased = 1 - Math.pow(1 - progress, 3);
                const current = Math.floor(eased * target);
                el.textContent = current.toLocaleString('id-ID') + suffix;

                if (progress < 1) {
                    requestAnimationFrame(tick);
                } else {
                    el.textContent = target.toLocaleString('id-ID') + suffix;
                }
            }
            requestAnimationFrame(tick);
        });
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !hasAnimated) {
                hasAnimated = true;
                animateCounters();
                observer.disconnect();
            }
        });
    }, { threshold: 0.4 });

    observer.observe(counterSection);
});
</script>
@endsection