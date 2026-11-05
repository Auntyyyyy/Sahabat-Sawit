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

    // Visi: teks lengkap + kata kunci yang tampil sebagai label kecil di bawahnya (sesuaikan bila isi visi berubah).
    $visi  = 'Menjadi perusahaan PKS dengan standar ISPO dan RSPO berskala internasional, berteknologi tinggi, dan berwawasan lingkungan.';
    $pilar = ['Standar ISPO & RSPO', 'Berskala internasional', 'Berteknologi tinggi', 'Berwawasan lingkungan'];

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

    // Bila jumlah fasilitas menyisakan 3 kartu di baris terakhir (grid 4 kolom), kartu pertama dilebarkan agar baris terisi penuh.
    $kartuPertamaLebar = count($fasilitas) % 4 === 3;

    // Ikon fasilitas (urutannya sama dengan $fasilitas di atas). Garis sederhana 24x24.
    $ikon = [
        'M12 20.5s-7.5-4.6-9.5-9.3A5.2 5.2 0 0112 6.6a5.2 5.2 0 019.5 4.6c-2 4.7-9.5 9.3-9.5 9.3z',                       // kesehatan: hati
        'M12 4L2 9l10 5 10-5-10-5zM6 11.5V16c0 1.4 2.7 3 6 3s6-1.6 6-3v-4.5',                                              // pelatihan: topi wisuda
        'M3 11l9-8 9 8M5 9.5V20h14V9.5M10 20v-6h4v6',                                                                      // mess: rumah
        'M4 6a2 2 0 012-2h12a2 2 0 012 2v10H4V6zM4 11h16M7.5 16v3M16.5 16v3M8 14h.01M16 14h.01',                           // transportasi: bus
        'M12 3l8 3v6c0 4.4-3.2 8-8 9-4.8-1-8-4.6-8-9V6l8-3zM9 12l2 2 4-4',                                                 // K3: perisai
        'M3 8a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V8zM3 8l12-4v2M16 14h3',                            // koperasi: dompet
        'M4 11h16v9H4v-9zM3 8h18v3H3V8zM12 8v12M12 8C9.5 8 8 6.8 8 5.5S9.8 3 12 8c2.2-5 4-4.5 4-2.5S14.5 8 12 8z',          // THR: hadiah
    ];
@endphp

<style>
    /* Nilai perusahaan — visi & misi (gelap, satu alur), fasilitas (terang, satu-satunya jeda terang). Class berawalan "nl-". */
    .nl-sec { --nl-gutter: max(clamp(1.25rem, 5vw, 3.5rem), calc((100vw - 80rem) / 2)); padding: clamp(3rem, 8vh, 5rem) var(--nl-gutter); scroll-margin-top: calc(var(--nav-h) + var(--tabs-h)); }
    .nl-dark { border-top: 1px solid rgba(242,244,236,.12); }
    .nl-light { background: #f2f4ec; color: #16321f; }

    .nl-label { font-size: .95rem; color: rgba(242,244,236,.6); }
    .nl-h2 { margin-top: .5rem; max-width: 20ch; font-size: clamp(1.8rem, 4vw, 3rem); font-weight: 800; line-height: 1.05; letter-spacing: -.03em; }
    .nl-p { margin-top: 1.25rem; max-width: 40rem; color: rgba(242,244,236,.78); }
    .nl-light .nl-label { color: rgba(22,50,31,.6); }
    .nl-light .nl-p { color: rgba(22,50,31,.8); }

    /* Visi */
    .nl-quote { display: block; margin-top: clamp(1.5rem, 4vh, 2.5rem); font-size: clamp(4.5rem, 10vw, 8rem); font-weight: 800; line-height: .6; color: #e3a72f; }
    .nl-vision { margin-top: .5rem; max-width: 26em; font-size: clamp(1.8rem, 4.2vw, 3.4rem); font-weight: 800; line-height: 1.12; letter-spacing: -.03em; }
    .nl-pillars { list-style: none; margin: clamp(1.5rem, 4vh, 2.5rem) 0 0; padding: 0; display: flex; flex-wrap: wrap; gap: .6rem; }
    .nl-pillars li { display: inline-flex; align-items: center; gap: .5rem; padding: .35rem 1rem; border: 1px solid rgba(242,244,236,.3); border-radius: 99px; font-size: .9rem; font-weight: 600; }
    .nl-pillars li::before { content: ''; width: .5rem; height: .5rem; border-radius: 50%; background: #e3a72f; }

    /* Misi (satu alur dengan visi, dipisah garis tipis) */
    .nl-misi { margin-top: clamp(3rem, 8vh, 4.5rem); padding-top: clamp(2rem, 5vh, 3rem); border-top: 1px solid rgba(242,244,236,.12); }
    .nl-h3-row { display: flex; flex-wrap: wrap; align-items: baseline; gap: .5rem 1rem; }
    .nl-h3 { font-size: clamp(1.6rem, 3.2vw, 2.4rem); font-weight: 800; line-height: 1.05; letter-spacing: -.03em; }
    .nl-count { font-size: .95rem; color: rgba(242,244,236,.55); }
    .nl-missions { list-style: none; margin: clamp(1.5rem, 4vh, 2.25rem) 0 0; padding: 0; display: grid; grid-template-columns: minmax(0, 1fr); gap: 1rem; }
    @media (min-width: 700px)  { .nl-missions { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    @media (min-width: 1100px) { .nl-missions { grid-template-columns: repeat(3, minmax(0, 1fr)); } }
    .nl-mission { position: relative; padding: 1.5rem 1.5rem 1.75rem; border: 1px solid rgba(242,244,236,.14); border-radius: .5rem; background: rgba(242,244,236,.045); transition: transform .25s, border-color .25s, background .25s; }
    .nl-mission::before { content: ''; position: absolute; left: 1.5rem; top: -1px; width: 2.5rem; height: 3px; background: #e3a72f; }
    .nl-no { display: block; font-size: 2.4rem; font-weight: 800; line-height: 1; letter-spacing: -.04em; color: #e3a72f; }
    .nl-mission p { margin-top: 1rem; color: rgba(242,244,236,.85); }

    /* Fasilitas (section terang) */
    .nl-facs { list-style: none; margin: clamp(2rem, 5vh, 3rem) 0 0; padding: 0; display: grid; grid-template-columns: minmax(0, 1fr); gap: 1rem; }
    @media (min-width: 640px)  { .nl-facs { grid-template-columns: repeat(2, minmax(0, 1fr)); } .nl-facs--wide .nl-fac:first-child { grid-column: span 2; } }
    @media (min-width: 1000px) { .nl-facs { grid-template-columns: repeat(4, minmax(0, 1fr)); } }
    .nl-fac { padding: 1.5rem; border: 1px solid rgba(22,50,31,.12); border-radius: .5rem; background: #fff; transition: transform .25s, border-color .25s, box-shadow .25s; }
    .nl-ico { display: flex; align-items: center; justify-content: center; width: 3rem; height: 3rem; border-radius: .5rem; background: rgba(227,167,47,.18); border: 1px solid rgba(154,106,5,.35); color: #9a6a05; }
    .nl-ico svg { width: 1.5rem; height: 1.5rem; }
    .nl-fac h3 { margin-top: 1.1rem; font-size: 1.2rem; font-weight: 700; letter-spacing: -.02em; line-height: 1.2; }
    .nl-fac p { margin-top: .5rem; font-size: .95rem; color: rgba(22,50,31,.72); }

    @media (hover: hover) and (prefers-reduced-motion: no-preference) {
        .nl-mission:hover { transform: translateY(-4px); background: rgba(242,244,236,.09); border-color: rgba(227,167,47,.55); }
        .nl-fac:hover { transform: translateY(-4px); border-color: rgba(47,90,58,.45); box-shadow: 0 14px 30px rgba(22,50,31,.12); }
    }
</style>

<div class="ab">

    @include('about._hero', [
        'title' => 'Nilai Perusahaan',
        'lead'  => 'Nilai-nilai yang menjadi pedoman dalam setiap langkah dan keputusan perusahaan.',
        'crumb' => 'Nilai Perusahaan',
    ])

    {{-- VISI & MISI: satu alur — visi sebagai pernyataan besar, misi langsung di bawahnya --}}
    <section class="nl-sec nl-dark">
        <p class="nl-label">Arah kami</p>
        <h2 class="nl-h2">Visi & Misi</h2>

        <span class="nl-quote" aria-hidden="true">&ldquo;</span>
        <p class="nl-vision">{{ $visi }}</p>

        <ul class="nl-pillars" aria-label="Kata kunci visi">
            @foreach($pilar as $p)
                <li>{{ $p }}</li>
            @endforeach
        </ul>

        <div class="nl-misi">
            <div class="nl-h3-row">
                <h3 class="nl-h3">Misi</h3>
                <span class="nl-count">{{ count($misi) }} langkah</span>
            </div>

            <ol class="nl-missions">
                @foreach($misi as $item)
                <li class="nl-mission">
                    <span class="nl-no" aria-hidden="true">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                    <p>{{ $item }}</p>
                </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- FASILITAS & KESEJAHTERAAN KARYAWAN (section terang sebagai jeda) --}}
    <section class="nl-sec nl-light">
        <p class="nl-label">Kepedulian kami</p>
        <h2 class="nl-h2">Fasilitas & Kesejahteraan Karyawan</h2>
        <p class="nl-p">
            Kami berkomitmen menjaga kesejahteraan seluruh karyawan melalui berbagai fasilitas dan program pendukung, sebagai bentuk apresiasi atas dedikasi mereka.
        </p>

        <ul class="nl-facs{{ $kartuPertamaLebar ? ' nl-facs--wide' : '' }}">
            @foreach($fasilitas as [$judul, $deskripsi])
            <li class="nl-fac">
                <span class="nl-ico" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $ikon[$loop->index] ?? $ikon[0] }}"/></svg>
                </span>
                <h3>{{ $judul }}</h3>
                <p>{{ $deskripsi }}</p>
            </li>
            @endforeach
        </ul>
    </section>

</div>
@endsection