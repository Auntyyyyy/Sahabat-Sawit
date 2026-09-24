@extends('layouts.app')

@section('title', 'Sertifikasi & Penghargaan — PT Sahabat Sawit')

@section('content')

<section class="relative bg-dark-green text-white pt-32 pb-10 md:pt-36 md:pb-14 overflow-hidden">
    <div class="absolute inset-0 opacity-[0.4]" style="background-image: radial-gradient(rgba(255,255,255,0.06) 1px, transparent 1px); background-size: 22px 22px;"></div>

    <div class="relative max-w-5xl mx-auto px-6 lg:px-8 text-center">
        <div class="flex items-center justify-center gap-2 text-sm text-white/60 mb-4">
            <a href="{{ url('/') }}" class="hover:text-white transition-colors">Beranda</a>
            <span>/</span>
            <a href="{{ route('about') }}" class="hover:text-white transition-colors">Tentang Kami</a>
            <span>/</span>
            <span class="text-white/90">Sertifikasi & Penghargaan</span>
        </div>

        <span class="block font-heading font-semibold text-light-green uppercase text-sm tracking-wide mb-2">Tentang Kami</span>
        <h1 class="font-heading font-bold text-2xl md:text-4xl mb-8">Sertifikasi & Penghargaan</h1>

        @include('about._subnav')
    </div>
</section>

<section class="pt-14 pb-16 lg:pt-16 lg:pb-20 bg-cream">
    <div class="max-w-6xl mx-auto px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-14">
            <span class="font-heading font-semibold text-primary-green uppercase text-sm tracking-wide">Legalitas</span>
            <h2 class="mt-3 font-heading font-bold text-2xl md:text-3xl text-dark-green">Sertifikasi</h2>
            <p class="mt-4 text-gray-text leading-relaxed">
                Komitmen kami terhadap standar mutu, keamanan, dan keberlanjutan dibuktikan melalui sertifikasi resmi berikut.
            </p>
        </div>

        {{-- Kartu sertifikat: logo ditampilkan utuh (object-contain, bukan
             cover) supaya tidak terpotong seperti sebelumnya, dengan padding
             di sekitarnya + judul dan deskripsi singkat di bawahnya --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-7">
            @foreach($sertifikasi as $item)
            <div class="group bg-white rounded-brand border border-dark-green/10 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">

                <div class="relative aspect-square bg-cream flex items-center justify-center p-6 md:p-7 overflow-hidden">
                    <img src="{{ asset('images/' . $item['photo']) }}"
                        alt="{{ $item['nama'] }}"
                        class="max-w-full max-h-full object-contain cursor-zoom-in transition-transform duration-500 group-hover:scale-105"
                        onclick="openCertLightbox(this.src)"
                        onerror="this.src='https://placehold.co/600x600/F5F1E8/1F5F3B?text={{ urlencode($item['nama']) }}'">

                    @if(!empty($item['tahun']))
                    <span class="absolute top-3 right-3 inline-flex items-center px-3 py-1 rounded-full bg-secondary-green text-white text-xs font-heading font-bold shadow-md">
                        {{ $item['tahun'] }}
                    </span>
                    @endif
                </div>

                <div class="px-4 py-4 border-t border-dark-green/5">
                    <p class="font-heading font-bold text-dark-green text-base leading-snug">{{ $item['nama'] }}</p>
                    @if(!empty($item['deskripsi']))
                    <p class="mt-1 text-sm text-gray-text leading-snug">{{ $item['deskripsi'] }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <!-- Lightbox modal -->
        <div id="certLightbox" class="cert-lightbox" onclick="closeCertLightbox()">
            <span class="cert-lightbox-close" onclick="closeCertLightbox()">&times;</span>
            <img id="certLightboxImg" src="" alt="Sertifikat diperbesar">
        </div>
    </div>
</section>

{{-- ============================= --}}
{{-- PENGHARGAAN --}}
{{-- ============================= --}}
<section class="pt-8 pb-16 lg:pt-10 lg:pb-20 bg-cream">
    <div class="max-w-6xl mx-auto px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-14">
            <span class="font-heading font-semibold text-primary-green uppercase text-sm tracking-wide">Apresiasi</span>
            <h2 class="mt-3 font-heading font-bold text-2xl md:text-3xl text-dark-green">Penghargaan</h2>
            <p class="mt-4 text-gray-text leading-relaxed">
                Berbagai apresiasi yang kami terima atas kinerja, kualitas layanan, dan kontribusi perusahaan.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 md:gap-6">
            @foreach($penghargaan as $item)
            <div class="flex items-start gap-4 bg-white rounded-brand border border-dark-green/10 shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 p-5 md:p-6">
                <div class="shrink-0 w-14 h-14 rounded-full bg-gold/15 text-gold flex items-center justify-center text-2xl">
                    <i class="bi bi-award-fill"></i>
                </div>
                <div>
                    @if(!empty($item['tahun']))
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-gold/15 text-gold text-xs font-heading font-bold mb-1.5">
                        {{ $item['tahun'] }}
                    </span>
                    @endif
                    <p class="font-heading font-bold text-dark-green text-base leading-snug">{{ $item['nama'] }}</p>
                    @if(!empty($item['deskripsi']))
                    <p class="mt-1 text-sm text-gray-text leading-snug">{{ $item['deskripsi'] }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    function openCertLightbox(src) {
        const lightbox = document.getElementById('certLightbox');
        const img = document.getElementById('certLightboxImg');
        img.src = src;
        lightbox.classList.add('active');
        document.body.style.overflow = 'hidden'; // cegah scroll di belakang
    }

    function closeCertLightbox() {
        document.getElementById('certLightbox').classList.remove('active');
        document.body.style.overflow = '';
    }

    // Tutup dengan tombol Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeCertLightbox();
    });
</script>
@endpush