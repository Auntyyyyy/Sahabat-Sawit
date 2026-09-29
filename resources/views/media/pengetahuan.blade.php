@extends('layouts.app')

@section('title', 'Pengetahuan Kelapa Sawit — PT Sahabat Sawit')

@section('content')
<section class="relative min-h-[40vh] flex items-center bg-dark-green text-white">
    <div class="absolute inset-0 hero-overlay"></div>

    <div class="relative w-full max-w-5xl mx-auto px-6 lg:px-8 text-center py-16">
        <div class="flex items-center justify-center gap-2 text-sm text-white/60 mb-4">
            <a href="{{ url('/') }}" class="hover:text-white transition-colors">Beranda</a>
            <span>/</span>
            <span class="text-white/90">Pengetahuan</span>
        </div>

        <span class="block font-heading font-semibold text-light-green uppercase text-sm tracking-wide">Media</span>
        <h1 class="mt-3 font-heading font-bold text-3xl md:text-5xl">SahabatPedia</h1>
        <p class="mt-4 text-white/75 max-w-2xl mx-auto leading-relaxed">
Ruang informasi yang menghadirkan pengetahuan, fakta menarik, berita, dan wawasan seputar kelapa sawit.        </p>
    </div>
</section>

<section class="py-16 lg:py-20 bg-white">
    <div class="max-w-6xl mx-auto px-6 lg:px-8">

        @if($pengetahuans->isEmpty())
            <div class="text-center py-16">
                <i class="bi bi-lightbulb fs-1 text-muted d-block mb-3"></i>
                <p class="text-gray-text">Belum ada konten pengetahuan yang dipublikasikan.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($pengetahuans as $item)
                <a href="{{ route('media.pengetahuan.show', $item->slug) }}" class="pengetahuan-card group block rounded-brand overflow-hidden bg-white border border-dark-green/10 shadow-sm hover:shadow-xl transition-shadow duration-300">
                    <div class="aspect-[4/3] overflow-hidden bg-cream">
                        <img src="{{ $item->gambar ? asset('storage/' . $item->gambar) : 'https://placehold.co/600x450/2F6B3F/F5F1E8?text=' . urlencode($item->judul) }}"
                             alt="{{ $item->judul }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                             onerror="this.onerror=null;this.src='https://placehold.co/600x450/2F6B3F/F5F1E8?text={{ urlencode($item->judul) }}'">
                    </div>
                    <div class="p-5">
                        <span class="inline-flex items-center gap-1.5 text-xs font-heading font-semibold text-primary-green uppercase tracking-wide">
                            <i class="bi bi-lightbulb"></i> Tahukah Anda?
                        </span>
                        <h3 class="mt-2 font-heading font-bold text-dark-green leading-snug group-hover:text-primary-green transition-colors">
                            {{ $item->judul }}
                        </h3>
                        <p class="mt-2 text-sm text-gray-text leading-relaxed line-clamp-3">
                            {{ $item->ringkasan }}
                        </p>
                    </div>
                </a>
                @endforeach
            </div>

            <div class="mt-14">
                {{ $pengetahuans->links() }}
            </div>
        @endif

    </div>
</section>
@endsection