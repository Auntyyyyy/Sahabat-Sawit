@extends('layouts.app')

@section('title', 'Kontak — PT Sahabat Sawit')

@section('content')
<section class="bg-dark-green text-white py-20">
    <div class="max-w-5xl mx-auto px-6 lg:px-8 text-center">
        <span class="font-heading font-semibold text-light-green uppercase text-sm tracking-wide">Kontak</span>
        <h1 class="mt-3 font-heading font-bold text-3xl md:text-5xl">Hubungi Kami</h1>
        <p class="mt-4 text-white/75 max-w-2xl mx-auto leading-relaxed">
            PT Sahabat Sawit — Kabupaten Rokan Hilir, Riau, Indonesia
        </p>
    </div>
</section>

<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-12">
        <div>
            <h2 class="font-heading font-bold text-2xl text-dark-green mb-6">Informasi Kontak</h2>
            <div class="space-y-5">
                <div class="flex items-start gap-4">
                    <span class="w-11 h-11 rounded-full bg-primary-green/10 text-primary-green flex items-center justify-center flex-shrink-0">📍</span>
                    <div>
                        <p class="font-heading font-semibold text-dark-green">Alamat</p>
                        <p class="text-gray-text text-sm mt-1">Jl. Perkebunan Sawit No. 1, Kabupaten Rokan Hilir, Riau, Indonesia (placeholder — sesuaikan dengan alamat aktual)</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <span class="w-11 h-11 rounded-full bg-primary-green/10 text-primary-green flex items-center justify-center flex-shrink-0">📞</span>
                    <div>
                        <p class="font-heading font-semibold text-dark-green">Telepon</p>
                        <p class="text-gray-text text-sm mt-1">+62 812-3456-7890 (placeholder)</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <span class="w-11 h-11 rounded-full bg-primary-green/10 text-primary-green flex items-center justify-center flex-shrink-0">✉️</span>
                    <div>
                        <p class="font-heading font-semibold text-dark-green">Email</p>
                        <p class="text-gray-text text-sm mt-1">info@sahabatsawit.co.id (placeholder)</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <span class="w-11 h-11 rounded-full bg-primary-green/10 text-primary-green flex items-center justify-center flex-shrink-0">🕐</span>
                    <div>
                        <p class="font-heading font-semibold text-dark-green">Jam Operasional</p>
                        <p class="text-gray-text text-sm mt-1">Senin – Jumat: 08.00 – 17.00 WIB</p>
                    </div>
                </div>
            </div>

            <div class="mt-8 rounded-brand overflow-hidden shadow-lg border border-gray-100">
                <iframe src="https://www.google.com/maps?q=Rokan+Hilir,+Riau&output=embed"
                        class="w-full h-[280px]" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade" title="Lokasi PT Sahabat Sawit"></iframe>
            </div>
        </div>

        <div class="bg-cream rounded-brand p-8 shadow-lg">
            <h2 class="font-heading font-bold text-2xl text-dark-green mb-6">Kirim Pesan</h2>
            @include('components.contact-form')
        </div>
    </div>
</section>
@endsection
