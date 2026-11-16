<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Inventaris') - Inventaris Sekolah</title>
    <link rel="stylesheet" href="{{ asset('hummingbird-ui-kit/dist/assets/main-CsgFyUFh.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-default text-default">
    <div class="app-shell">
        <button class="app-backdrop" type="button" aria-label="Tutup menu" data-sidebar-close></button>

        <aside class="app-sidebar" id="app-sidebar">
            <a class="app-brand" href="{{ route('dashboard') }}">
                <span class="app-brand-mark" aria-hidden="true">I</span>
                <span>
                    <strong>Inventaris</strong>
                    <small>Manajemen aset sekolah</small>
                </span>
            </a>

            <div class="app-nav-label">MENU UTAMA</div>
            <nav class="app-nav" aria-label="Navigasi utama">
                <a class="app-nav-link {{ request()->routeIs('dashboard') ? 'is-active' : '' }}" href="{{ route('dashboard') }}">
                    <span class="app-nav-icon" aria-hidden="true">01</span>
                    <span>Ringkasan</span>
                </a>
                <a class="app-nav-link {{ request()->routeIs('barangs.*') ? 'is-active' : '' }}" href="{{ route('barangs.index') }}">
                    <span class="app-nav-icon" aria-hidden="true">02</span>
                    <span>Data Barang</span>
                </a>
                <a class="app-nav-link {{ request()->routeIs('laporan-kerusakan.*') ? 'is-active' : '' }}" href="{{ route('laporan-kerusakan.index') }}">
                    <span class="app-nav-icon" aria-hidden="true">03</span>
                    <span>Laporan Kerusakan</span>
                </a>
                <div class="app-nav-label app-nav-section-label">DATA MASTER</div>
                <a class="app-nav-link {{ request()->routeIs('ruangans.*') ? 'is-active' : '' }}" href="{{ route('ruangans.index') }}">
                    <span class="app-nav-icon" aria-hidden="true">04</span>
                    <span>Ruangan</span>
                </a>
                <a class="app-nav-link {{ request()->routeIs('kategori-barangs.*') ? 'is-active' : '' }}" href="{{ route('kategori-barangs.index') }}">
                    <span class="app-nav-icon" aria-hidden="true">05</span>
                    <span>Kategori Barang</span>
                </a>
            </nav>

            <div class="app-sidebar-note">
                <span class="app-sidebar-note-mark" aria-hidden="true">i</span>
                <div>
                    <strong>Data inventaris</strong>
                    <p>Kelola aset dan pantau tindak lanjut kerusakan dalam satu tempat.</p>
                </div>
            </div>

            <div class="app-sidebar-footer">
                <span class="app-online-dot"></span>
                Sistem inventaris aktif
            </div>
        </aside>

        <main class="app-main">
            <header class="app-topbar">
                <div class="app-topbar-left">
                    <button class="btn btn-icon btn-subtle-neutral app-menu-toggle" type="button" aria-label="Buka menu" aria-controls="app-sidebar" aria-expanded="false" data-sidebar-toggle>
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16" /></svg>
                    </button>
                    <div class="app-breadcrumb">
                        <span>Inventaris</span>
                        <span aria-hidden="true">/</span>
                        <strong>@yield('breadcrumb', 'Ringkasan')</strong>
                    </div>
                </div>
                <div class="app-topbar-right">
                    <span class="app-date">{{ now()->format('d/m/Y') }}</span>
                    <button class="btn btn-icon btn-subtle-neutral app-theme-toggle" type="button" aria-label="Ganti tema" data-theme-toggle>
                        <svg class="app-sun-icon" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="4" /><path d="M12 2v2m0 16v2M4.93 4.93l1.42 1.42m11.3 11.3 1.42 1.42M2 12h2m16 0h2M4.93 19.07l1.42-1.42m11.3-11.3 1.42-1.42" /></svg>
                        <svg class="app-moon-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M20.2 15.4A8.5 8.5 0 0 1 8.6 3.8 8.5 8.5 0 1 0 20.2 15.4Z" /></svg>
                    </button>
                    <div class="app-user-avatar" aria-label="Admin">AD</div>
                </div>
            </header>

            <div class="app-content">
                @if (! empty($setupWarnings))
                    <div class="app-alert app-alert-warning" role="status">
                        <span class="app-alert-icon" aria-hidden="true">!</span>
                        <div>
                            <strong>Persiapan database diperlukan</strong>
                            <ul>
                                @foreach ($setupWarnings as $warning)
                                    <li>{{ $warning }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @if (session('success'))
                    <div class="app-alert app-alert-success" role="status">
                        <span class="app-alert-icon" aria-hidden="true">✓</span>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="app-alert app-alert-danger" role="alert">
                        <span class="app-alert-icon" aria-hidden="true">!</span>
                        <div>{{ session('error') }}</div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="app-alert app-alert-danger" role="alert">
                        <span class="app-alert-icon" aria-hidden="true">!</span>
                        <div>
                            <strong>Periksa kembali data yang dimasukkan.</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>

            <footer class="app-footer">
                <span>Inventaris Sekolah</span>
                <span>Pengelolaan aset yang lebih tertata.</span>
            </footer>
        </main>
    </div>
</body>
</html>
