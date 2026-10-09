@extends('layouts.app')

@section('title', 'Nilai Perusahaan — PT Sahabat Sawit')

@section('content')

{{-- Hero: disamakan dengan hero Profil Perusahaan (foto latar penuh + overlay gradasi
     + badge + subnav). Yang berbeda hanya judul besarnya dan breadcrumb-nya. --}}
<section class="relative min-h-screen flex items-center bg-dark-green text-white bg-cover bg-center bg-no-repeat hero-section" style="background-image: url('{{ asset('images/herotentang.png') }}');">
    <!-- Overlay Gradient -->
    <div class="absolute inset-0 hero-overlay"></div>

    <div class="relative w-full max-w-4xl mx-auto px-6 lg:px-8 text-center" style="padding-top:7rem;padding-bottom:6rem;">

        <!-- Breadcrumb -->
        <div class="hero-fade hero-delay-1 flex items-center justify-center gap-2 text-sm text-white/60">
            <a href="{{ url('/') }}" class="hover:text-white transition-colors">Beranda</a>
            <span>/</span>
            <a href="{{ route('about') }}" class="hover:text-white transition-colors">Tentang Kami</a>
            <span>/</span>
            <span class="text-white/90">Nilai Perusahaan</span>
        </div>

        <!-- Label + Judul -->
        <span class="hero-fade hero-delay-2 block mt-6 font-heading font-semibold text-light-green uppercase text-sm tracking-wide">Tentang Kami</span>

        <h1 class="hero-fade hero-delay-3 mt-4 font-heading font-bold text-3xl md:text-5xl" style="line-height:1.2;text-wrap:balance;">
            Nilai Perusahaan
        </h1>

        <p class="hero-fade hero-delay-4 mt-6 text-white/75 max-w-2xl mx-auto leading-relaxed" style="text-wrap:balance;">
        Nilai-nilai yang menjadi pedoman dalam setiap langkah dan keputusan perusahaan.        </p>

        <!-- Badge Statistik -->
        <div class="hero-fade hero-delay-5 flex flex-wrap justify-center gap-3 mt-8">
            <span class="hero-badge">Beroperasi sejak 2023</span>
            <span class="hero-badge">Kabupaten Rokan Hilir</span>
            <span class="hero-badge">Standar ISPO & RSPO</span>
        </div>

        <!-- Sub-navigasi "Tentang Kami" -->
        <div class="hero-fade hero-delay-6 mt-10">
            @include('about._subnav')
        </div>
    </div>
</section>


<section class="py-14 lg:py-16 bg-cream">
    <div class="max-w-4xl mx-auto px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-14">
            <span class="font-heading font-semibold text-secondary-green uppercase text-sm tracking-wide">Arah Kami</span>
            <h2 class="mt-3 font-heading font-bold text-2xl md:text-3xl text-dark-green">Visi & Misi</h2>
        </div>

        <div class="vm-stack">
            <!-- Visi -->
            <div class="vm-stack-card">
                <h3 class="vm-stack-title text-secondary-green">Visi</h3>
                <p class="vm-stack-text">
                    Menjadi perusahaan PKS dengan standar ISPO dan RSPO berskala internasional, berteknologi tinggi, dan berwawasan lingkungan.
                </p>
            </div>

            <!-- Misi -->
            <div class="vm-stack-card vm-stack-card-secondary">
                <h3 class="vm-stack-title text-dark-green">Misi</h3>
                <ul class="vm-stack-list">
                    <li>Memprioritaskan pelayanan penerimaan TBS masyarakat sekitar PKS.</li>
                    <li>Turut serta membina petani sawit untuk meningkatkan kualitas dan kuantitas TBS.</li>
                    <li>Melakukan pengolahan yang efisien dan efektif dengan standar loses di bawah acuan.</li>
                    <li>Menerapkan manajemen perusahaan yang baik, menjalin komunikasi dua arah, dan menghargai karyawan sebagai mitra.</li>
                    <li>Menjadi perusahaan yang peduli dan memperhatikan lingkungan masyarakat sekitar.</li>
                    <li>Menjadi mitra bisnis yang andal dan terpercaya bagi seluruh vendor, dengan menjunjung tinggi profesionalisme, komunikasi terbuka, serta membangun hubungan jangka panjang yang saling menguntungkan.</li>
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- Fasilitas & Kesejahteraan Karyawan — digabung ke Nilai Perusahaan karena
     mencerminkan bagaimana nilai-nilai perusahaan diterapkan ke karyawan. --}}
<section class="py-14 lg:py-16 bg-white">
    <div class="max-w-6xl mx-auto px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-14">
            <span class="font-heading font-semibold text-primary-green uppercase text-sm tracking-wide">Kepedulian Kami</span>
            <h2 class="mt-3 font-heading font-bold text-2xl md:text-3xl text-dark-green">Fasilitas & Kesejahteraan Karyawan</h2>
            <p class="mt-4 text-gray-text leading-relaxed">
                Kami berkomitmen menjaga kesejahteraan seluruh karyawan melalui berbagai fasilitas dan program pendukung, sebagai bentuk apresiasi atas dedikasi mereka.
            </p>
        </div>

        <div class="facility-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

            <div class="facility-card">
                <div class="facility-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                <h3 class="facility-title">Jaminan Kesehatan</h3>
                <p class="facility-desc">BPJS Kesehatan dan BPJS Ketenagakerjaan bagi seluruh karyawan tetap.</p>
            </div>

            <div class="facility-card">
                <div class="facility-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                    </svg>
                </div>
                <h3 class="facility-title">Pelatihan & Pengembangan</h3>
                <p class="facility-desc">Program pelatihan rutin untuk meningkatkan kompetensi dan jenjang karier karyawan.</p>
            </div>

            <div class="facility-card">
                <div class="facility-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75"/>
                    </svg>
                </div>
                <h3 class="facility-title">Mess Karyawan</h3>
                <p class="facility-desc">Tempat tinggal yang layak dan aman di sekitar area operasional perkebunan.</p>
            </div>

            <div class="facility-card">
                <div class="facility-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.125-.504 1.125-1.125V14.25m-17.25 4.5V14.25m0 0V9.375c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125V14.25m-6.75 0h6.75m0 0h4.5m-4.5 0V9.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V14.25"/>
                    </svg>
                </div>
                <h3 class="facility-title">Transportasi Karyawan</h3>
                <p class="facility-desc">Fasilitas antar-jemput bagi karyawan menuju area operasional perkebunan.</p>
            </div>

            <div class="facility-card">
                <div class="facility-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.75h-.152c-3.196 0-6.1-1.248-8.25-3.286z"/>
                    </svg>
                </div>
                <h3 class="facility-title">Keselamatan & Kesehatan Kerja</h3>
                <p class="facility-desc">Program K3 yang konsisten dengan alat pelindung diri (APD) di seluruh area kerja.</p>
            </div>

            <div class="facility-card">
                <div class="facility-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
                    </svg>
                </div>
                <h3 class="facility-title">Koperasi Karyawan</h3>
                <p class="facility-desc">Koperasi simpan pinjam untuk membantu kebutuhan finansial karyawan.</p>
            </div>

            <div class="facility-card">
                <div class="facility-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8.25v10.5m0-10.5a2.25 2.25 0 110-4.5 2.25 2.25 0 010 4.5zM3.75 8.25h16.5v10.5H3.75V8.25zm0 0V6a2.25 2.25 0 012.25-2.25h12A2.25 2.25 0 0120.25 6v2.25"/>
                    </svg>
                </div>
                <h3 class="facility-title">Tunjangan Hari Raya</h3>
                <p class="facility-desc">THR dan bonus tahunan sebagai bentuk apresiasi atas kinerja karyawan.</p>
            </div>

        </div>
    </div>
</section>
@endsection