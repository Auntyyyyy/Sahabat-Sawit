{{--
    Sub-navigasi "Tentang Kami" (gaya baru: tautan bergaris bawah).
    Dipakai di keempat halaman; tab aktif dideteksi otomatis lewat request()->routeIs().
--}}
@php
    $aboutTabs = [
        ['route' => 'about',             'label' => 'Profil Perusahaan'],
        ['route' => 'about.struktur',    'label' => 'Profil Manajemen'],
        ['route' => 'about.nilai',       'label' => 'Nilai Perusahaan'],
        ['route' => 'about.sertifikasi', 'label' => 'Sertifikasi & Penghargaan'],
    ];
@endphp

<nav class="ab-tabs" aria-label="Sub-navigasi Tentang Kami">
    @foreach ($aboutTabs as $tab)
        <a href="{{ route($tab['route']) }}"
           class="{{ request()->routeIs($tab['route']) ? 'is-active' : '' }}"
           @if (request()->routeIs($tab['route'])) aria-current="page" @endif>{{ $tab['label'] }}</a>
    @endforeach
</nav>