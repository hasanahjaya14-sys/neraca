<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Rekonsiliasi PDRB') — BPS Kalimantan Tengah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --sidebar-w: 240px;
            --sidebar-w-collapsed: 64px;
            --transition: 200ms ease;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: #f1f5f9;
            min-height: 100vh;
            margin: 0;
        }

        /* ── SIDEBAR ── */
        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: var(--sidebar-w);
            background: #1e293b;
            display: flex;
            flex-direction: column;
            transition: width var(--transition);
            z-index: 50;
            overflow: hidden;
        }

        #sidebar.collapsed {
            width: var(--sidebar-w-collapsed);
        }

        /* brand */
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 20px 16px;
            border-bottom: 1px solid #334155;
            min-height: 68px;
            white-space: nowrap;
        }

        .brand-icon {
            width: 32px;
            height: 32px;
            background: #3b82f6;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .brand-icon svg {
            width: 18px;
            height: 18px;
            color: white;
        }

        .brand-text {
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .brand-title {
            font-size: 13px;
            font-weight: 700;
            color: #f8fafc;
            letter-spacing: 0.02em;
            line-height: 1.2;
        }

        .brand-sub {
            font-size: 10px;
            color: #94a3b8;
            margin-top: 2px;
        }

        /* nav */
        .sidebar-nav {
            flex: 1;
            padding: 12px 8px;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .nav-section-label {
            font-size: 10px;
            font-weight: 600;
            color: #475569;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 8px 8px 4px;
            white-space: nowrap;
            overflow: hidden;
            transition: opacity var(--transition);
        }

        #sidebar.collapsed .nav-section-label {
            opacity: 0;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 10px;
            border-radius: 8px;
            color: #94a3b8;
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            white-space: nowrap;
            transition: background var(--transition), color var(--transition);
            position: relative;
        }

        .nav-item:hover {
            background: #334155;
            color: #e2e8f0;
        }

        .nav-item.active {
            background: #1d4ed8;
            color: #ffffff;
        }

        .nav-item svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        .nav-label {
            overflow: hidden;
            transition: opacity var(--transition), max-width var(--transition);
            max-width: 180px;
        }

        #sidebar.collapsed .nav-label {
            opacity: 0;
            max-width: 0;
        }

        .nav-badge {
            margin-left: auto;
            background: #ef4444;
            color: white;
            font-size: 10px;
            font-weight: 700;
            padding: 1px 6px;
            border-radius: 99px;
            flex-shrink: 0;
            transition: opacity var(--transition);
        }

        #sidebar.collapsed .nav-badge {
            opacity: 0;
        }

        /* tooltip saat collapsed */
        #sidebar.collapsed .nav-item::after {
            content: attr(data-tooltip);
            position: absolute;
            left: calc(var(--sidebar-w-collapsed) + 8px);
            background: #1e293b;
            color: #e2e8f0;
            font-size: 12px;
            padding: 5px 10px;
            border-radius: 6px;
            white-space: nowrap;
            pointer-events: none;
            opacity: 0;
            transition: opacity 150ms;
            z-index: 100;
        }

        #sidebar.collapsed .nav-item:hover::after {
            opacity: 1;
        }

        /* toggle button */
        .sidebar-footer {
            border-top: 1px solid #334155;
            padding: 12px 8px;
        }

        #toggleSidebar {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 9px 10px;
            border-radius: 8px;
            background: transparent;
            border: none;
            color: #64748b;
            font-size: 13px;
            cursor: pointer;
            white-space: nowrap;
            transition: background var(--transition), color var(--transition);
        }

        #toggleSidebar:hover {
            background: #334155;
            color: #e2e8f0;
        }

        #toggleSidebar svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
            transition: transform var(--transition);
        }

        #sidebar.collapsed #toggleSidebar svg {
            transform: rotate(180deg);
        }

        #toggleSidebar .toggle-label {
            transition: opacity var(--transition), max-width var(--transition);
            max-width: 180px;
            overflow: hidden;
        }

        #sidebar.collapsed #toggleSidebar .toggle-label {
            opacity: 0;
            max-width: 0;
        }

        /* ── MAIN ── */
        #main {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left var(--transition);
        }

        #main.expanded {
            margin-left: var(--sidebar-w-collapsed);
        }

        /* topbar */
        .topbar {
            background: white;
            border-bottom: 1px solid #e2e8f0;
            padding: 0 24px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 40;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: #64748b;
        }

        .breadcrumb .current {
            color: #1e293b;
            font-weight: 600;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .topbar-btn {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #64748b;
            cursor: pointer;
            position: relative;
            transition: background 150ms;
        }

        .topbar-btn:hover {
            background: #f1f5f9;
            color: #1e293b;
        }

        .topbar-btn svg {
            width: 17px;
            height: 17px;
        }

        .notif-dot {
            position: absolute;
            top: 7px;
            right: 7px;
            width: 6px;
            height: 6px;
            background: #ef4444;
            border-radius: 50%;
            border: 1.5px solid white;
        }

        .user-pill {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 6px 10px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            cursor: pointer;
            transition: background 150ms;
        }

        .user-pill:hover {
            background: #f1f5f9;
        }

        .user-avatar {
            width: 26px;
            height: 26px;
            background: #3b82f6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: 700;
            color: white;
        }

        .user-info {
            font-size: 12px;
        }

        .user-name {
            font-weight: 600;
            color: #1e293b;
            line-height: 1.2;
        }

        .user-role {
            color: #94a3b8;
            font-size: 10px;
        }

        /* page content */
        .page-content {
            flex: 1;
            padding: 28px 28px;
        }
    </style>
</head>

<body>

    {{-- ── SIDEBAR ── --}}
    <aside id="sidebar">

        {{-- Brand --}}
        <div class="sidebar-brand">
            <div class="brand-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0
                    002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0
                    002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2
                    2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
            <div class="brand-text">
                <span class="brand-title">Rekonsiliasi PDRB</span>
                <span class="brand-sub">BPS Kalimantan Tengah</span>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="sidebar-nav">

            {{-- Menu Utama --}}
            <div class="nav-section-label">Utama</div>

            <a href="{{ route('dashboard') }}" data-tooltip="Dashboard"
                class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2
                    2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0
                    011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="nav-label">Dashboard</span>
            </a>

            <a href="{{ route('monitoring.index') }}" data-tooltip="Monitoring Kabko"
                class="nav-item {{ request()->routeIs('monitoring.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0
                    012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0
                    01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span class="nav-label">Monitoring Kabko</span>
                {{-- contoh badge notif --}}
                <span class="nav-badge">3</span>
            </a>

            {{-- Pengisian Data --}}
            <div class="nav-section-label" style="margin-top:8px;">Pengisian</div>

            <a href="{{ route('pengisian.index') }}" data-tooltip="Pengisian Data"
                class="nav-item {{ request()->routeIs('pengisian.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0
                    002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828
                    15H9v-2.828l8.586-8.586z" />
                </svg>
                <span class="nav-label">Pengisian Data</span>
            </a>

            <a href="{{ route('rekonsiliasi.index') }}" data-tooltip="Rekonsiliasi"
                class="nav-item {{ request()->routeIs('rekonsiliasi.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0
                    0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357
                    2H15" />
                </svg>
                <span class="nav-label">Rekonsiliasi</span>
            </a>

            {{-- Konfigurasi --}}
            <div class="nav-section-label" style="margin-top:8px;">Konfigurasi</div>

            <a href="{{ route('formula.index') }}" data-tooltip="Formula Builder"
                class="nav-item {{ request()->routeIs('formula.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                </svg>
                <span class="nav-label">Formula Builder</span>
            </a>

            <a href="{{ route('metadata.index') }}" data-tooltip="Metadata"
                class="nav-item {{ request()->routeIs('metadata.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7
                    7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828
                    0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
                <span class="nav-label">Metadata</span>
            </a>

            <a href="{{ route('data.index') }}" data-tooltip="Data"
                class="nav-item {{ request()->routeIs('data.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79
                    8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79
                    8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8
                    4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                </svg>
                <span class="nav-label">Data</span>
            </a>

            {{-- Admin --}}
            <div class="nav-section-label" style="margin-top:8px;">Admin</div>

            <a href="{{ route('users.index') }}" data-tooltip="Manajemen User"
                class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0
                    0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4
                    4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span class="nav-label">Manajemen User</span>
            </a>

        </nav>

        {{-- Toggle Button --}}
        <div class="sidebar-footer">
            <button id="toggleSidebar">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                </svg>
                <span class="toggle-label">Tutup Panel</span>
            </button>
        </div>

    </aside>

    {{-- ── MAIN ── --}}
    <div id="main">

        {{-- Topbar --}}
        <header class="topbar">

            <div class="topbar-left">
                <div class="breadcrumb">
                    <span>BPS Kalteng</span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="current">@yield('page-title', 'Halaman')</span>
                </div>
            </div>

            <div class="topbar-right">

                {{-- Notifikasi --}}
                <div class="topbar-btn" title="Notifikasi">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118
                        14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0
                        10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0
                        .538-.214 1.055-.595 1.436L4 17h5m6
                        0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span class="notif-dot"></span>
                </div>

                {{-- User --}}
                <div class="user-pill">
                    <div class="user-avatar">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                    </div>
                    <div class="user-info">
                        <div class="user-name">
                            {{ auth()->user()->name ?? 'User' }}
                        </div>
                        <div class="user-role">
                            {{ ucfirst(auth()->user()->role ?? 'provinsi') }}
                        </div>
                    </div>
                </div>

            </div>

        </header>

        {{-- Page Content --}}
        <main class="page-content">
            @yield('content')
        </main>

    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const main = document.getElementById('main');
        const toggle = document.getElementById('toggleSidebar');

        const STORAGE_KEY = 'sidebar_collapsed';

        // restore state
        if (localStorage.getItem(STORAGE_KEY) === 'true') {
            sidebar.classList.add('collapsed');
            main.classList.add('expanded');
        }

        toggle.addEventListener('click', () => {
            const isCollapsed = sidebar.classList.toggle('collapsed');
            main.classList.toggle('expanded', isCollapsed);
            localStorage.setItem(STORAGE_KEY, isCollapsed);
        });
    </script>

    @stack('scripts')

</body>

</html>