<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — PT. SSRS</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    @stack('styles')
</head>
<body class="admin-body">

    <div class="admin-layout">

        {{-- ================= SIDEBAR ================= --}}
        <aside class="admin-sidebar">
            <a href="{{ route('admin.products.index') }}" class="admin-brand">
                <span class="brand-mark">SSRS</span>
                <span class="brand-text">
                    <span class="brand-name">Admin Panel</span>
                    <span class="brand-sub">Sahabat Sawit RS</span>
                </span>
            </a>

            <nav class="admin-nav">
                <a href="{{ route('admin.products.index') }}"
                   class="admin-nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="3.5" y="3.5" width="7" height="7" rx="2" stroke="currentColor" stroke-width="1.6"/>
                        <rect x="13.5" y="3.5" width="7" height="7" rx="2" stroke="currentColor" stroke-width="1.6"/>
                        <rect x="3.5" y="13.5" width="7" height="7" rx="2" stroke="currentColor" stroke-width="1.6"/>
                        <rect x="13.5" y="13.5" width="7" height="7" rx="2" stroke="currentColor" stroke-width="1.6"/>
                    </svg>
                    Produk
                </a>
            </nav>

            <div class="admin-sidebar-footer">
                <a href="{{ url('/') }}" class="admin-nav-link" target="_blank">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 12s3.6-6.5 8-6.5 8 6.5 8 6.5-3.6 6.5-8 6.5S4 12 4 12Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
                        <circle cx="12" cy="12" r="2.4" stroke="currentColor" stroke-width="1.6"/>
                    </svg>
                    Lihat Website
                </a>
            </div>
        </aside>

        {{-- ================= MAIN ================= --}}
        <div class="admin-main">

            <header class="admin-topbar">
                <h1 class="admin-topbar-title">@yield('page-title', 'Dashboard')</h1>
            </header>

            <main class="admin-content">

                @if (session('success'))
                    <div class="admin-alert admin-alert-success">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div class="admin-alert admin-alert-error">{{ session('error') }}</div>
                @endif

                @yield('content')

            </main>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>