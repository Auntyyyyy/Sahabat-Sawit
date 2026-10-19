@extends('layouts.app')

@section('title', 'Nilai Perusahaan — PT Sahabat Sawit')

@section('content')
@include('about._style')

@php
    $misi = [
        'Memprioritaskan pelayanan penerimaan TBS masyarakat sekitar PKS.',
        'Turut serta membina petani sawit untuk meningkatkan kualitas dan kuantitas TBS.',
        'Melakukan pengolahan yang efisien dan efektif dengan standar loses di bawah acuan.',
        'Menerapkan manajemen perusahaan yang baik, menjalin komunikasi dua arah, dan menghargai karyawan sebagai mitra.',
        'Menjadi perusahaan yang peduli dan memperhatikan lingkungan masyarakat sekitar.',
        'Menjadi mitra bisnis yang andal dan terpercaya bagi seluruh vendor, dengan menjunjung tinggi profesionalisme, komunikasi terbuka, serta membangun hubungan jangka panjang yang saling menguntungkan.',
    ];

    // Fasilitas & kesejahteraan karyawan: mencerminkan bagaimana nilai perusahaan diterapkan ke karyawan.
    $fasilitas = [
        ['Jaminan Kesehatan',            'BPJS Kesehatan dan BPJS Ketenagakerjaan bagi seluruh karyawan tetap.'],
        ['Pelatihan & Pengembangan',     'Program pelatihan rutin untuk meningkatkan kompetensi dan jenjang karier karyawan.'],
        ['Mess Karyawan',                'Tempat tinggal yang layak dan aman di sekitar area operasional perkebunan.'],
        ['Transportasi Karyawan',        'Fasilitas antar-jemput bagi karyawan menuju area operasional perkebunan.'],
        ['Keselamatan & Kesehatan Kerja','Program K3 yang konsisten dengan alat pelindung diri (APD) di seluruh area kerja.'],
        ['Koperasi Karyawan',            'Koperasi simpan pinjam untuk membantu kebutuhan finansial karyawan.'],
        ['Tunjangan Hari Raya',          'THR dan bonus tahunan sebagai bentuk apresiasi atas kinerja karyawan.'],
    ];
@endphp

<style>
    .ab-vision { margin-top: 1.25rem; max-width: 30em; font-size: clamp(1.5rem, 3vw, 2.4rem); font-weight: 600; line-height: 1.25; letter-spacing: -.02em; }
    .ab-sub { margin-top: 3rem; font-size: .95rem; color: rgba(242,244,236,.6); }
    .ab-missions { list-style: none; counter-reset: misi; margin: 1rem 0 0; padding: 0; max-width: 46rem; }
    .ab-missions li { counter-increment: misi; display: grid; grid-template-columns: 3rem 1fr; gap: 1rem; padding: 1rem 0; border-top: 1px solid rgba(242,244,236,.15); color: rgba(242,244,236,.85); }
    .ab-missions li:last-child { border-bottom: 1px solid rgba(242,244,236,.15); }
    .ab-missions li::before { content: counter(misi, decimal-leading-zero); padding-top: .2rem; font-size: .85rem; font-weight: 600; color: var(--emas); }

    .ab-rows { list-style: none; margin: clamp(2.5rem, 6vh, 4rem) 0 0; padding: 0; }
    .ab-row { display: grid; grid-template-columns: 1fr; gap: .5rem 3rem; padding: 1.5rem 0; border-top: 1px solid rgba(242,244,236,.15); }
    .ab-row:last-child { border-bottom: 1px solid rgba(242,244,236,.15); }
    .ab-row h3 { font-size: clamp(1.3rem, 2.4vw, 2rem); font-weight: 700; letter-spacing: -.02em; line-height: 1.15; transition: color .25s; }
    .ab-row p { max-width: 34rem; color: rgba(242,244,236,.7); }
    .ab-row:hover h3 { color: var(--emas); }
    @media (min-width: 900px) { .ab-row { grid-template-columns: 1fr 1fr; align-items: baseline; } }
</style>

<div class="ab">

    @include('about._hero', [
        'title' => 'Nilai Perusahaan',
        'lead'  => 'Nilai-nilai yang menjadi pedoman dalam setiap langkah dan keputusan perusahaan.',
        'crumb' => 'Nilai Perusahaan',
    ])

    {{-- VISI & MISI --}}
    <section class="ab-sec">
        <div class="ab-split ab-split-top">
            <p class="ab-label">Arah kami</p>
            <div>
                <h2 class="ab-h2">Visi & Misi</h2>

                <p class="ab-sub">Visi</p>
                <p class="ab-vision">
                    Menjadi perusahaan PKS dengan standar ISPO dan RSPO berskala internasional, berteknologi tinggi, dan berwawasan lingkungan.
                </p>

                <p class="ab-sub">Misi</p>
                <ol class="ab-missions">
                    @foreach($misi as $item)
                        <li><span>{{ $item }}</span></li>
                    @endforeach
                </ol>
            </div>
        </div>
    </section>

    {{-- FASILITAS & KESEJAHTERAAN KARYAWAN --}}
    <section class="ab-sec">
        <p class="ab-label">Kepedulian kami</p>
        <h2 class="ab-h2">Fasilitas & Kesejahteraan Karyawan</h2>
        <p class="ab-p">
            Kami berkomitmen menjaga kesejahteraan seluruh karyawan melalui berbagai fasilitas dan program pendukung, sebagai bentuk apresiasi atas dedikasi mereka.
        </p>

        <ul class="ab-rows">
            @foreach($fasilitas as [$judul, $deskripsi])
            <li class="ab-row">
                <h3>{{ $judul }}</h3>
                <p>{{ $deskripsi }}</p>
            </li>
            @endforeach
        </ul>
    </section>

</div>
@endsection