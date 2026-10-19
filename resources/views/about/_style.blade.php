<style>
    /* Gaya halaman "Tentang Kami" — disamakan dengan Home & Produk (gelap kehijauan). Class berawalan "ab-". */
    @import url('https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,600;12..96,800&display=swap');

    .ab {
        --hijau: #16321f; --hijau-muda: #2f5a3a; --emas: #e3a72f; --krem: #f2f4ec;
        --gutter: max(clamp(1.25rem, 5vw, 3.5rem), calc((100vw - 80rem) / 2));
        font-family: "Bricolage Grotesque", system-ui, -apple-system, "Segoe UI", sans-serif;
        background: var(--hijau); color: var(--krem); line-height: 1.6;
    }
    .ab *, .ab *::before, .ab *::after { box-sizing: border-box; }
    .ab a { color: inherit; text-decoration: none; }
    .ab a:focus-visible, .ab button:focus-visible { outline: 3px solid var(--krem); outline-offset: 3px; }

    /* Hero */
    .ab-hero { padding: clamp(8rem, 18vh, 11rem) var(--gutter) clamp(3rem, 8vh, 5rem); }
    .ab-crumb { display: flex; flex-wrap: wrap; align-items: center; gap: .5rem; font-size: .9rem; color: rgba(242,244,236,.6); }
    .ab-crumb a:hover { color: var(--krem); }
    .ab-crumb [aria-current] { color: var(--krem); }
    .ab-title { margin-top: 1.5rem; font-size: clamp(2.4rem, 7vw, 5.5rem); font-weight: 800; line-height: 1.02; letter-spacing: -.04em; max-width: 18ch; }
    .ab-lead { margin-top: 1.5rem; max-width: 38rem; color: rgba(242,244,236,.75); }
    .ab-pills { list-style: none; margin: 2rem 0 0; padding: 0; display: flex; flex-wrap: wrap; gap: .6rem; }
    .ab-pills li { font-size: .85rem; padding: .3rem .85rem; border: 1px solid rgba(242,244,236,.25); border-radius: 99px; color: rgba(242,244,236,.8); }

    /* Sub-navigasi */
    .ab-tabs { margin-top: 2.5rem; padding-top: 1.25rem; border-top: 1px solid rgba(242,244,236,.15); display: flex; flex-wrap: wrap; gap: .5rem 1.75rem; }
    .ab-tabs a { padding-bottom: .2rem; border-bottom: 2px solid transparent; font-weight: 600; font-size: .95rem; color: rgba(242,244,236,.6); transition: color .2s, border-color .2s; }
    .ab-tabs a:hover { color: var(--krem); }
    .ab-tabs a.is-active { color: var(--krem); border-bottom-color: var(--emas); }

    /* Section */
    .ab-sec { padding: clamp(4rem, 10vh, 7rem) var(--gutter); }
    .ab-hero + .ab-sec, .ab-sec + .ab-sec { border-top: 1px solid rgba(242,244,236,.12); }
    .ab-split { display: grid; grid-template-columns: 1fr; gap: 2.5rem; align-items: center; }
    .ab-split-top { align-items: start; }
    @media (min-width: 900px) {
        .ab-split { grid-template-columns: 1fr 1fr; gap: 4rem; }
        .ab-split-top { grid-template-columns: 1fr 3fr; gap: 3rem; }
    }
    .ab-label { font-size: .95rem; color: rgba(242,244,236,.6); }
    .ab-h2 { margin-top: .5rem; font-size: clamp(1.8rem, 4vw, 3rem); font-weight: 800; line-height: 1.05; letter-spacing: -.03em; max-width: 20ch; }
    .ab-p { margin-top: 1.25rem; max-width: 40rem; color: rgba(242,244,236,.78); }
    .ab-p strong, .ab-prose strong { color: var(--krem); font-weight: 600; }
    .ab-prose { margin-top: 2rem; max-width: 44rem; color: rgba(242,244,236,.78); }
    .ab-prose p + p { margin-top: 1.25rem; }
    .ab-photo { display: block; width: 100%; height: clamp(280px, 38vw, 460px); object-fit: cover; border-radius: .25rem; background: var(--hijau-muda); }

    .ab-facts { margin: 2rem 0 0; display: grid; grid-template-columns: 1fr; gap: 1.5rem 3rem; max-width: 44rem; }
    @media (min-width: 640px) { .ab-facts { grid-template-columns: 1fr 1fr; } }
    .ab-facts div { padding-top: 1rem; border-top: 1px solid rgba(242,244,236,.2); }
    .ab-facts dt { font-size: clamp(1.8rem, 3.5vw, 2.6rem); font-weight: 800; letter-spacing: -.03em; line-height: 1.1; }
    .ab-facts dd { margin: .25rem 0 0; font-size: .95rem; color: rgba(242,244,236,.65); }
</style>