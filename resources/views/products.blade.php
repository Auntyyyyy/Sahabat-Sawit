@extends('layouts.app')

@section('title', 'Produk — PT Sahabat Sawit')

@section('content')
<section class="bg-dark-green text-white py-20">
    <div class="max-w-5xl mx-auto px-6 lg:px-8 text-center">
        <span class="font-heading font-semibold text-light-green uppercase text-sm tracking-wide">Produk Kami</span>
        <h1 class="mt-3 font-heading font-bold text-3xl md:text-5xl">Hasil Perkebunan Berkualitas</h1>
        <p class="mt-4 text-white/75 max-w-2xl mx-auto leading-relaxed">
            Produk unggulan dari perkebunan kelapa sawit PT Sahabat Sawit di Rokan Hilir, Riau.
        </p>
    </div>
</section>

<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-8">
        @foreach($products as $product)
        <div class="rounded-brand overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 group">
            <div class="h-56 overflow-hidden">
                <img src="{{ asset('images/products/' . $product['image']) }}" alt="{{ $product['name'] }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                     onerror="this.src='https://placehold.co/500x400/2E7D32/F5F1E8?text={{ urlencode($product['name']) }}'">
            </div>
            <div class="p-6 bg-cream">
                <h3 class="font-heading font-semibold text-lg text-dark-green">{{ $product['name'] }}</h3>
                <p class="mt-2 text-sm text-gray-text leading-relaxed">{{ $product['desc'] }}</p>
            </div>
        </div>
        @endforeach
    </div>
    <p class="max-w-7xl mx-auto px-6 lg:px-8 mt-8 text-xs text-gray-text">
        *Data produk bersifat placeholder dan dapat diganti melalui data perusahaan pada database Laravel.
    </p>
</section>
@endsection
