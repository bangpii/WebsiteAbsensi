<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — SMKN 8 Medan</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <style>
        :root {
            --sidebar-width: 272px;
            --primary: #1D4ED8;
            --primary-dark: #1E3A8A;
            --darker: #0F172A;
            --bg: #F1F5F9;
        }

        /* Scrollbar custom */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(148,163,184,0.3); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(148,163,184,0.5); }

        /* Sidebar gradient */
        #sidebar {
            background: linear-gradient(180deg, #1D4ED8 0%, #1E3A8A 50%, #0F172A 100%);
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1), width 0.3s ease;
        }

        /* Active menu item */
        .menu-item.active {
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            border-left: 3px solid #60A5FA;
        }

        /* Hover menu item */
        .menu-item:hover {
            background: rgba(255,255,255,0.10);
            transform: translateX(4px);
        }

        /* Main content push when sidebar open */
        #main-content {
            transition: margin-left 0.3s ease;
        }

        /* Card gradient shimmer */
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        .card-stat {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card-stat:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 40px rgba(29,78,216,0.15);
        }

        /* Sidebar collapsed */
        .sidebar-collapsed #sidebar {
            transform: translateX(-100%);
        }
        .sidebar-collapsed #main-content {
            margin-left: 0 !important;
        }

        /* Header enhancements */
        .header-glass {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        /* Notification badge pulse */
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

        /* Mobile overlay blur */
        #overlay {
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        @media (max-width: 1024px) {
            #sidebar {
                position: fixed;
                z-index: 50;
                transform: translateX(-100%);
                box-shadow: 4px 0 24px rgba(0,0,0,0.15);
            }
            #sidebar.open {
                transform: translateX(0);
            }
            #main-content {
                margin-left: 0 !important;
            }
            #overlay {
                opacity: 0;
                visibility: hidden;
                display: block !important;
            }
            #overlay.active {
                opacity: 1;
                visibility: visible;
            }
        }

        /* Profile dropdown animation */
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

        /* Search focus glow */
        .search-glow:focus {
            box-shadow: 0 0 0 3px rgba(29, 78, 216, 0.15);
        }
    </style>
</head>

<body class="font-sans bg-[#F1F5F9] text-[#0F172A] overflow-hidden">

<div class="flex h-screen overflow-hidden relative">

    <!-- Overlay mobile dengan blur gelap -->
    <div id="overlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden" onclick="toggleSidebar()"></div>

    <!-- ===================== SIDEBAR ===================== -->
    <aside id="sidebar" class="fixed lg:relative w-[272px] h-screen flex flex-col z-50 overflow-y-auto overflow-x-hidden">

        <!-- Logo Area -->
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

        <!-- Menu Utama -->
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

        <!-- Profile Admin -->
        <div class="px-4 py-4 border-t border-white/10 mt-2">
            <div class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-white/10 cursor-pointer transition">
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-300 to-blue-600 flex items-center justify-center flex-shrink-0 shadow">
                    <i class='bx bxs-user text-white text-lg'></i>
                </div>
                <div class="leading-tight overflow-hidden">
                    <p class="text-white text-sm font-semibold truncate">Administrator</p>
                    <p class="text-blue-300 text-xs">Super Admin</p>
                </div>
                <i class='bx bx-dots-vertical-rounded text-white/50 ml-auto'></i>
            </div>
        </div>

    </aside>
    <!-- ===================== END SIDEBAR ===================== -->

    <!-- ===================== MAIN CONTENT ===================== -->
    <div id="main-content" class="flex-1 flex flex-col overflow-hidden lg:ml-0" style="margin-left: 0;">

        <!-- TOP NAVBAR - DIPERCANTIK -->
        <header class="header-glass border-b border-slate-200/80 px-4 sm:px-6 py-3 flex items-center gap-4 shadow-sm flex-shrink-0 z-30">

            <!-- Hamburger - HANYA MUNCUL DI MOBILE -->
            <button onclick="toggleSidebar()"
                class="lg:hidden w-9 h-9 rounded-xl bg-slate-100 hover:bg-blue-50 active:bg-blue-100 flex items-center justify-center transition-all duration-200 text-slate-600 hover:text-blue-600 shadow-sm">
                <i class='bx bx-menu text-xl'></i>
            </button>

            <!-- Breadcrumb / Page Title (Desktop) -->
            <div class="hidden lg:flex items-center gap-2 text-sm text-slate-500">
                <span class="font-medium text-slate-800">@yield('title', 'Dashboard')</span>
                <i class='bx bx-chevron-right text-slate-400'></i>
                <span class="text-slate-400">SMKN 8 Medan</span>
            </div>

            <!-- Search Bar - Lebih Elegan -->
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

            <!-- Right Actions - Lebih Ramai & Modern -->
            <div class="flex items-center gap-2 sm:gap-3 ml-auto">

                <!-- Quick Actions (Desktop) -->
                <div class="hidden md:flex items-center gap-2">
                    <button class="w-9 h-9 rounded-xl bg-slate-50 hover:bg-blue-50 border border-slate-200/60 hover:border-blue-200 flex items-center justify-center transition-all duration-200 text-slate-500 hover:text-blue-600 shadow-sm" title="Fullscreen" onclick="toggleFullscreen()">
                        <i class='bx bx-fullscreen text-lg'></i>
                    </button>
                    <button class="w-9 h-9 rounded-xl bg-slate-50 hover:bg-blue-50 border border-slate-200/60 hover:border-blue-200 flex items-center justify-center transition-all duration-200 text-slate-500 hover:text-blue-600 shadow-sm" title="Refresh" onclick="location.reload()">
                        <i class='bx bx-refresh text-lg'></i>
                    </button>
                </div>

                <!-- Divider -->
                <div class="hidden sm:block w-px h-7 bg-slate-200 mx-1"></div>

                <!-- Notification - Dengan Pulse Animation -->
                <button class="relative w-9 h-9 rounded-xl bg-slate-50 hover:bg-blue-50 border border-slate-200/60 hover:border-blue-200 flex items-center justify-center transition-all duration-200 text-slate-500 hover:text-blue-600 shadow-sm notif-pulse">
                    <i class='bx bxs-bell text-lg'></i>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                </button>

                <!-- Messages -->
                <button class="relative w-9 h-9 rounded-xl bg-slate-50 hover:bg-blue-50 border border-slate-200/60 hover:border-blue-200 flex items-center justify-center transition-all duration-200 text-slate-500 hover:text-blue-600 shadow-sm">
                    <i class='bx bxs-message-dots text-lg'></i>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-blue-500 rounded-full border-2 border-white"></span>
                </button>

                <!-- Divider -->
                <div class="hidden sm:block w-px h-7 bg-slate-200 mx-1"></div>

                <!-- Profile Dropdown - Lebih Premium -->
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
                            <span class="text-sm font-semibold text-slate-700 block">Admin</span>
                            <span class="text-[10px] text-slate-400 font-medium">Super Admin</span>
                        </div>
                        <i class='bx bx-chevron-down text-slate-400 text-sm transition-transform duration-200' id="dropdownArrow"></i>
                    </button>

                    <!-- Dropdown - Modern & Smooth -->
                    <div id="profileDropdown"
                        class="absolute right-0 top-14 w-56 bg-white rounded-2xl shadow-2xl border border-slate-100 py-2 hidden z-50 overflow-hidden">
                        <!-- Header Dropdown -->
                        <div class="px-4 py-3 border-b border-slate-100 bg-slate-50/50">
                            <p class="text-sm font-bold text-slate-800">Administrator</p>
                            <p class="text-xs text-slate-400 mt-0.5">admin@smkn8medan.sch.id</p>
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
                            <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-sm text-slate-600 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition-colors duration-150">
                                <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center">
                                    <i class='bx bxs-cog text-slate-500 text-lg'></i>
                                </div>
                                <span class="font-medium">Pengaturan</span>
                            </a>
                        </div>
                        
                        <hr class="my-1 border-slate-100 mx-2">
                        
                        <div class="p-1.5">
                            <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-sm text-red-500 hover:bg-red-50 rounded-xl transition-colors duration-150">
                                <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center">
                                    <i class='bx bxs-log-out text-red-500 text-lg'></i>
                                </div>
                                <span class="font-medium">Keluar</span>
                            </a>
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

    // Initialize sidebar visible on desktop
    window.addEventListener('DOMContentLoaded', () => {
        if (window.innerWidth >= 1024) {
            const sidebar = document.getElementById('sidebar');
            sidebar.style.transform = '';
        }
    });

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', (e) => {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const hamburger = document.querySelector('[onclick="toggleSidebar()"]');
        
        if (window.innerWidth < 1024) {
            // Jika sidebar terbuka dan klik di luar sidebar dan bukan di tombol hamburger
            if (sidebar.classList.contains('open') && 
                !sidebar.contains(e.target) && 
                !hamburger.contains(e.target)) {
                sidebar.classList.remove('open');
                overlay.classList.remove('active');
            }
        }
    });

    // Profile dropdown
    function toggleDropdown() {
        const dropdown = document.getElementById('profileDropdown');
        const arrow = document.getElementById('dropdownArrow');
        dropdown.classList.toggle('hidden');
        
        if (!dropdown.classList.contains('hidden')) {
            arrow.style.transform = 'rotate(180deg)';
        } else {
            arrow.style.transform = 'rotate(0deg)';
        }
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

    // Fullscreen toggle
    function toggleFullscreen() {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen();
        } else {
            document.exitFullscreen();
        }
    }

    // Keyboard shortcut untuk search (Cmd/Ctrl + K)
    document.addEventListener('keydown', (e) => {
        if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
            e.preventDefault();
            document.querySelector('.search-glow').focus();
        }
    });
</script>

</body>
</html>