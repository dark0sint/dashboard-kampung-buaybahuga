<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — Kecamatan Buay Bahuga</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
</head>
<body>
<div class="bb-shell">
    <aside class="bb-sidebar" id="bbSidebar">
        <div class="bb-brand">
            <span class="bb-brand-mark">BB</span>
            <div>
                <div class="bb-brand-title">Buay Bahuga</div>
                <div class="bb-brand-sub">Sistem Data 9 Kampung</div>
            </div>
        </div>
        <nav class="bb-nav">
            <a href="{{ route('dashboard') }}" class="bb-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i> Ringkasan
            </a>
            <a href="{{ route('kampung.index') }}" class="bb-nav-link {{ request()->routeIs('kampung.*') ? 'active' : '' }}">
                <i class="bi bi-signpost-split"></i> Data Kampung
            </a>
            <a href="{{ route('penduduk.index') }}" class="bb-nav-link {{ request()->routeIs('penduduk.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Data Penduduk
            </a>
            <a href="{{ route('laporan.index') }}" class="bb-nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                <i class="bi bi-clipboard-data"></i> Laporan Bulanan
            </a>
            <a href="{{ route('integrasi.rekap') }}" class="bb-nav-link {{ request()->routeIs('integrasi.*') ? 'active' : '' }}">
                <i class="bi bi-diagram-3"></i> Integrasi Kecamatan
            </a>
            <div class="bb-nav-divider"></div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bb-nav-link bb-nav-logout"><i class="bi bi-box-arrow-left"></i> Keluar</button>
            </form>
        </nav>
        <div class="bb-sidebar-foot">
            Kabupaten Way Kanan · Lampung
        </div>
    </aside>

    <div class="bb-main">
        <header class="bb-topbar">
            <button class="bb-burger d-lg-none" id="bbBurger" aria-label="Buka menu"><i class="bi bi-list"></i></button>
            <h1 class="bb-page-title">@yield('title', 'Ringkasan')</h1>
            <div class="bb-topbar-right">
                <span class="bb-user-role">{{ auth()->user()?->role === 'admin_kecamatan' ? 'Admin Kecamatan' : 'Operator Kampung' }}</span>
                <div class="bb-user-avatar">{{ strtoupper(substr(auth()->user()?->name ?? 'U', 0, 1)) }}</div>
            </div>
        </header>

        <main class="bb-content">
            @if (session('success'))
                <div class="alert alert-bb-success" role="alert">
                    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-bb-error" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<div class="bb-overlay" id="bbOverlay"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    const burger = document.getElementById('bbBurger');
    const sidebar = document.getElementById('bbSidebar');
    const overlay = document.getElementById('bbOverlay');
    function toggleSidebar() {
        sidebar.classList.toggle('open');
        overlay.classList.toggle('show');
    }
    burger?.addEventListener('click', toggleSidebar);
    overlay?.addEventListener('click', toggleSidebar);
</script>
@yield('scripts')
</body>
</html>
