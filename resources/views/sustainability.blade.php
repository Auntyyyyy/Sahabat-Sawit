@extends('layouts.app')

@section('title', 'Keberlanjutan — PT Sahabat Sawit')

@section('content')

{{-- Foto sementara: ganti nama file di sini setelah foto asli terkumpul (file ada di public/images). --}}
@php
    $fotoHero  = 'herotentang.png';
    $fotoPilar = 'sawitangkong.jpg';
    $fotoAngka = 'prosesnunggu.png';
@endphp

<style>
    /* Halaman Keberlanjutan — gaya disamakan dengan Home/Produk/Tentang Kami/Kontak. Class berawalan "sb-". */
    @import url('https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,600;12..96,800&display=swap');

    .sb {
        --hijau: #16321f; --hijau-muda: #2f5a3a; --emas: #e3a72f; --krem: #f2f4ec;
        --gutter: max(clamp(1.25rem, 5vw, 3.5rem), calc((100vw - 80rem) / 2));
        font-family: "Bricolage Grotesque", system-ui, -apple-system, "Segoe UI", sans-serif;
        background: var(--hijau); color: var(--krem); line-height: 1.6;
    }
    .sb *, .sb *::before, .sb *::after { box-sizing: border-box; }
    .sb a { color: inherit; text-decoration: none; }
    .sb a:focus-visible, .sb button:focus-visible { outline: 3px solid var(--krem); outline-offset: 3px; }

    .sb-hero { position: relative; isolation: isolate; overflow: hidden; display: flex; align-items: flex-end; min-height: min(92vh, 58rem); background: linear-gradient(145deg, var(--hijau-muda), var(--hijau)); }
    .sb-hero::after { content: ''; position: absolute; inset: 0; z-index: -1; background: linear-gradient(90deg, rgba(22,50,31,.92) 0%, rgba(22,50,31,.6) 50%, rgba(22,50,31,.2) 100%), linear-gradient(0deg, rgba(22,50,31,.85) 0%, transparent 45%); }
    .sb-hero-inner { width: 100%; padding: clamp(8rem, 18vh, 11rem) var(--gutter) clamp(3rem, 8vh, 5rem); }
    .sb-bg { position: absolute; inset: 0; z-index: -2; width: 100%; height: 100%; object-fit: cover; }
    .sb-crumb { display: flex; flex-wrap: wrap; align-items: center; gap: .5rem; font-size: .9rem; color: rgba(242,244,236,.6); }
    .sb-crumb a:hover { color: var(--krem); }
    .sb-crumb [aria-current] { color: var(--krem); }
    .sb-title { margin-top: 1.5rem; max-width: 14ch; font-size: clamp(2.6rem, 8vw, 6.5rem); font-weight: 800; line-height: 1.02; letter-spacing: -.04em; }
    .sb-lead { margin-top: 1.5rem; max-width: 38rem; color: rgba(242,244,236,.75); }

    .sb-sec { padding: clamp(4rem, 10vh, 7rem) var(--gutter); }
    .sb-hero + .sb-sec, .sb-sec + .sb-sec { border-top: 1px solid rgba(242,244,236,.12); }
    .sb-label { font-size: .95rem; color: rgba(242,244,236,.6); }
    .sb-h2 { margin-top: .5rem; max-width: 20ch; font-size: clamp(1.8rem, 4vw, 3rem); font-weight: 800; line-height: 1.05; letter-spacing: -.03em; }
    .sb-p { margin-top: 1.25rem; max-width: 40rem; color: rgba(242,244,236,.78); }
    .sb-link { display: inline-flex; align-items: center; gap: .55rem; margin-top: 2rem; font-weight: 600; font-size: .95rem; border-bottom: 2px solid var(--emas); padding-bottom: .2rem; }
    .sb-link:hover { color: var(--emas); }

    /* Pernyataan: satu kalimat besar, rata tengah, di latar paling pekat */
    .sb-statement { background: #0f2417; text-align: center; }
    .sb-statement p { margin: 0 auto; max-width: 26em; font-size: clamp(1.6rem, 3.8vw, 3.2rem); font-weight: 800; line-height: 1.15; letter-spacing: -.03em; }

    /* Pilar: section terang sebagai jeda, judul + foto menempel di kiri */
    .sb-light { background: var(--krem); color: var(--hijau); }
    .sb-light .sb-label { color: rgba(22,50,31,.6); }
    .sb-split { display: grid; grid-template-columns: 1fr; gap: 3rem; }
    @media (min-width: 900px) { .sb-split { grid-template-columns: 5fr 7fr; gap: 5rem; align-items: start; } .sb-sticky { position: sticky; top: 7rem; } }
    .sb-photo-tall { display: block; width: 100%; margin-top: 2.5rem; aspect-ratio: 4 / 5; object-fit: cover; border-radius: .25rem; background: linear-gradient(145deg, var(--hijau-muda), var(--hijau)); }
    .sb-pillars { list-style: none; margin: 0; padding: 0; }
    .sb-pil { padding: 1.75rem 0; border-top: 1px solid rgba(22,50,31,.2); }
    .sb-pil:last-child { border-bottom: 1px solid rgba(22,50,31,.2); }
    .sb-pil-no { font-size: clamp(2.5rem, 5vw, 4rem); font-weight: 800; letter-spacing: -.04em; line-height: 1; color: rgba(22,50,31,.2); }
    .sb-pil h3 { margin-top: .75rem; font-size: clamp(1.5rem, 2.8vw, 2.3rem); font-weight: 800; letter-spacing: -.03em; line-height: 1.1; }
    .sb-pil p { margin-top: .6rem; max-width: 34rem; color: rgba(22,50,31,.78); }

    /* Angka: pita lebar berlatar foto, angka raksasa rata tengah */
    .sb-band { position: relative; isolation: isolate; overflow: hidden; text-align: center; background: linear-gradient(145deg, var(--hijau-muda), var(--hijau)); }
    .sb-band::after { content: ''; position: absolute; inset: 0; z-index: -1; background: rgba(15,36,23,.8); }
    .sb-band .sb-h2, .sb-band .sb-p { margin-left: auto; margin-right: auto; }
    .sb-stats { margin: clamp(2.5rem, 6vh, 4rem) 0 0; display: grid; grid-template-columns: repeat(auto-fit, minmax(11rem, 1fr)); gap: 2.5rem 3rem; }
    .sb-stat-value { font-size: clamp(3rem, 8vw, 6.5rem); font-weight: 800; letter-spacing: -.05em; line-height: 1; color: var(--emas); }
    .sb-stat-label { margin-top: .75rem; font-size: 1rem; color: rgba(242,244,236,.8); }

    /* ===== Program CSR ===== */

    /* Bar: tab kategori berupa chip (digeser ke samping di layar sempit) + panah dengan penghitung */
    .sb-bar { margin-top: 2.5rem; display: flex; flex-wrap: wrap; align-items: flex-start; justify-content: space-between; gap: 1.25rem 2rem; }
    .sb-tabs { flex: 1 1 100%; min-width: 0; display: flex; gap: .6rem; padding: .2rem 0 .5rem; overflow-x: auto; scroll-snap-type: x proximity; scrollbar-width: none; }
    .sb-tabs::-webkit-scrollbar { display: none; }
    @media (min-width: 900px) { .sb-tabs { flex: 1 1 0; flex-wrap: wrap; overflow: visible; padding-bottom: 0; } }
    .sb-tab { flex: none; scroll-snap-align: start; display: inline-flex; align-items: center; gap: .5rem; padding: .55rem 1.05rem; border: 1px solid rgba(242,244,236,.22); border-radius: 99px; background: transparent; font: inherit; font-weight: 600; font-size: .92rem; line-height: 1.2; color: rgba(242,244,236,.78); white-space: nowrap; cursor: pointer; transition: background .2s, border-color .2s, color .2s; }
    .sb-tab:hover { border-color: rgba(242,244,236,.55); color: var(--krem); }
    .sb-tab[aria-selected="true"] { background: var(--emas); border-color: var(--emas); color: var(--hijau); font-weight: 700; }
    .sb-tab span { font-size: 1.05rem; line-height: 1; }

    .sb-arrows { flex: none; margin-left: auto; display: flex; align-items: center; gap: .6rem; }
    .sb-count { min-width: 4.4rem; text-align: center; font-size: .85rem; font-weight: 600; letter-spacing: .04em; color: rgba(242,244,236,.7); }
    .sb-btn { width: 44px; height: 44px; border-radius: 50%; border: 1px solid rgba(242,244,236,.4); background: transparent; color: var(--krem); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: background .2s, border-color .2s, color .2s; }
    .sb-btn:hover { background: var(--emas); border-color: var(--emas); color: var(--hijau); }
    .sb-btn svg { width: 20px; height: 20px; }

    /* Panel: kepala kategori (ikon, judul, deskripsi, jumlah kegiatan) */
    .sb-panel[hidden] { display: none; }
    .sb-panel { margin-top: 1.5rem; }
    @media (prefers-reduced-motion: no-preference) { .sb-panel:not([hidden]) { animation: sbIn .35s ease both; } }
    @keyframes sbIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: none; } }
    .sb-panel-head { display: grid; grid-template-columns: minmax(0, 1fr); gap: 1.25rem 1.5rem; align-items: start; padding: 1.75rem; border: 1px solid rgba(242,244,236,.12); border-left: 3px solid var(--emas); border-radius: .5rem; background: rgba(242,244,236,.05); }
    .sb-panel-head.has-icon { grid-template-columns: auto minmax(0, 1fr); }
    .sb-panel-icon { display: flex; align-items: center; justify-content: center; width: 4rem; height: 4rem; border: 1px solid rgba(227,167,47,.4); border-radius: .5rem; background: rgba(227,167,47,.12); font-size: 1.9rem; line-height: 1; }
    .sb-panel-head h3 { font-size: clamp(1.6rem, 3vw, 2.4rem); font-weight: 800; letter-spacing: -.03em; line-height: 1.1; }
    .sb-panel-head p { margin-top: .75rem; max-width: 44rem; color: rgba(242,244,236,.78); }
    .sb-pill { display: inline-block; margin-top: 1rem; padding: .15rem .8rem; border: 1px solid rgba(242,244,236,.3); border-radius: 99px; font-size: .8rem; font-weight: 600; color: rgba(242,244,236,.8); }

    /* Kartu kegiatan */
    .sb-acts { margin-top: 1.5rem; display: grid; grid-template-columns: minmax(0, 1fr); gap: 1.5rem; }
    @media (min-width: 700px)  { .sb-acts { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    @media (min-width: 1100px) { .sb-acts { grid-template-columns: repeat(3, minmax(0, 1fr)); } }
    .sb-act { display: flex; flex-direction: column; overflow: hidden; border: 1px solid rgba(242,244,236,.12); border-radius: .5rem; background: rgba(242,244,236,.045); transition: transform .3s, border-color .3s, background .3s; }
    .sb-act-img { display: block; width: 100%; padding: 0; border: 0; aspect-ratio: 16 / 10; overflow: hidden; background: linear-gradient(145deg, var(--hijau-muda), #1d4129); cursor: pointer; }
    .sb-act-img img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform .6s ease; }
    .sb-act-body { display: flex; flex: 1; flex-direction: column; padding: 1.25rem 1.25rem 1.4rem; }
    .sb-act-meta { display: flex; flex-wrap: wrap; justify-content: space-between; gap: .25rem .75rem; font-size: .85rem; color: rgba(242,244,236,.6); }
    .sb-act-body .sb-act-meta span:first-child { font-weight: 600; color: var(--emas); }
    .sb-act h4 { margin-top: .6rem; font-size: 1.25rem; font-weight: 700; letter-spacing: -.01em; line-height: 1.3; }
    .sb-act-story { margin-top: .5rem; font-size: .95rem; color: rgba(242,244,236,.7); display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
    .sb-act-more { align-self: flex-start; margin-top: auto; padding: .9rem 0 .15rem; border: 0; background: none; color: var(--krem); font: inherit; font-weight: 600; font-size: .9rem; cursor: pointer; display: inline-flex; align-items: center; gap: .45rem; }
    .sb-act-more::after { content: ''; width: .9rem; height: 2px; background: var(--emas); transition: width .25s ease; }
    .sb-act-more:hover { color: var(--emas); }
    .sb-act-more:hover::after { width: 1.5rem; }
    @media (hover: hover) and (prefers-reduced-motion: no-preference) {
        .sb-act:hover { transform: translateY(-4px); border-color: rgba(227,167,47,.5); background: rgba(242,244,236,.08); }
        .sb-act:hover .sb-act-img img { transform: scale(1.04); }
    }
    .sb-empty { margin-top: 1.5rem; padding: 2.5rem 1rem; text-align: center; color: rgba(242,244,236,.6); border: 1px solid rgba(242,244,236,.15); border-radius: .5rem; }

    /* Popup cerita kegiatan */
    .sb-modal { position: fixed; inset: 0; z-index: 100; display: flex; align-items: center; justify-content: center; padding: 1.5rem; background: rgba(5,8,5,.9); overflow-y: auto; }
    .sb-modal[hidden] { display: none; }
    .sb-dialog { position: relative; width: min(100%, 44rem); max-height: 90vh; overflow-y: auto; border: 1px solid rgba(242,244,236,.15); border-radius: .25rem; background: #102a1a; }
    .sb-dialog img { display: block; width: 100%; aspect-ratio: 16 / 9; object-fit: cover; }
    .sb-dialog-body { padding: 1.5rem; }
    .sb-dialog h3 { margin-top: .5rem; font-size: clamp(1.4rem, 3vw, 2rem); font-weight: 700; letter-spacing: -.02em; line-height: 1.2; }
    .sb-dialog-story { margin-top: 1rem; color: rgba(242,244,236,.8); white-space: pre-line; text-align: justify; text-justify: inter-word; hyphens: auto; }
    .sb-close { position: absolute; top: .75rem; right: .75rem; }
    .sb-close.sb-btn { background: rgba(16,42,26,.8); }
</style>

<div class="sb">

    {{-- HERO berfoto --}}
    <header class="sb-hero">
        <img class="sb-bg" src="{{ asset('images/' . $fotoHero) }}" alt="" onerror="this.style.display='none'">
        <div class="sb-hero-inner">
            <nav class="sb-crumb" aria-label="Breadcrumb">
                <a href="{{ url('/') }}">Beranda</a>
                <span aria-hidden="true">/</span>
                <span aria-current="page">Keberlanjutan</span>
            </nav>
            <h1 class="sb-title">Komitmen untuk Keberlanjutan</h1>
            <p class="sb-lead">Menjaga keseimbangan antara produktivitas, lingkungan, dan kesejahteraan masyarakat.</p>
        </div>
    </header>

    {{-- PERNYATAAN --}}
    <section class="sb-sec sb-statement">
        <p>
            Setiap keputusan operasional kami berpijak pada pilar-pilar utama ini, demi keberlanjutan
            usaha dan manfaat jangka panjang bagi lingkungan serta masyarakat sekitar.
        </p>
    </section>

    {{-- PILAR (section terang) --}}
    <section class="sb-sec sb-light">
        <div class="sb-split">
            <div class="sb-sticky">
                <p class="sb-label">Fondasi kami</p>
                <h2 class="sb-h2">Pilar Keberlanjutan</h2>
                <img class="sb-photo-tall" src="{{ asset('images/' . $fotoPilar) }}" alt="" loading="lazy" onerror="this.style.display='none'">
            </div>
            <ul class="sb-pillars">
                @foreach($pillars as $pillar)
                <li class="sb-pil">
                    <span class="sb-pil-no">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                    <h3>{{ $pillar['title'] }}</h3>
                    <p>{{ $pillar['desc'] }}</p>
                </li>
                @endforeach
            </ul>
        </div>
    </section>

    {{-- ANGKA (pita foto lebar) --}}
    <section class="sb-sec sb-band" data-counter-section>
        <img class="sb-bg" src="{{ asset('images/' . $fotoAngka) }}" alt="" loading="lazy" onerror="this.style.display='none'">
        <p class="sb-label">Pencapaian kami</p>
        <h2 class="sb-h2">Keberlanjutan dalam Angka</h2>
        <p class="sb-p">Sejumlah pencapaian yang mencerminkan konsistensi kami dalam menjalankan praktik berkelanjutan.</p>
        <div class="sb-stats">
            @foreach($statistics as $stat)
            <div>
                <p class="sb-stat-value" data-counter="{{ $stat['value'] }}">{{ $stat['value'] }}</p>
                <p class="sb-stat-label">{{ $stat['label'] }}</p>
            </div>
            @endforeach
        </div>
    </section>

    {{-- PROGRAM CSR (dari database, dikelola dari admin) --}}
    <section class="sb-sec">
        <p class="sb-label">Program CSR</p>
        <h2 class="sb-h2">Kontribusi Kami untuk Masyarakat</h2>
        <p class="sb-p">Pilar-pilar utama program tanggung jawab sosial perusahaan yang kami jalankan secara berkelanjutan.</p>

        @if($csrCategories->isNotEmpty())
        <div class="sb-bar">
            <div class="sb-tabs" role="tablist" aria-label="Kategori program CSR">
                @foreach($csrCategories as $index => $category)
                <button type="button" class="sb-tab" role="tab" data-sb-tab
                        aria-selected="{{ $index === 0 ? 'true' : 'false' }}" tabindex="{{ $index === 0 ? '0' : '-1' }}">
                    @if($category->icon)<span aria-hidden="true">{{ $category->icon }}</span>@endif{{ $category->title }}
                </button>
                @endforeach
            </div>
            @if($csrCategories->count() > 1)
            <div class="sb-arrows">
                <button type="button" class="sb-btn" data-sb-nav="-1" aria-label="Program CSR sebelumnya">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <span class="sb-count" id="sbCount" aria-live="polite">{{ str_pad(1, 2, '0', STR_PAD_LEFT) }} / {{ str_pad($csrCategories->count(), 2, '0', STR_PAD_LEFT) }}</span>
                <button type="button" class="sb-btn" data-sb-nav="1" aria-label="Program CSR selanjutnya">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
            @endif
        </div>

        @foreach($csrCategories as $index => $category)
        <div class="sb-panel" data-sb-panel role="tabpanel" @unless($index === 0) hidden @endunless>
            <div class="sb-panel-head {{ $category->icon ? 'has-icon' : '' }}">
                @if($category->icon)
                    <span class="sb-panel-icon" aria-hidden="true">{{ $category->icon }}</span>
                @endif
                <div>
                    <h3>{{ $category->title }}</h3>
                    <p>{{ $category->description }}</p>
                    <span class="sb-pill">{{ $category->activities->count() }} kegiatan</span>
                </div>
            </div>

            @if($category->activities->isNotEmpty())
            <div class="sb-acts">
                @foreach($category->activities as $activity)
                @php $imageUrl = $activity->image ? asset('storage/' . $activity->image) : null; @endphp
                <article class="sb-act"
                         data-title="{{ $activity->title }}" data-date="{{ $activity->date }}"
                         data-location="{{ $activity->location }}" data-story="{{ $activity->story }}"
                         data-image="{{ $imageUrl }}">
                    <button type="button" class="sb-act-img" data-sb-open aria-label="Baca selengkapnya: {{ $activity->title }}">
                        @if($imageUrl)
                            <img src="{{ $imageUrl }}" alt="" loading="lazy" onerror="this.style.display='none'">
                        @endif
                    </button>
                    <div class="sb-act-body">
                        <div class="sb-act-meta">
                            <span>{{ $activity->date }}</span>
                            <span>{{ $activity->location }}</span>
                        </div>
                        <h4>{{ $activity->title }}</h4>
                        <p class="sb-act-story">{{ $activity->story }}</p>
                        <button type="button" class="sb-act-more" data-sb-open>Baca selengkapnya</button>
                    </div>
                </article>
                @endforeach
            </div>
            @else
            <p class="sb-empty">Belum ada kegiatan untuk kategori ini.</p>
            @endif
        </div>
        @endforeach

        @else
        <p class="sb-empty">Belum ada kategori CSR yang ditambahkan.</p>
        @endif
    </section>

    {{-- PENUTUP --}}
    <section class="sb-sec">
        <h2 class="sb-h2" style="max-width:24ch">Ingin tahu lebih jauh atau berdiskusi soal program CSR?</h2>
        <p class="sb-p">Ingin tahu lebih jauh tentang komitmen keberlanjutan kami atau berdiskusi soal kerja sama program CSR?</p>
        <a class="sb-link" href="{{ route('contact') }}">Hubungi Tim Kami</a>
    </section>

    {{-- POPUP: satu popup dipakai semua kartu kegiatan --}}
    <div class="sb-modal" id="sbModal" role="dialog" aria-modal="true" aria-labelledby="sbModalTitle" hidden>
        <div class="sb-dialog" lang="id">
            <button type="button" class="sb-btn sb-close" id="sbClose" aria-label="Tutup">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6L6 18"/></svg>
            </button>
            <img id="sbModalImg" src="" alt="">
            <div class="sb-dialog-body">
                <p class="sb-act-meta" style="margin-top:0"><span id="sbModalDate"></span><span id="sbModalLoc"></span></p>
                <h3 id="sbModalTitle"></h3>
                <p class="sb-dialog-story" id="sbModalStory"></p>
            </div>
        </div>
    </div>
</div>

<script>
    // Tab kategori CSR + tombol panah + penghitung (tab aktif ikut tergeser ke area terlihat di layar sempit)
    (function () {
        const tabs = [...document.querySelectorAll('[data-sb-tab]')];
        const panels = [...document.querySelectorAll('[data-sb-panel]')];
        const count = document.getElementById('sbCount');
        if (!tabs.length) return;
        let cur = 0;
        const pad = function (n) { return String(n).padStart(2, '0'); };

        function show(i) {
            cur = (i + tabs.length) % tabs.length;
            tabs.forEach(function (t, k) {
                t.setAttribute('aria-selected', k === cur ? 'true' : 'false');
                t.tabIndex = k === cur ? 0 : -1;
            });
            panels.forEach(function (p, k) { p.hidden = k !== cur; });
            if (count) count.textContent = pad(cur + 1) + ' / ' + pad(tabs.length);
            tabs[cur].scrollIntoView({ inline: 'nearest', block: 'nearest', behavior: 'smooth' });
        }

        tabs.forEach(function (t, k) {
            t.addEventListener('click', function () { show(k); });
            // Panah kiri/kanan di keyboard berpindah antar tab
            t.addEventListener('keydown', function (e) {
                if (e.key === 'ArrowRight') { e.preventDefault(); show(cur + 1); tabs[cur].focus(); }
                if (e.key === 'ArrowLeft')  { e.preventDefault(); show(cur - 1); tabs[cur].focus(); }
            });
        });
        document.querySelectorAll('[data-sb-nav]').forEach(function (b) {
            b.addEventListener('click', function () { show(cur + Number(b.dataset.sbNav)); });
        });
    })();

    // Popup cerita lengkap kegiatan
    (function () {
        const modal = document.getElementById('sbModal');
        if (!modal) return;
        const img = document.getElementById('sbModalImg');
        const closeBtn = document.getElementById('sbClose');
        let last = null;

        function open(card) {
            img.hidden = !card.dataset.image;
            img.src = card.dataset.image || '';
            img.alt = card.dataset.title;
            document.getElementById('sbModalTitle').textContent = card.dataset.title;
            document.getElementById('sbModalDate').textContent = card.dataset.date;
            document.getElementById('sbModalLoc').textContent = card.dataset.location;
            document.getElementById('sbModalStory').textContent = card.dataset.story;
            last = document.activeElement;
            modal.hidden = false;
            document.body.style.overflow = 'hidden';
            closeBtn.focus();
        }
        function close() {
            modal.hidden = true;
            document.body.style.overflow = '';
            if (last) last.focus();
        }

        document.addEventListener('click', function (e) {
            const opener = e.target.closest('[data-sb-open]');
            if (opener) { open(opener.closest('.sb-act')); return; }
            if (e.target === modal || e.target.closest('#sbClose')) close();
        });
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !modal.hidden) close(); });
    })();

    // Animasi angka saat section masuk layar (dilewati bila "kurangi gerakan")
    document.addEventListener('DOMContentLoaded', function () {
        const section = document.querySelector('[data-counter-section]');
        if (!section || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

        const counters = [...section.querySelectorAll('[data-counter]')].filter(function (el) {
            return /[\d.,]+/.test(el.dataset.counter);
        });
        counters.forEach(function (el) {
            const raw = el.dataset.counter;
            el.textContent = raw.replace(/[\d.,]+/, '0');
        });

        function run() {
            counters.forEach(function (el) {
                const raw = el.dataset.counter;
                const num = raw.match(/[\d.,]+/)[0];
                const suffix = raw.replace(num, '');
                const target = parseFloat(num.replace(/,/g, ''));
                const start = performance.now(), dur = 1500;
                function tick(now) {
                    const p = Math.min((now - start) / dur, 1);
                    const eased = 1 - Math.pow(1 - p, 3);
                    el.textContent = (p < 1 ? Math.floor(eased * target) : target).toLocaleString('id-ID') + suffix;
                    if (p < 1) requestAnimationFrame(tick);
                }
                requestAnimationFrame(tick);
            });
        }

        const io = new IntersectionObserver(function (entries) {
            if (entries.some(function (e) { return e.isIntersecting; })) { io.disconnect(); run(); }
        }, { threshold: 0.4 });
        io.observe(section);
    });
</script>

@endsection