@extends('layouts.app')

@section('title', 'Profil Perusahaan — PT Sahabat Sawit')

@section('content')
@include('about._style')

@php
    // Ringkasan cepat di bagian pengantar (semuanya diambil dari isi halaman ini).
    $ringkas = [
        ['Pendiri', 'Siswaja Muljadi'],
        ['Lokasi',  'Kabupaten Rokan Hilir, Riau'],
        ['Bidang',  'Perkebunan & industri kelapa sawit'],
    ];

    // Garis waktu: tahun besar, tanggal (opsional) di atasnya.
    $tonggak = [
        ['tahun' => '2008', 'tanggal' => null,      'teks' => 'Awal berdiri di lokasi yang hingga kini menjadi tempat beroperasinya perusahaan'],
        ['tahun' => '2023', 'tanggal' => '14 Juli', 'teks' => 'Resmi mulai beroperasi'],
    ];
@endphp

<style>
    /* Profil perusahaan — pengantar berfoto, garis waktu (terang), kisah dengan kutipan. Class berawalan "pf-". */
    .pf-sec { --pf-gutter: max(clamp(1.25rem, 5vw, 3.5rem), calc((100vw - 80rem) / 2)); padding: clamp(4rem, 10vh, 7rem) var(--pf-gutter); }
    .pf-dark { border-top: 1px solid rgba(242,244,236,.12); }
    .pf-light { background: #f2f4ec; color: #16321f; }

    .pf-label { font-size: .95rem; color: rgba(242,244,236,.6); }
    .pf-light .pf-label { color: rgba(22,50,31,.6); }
    .pf-h2 { margin-top: .5rem; max-width: 20ch; font-size: clamp(1.8rem, 4vw, 3rem); font-weight: 800; line-height: 1.05; letter-spacing: -.03em; }

    /* Pengantar: foto berbingkai emas + teks */
    .pf-intro { overflow-x: clip; }
    .pf-intro-grid { display: grid; grid-template-columns: minmax(0, 1fr); gap: 3.5rem; align-items: center; }
    @media (min-width: 900px) { .pf-intro-grid { grid-template-columns: minmax(0, 5fr) minmax(0, 6fr); gap: 5rem; } }
    .pf-photo { position: relative; isolation: isolate; margin: 0; }
    .pf-photo::before { content: ''; position: absolute; z-index: -1; inset: 1rem -1rem -1rem 1rem; border: 2px solid #e3a72f; border-radius: .5rem; }
    .pf-photo img { display: block; width: 100%; aspect-ratio: 4 / 5; max-height: 38rem; object-fit: cover; border-radius: .5rem; background: linear-gradient(145deg, #2f5a3a, #16321f); }
    .pf-badge { position: absolute; left: -.75rem; bottom: 1.75rem; padding: .7rem 1.1rem; border-radius: .25rem; background: #e3a72f; color: #16321f; box-shadow: 0 14px 30px rgba(0,0,0,.3); }
    .pf-badge small { display: block; font-size: .8rem; font-weight: 600; opacity: .8; }
    .pf-badge b { display: block; font-size: 1.9rem; font-weight: 800; line-height: 1; letter-spacing: -.03em; }

    .pf-lead { margin-top: 1.5rem; font-size: clamp(1.15rem, 1.8vw, 1.4rem); font-weight: 600; line-height: 1.45; letter-spacing: -.01em; color: #f2f4ec; }
    .pf-p { margin-top: 1.1rem; max-width: 38rem; color: rgba(242,244,236,.78); }
    .pf-tiles { margin: 2rem 0 0; padding: 0; display: grid; grid-template-columns: minmax(0, 1fr); gap: .75rem; }
    @media (min-width: 640px) { .pf-tiles { grid-template-columns: repeat(3, minmax(0, 1fr)); } }
    .pf-tile { padding: 1rem 1.1rem; border: 1px solid rgba(242,244,236,.14); border-radius: .5rem; background: rgba(242,244,236,.05); }
    .pf-tile dt { font-size: .85rem; color: rgba(242,244,236,.55); }
    .pf-tile dd { margin: .25rem 0 0; font-weight: 700; line-height: 1.3; }

    /* Garis waktu */
    .pf-timeline { margin: clamp(2.5rem, 6vh, 4rem) 0 0; display: grid; grid-template-columns: minmax(0, 1fr); gap: 2.5rem; }
    .pf-ms { position: relative; padding: 0 0 0 2rem; border-left: 2px solid rgba(22,50,31,.25); }
    .pf-ms::before { content: ''; position: absolute; left: -.55rem; top: .5rem; width: 1rem; height: 1rem; border-radius: 50%; background: #e3a72f; box-shadow: 0 0 0 5px #f2f4ec; }
    .pf-ms dt { margin: 0; }
    .pf-day { display: block; font-size: 1rem; font-weight: 600; color: rgba(22,50,31,.65); }
    .pf-year { display: block; font-size: clamp(3.5rem, 9vw, 6.5rem); font-weight: 800; line-height: 1; letter-spacing: -.05em; color: #16321f; }
    .pf-ms dd { margin: .75rem 0 0; max-width: 24rem; font-size: 1.1rem; color: rgba(22,50,31,.85); }
    @media (min-width: 800px) {
        .pf-timeline { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 3rem; }
        .pf-ms { padding: 2.25rem 0 0; border-left: 0; border-top: 2px solid rgba(22,50,31,.25); }
        .pf-ms::before { left: 0; top: -.55rem; }
    }

    /* Kisah perusahaan */
    .pf-story { display: grid; grid-template-columns: minmax(0, 1fr); gap: 2.5rem; }
    @media (min-width: 900px) { .pf-story { grid-template-columns: minmax(0, 5fr) minmax(0, 7fr); gap: 5rem; align-items: start; } .pf-sticky { position: sticky; top: 7rem; } }
    .pf-prose { max-width: 42rem; font-size: 1.08rem; line-height: 1.8; text-align: justify; text-justify: inter-word; hyphens: auto; color: rgba(242,244,236,.8); }
    .pf-prose p + p { margin-top: 1.4rem; }
    .pf-prose strong { color: #f2f4ec; }
    .pf-prose .pf-first { font-size: 1.3rem; line-height: 1.6; color: #f2f4ec; }
    .pf-quote { margin: 2rem 0; padding: .25rem 0 .25rem 1.5rem; border-left: 3px solid #e3a72f; text-align: left; hyphens: none; font-size: clamp(1.5rem, 3vw, 2.2rem); font-weight: 800; line-height: 1.2; letter-spacing: -.03em; color: #f2f4ec; }
    .pf-milestone { margin-top: 2rem; padding: 1.5rem; border: 1px solid rgba(227,167,47,.4); border-radius: .5rem; background: rgba(227,167,47,.08); }
    .pf-milestone p { margin: 0; color: rgba(242,244,236,.9); }
</style>

<div class="ab">

    @include('about._hero', [
        'title' => 'Mengenal PT. Sahabat Sawit Rokan Sejahtera',
        'lead'  => 'Perusahaan perkebunan kelapa sawit yang tumbuh dan berkembang di Kabupaten Rokan Hilir, Provinsi Riau.',
    ])

    {{-- PENGANTAR: foto berbingkai + profil singkat --}}
    <section class="pf-sec pf-intro">
        <div class="pf-intro-grid">
            <figure class="pf-photo">
                <img src="{{ asset('images/herotentang.png') }}" alt="Tim PT Sahabat Sawit di area perkebunan" onerror="this.closest('.pf-photo').style.display='none'">
                <figcaption class="pf-badge"><small>Berdiri sejak</small><b>2008</b></figcaption>
            </figure>

            <div>
                <p class="pf-label">Profil perusahaan</p>
                <h2 class="pf-h2">Berkembang Bersama Alam dan Masyarakat.</h2>
                <p class="pf-lead">
                    PT. Sahabat Sawit Rokan Sejahtera adalah perusahaan yang bergerak di bidang perkebunan dan industri kelapa sawit di Kabupaten Rokan Hilir, Riau. Sejak berdiri, kami berkomitmen mengelola perkebunan secara profesional dan bertanggung jawab, sekaligus menjaga kelestarian lingkungan dan memberikan manfaat bagi masyarakat sekitar.
                </p>
                <p class="pf-p">
                    Kami percaya bahwa pertumbuhan bisnis yang berkelanjutan hanya dapat dicapai melalui keseimbangan antara produktivitas, tanggung jawab sosial, dan pelestarian lingkungan.
                </p>

                <dl class="pf-tiles">
                    @foreach($ringkas as [$k, $v])
                    <div class="pf-tile"><dt>{{ $k }}</dt><dd>{{ $v }}</dd></div>
                    @endforeach
                </dl>
            </div>
        </div>
    </section>

    {{-- GARIS WAKTU (section terang sebagai jeda) --}}
    <section class="pf-sec pf-light" id="awal-perjalanan">
        <p class="pf-label">Awal perjalanan</p>
        <h2 class="pf-h2">Latar Belakang Perusahaan</h2>

        <dl class="pf-timeline">
            @foreach($tonggak as $t)
            <div class="pf-ms">
                <dt>
                    @if($t['tanggal'])<span class="pf-day">{{ $t['tanggal'] }}</span>@endif
                    <span class="pf-year">{{ $t['tahun'] }}</span>
                </dt>
                <dd>{{ $t['teks'] }}</dd>
            </div>
            @endforeach
        </dl>
    </section>

    {{-- KISAH PERUSAHAAN --}}
    <section class="pf-sec pf-dark">
        <div class="pf-story">
            <div class="pf-sticky">
                <p class="pf-label">Perjalanan kami</p>
                <h2 class="pf-h2">Dari Langkah Sederhana</h2>
            </div>

            <div class="pf-prose" lang="id">
                <p class="pf-first">
                    Perjalanan <strong>PT. Sahabat Sawit Rokan Sejahtera (SSRS)</strong> berawal dari sebuah langkah sederhana yang penuh keyakinan. Didirikan oleh Siswaja Muljadi, PT. SSRS mulai berdiri di lokasi yang hingga kini menjadi tempat beroperasinya perusahaan sejak tahun 2008.
                </p>

                <blockquote class="pf-quote">“Sebuah langkah sederhana yang penuh keyakinan.”</blockquote>

                <p>
                    Sebelum mendirikan PT. SSRS, Siswaja Muljadi telah lebih dahulu berkecimpung dalam dunia kelapa sawit sebagai pemasok Tandan Buah Segar (TBS) kepada PT. Sawit Riau Makmur, salah satu Pabrik Kelapa Sawit (PKS) yang berlokasi di Desa Teluk Mega, Kecamatan Tanah Putih, Kabupaten Rokan Hilir.
                </p>
                <p>
                    Berbekal pengalaman, pemahaman terhadap industri kelapa sawit, serta semangat untuk terus berkembang, perjalanan tersebut kemudian menjadi titik awal berdirinya PT. SSRS. Dari sebuah langkah awal yang sederhana, perusahaan terus bertumbuh dan membangun fondasi untuk menjadi bagian dari perkembangan industri kelapa sawit di Kabupaten Rokan Hilir.
                </p>

                <div class="pf-milestone">
                    <p>
                        Setelah melalui berbagai proses perkembangan, <strong>PT. Sahabat Sawit Rokan Sejahtera resmi mulai beroperasi pada 14 Juli 2023</strong>, menjadi salah satu tonggak penting dalam perjalanan dan perkembangan perusahaan.
                    </p>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection