@extends('layouts.app')

@section('title', 'Produk — PT Sahabat Sawit')

@section('content')

@include('products._hero')

<style>
    /* Semua section di bawah hero memakai gaya Home (gelap kehijauan). Class berawalan "pr-". */
    @import url('https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,600;12..96,800&display=swap');

    .pr {
        --hijau: #16321f; --hijau-muda: #2f5a3a; --emas: #e3a72f; --krem: #f2f4ec;
        font-family: "Bricolage Grotesque", system-ui, -apple-system, "Segoe UI", sans-serif;
        background: var(--hijau); color: var(--krem); line-height: 1.6;
    }
    .pr *, .pr *::before, .pr *::after { box-sizing: border-box; }
    .pr a { color: inherit; text-decoration: none; }
    .pr a:focus-visible, .pr button:focus-visible { outline: 3px solid var(--krem); outline-offset: 3px; }

    .pr-sec { --gutter: max(clamp(1.25rem, 5vw, 3.5rem), calc((100vw - 80rem) / 2)); padding: clamp(4rem, 10vh, 7rem) var(--gutter); }
    .pr-sec + .pr-sec { border-top: 1px solid rgba(242,244,236,.12); }
    .pr-head { display: flex; align-items: flex-end; justify-content: space-between; flex-wrap: wrap; gap: 1rem; }
    .pr-title { font-size: clamp(2.2rem, 6vw, 4.5rem); font-weight: 800; line-height: 1.02; letter-spacing: -.04em; max-width: 18ch; }
    .pr-label { font-size: .95rem; color: rgba(242,244,236,.6); }
    .pr-lead { margin-top: 1.25rem; max-width: 36rem; color: rgba(242,244,236,.75); }
    .pr-muted { color: rgba(242,244,236,.7); }
    .pr-mt { margin-top: clamp(2rem, 5vh, 3.5rem); }
    .pr-split { display: grid; grid-template-columns: 1fr; gap: 3rem; align-items: center; }
    @media (min-width: 900px) { .pr-split { grid-template-columns: 1fr 1fr; } }
    .pr-link { display: inline-flex; align-items: center; gap: .55rem; font-weight: 600; font-size: .95rem; border-bottom: 2px solid var(--emas); padding-bottom: .2rem; }
    .pr-link:hover { color: var(--emas); }
    .pr-photo { display: block; width: 100%; height: clamp(260px, 34vw, 420px); object-fit: cover; border-radius: .25rem; }

    /* Katalog */
    .pr-grid { display: grid; grid-template-columns: 1fr; gap: 2.5rem 1.5rem; }
    @media (min-width: 640px) { .pr-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (min-width: 1280px) { .pr-grid { grid-template-columns: repeat(4, 1fr); } }
    .pr-card { display: block; }
    .pr-card-img, .pr-step-img { aspect-ratio: 4 / 3; overflow: hidden; border-radius: .25rem; background: linear-gradient(145deg, var(--hijau-muda), #1d4129); }
    .pr-card-img img, .pr-step-img img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform .6s ease; }
    .pr-card:hover .pr-card-img img { transform: scale(1.05); }
    .pr-card-row { margin-top: 1rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem; }
    .pr-card-name { font-family: 'Archivo', sans-serif; font-weight: 900; font-style: italic; text-transform: uppercase; font-size: 1.15rem; line-height: 1.1; letter-spacing: -.01em; }
    .pr-plus { flex: none; width: 2rem; height: 2rem; border-radius: 50%; border: 1px solid rgba(242,244,236,.35); display: flex; align-items: center; justify-content: center; transition: background .25s, border-color .25s, color .25s; }
    .pr-plus svg { width: 1rem; height: 1rem; }
    .pr-card:hover .pr-plus { background: var(--emas); border-color: var(--emas); color: var(--hijau); }

    /* Proses: section menempel di layar; scroll ke bawah menggeser kartu ke samping, scroll ke atas normal */
    .pr-hs { --gutter: max(clamp(1.25rem, 5vw, 3.5rem), calc((100vw - 80rem) / 2)); position: relative; border-top: 1px solid rgba(242,244,236,.12); }
    .pr-hs + .pr-sec { border-top: 1px solid rgba(242,244,236,.12); }
    .pr-hs-pin { position: sticky; top: 0; min-height: 100vh; overflow: hidden; display: flex; flex-direction: column; justify-content: center; gap: clamp(1.25rem, 3vh, 2.5rem); padding: 7rem 0 2.5rem; }
    .pr-hs.is-flow .pr-hs-pin { position: static; }
    .pr-hs-head { padding: 0 var(--gutter); }
    .pr-hs-track { display: flex; gap: 1.25rem; width: max-content; padding: 0 var(--gutter); will-change: transform; }
    .pr-hs .pr-step { flex: 0 0 clamp(18rem, 32vw, 30rem); }
    .pr-hs .pr-step-img { aspect-ratio: auto; height: clamp(13rem, 40vh, 24rem); }
    .pr-step-no { margin-top: 1rem; font-size: .85rem; font-weight: 600; color: var(--emas); }
    .pr-step p { margin-top: .2rem; font-weight: 600; line-height: 1.35; }
    @media (max-height: 760px) { .pr-hs .pr-lead { display: none; } }
    /* Cadangan (kurangi gerakan / kartu muat di layar): baris biasa yang bisa digeser */
    .pr-hs.is-native .pr-hs-pin { position: static; min-height: 0; padding: clamp(4rem, 10vh, 7rem) 0; }
    .pr-hs.is-native .pr-hs-track { width: auto; overflow-x: auto; scrollbar-width: none; }

    /* Sertifikasi */
    .pr-certs { display: grid; grid-template-columns: 1fr; gap: 0 3rem; }
    @media (min-width: 700px) { .pr-certs { grid-template-columns: repeat(2, 1fr); } }
    .pr-cert { display: flex; align-items: center; gap: 1.25rem; padding: 1.25rem 0; border-top: 1px solid rgba(242,244,236,.15); }
    .pr-cert-logo { flex: none; width: 4rem; height: 4rem; padding: .4rem; border-radius: .25rem; background: var(--krem); }
    .pr-cert-logo img { width: 100%; height: 100%; object-fit: contain; display: block; }
    .pr-cert b { display: block; font-size: 1.15rem; font-weight: 700; }
    .pr-cert span { font-size: .9rem; color: rgba(242,244,236,.65); }

    /* Mengenal sawit */
    .pr-checks { list-style: none; margin: 2rem 0 0; padding: 0; display: grid; grid-template-columns: 1fr; gap: .75rem; }
    @media (min-width: 640px) { .pr-checks { grid-template-columns: 1fr 1fr; } }
    .pr-checks li { display: flex; align-items: center; gap: .6rem; }
    .pr-checks svg { flex: none; width: 1.1rem; height: 1.1rem; color: var(--emas); }

    /* Jenis & sebaran */
    .pr-types { display: grid; grid-template-columns: 1fr; gap: 2.5rem; }
    @media (min-width: 900px) { .pr-types { grid-template-columns: repeat(3, 1fr); } }
    .pr-type img { width: 9rem; height: 9rem; object-fit: cover; border-radius: .25rem; display: block; background: var(--hijau-muda); }
    .pr-type h3 { margin-top: 1rem; font-size: 1.5rem; font-weight: 700; letter-spacing: -.02em; }
    .pr-type p { margin-top: .5rem; color: rgba(242,244,236,.7); }
    .pr-regions { margin: 1.5rem 0 0; padding: 0; list-style: none; }
    .pr-region { display: grid; grid-template-columns: 3rem 1fr; gap: .25rem 1rem; padding: 1rem 0; border-top: 1px solid rgba(242,244,236,.15); }
    .pr-region:last-child { border-bottom: 1px solid rgba(242,244,236,.15); }
    .pr-rank { font-size: .85rem; font-weight: 600; color: rgba(242,244,236,.5); padding-top: .2rem; }
    .pr-region h4 { font-size: 1.15rem; font-weight: 700; display: flex; align-items: center; flex-wrap: wrap; gap: .75rem; }
    .pr-badge { font-size: .75rem; font-weight: 600; padding: .1rem .65rem; border-radius: 99px; background: var(--emas); color: var(--hijau); }
    .pr-region p { margin-top: .25rem; font-size: .95rem; color: rgba(242,244,236,.7); }
    .pr-links { margin-top: 2rem; display: flex; flex-wrap: wrap; gap: 1rem 2rem; }
</style>

<div class="pr">

    {{-- 1. KATALOG PRODUK --}}
    <section class="pr-sec">
        <h2 class="pr-title">Produk Unggulan Kami</h2>
        <p class="pr-lead">Berbagai hasil olahan kelapa sawit berkualitas tinggi, diproduksi dengan standar mutu terbaik.</p>

        <div class="pr-grid pr-mt">
            @forelse($products as $product)
            <a href="{{ route('products.show', $product->slug) }}" class="pr-card">
                <div class="pr-card-img">
                    @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" loading="lazy" onerror="this.style.display='none'">
                    @endif
                </div>
                <div class="pr-card-row">
                    <h3 class="pr-card-name">{{ $product->name }}</h3>
                    <span class="pr-plus" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    </span>
                    <span class="sr-only">Lihat detail {{ $product->name }}</span>
                </div>
            </a>
            @empty
            <p class="pr-muted">Belum ada produk yang ditambahkan.</p>
            @endforelse
        </div>
    </section>

    {{-- 2. PROSES: menempel saat scroll turun (kartu bergeser ke samping); saat scroll naik halaman bergerak normal --}}
    <section class="pr-hs" id="prProses">
        <div class="pr-hs-pin">
            <div class="pr-hs-head">
                <p class="pr-label">Dari Kebun ke Produk</p>
                <h2 class="pr-title" style="margin-top:.5rem;font-size:clamp(2rem,4.5vw,3.5rem)">Proses & Produk</h2>
                <p class="pr-lead">Setiap tahap diawasi dengan standar mutu ketat, mulai dari panen hingga produk siap didistribusikan.</p>
            </div>
            <div class="pr-hs-track" id="prProsesTrack">
                @foreach($processSteps as $step)
                <figure class="pr-step" style="margin:0">
                    <div class="pr-step-img">
                        <img src="{{ asset('images/' . $step['image']) }}" alt="{{ $step['alt'] }}" loading="lazy" onerror="this.style.display='none'">
                    </div>
                    <figcaption>
                        <p class="pr-step-no">{{ $step['step'] }}</p>
                        <p>{{ $step['label'] }}</p>
                    </figcaption>
                </figure>
                @endforeach
            </div>
        </div>
    </section>

    {{-- 3. SERTIFIKASI --}}
    <section class="pr-sec">
        <p class="pr-label">Sertifikasi</p>
        <h2 class="pr-title" style="margin-top:.5rem">Standar Mutu yang Diakui</h2>
        <div class="pr-certs pr-mt">
            <div class="pr-cert">
                <span class="pr-cert-logo"><img src="{{ asset('images/ispo.png') }}" alt="Sertifikasi ISPO" onerror="this.style.display='none'"></span>
                <div><b>Tersertifikasi ISPO</b><span>Indonesian Sustainable Palm Oil</span></div>
            </div>
            <div class="pr-cert">
                <span class="pr-cert-logo"><img src="{{ asset('images/halal.png') }}" alt="Sertifikasi Halal" onerror="this.style.display='none'"></span>
                <div><b>Tersertifikasi Halal</b><span>BPJPH - Kementerian Agama RI</span></div>
            </div>
        </div>
    </section>

    {{-- 4. MENGENAL KELAPA SAWIT --}}
    <section class="pr-sec">
        <div class="pr-split">
            <img class="pr-photo" src="{{ asset('images/sawitangkong.jpg') }}" alt="Mengenal Kelapa Sawit" onerror="this.style.display='none'">
            <div>
                <p class="pr-label">Mengenal Kelapa Sawit</p>
                <h2 class="pr-title" style="margin-top:.5rem;font-size:clamp(1.8rem,4vw,3rem)">Sumber Daya Alam dengan Segudang Manfaat</h2>
                <p class="pr-lead">
                    Kelapa sawit (<em>Elaeis guineensis</em>) adalah tanaman perkebunan tropis yang menjadi salah satu komoditas unggulan Indonesia.
                    Setiap tandan buah segar (TBS) diolah menjadi berbagai produk turunan bernilai tinggi, mulai dari minyak goreng,
                    minyak inti sawit (PKO), hingga bahan baku industri pangan dan non-pangan.
                </p>
                <p class="pr-lead" style="margin-top:1rem">
                    Melalui pengelolaan perkebunan yang berkelanjutan, PT Sahabat Sawit berkomitmen menghasilkan produk berkualitas
                    tinggi sekaligus menjaga kelestarian lingkungan dan kesejahteraan masyarakat sekitar.
                </p>
                <ul class="pr-checks">
                    @foreach(['Diolah dari TBS pilihan', 'Standar mutu terjaga', 'Perkebunan berkelanjutan', 'Ramah lingkungan'] as $poin)
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        {{ $poin }}
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </section>

    {{-- 5. JENIS & SEBARAN --}}
    <section class="pr-sec">
        <p class="pr-label">Jenis & Sebaran</p>
        <h2 class="pr-title" style="margin-top:.5rem">Jenis Kelapa Sawit dan Daerah Penghasil</h2>

        <div class="pr-types pr-mt">
            <div class="pr-type">
                <img src="{{ asset('images/tenera.png') }}" alt="Kelapa Sawit Jenis Tenera" onerror="this.style.display='none'">
                <h3>Tenera</h3>
                <p>Hasil persilangan Dura dan Pisifera. Cangkang tipis dengan rendemen minyak tinggi, menjadi jenis paling banyak dibudidayakan secara komersial.</p>
            </div>
            <div class="pr-type">
                <img src="{{ asset('images/dura.png') }}" alt="Kelapa Sawit Jenis Dura" onerror="this.style.display='none'">
                <h3>Dura</h3>
                <p>Memiliki cangkang tebal dan daging buah relatif tipis. Umumnya digunakan sebagai induk dalam program persilangan bibit unggul.</p>
            </div>
            <div class="pr-type">
                <img src="{{ asset('images/pisifera.png') }}" alt="Kelapa Sawit Jenis Pisifera" onerror="this.style.display='none'">
                <h3>Pisifera</h3>
                <p>Hampir tidak memiliki cangkang dengan daging buah tebal, namun produktivitas rendah sehingga jarang ditanam secara mandiri.</p>
            </div>
        </div>

        <div class="pr-split pr-mt" style="align-items:start">
            <img class="pr-photo" style="height:clamp(280px,40vw,480px)" src="{{ asset('images/peta.png') }}" alt="Peta Daerah Penghasil Kelapa Sawit di Indonesia" onerror="this.style.display='none'">
            <div>
                <p class="pr-label">Sebaran Wilayah</p>
                <h3 class="pr-title" style="margin-top:.5rem;font-size:clamp(1.6rem,3.2vw,2.4rem)">Daerah Penghasil Utama di Indonesia</h3>
                <p class="pr-lead">Indonesia merupakan produsen kelapa sawit terbesar di dunia, dengan sebagian besar perkebunan terkonsentrasi di Pulau Sumatera dan Kalimantan.</p>
                <ul class="pr-regions">
                    <li class="pr-region">
                        <span class="pr-rank">01</span>
                        <div>
                            <h4>Riau <span class="pr-badge">Lokasi Kebun Kami</span></h4>
                            <p>Provinsi dengan luas areal sawit terbesar di Indonesia, termasuk lokasi kebun PT. Sahabat Sawit Rokan Sejahtera.</p>
                        </div>
                    </li>
                    <li class="pr-region">
                        <span class="pr-rank">02</span>
                        <div><h4>Sumatera Utara</h4><p>Sentra sawit tertua dengan infrastruktur pengolahan yang matang.</p></div>
                    </li>
                    <li class="pr-region">
                        <span class="pr-rank">03</span>
                        <div><h4>Kalimantan Tengah & Barat</h4><p>Wilayah ekspansi perkebunan sawit terbesar dalam dua dekade terakhir.</p></div>
                    </li>
                    <li class="pr-region">
                        <span class="pr-rank">04</span>
                        <div><h4>Sumatera Selatan & Jambi</h4><p>Kontributor produksi sawit yang terus berkembang di kawasan Sumatera bagian selatan.</p></div>
                    </li>
                </ul>
            </div>
        </div>
    </section>

    {{-- 6. AJAKAN BERTINDAK --}}
    <section class="pr-sec">
        <h2 class="pr-title">Butuh penawaran atau sampel produk?</h2>
        <p class="pr-lead">Tim kami siap membantu kebutuhan Anda. Hubungi kami lewat WhatsApp atau halaman kontak.</p>
        <div class="pr-links">
            <a class="pr-link" href="https://wa.me/628139654581" target="_blank" rel="noopener noreferrer">WhatsApp</a>
            <a class="pr-link" href="{{ route('contact') }}">Halaman Kontak</a>
        </div>
    </section>

</div>

<script>
    // Proses: saat scroll KE BAWAH section menempel dan kartu bergeser ke samping.
    // Saat scroll KE ATAS pin dilepas: halaman bergerak normal dan kartu tidak ikut mundur.
    (function () {
        const sec = document.getElementById('prProses');
        const track = document.getElementById('prProsesTrack');
        if (!sec || !track) return;
        const pin = sec.querySelector('.pr-hs-pin');
        const reduce = window.matchMedia('(prefers-reduced-motion: reduce)');
        document.documentElement.style.overflowAnchor = 'none';

        let dist = 0, p = 0, expanded = true, native = false, lastY = window.scrollY;

        const secTop = function () { return sec.getBoundingClientRect().top + window.scrollY; };
        const jump = function (y) { window.scrollTo({ top: y, left: 0, behavior: 'instant' }); lastY = y; };
        const draw = function () { track.style.transform = 'translate3d(' + (-dist * p) + 'px,0,0)'; };
        function setExpanded(on) {
            expanded = on;
            sec.classList.toggle('is-flow', !on);
            sec.style.height = on ? (pin.offsetHeight + dist) + 'px' : '';
        }

        function onScroll() {
            if (native) return;
            const y = window.scrollY, dir = y - lastY;
            lastY = y;
            const top = secTop(), vh = window.innerHeight;

            if (expanded) {
                const s = y - top;
                if (dir < 0 && s > 0 && s < dist) {        // mulai naik di area pin: lepas pin
                    setExpanded(false); draw(); jump(top); return;
                }
                if (s <= 0) p = 0;
                else if (s >= dist) p = 1;
                else p = Math.max(p, s / dist);             // turun: kartu hanya maju
                draw();
            } else {
                const rel = top - y;                        // posisi atas section terhadap layar
                if (rel >= vh) { p = 0; draw(); setExpanded(true); }                       // section sudah di luar layar: ulang dari awal
                else if (dir > 0 && rel <= 0) { setExpanded(true); jump(top + p * dist); draw(); }  // turun lagi: lanjut dari posisi terakhir
            }
        }

        function layout() {
            sec.classList.remove('is-native', 'is-flow');
            sec.style.height = '';
            track.style.transform = '';
            dist = Math.max(0, track.scrollWidth - window.innerWidth);
            if (reduce.matches || dist < 8) { native = true; sec.classList.add('is-native'); return; }
            native = false;
            setExpanded(true);
            lastY = window.scrollY;
            onScroll();
        }

        window.addEventListener('scroll', onScroll, { passive: true });
        window.addEventListener('resize', layout);
        window.addEventListener('load', layout);
        layout();
    })();
</script>

@endsection