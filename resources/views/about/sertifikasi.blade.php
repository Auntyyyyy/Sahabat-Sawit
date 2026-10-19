@extends('layouts.app')

@section('title', 'Sertifikasi & Penghargaan — PT Sahabat Sawit')

@section('content')
@include('about._style')

<style>
    /* Sertifikat */
    .ab-certs { list-style: none; margin: clamp(2.5rem, 6vh, 4rem) 0 0; padding: 0; display: grid; grid-template-columns: 1fr; gap: 2.5rem 1.5rem; }
    @media (min-width: 640px)  { .ab-certs { grid-template-columns: repeat(2, 1fr); } }
    @media (min-width: 1024px) { .ab-certs { grid-template-columns: repeat(4, 1fr); } }
    .ab-cert-img { position: relative; display: flex; align-items: center; justify-content: center; width: 100%; aspect-ratio: 1 / 1; padding: 1.5rem; border: 0; border-radius: .25rem; background: var(--krem); cursor: zoom-in; overflow: hidden; }
    .ab-cert-img img { max-width: 100%; max-height: 100%; object-fit: contain; display: block; transition: transform .5s ease; }
    .ab-cert-img:hover img { transform: scale(1.04); }
    .ab-cert-year { position: absolute; top: .75rem; right: .75rem; padding: .15rem .7rem; border-radius: 99px; background: var(--hijau); color: var(--krem); font-size: .75rem; font-weight: 600; }
    .ab-cert-name { margin-top: 1rem; font-size: 1.15rem; font-weight: 700; letter-spacing: -.01em; line-height: 1.25; }
    .ab-cert-desc { margin-top: .25rem; font-size: .9rem; color: rgba(242,244,236,.65); }

    /* Penghargaan */
    .ab-awards { list-style: none; margin: clamp(2.5rem, 6vh, 4rem) 0 0; padding: 0; }
    .ab-award { display: grid; grid-template-columns: 1fr; gap: .4rem 2rem; padding: 1.5rem 0; border-top: 1px solid rgba(242,244,236,.15); }
    .ab-award:last-child { border-bottom: 1px solid rgba(242,244,236,.15); }
    .ab-award-year { font-size: .9rem; font-weight: 600; color: var(--emas); }
    .ab-award h3 { font-size: clamp(1.2rem, 2.2vw, 1.8rem); font-weight: 700; letter-spacing: -.02em; line-height: 1.2; }
    .ab-award-desc { color: rgba(242,244,236,.7); max-width: 32rem; }
    @media (min-width: 900px) { .ab-award { grid-template-columns: 7rem 1fr 1fr; align-items: baseline; } }

    /* Lightbox */
    .ab-lightbox { position: fixed; inset: 0; z-index: 100; display: flex; align-items: center; justify-content: center; padding: 2rem; background: rgba(5,8,5,.92); }
    .ab-lightbox[hidden] { display: none; }
    .ab-lightbox img { max-width: min(92vw, 60rem); max-height: 88vh; object-fit: contain; border-radius: .25rem; background: var(--krem); }
    .ab-lightbox-close { position: absolute; top: 1rem; right: 1.25rem; width: 44px; height: 44px; border: 1px solid rgba(242,244,236,.4); border-radius: 50%; background: transparent; color: var(--krem); font-size: 1.6rem; line-height: 1; cursor: pointer; }
    .ab-lightbox-close:hover { background: var(--emas); border-color: var(--emas); color: var(--hijau); }
</style>

<div class="ab">

    @include('about._hero', [
        'title' => 'Sertifikasi & Penghargaan',
        'lead'  => 'Bentuk pengakuan atas komitmen perusahaan dalam menerapkan standar, menjaga kualitas, dan menjalankan praktik usaha yang bertanggung jawab.',
        'crumb' => 'Sertifikasi & Penghargaan',
    ])

    {{-- SERTIFIKASI --}}
    <section class="ab-sec">
        <p class="ab-label">Legalitas</p>
        <h2 class="ab-h2">Sertifikasi</h2>
        <p class="ab-p">
            Komitmen kami terhadap standar mutu, keamanan, dan keberlanjutan dibuktikan melalui sertifikasi resmi berikut.
        </p>

        <ul class="ab-certs">
            @foreach($sertifikasi as $item)
            <li>
                <button type="button" class="ab-cert-img"
                        data-cert="{{ asset('images/' . $item['photo']) }}" data-alt="{{ $item['nama'] }}"
                        aria-label="Perbesar {{ $item['nama'] }}">
                    <img src="{{ asset('images/' . $item['photo']) }}" alt="{{ $item['nama'] }}" loading="lazy" onerror="this.style.display='none'">
                    @if(!empty($item['tahun']))
                        <span class="ab-cert-year">{{ $item['tahun'] }}</span>
                    @endif
                </button>
                <p class="ab-cert-name">{{ $item['nama'] }}</p>
                @if(!empty($item['deskripsi']))
                    <p class="ab-cert-desc">{{ $item['deskripsi'] }}</p>
                @endif
            </li>
            @endforeach
        </ul>
    </section>

    {{-- PENGHARGAAN --}}
    <section class="ab-sec">
        <p class="ab-label">Apresiasi</p>
        <h2 class="ab-h2">Penghargaan</h2>
        <p class="ab-p">
            Berbagai apresiasi yang kami terima atas kinerja, kualitas layanan, dan kontribusi perusahaan.
        </p>

        <ul class="ab-awards">
            @foreach($penghargaan as $item)
            <li class="ab-award">
                <p class="ab-award-year">{{ $item['tahun'] ?? '' }}</p>
                <h3>{{ $item['nama'] }}</h3>
                <p class="ab-award-desc">{{ $item['deskripsi'] ?? '' }}</p>
            </li>
            @endforeach
        </ul>
    </section>

    {{-- Lightbox: klik sertifikat untuk memperbesar; tutup dengan tombol, klik latar, atau Escape --}}
    <div class="ab-lightbox" id="abLightbox" role="dialog" aria-modal="true" aria-label="Sertifikat diperbesar" hidden>
        <button type="button" class="ab-lightbox-close" id="abLightboxClose" aria-label="Tutup">&times;</button>
        <img id="abLightboxImg" src="" alt="">
    </div>

</div>

<script>
    (function () {
        const box = document.getElementById('abLightbox');
        const img = document.getElementById('abLightboxImg');
        const closeBtn = document.getElementById('abLightboxClose');
        let trigger = null;

        function open(src, alt, from) {
            img.src = src; img.alt = alt;
            box.hidden = false;
            document.body.style.overflow = 'hidden';   // cegah scroll di belakang
            trigger = from;
            closeBtn.focus();
        }
        function close() {
            box.hidden = true; img.src = '';
            document.body.style.overflow = '';
            if (trigger) trigger.focus();
        }

        document.querySelectorAll('[data-cert]').forEach(function (b) {
            b.addEventListener('click', function () { open(b.dataset.cert, b.dataset.alt, b); });
        });
        box.addEventListener('click', function (e) { if (e.target !== img) close(); });
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !box.hidden) close(); });
    })();
</script>
@endsection