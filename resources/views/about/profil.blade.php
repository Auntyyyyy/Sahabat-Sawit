@extends('layouts.app')

@section('title', 'Profil Perusahaan — PT Sahabat Sawit')

@section('content')
<section class="relative min-h-screen flex items-center bg-dark-green text-white bg-cover bg-center bg-no-repeat hero-section" style="background-image: url('{{ asset('images/herotentang.png') }}');">
    <!-- Overlay Gradient -->
    <div class="absolute inset-0 hero-overlay"></div>

    {{-- DIRAPIKAN: padding atas/bawah supaya isi tidak menempel navbar dan tepi bawah,
         lebar isi dipersempit sedikit supaya baris teks lebih seimbang --}}
    <div class="relative w-full max-w-4xl mx-auto px-6 lg:px-8 text-center" style="padding-top:7rem;padding-bottom:6rem;">

        <!-- Breadcrumb -->
        <div class="hero-fade hero-delay-1 flex items-center justify-center gap-2 text-sm text-white/60">
            <a href="{{ url('/') }}" class="hover:text-white transition-colors">Beranda</a>
            <span>/</span>
            <span class="text-white/90">Tentang Kami</span>
        </div>

        <!-- Label + Judul -->
        <span class="hero-fade hero-delay-2 block mt-6 font-heading font-semibold text-light-green uppercase text-sm tracking-wide">Tentang Kami</span>

        {{-- line-height 1.2 + text-wrap:balance: judul dua baris jadi seimbang
             ("Mengenal PT. Sahabat Sawit" / "Rokan Sejahtera"), tidak ada kata yang sendirian --}}
        <h1 class="hero-fade hero-delay-3 mt-4 font-heading font-bold text-3xl md:text-5xl" style="line-height:1.2;text-wrap:balance;">
            Mengenal PT. Sahabat Sawit Rokan Sejahtera
        </h1>

        <p class="hero-fade hero-delay-4 mt-6 text-white/75 max-w-2xl mx-auto leading-relaxed" style="text-wrap:balance;">
            Perusahaan perkebunan kelapa sawit yang tumbuh dan berkembang di Kabupaten Rokan Hilir, Provinsi Riau.
        </p>

        <!-- Badge Statistik -->
        <div class="hero-fade hero-delay-5 flex flex-wrap justify-center gap-3 mt-8">
            <span class="hero-badge">Beroperasi sejak 2023</span>
            <span class="hero-badge">Kabupaten Rokan Hilir</span>
            <span class="hero-badge">Standar ISPO & RSPO</span>
        </div>

        <!-- Sub-navigasi "Tentang Kami" (diberi jarak lebih lega dari badge di atasnya) -->
        <div class="hero-fade hero-delay-6 mt-10">
            @include('about._subnav')
        </div>
    </div>
</section>

<section class="pt-14 pb-8 lg:pt-16 lg:pb-10 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <img src="{{ asset('images/herotentang.png') }}" alt="Tim PT Sahabat Sawit di area perkebunan"
             class="rounded-brand shadow-xl w-full h-[420px] object-cover"
             onerror="this.src='https://placehold.co/700x500/1F5F3B/F5F1E8?text=PT+Sahabat+Sawit'">
        <div>
            <h2 class="font-heading font-bold text-2xl md:text-3xl text-dark-green">Berkembang Bersama Alam dan Masyarakat.</h2>
            <p class="mt-5 text-gray-text leading-relaxed text-justify">
                PT. Sahabat Sawit Rokan Sejahtera adalah perusahaan yang bergerak di bidang perkebunan dan industri kelapa sawit di Kabupaten Rokan Hilir, Riau. Sejak berdiri, kami berkomitmen mengelola perkebunan secara profesional dan bertanggung jawab, sekaligus menjaga kelestarian lingkungan dan memberikan manfaat bagi masyarakat sekitar.
            </p>
            <p class="mt-4 text-gray-text leading-relaxed text-justify">
                Kami percaya bahwa pertumbuhan bisnis yang berkelanjutan hanya dapat dicapai melalui keseimbangan antara produktivitas, tanggung jawab sosial, dan pelestarian lingkungan.
            </p>
        </div>
    </div>
</section>

<section id="awal-perjalanan" class="pt-8 pb-16 lg:pt-10 lg:pb-20 bg-white">
    <div class="max-w-5xl mx-auto px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-14">
            <span class="font-heading font-semibold text-primary-green uppercase text-sm tracking-wide">Awal perjalanan</span>
            <h2 class="mt-3 font-heading font-bold text-2xl md:text-3xl text-dark-green">Latar Belakang Perusahaan</h2>
        </div>
        <div class="text-gray-text leading-relaxed space-y-4 text-justify">
            <p>
            Perjalanan <strong>PT. Sahabat Sawit Rokan Sejahtera (SSRS)</strong> berawal dari sebuah langkah sederhana yang penuh keyakinan. Didirikan oleh Siswaja Muljadi, PT. SSRS mulai berdiri di lokasi yang hingga kini menjadi tempat beroperasinya perusahaan sejak tahun 2008.
            </p>
            <p>
            Sebelum mendirikan PT. SSRS, Siswaja Muljadi telah lebih dahulu berkecimpung dalam dunia kelapa sawit sebagai pemasok Tandan Buah Segar (TBS) kepada PT. Sawit Riau Makmur, salah satu Pabrik Kelapa Sawit (PKS) yang berlokasi di Desa Teluk Mega, Kecamatan Tanah Putih, Kabupaten Rokan Hilir.
            </p>
            <p>
            Berbekal pengalaman, pemahaman terhadap industri kelapa sawit, serta semangat untuk terus berkembang, perjalanan tersebut kemudian menjadi titik awal berdirinya PT. SSRS. Dari sebuah langkah awal yang sederhana, perusahaan terus bertumbuh dan membangun fondasi untuk menjadi bagian dari perkembangan industri kelapa sawit di Kabupaten Rokan Hilir.
            </p>
            <p>
            Setelah melalui berbagai proses perkembangan, <strong>PT. Sahabat Sawit Rokan Sejahtera resmi mulai beroperasi pada 14 Juli 2023</strong>, menjadi salah satu tonggak penting dalam perjalanan dan perkembangan perusahaan.
            </p>
        </div>
    </div>
</section>
@endsection