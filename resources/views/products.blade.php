@extends('layouts.app')

@section('title', 'Produk — PT Sahabat Sawit')

@section('content')
<section class="relative bg-dark-green text-white py-20 bg-cover bg-center"
         style="background-image: url('{{ asset('images/sawittangan.jpg') }}');">
    <div class="absolute inset-0 bg-dark-green/80"></div>

    <div class="relative max-w-5xl mx-auto px-6 lg:px-8 text-center">
        <span class="font-heading font-semibold text-light-green uppercase text-sm tracking-wide">Produk Kami</span>
        <h1 class="mt-3 font-heading font-bold text-3xl md:text-5xl">Hasil Perkebunan Berkualitas</h1>
        <p class="mt-4 text-white/75 max-w-2xl mx-auto leading-relaxed">
            Produk unggulan dari perkebunan kelapa sawit PT Sahabat Sawit di Rokan Hilir, Riau.
        </p>
    </div>
</section>

<section class="py-16 bg-cream">
    <div class="max-w-6xl mx-auto px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
            <div>
                <img src="{{ asset('images/sawitangkong.jpg') }}" alt="Mengenal Kelapa Sawit"
                     class="rounded-brand shadow-lg w-full h-80 object-cover"
                     onerror="this.src='https://placehold.co/600x400/2E7D32/F5F1E8?text=Kelapa+Sawit'">
            </div>
            <div>
                <span class="font-heading font-semibold text-secondary-green uppercase text-sm tracking-wide">Mengenal Kelapa Sawit</span>
                <h2 class="mt-3 font-heading font-bold text-2xl md:text-4xl text-dark-green">
                    Sumber Daya Alam dengan Segudang Manfaat
                </h2>
                <p class="mt-4 text-gray-text leading-relaxed text-justify">
                    Kelapa sawit (<em>Elaeis guineensis</em>) adalah tanaman perkebunan tropis yang menjadi salah satu komoditas unggulan Indonesia.
                    Setiap tandan buah segar (TBS) diolah menjadi berbagai produk turunan bernilai tinggi, mulai dari minyak goreng,
                    minyak inti sawit (PKO), hingga bahan baku industri pangan dan non-pangan.
                </p>
                <p class="mt-4 text-gray-text leading-relaxed text-justify">
                    Melalui pengelolaan perkebunan yang berkelanjutan, PT Sahabat Sawit berkomitmen menghasilkan produk berkualitas
                    tinggi sekaligus menjaga kelestarian lingkungan dan kesejahteraan masyarakat sekitar.
                </p>

                <div class="cert-badges">
                    <div class="cert-badge">
                        <img src="{{ asset('images/ispo.png') }}" alt="Sertifikasi ISPO"
                            onerror="this.src='https://placehold.co/60x60/164A2E/F5F1E8?text=ISPO'">
                        <div>
                            <span class="cert-badge-title">Tersertifikasi ISPO</span>
                            <span class="cert-badge-desc">Indonesian Sustainable Palm Oil</span>
                        </div>
                    </div>

                    <div class="cert-badge">
                        <img src="{{ asset('images/halal.png') }}" alt="Sertifikasi Halal"
                            onerror="this.src='https://placehold.co/60x60/164A2E/F5F1E8?text=Halal'">
                        <div>
                            <span class="cert-badge-title">Tersertifikasi Halal</span>
                            <span class="cert-badge-desc">BPJPH - Kementerian Agama RI</span>
                        </div>
                    </div>
                </div>

                <div class="feature-grid">
                    <div class="feature-item">
                        <span class="feature-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                        <p>Diolah dari TBS pilihan</p>
                    </div>
                    <div class="feature-item">
                        <span class="feature-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                        <p>Standar mutu terjaga</p>
                    </div>
                    <div class="feature-item">
                        <span class="feature-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                        <p>Perkebunan berkelanjutan</p>
                    </div>
                    <div class="feature-item">
                        <span class="feature-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                        <p>Ramah lingkungan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-white">
    <div class="max-w-6xl mx-auto px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto">
            <span class="font-heading font-semibold text-secondary-green uppercase text-sm tracking-wide">Jenis & Sebaran</span>
            <h2 class="mt-3 font-heading font-bold text-2xl md:text-4xl text-dark-green">
                Jenis Kelapa Sawit dan Daerah Penghasil
            </h2>
        </div>

        <div class="mt-12 grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="palm-type-card">
                <div class="palm-type-img-wrap">
                    <img src="{{ asset('images/tenera.png') }}" alt="Kelapa Sawit Jenis Tenera"
                         class="palm-type-img"
                         onerror="this.src='https://placehold.co/300x300/6B8E23/F5F1E8?text=Tenera'">
                </div>
                <h3 class="palm-type-title">Tenera</h3>
                <p class="palm-type-desc">
                    Hasil persilangan Dura dan Pisifera. Cangkang tipis dengan rendemen minyak tinggi,
                    menjadi jenis paling banyak dibudidayakan secara komersial.
                </p>
            </div>
            <div class="palm-type-card">
                <div class="palm-type-img-wrap">
                    <img src="{{ asset('images/dura.png') }}" alt="Kelapa Sawit Jenis Dura"
                         class="palm-type-img"
                         onerror="this.src='https://placehold.co/300x300/6B8E23/F5F1E8?text=Dura'">
                </div>
                <h3 class="palm-type-title">Dura</h3>
                <p class="palm-type-desc">
                    Memiliki cangkang tebal dan daging buah relatif tipis. Umumnya digunakan sebagai
                    induk dalam program persilangan bibit unggul.
                </p>
            </div>
            <div class="palm-type-card">
                <div class="palm-type-img-wrap">
                    <img src="{{ asset('images/pisifera.png') }}" alt="Kelapa Sawit Jenis Pisifera"
                         class="palm-type-img"
                         onerror="this.src='https://placehold.co/300x300/6B8E23/F5F1E8?text=Pisifera'">
                </div>
                <h3 class="palm-type-title">Pisifera</h3>
                <p class="palm-type-desc">
                    Hampir tidak memiliki cangkang dengan daging buah tebal, namun produktivitas rendah
                    sehingga jarang ditanam secara mandiri.
                </p>
            </div>
        </div>

        <div class="mt-16 grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
            <div>
                <img src="{{ asset('images/sebaranid.png') }}" alt="Peta Daerah Penghasil Kelapa Sawit di Indonesia"
                     class="rounded-brand shadow-lg w-full h-80 object-cover"
                     onerror="this.src='https://placehold.co/600x400/6B8E23/F5F1E8?text=Peta+Sebaran+Sawit'">
            </div>
            <div>
                <span class="font-heading font-semibold text-secondary-green uppercase text-sm tracking-wide">Sebaran Wilayah</span>
                <h3 class="mt-3 font-heading font-bold text-xl md:text-2xl text-dark-green">
                    Daerah Penghasil Utama di Indonesia
                </h3>
                <p class="mt-3 text-gray-text leading-relaxed text-justify">
                    Indonesia merupakan produsen kelapa sawit terbesar di dunia, dengan sebagian besar perkebunan
                    terkonsentrasi di Pulau Sumatera dan Kalimantan.
                </p>

                <div class="region-grid">
                    <div class="region-card">
                        <span class="region-rank">01</span>
                        <div class="region-content">
                            <h4 class="region-name">Riau</h4>
                            <p class="region-note text-justify">Provinsi dengan luas areal sawit terbesar di Indonesia, termasuk lokasi kebun PT. Sahabat Sawit Rokan Sejahtera.</p>
                        </div>
                    </div>

                    <div class="region-card">
                        <span class="region-rank">02</span>
                        <div class="region-content">
                            <h4 class="region-name">Sumatera Utara</h4>
                            <p class="region-note text-justify">Sentra sawit tertua dengan infrastruktur pengolahan yang matang.</p>
                        </div>
                    </div>

                    <div class="region-card">
                        <span class="region-rank">03</span>
                        <div class="region-content">
                            <h4 class="region-name">Kalimantan Tengah & Barat</h4>
                            <p class="region-note text-justify">Wilayah ekspansi perkebunan sawit terbesar dalam dua dekade terakhir.</p>
                        </div>
                    </div>

                    <div class="region-card">
                        <span class="region-rank">04</span>
                        <div class="region-content">
                            <h4 class="region-name">Sumatera Selatan & Jambi</h4>
                            <p class="region-note text-justify">Kontributor produksi sawit yang terus berkembang di kawasan Sumatera bagian selatan.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-16 bg-body-bg">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="font-heading font-semibold text-secondary-green uppercase text-sm tracking-wide">Dari Kebun ke Produk</span>
            <h2 class="mt-3 font-heading font-bold text-2xl md:text-4xl text-dark-green">
                Proses & Produk
            </h2>
            <p class="mt-4 text-gray-text leading-relaxed text-justify max-w-2xl mx-auto">
                Setiap tahap diawasi dengan standar mutu ketat, mulai dari panen hingga produk siap didistribusikan.
            </p>
        </div>

        <div class="process-slider-wrap">
            <button type="button" class="process-arrow process-arrow-left" onclick="scrollProcessGallery(-1)" aria-label="Sebelumnya">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <div class="process-gallery" id="processGallery">
                @foreach($processSteps as $step)
                <figure class="process-item">
                    <img src="{{ asset('images/' . $step['image']) }}" alt="{{ $step['alt'] }}"
                         onerror="this.src='https://placehold.co/400x300/164A2E/F5F1E8?text={{ urlencode($step['label']) }}'">
                    <figcaption>
                        <span class="process-step">{{ $step['step'] }}</span>
                        <span class="process-label">{{ $step['label'] }}</span>
                    </figcaption>
                </figure>
                @endforeach
            </div>

            <button type="button" class="process-arrow process-arrow-right" onclick="scrollProcessGallery(1)" aria-label="Selanjutnya">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </div>
</section>

<script>
    function scrollProcessGallery(direction) {
        const gallery = document.getElementById('processGallery');
        gallery.scrollBy({ left: direction * gallery.clientWidth, behavior: 'smooth' });
    }
</script>

<section class="py-5 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-14">
            <span class="font-heading font-semibold text-secondary-green uppercase text-sm tracking-wide">Katalog Produk</span>
            <h2 class="mt-3 font-heading font-bold text-2xl md:text-4xl text-dark-green">
                Produk Unggulan Kami
            </h2>
            <p class="mt-4 text-gray-text leading-relaxed text-justify max-w-2xl mx-auto">
                Berbagai hasil olahan kelapa sawit berkualitas tinggi, diproduksi dengan standar mutu terbaik.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-8">
            @forelse($products as $product)
            <a href="{{ route('products.show', $product->slug) }}"
               class="product-card rounded-brand overflow-hidden shadow-lg group block">
                <div class="product-img-wrap">
                    <img src="{{ $product->image ? asset('storage/' . $product->image) : '' }}" alt="{{ $product->name }}"
                         class="product-img"
                         onerror="this.src='https://placehold.co/500x400/2E7D32/F5F1E8?text={{ urlencode($product->name) }}'">
                </div>
                <div class="p-6 bg-cream product-card-body">
                    <div>
                        <h3 class="font-heading font-semibold text-lg text-dark-green">{{ $product->name }}</h3>
                        <p class="mt-3 text-sm text-gray-text leading-relaxed text-justify product-desc">
                            {{ $product->description }}
                        </p>
                    </div>
                    <span class="product-card-link">
                        Lihat Detail
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </span>
                </div>
            </a>
            @empty
            <p class="col-span-full text-center text-gray-text">Belum ada produk yang ditambahkan.</p>
            @endforelse
        </div>
    </div>
</section>
@endsection