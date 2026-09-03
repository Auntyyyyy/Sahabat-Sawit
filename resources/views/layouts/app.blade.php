<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PT. SAHABAT SAWIT ROKAN SEJAHTERA')</title>
    <meta name="description" content="@yield('description', 'PT Sahabat Sawit adalah perusahaan perkebunan kelapa sawit yang berlokasi di Kabupaten Rokan Hilir, Provinsi Riau, berkomitmen pada praktik perkebunan yang profesional dan berkelanjutan.')">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (CDN, dev only — pada produksi gunakan build Tailwind via npm) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-green': '#1F5F3B',
                        'secondary-green': '#2E7D32',
                        'dark-green': '#123524',
                        'light-green': '#8DBF44',
                        'palm-leaf': '#4F772D',
                        'gold': '#D4A017',
                        'cream': '#F5F1E8',
                        'dark-text': '#1F2933',
                        'gray-text': '#6B7280',
                        'body-bg': '#F8FAF7',
                    },
                    fontFamily: {
                        heading: ['Poppins', 'sans-serif'],
                        body: ['Inter', 'sans-serif'],
                    },
                    borderRadius: {
                        'brand': '16px',
                    },
                }
            }
        }
    </script>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @stack('styles')
</head>
<body class="font-body text-dark-text bg-body-bg antialiased">

    @include('components.navbar')

    <main>
        @yield('content')
    </main>

    @include('components.footer')

    <script>
        // Smooth scroll untuk anchor link
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });

        // Navbar shadow saat discroll
        const navbar = document.getElementById('main-navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 10) {
                navbar.classList.add('shadow-md');
            } else {
                navbar.classList.remove('shadow-md');
            }
        });

    
        // Statistik counter animasi sederhana (opsional, dipakai di section keberlanjutan/hero)
        function animateCounters() {
            document.querySelectorAll('[data-counter]').forEach(el => {
                const target = el.getAttribute('data-counter');
                const numeric = parseInt(target.replace(/\D/g, '')) || 0;
                if (numeric === 0) { return; }
                let current = 0;
                const step = Math.max(1, Math.ceil(numeric / 60));
                const suffix = target.replace(/[0-9]/g, '');
                const timer = setInterval(() => {
                    current += step;
                    if (current >= numeric) {
                        current = numeric;
                        clearInterval(timer);
                    }
                    el.textContent = current + suffix;
                }, 20);
            });
        }
        const counterSection = document.querySelector('[data-counter-section]');
        if (counterSection) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        animateCounters();
                        observer.disconnect();
                    }
                });
            }, { threshold: 0.3 });
            observer.observe(counterSection);
        }
    </script>
    @stack('scripts')
</body>
</html>
