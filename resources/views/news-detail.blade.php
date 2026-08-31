@extends('layouts.app')

@section('title', $article['title'] . ' — PT Sahabat Sawit')

@section('content')
<section class="bg-dark-green text-white py-20">
    <div class="max-w-3xl mx-auto px-6 lg:px-8 text-center">
        <span class="px-3 py-1 rounded-full bg-gold/15 border border-gold/40 text-gold text-xs font-heading font-semibold uppercase">{{ $article['category'] }}</span>
        <h1 class="mt-4 font-heading font-bold text-3xl md:text-4xl">{{ $article['title'] }}</h1>
        <p class="mt-3 text-white/60 text-sm">{{ $article['date'] }}</p>
    </div>
</section>

<section class="py-16 bg-white">
    <div class="max-w-3xl mx-auto px-6 lg:px-8">
        <p class="text-gray-text leading-relaxed text-lg">{{ $article['body'] }}</p>
        <a href="{{ route('news') }}" class="mt-10 inline-flex items-center gap-2 text-primary-green font-heading font-semibold hover:text-dark-green transition-colors">
            ← Kembali ke Berita
        </a>
    </div>
</section>
@endsection
