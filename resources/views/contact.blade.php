@extends('layouts.app')

@section('title', 'Kontak Kami - PT. Sahabat Sawit Rokan Sejahtera')

@section('content')

{{-- ============================= --}}
{{-- HERO SECTION - HALAMAN KONTAK --}}
{{-- ============================= --}}
<section class="relative overflow-hidden bg-dark-green py-28 md:py-36 lg:py-40">

    {{-- Foto latar full-bleed --}}
    {{-- TODO: ganti path gambar di bawah dengan foto asli kebun/operasional perusahaan --}}
    <div class="absolute inset-0">
        <img src="{{ asset('images/prosesnunggu.png') }}"
             alt="Perkebunan Kelapa Sawit PT. Sahabat Sawit Rokan Sejahtera"
             class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-dark-green/95 via-dark-green/70 to-dark-green/20"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-dark-green/60 via-transparent to-transparent"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

            {{-- Kolom Teks --}}
            <div class="text-left">

                <!-- Breadcrumb -->
                <div class="contact-fade contact-delay-1 flex items-center gap-2 text-sm text-white/60 mb-6">
                    <a href="{{ url('/') }}" class="hover:text-white transition-colors">Beranda</a>
                    <span>/</span>
                    <span class="text-white/90">Kontak</span>
                </div>

                <span class="contact-fade contact-delay-2 block font-heading font-semibold text-light-green uppercase text-sm tracking-widest">Kontak Kami</span>

                <h1 class="contact-fade contact-delay-3 mt-4 font-heading font-extrabold text-4xl md:text-5xl lg:text-[3.25rem] leading-[1.1] text-white uppercase">
                    Terbuka untuk<br>
                    Kerja Sama &amp;<br>
                    Kemitraan Sawit
                </h1>

                <p class="contact-fade contact-delay-4 mt-6 max-w-md text-white/80 leading-relaxed text-base md:text-lg">
                    Kami terbuka untuk menjalin kerja sama, kemitraan, maupun menjawab pertanyaan
                    seputar PT. Sahabat Sawit Rokan Sejahtera. Tim kami siap membantu Anda.
                </p>

                <!-- CTA Utama Hero -->
                <div class="contact-fade contact-delay-5 flex flex-wrap items-center gap-4 mt-9">
                    <a href="https://wa.me/628139654581"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="inline-flex items-center gap-2.5 bg-[#25D366] hover:bg-[#1EBE5A] text-white font-heading font-semibold text-sm md:text-base pl-6 pr-7 py-3.5 rounded-full shadow-lg shadow-[#25D366]/25 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">
                        <i class="bi bi-whatsapp text-lg"></i>
                        <span>Hubungi Kami</span>
                    </a>
                    <a href="mailto:info@sahabatsawitrokan.co.id"
                       class="inline-flex items-center gap-2.5 text-white/90 hover:text-white font-heading font-semibold text-sm md:text-base transition-colors">
                        <span class="w-10 h-10 rounded-full border border-white/30 flex items-center justify-center">
                            <i class="bi bi-envelope-fill text-sm"></i>
                        </span>
                        <span>Kirim Email</span>
                    </a>
                </div>
            </div>

            {{-- Kolom Foto Bertumpuk --}}
            <div class="contact-fade contact-delay-6 relative hidden lg:block h-[420px]">
                {{-- TODO: ganti kedua path gambar berikut dengan foto asli (hasil panen & pekerja/operasional) --}}
                <div class="absolute top-0 right-16 w-56 h-72 rounded-brand bg-white/10 border border-white/20 rotate-3 shadow-2xl overflow-hidden">
                    <img src="{{ asset('images/hero/hasil-sawit.jpg') }}" alt="Hasil Kelapa Sawit" class="w-full h-full object-cover">
                </div>
                <div class="absolute bottom-0 right-0 w-64 h-80 rounded-brand -rotate-2 shadow-2xl overflow-hidden border-4 border-white/10">
                    <img src="{{ asset('images/hero/pekerja-sawit.jpg') }}" alt="Pekerja PT. Sahabat Sawit Rokan Sejahtera" class="w-full h-full object-cover">
                </div>
            </div>
        </div>
    </div>

    {{-- Lengkung putih di bagian bawah hero --}}
    <div class="absolute bottom-0 left-0 w-full leading-none pointer-events-none">
        <svg viewBox="0 0 1440 100" class="w-full h-16 md:h-24" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0,100 C280,10 620,100 1440,30 L1440,100 L0,100 Z" fill="var(--color-body-bg, #ffffff)"></path>
        </svg>
    </div>
</section>

{{-- ============================= --}}
{{-- KANAL KONTAK --}}
{{-- ============================= --}}
<section class="relative -mt-6 md:-mt-10 z-10">
    <div class="max-w-5xl mx-auto px-6">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 md:gap-6">

            <a href="https://wa.me/628139654581" target="_blank" rel="noopener noreferrer"
               class="group bg-white rounded-brand border border-dark-green/10 shadow-lg p-7 text-center hover:-translate-y-1.5 hover:shadow-xl transition-all duration-300">
                <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-[#25D366]/10 text-[#25D366] flex items-center justify-center text-2xl group-hover:bg-[#25D366] group-hover:text-white transition-colors duration-300">
                    <i class="bi bi-whatsapp"></i>
                </div>
                <h3 class="font-heading font-bold text-dark-green mb-1.5">WhatsApp</h3>
                <p class="font-body text-sm text-dark-text/70 mb-1">Respon tercepat untuk pertanyaan & kerja sama</p>
                <span class="font-body text-sm text-secondary-green font-semibold">+62 813-9654-581</span>
            </a>

            <a href="mailto:info@sahabatsawitrokan.co.id"
               class="group bg-white rounded-brand border border-dark-green/10 shadow-lg p-7 text-center hover:-translate-y-1.5 hover:shadow-xl transition-all duration-300">
                <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-secondary-green/10 text-secondary-green flex items-center justify-center text-2xl group-hover:bg-secondary-green group-hover:text-white transition-colors duration-300">
                    <i class="bi bi-envelope-fill"></i>
                </div>
                <h3 class="font-heading font-bold text-dark-green mb-1.5">Email</h3>
                <p class="font-body text-sm text-dark-text/70 mb-1">Untuk surat resmi, proposal, dan dokumen kerja sama</p>
                <span class="font-body text-sm text-secondary-green font-semibold">info@sahabatsawitrokan.co.id</span>
            </a>

            <div class="bg-white rounded-brand border border-dark-green/10 shadow-lg p-7 text-center hover:-translate-y-1.5 hover:shadow-xl transition-all duration-300">
                <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-secondary-green/10 text-secondary-green flex items-center justify-center text-2xl">
                    <i class="bi bi-clock-fill"></i>
                </div>
                <h3 class="font-heading font-bold text-dark-green mb-1.5">Jam Operasional</h3>
                <p class="font-body text-sm text-dark-text/70 mb-1">Senin – Jumat</p>
                <span class="font-body text-sm text-secondary-green font-semibold">08.00 – 17.00 WIB</span>
            </div>

        </div>
    </div>
</section>

{{-- ============================= --}}
{{-- INFORMASI LOKASI + MAPS (TAB) --}}
{{-- ============================= --}}
<section class="py-20 bg-body-bg">
    <div class="max-w-5xl mx-auto px-6">

        <div class="text-center mb-10">
            <span class="font-heading font-semibold text-secondary-green uppercase text-sm tracking-widest">Lokasi Kami</span>
            <h2 class="mt-3 font-heading font-bold text-3xl md:text-4xl text-dark-green">Titik Lokasi di Peta</h2>
        </div>

        {{-- Tab Switcher --}}
        <div class="flex justify-center mb-8">
            <div class="inline-flex bg-white border border-dark-green/10 rounded-full p-1.5 shadow-sm" id="map-tabs">
                <button type="button"
                        class="map-tab-btn active px-6 py-2.5 rounded-full font-heading font-semibold text-sm transition-all duration-300"
                        data-target="kantor-pusat">
                    Kantor Pusat
                </button>
                <button type="button"
                        class="map-tab-btn px-6 py-2.5 rounded-full font-heading font-semibold text-sm transition-all duration-300"
                        data-target="site-operasional">
                    Site / Operasional
                </button>
            </div>
        </div>

        {{-- Kotak Maps + Kartu Info --}}
        <div class="relative rounded-brand border border-dark-green/10 overflow-hidden shadow-sm">

            {{-- Kartu Info (overlay kiri atas) --}}
            <div class="absolute top-4 left-4 z-10 w-[280px] max-w-[80%] bg-white rounded-brand shadow-lg p-5" id="map-info-card">

                <div id="info-kantor-pusat" class="map-info-panel">
                    <div class="flex items-start justify-between gap-2">
                        <h3 class="font-heading font-bold text-dark-green leading-snug">Kantor Pusat</h3>
                        <div class="flex items-center gap-1.5 shrink-0">
                            <a href="https://www.google.com/maps?q=Jl.+Haji+Misbah,+Komplek+Multatuli+Indah+Blok+D+No.+36,+Kel.+Hamdan,+Kec.+Medan+Maimun,+Kota+Medan,+Sumatera+Utara"
                               target="_blank" rel="noopener noreferrer"
                               class="w-8 h-8 rounded-full bg-body-bg flex items-center justify-center text-dark-green hover:bg-secondary-green hover:text-white transition-colors">
                                <i class="bi bi-box-arrow-up-right text-sm"></i>
                            </a>
                            <a href="https://www.google.com/maps/dir/?api=1&destination=Jl.+Haji+Misbah,+Komplek+Multatuli+Indah+Blok+D+No.+36,+Kel.+Hamdan,+Kec.+Medan+Maimun,+Kota+Medan,+Sumatera+Utara"
                               target="_blank" rel="noopener noreferrer"
                               class="w-8 h-8 rounded-full bg-secondary-green flex items-center justify-center text-white hover:bg-dark-green transition-colors">
                                <i class="bi bi-signpost-split-fill text-sm"></i>
                            </a>
                        </div>
                    </div>
                    <p class="mt-2 font-body text-sm text-dark-text/80 leading-relaxed">
                        Jl. Haji Misbah, Komplek Multatuli Indah Blok D No. 36, Kel. Hamdan,
                        Kec. Medan Maimun, Kota Medan, Sumatera Utara
                    </p>
                </div>

                <div id="info-site-operasional" class="map-info-panel hidden">
                    <div class="flex items-start justify-between gap-2">
                        <h3 class="font-heading font-bold text-dark-green leading-snug">Site / Operasional</h3>
                        <div class="flex items-center gap-1.5 shrink-0">
                            <a href="https://www.google.com/maps?q=Jl.+Lintas+Medan+KM+1,+Kepenghuluan+Sungai+Meranti,+Kec.+Tanjung+Medan,+Kab.+Rokan+Hilir,+Riau+28983"
                               target="_blank" rel="noopener noreferrer"
                               class="w-8 h-8 rounded-full bg-body-bg flex items-center justify-center text-dark-green hover:bg-secondary-green hover:text-white transition-colors">
                                <i class="bi bi-box-arrow-up-right text-sm"></i>
                            </a>
                            <a href="https://www.google.com/maps/dir/?api=1&destination=Jl.+Lintas+Medan+KM+1,+Kepenghuluan+Sungai+Meranti,+Kec.+Tanjung+Medan,+Kab.+Rokan+Hilir,+Riau+28983"
                               target="_blank" rel="noopener noreferrer"
                               class="w-8 h-8 rounded-full bg-secondary-green flex items-center justify-center text-white hover:bg-dark-green transition-colors">
                                <i class="bi bi-signpost-split-fill text-sm"></i>
                            </a>
                        </div>
                    </div>
                    <p class="mt-2 font-body text-sm text-dark-text/80 leading-relaxed">
                        Jl. Lintas Medan KM 1, RT/RW 001/001 Kepenghuluan Sungai Meranti,
                        Kec. Tanjung Medan, Kab. Rokan Hilir, Riau 28983
                    </p>
                </div>
            </div>

            {{-- Iframe Peta --}}
            <div class="w-full h-[480px]">
                <iframe
                    id="map-frame-kantor-pusat"
                    src="https://www.google.com/maps?q=Jl.+Haji+Misbah,+Komplek+Multatuli+Indah+Blok+D+No.+36,+Kel.+Hamdan,+Kec.+Medan+Maimun,+Kota+Medan,+Sumatera+Utara&output=embed"
                    class="map-frame w-full h-full border-0"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    title="Peta Lokasi Kantor Pusat PT. Sahabat Sawit Rokan Sejahtera">
                </iframe>
                <iframe
                    id="map-frame-site-operasional"
                    src="https://www.google.com/maps?q=Jl.+Lintas+Medan+KM+1,+Kepenghuluan+Sungai+Meranti,+Kec.+Tanjung+Medan,+Kab.+Rokan+Hilir,+Riau+28983&output=embed"
                    class="map-frame w-full h-full border-0 hidden"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    title="Peta Lokasi Site Operasional PT. Sahabat Sawit Rokan Sejahtera">
                </iframe>
            </div>
        </div>
    </div>
</section>

{{-- ============================= --}}
{{-- FAQ SECTION --}}
{{-- ============================= --}}
<section class="py-20 bg-cream">
    <div class="max-w-3xl mx-auto px-6">
        <div class="text-center mb-14">
            <span class="font-heading font-semibold text-secondary-green uppercase text-sm tracking-widest">FAQ</span>
            <h2 class="mt-3 font-heading font-bold text-3xl md:text-4xl text-dark-green">Pertanyaan yang Sering Diajukan</h2>
            <p class="mt-4 text-dark-text/80 leading-relaxed">
                Beberapa hal yang paling sering ditanyakan seputar perusahaan dan kerja sama dengan kami.
                Tidak menemukan jawaban yang Anda cari? Hubungi kami langsung via WhatsApp.
            </p>
        </div>

        <div class="space-y-4" id="faq-accordion">

            <div class="faq-item bg-white rounded-brand border border-dark-green/10 overflow-hidden">
                <button type="button" class="faq-trigger w-full flex items-center justify-between gap-4 text-left px-6 py-5 font-heading font-semibold text-dark-green">
                    <span class="flex items-start gap-3">
                        <span class="shrink-0 w-7 h-7 rounded-full bg-secondary-green/10 text-secondary-green text-xs font-bold flex items-center justify-center">01</span>
                        Apa saja bidang usaha PT. Sahabat Sawit Rokan Sejahtera?
                    </span>
                    <i class="bi bi-chevron-down faq-icon text-secondary-green transition-transform duration-300 shrink-0"></i>
                </button>
                <div class="faq-content max-h-0 overflow-hidden transition-all duration-300">
                    <p class="pl-[3.75rem] pr-6 pb-5 font-body text-dark-text/90 leading-relaxed">
                        Kami bergerak di bidang perkebunan dan pengolahan kelapa sawit, mulai dari budidaya, panen,
                        hingga distribusi hasil produksi, dengan berpegang pada prinsip pengelolaan yang bertanggung jawab.
                    </p>
                </div>
            </div>

            <div class="faq-item bg-white rounded-brand border border-dark-green/10 overflow-hidden">
                <button type="button" class="faq-trigger w-full flex items-center justify-between gap-4 text-left px-6 py-5 font-heading font-semibold text-dark-green">
                    <span class="flex items-start gap-3">
                        <span class="shrink-0 w-7 h-7 rounded-full bg-secondary-green/10 text-secondary-green text-xs font-bold flex items-center justify-center">02</span>
                        Di mana lokasi kebun dan operasional perusahaan?
                    </span>
                    <i class="bi bi-chevron-down faq-icon text-secondary-green transition-transform duration-300 shrink-0"></i>
                </button>
                <div class="faq-content max-h-0 overflow-hidden transition-all duration-300">
                    <p class="pl-[3.75rem] pr-6 pb-5 font-body text-dark-text/90 leading-relaxed">
                        Kantor pusat kami berada di Kota Medan, Sumatera Utara, sedangkan area perkebunan dan
                        operasional utama berlokasi di Kabupaten Rokan Hilir, Provinsi Riau.
                    </p>
                </div>
            </div>

            <div class="faq-item bg-white rounded-brand border border-dark-green/10 overflow-hidden">
                <button type="button" class="faq-trigger w-full flex items-center justify-between gap-4 text-left px-6 py-5 font-heading font-semibold text-dark-green">
                    <span class="flex items-start gap-3">
                        <span class="shrink-0 w-7 h-7 rounded-full bg-secondary-green/10 text-secondary-green text-xs font-bold flex items-center justify-center">03</span>
                        Bagaimana cara menjalin kerja sama atau kemitraan?
                    </span>
                    <i class="bi bi-chevron-down faq-icon text-secondary-green transition-transform duration-300 shrink-0"></i>
                </button>
                <div class="faq-content max-h-0 overflow-hidden transition-all duration-300">
                    <p class="pl-[3.75rem] pr-6 pb-5 font-body text-dark-text/90 leading-relaxed">
                        Silakan hubungi tim kami melalui WhatsApp pada tombol di atas. Tim kami akan menindaklanjuti
                        dan menjadwalkan diskusi lebih lanjut sesuai kebutuhan kerja sama Anda.
                    </p>
                </div>
            </div>

            <div class="faq-item bg-white rounded-brand border border-dark-green/10 overflow-hidden">
                <button type="button" class="faq-trigger w-full flex items-center justify-between gap-4 text-left px-6 py-5 font-heading font-semibold text-dark-green">
                    <span class="flex items-start gap-3">
                        <span class="shrink-0 w-7 h-7 rounded-full bg-secondary-green/10 text-secondary-green text-xs font-bold flex items-center justify-center">04</span>
                        Apakah perusahaan membuka lowongan pekerjaan?
                    </span>
                    <i class="bi bi-chevron-down faq-icon text-secondary-green transition-transform duration-300 shrink-0"></i>
                </button>
                <div class="faq-content max-h-0 overflow-hidden transition-all duration-300">
                    <p class="pl-[3.75rem] pr-6 pb-5 font-body text-dark-text/90 leading-relaxed">
                        Informasi lowongan yang tersedia akan kami umumkan melalui halaman Karier di situs ini.
                        Anda juga dapat menanyakan langsung melalui WhatsApp untuk informasi terbaru.
                    </p>
                </div>
            </div>

            <div class="faq-item bg-white rounded-brand border border-dark-green/10 overflow-hidden">
                <button type="button" class="faq-trigger w-full flex items-center justify-between gap-4 text-left px-6 py-5 font-heading font-semibold text-dark-green">
                    <span class="flex items-start gap-3">
                        <span class="shrink-0 w-7 h-7 rounded-full bg-secondary-green/10 text-secondary-green text-xs font-bold flex items-center justify-center">05</span>
                        Bagaimana komitmen perusahaan terhadap keberlanjutan?
                    </span>
                    <i class="bi bi-chevron-down faq-icon text-secondary-green transition-transform duration-300 shrink-0"></i>
                </button>
                <div class="faq-content max-h-0 overflow-hidden transition-all duration-300">
                    <p class="pl-[3.75rem] pr-6 pb-5 font-body text-dark-text/90 leading-relaxed">
                        Kami menerapkan praktik perkebunan yang bertanggung jawab dan berkelanjutan, memperhatikan
                        kelestarian lingkungan serta kesejahteraan masyarakat sekitar area operasional.
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ============================= --}}
{{-- SECTION PENUTUP --}}
{{-- ============================= --}}
<section class="relative bg-dark-green py-16 md:py-20 overflow-hidden">
    <div class="absolute inset-0 opacity-90 bg-gradient-to-br from-dark-green via-dark-green to-secondary-green"></div>

    <div class="relative max-w-2xl mx-auto px-6 text-center">
        <span class="font-heading font-semibold text-light-green uppercase text-sm tracking-widest">Bersama Membangun</span>
        <h2 class="mt-3 font-heading font-bold text-2xl md:text-3xl text-white mb-4">Mari Terhubung Bersama Kami</h2>
        <p class="font-body text-white/80 leading-relaxed mb-8">
            Bersama membangun industri kelapa sawit yang produktif, bertanggung jawab,
            dan berkelanjutan bagi masyarakat serta lingkungan di Rokan Hilir, Riau.
        </p>

        <div class="flex flex-wrap items-center justify-center gap-3">
            <a href="https://wa.me/628139654581"
               target="_blank"
               rel="noopener noreferrer"
               class="inline-flex items-center gap-2.5 bg-[#25D366] hover:bg-[#1EBE5A] text-white font-heading font-semibold text-sm md:text-base px-7 py-3.5 rounded-full shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">
                <i class="bi bi-whatsapp text-lg"></i>
                <span>Mulai Percakapan</span>
            </a>
            <a href="mailto:info@sahabatsawitrokan.co.id"
               class="inline-flex items-center gap-2.5 bg-white/10 hover:bg-white/20 border border-white/25 text-white font-heading font-semibold text-sm md:text-base px-7 py-3.5 rounded-full backdrop-blur-sm hover:-translate-y-0.5 transition-all duration-300">
                <i class="bi bi-envelope-fill text-lg"></i>
                <span>Kirim Email</span>
            </a>
        </div>
    </div>
</section>

@push('styles')
<style>
    .map-tab-btn {
        color: var(--color-dark-text, #4b4b4b);
    }
    .map-tab-btn.active {
        background-color: var(--color-dark-green, #1f3d2b);
        color: #fff;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // FAQ accordion
        document.querySelectorAll('.faq-trigger').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var item = btn.closest('.faq-item');
                var content = item.querySelector('.faq-content');
                var icon = item.querySelector('.faq-icon');
                var isOpen = item.classList.contains('faq-open');

                document.querySelectorAll('.faq-item').forEach(function (other) {
                    other.classList.remove('faq-open');
                    other.querySelector('.faq-content').style.maxHeight = null;
                    other.querySelector('.faq-icon').classList.remove('rotate-180');
                });

                if (!isOpen) {
                    item.classList.add('faq-open');
                    content.style.maxHeight = content.scrollHeight + 'px';
                    icon.classList.add('rotate-180');
                }
            });
        });

        // Map tab switcher
        document.querySelectorAll('.map-tab-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var target = btn.getAttribute('data-target');

                document.querySelectorAll('.map-tab-btn').forEach(function (b) {
                    b.classList.remove('active');
                });
                btn.classList.add('active');

                document.querySelectorAll('.map-frame').forEach(function (frame) {
                    frame.classList.add('hidden');
                });
                document.getElementById('map-frame-' + target).classList.remove('hidden');

                document.querySelectorAll('.map-info-panel').forEach(function (panel) {
                    panel.classList.add('hidden');
                });
                document.getElementById('info-' + target).classList.remove('hidden');
            });
        });
    });
</script>
@endpush

@endsection