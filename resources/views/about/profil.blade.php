@extends('layouts.app')

@section('title', 'Profil Perusahaan — PT Sahabat Sawit')

@section('content')
@include('about._style')

<div class="ab">

    @include('about._hero', [
        'title' => 'Mengenal PT. Sahabat Sawit Rokan Sejahtera',
        'lead'  => 'Perusahaan perkebunan kelapa sawit yang tumbuh dan berkembang di Kabupaten Rokan Hilir, Provinsi Riau.',
    ])

    <section class="ab-sec">
        <div class="ab-split">
            <img class="ab-photo" src="{{ asset('images/herotentang.png') }}" alt="Tim PT Sahabat Sawit di area perkebunan" onerror="this.style.display='none'">
            <div>
                <p class="ab-label">Profil perusahaan</p>
                <h2 class="ab-h2">Berkembang Bersama Alam dan Masyarakat.</h2>
                <p class="ab-p">
                    PT. Sahabat Sawit Rokan Sejahtera adalah perusahaan yang bergerak di bidang perkebunan dan industri kelapa sawit di Kabupaten Rokan Hilir, Riau. Sejak berdiri, kami berkomitmen mengelola perkebunan secara profesional dan bertanggung jawab, sekaligus menjaga kelestarian lingkungan dan memberikan manfaat bagi masyarakat sekitar.
                </p>
                <p class="ab-p">
                    Kami percaya bahwa pertumbuhan bisnis yang berkelanjutan hanya dapat dicapai melalui keseimbangan antara produktivitas, tanggung jawab sosial, dan pelestarian lingkungan.
                </p>
            </div>
        </div>
    </section>

    <section class="ab-sec" id="awal-perjalanan">
        <div class="ab-split ab-split-top">
            <p class="ab-label">Awal perjalanan</p>
            <div>
                <h2 class="ab-h2">Latar Belakang Perusahaan</h2>

                <dl class="ab-facts">
                    <div><dt>2008</dt><dd>Awal berdiri di lokasi yang hingga kini menjadi tempat beroperasinya perusahaan</dd></div>
                    <div><dt>14 Juli 2023</dt><dd>Resmi mulai beroperasi</dd></div>
                </dl>

                <div class="ab-prose">
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
        </div>
    </section>

</div>
@endsection