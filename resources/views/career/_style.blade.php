<style>
    @import url('https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,600;12..96,800&display=swap');

    .kr {
        --hijau: #16321f; --hijau-muda: #2f5a3a; --emas: #e3a72f; --krem: #f2f4ec;
        font-family: "Bricolage Grotesque", system-ui, -apple-system, "Segoe UI", sans-serif;
        background: var(--hijau);
        color: var(--krem);
        line-height: 1.6;
        min-height: 70vh;
    }
    .kr *, .kr *::before, .kr *::after { box-sizing: border-box; }
    .kr a { color: inherit; text-decoration: none; }
    .kr a:focus-visible, .kr button:focus-visible { outline: 3px solid var(--krem); outline-offset: 3px; }

    /* Ruang atas untuk navbar melayang */
    .kr-sec { padding: clamp(8rem, 18vh, 11rem) clamp(1.25rem, 5vw, 3.5rem) clamp(4rem, 10vh, 7rem); }
    .kr-inner { max-width: 80rem; margin: 0 auto; }
    .kr-title { font-size: clamp(2.4rem, 7vw, 5.5rem); font-weight: 800; line-height: 1.02; letter-spacing: -.04em; max-width: 20ch; }
    .kr-lead { margin-top: 1.5rem; max-width: 36rem; color: rgba(242,244,236,.75); }

    /* Daftar lowongan */
    .kr-list { margin-top: clamp(2.5rem, 6vh, 4rem); }
    .kr-row {
        display: grid; grid-template-columns: 1fr; gap: .5rem 3rem; align-items: baseline;
        padding: 1.5rem 0;
        border-top: 1px solid rgba(242,244,236,.15);
    }
    .kr-row:last-child { border-bottom: 1px solid rgba(242,244,236,.15); }
    .kr-row h2 { font-size: clamp(1.4rem, 2.6vw, 2.2rem); font-weight: 700; letter-spacing: -.02em; line-height: 1.15; transition: color .25s; }
    .kr-row:hover h2 { color: var(--emas); }
    .kr-meta { display: flex; flex-direction: column; font-size: .95rem; color: rgba(242,244,236,.65); }
    @media (min-width: 900px) { .kr-row { grid-template-columns: 2fr 1fr; } }
    .kr-empty { margin-top: clamp(2.5rem, 6vh, 4rem); padding: 2.5rem 1rem; text-align: center; color: rgba(242,244,236,.6); border: 1px solid rgba(242,244,236,.15); border-radius: .25rem; }

    /* Detail lowongan */
    .kr-back { display: inline-flex; align-items: center; gap: .5rem; margin-bottom: 1.5rem; font-size: .95rem; color: rgba(242,244,236,.65); }
    .kr-back:hover { color: var(--emas); }
    .kr-back svg, .kr-link svg { width: 16px; height: 16px; }
    .kr-split { margin-top: clamp(2.5rem, 6vh, 4rem); display: grid; grid-template-columns: 1fr; gap: 1.5rem; }
    @media (min-width: 900px) { .kr-split { grid-template-columns: 1fr 3fr; gap: 3rem; } }
    .kr-label { font-size: .95rem; color: rgba(242,244,236,.6); }
    .kr-points { margin: 0; padding-left: 1.25rem; max-width: 40rem; }
    .kr-points li { margin-bottom: .75rem; color: rgba(242,244,236,.85); }
    .kr-info { margin-top: 1.5rem; max-width: 40rem; color: rgba(242,244,236,.85); }
    .kr-actions { margin-top: 2.5rem; display: flex; flex-wrap: wrap; gap: 1.75rem; }
    .kr-link {
        display: inline-flex; align-items: center; gap: .55rem;
        background: none; border: 0; border-bottom: 2px solid var(--emas); padding: 0 0 .2rem; border-radius: 0;
        color: var(--krem); font: inherit; font-weight: 600; font-size: .95rem; cursor: pointer;
    }
    .kr-link:hover { color: var(--emas); }
    .kr-share { margin-top: 3rem; }
    .kr-share-row { margin-top: .75rem; display: flex; flex-wrap: wrap; align-items: center; gap: .6rem; }
    .kr-ic {
        width: 44px; height: 44px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        border: 1px solid rgba(242,244,236,.35); background: transparent; color: var(--krem);
        font-size: 1.05rem; cursor: pointer;
        transition: background .2s, border-color .2s, color .2s;
    }
    .kr-ic:hover { background: var(--emas); border-color: var(--emas); color: var(--hijau); }
    .kr-copied { font-size: .85rem; color: var(--emas); }

    /* Cetak: sembunyikan navbar, footer, dan tombol; tampilkan teks hitam di kertas putih */
    @media print {
        #main-navbar, .footer-ssrs, .kr-noprint { display: none !important; }
        .kr { background: #fff; color: #000; }
        .kr * { color: #000 !important; }
        .kr-sec { padding-top: 1rem; }
    }
</style>