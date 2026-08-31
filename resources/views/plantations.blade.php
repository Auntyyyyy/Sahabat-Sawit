@extends('layouts.app')

@section('title', 'Perkebunan Kami — PT Sahabat Sawit')

@section('content')
<section class="bg-dark-green text-white py-20">
    <div class="max-w-5xl mx-auto px-6 lg:px-8 text-center">
        <span class="font-heading font-semibold text-light-green uppercase text-sm tracking-wide">Perkebunan</span>
        <h1 class="mt-3 font-heading font-bold text-3xl md:text-5xl">Area Perkebunan Kami</h1>
        <p class="mt-4 text-white/75 max-w-2xl mx-auto leading-relaxed">
            Beroperasi dan berkembang di Kabupaten Rokan Hilir, Provinsi Riau.
        </p>
    </div>
</section>

<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
        <img src="{{ asset('images/perkebunan.jpg') }}" alt="Area perkebunan kelapa sawit PT Sahabat Sawit"
             class="rounded-brand shadow-xl w-full h-[420px] object-cover"
             onerror="this.src='https://placehold.co/700x500/4F772D/F5F1E8?text=Perkebunan+Kami'">
        <div class="space-y-6">
            <div class="bg-cream rounded-brand p-6">
                <p class="text-xs uppercase tracking-wide text-primary-green font-heading font-semibold">Lokasi</p>
                <p class="mt-1 text-dark-green font-heading font-semibold text-lg">{{ $info['lokasi'] }}</p>
            </div>
            <div class="bg-cream rounded-brand p-6">
                <p class="text-xs uppercase tracking-wide text-primary-green font-heading font-semibold">Luas Area</p>
                <p class="mt-1 text-dark-green font-heading font-semibold text-lg">{{ $info['luas'] }}</p>
            </div>
            <div class="bg-cream rounded-brand p-6">
                <p class="text-xs uppercase tracking-wide text-primary-green font-heading font-semibold">Sistem Pengelolaan</p>
                <p class="mt-1 text-dark-green">{{ $info['sistem'] }}</p>
            </div>
            <div class="bg-cream rounded-brand p-6">
                <p class="text-xs uppercase tracking-wide text-primary-green font-heading font-semibold">Komitmen Lingkungan</p>
                <p class="mt-1 text-dark-green">{{ $info['komitmen'] }}</p>
            </div>
        </div>
    </div>
</section>

<section class="py-20 bg-cream">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <h2 class="text-center font-heading font-bold text-2xl md:text-3xl text-dark-green mb-10">Peta Lokasi</h2>
        <div class="rounded-brand overflow-hidden shadow-xl border border-gray-100">
            <iframe src="https://www.google.com/maps?q=Rokan+Hilir,+Riau&output=embed"
                    class="w-full h-[420px]" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    title="Peta Lokasi Perkebunan PT Sahabat Sawit"></iframe>
        </div>
    </div>
</section>
@endsection
