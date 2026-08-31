@extends('layouts.app')

@section('title', 'Karier — PT Sahabat Sawit')

@section('content')
<section class="bg-primary-green text-white py-20">
    <div class="max-w-3xl mx-auto px-6 lg:px-8 text-center">
        <h1 class="font-heading font-bold text-3xl md:text-5xl">Bergabung Bersama Kami</h1>
        <p class="mt-4 text-white/85 leading-relaxed">
            Bersama kami, tumbuh dan berkontribusi dalam membangun masa depan industri perkebunan yang lebih baik.
        </p>
    </div>
</section>

<section class="py-20 bg-cream">
    <div class="max-w-5xl mx-auto px-6 lg:px-8">
        <h2 class="font-heading font-bold text-2xl text-dark-green mb-8">Lowongan Tersedia</h2>
        <div class="space-y-5">
            @forelse($jobs as $job)
            <div class="bg-white rounded-brand p-6 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h3 class="font-heading font-semibold text-lg text-dark-green">{{ $job['title'] }}</h3>
                    <p class="text-sm text-gray-text mt-1">📍 {{ $job['location'] }} &nbsp;•&nbsp; {{ $job['type'] }}</p>
                </div>
                <a href="{{ route('contact') }}" class="inline-flex items-center justify-center px-6 py-2.5 rounded-brand bg-primary-green text-white font-heading font-semibold text-sm hover:bg-dark-green transition-all duration-300 whitespace-nowrap">
                    Lamar Sekarang
                </a>
            </div>
            @empty
            <p class="text-gray-text">Belum ada lowongan yang tersedia saat ini.</p>
            @endforelse
        </div>
    </div>
</section>
@endsection
