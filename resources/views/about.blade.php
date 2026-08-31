@extends('layouts.app')

@section('title', 'Tentang Kami — PT Sahabat Sawit')

@section('content')
<section class="bg-dark-green text-white py-20">
    <div class="max-w-5xl mx-auto px-6 lg:px-8 text-center">
        <span class="font-heading font-semibold text-light-green uppercase text-sm tracking-wide">Tentang Kami</span>
        <h1 class="mt-3 font-heading font-bold text-3xl md:text-5xl">Mengenal PT Sahabat Sawit</h1>
        <p class="mt-4 text-white/75 max-w-2xl mx-auto leading-relaxed">
            Perusahaan perkebunan kelapa sawit yang tumbuh dan berkembang di Kabupaten Rokan Hilir, Provinsi Riau.
        </p>
    </div>
</section>

<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <img src="{{ asset('images/tentang-kami-full.jpg') }}" alt="Tim PT Sahabat Sawit di area perkebunan"
             class="rounded-brand shadow-xl w-full h-[420px] object-cover"
             onerror="this.src='https://placehold.co/700x500/1F5F3B/F5F1E8?text=PT+Sahabat+Sawit'">
        <div>
            <h2 class="font-heading font-bold text-2xl md:text-3xl text-dark-green">Berkembang Bersama Alam dan Masyarakat</h2>
            <p class="mt-5 text-gray-text leading-relaxed">
                PT Sahabat Sawit adalah perusahaan yang bergerak di bidang perkebunan dan industri kelapa sawit di Kabupaten Rokan Hilir, Riau. Sejak berdiri, kami berkomitmen mengelola perkebunan secara profesional dan bertanggung jawab, sekaligus menjaga kelestarian lingkungan dan memberikan manfaat bagi masyarakat sekitar.
            </p>
            <p class="mt-4 text-gray-text leading-relaxed">
                Kami percaya bahwa pertumbuhan bisnis yang berkelanjutan hanya dapat dicapai melalui keseimbangan antara produktivitas, tanggung jawab sosial, dan pelestarian lingkungan.
            </p>
        </div>
    </div>
</section>

<section class="py-20 bg-cream">
    <div class="max-w-5xl mx-auto px-6 lg:px-8">
        <h2 class="text-center font-heading font-bold text-2xl md:text-3xl text-dark-green mb-14">Perjalanan Kami</h2>
        <div class="space-y-8 border-l-2 border-primary-green/30 pl-8">
            @foreach($milestones as $m)
            <div class="relative">
                <span class="absolute -left-[38px] top-1 w-4 h-4 rounded-full bg-gold border-4 border-cream"></span>
                <p class="font-heading font-bold text-primary-green">{{ $m['year'] }}</p>
                <p class="mt-1 text-gray-text">{{ $m['text'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
