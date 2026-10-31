@extends('layouts.app')

@section('title', $lowongan->judul . ' — Karir PT Sahabat Sawit')

@section('content')
@include('career._style')

@php
    $url  = urlencode(url()->current());
    $teks = urlencode($lowongan->judul . ' - PT Sahabat Sawit Rokan Sejahtera');

    // Foto latar hero: samakan dengan halaman daftar lowongan (file ada di public/images).
    $fotoKarir = 'batik.jpeg';

    // Sisa hari sampai batas lamaran (null bila tidak ada batas waktu)
    $sisa = $lowongan->batas_waktu
        ? (int) now()->startOfDay()->diffInDays($lowongan->batas_waktu->copy()->startOfDay(), false)
        : null;
    $segera = $sisa !== null && $sisa >= 0 && $sisa <= 7;
@endphp

<style>
    /* Detail lowongan — hero berfoto, deskripsi di section terang dengan kartu ringkasan yang menempel, ajakan bertanya. Class berawalan "cd-". */
    @import url('https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,600;12..96,800&display=swap');

    .cd {
        --hijau: #16321f; --hijau-muda: #2f5a3a; --emas: #e3a72f; --krem: #f2f4ec;
        --gutter: max(clamp(1.25rem, 5vw, 3.5rem), calc((100vw - 80rem) / 2));
        font-family: "Bricolage Grotesque", system-ui, -apple-system, "Segoe UI", sans-serif;
        background: var(--hijau); color: var(--krem); line-height: 1.6;
    }
    .cd *, .cd *::before, .cd *::after { box-sizing: border-box; }
    .cd a { color: inherit; text-decoration: none; }
    .cd a:focus-visible, .cd button:focus-visible { outline: 3px solid var(--emas); outline-offset: 3px; }

    /* ===== Hero ===== */
    .cd-hero { position: relative; isolation: isolate; overflow: hidden; display: flex; align-items: flex-end; min-height: min(62vh, 38rem); background: linear-gradient(145deg, var(--hijau-muda), var(--hijau)); }
    .cd-hero::after { content: ''; position: absolute; inset: 0; z-index: -1; background: linear-gradient(90deg, rgba(22,50,31,.94) 0%, rgba(22,50,31,.7) 55%, rgba(22,50,31,.35) 100%), linear-gradient(0deg, rgba(22,50,31,.85) 0%, transparent 50%); }
    .cd-bg { position: absolute; inset: 0; z-index: -2; width: 100%; height: 100%; object-fit: cover; }
    .cd-hero-inner { width: 100%; padding: clamp(7.5rem, 16vh, 10rem) var(--gutter) clamp(2.5rem, 7vh, 4rem); }
    .cd-crumb { display: flex; flex-wrap: wrap; align-items: center; gap: .5rem; font-size: .9rem; color: rgba(242,244,236,.6); }
    .cd-crumb a:hover { color: var(--krem); }
    .cd-crumb [aria-current] { color: var(--krem); }
    .cd-back { display: inline-flex; align-items: center; gap: .55rem; margin-top: 1.5rem; font-weight: 600; font-size: .95rem; border-bottom: 2px solid var(--emas); padding-bottom: .2rem; }
    .cd-back:hover { color: var(--emas); }
    .cd-back svg { width: 1.1rem; height: 1.1rem; }
    .cd-title { margin-top: 1.5rem; max-width: 20ch; font-size: clamp(2.2rem, 6.5vw, 5rem); font-weight: 800; line-height: 1.02; letter-spacing: -.04em; }
    .cd-chips { list-style: none; margin: 1.75rem 0 0; padding: 0; display: flex; flex-wrap: wrap; gap: .6rem; }
    .cd-chips li { display: inline-flex; align-items: center; gap: .5rem; padding: .4rem 1rem; border: 1px solid rgba(242,244,236,.4); border-radius: 99px; background: rgba(15,36,23,.5); font-size: .9rem; font-weight: 600; }
    .cd-chips svg { flex: none; width: 1rem; height: 1rem; color: var(--emas); }
    .cd-chips li.cd-chip-gold { background: var(--emas); border-color: var(--emas); color: var(--hijau); }

    /* ===== Isi (section terang) ===== */
    .cd-body { padding: clamp(3.5rem, 9vh, 6rem) var(--gutter); background: var(--krem); color: var(--hijau); }
    .cd-grid { display: grid; grid-template-columns: minmax(0, 1fr); gap: 2.5rem; align-items: start; }
    @media (min-width: 960px) { .cd-grid { grid-template-columns: minmax(0, 7fr) minmax(0, 4fr); gap: 4rem; } .cd-side { position: sticky; top: 7rem; } }
    .cd-label { font-size: .95rem; color: rgba(22,50,31,.6); }
    .cd-h2 { margin-top: .4rem; font-size: clamp(1.8rem, 4vw, 3rem); font-weight: 800; line-height: 1.05; letter-spacing: -.03em; }

    .cd-points { list-style: none; margin: 2rem 0 0; padding: 0; }
    .cd-points li { display: grid; grid-template-columns: 2rem minmax(0, 1fr); gap: 1rem; padding: 1.1rem 0; border-top: 1px solid rgba(22,50,31,.18); font-size: 1.05rem; line-height: 1.65; color: rgba(22,50,31,.9); }
    .cd-points li:last-child { border-bottom: 1px solid rgba(22,50,31,.18); }
    .cd-check { display: flex; align-items: center; justify-content: center; width: 1.75rem; height: 1.75rem; margin-top: .1rem; border-radius: 50%; background: rgba(227,167,47,.22); color: var(--hijau); }
    .cd-check svg { width: .95rem; height: .95rem; }

    /* Kartu ringkasan (menempel di desktop) */
    .cd-card { position: relative; padding: 1.75rem 1.5rem 1.5rem; border: 1px solid rgba(22,50,31,.14); border-radius: .5rem; background: #fff; }
    .cd-card::before { content: ''; position: absolute; left: 1.5rem; top: -1px; width: 2.5rem; height: 3px; background: var(--emas); }
    .cd-card-title { font-size: 1.15rem; font-weight: 800; letter-spacing: -.02em; }
    .cd-facts { margin: 1.1rem 0 0; }
    .cd-facts > div { padding: .85rem 0; border-top: 1px solid rgba(22,50,31,.12); }
    .cd-facts dt { font-size: .85rem; color: rgba(22,50,31,.6); }
    .cd-facts dd { margin: .15rem 0 0; font-weight: 700; line-height: 1.35; }
    .cd-actions { display: grid; gap: .6rem; margin-top: .5rem; }
    .cd-btn { display: inline-flex; align-items: center; justify-content: center; gap: .5rem; width: 100%; padding: .8rem 1.2rem; border: 1px solid var(--hijau); border-radius: .25rem; background: transparent; color: var(--hijau); font: inherit; font-weight: 700; font-size: .95rem; cursor: pointer; transition: background .2s, color .2s, border-color .2s; }
    .cd-btn:hover { background: var(--hijau); color: var(--krem); }
    .cd-btn--gold { background: var(--emas); border-color: var(--emas); }
    .cd-btn--gold:hover { background: #f0b84a; border-color: #f0b84a; color: var(--hijau); }

    .cd-share { margin-top: 1.5rem; padding-top: 1.25rem; border-top: 1px solid rgba(22,50,31,.12); }
    .cd-share-row { margin-top: .75rem; display: flex; flex-wrap: wrap; align-items: center; gap: .6rem; }
    .cd-ic { width: 42px; height: 42px; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(22,50,31,.3); border-radius: 50%; background: transparent; color: var(--hijau); font-size: 1.05rem; cursor: pointer; transition: background .2s, border-color .2s, color .2s; }
    .cd-ic:hover { background: var(--hijau); border-color: var(--hijau); color: var(--krem); }
    .cd-copied { flex-basis: 100%; min-height: 1.2em; font-size: .85rem; color: var(--hijau-muda); }

    /* ===== Ajakan bertanya (section gelap) ===== */
    .cd-cta { padding: clamp(3.5rem, 9vh, 6rem) var(--gutter); background: #0f2417; }
    .cd-cta-grid { display: grid; grid-template-columns: minmax(0, 1fr); gap: 2rem; align-items: center; }
    @media (min-width: 900px) { .cd-cta-grid { grid-template-columns: minmax(0, 1.4fr) minmax(0, 1fr); gap: 4rem; } }
    .cd-cta h2 { max-width: 20ch; font-size: clamp(1.8rem, 4vw, 3rem); font-weight: 800; line-height: 1.05; letter-spacing: -.03em; }
    .cd-cta p { margin-top: 1rem; max-width: 36rem; color: rgba(242,244,236,.75); }
    .cd-cta-btns { display: flex; flex-wrap: wrap; gap: .9rem; }
    @media (min-width: 900px) { .cd-cta-btns { justify-content: flex-end; } }
    .cd-cta-btn { display: inline-flex; align-items: center; padding: .85rem 1.6rem; border: 1px solid rgba(242,244,236,.6); border-radius: .25rem; font-weight: 700; font-size: .95rem; transition: background .2s, border-color .2s, color .2s; }
    .cd-cta-btn:hover { background: var(--krem); border-color: var(--krem); color: var(--hijau); }
    .cd-cta-btn--gold { background: var(--emas); border-color: var(--emas); color: var(--hijau); }
    .cd-cta-btn--gold:hover { background: #f0b84a; border-color: #f0b84a; }

    /* Cetak: hanya isi lowongan, latar putih, tanpa elemen interaktif */
    @media print {
        .cd-noprint { display: none !important; }
        .cd, .cd-body { background: #fff !important; color: #000 !important; }
        .cd-hero { min-height: 0; background: none; color: #000; }
        .cd-hero::after, .cd-bg { display: none; }
        .cd-hero-inner { padding: 0 0 1rem; }
        .cd-title { color: #000; font-size: 2rem; }
        .cd-crumb, .cd-crumb [aria-current] { color: #444; }
        .cd-chips li { border-color: #999; background: none; color: #000; }
        .cd-body { padding: 0; }
        .cd-grid { display: block; }
        .cd-side { position: static; margin-top: 1.5rem; }
        .cd-card { border-color: #999; }
        .cd-points li { color: #000; border-color: #ccc; }
    }
</style>

<div class="cd">

    {{-- HERO --}}
    <header class="cd-hero">
        <img class="cd-bg" src="{{ asset('images/' . $fotoKarir) }}" alt="" onerror="this.style.display='none'">
        <div class="cd-hero-inner">
            <nav class="cd-crumb cd-noprint" aria-label="Breadcrumb">
                <a href="{{ url('/') }}">Beranda</a>
                <span aria-hidden="true">/</span>
                <a href="{{ route('career') }}">Karir</a>
                <span aria-hidden="true">/</span>
                <span aria-current="page">{{ \Illuminate\Support\Str::limit($lowongan->judul, 48) }}</span>
            </nav>

            <a class="cd-back cd-noprint" href="{{ route('career') }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16l-4-4m0 0l4-4m-4 4h18"/></svg>
                Semua lowongan
            </a>

            <h1 class="cd-title">{{ $lowongan->judul }}</h1>

            <ul class="cd-chips">
                @if ($lowongan->lokasi)
                <li>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 21s-7-6.2-7-11a7 7 0 0114 0c0 4.8-7 11-7 11z"/><circle cx="12" cy="10" r="2.5"/></svg>
                    {{ $lowongan->lokasi }}
                </li>
                @endif
                @if ($lowongan->batas_waktu)
                <li>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3.5" y="5" width="17" height="15" rx="2"/><path d="M3.5 10h17M8 3v4M16 3v4"/></svg>
                    Batas lamaran {{ $lowongan->batas_waktu->locale('id')->translatedFormat('j F Y') }}
                </li>
                @endif
                @if ($segera)
                <li class="cd-chip-gold cd-noprint">{{ $sisa === 0 ? 'Berakhir hari ini' : 'Segera berakhir · ' . $sisa . ' hari lagi' }}</li>
                @endif
            </ul>
        </div>
    </header>

    {{-- DESKRIPSI + RINGKASAN (section terang) --}}
    <section class="cd-body">
        <div class="cd-grid">

            <div class="cd-main">
                <p class="cd-label">Deskripsi</p>
                <h2 class="cd-h2">Deskripsi Pekerjaan</h2>

                <ul class="cd-points">
                    @foreach ($lowongan->daftar_deskripsi as $poin)
                    <li>
                        <span class="cd-check" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <span>{{ $poin }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>

            <aside class="cd-side" aria-label="Ringkasan lowongan">
                <div class="cd-card">
                    <p class="cd-card-title">Ringkasan Lowongan</p>

                    <dl class="cd-facts">
                        <div>
                            <dt>Lokasi penempatan</dt>
                            <dd>{{ $lowongan->lokasi }}</dd>
                        </div>
                        @if ($lowongan->batas_waktu)
                        <div>
                            <dt>Batas lamaran</dt>
                            <dd>{{ $lowongan->batas_waktu->locale('id')->translatedFormat('j F Y') }}</dd>
                        </div>
                        @endif
                    </dl>

                    <div class="cd-actions cd-noprint">
                        {{-- LANGKAH 2: tombol "Apply Sekarang" (menggulir ke form) ditambahkan di sini --}}
                        <button type="button" class="cd-btn" onclick="window.print()">Cetak</button>
                    </div>

                    <div class="cd-share cd-noprint">
                        <p class="cd-label">Bagikan</p>
                        <div class="cd-share-row">
                            <a class="cd-ic" href="https://wa.me/?text={{ $teks }}%20{{ $url }}" target="_blank" rel="noopener noreferrer" aria-label="Bagikan lewat WhatsApp"><i class="bi bi-whatsapp"></i></a>
                            <a class="cd-ic" href="mailto:?subject={{ $teks }}&body={{ $url }}" aria-label="Bagikan lewat email"><i class="bi bi-envelope"></i></a>
                            <a class="cd-ic" href="https://www.facebook.com/sharer/sharer.php?u={{ $url }}" target="_blank" rel="noopener noreferrer" aria-label="Bagikan ke Facebook"><i class="bi bi-facebook"></i></a>
                            <a class="cd-ic" href="https://www.linkedin.com/sharing/share-offsite/?url={{ $url }}" target="_blank" rel="noopener noreferrer" aria-label="Bagikan ke LinkedIn"><i class="bi bi-linkedin"></i></a>
                            <button type="button" class="cd-ic" id="krCopy" aria-label="Salin tautan"><i class="bi bi-link-45deg"></i></button>
                            <span class="cd-copied" id="krCopied" role="status"></span>
                        </div>
                    </div>
                </div>
            </aside>

        </div>

        {{-- LANGKAH 2: Form Aplikasi ditambahkan di sini --}}
    </section>

    {{-- AJAKAN BERTANYA (section gelap) --}}
    <section class="cd-cta cd-noprint">
        <div class="cd-cta-grid">
            <div>
                <h2>Ada pertanyaan tentang posisi ini?</h2>
                <p>Tanyakan langsung kepada tim kami, atau lihat lowongan lain yang sedang dibuka.</p>
            </div>
            <div class="cd-cta-btns">
                <a class="cd-cta-btn cd-cta-btn--gold" href="https://wa.me/628139654581?text={{ urlencode('Halo, saya ingin bertanya tentang lowongan ' . $lowongan->judul) }}" target="_blank" rel="noopener noreferrer">Tanya via WhatsApp</a>
                <a class="cd-cta-btn" href="{{ route('career') }}">Lowongan lainnya</a>
            </div>
        </div>
    </section>

</div>

<script>
    (function () {
        const btn = document.getElementById('krCopy');
        const note = document.getElementById('krCopied');
        if (!btn) return;

        function done() {
            note.textContent = 'Tautan disalin';
            setTimeout(function () { note.textContent = ''; }, 2000);
        }

        btn.addEventListener('click', function () {
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(window.location.href).then(done);
                return;
            }
            // Cadangan untuk halaman yang dibuka lewat http biasa
            const temp = document.createElement('textarea');
            temp.value = window.location.href;
            temp.style.position = 'fixed';
            temp.style.opacity = '0';
            document.body.appendChild(temp);
            temp.select();
            try { document.execCommand('copy'); done(); } catch (e) {}
            document.body.removeChild(temp);
        });
    })();
</script>
@endsection