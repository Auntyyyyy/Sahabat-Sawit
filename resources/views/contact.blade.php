@extends('layouts.app')

@section('title', 'Kontak Kami - PT. Sahabat Sawit Rokan Sejahtera')

@section('content')

{{-- ============================= --}}
{{-- HERO SECTION - HALAMAN KONTAK --}}
{{-- ============================= --}}
<section class="bg-dark-green py-20 md:py-28">
    <div class="max-w-3xl mx-auto px-6 text-center">
        <span class="font-heading font-semibold text-light-green uppercase text-sm tracking-widest">Kontak</span>
        <h1 class="mt-3 font-heading font-bold text-4xl md:text-5xl text-white">Hubungi Kami</h1>
        <p class="mt-5 text-white/80 leading-relaxed text-base md:text-lg">
            Kami terbuka untuk menjalin kerja sama, kemitraan, maupun menjawab pertanyaan
            seputar PT. Sahabat Sawit Rokan Sejahtera. Sampaikan langsung melalui WhatsApp kami.
        </p>
    </div>
</section>

{{-- ============================= --}}
{{-- INFORMASI LOKASI + MAPS --}}
{{-- ============================= --}}
<section class="py-20 bg-body-bg">
    <div class="max-w-5xl mx-auto px-6">
        {{-- Kotak Alamat --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">

            <div class="bg-cream rounded-brand border border-dark-green/10 p-8 md:p-10 text-center hover:-translate-y-1.5 hover:shadow-xl transition-all duration-300">
                <div class="w-16 h-16 mx-auto mb-5 rounded-full bg-secondary-green/10 text-secondary-green flex items-center justify-center text-2xl">
                    <i class="bi bi-building"></i>
                </div>
                <h3 class="font-heading font-bold text-xl text-dark-green mb-2">Kantor Pusat</h3>
                <p class="font-body text-dark-text">Jl. Haji Misbah, Komplek Multatuli Indah Blok D No. 36. Kel. Hamdan, Kec. Medan Maimun, Kota Medan, Sumatera Utara</p>
            </div>

            <div class="bg-cream rounded-brand border border-dark-green/10 p-8 md:p-10 text-center hover:-translate-y-1.5 hover:shadow-xl transition-all duration-300">
                <div class="w-16 h-16 mx-auto mb-5 rounded-full bg-secondary-green/10 text-secondary-green flex items-center justify-center text-2xl">
                    <i class="bi bi-geo-alt-fill"></i>
                </div>
                <h3 class="font-heading font-bold text-xl text-dark-green mb-2">Site / Operasional</h3>
                <p class="font-body text-dark-text">Jl. Lintas Medan KM 1, RT/RW 001/001 Kepenghuluan Sungai Meranti, Kec. Tanjung Medan, Kab. Rokan Hilir, Riau 28983</p>
            </div>

        </div>

        {{-- Kotak Maps --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 mt-6 md:mt-8">

            <div class="rounded-brand border border-dark-green/10 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300">
                <div class="w-full h-64">
                    <iframe
                        src="https://www.google.com/maps?q=Jl.+Haji+Misbah,+Komplek+Multatuli+Indah+Blok+D+No.+36,+Kel.+Hamdan,+Kec.+Medan+Maimun,+Kota+Medan,+Sumatera+Utara&output=embed"
                        class="w-full h-full border-0"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Peta Lokasi Kantor Pusat PT. Sahabat Sawit Rokan Sejahtera">
                    </iframe>
                </div>
            </div>

            <div class="rounded-brand border border-dark-green/10 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300">
                <div class="w-full h-64">
                    <iframe
                        src="https://www.google.com/maps?q=Jl.+Lintas+Medan+KM+1,+Kepenghuluan+Sungai+Meranti,+Kec.+Tanjung+Medan,+Kab.+Rokan+Hilir,+Riau+28983&output=embed"
                        class="w-full h-full border-0"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Peta Lokasi Site Operasional PT. Sahabat Sawit Rokan Sejahtera">
                    </iframe>
                </div>
            </div>

        </div>

        {{-- ============================= --}}
        {{-- CTA UTAMA - WHATSAPP --}}
        {{-- ============================= --}}
        <div class="mt-16 text-center">
            <p class="font-body text-dark-text text-lg font-medium mb-6">Ingin berdiskusi langsung dengan tim kami?</p>

            {{-- Ganti nomor WhatsApp di bawah ini sesuai kebutuhan (format: 62xxxxxxxxxxx) --}}
            <a href="https://wa.me/628139654581"
               target="_blank"
               rel="noopener noreferrer"
               class="inline-flex items-center gap-3 bg-[#25D366] hover:bg-[#1EBE5A] text-white font-heading font-semibold text-base md:text-lg px-9 py-4 rounded-full shadow-lg shadow-[#25D366]/30 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">
                <i class="bi bi-whatsapp text-2xl"></i>
                <span>Hubungi Kami via WhatsApp</span>
            </a>
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
            </p>
        </div>

        <div class="space-y-4" id="faq-accordion">

            <div class="faq-item bg-white rounded-brand border border-dark-green/10 overflow-hidden">
                <button type="button" class="faq-trigger w-full flex items-center justify-between gap-4 text-left px-6 py-5 font-heading font-semibold text-dark-green">
                    <span>Apa saja bidang usaha PT. Sahabat Sawit Rokan Sejahtera?</span>
                    <i class="bi bi-chevron-down faq-icon text-secondary-green transition-transform duration-300"></i>
                </button>
                <div class="faq-content max-h-0 overflow-hidden transition-all duration-300">
                    <p class="px-6 pb-5 font-body text-dark-text/90 leading-relaxed">
                        Kami bergerak di bidang perkebunan dan pengolahan kelapa sawit, mulai dari budidaya, panen,
                        hingga distribusi hasil produksi, dengan berpegang pada prinsip pengelolaan yang bertanggung jawab.
                    </p>
                </div>
            </div>

            <div class="faq-item bg-white rounded-brand border border-dark-green/10 overflow-hidden">
                <button type="button" class="faq-trigger w-full flex items-center justify-between gap-4 text-left px-6 py-5 font-heading font-semibold text-dark-green">
                    <span>Di mana lokasi kebun dan operasional perusahaan?</span>
                    <i class="bi bi-chevron-down faq-icon text-secondary-green transition-transform duration-300"></i>
                </button>
                <div class="faq-content max-h-0 overflow-hidden transition-all duration-300">
                    <p class="px-6 pb-5 font-body text-dark-text/90 leading-relaxed">
                        Kantor pusat kami berada di Kota Medan, Sumatera Utara, sedangkan area perkebunan dan
                        operasional utama berlokasi di Kabupaten Rokan Hilir, Provinsi Riau.
                    </p>
                </div>
            </div>

            <div class="faq-item bg-white rounded-brand border border-dark-green/10 overflow-hidden">
                <button type="button" class="faq-trigger w-full flex items-center justify-between gap-4 text-left px-6 py-5 font-heading font-semibold text-dark-green">
                    <span>Bagaimana cara menjalin kerja sama atau kemitraan?</span>
                    <i class="bi bi-chevron-down faq-icon text-secondary-green transition-transform duration-300"></i>
                </button>
                <div class="faq-content max-h-0 overflow-hidden transition-all duration-300">
                    <p class="px-6 pb-5 font-body text-dark-text/90 leading-relaxed">
                        Silakan hubungi tim kami melalui WhatsApp pada tombol di atas. Tim kami akan menindaklanjuti
                        dan menjadwalkan diskusi lebih lanjut sesuai kebutuhan kerja sama Anda.
                    </p>
                </div>
            </div>

            <div class="faq-item bg-white rounded-brand border border-dark-green/10 overflow-hidden">
                <button type="button" class="faq-trigger w-full flex items-center justify-between gap-4 text-left px-6 py-5 font-heading font-semibold text-dark-green">
                    <span>Apakah perusahaan membuka lowongan pekerjaan?</span>
                    <i class="bi bi-chevron-down faq-icon text-secondary-green transition-transform duration-300"></i>
                </button>
                <div class="faq-content max-h-0 overflow-hidden transition-all duration-300">
                    <p class="px-6 pb-5 font-body text-dark-text/90 leading-relaxed">
                        Informasi lowongan yang tersedia akan kami umumkan melalui halaman Karier di situs ini.
                        Anda juga dapat menanyakan langsung melalui WhatsApp untuk informasi terbaru.
                    </p>
                </div>
            </div>

            <div class="faq-item bg-white rounded-brand border border-dark-green/10 overflow-hidden">
                <button type="button" class="faq-trigger w-full flex items-center justify-between gap-4 text-left px-6 py-5 font-heading font-semibold text-dark-green">
                    <span>Bagaimana komitmen perusahaan terhadap keberlanjutan?</span>
                    <i class="bi bi-chevron-down faq-icon text-secondary-green transition-transform duration-300"></i>
                </button>
                <div class="faq-content max-h-0 overflow-hidden transition-all duration-300">
                    <p class="px-6 pb-5 font-body text-dark-text/90 leading-relaxed">
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
<section class="bg-secondary-green py-16 md:py-20">
    <div class="max-w-2xl mx-auto px-6 text-center">
        <h2 class="font-heading font-bold text-2xl md:text-3xl text-white mb-4">Mari Terhubung Bersama Kami</h2>
        <p class="font-body text-white/85 leading-relaxed">
            Bersama membangun industri kelapa sawit yang produktif, bertanggung jawab,
            dan berkelanjutan bagi masyarakat serta lingkungan di Rokan Hilir, Riau.
        </p>
    </div>
</section>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.faq-trigger').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var item = btn.closest('.faq-item');
                var content = item.querySelector('.faq-content');
                var icon = item.querySelector('.faq-icon');
                var isOpen = item.classList.contains('faq-open');

                // Tutup semua item FAQ lain
                document.querySelectorAll('.faq-item').forEach(function (other) {
                    other.classList.remove('faq-open');
                    other.querySelector('.faq-content').style.maxHeight = null;
                    other.querySelector('.faq-icon').classList.remove('rotate-180');
                });

                // Toggle item yang diklik
                if (!isOpen) {
                    item.classList.add('faq-open');
                    content.style.maxHeight = content.scrollHeight + 'px';
                    icon.classList.add('rotate-180');
                }
            });
        });
    });
</script>
@endpush

@endsection