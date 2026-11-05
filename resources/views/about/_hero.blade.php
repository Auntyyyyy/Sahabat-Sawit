{{--
    Hero bersama halaman "Tentang Kami".
    Parameter: title, lead (wajib) | crumb (nama halaman, kosongkan untuk Profil Perusahaan)
               chips (true = tampilkan tiga chip fakta; hanya dipakai di Profil Perusahaan)
               foto (nama file di public/images, default herotentang.png)
    Sub-navigasi dan skrip bersama dimuat SETELAH header, supaya sub-nav bisa menempel (sticky).
--}}
<header class="ab-hero">
    <img class="ab-bg" src="{{ asset('images/' . ($foto ?? 'herotentang.png')) }}" alt="" onerror="this.style.display='none'">

    <div class="ab-hero-inner">
        <nav class="ab-crumb" aria-label="Breadcrumb">
            <a href="{{ url('/') }}">Beranda</a>
            <span aria-hidden="true">/</span>
            @if (!empty($crumb))
                <a href="{{ route('about') }}">Tentang Kami</a>
                <span aria-hidden="true">/</span>
                <span aria-current="page">{{ $crumb }}</span>
            @else
                <span aria-current="page">Tentang Kami</span>
            @endif
        </nav>

        <h1 class="ab-title">{{ $title }}</h1>
        <p class="ab-lead">{{ $lead }}</p>

        @if (!empty($chips))
            <ul class="ab-pills">
                <li>Beroperasi sejak 2023</li>
                <li>Kabupaten Rokan Hilir</li>
                <li>Standar ISPO & RSPO</li>
            </ul>
        @endif
    </div>
</header>

@include('about._subnav')
@include('about._scripts')