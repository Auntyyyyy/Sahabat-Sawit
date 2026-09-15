@extends('layouts.app')

@section('title', 'Keberlanjutan — PT Sahabat Sawit')

@section('content')
<section class="bg-dark-green text-white py-16 lg:py-20">
    <div class="max-w-5xl mx-auto px-6 lg:px-8 text-center">
        <span class="font-heading font-semibold text-light-green uppercase text-sm tracking-wide">Keberlanjutan</span>
        <h1 class="mt-3 font-heading font-bold text-3xl md:text-5xl">Komitmen untuk Keberlanjutan</h1>
        <p class="mt-4 text-white/75 max-w-2xl mx-auto leading-relaxed">
            Menjaga keseimbangan antara produktivitas, lingkungan, dan kesejahteraan masyarakat.
        </p>
    </div>
</section>

<section class="py-16 lg:py-20 bg-cream">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($pillars as $pillar)
        <div class="bg-white rounded-brand p-7 shadow-md hover:shadow-lg transition-all duration-300">
            <div class="w-12 h-12 rounded-full bg-light-green/20 flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-palm-leaf" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
            </div>
            <h3 class="font-heading font-semibold text-lg text-dark-green mb-2">{{ $pillar['title'] }}</h3>
            <p class="text-sm text-gray-text leading-relaxed">{{ $pillar['desc'] }}</p>
        </div>
        @endforeach
    </div>
</section>

<section data-counter-section class="py-16 lg:py-20 bg-white">
    <div class="max-w-4xl mx-auto px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($statistics as $stat)
        <div class="{{ $loop->first ? 'bg-primary-green' : 'bg-dark-green' }} text-white rounded-brand p-10 text-center">
            <p data-counter="{{ $stat['value'] }}" class="font-heading font-extrabold text-5xl text-gold">{{ $stat['value'] }}</p>
            <p class="mt-3 text-white/80">{{ $stat['label'] }}</p>
        </div>
        @endforeach
    </div>
    <p class="text-center mt-6 text-xs text-gray-text">*Angka statistik dapat diperbarui melalui data perusahaan.</p>
</section>

<section class="pt-16 pb-20 lg:pt-20 lg:pb-24 bg-cream">
    <div class="max-w-6xl mx-auto px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto">
            <span class="font-heading font-semibold text-secondary-green uppercase text-sm tracking-wide">Program CSR</span>
            <h2 class="mt-3 font-heading font-bold text-2xl md:text-4xl text-dark-green">
                Kontribusi Kami untuk Masyarakat
            </h2>
            <p class="mt-4 text-gray-text leading-relaxed">
                Lima pilar utama program tanggung jawab sosial perusahaan yang kami jalankan secara berkelanjutan.
            </p>
        </div>

        <!-- Kartu Kategori CSR -->
        <div class="mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4" id="csr-tabs">
            @foreach($csrCategories as $index => $category)
            <button type="button"
                    class="csr-tab-btn {{ $index === 0 ? 'active' : '' }}"
                    data-target="csr-panel-{{ $index }}"
                    onclick="switchCsrTab({{ $index }})">
                <span class="csr-tab-icon">{{ $category['icon'] }}</span>
                <span class="csr-tab-title">{{ $category['title'] }}</span>
            </button>
            @endforeach
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
                        <h3 class="font-heading font-bold text-xl text-dark-green">{{ $category['title'] }}</h3>
                        <p class="mt-2 text-gray-text leading-relaxed">{{ $category['desc'] }}</p>
                    </div>

                    <div class="csr-gallery csr-gallery-{{ count($category['activities']) }}">
                        @foreach($category['activities'] as $activity)
                        <div class="csr-timeline-card">
                            <img src="{{ asset('images/csr/' . $activity['image']) }}" alt="{{ $activity['title'] }}"
                                 class="csr-timeline-img"
                                 onerror="this.onerror=null;this.src='https://placehold.co/500x300/164A2E/F5F1E8?text=Foto+Kegiatan'">
                            <div class="csr-timeline-body">
                                <div class="csr-timeline-meta">
                                    <span class="csr-meta-date">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        {{ $activity['date'] }}
                                    </span>
                                    <span class="csr-meta-location">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        {{ $activity['location'] }}
                                    </span>
                                </div>
                                <h4 class="csr-timeline-title">{{ $activity['title'] }}</h4>
                                <p class="csr-timeline-story">{{ $activity['story'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>

            <button type="button" class="csr-nav-arrow csr-nav-next" onclick="csrNavCategory(1)" aria-label="Program CSR Selanjutnya">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>
    </div>
</section>

<script>
const csrTotalCategories = {{ count($csrCategories) }};
let csrCurrentCategory = 0;

function switchCsrTab(index) {
    document.querySelectorAll('.csr-tab-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.csr-panel').forEach(panel => panel.classList.remove('active'));

    document.querySelectorAll('.csr-tab-btn')[index].classList.add('active');
    document.getElementById(`csr-panel-${index}`).classList.add('active');

    csrCurrentCategory = index;
}

function csrNavCategory(direction) {
    let next = csrCurrentCategory + direction;
    if (next < 0) next = csrTotalCategories - 1;
    if (next >= csrTotalCategories) next = 0;

    switchCsrTab(next);
}
</script>
@endsection