{{--
    Hero bersama halaman "Tentang Kami".
    Pemakaian:
      @include('about._hero', ['title' => '...', 'lead' => '...'])                       // halaman Profil Perusahaan
      @include('about._hero', ['title' => '...', 'lead' => '...', 'crumb' => 'Nama'])     // halaman lainnya
    Foto latar: default herotentang.png; tiap halaman bisa memakai foto sendiri lewat parameter 'foto' => 'nama-file.jpg'
    (file ada di public/images).
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

    <ul class="ab-pills">
        <li>Beroperasi sejak 2023</li>
        <li>Kabupaten Rokan Hilir</li>
        <li>Standar ISPO & RSPO</li>
    </ul>

    @include('about._subnav')
    </div>
</header>