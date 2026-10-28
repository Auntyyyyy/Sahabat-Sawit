<style>
    /* Footer — versi putih ringkas, gaya disamakan dengan website baru (huruf tegas, garis tipis). Class berawalan "ft-". */
    @import url('https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,600;12..96,800&display=swap');

    footer.ft {
        --hijau: #16321f; --hijau-muda: #2f5a3a; --emas: #e3a72f; --krem: #f2f4ec;
        --gutter: max(clamp(1.25rem, 5vw, 3.5rem), calc((100vw - 80rem) / 2));
        position: relative; overflow: hidden; isolation: isolate;
        margin: 0; border: 0; box-shadow: none;
        padding: clamp(2.25rem, 5vh, 3.25rem) var(--gutter) 1.25rem;
        background: #fff; color: var(--hijau);
        font-family: "Bricolage Grotesque", system-ui, -apple-system, "Segoe UI", sans-serif; font-size: .9rem; line-height: 1.5;
    }
    .ft *, .ft *::before, .ft *::after { box-sizing: border-box; }
    .ft a { color: inherit; text-decoration: none; }
    .ft a:focus-visible { outline: 3px solid var(--hijau-muda); outline-offset: 3px; }

    .ft-main { display: grid; grid-template-columns: 1fr; gap: 1.75rem 2rem; }
    .ft-main > * { min-width: 0; }   /* cegah kolom melebar karena teks panjang */
    @media (min-width: 640px)  { .ft-main { grid-template-columns: repeat(2, 1fr); } }
    @media (min-width: 1024px) { .ft-main { grid-template-columns: 1.3fr .8fr 1.4fr 1.9fr; gap: 1.75rem 2.25rem; } }

    .ft-logo { display: inline-flex; align-items: center; gap: .6rem; }
    .ft-logo img { height: 36px; width: auto; display: block; }
    .ft-logo strong { display: block; font-size: 1rem; font-weight: 800; letter-spacing: -.01em; line-height: 1.15; }
    .ft-logo span { display: block; font-size: .75rem; color: rgba(22,50,31,.65); }
    .ft-desc { margin: .75rem 0 0; max-width: 22rem; font-size: .85rem; color: rgba(22,50,31,.7); }

    .ft-col h3 { margin: 0 0 .6rem; font-size: .85rem; font-weight: 600; color: rgba(22,50,31,.55); }
    .ft-col ul { list-style: none; margin: 0; padding: 0; }
    .ft-col li + li { margin-top: .3rem; }
    .ft-link { font-size: .9rem; font-weight: 600; transition: color .2s; }
    .ft-link:hover { color: var(--hijau-muda); text-decoration: underline; text-decoration-color: var(--emas); text-underline-offset: 3px; }
    .ft-col address { font-style: normal; font-size: .85rem; color: rgba(22,50,31,.78); }
    .ft-col address + address { margin-top: .7rem; }
    .ft-col address b { display: block; margin-bottom: .05rem; font-weight: 600; color: var(--hijau); }

    /* Kontak: email tidak terpotong di tengah kata; bila sempit, baris baru dimulai sebelum "@" */
    .ft-contact { display: block; font-size: .9rem; font-weight: 600; line-height: 1.4; overflow-wrap: break-word; transition: color .2s; }
    .ft-contact + .ft-contact { margin-top: .7rem; }
    .ft-contact small { display: block; margin-bottom: .05rem; font-size: .78rem; font-weight: 400; color: rgba(22,50,31,.55); }
    .ft-contact:hover { color: var(--hijau-muda); text-decoration: underline; text-decoration-color: var(--emas); text-underline-offset: 3px; }

    .ft-cert { margin-top: 1.75rem; padding-top: 1rem; border-top: 1px solid rgba(22,50,31,.15); display: flex; flex-wrap: wrap; align-items: center; gap: .75rem 1.25rem; }
    .ft-cert-label { font-size: .85rem; color: rgba(22,50,31,.55); }
    .ft-cert-logos { display: flex; flex-wrap: wrap; gap: .5rem; }
    .ft-cert-logos img { display: block; height: 2.5rem; width: auto; padding: .25rem; border-radius: .25rem; background: #fff; border: 1px solid rgba(22,50,31,.15); }

    .ft-bottom { margin-top: 1rem; padding-top: .9rem; border-top: 1px solid rgba(22,50,31,.15); display: grid; grid-template-columns: 1fr; gap: .5rem; text-align: center; font-size: .78rem; color: rgba(22,50,31,.6); }
    .ft-bottom p { margin: 0; }
    @media (min-width: 1024px) { .ft-bottom { grid-template-columns: 1fr auto 1fr; align-items: center; } .ft-copy { text-align: left; } .ft-legal { justify-content: flex-end; } }
    .ft-credit b { font-weight: 600; color: var(--hijau); }
    .ft-legal { display: flex; justify-content: center; gap: 1.25rem; }
    .ft-legal a:hover { color: var(--hijau-muda); text-decoration: underline; text-decoration-color: var(--emas); text-underline-offset: 3px; }
</style>

<footer class="ft footer-ssrs">
    <div class="ft-main">

        {{-- Logo & deskripsi --}}
        <div>
            <a href="{{ route('home') }}" class="ft-logo">
                <img src="{{ asset('images/logo.png') }}" alt="PT. Sahabat Sawit Rokan Sejahtera">
                <span><strong>Sahabat Sawit</strong><span>Rokan Sejahtera</span></span>
            </a>
            <p class="ft-desc">
                Perusahaan perkebunan dan industri kelapa sawit di Rokan Hilir, Riau, yang berkomitmen pada pertumbuhan berkelanjutan, kelestarian lingkungan, dan kesejahteraan masyarakat.
            </p>
        </div>

        {{-- Tautan cepat --}}
        <nav class="ft-col" aria-label="Tautan cepat">
            <h3>Tautan Cepat</h3>
            <ul>
                <li><a href="{{ route('about') }}" class="ft-link">Tentang Kami</a></li>
                <li><a href="{{ route('products') }}" class="ft-link">Produk</a></li>
                <li><a href="{{ route('sustainability') }}" class="ft-link">Keberlanjutan</a></li>
                <li><a href="{{ route('plantation') }}" class="ft-link">Perkebunan</a></li>
                <li><a href="{{ route('career') }}" class="ft-link">Karir</a></li>
            </ul>
        </nav>

        {{-- Alamat --}}
        <div class="ft-col">
            <h3>Alamat</h3>
            <address>
                <b>Kantor Pusat</b>
                Jl. Haji Misbah, Komplek Multatuli Indah Blok D No. 36. Kel. Hamdan, Kec. Medan Maimun, Kota Medan, Sumatera Utara
            </address>
            <address>
                <b>Site / Produksi</b>
                Jl. Lintas Medan KM 1, RT/RW 001/001 Kepenghuluan Sungai Meranti, Kec. Tanjung Medan, Kab. Rokan Hilir, Riau 28983
            </address>
        </div>

        {{-- Kontak --}}
        <div class="ft-col">
            <h3>Kontak</h3>
            <a href="mailto:ptsahabatsawitrokansejahtera@gmail.com" class="ft-contact">
                <small>Email</small>ptsahabatsawitrokansejahtera<wbr>@gmail.com
            </a>
            <a href="https://wa.me/628139654581" target="_blank" rel="noopener noreferrer" class="ft-contact">
                <small>WhatsApp</small>0813-965-4581
            </a>
        </div>

    </div>

    {{-- Sertifikasi --}}
    <div class="ft-cert">
        <span class="ft-cert-label">Sertifikasi Resmi</span>
        <div class="ft-cert-logos">
            <img src="{{ asset('images/halal.jpeg') }}" alt="Sertifikat Halal">
            <img src="{{ asset('images/ispo.jpeg') }}" alt="Sertifikat ISPO">
            <img src="{{ asset('images/tsi.jpeg') }}" alt="TSI">
            <img src="{{ asset('images/kan.jpeg') }}" alt="KAN">
        </div>
    </div>

    {{-- Baris bawah: copyright (kiri), credit developer (tengah), legal (kanan) --}}
    <div class="ft-bottom">
        <p class="ft-copy">&copy; {{ date('Y') }} PT Sahabat Sawit Rokan Sejahtera. All Rights Reserved.</p>

        <p class="ft-credit">Developed under the royal supervision of <b>Kanjeng Ratu</b></p>

        <div class="ft-legal">
            <a href="#">Privacy Policy</a>
            <a href="#">Terms &amp; Conditions</a>
        </div>
    </div>
</footer>