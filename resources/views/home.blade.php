@extends('layouts.app')

@section('title', 'PT Sahabat Sawit Rokan Sejahtera')

@section('content')

    {{-- 1. HERO SECTION --}}
    <x-hero :stats="$stats" />

    {{-- 2. TENTANG KAMI --}}
    <section class="pt-16 pb-10 lg:pt-20 lg:pb-14 bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="relative">
                    <img src="{{ asset('images/tangki.png') }}"
                         alt="Aktivitas perkebunan kelapa sawit PT Sahabat Sawit"
                         class="rounded-brand shadow-xl w-full h-[420px] object-cover"
                         onerror="this.src='https://placehold.co/700x500/1F5F3B/F5F1E8?text=PT+Sahabat+Sawit'">
                    <div class="absolute -bottom-6 -right-6 hidden md:flex bg-gold text-white font-heading font-bold rounded-brand px-6 py-4 shadow-lg">
                        3+ Tahun Pengalaman
                    </div>
                </div>
                <div>
                    <span class="font-heading font-semibold text-primary-green uppercase text-sm tracking-wide">Tentang Kami</span>
                    <h2 class="mt-3 font-heading font-bold text-3xl md:text-4xl text-dark-green">PT. Sahabat Sawit Rokan Sejahtera</h2>
                    <p class="mt-2 font-heading text-lg text-palm-leaf">Berkembang Bersama Alam dan Masyarakat</p>
                    <p class="mt-6 text-gray-text leading-relaxed text-justify">
                        PT Sahabat Sawit adalah perusahaan yang bergerak di bidang perkebunan dan industri kelapa sawit, berlokasi di Kabupaten Rokan Hilir, Provinsi Riau. Kami berkomitmen mengelola perkebunan secara profesional, produktif, dan bertanggung jawab, dengan tetap menjaga kelestarian lingkungan serta memberikan manfaat nyata bagi masyarakat sekitar.
                    </p>
                    <p class="mt-4 text-gray-text leading-relaxed text-justify">
                        Dengan dukungan tim yang berpengalaman dan standar operasional yang terukur, kami terus bertumbuh menjadi mitra terpercaya dalam industri kelapa sawit di Riau.
                    </p>
                    {{-- NOTE: route('about') dibiarkan seperti semula. Jika halaman "Tentang Kami" belum dibuat,
                         ganti href="{{ route('about') }}" di bawah ini menjadi href="#" juga. --}}
                    <a href="{{ route('about') }}" class="mt-8 inline-flex items-center gap-2 px-7 py-3 rounded-brand bg-primary-green text-white font-heading font-semibold hover:bg-dark-green transition-all duration-300">
                        Selengkapnya
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- 4. KEUNGGULAN PERUSAHAAN --}}
    <section class="pt-10 pb-16 lg:pt-14 lg:pb-20 bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto">
                <span class="font-heading font-semibold text-primary-green uppercase text-sm tracking-wide">Keunggulan Kami</span>
                <h2 class="mt-3 font-heading font-bold text-3xl md:text-4xl text-dark-green">Mengapa Memilih SSRS?</h2>
            </div>

            <div class="mt-14 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
                    $icons = [
                        'leaf' => '<path stroke-linecap="round" stroke-linejoin="round" d="M6.115 5.19l.319 1.913A6 6 0 008.11 10.36L9.75 12l6-6M12 21a9 9 0 100-18 9 9 0 000 18z"/>',
                        'cog' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.28z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>',
                        'handshake' => '<path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.5c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 012.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 00.322-1.672V3a.75.75 0 01.75-.75A2.25 2.25 0 0116.5 4.5c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 01-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 00-1.423-.23H5.904M14.25 9h2.25M5.904 18.75c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 01-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 10.203 4.167 9.75 5 9.75h1.053c.472 0 .745.556.5.96a8.958 8.958 0 00-1.302 4.665c0 1.194.232 2.333.654 3.375z"/>',
                        'shield' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>',
                        'globe' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"/>',
                        'chart' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/>',
                    ];
                @endphp
                @foreach($advantages as $adv)
                <div class="group bg-cream rounded-brand p-7 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-transparent hover:border-gold/40">
                    <div class="w-14 h-14 rounded-full bg-primary-green flex items-center justify-center mb-5 group-hover:bg-gold transition-colors duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            {!! $icons[$adv['icon']] ?? '' !!}
                        </svg>
                    </div>
                    <h3 class="font-heading font-semibold text-lg text-dark-green mb-2">{{ $adv['title'] }}</h3>
                    <p class="text-sm text-gray-text leading-relaxed">{{ $adv['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- 5. PETA LOKASI --}}
    <section class="py-16 lg:py-20 bg-dark-green text-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <span class="font-heading font-semibold text-light-green uppercase text-sm tracking-wide">Perkebunan Kami</span>
                    <h2 class="mt-3 font-heading font-bold text-3xl md:text-4xl">bertumbuh bersama dari sumber terbaik</h2>
                    <p class="mt-4 text-white/75 leading-relaxed text-justify">
                    Bahan baku berkualitas berasal dari kebun yang dikelola secara mandiri serta kemitraan dengan petani kelapa sawit. Bersama para mitra, PT. Sahabat Sawit Rokan Sejahtera membangun rantai pasok yang konsisten, berkelanjutan, dan memberikan manfaat bagi masyarakat sekitar.
                    </p>

                    <div class="mt-8 grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-white/50 text-xs uppercase tracking-wide">Lokasi</p>
                            <p class="mt-1 font-heading font-semibold">Rokan Hilir, Riau</p>
                        </div>
                        <div>
                            <p class="text-white/50 text-xs uppercase tracking-wide">Luas Area</p>
                            <p class="mt-1 font-heading font-semibold">11.392+ (Ha)</p>
                        </div>
                        <div>
                            <p class="text-white/50 text-xs uppercase tracking-wide">Kemitraan</p>
                            <p class="mt-1 font-heading font-semibold">9348,891(Ha)</p>
                        </div>
                        <div>
                            <p class="text-white/50 text-xs uppercase tracking-wide">Diusahan sendiri</p>
                            <p class="mt-1 font-heading font-semibold">2043,75(Ha)</p>
                        </div>
                    </div>
                    <p class="mt-4 text-xs text-white/40">*SK : SK : 903/MENLHK/SETJEN/PLA.2/12/2016 RIAU.</p>

                    {{-- FIX: halaman Perkebunan belum ada, route('plantation') diganti href="#" --}}
                    <a href="{{ route('plantation') }}" class="mt-8 inline-flex items-center gap-2 px-7 py-3 rounded-brand bg-gold text-dark-green font-heading font-semibold hover:bg-light-green transition-all duration-300">
                        Lihat Detail Perkebunan
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>

                <div class="rounded-brand overflow-hidden shadow-2xl border border-white/10">
                    <!-- Placeholder Google Maps embed -->
                    <iframe
                        src="https://www.google.com/maps?q=Rokan+Hilir,+Riau&output=embed"
                        class="w-full h-[380px]" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Lokasi Perkebunan PT Sahabat Sawit di Rokan Hilir, Riau">
                    </iframe>
                </div>
            </div>
        </div>
    </section>
@endsection