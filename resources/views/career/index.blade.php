@extends('layouts.app')

@section('title', 'Karir — PT Sahabat Sawit')

@section('content')
@include('career._style')

{{-- Foto latar hero: taruh file Anda di public/images/ lalu samakan namanya di sini. --}}
@php
    $fotoKarir = 'batik.jpeg';

    // Daftar lokasi unik untuk filter (filter hanya tampil bila ada lebih dari satu lokasi).
    $lokasiList = $lowongan->pluck('lokasi')->filter()->unique()->values();
@endphp

<style>
    /* Halaman Karir (daftar lowongan) — hero berfoto, daftar kartu dengan filter lokasi, ajakan kontak. Class berawalan "cr-". */
    @import url('https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,600;12..96,800&display=swap');

    .cr {
        --hijau: #16321f; --hijau-muda: #2f5a3a; --emas: #e3a72f; --krem: #f2f4ec;
        --gutter: max(clamp(1.25rem, 5vw, 3.5rem), calc((100vw - 80rem) / 2));
        font-family: "Bricolage Grotesque", system-ui, -apple-system, "Segoe UI", sans-serif;
        background: var(--hijau); color: var(--krem); line-height: 1.6;
    }
    .cr *, .cr *::before, .cr *::after { box-sizing: border-box; }
    .cr a { color: inherit; text-decoration: none; }
    .cr a:focus-visible, .cr button:focus-visible { outline: 3px solid var(--emas); outline-offset: 3px; }

    /* ===== Hero berfoto ===== */
    .cr-hero { position: relative; isolation: isolate; overflow: hidden; display: flex; align-items: flex-end; min-height: min(76vh, 46rem); background: linear-gradient(145deg, var(--hijau-muda), var(--hijau)); }
    .cr-hero::after { content: ''; position: absolute; inset: 0; z-index: -1; background: linear-gradient(90deg, rgba(22,50,31,.92) 0%, rgba(22,50,31,.6) 50%, rgba(22,50,31,.2) 100%), linear-gradient(0deg, rgba(22,50,31,.85) 0%, transparent 45%); }
    .cr-bg { position: absolute; inset: 0; z-index: -2; width: 100%; height: 100%; object-fit: cover; }
    .cr-hero-inner { width: 100%; padding: clamp(8rem, 18vh, 11rem) var(--gutter) clamp(3rem, 8vh, 4.5rem); }
    .cr-crumb { display: flex; flex-wrap: wrap; align-items: center; gap: .5rem; font-size: .9rem; color: rgba(242,244,236,.6); }
    .cr-crumb a:hover { color: var(--krem); }
    .cr-crumb [aria-current] { color: var(--krem); }
    .cr-title { margin-top: 1.5rem; max-width: 12ch; font-size: clamp(3rem, 10vw, 8rem); font-weight: 800; line-height: .98; letter-spacing: -.045em; }
    .cr-lead { margin-top: 1.5rem; max-width: 38rem; color: rgba(242,244,236,.78); }
    .cr-hero-row { margin-top: 2rem; display: flex; flex-wrap: wrap; align-items: center; gap: 1rem 1.75rem; }
    .cr-chip { display: inline-flex; align-items: center; gap: .6rem; padding: .45rem 1.05rem; border: 1px solid rgba(242,244,236,.4); border-radius: 99px; background: rgba(15,36,23,.5); font-size: .9rem; font-weight: 600; }
    .cr-chip::before { content: ''; width: .55rem; height: .55rem; border-radius: 50%; background: var(--emas); }
    .cr-link { display: inline-flex; align-items: center; gap: .55rem; font-weight: 600; font-size: .95rem; border-bottom: 2px solid var(--emas); padding-bottom: .2rem; }
    .cr-link:hover { color: var(--emas); }

    /* ===== Daftar lowongan (section terang) ===== */
    .cr-list-sec { scroll-margin-top: 4rem; padding: clamp(3.5rem, 9vh, 6rem) var(--gutter); background: var(--krem); color: var(--hijau); }
    .cr-list-head { display: flex; flex-wrap: wrap; align-items: flex-end; justify-content: space-between; gap: 1.25rem 2rem; }
    .cr-label { font-size: .95rem; color: rgba(22,50,31,.6); }
    .cr-h2 { margin-top: .4rem; font-size: clamp(1.8rem, 4vw, 3rem); font-weight: 800; line-height: 1.05; letter-spacing: -.03em; }

    .cr-filters { display: flex; flex-wrap: wrap; gap: .5rem; }
    .cr-filter { padding: .45rem 1.05rem; border: 1px solid rgba(22,50,31,.28); border-radius: 99px; background: transparent; font: inherit; font-weight: 600; font-size: .88rem; color: var(--hijau); cursor: pointer; transition: background .2s, border-color .2s, color .2s; }
    .cr-filter:hover { border-color: var(--hijau); }
    .cr-filter[aria-pressed="true"] { background: var(--hijau); border-color: var(--hijau); color: var(--krem); }

    .cr-grid { margin-top: 2rem; display: grid; grid-template-columns: repeat(auto-fill, minmax(min(100%, 22rem), 1fr)); gap: 1.25rem; }
    .cr-card { position: relative; overflow: hidden; display: flex; flex-direction: column; min-height: 15rem; padding: 1.75rem 1.5rem 1.4rem; border: 1px solid rgba(22,50,31,.14); border-radius: .5rem; background: #fff; color: var(--hijau); transition: transform .3s, border-color .3s, box-shadow .3s; }
    .cr-card[hidden] { display: none; }
    .cr-card::before { content: ''; position: absolute; left: 1.5rem; top: -1px; width: 2.5rem; height: 3px; background: var(--emas); }
    .cr-no { position: absolute; top: 1rem; right: 1.25rem; font-size: 2.6rem; font-weight: 800; line-height: 1; letter-spacing: -.05em; color: rgba(22,50,31,.1); }
    .cr-badge { align-self: flex-start; margin-bottom: .9rem; padding: .15rem .75rem; border-radius: 99px; background: var(--emas); color: var(--hijau); font-size: .75rem; font-weight: 700; }
    .cr-card h2 { padding-right: 3rem; font-size: clamp(1.3rem, 2.2vw, 1.7rem); font-weight: 800; letter-spacing: -.025em; line-height: 1.15; }
    .cr-meta { list-style: none; margin: 1rem 0 0; padding: 0; display: flex; flex-wrap: wrap; gap: .5rem; }
    .cr-meta li { display: inline-flex; align-items: center; gap: .4rem; padding: .25rem .75rem; border-radius: 99px; background: var(--krem); font-size: .85rem; color: rgba(22,50,31,.82); }
    .cr-meta svg { flex: none; width: .95rem; height: .95rem; color: var(--hijau-muda); }
    .cr-go { margin-top: auto; padding-top: 1.5rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem; font-weight: 700; font-size: .95rem; }
    .cr-arrow { flex: none; display: flex; align-items: center; justify-content: center; width: 2.4rem; height: 2.4rem; border: 1px solid rgba(22,50,31,.3); border-radius: 50%; transition: background .25s, border-color .25s, color .25s, transform .25s; }
    .cr-arrow svg { width: 1.05rem; height: 1.05rem; }
    .cr-card:hover .cr-arrow { background: var(--emas); border-color: var(--emas); color: var(--hijau); }
    @media (hover: hover) and (prefers-reduced-motion: no-preference) {
        .cr-card:hover { transform: translateY(-5px); border-color: var(--hijau-muda); box-shadow: 0 18px 36px rgba(22,50,31,.14); }
        .cr-card:hover .cr-arrow { transform: translateX(3px); }
    }

    /* Kosong */
    .cr-empty { margin-top: 2rem; padding: clamp(2.5rem, 7vh, 4rem) 1.5rem; text-align: center; border: 1px dashed rgba(22,50,31,.3); border-radius: .5rem; background: #fff; }
    .cr-empty-ico { display: inline-flex; align-items: center; justify-content: center; width: 3.5rem; height: 3.5rem; border-radius: 50%; background: rgba(227,167,47,.18); color: var(--hijau); }
    .cr-empty-ico svg { width: 1.6rem; height: 1.6rem; }
    .cr-empty h2 { margin-top: 1rem; font-size: clamp(1.4rem, 2.6vw, 2rem); font-weight: 800; letter-spacing: -.02em; line-height: 1.15; }
    .cr-empty p { margin: .6rem auto 0; max-width: 32rem; color: rgba(22,50,31,.72); }

    /* ===== Ajakan kontak (section gelap) ===== */
    .cr-cta-sec { padding: clamp(3.5rem, 9vh, 6rem) var(--gutter); background: #0f2417; }
    .cr-cta-grid { display: grid; grid-template-columns: minmax(0, 1fr); gap: 2rem; align-items: center; }
    @media (min-width: 900px) { .cr-cta-grid { grid-template-columns: minmax(0, 1.4fr) minmax(0, 1fr); gap: 4rem; } }
    .cr-cta-grid h2 { max-width: 20ch; font-size: clamp(1.8rem, 4vw, 3rem); font-weight: 800; line-height: 1.05; letter-spacing: -.03em; }
    .cr-cta-grid p { margin-top: 1rem; max-width: 36rem; color: rgba(242,244,236,.75); }
    .cr-btns { display: flex; flex-wrap: wrap; gap: .9rem; }
    @media (min-width: 900px) { .cr-btns { justify-content: flex-end; } }
    .cr-btn { display: inline-flex; align-items: center; padding: .85rem 1.6rem; border: 1px solid rgba(242,244,236,.6); border-radius: .25rem; font-weight: 700; font-size: .95rem; transition: background .2s, border-color .2s, color .2s; }
    .cr-btn:hover { background: var(--krem); border-color: var(--krem); color: var(--hijau); }
    .cr-btn--gold { background: var(--emas); border-color: var(--emas); color: var(--hijau); }
    .cr-btn--gold:hover { background: #f0b84a; border-color: #f0b84a; color: var(--hijau); }
</style>

<div class="cr">

    {{-- HERO berfoto --}}
    <header class="cr-hero">
        <img class="cr-bg" src="{{ asset('images/' . $fotoKarir) }}" alt="" onerror="this.style.display='none'">
        <div class="cr-hero-inner">
            <nav class="cr-crumb" aria-label="Breadcrumb">
                <a href="{{ url('/') }}">Beranda</a>
                <span aria-hidden="true">/</span>
                <span aria-current="page">Karir</span>
            </nav>
            <h1 class="cr-title">Karir</h1>
            <p class="cr-lead">
                Bergabunglah dengan PT Sahabat Sawit Rokan Sejahtera dan tumbuh bersama kami di industri perkebunan dan kelapa sawit Rokan Hilir, Riau.
            </p>

            <div class="cr-hero-row">
                @if ($lowongan->isNotEmpty())
                    <span class="cr-chip">{{ $lowongan->count() }} lowongan dibuka</span>
                    <a class="cr-link" href="#lowongan">Lihat lowongan</a>
                @endif
            </div>
        </div>
    </header>

    {{-- DAFTAR LOWONGAN (section terang) --}}
    <section class="cr-list-sec" id="lowongan">
        @if ($lowongan->isNotEmpty())
        <div class="cr-list-head">
            <div>
                <p class="cr-label"><span id="crCount">{{ $lowongan->count() }}</span> lowongan dibuka</p>
                <h2 class="cr-h2">Posisi yang Tersedia</h2>
            </div>

            @if ($lokasiList->count() > 1)
            <div class="cr-filters" role="group" aria-label="Filter lokasi">
                <button type="button" class="cr-filter" data-cr-filter="*" aria-pressed="true">Semua</button>
                @foreach ($lokasiList as $lok)
                    <button type="button" class="cr-filter" data-cr-filter="{{ $lok }}" aria-pressed="false">{{ $lok }}</button>
                @endforeach
            </div>
            @endif
        </div>

        <div class="cr-grid">
            @foreach ($lowongan as $l)
            @php
                // Sisa hari sampai batas lamaran (null bila tidak ada batas waktu)
                $sisa = $l->batas_waktu
                    ? (int) now()->startOfDay()->diffInDays($l->batas_waktu->copy()->startOfDay(), false)
                    : null;
                $segera = $sisa !== null && $sisa >= 0 && $sisa <= 7;
            @endphp
            <a class="cr-card" href="{{ route('career.show', $l->slug) }}" data-cr-card data-lokasi="{{ $l->lokasi }}">
                <span class="cr-no" aria-hidden="true">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>

                @if ($segera)
                    <span class="cr-badge">{{ $sisa === 0 ? 'Berakhir hari ini' : 'Segera berakhir · ' . $sisa . ' hari lagi' }}</span>
                @endif

                <h2>{{ $l->judul }}</h2>

                <ul class="cr-meta">
                    @if ($l->lokasi)
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 21s-7-6.2-7-11a7 7 0 0114 0c0 4.8-7 11-7 11z"/><circle cx="12" cy="10" r="2.5"/></svg>
                        <span>{{ $l->lokasi }}</span>
                    </li>
                    @endif
                    @if ($l->batas_waktu)
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3.5" y="5" width="17" height="15" rx="2"/><path d="M3.5 10h17M8 3v4M16 3v4"/></svg>
                        <span>Batas lamaran {{ $l->batas_waktu->locale('id')->translatedFormat('j F Y') }}</span>
                    </li>
                    @endif
                </ul>

                <span class="cr-go">
                    Lihat detail
                    <span class="cr-arrow" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </span>
                </span>
            </a>
            @endforeach
        </div>
        @else
        <div class="cr-empty">
            <span class="cr-empty-ico" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M9 7V5a2 2 0 012-2h2a2 2 0 012 2v2M3 13h18"/></svg>
            </span>
            <h2>Belum ada lowongan yang dibuka</h2>
            <p>Belum ada lowongan yang dibuka saat ini. Silakan cek kembali nanti.</p>
        </div>
        @endif
    </section>

    {{-- AJAKAN KONTAK (section gelap) --}}
    <section class="cr-cta-sec">
        <div class="cr-cta-grid">
            <div>
                <h2>Belum menemukan posisi yang sesuai?</h2>
                <p>Informasi lowongan terbaru kami umumkan di halaman ini. Anda juga dapat menanyakan langsung kepada tim kami.</p>
            </div>
            <div class="cr-btns">
                <a class="cr-btn cr-btn--gold" href="https://wa.me/628139654581" target="_blank" rel="noopener noreferrer">Tanya via WhatsApp</a>
                <a class="cr-btn" href="{{ route('contact') }}">Halaman Kontak</a>
            </div>
        </div>
    </section>

</div>

<script>
    // Filter lokasi lowongan: tampilkan hanya kartu dengan lokasi terpilih dan perbarui jumlahnya.
    (function () {
        const chips = [...document.querySelectorAll('[data-cr-filter]')];
        const cards = [...document.querySelectorAll('[data-cr-card]')];
        const count = document.getElementById('crCount');
        if (!chips.length) return;

        chips.forEach(function (chip) {
            chip.addEventListener('click', function () {
                const value = chip.dataset.crFilter;
                chips.forEach(function (c) { c.setAttribute('aria-pressed', c === chip ? 'true' : 'false'); });
                let shown = 0;
                cards.forEach(function (card) {
                    const show = value === '*' || card.dataset.lokasi === value;
                    card.hidden = !show;
                    if (show) shown++;
                });
                if (count) count.textContent = shown;
            });
        });
    })();
</script>
@endsection