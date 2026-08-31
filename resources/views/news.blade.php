@extends('layouts.app')

@section('title', 'Berita — PT Sahabat Sawit')

@section('content')
<section class="bg-dark-green text-white py-20">
    <div class="max-w-5xl mx-auto px-6 lg:px-8 text-center">
        <span class="font-heading font-semibold text-light-green uppercase text-sm tracking-wide">Berita</span>
        <h1 class="mt-3 font-heading font-bold text-3xl md:text-5xl">Berita Terbaru</h1>
    </div>
</section>

<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-8">
        @foreach($news as $slug => $item)
        <article class="rounded-brand overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100">
            <div class="h-48 overflow-hidden">
                <img src="https://placehold.co/500x300/1F5F3B/F5F1E8?text={{ urlencode($item['category']) }}" alt="{{ $item['title'] }}" class="w-full h-full object-cover">
            </div>
            <div class="p-6">
                <div class="flex items-center gap-3 text-xs text-gray-text">
                    <span class="px-3 py-1 rounded-full bg-light-green/20 text-palm-leaf font-semibold">{{ $item['category'] }}</span>
                    <span>{{ $item['date'] }}</span>
                </div>
                <h3 class="mt-4 font-heading font-semibold text-lg text-dark-green leading-snug">{{ $item['title'] }}</h3>
                <p class="mt-2 text-sm text-gray-text leading-relaxed">{{ $item['excerpt'] }}</p>
                <a href="{{ route('news.show', $slug) }}" class="mt-4 inline-flex items-center gap-1 text-primary-green font-heading font-semibold text-sm hover:text-gold transition-colors">
                    Baca Selengkapnya →
                </a>
            </div>
        </article>
        @endforeach
    </div>
</section>
@endsection
