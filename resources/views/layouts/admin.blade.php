<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - PT. Sahabat Sawit Rokan Sejahtera</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @stack('styles')
</head>
<body class="admin-body">

<div class="admin-sidebar-overlay" id="sidebarOverlay"></div>

<div class="d-flex">

    <nav class="sidebar d-flex flex-column" id="adminSidebar">
        <div class="sidebar-brand">
            <img src="{{ asset('images/logo.png') }}" alt="Sahabat Sawit" class="sidebar-brand-logo">
            <div class="sidebar-brand-text">
                <span class="sidebar-brand-title">Sahabat Sawit</span>
                <span class="sidebar-brand-sub">Rokan Sejahtera</span>
            </div>
        </div>
        
        <ul class="nav flex-column sidebar-nav">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
                    <i class="bi bi-box-seam"></i> <span>Kelola Produk</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.berita.*') ? 'active' : '' }}" href="{{ route('admin.berita.index') }}">
                    <i class="bi bi-newspaper"></i> <span>Kelola Berita</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}" href="{{ route('admin.pages.index') }}">
                    <i class="bi bi-file-earmark-text"></i> <span>Kelola Halaman</span>
                </a>
            </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.csr-categories.*') ? 'active' : '' }}" href="{{ route('admin.csr-categories.index') }}">
                        <i class="bi bi-heart"></i> <span>Kelola CSR</span>
                    </a>
                </li>
            </li>
        </ul>

        <div class="mt-auto sidebar-footer">
            <a class="nav-link" href="{{ route('home') }}" target="_blank">
                <i class="bi bi-box-arrow-up-right"></i> <span></span>
            </a>
        </div>
    </nav>

    <div class="flex-grow-1 admin-main">
        <nav class="navbar navbar-admin px-3 px-md-4">
            <button class="sidebar-toggle-btn d-lg-none" id="sidebarToggle" type="button" aria-label="Buka menu">
                <i class="bi bi-list"></i>
            </button>

            <span class="navbar-page-title d-none d-md-inline">@yield('page-title', 'Dashboard')</span>

            <div class="ms-auto d-flex align-items-center gap-3">
                <div class="admin-user-chip">
                    <div class="admin-user-avatar">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <span class="d-none d-sm-inline">{{ auth()->user()->name ?? 'Admin' }}</span>
                </div>

                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="btn-admin-logout" title="Logout">
                        <i class="bi bi-box-arrow-right"></i>
                        <span class="d-none d-sm-inline">Logout</span>
                    </button>
                </form>
            </div>
        </nav>

        <main class="p-3 p-md-4">
            @if (session('success'))
                <div class="admin-alert admin-alert--success">
                    <i class="bi bi-check-circle-fill"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            @if ($errors->any())
                <div class="admin-alert admin-alert--error">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <div>
                        <strong>Periksa kembali isian kamu:</strong>
                        <ul class="mb-0 mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<script>
    const sidebar = document.getElementById('adminSidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const toggleBtn = document.getElementById('sidebarToggle');

    function openSidebar() {
        sidebar.classList.add('is-open');
        overlay.classList.add('is-visible');
    }
    function closeSidebar() {
        sidebar.classList.remove('is-open');
        overlay.classList.remove('is-visible');
    }

    toggleBtn?.addEventListener('click', openSidebar);
    overlay?.addEventListener('click', closeSidebar);
</script>

@stack('scripts')
</body>
</html>