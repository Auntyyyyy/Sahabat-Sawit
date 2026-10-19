{{--
    Hero bersama halaman "Tentang Kami".
    Pemakaian:
      @include('about._hero', ['title' => '...', 'lead' => '...'])                       // halaman Profil Perusahaan
      @include('about._hero', ['title' => '...', 'lead' => '...', 'crumb' => 'Nama'])     // halaman lainnya
--}}
<header class="ab-hero">
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
</header>