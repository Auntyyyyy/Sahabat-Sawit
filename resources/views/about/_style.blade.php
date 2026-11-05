<style>
    /* Gaya bersama halaman "Tentang Kami" — gelap kehijauan, selaras dengan Home & Produk. Class berawalan "ab-". */
    @import url('https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,600;12..96,800&display=swap');

    html { scroll-behavior: smooth; }
    @media (prefers-reduced-motion: reduce) { html { scroll-behavior: auto; } }

    .ab {
        --hijau: #16321f; --hijau-muda: #2f5a3a; --emas: #e3a72f; --krem: #f2f4ec;
        --nav-h: 0px;       /* diisi otomatis oleh skrip bila navbar bersifat fixed/sticky */
        --tabs-h: 3.25rem;  /* perkiraan tinggi sub-navigasi */
        --gutter: max(clamp(1.25rem, 5vw, 3.5rem), calc((100vw - 80rem) / 2));
        font-family: "Bricolage Grotesque", system-ui, -apple-system, "Segoe UI", sans-serif;
        background: var(--hijau); color: var(--krem); line-height: 1.6;
    }
    .ab *, .ab *::before, .ab *::after { box-sizing: border-box; }
    .ab a { color: inherit; text-decoration: none; }
    .ab a:focus-visible, .ab button:focus-visible { outline: 3px solid var(--krem); outline-offset: 3px; }

    /* Hero (kompak: konten pertama sudah terlihat di layar pertama) */
    .ab-hero { position: relative; isolation: isolate; overflow: hidden; display: flex; align-items: flex-end; min-height: min(52vh, 28rem); background: linear-gradient(145deg, var(--hijau-muda), var(--hijau)); }
    .ab-hero::after { content: ''; position: absolute; inset: 0; z-index: -1; background: linear-gradient(90deg, rgba(15,36,23,.94) 0%, rgba(15,36,23,.7) 50%, rgba(15,36,23,.3) 100%), linear-gradient(0deg, rgba(22,50,31,.9) 0%, transparent 45%); }
    .ab-bg { position: absolute; left: 0; right: 0; top: -15%; z-index: -2; width: 100%; height: 115%; object-fit: cover; will-change: transform; }
    .ab-hero-inner { width: 100%; padding: clamp(6.5rem, 14vh, 8rem) var(--gutter) clamp(1.5rem, 4vh, 2.25rem); }
    .ab-crumb { display: flex; flex-wrap: wrap; align-items: center; gap: .5rem; font-size: .85rem; color: rgba(242,244,236,.55); }
    .ab-crumb a:hover { color: var(--krem); }
    .ab-crumb [aria-current] { color: var(--krem); }
    .ab-title { margin-top: 1rem; font-size: clamp(1.9rem, 4.2vw, 3.4rem); font-weight: 800; line-height: 1.05; letter-spacing: -.04em; max-width: 30ch; }
    .ab-lead { margin-top: 1rem; max-width: 40rem; color: rgba(242,244,236,.85); }
    .ab-pills { list-style: none; margin: 1.25rem 0 0; padding: 0; display: flex; flex-wrap: wrap; gap: .5rem; }
    .ab-pills li { font-size: .8rem; padding: .2rem .7rem; border: 1px solid rgba(242,244,236,.3); border-radius: 99px; background: rgba(15,36,23,.35); color: rgba(242,244,236,.85); -webkit-backdrop-filter: blur(6px); backdrop-filter: blur(6px); }

    /* Sub-navigasi: menempel di atas saat halaman digulir; di layar sempit bisa digeser ke samping */
    .ab-tabs { position: sticky; top: var(--nav-h); z-index: 40; display: flex; flex-wrap: nowrap; gap: 0 1.75rem; overflow-x: auto; padding: 0 var(--gutter); background: rgba(22,50,31,.94); -webkit-backdrop-filter: blur(10px); backdrop-filter: blur(10px); border-bottom: 1px solid rgba(242,244,236,.12); scrollbar-width: none; }
    .ab-tabs::-webkit-scrollbar { display: none; }
    .ab-tabs a { flex: none; padding: .9rem 0 .75rem; border-bottom: 2px solid transparent; font-weight: 600; font-size: .95rem; color: rgba(242,244,236,.6); transition: color .2s, border-color .2s; }
    .ab-tabs a:hover { color: var(--krem); }
    .ab-tabs a.is-active { color: var(--krem); border-bottom-color: var(--emas); }

    /* Section */
    .ab-sec { padding: clamp(3rem, 8vh, 5rem) var(--gutter); scroll-margin-top: calc(var(--nav-h) + var(--tabs-h)); }
    .ab-tabs + section { border-top: 0; }
    .ab-sec + .ab-sec { border-top: 1px solid rgba(242,244,236,.12); }
    .ab-split { display: grid; grid-template-columns: 1fr; gap: 2.5rem; align-items: center; }
    .ab-split-top { align-items: start; }
    @media (min-width: 900px) {
        .ab-split { grid-template-columns: 1fr 1fr; gap: 4rem; }
        .ab-split-top { grid-template-columns: 1fr 3fr; gap: 3rem; }
    }
    .ab-label { font-size: .95rem; color: rgba(242,244,236,.6); }
    .ab-h2 { margin-top: .5rem; font-size: clamp(1.8rem, 4vw, 3rem); font-weight: 800; line-height: 1.05; letter-spacing: -.03em; max-width: 20ch; }
    .ab-p { margin-top: 1.25rem; max-width: 40rem; color: rgba(242,244,236,.78); text-align: justify; }
    .ab-p strong, .ab-prose strong { color: var(--krem); font-weight: 600; }
    .ab-prose { margin-top: 2rem; max-width: 44rem; color: rgba(242,244,236,.78); text-align: justify; }
    .ab-prose p + p { margin-top: 1.25rem; }
    .ab-photo { display: block; width: 100%; height: clamp(280px, 38vw, 460px); object-fit: cover; border-radius: .25rem; background: var(--hijau-muda); }

    .ab-facts { margin: 2rem 0 0; display: grid; grid-template-columns: 1fr; gap: 1.5rem 3rem; max-width: 44rem; }
    @media (min-width: 640px) { .ab-facts { grid-template-columns: 1fr 1fr; } }
    .ab-facts div { padding-top: 1rem; border-top: 1px solid rgba(242,244,236,.2); }
    .ab-facts dt { font-size: clamp(1.8rem, 3.5vw, 2.6rem); font-weight: 800; letter-spacing: -.03em; line-height: 1.1; }
    .ab-facts dd { margin: .25rem 0 0; font-size: .95rem; color: rgba(242,244,236,.65); }

    /* Chip lompat antar-bagian (dipakai di halaman yang panjang) */
    .ab-jump { display: flex; flex-wrap: wrap; gap: .5rem; margin-top: 1.5rem; }
    .ab-jump a { padding: .3rem .9rem; border: 1px solid rgba(242,244,236,.3); border-radius: 99px; font-size: .85rem; font-weight: 600; color: rgba(242,244,236,.85); transition: background .2s, border-color .2s, color .2s; }
    .ab-jump a:hover { background: var(--emas); border-color: var(--emas); color: var(--hijau); }

    /* Indikator gulir & tombol kembali ke atas */
    .ab-progress { position: fixed; top: 0; left: 0; right: 0; z-index: 70; height: 3px; background: var(--emas); transform: scaleX(0); transform-origin: 0 50%; pointer-events: none; }
    .ab-top { position: fixed; right: 1.25rem; bottom: 1.25rem; z-index: 60; display: flex; align-items: center; justify-content: center; width: 2.9rem; height: 2.9rem; border: 1px solid rgba(242,244,236,.35); border-radius: 50%; background: rgba(15,36,23,.88); color: var(--krem); cursor: pointer; opacity: 0; transform: translateY(10px); pointer-events: none; transition: opacity .25s, transform .25s, background .2s, color .2s; -webkit-backdrop-filter: blur(6px); backdrop-filter: blur(6px); }
    .ab-top.is-on { opacity: 1; transform: none; pointer-events: auto; }
    .ab-top:hover { background: var(--emas); border-color: var(--emas); color: var(--hijau); }
</style>