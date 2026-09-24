{{--
    Partial: Sub-navigasi "Tentang Kami"
    Dipakai di keempat halaman (profil, struktur, nilai, sertifikasi) supaya
    tab-nya selalu konsisten. Cukup include, tidak perlu parameter apa pun —
    state aktif dideteksi otomatis lewat request()->routeIs().
--}}
@php
    $aboutTabs = [
        ['route' => 'about',              'label' => 'Profil Perusahaan'],
        ['route' => 'about.struktur',     'label' => 'Profil Manajemen'],
        ['route' => 'about.nilai',        'label' => 'Nilai Perusahaan'],
        ['route' => 'about.sertifikasi',  'label' => 'Sertifikasi & Penghargaan'],
    ];
@endphp

<div class="relative z-10 flex justify-center px-2">
    <div class="inline-flex flex-wrap justify-center gap-1.5 bg-white/10 border border-white/15 backdrop-blur-sm rounded-full p-1.5">
        @foreach($aboutTabs as $tab)
        <a href="{{ route($tab['route']) }}"
           class="px-4 md:px-5 py-2 rounded-full font-heading font-semibold text-xs md:text-sm whitespace-nowrap transition-all duration-300 {{ request()->routeIs($tab['route']) ? 'bg-white text-dark-green shadow-md' : 'text-white/75 hover:text-white hover:bg-white/10' }}">
            {{ $tab['label'] }}
        </a>
        @endforeach
    </div>
</div>