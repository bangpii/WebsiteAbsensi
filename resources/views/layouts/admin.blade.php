<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — SMKN 8 Medan</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        :root {
            --sidebar-width: 272px;
            --primary: #1D4ED8;
            --primary-dark: #1E3A8A;
            --darker: #0F172A;
            --bg: #F1F5F9;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(148,163,184,0.3); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(148,163,184,0.5); }

        /* Sidebar */
        #sidebar {
            background: linear-gradient(180deg, #1D4ED8 0%, #1E3A8A 50%, #0F172A 100%);
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1), width 0.3s ease;
        }

        .menu-item.active {
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            border-left: 3px solid #60A5FA;
        }
        .menu-item:hover {
            background: rgba(255,255,255,0.10);
            transform: translateX(4px);
        }

        #main-content { transition: margin-left 0.3s ease; }

        .card-stat { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .card-stat:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 40px rgba(29,78,216,0.15);
        }

        .header-glass {
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        @keyframes pulse-ring {
            0% { transform: scale(0.8); opacity: 1; }
            100% { transform: scale(2); opacity: 0; }
        }
        .notif-pulse::before {
            content: '';
            position: absolute;
            top: 1px; right: 1px;
            width: 8px; height: 8px;
            background: #EF4444;
            border-radius: 50%;
            animation: pulse-ring 2s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
        }

        #overlay {
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        #profileDropdown {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            transform-origin: top right;
        }
        #profileDropdown.hidden {
            opacity: 0;
            transform: scale(0.95);
            pointer-events: none;
        }
        #profileDropdown:not(.hidden) {
            opacity: 1;
            transform: scale(1);
            pointer-events: auto;
        }

        .search-glow:focus {
            box-shadow: 0 0 0 3px rgba(29,78,216,0.15);
        }

        @media (max-width: 1024px) {
            #sidebar {
                position: fixed;
                z-index: 50;
                transform: translateX(-100%);
                box-shadow: 4px 0 24px rgba(0,0,0,0.15);
            }
            #sidebar.open { transform: translateX(0); }
            #main-content { margin-left: 0 !important; }
            #overlay { opacity: 0; visibility: hidden; display: block !important; }
            #overlay.active { opacity: 1; visibility: visible; }
        }

        /* ====================================================
           LOGIN MODAL
        ==================================================== */

        #loginModal {
            position: fixed;
            inset: 0;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        /* Backdrop: blur + gelap */
        #loginModalBackdrop {
            position: absolute;
            inset: 0;
            background: rgba(10, 20, 50, 0.72);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
        }

        /* Glass card modal */
        #loginModalCard {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 420px;
            background: rgba(15, 30, 80, 0.75);
            backdrop-filter: blur(32px);
            -webkit-backdrop-filter: blur(32px);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 28px;
            padding: 2.5rem 2.25rem;
            box-shadow:
                0 40px 80px rgba(0,0,0,0.5),
                0 0 0 1px rgba(255,255,255,0.06) inset,
                0 1px 0 rgba(255,255,255,0.12) inset;
            animation: modalIn 0.55s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }

        @keyframes modalIn {
            from {
                opacity: 0;
                transform: translateY(40px) scale(0.92);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Stagger children dalam modal */
        #loginModalCard .m-stagger > * {
            opacity: 0;
            animation: mFadeUp 0.45s ease forwards;
        }
        #loginModalCard .m-stagger > *:nth-child(1) { animation-delay: 0.1s; }
        #loginModalCard .m-stagger > *:nth-child(2) { animation-delay: 0.2s; }
        #loginModalCard .m-stagger > *:nth-child(3) { animation-delay: 0.28s; }
        #loginModalCard .m-stagger > *:nth-child(4) { animation-delay: 0.36s; }
        #loginModalCard .m-stagger > *:nth-child(5) { animation-delay: 0.44s; }

        @keyframes mFadeUp {
            from { opacity: 0; transform: translateY(14px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Orbs dalam modal */
        .modal-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(50px);
            opacity: 0.18;
            pointer-events: none;
        }
        .modal-orb-1 {
            width: 200px; height: 200px;
            background: #3b82f6;
            top: -60px; right: -60px;
            animation: floatOrb 7s ease-in-out infinite;
        }
        .modal-orb-2 {
            width: 150px; height: 150px;
            background: #818cf8;
            bottom: -40px; left: -40px;
            animation: floatOrb 9s ease-in-out infinite reverse;
        }
        @keyframes floatOrb {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }

        /* Input dalam modal */
        .modal-input {
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.14);
            color: white;
            transition: all 0.3s ease;
        }
        .modal-input::placeholder { color: rgba(255,255,255,0.3); }
        .modal-input:focus {
            outline: none;
            background: rgba(255,255,255,0.12);
            border-color: rgba(96,165,250,0.65);
            box-shadow: 0 0 0 3px rgba(59,130,246,0.2);
        }

        /* Submit dalam modal */
        .modal-btn {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            box-shadow: 0 8px 24px rgba(59,130,246,0.4), 0 1px 0 rgba(255,255,255,0.1) inset;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .modal-btn::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
            transition: left 0.5s ease;
        }
        .modal-btn:hover::before { left: 100%; }
        .modal-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(59,130,246,0.5), 0 1px 0 rgba(255,255,255,0.1) inset;
        }
        .modal-btn:active { transform: translateY(0); }

        /* Logo pulse */
        .modal-logo {
            animation: logoPulse 3s ease-in-out infinite;
        }
        @keyframes logoPulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(96,165,250,0.5); }
            50% { box-shadow: 0 0 0 10px rgba(96,165,250,0); }
        }

        /* Error shake */
        .shake { animation: shake 0.4s ease; }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-6px); }
            40%, 80% { transform: translateX(6px); }
        }

        /* Spinner */
        .modal-spinner {
            width: 16px; height: 16px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>

<body class="font-sans bg-[#F1F5F9] text-[#0F172A] overflow-hidden">

{{-- ===================================================
     LOGIN MODAL (muncul ketika session expired)
     Ditampilkan via Blade @if — cek session token
==================================================== --}}
@if(session()->missing('token') && !request()->is('admin/login'))
<div id="loginModal">
    <!-- Backdrop blur gelap -->
    <div id="loginModalBackdrop"></div>

    <!-- Card modal login -->
    <div id="loginModalCard">

        <!-- Orbs dekoratif -->
        <div class="modal-orb modal-orb-1"></div>
        <div class="modal-orb modal-orb-2"></div>

        <div class="m-stagger">

            <!-- Header -->
            <div class="text-center mb-7">
                <div class="flex justify-center mb-4">
                    <div class="modal-logo w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-400 to-blue-700 flex items-center justify-center shadow-2xl">
                        <i class='bx bxs-graduation text-white text-2xl'></i>
                    </div>
                </div>
                <h2 class="text-xl font-bold text-white tracking-tight">Masuk ke Panel Admin</h2>
                <p class="text-blue-200/60 text-xs mt-1.5 font-medium">SMKN 8 Medan — Sistem Absensi</p>
            </div>

            <!-- Error alert -->
            @if ($errors->any())
            <div class="shake mb-4 flex items-start gap-2.5 bg-red-500/15 border border-red-400/25 rounded-xl px-3.5 py-3">
                <i class='bx bxs-error-circle text-red-400 text-lg flex-shrink-0 mt-0.5'></i>
                <div>
                    @foreach ($errors->all() as $error)
                        <p class="text-red-300 text-xs font-medium">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('login.post') }}" id="modalLoginForm">
                @csrf

                <!-- Login field -->
                <div class="mb-3.5">
                    <label class="block text-blue-100/70 text-[10px] font-bold uppercase tracking-widest mb-1.5">
                        Username / NIP / Email
                    </label>
                    <div class="relative">
                        <i class='bx bxs-user absolute left-3.5 top-1/2 -translate-y-1/2 text-blue-300/50 text-base'></i>
                        <input
                            type="text"
                            name="login"
                            value="{{ old('login') }}"
                            placeholder="Masukkan username atau NIP"
                            autocomplete="username"
                            class="modal-input w-full pl-10 pr-4 py-3 rounded-xl text-sm font-medium"
                            required
                            autofocus
                        >
                    </div>
                </div>

                <!-- Password field -->
                <div class="mb-5">
                    <label class="block text-blue-100/70 text-[10px] font-bold uppercase tracking-widest mb-1.5">
                        Password
                    </label>
                    <div class="relative">
                        <i class='bx bxs-lock-alt absolute left-3.5 top-1/2 -translate-y-1/2 text-blue-300/50 text-base'></i>
                        <input
                            type="password"
                            name="password"
                            id="modalPasswordInput"
                            placeholder="Masukkan password"
                            autocomplete="current-password"
                            class="modal-input w-full pl-10 pr-11 py-3 rounded-xl text-sm font-medium"
                            required
                        >
                        <button
                            type="button"
                            onclick="toggleModalPassword()"
                            class="absolute right-3.5 top-1/2 -translate-y-1/2 text-blue-300/40 hover:text-blue-200/80 transition-colors focus:outline-none"
                        >
                            <i class='bx bx-show text-lg' id="modalEyeIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Submit button -->
                <button
                    type="submit"
                    id="modalSubmitBtn"
                    class="modal-btn w-full py-3.5 rounded-xl text-white font-bold text-sm tracking-wide flex items-center justify-center gap-2"
                >
                    <span id="modalBtnText">Masuk</span>
                    <i class='bx bx-log-in-circle text-lg' id="modalBtnIcon"></i>
                    <div class="modal-spinner hidden" id="modalBtnSpinner"></div>
                </button>

            </form>

            <!-- Footer kecil -->
            <p class="mt-5 text-center text-blue-300/30 text-[10px]">
                Hanya untuk akun administrator terdaftar
            </p>

        </div>
    </div>
</div>
@endif

{{-- ===================================================
     LAYOUT UTAMA
==================================================== --}}
<div class="flex h-screen overflow-hidden relative {{ session()->missing('token') ? 'pointer-events-none select-none' : '' }}">

    <!-- Overlay mobile -->
    <div id="overlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden" onclick="toggleSidebar()"></div>

    <!-- ===================== SIDEBAR ===================== -->
    <aside id="sidebar" class="fixed lg:relative w-[272px] h-screen flex flex-col z-50 overflow-y-auto overflow-x-hidden">

        <!-- Logo -->
        <div class="flex items-center gap-3 px-5 py-6 border-b border-white/10">
            <div class="w-11 h-11 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center flex-shrink-0 shadow-lg">
                <i class='bx bxs-graduation text-white text-2xl'></i>
            </div>
            <div class="leading-tight">
                <p class="text-white font-bold text-sm tracking-wide">SMKN 8 MEDAN</p>
                <p class="text-blue-200 text-xs font-medium">Sistem Absensi</p>
            </div>
        </div>

        <!-- Menu Dashboard -->
        <div class="px-4 mt-5">
            <p class="text-blue-300/60 text-[10px] font-semibold uppercase tracking-widest px-3 mb-2">Main</p>
            <a href="{{ route('admin.dashboard') }}"
               class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-xl cursor-pointer mb-1 transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class='bx bxs-dashboard text-xl text-white/90'></i>
                <span class="text-white/90 text-sm font-medium">Dashboard</span>
            </a>
        </div>

        <!-- Menu Manajemen -->
        <div class="px-4 mt-4 flex-1">
            <p class="text-blue-300/60 text-[10px] font-semibold uppercase tracking-widest px-3 mb-2">Manajemen</p>

            <a href="{{ route('admin.akun-siswa') }}"
               class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-xl cursor-pointer mb-1 transition-all duration-200 {{ request()->routeIs('admin.akun-siswa') ? 'active' : '' }}">
                <i class='bx bxs-user text-xl text-white/70'></i>
                <span class="text-white/80 text-sm font-medium">Data Siswa</span>
            </a>

            <a href="{{ route('admin.libur') }}"
               class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-xl cursor-pointer mb-1 transition-all duration-200 {{ request()->routeIs('admin.libur') ? 'active' : '' }}">
                <i class='bx bxs-building text-xl text-white/70'></i>
                <span class="text-white/80 text-sm font-medium">Libur</span>
            </a>

            <p class="text-blue-300/60 text-[10px] font-semibold uppercase tracking-widest px-3 mb-2 mt-5">Absensi</p>

            <a href="{{ route('admin.absensi') }}"
            class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-xl cursor-pointer mb-1 transition-all duration-200 {{ request()->routeIs('admin.absensi') ? 'active' : '' }}">
                <i class='bx bxs-calendar-check text-xl text-white/70'></i>
                <span class="text-white/80 text-sm font-medium">Absensi</span>
            </a>

            <a href="{{ route('admin.lokasi') }}"
            class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-xl cursor-pointer mb-1 transition-all duration-200 {{ request()->routeIs('admin.lokasi') ? 'active' : '' }}">
                <i class='bx bxs-map text-xl text-white/70'></i>
                <span class="text-white/80 text-sm font-medium">Lokasi</span>
</a>

            <a href="{{ route('admin.izin') }}"
               class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-xl cursor-pointer mb-1 transition-all duration-200 {{ request()->routeIs('admin.izin') ? 'active' : '' }}">
                <i class='bx bxs-envelope text-xl text-white/70'></i>
                <span class="text-white/80 text-sm font-medium">Izin & Sakit</span>
            </a>

            <a href="{{ route('admin.waktu') }}"
               class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-xl cursor-pointer mb-1 transition-all duration-200 {{ request()->routeIs('admin.waktu') ? 'active' : '' }}">
                <i class='bx bxs-time text-xl text-white/70'></i>
                <span class="text-white/80 text-sm font-medium">Waktu</span>
            </a>

            <p class="text-blue-300/60 text-[10px] font-semibold uppercase tracking-widest px-3 mb-2 mt-5">Lainnya</p>

            <a href="{{ route('admin.pengumuman.index') }}"
               class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-xl cursor-pointer mb-1 transition-all duration-200 {{ request()->routeIs('admin.pengumuman.*') ? 'active' : '' }}">
                <i class='bx bxs-megaphone text-xl text-white/70'></i>
                <span class="text-white/80 text-sm font-medium">Pengumuman</span>
            </a>

            <a href="{{ route('admin.event.index') }}"
               class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-xl cursor-pointer mb-1 transition-all duration-200 {{ request()->routeIs('admin.event.*') ? 'active' : '' }}">
                <i class='bx bxs-calendar-event text-xl text-white/70'></i>
                <span class="text-white/80 text-sm font-medium">Event</span>
            </a>

            <a href="{{ route('admin.pengaturan') }}"
               class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-xl cursor-pointer mb-1 transition-all duration-200 {{ request()->routeIs('admin.pengaturan') ? 'active' : '' }}">
                <i class='bx bxs-cog text-xl text-white/70'></i>
                <span class="text-white/80 text-sm font-medium">Pengaturan</span>
            </a>
        </div>

        <!-- Profile Admin di Sidebar -->
        <div class="px-4 py-4 border-t border-white/10 mt-2">
            <div class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-white/10 cursor-pointer transition">
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-300 to-blue-600 flex items-center justify-center flex-shrink-0 shadow">
                    <i class='bx bxs-user text-white text-lg'></i>
                </div>
                <div class="leading-tight overflow-hidden">
                    <p class="text-white text-sm font-semibold truncate">
                        {{ session('user')['name'] ?? 'Administrator' }}
                    </p>
                    <p class="text-blue-300 text-xs">Super Admin</p>
                </div>
                <i class='bx bx-dots-vertical-rounded text-white/50 ml-auto'></i>
            </div>
        </div>

    </aside>
    <!-- ===================== END SIDEBAR ===================== -->

    <!-- ===================== MAIN CONTENT ===================== -->
    <div id="main-content" class="flex-1 flex flex-col overflow-hidden lg:ml-0" style="margin-left: 0;">

        <!-- TOP NAVBAR -->
        <header class="header-glass border-b border-slate-200/80 px-4 sm:px-6 py-3 flex items-center gap-4 shadow-sm flex-shrink-0 z-30">

            <!-- Hamburger -->
            <button onclick="toggleSidebar()"
                class="lg:hidden w-9 h-9 rounded-xl bg-slate-100 hover:bg-blue-50 active:bg-blue-100 flex items-center justify-center transition-all duration-200 text-slate-600 hover:text-blue-600 shadow-sm">
                <i class='bx bx-menu text-xl'></i>
            </button>

            <!-- Breadcrumb (Desktop) -->
            <div class="hidden lg:flex items-center gap-2 text-sm text-slate-500">
                <span class="font-medium text-slate-800">@yield('title', 'Dashboard')</span>
                <i class='bx bx-chevron-right text-slate-400'></i>
                <span class="text-slate-400">SMKN 8 Medan</span>
            </div>

            <!-- Search -->
            <div class="flex-1 max-w-md ml-0 lg:ml-4">
                <div class="relative group">
                    <i class='bx bx-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg group-focus-within:text-blue-500 transition-colors'></i>
                    <input type="text"
                        placeholder="Cari menu, data, atau lainnya..."
                        class="search-glow w-full pl-11 pr-4 py-2.5 bg-slate-50/80 border border-slate-200/80 rounded-xl text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 focus:bg-white transition-all duration-200">
                    <kbd class="hidden sm:inline-flex absolute right-3 top-1/2 -translate-y-1/2 items-center gap-1 px-2 py-0.5 bg-slate-100 border border-slate-200 rounded-md text-[10px] text-slate-400 font-mono">
                        <span class="text-xs">⌘</span>K
                    </kbd>
                </div>
            </div>

            <!-- Right Actions -->
            <div class="flex items-center gap-2 sm:gap-3 ml-auto">

                <!-- Quick Actions Desktop -->
                <div class="hidden md:flex items-center gap-2">
                    <button class="w-9 h-9 rounded-xl bg-slate-50 hover:bg-blue-50 border border-slate-200/60 hover:border-blue-200 flex items-center justify-center transition-all duration-200 text-slate-500 hover:text-blue-600 shadow-sm" onclick="toggleFullscreen()">
                        <i class='bx bx-fullscreen text-lg'></i>
                    </button>
                    <button class="w-9 h-9 rounded-xl bg-slate-50 hover:bg-blue-50 border border-slate-200/60 hover:border-blue-200 flex items-center justify-center transition-all duration-200 text-slate-500 hover:text-blue-600 shadow-sm" onclick="location.reload()">
                        <i class='bx bx-refresh text-lg'></i>
                    </button>
                </div>

                <div class="hidden sm:block w-px h-7 bg-slate-200 mx-1"></div>

                <!-- Notification -->
                <button class="relative w-9 h-9 rounded-xl bg-slate-50 hover:bg-blue-50 border border-slate-200/60 hover:border-blue-200 flex items-center justify-center transition-all duration-200 text-slate-500 hover:text-blue-600 shadow-sm notif-pulse">
                    <i class='bx bxs-bell text-lg'></i>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                </button>

                <!-- Messages -->
                <button class="relative w-9 h-9 rounded-xl bg-slate-50 hover:bg-blue-50 border border-slate-200/60 hover:border-blue-200 flex items-center justify-center transition-all duration-200 text-slate-500 hover:text-blue-600 shadow-sm">
                    <i class='bx bxs-message-dots text-lg'></i>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-blue-500 rounded-full border-2 border-white"></span>
                </button>

                <div class="hidden sm:block w-px h-7 bg-slate-200 mx-1"></div>

                <!-- Profile Dropdown -->
                <div class="relative">
                    <button onclick="toggleDropdown()"
                        class="flex items-center gap-2.5 pl-2 pr-3 py-1.5 rounded-xl hover:bg-slate-100/80 transition-all duration-200 cursor-pointer border border-transparent hover:border-slate-200/60">
                        <div class="relative">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-blue-700 flex items-center justify-center shadow-md ring-2 ring-white">
                                <i class='bx bxs-user text-white text-sm'></i>
                            </div>
                            <span class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 bg-emerald-500 rounded-full border-2 border-white"></span>
                        </div>
                        <div class="hidden sm:block text-left leading-tight">
                            <span class="text-sm font-semibold text-slate-700 block">
                                {{ session('user')['name'] ?? 'Admin' }}
                            </span>
                            <span class="text-[10px] text-slate-400 font-medium">Super Admin</span>
                        </div>
                        <i class='bx bx-chevron-down text-slate-400 text-sm transition-transform duration-200' id="dropdownArrow"></i>
                    </button>

                    <!-- Dropdown -->
                    <div id="profileDropdown"
                        class="absolute right-0 top-14 w-56 bg-white rounded-2xl shadow-2xl border border-slate-100 py-2 hidden z-50 overflow-hidden">

                        <div class="px-4 py-3 border-b border-slate-100 bg-slate-50/50">
                            <p class="text-sm font-bold text-slate-800">
                                {{ session('user')['name'] ?? 'Administrator' }}
                            </p>
                            <p class="text-xs text-slate-400 mt-0.5">
                                {{ session('user')['email'] ?? 'admin@smkn8medan.sch.id' }}
                            </p>
                        </div>

                        <div class="p-1.5">
                            <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-sm text-slate-600 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition-colors duration-150">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                                    <i class='bx bxs-user-circle text-blue-500 text-lg'></i>
                                </div>
                                <span class="font-medium">Profil Saya</span>
                            </a>
                            <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-sm text-slate-600 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition-colors duration-150">
                                <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center">
                                    <i class='bx bxs-key text-amber-500 text-lg'></i>
                                </div>
                                <span class="font-medium">Ganti Password</span>
                            </a>
                            <a href="{{ route('admin.pengaturan') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm text-slate-600 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition-colors duration-150">
                                <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center">
                                    <i class='bx bxs-cog text-slate-500 text-lg'></i>
                                </div>
                                <span class="font-medium">Pengaturan</span>
                            </a>
                        </div>

                        <hr class="my-1 border-slate-100 mx-2">

                        <div class="p-1.5">
                            <!-- Logout pakai POST form -->
                            <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center gap-3 px-3 py-2.5 text-sm text-red-500 hover:bg-red-50 rounded-xl transition-colors duration-150 text-left">
                                    <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center">
                                        <i class='bx bxs-log-out text-red-500 text-lg'></i>
                                    </div>
                                    <span class="font-medium">Keluar</span>
                                </button>
                            </form>
                        </div>

                    </div>
                </div>

            </div>
        </header>

        <!-- PAGE CONTENT -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6">
            @yield('content')
        </main>

    </div>
    <!-- ===================== END MAIN CONTENT ===================== -->

</div>

<!-- AOS & Scripts -->
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
    AOS.init({ duration: 600, once: true, easing: 'ease-out-cubic' });

    // Sidebar toggle
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const isLg = window.innerWidth >= 1024;

        if (isLg) {
            const main = document.getElementById('main-content');
            if (sidebar.style.transform === 'translateX(-100%)') {
                sidebar.style.transform = '';
                main.style.marginLeft = '0';
            } else {
                sidebar.style.transform = 'translateX(-100%)';
                main.style.marginLeft = '0';
            }
        } else {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
        }
    }

    window.addEventListener('DOMContentLoaded', () => {
        if (window.innerWidth >= 1024) {
            document.getElementById('sidebar').style.transform = '';
        }
    });

    // Profile dropdown
    function toggleDropdown() {
        const dropdown = document.getElementById('profileDropdown');
        const arrow = document.getElementById('dropdownArrow');
        dropdown.classList.toggle('hidden');
        arrow.style.transform = dropdown.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
    }

    document.addEventListener('click', (e) => {
        const dd = document.getElementById('profileDropdown');
        const arrow = document.getElementById('dropdownArrow');
        const btn = e.target.closest('[onclick="toggleDropdown()"]');
        if (!btn && !dd.classList.contains('hidden')) {
            dd.classList.add('hidden');
            arrow.style.transform = 'rotate(0deg)';
        }
    });

    // Fullscreen
    function toggleFullscreen() {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen();
        } else {
            document.exitFullscreen();
        }
    }

    // Shortcut Cmd/Ctrl + K
    document.addEventListener('keydown', (e) => {
        if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
            e.preventDefault();
            document.querySelector('.search-glow')?.focus();
        }
    });

    // Modal login: toggle password
    function toggleModalPassword() {
        const input = document.getElementById('modalPasswordInput');
        const icon = document.getElementById('modalEyeIcon');
        if (input && icon) {
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'bx bx-hide text-lg';
            } else {
                input.type = 'password';
                icon.className = 'bx bx-show text-lg';
            }
        }
    }

    // Modal login: loading state
    const modalForm = document.getElementById('modalLoginForm');
    if (modalForm) {
        modalForm.addEventListener('submit', function () {
            const btn = document.getElementById('modalSubmitBtn');
            const text = document.getElementById('modalBtnText');
            const icon = document.getElementById('modalBtnIcon');
            const spinner = document.getElementById('modalBtnSpinner');
            if (btn && text && icon && spinner) {
                btn.disabled = true;
                btn.style.opacity = '0.85';
                text.textContent = 'Memverifikasi...';
                icon.classList.add('hidden');
                spinner.classList.remove('hidden');
            }
        });
    }
</script>

</body>
</html>
