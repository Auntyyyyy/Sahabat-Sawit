@extends('layouts.app')

@section('title', 'Keberlanjutan — PT Sahabat Sawit')

@section('content')
<section class="bg-dark-green text-white py-20">
    <div class="max-w-5xl mx-auto px-6 lg:px-8 text-center">
        <span class="font-heading font-semibold text-light-green uppercase text-sm tracking-wide">Keberlanjutan</span>
        <h1 class="mt-3 font-heading font-bold text-3xl md:text-5xl">Komitmen untuk Keberlanjutan</h1>
        <p class="mt-4 text-white/75 max-w-2xl mx-auto leading-relaxed">
            Menjaga keseimbangan antara produktivitas, lingkungan, dan kesejahteraan masyarakat.
        </p>
    </div>
</section>

<section class="py-20 bg-cream">
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

<section data-counter-section class="py-20 bg-white">
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
@endsection
