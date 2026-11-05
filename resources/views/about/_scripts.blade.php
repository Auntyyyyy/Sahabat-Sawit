{{-- Pelengkap gulir halaman "Tentang Kami": indikator gulir, tombol ke atas, parallax halus, posisi sub-nav sticky. --}}
<div class="ab-progress" id="abProgress" aria-hidden="true"></div>
<button type="button" class="ab-top" id="abTop" aria-label="Kembali ke atas">
    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
</button>

<script>
    (function () {
        var root = document.querySelector('.ab');
        if (!root) return;

        var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        var bar = document.getElementById('abProgress');
        var topBtn = document.getElementById('abTop');
        var hero = root.querySelector('.ab-hero');
        var bg = hero ? hero.querySelector('.ab-bg') : null;
        var tabs = root.querySelector('.ab-tabs');
        var lastNav = -1;
        var ticking = false;

        // Jika navbar situs menempel di atas (fixed/sticky), sub-nav ditempatkan tepat di bawahnya.
        function measureNav() {
            var h = 0;
            document.querySelectorAll('header, nav').forEach(function (el) {
                if (el.closest('.ab')) return;
                var cs = window.getComputedStyle(el);
                if (cs.position !== 'fixed' && cs.position !== 'sticky') return;
                var r = el.getBoundingClientRect();
                if (r.top <= 1 && r.height < 200) h = Math.max(h, r.bottom);
            });
            h = Math.max(0, Math.round(h));
            if (h !== lastNav) {
                lastNav = h;
                root.style.setProperty('--nav-h', h + 'px');
            }
        }

        function update() {
            var y = window.scrollY;
            var max = document.documentElement.scrollHeight - window.innerHeight;
            if (bar) bar.style.transform = 'scaleX(' + (max > 0 ? Math.min(y / max, 1) : 0) + ')';
            if (topBtn) topBtn.classList.toggle('is-on', y > 700);
            if (bg && hero && !reduce && y < hero.offsetHeight) {
                bg.style.transform = 'translate3d(0,' + (y * 0.15) + 'px,0)';
            }
            measureNav();
            ticking = false;
        }

        window.addEventListener('scroll', function () {
            if (!ticking) { ticking = true; window.requestAnimationFrame(update); }
        }, { passive: true });
        window.addEventListener('resize', measureNav);
        window.addEventListener('load', function () { measureNav(); update(); });

        if (topBtn) {
            topBtn.addEventListener('click', function () {
                window.scrollTo({ top: 0, behavior: reduce ? 'auto' : 'smooth' });
            });
        }

        // Pastikan tab aktif terlihat di layar sempit (sub-nav bisa digeser ke samping)
        var active = tabs ? tabs.querySelector('a.is-active') : null;
        if (active) tabs.scrollLeft = active.offsetLeft - (tabs.clientWidth - active.offsetWidth) / 2;

        measureNav();
        update();
    })();
</script>