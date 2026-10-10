@extends('layouts.app')

@section('title', $berita->judul . ' — PT Sahabat Sawit')

@section('content')
<section class="relative bg-dark-green text-white overflow-hidden hero-detail-fullscreen">

    {{-- BARU: background hero mengikuti foto utama berita ini, jadi tiap berita
         punya tampilan hero sendiri. Lapisan hijau gelap di atasnya menjaga teks
         tetap terbaca. Kalau berita tidak punya foto, tampil polos seperti sebelumnya. --}}
    @if($berita->gambar)
        <div class="absolute inset-0">
            <img src="{{ asset('storage/' . $berita->gambar) }}"
                 alt=""
                 class="w-full h-full object-cover"
                 onerror="this.style.display='none'">
        </div>
        <div class="absolute inset-0 bg-gradient-to-b from-dark-green/85 via-dark-green/70 to-dark-green/90"></div>
    @else
        <div class="absolute inset-0 pointer-events-none opacity-[0.4]" style="background-image: radial-gradient(rgba(255,255,255,0.06) 1px, transparent 1px); background-size: 22px 22px;"></div>
        <div class="absolute inset-0 bg-dark-green/70"></div>
    @endif

    <div class="relative max-w-4xl mx-auto px-6 lg:px-8 w-full">
        <nav aria-label="Breadcrumb" class="flex mb-5">
            <div class="breadcrumb-pill">
                <a href="{{ route('home') }}" class="breadcrumb-link" aria-label="Home">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 9.75L12 3l9 6.75V21a.75.75 0 01-.75.75H15a.75.75 0 01-.75-.75v-4.5a.75.75 0 00-.75-.75h-3a.75.75 0 00-.75.75V21a.75.75 0 01-.75.75H3.75A.75.75 0 013 21V9.75z"/>
                    </svg>
                </a>
                <svg class="breadcrumb-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <a href="{{ route('media.berita') }}" class="breadcrumb-link-text">Berita</a>
                <svg class="breadcrumb-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="breadcrumb-current text-light-green">{{ $berita->judul }}</span>
            </div>
        </nav>

        <div style="display:flex;flex-wrap:wrap;align-items:center;gap:.25rem 1rem;">
            <span class="font-heading font-semibold text-light-green uppercase text-sm tracking-wide">
                {{ $berita->tanggal->translatedFormat('d F Y') }}
            </span>
            {{-- BARU: jumlah berita dilihat --}}
            <span class="inline-flex items-center gap-1.5 text-sm text-white/70">
                <i class="bi bi-eye"></i> {{ number_format($berita->views ?? 0, 0, ',', '.') }}x dilihat
            </span>
        </div>
        <h1 class="mt-2 font-heading font-bold text-3xl md:text-4xl">{{ $berita->judul }}</h1>
    </div>
</section>

<section class="py-14 md:py-16 bg-white">
    <div class="max-w-4xl mx-auto px-6 lg:px-8">

        <img src="{{ $berita->gambar ? asset('storage/' . $berita->gambar) : 'https://placehold.co/900x550/1F5F3B/F5F1E8?text=' . urlencode($berita->judul) }}"
             alt="{{ $berita->judul }}"
             class="w-full h-[320px] md:h-[420px] object-cover rounded-brand shadow-lg"
             onerror="this.onerror=null;this.src='https://placehold.co/900x550/1F5F3B/F5F1E8?text={{ urlencode($berita->judul) }}'">

        <div class="mt-8">
            <p class="text-gray-text leading-relaxed text-justify text-base md:text-lg">
                {{ $berita->deskripsi_singkat }}
            </p>

            {{-- BARU: label kecil (contoh: MoU), hanya tampil kalau diisi --}}
            @if($berita->tag)
                <span class="berita-tag">{{ $berita->tag }}</span>
            @endif
        </div>

        {{-- BARU: isi lengkap berita dari editor (sudah dibersihkan saat disimpan) --}}
        @if($berita->isi)
        <div class="berita-isi mt-8">
            {!! $berita->isi !!}
        </div>
        @endif

        {{-- BARU: tombol bagikan --}}
        @php
            $shareUrl  = route('media.berita.show', $berita->slug);
            $shareText = $berita->judul;
        @endphp
        <div class="mt-12 pt-8 border-t border-gray-100">
            <div class="berita-share">
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}"
                   target="_blank" rel="noopener" class="berita-share-btn" aria-label="Bagikan ke Facebook">
                    <i class="bi bi-facebook"></i>
                </a>
                <a href="https://twitter.com/intent/tweet?url={{ urlencode($shareUrl) }}&text={{ urlencode($shareText) }}"
                   target="_blank" rel="noopener" class="berita-share-btn" aria-label="Bagikan ke X">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                </a>
                <a href="https://wa.me/?text={{ urlencode($shareText . ' ' . $shareUrl) }}"
                   target="_blank" rel="noopener" class="berita-share-btn berita-share-btn--wa" aria-label="Bagikan ke WhatsApp">
                    <i class="bi bi-whatsapp"></i>
                </a>
                <button type="button" class="berita-share-btn" id="btn-copy-link" data-url="{{ $shareUrl }}">
                    <i class="bi bi-copy"></i>
                    <span>Copy Link</span>
                </button>
            </div>

            <a href="{{ route('media.berita') }}" class="mt-8 inline-flex items-center gap-2 text-primary-green font-heading font-semibold hover:text-dark-green transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Berita & Galeri Kegiatan
            </a>
        </div>

    </div>
</section>

{{-- BARU: Berita Lainnya (di bawah isi berita) --}}
@if($beritaLain->isNotEmpty())
<section class="py-14 md:py-16 bg-cream">
    <div class="max-w-6xl mx-auto px-6 lg:px-8">

        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <span class="font-heading font-semibold text-primary-green uppercase text-sm tracking-wide">Media</span>
                <h2 class="mt-2 font-heading font-bold text-2xl md:text-3xl text-dark-green">Berita Lainnya</h2>
            </div>
            <a href="{{ route('media.berita') }}"
               class="shrink-0 inline-flex items-center gap-2 font-heading font-semibold text-dark-green hover:text-primary-green transition-colors group">
                Lihat Semua Berita
                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>

        <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($beritaLain as $item)
            <a href="{{ route('media.berita.show', $item->slug) }}"
               class="group block bg-white rounded-brand overflow-hidden shadow-sm hover:shadow-xl transition-shadow duration-300">
                <div class="aspect-[4/3] overflow-hidden bg-cream">
                    <img src="{{ $item->gambar ? asset('storage/' . $item->gambar) : 'https://placehold.co/600x450/1F5F3B/F5F1E8?text=' . urlencode($item->judul) }}"
                         alt="{{ $item->judul }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                         onerror="this.onerror=null;this.src='https://placehold.co/600x450/1F5F3B/F5F1E8?text={{ urlencode($item->judul) }}'">
                </div>
                <div class="p-5">
                    <span class="text-xs font-heading font-semibold text-primary-green uppercase tracking-wide">
                        {{ $item->tanggal->translatedFormat('d F Y') }}
                    </span>
                    <h3 class="mt-2 font-heading font-bold text-dark-green leading-snug group-hover:text-primary-green transition-colors line-clamp-2">
                        {{ $item->judul }}
                    </h3>
                    <p class="mt-2 text-sm text-gray-text leading-relaxed line-clamp-2">
                        {{ $item->deskripsi_singkat }}
                    </p>
                </div>
            </a>
            @endforeach
        </div>

    </div>
</section>
@endif

{{-- BARU: skrip tombol Copy Link --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('btn-copy-link');
    if (!btn) return;

    const label = btn.querySelector('span');
    const original = label.textContent;

    function done() {
        label.textContent = 'Tersalin!';
        setTimeout(function () { label.textContent = original; }, 2000);
    }

    btn.addEventListener('click', function () {
        const url = btn.dataset.url;

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(url).then(done);
            return;
        }

        // Cadangan untuk halaman yang dibuka lewat http biasa
        const temp = document.createElement('textarea');
        temp.value = url;
        temp.style.position = 'fixed';
        temp.style.opacity = '0';
        document.body.appendChild(temp);
        temp.select();
        try { document.execCommand('copy'); done(); } catch (e) {}
        document.body.removeChild(temp);
    });
});
</script>
@endsection