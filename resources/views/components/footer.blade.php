<footer class="footer-ssrs">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-x-16 gap-y-10">

            <!-- Logo & Deskripsi -->
            <div>
               <a href="{{ route('home') }}" class="footer-logo-wrap inline-flex items-center gap-3 mb-4">
                    <img src="{{ asset('images/logo.png') }}" alt="PT. Sahabat Sawit Rokan Sejahtera" class="footer-logo">
                    <span class="footer-brand-text flex flex-col leading-tight">
                        <span class="font-heading font-bold text-base md:text-lg text-white">Sahabat Sawit</span>
                        <span class="font-heading text-[11px] md:text-xs text-white/60 tracking-wide -mt-0.5">Rokan Sejahtera</span>
                    </span>
                </a>
                <p class="text-sm footer-text leading-relaxed text-justify">
                    Perusahaan perkebunan dan industri kelapa sawit di Rokan Hilir, Riau, yang berkomitmen pada pertumbuhan berkelanjutan, kelestarian lingkungan, dan kesejahteraan masyarakat.
                </p>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="font-heading font-semibold footer-heading mb-4">Tautan Cepat</h3>
                <ul class="space-y-2 text-sm footer-text">
                    <li><a href="{{ route('about') }}" class="footer-link">Tentang Kami</a></li>
                    <li><a href="{{ route('products') }}" class="footer-link">Produk</a></li>
                    <li><a href="{{ route('sustainability') }}" class="footer-link">Keberlanjutan</a></li>
                </ul>
            </div>

            <!-- Alamat -->
            <div>
                <h3 class="font-heading font-semibold footer-heading mb-4">Alamat</h3>
                    <ul class="space-y-4 text-sm footer-text">
                    <li>
                        <span class="block font-heading font-medium footer-addr-label mb-0.5">Kantor Pusat</span>
                        <span>Jl. Haji Misbah, Komplek Multatuli Indah Blok D No. 36. Kel. Hamdan, Kec. Medan Maimun, Kota Medan, Sumatera Utara</span>
                    </li>
                    <li>
                        <span class="block font-heading font-medium footer-addr-label mb-0.5">Site / Produksi</span>
                        <span>Jl. Lintas Medan KM 1, RT/RW 001/001 Kepenghuluan Sungai Meranti, Kec. Tanjung Medan, Kab. Rokan Hilir, Riau 28983</span>
                    </li>
                </ul>
            </div>

            <!-- Kontak -->
            <div>
                <h3 class="font-heading font-semibold footer-heading mb-4">Kontak</h3>
                <div class="space-y-3 text-sm footer-text">
                    <a href="mailto:ptsahabatsawitrokansejahtera@gmail.com"
                       class="flex items-center gap-2 footer-link">
                        <i class="bi bi-envelope-fill"></i>
                        <span>ptsahabatsawitrokansejahtera@gmail.com</span>
                    </a>
                    <a href="https://wa.me/628139654581"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="flex items-center gap-2 footer-link">
                        <i class="bi bi-telephone-fill"></i>
                        <span>0813-965-4581</span>
                    </a>
                </div>
            </div>

        </div>

        <!-- Logo Sertifikasi -->
        <div class="footer-cert mt-12 pt-8 border-t border-white/10">
            <span class="footer-cert-label">Sertifikasi Resmi</span>
            <div class="footer-cert-logos">
                <img src="{{ asset('images/halal.jpeg') }}" alt="Sertifikat Halal" class="footer-cert-logo">
                <img src="{{ asset('images/ispo.jpeg') }}" alt="Sertifikat ISPO" class="footer-cert-logo">
                <img src="{{ asset('images/tsi.jpeg') }}" alt="TSI" class="footer-cert-logo">
                <img src="{{ asset('images/kan.jpeg') }}" alt="KAN" class="footer-cert-logo">
            </div>
        </div>

        <div class="footer-bottom mt-8 pt-8 flex flex-col md:flex-row items-center justify-between gap-4 text-sm">
            <p>&copy; {{ date('Y') }} PT Sahabat Sawit Rokan Sejahtera. All Rights Reserved.</p>
            <div class="flex gap-6">
                <a href="#" class="footer-link">Privacy Policy</a>
                <a href="#" class="footer-link">Terms & Conditions</a>
            </div>
        </div>
    </div>
</footer>