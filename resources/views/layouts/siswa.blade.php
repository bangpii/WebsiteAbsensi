<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Portal Siswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="w-64 bg-slate-900 text-white flex flex-col shrink-0">
            <div class="p-6 text-xl font-bold border-b border-slate-800 flex items-center">
                <i class="fas fa-user-graduate mr-3 text-indigo-400"></i> Siswa Panel
            </div>
            <nav class="flex-1 mt-4 overflow-y-auto">
                <a href="{{ route('siswa.dashboard') }}" class="flex items-center px-6 py-4 transition {{ request()->routeIs('siswa.dashboard') ? 'bg-indigo-600 text-white shadow-lg' : 'text-gray-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fas fa-th-large mr-3 w-5"></i> Dashboard
                </a>
                <a href="{{ route('siswa.absensi') }}" class="flex items-center px-6 py-4 transition {{ request()->routeIs('siswa.absensi') ? 'bg-indigo-600 text-white shadow-lg' : 'text-gray-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fas fa-user-check mr-3 w-5"></i> Absensi
                </a>
                <a href="{{ route('siswa.izin') }}" class="flex items-center px-6 py-4 transition {{ request()->routeIs('siswa.izin') ? 'bg-indigo-600 text-white shadow-lg' : 'text-gray-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fas fa-file-alt mr-3 w-5"></i> Pengajuan Izin
                </a>
                <a href="{{ route('siswa.jadwal') }}" class="flex items-center px-6 py-4 transition {{ request()->routeIs('siswa.jadwal') ? 'bg-indigo-600 text-white shadow-lg' : 'text-gray-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fas fa-calendar-alt mr-3 w-5"></i> Jadwal Pelajaran
                </a>
                <a href="{{ route('siswa.pengumuman') }}" class="flex items-center px-6 py-4 transition {{ request()->routeIs('siswa.pengumuman') ? 'bg-indigo-600 text-white shadow-lg' : 'text-gray-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fas fa-bullhorn mr-3 w-5"></i> Pengumuman
                </a>
                <a href="{{ route('siswa.event') }}" class="flex items-center px-6 py-4 transition {{ request()->routeIs('siswa.event') ? 'bg-indigo-600 text-white shadow-lg' : 'text-gray-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fas fa-star mr-3 w-5"></i> Event Sekolah
                </a>
                <a href="{{ route('siswa.mail') }}" class="flex items-center px-6 py-4 transition {{ request()->routeIs('siswa.mail') ? 'bg-indigo-600 text-white shadow-lg' : 'text-gray-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fas fa-envelope mr-3 w-5"></i> Mail / Pesan
                </a>
            </nav>
            <div class="p-4 border-t border-slate-800 text-xs text-gray-500 text-center">
                &copy; {{ date('Y') }} Sistem Absensi
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white shadow-sm h-16 flex items-center justify-between px-8 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">@yield('title')</h2>
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-900 font-bold">John Doe</p>
                        <p class="text-xs text-gray-500">XII IPA 1</p>
                    </div>
                    <div class="h-10 w-10 rounded-full bg-indigo-500 flex items-center justify-center text-white">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
            </header>
            <main class="flex-1 overflow-y-auto p-8 bg-gray-50">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>