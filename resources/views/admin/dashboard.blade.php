@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<!-- ===================== CAROUSEL EVENT BANNER ===================== -->
<div class="mb-6" data-aos="fade-down">
    <div class="relative w-full h-48 sm:h-56 md:h-64 rounded-2xl overflow-hidden shadow-lg group">
        <!-- Slides Container -->
        <div id="carouselSlides" class="flex transition-transform duration-700 ease-in-out h-full">
            <!-- Slide 1 -->
            <div class="min-w-full h-full relative">
                <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=1200&h=400&fit=crop" 
                     alt="Event Sekolah" 
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                <div class="absolute bottom-4 left-4 sm:bottom-6 sm:left-6 text-white">
                    <span class="inline-block px-2.5 py-1 bg-blue-600 text-xs font-bold rounded-full mb-2">Event Aktif</span>
                    <h3 class="text-lg sm:text-xl font-bold">Ujian Akhir Semester Genap 2026</h3>
                    <p class="text-sm text-white/80 mt-1">20 Mei - 5 Juni 2026 • SMKN 8 Medan</p>
                </div>
            </div>
            <!-- Slide 2 -->
            <div class="min-w-full h-full relative">
                <img src="https://images.unsplash.com/photo-1541339907198-e08756dedf3f?w=1200&h=400&fit=crop" 
                     alt="Pengumuman" 
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                <div class="absolute bottom-4 left-4 sm:bottom-6 sm:left-6 text-white">
                    <span class="inline-block px-2.5 py-1 bg-emerald-600 text-xs font-bold rounded-full mb-2">Pengumuman</span>
                    <h3 class="text-lg sm:text-xl font-bold">Pendaftaran Prakerin Gelombang 2</h3>
                    <p class="text-sm text-white/80 mt-1">15 Mei - 30 Mei 2026 • Bagi siswa kelas XI</p>
                </div>
            </div>
            <!-- Slide 3 -->
            <div class="min-w-full h-full relative">
                <img src="https://images.unsplash.com/photo-1427504740701-25d3e5197a9a?w=1200&h=400&fit=crop" 
                     alt="Kegiatan" 
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                <div class="absolute bottom-4 left-4 sm:bottom-6 sm:left-6 text-white">
                    <span class="inline-block px-2.5 py-1 bg-amber-600 text-xs font-bold rounded-full mb-2">Kegiatan</span>
                    <h3 class="text-lg sm:text-xl font-bold">Workshop Teknologi AI untuk Guru</h3>
                    <p class="text-sm text-white/80 mt-1">18 Mei 2026 • Aula Utama</p>
                </div>
            </div>
        </div>
        
        <!-- Navigation Dots -->
        <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-2 z-10">
            <button onclick="goToSlide(0)" class="carousel-dot w-2 h-2 rounded-full bg-white/50 hover:bg-white transition-all duration-300 active-dot"></button>
            <button onclick="goToSlide(1)" class="carousel-dot w-2 h-2 rounded-full bg-white/50 hover:bg-white transition-all duration-300"></button>
            <button onclick="goToSlide(2)" class="carousel-dot w-2 h-2 rounded-full bg-white/50 hover:bg-white transition-all duration-300"></button>
        </div>
        
        <!-- Prev/Next Buttons -->
        <button onclick="prevSlide()" class="absolute left-3 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-white/20 hover:bg-white/40 backdrop-blur flex items-center justify-center text-white transition-all opacity-0 group-hover:opacity-100">
            <i class='bx bx-chevron-left text-xl'></i>
        </button>
        <button onclick="nextSlide()" class="absolute right-3 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-white/20 hover:bg-white/40 backdrop-blur flex items-center justify-center text-white transition-all opacity-0 group-hover:opacity-100">
            <i class='bx bx-chevron-right text-xl'></i>
        </button>
    </div>
</div>

<!-- ===================== STAT CARDS (4 CARD) ===================== -->
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">

    <!-- Card Total Siswa -->
    <div class="card-stat bg-white rounded-2xl p-5 shadow-sm border border-slate-100" data-aos="fade-up" data-aos-delay="0">
        <div class="flex items-start justify-between mb-4">
            <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center">
                <i class='bx bxs-group text-blue-600 text-xl'></i>
            </div>
            <span class="flex items-center gap-1 text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">
                <i class='bx bx-trending-up text-sm'></i> +2.4%
            </span>
        </div>
        <p class="text-3xl font-bold text-slate-800">1,248</p>
        <p class="text-slate-500 text-sm font-medium mt-1">Total Siswa</p>
        <div class="mt-3 h-1.5 bg-slate-100 rounded-full overflow-hidden">
            <div class="h-full bg-blue-500 rounded-full" style="width: 82%"></div>
        </div>
    </div>

    <!-- Card Event Aktif -->
    <div class="card-stat bg-white rounded-2xl p-5 shadow-sm border border-slate-100" data-aos="fade-up" data-aos-delay="80">
        <div class="flex items-start justify-between mb-4">
            <div class="w-11 h-11 rounded-xl bg-purple-50 flex items-center justify-center">
                <i class='bx bxs-calendar-event text-purple-600 text-xl'></i>
            </div>
            <span class="flex items-center gap-1 text-xs font-semibold text-purple-600 bg-purple-50 px-2 py-1 rounded-full">
                <i class='bx bx-calendar-check text-sm'></i> 3 Aktif
            </span>
        </div>
        <p class="text-3xl font-bold text-slate-800">12</p>
        <p class="text-slate-500 text-sm font-medium mt-1">Event Bulan Ini</p>
        <div class="mt-3 h-1.5 bg-slate-100 rounded-full overflow-hidden">
            <div class="h-full bg-purple-500 rounded-full" style="width: 65%"></div>
        </div>
    </div>

    <!-- Card Pengumuman -->
    <div class="card-stat bg-white rounded-2xl p-5 shadow-sm border border-slate-100" data-aos="fade-up" data-aos-delay="160">
        <div class="flex items-start justify-between mb-4">
            <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center">
                <i class='bx bxs-megaphone text-amber-500 text-xl'></i>
            </div>
            <span class="flex items-center gap-1 text-xs font-semibold text-amber-600 bg-amber-50 px-2 py-1 rounded-full">
                <i class='bx bxs-bell text-sm'></i> 5 Baru
            </span>
        </div>
        <p class="text-3xl font-bold text-slate-800">8</p>
        <p class="text-slate-500 text-sm font-medium mt-1">Pengumuman</p>
        <div class="mt-3 h-1.5 bg-slate-100 rounded-full overflow-hidden">
            <div class="h-full bg-amber-400 rounded-full" style="width: 45%"></div>
        </div>
    </div>

    <!-- Card Siswa Online -->
    <div class="card-stat bg-white rounded-2xl p-5 shadow-sm border border-slate-100" data-aos="fade-up" data-aos-delay="240">
        <div class="flex items-start justify-between mb-4">
            <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center relative">
                <i class='bx bxs-wifi text-emerald-600 text-xl'></i>
                <span class="absolute -top-0.5 -right-0.5 w-2.5 h-2.5 bg-emerald-500 rounded-full border-2 border-white animate-pulse"></span>
            </div>
            <span class="flex items-center gap-1 text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">
                <i class='bx bx-trending-up text-sm'></i> Live
            </span>
        </div>
        <p class="text-3xl font-bold text-slate-800">847</p>
        <p class="text-slate-500 text-sm font-medium mt-1">Siswa Online</p>
        <div class="mt-3 h-1.5 bg-slate-100 rounded-full overflow-hidden">
            <div class="h-full bg-emerald-500 rounded-full" style="width: 68%"></div>
        </div>
    </div>

</div>

<!-- ===================== MAIN ROW: TABLE + DONUT CHART ===================== -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-4 mb-6">

    <!-- TABLE: SISWA ONLINE AKTIF (Ganti Grafik Batang) -->
    <div class="xl:col-span-2 bg-white rounded-2xl p-5 shadow-sm border border-slate-100" data-aos="fade-right">
        <div class="flex items-center justify-between mb-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
                    <i class='bx bxs-wifi text-emerald-600 text-lg'></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-800">Siswa Online Aktif</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Real-time • {{ now()->format('H:i') }} WIB</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-700 text-xs font-semibold rounded-full">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                    847 Online
                </span>
                <a href="#" class="text-xs text-blue-600 font-semibold hover:underline ml-2">Lihat Semua →</a>
            </div>
        </div>

        <div class="overflow-x-auto max-h-72 overflow-y-auto pr-1">
            <table class="w-full text-sm">
                <thead class="sticky top-0 bg-white z-10">
                    <tr class="border-b border-slate-100">
                        <th class="text-left text-xs font-semibold text-slate-400 pb-3 pr-4 w-10">No</th>
                        <th class="text-left text-xs font-semibold text-slate-400 pb-3 pr-4">Nama Siswa</th>
                        <th class="text-left text-xs font-semibold text-slate-400 pb-3 pr-4">Kelas</th>
                        <th class="text-left text-xs font-semibold text-slate-400 pb-3 pr-4">Status</th>
                        <th class="text-left text-xs font-semibold text-slate-400 pb-3">Waktu Login</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @php
                        $siswaOnline = [
                            ['no' => 1, 'nama' => 'Andi Pratama', 'kelas' => 'XII RPL 1', 'status' => 'Aktif', 'waktu' => '07:15', 'avatar' => 'A'],
                            ['no' => 2, 'nama' => 'Siti Rahma', 'kelas' => 'XI TKJ 2', 'status' => 'Aktif', 'waktu' => '07:22', 'avatar' => 'S'],
                            ['no' => 3, 'nama' => 'Budi Santoso', 'kelas' => 'X MM 1', 'status' => 'Aktif', 'waktu' => '07:30', 'avatar' => 'B'],
                            ['no' => 4, 'nama' => 'Dewi Lestari', 'kelas' => 'XII AKL 1', 'status' => 'Aktif', 'waktu' => '07:18', 'avatar' => 'D'],
                            ['no' => 5, 'nama' => 'Fajar Nugroho', 'kelas' => 'XI RPL 2', 'status' => 'Aktif', 'waktu' => '07:45', 'avatar' => 'F'],
                            ['no' => 6, 'nama' => 'Rina Wulandari', 'kelas' => 'XII TKJ 1', 'status' => 'Aktif', 'waktu' => '07:12', 'avatar' => 'R'],
                            ['no' => 7, 'nama' => 'Ahmad Fauzi', 'kelas' => 'XI MM 2', 'status' => 'Aktif', 'waktu' => '07:38', 'avatar' => 'A'],
                            ['no' => 8, 'nama' => 'Maya Sari', 'kelas' => 'X RPL 1', 'status' => 'Aktif', 'waktu' => '07:50', 'avatar' => 'M'],
                            ['no' => 9, 'nama' => 'Dodi Kurniawan', 'kelas' => 'XII TKJ 2', 'status' => 'Idle', 'waktu' => '07:05', 'avatar' => 'D'],
                            ['no' => 10, 'nama' => 'Lina Marlina', 'kelas' => 'XI AKL 1', 'status' => 'Aktif', 'waktu' => '07:28', 'avatar' => 'L'],
                        ];
                    @endphp

                    @foreach($siswaOnline as $item)
                    <tr class="hover:bg-slate-50/80 transition group">
                        <td class="py-3 pr-4 text-slate-400 font-medium">{{ $item['no'] }}</td>
                        <td class="py-3 pr-4">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center flex-shrink-0 shadow-sm">
                                    <span class="text-white text-xs font-bold">{{ $item['avatar'] }}</span>
                                </div>
                                <span class="font-medium text-slate-700">{{ $item['nama'] }}</span>
                            </div>
                        </td>
                        <td class="py-3 pr-4">
                            <span class="bg-blue-50 text-blue-700 text-xs font-semibold px-2.5 py-1 rounded-full">{{ $item['kelas'] }}</span>
                        </td>
                        <td class="py-3 pr-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $item['status'] === 'Aktif' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $item['status'] === 'Aktif' ? 'bg-emerald-500 animate-pulse' : 'bg-amber-500' }}"></span>
                                {{ $item['status'] }}
                            </span>
                        </td>
                        <td class="py-3 text-slate-500 text-xs font-medium">
                            <span class="flex items-center gap-1">
                                <i class='bx bx-time text-slate-400'></i>
                                {{ $item['waktu'] }} WIB
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination mini -->
        <div class="flex items-center justify-between mt-4 pt-3 border-t border-slate-100">
            <p class="text-xs text-slate-400">Menampilkan 10 dari 847 siswa</p>
            <div class="flex gap-1">
                <button class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-blue-50 text-slate-500 hover:text-blue-600 flex items-center justify-center text-xs transition">
                    <i class='bx bx-chevron-left'></i>
                </button>
                <button class="w-7 h-7 rounded-lg bg-blue-600 text-white flex items-center justify-center text-xs font-semibold">1</button>
                <button class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-blue-50 text-slate-500 hover:text-blue-600 flex items-center justify-center text-xs transition">2</button>
                <button class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-blue-50 text-slate-500 hover:text-blue-600 flex items-center justify-center text-xs transition">3</button>
                <button class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-blue-50 text-slate-500 hover:text-blue-600 flex items-center justify-center text-xs transition">
                    <i class='bx bx-chevron-right'></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Donut Chart - Kehadiran -->
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100" data-aos="fade-left">
        <div class="mb-4">
            <h2 class="text-base font-bold text-slate-800">Kehadiran</h2>
            <p class="text-xs text-slate-400 mt-0.5">Persentase hari ini</p>
        </div>
        <div class="relative h-44 flex items-center justify-center">
            <canvas id="donutChart"></canvas>
            <div class="absolute text-center pointer-events-none">
                <p class="text-2xl font-bold text-slate-800">89%</p>
                <p class="text-xs text-slate-400">Hadir</p>
            </div>
        </div>
        <!-- Legend -->
        <div class="mt-4 space-y-2.5">
            <div class="flex items-center justify-between text-sm p-2 rounded-lg hover:bg-slate-50 transition cursor-pointer">
                <span class="flex items-center gap-2 text-slate-600"><span class="w-3 h-3 rounded-full bg-blue-500 inline-block shadow-sm shadow-blue-200"></span>Hadir</span>
                <span class="font-bold text-slate-800">89.2%</span>
            </div>
            <div class="flex items-center justify-between text-sm p-2 rounded-lg hover:bg-slate-50 transition cursor-pointer">
                <span class="flex items-center gap-2 text-slate-600"><span class="w-3 h-3 rounded-full bg-amber-400 inline-block shadow-sm shadow-amber-200"></span>Terlambat</span>
                <span class="font-bold text-slate-800">5.8%</span>
            </div>
            <div class="flex items-center justify-between text-sm p-2 rounded-lg hover:bg-slate-50 transition cursor-pointer">
                <span class="flex items-center gap-2 text-slate-600"><span class="w-3 h-3 rounded-full bg-red-400 inline-block shadow-sm shadow-red-200"></span>Alpha</span>
                <span class="font-bold text-slate-800">5.0%</span>
            </div>
        </div>
    </div>

</div>

<!-- ===================== BOTTOM ROW: INFO CARDS ELEGAN ===================== -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">

    <!-- Info Card 1: Quick Stats -->
    <div class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-2xl p-5 shadow-lg text-white" data-aos="fade-up" data-aos-delay="0">
        <div class="flex items-start justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                <i class='bx bxs-graduation text-white text-xl'></i>
            </div>
            <span class="text-xs bg-white/20 px-2 py-1 rounded-full font-medium">Hari Ini</span>
        </div>
        <p class="text-2xl font-bold">12 Kelas</p>
        <p class="text-blue-200 text-sm mt-1">Sedang berlangsung</p>
        <div class="mt-3 flex items-center gap-2 text-xs text-blue-200">
            <i class='bx bx-time'></i>
            <span>07:00 - 15:00 WIB</span>
        </div>
    </div>

    <!-- Info Card 2: Guru Aktif -->
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100" data-aos="fade-up" data-aos-delay="80">
        <div class="flex items-start justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center">
                <i class='bx bxs-chalkboard text-indigo-600 text-xl'></i>
            </div>
            <span class="text-xs text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full font-semibold">Aktif</span>
        </div>
        <p class="text-2xl font-bold text-slate-800">48 Guru</p>
        <p class="text-slate-500 text-sm mt-1">Sedang mengajar</p>
        <div class="mt-3 flex -space-x-2">
            <div class="w-7 h-7 rounded-full bg-blue-400 border-2 border-white flex items-center justify-center text-white text-xs font-bold">A</div>
            <div class="w-7 h-7 rounded-full bg-emerald-400 border-2 border-white flex items-center justify-center text-white text-xs font-bold">B</div>
            <div class="w-7 h-7 rounded-full bg-amber-400 border-2 border-white flex items-center justify-center text-white text-xs font-bold">C</div>
            <div class="w-7 h-7 rounded-full bg-slate-200 border-2 border-white flex items-center justify-center text-slate-500 text-xs font-bold">+45</div>
        </div>
    </div>

    <!-- Info Card 3: Izin Pending -->
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100" data-aos="fade-up" data-aos-delay="160">
        <div class="flex items-start justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
                <i class='bx bxs-envelope text-amber-500 text-xl'></i>
            </div>
            <span class="text-xs text-amber-600 bg-amber-50 px-2 py-1 rounded-full font-semibold">Pending</span>
        </div>
        <p class="text-2xl font-bold text-slate-800">7 Izin</p>
        <p class="text-slate-500 text-sm mt-1">Menunggu persetujuan</p>
        <div class="mt-3 flex items-center gap-2">
            <div class="flex-1 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full bg-amber-400 rounded-full" style="width: 70%"></div>
            </div>
            <span class="text-xs text-slate-400">70%</span>
        </div>
    </div>

    <!-- Info Card 4: System Status -->
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100" data-aos="fade-up" data-aos-delay="240">
        <div class="flex items-start justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
                <i class='bx bxs-server text-emerald-600 text-xl'></i>
            </div>
            <span class="flex items-center gap-1 text-xs text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full font-semibold">
                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                Normal
            </span>
        </div>
        <p class="text-2xl font-bold text-slate-800">100%</p>
        <p class="text-slate-500 text-sm mt-1">Server uptime</p>
        <div class="mt-3 flex items-center gap-2 text-xs text-slate-400">
            <i class='bx bx-check-circle text-emerald-500'></i>
            <span>Semua sistem berjalan lancar</span>
        </div>
    </div>

</div>

<!-- ===================== TABEL SISWA TERLAMBAT (Pindah ke Bawah) ===================== -->
<div class="grid grid-cols-1 xl:grid-cols-5 gap-4 mb-6">

    <!-- Tabel Siswa Terlambat -->
    <div class="xl:col-span-3 bg-white rounded-2xl p-5 shadow-sm border border-slate-100" data-aos="fade-up">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
                    <i class='bx bxs-time text-amber-500 text-lg'></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-800">Siswa Terlambat</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Data terlambat hari ini</p>
                </div>
            </div>
            <a href="#" class="text-xs text-blue-600 font-semibold hover:underline">Lihat Semua →</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="text-left text-xs font-semibold text-slate-400 pb-3 pr-4">No</th>
                        <th class="text-left text-xs font-semibold text-slate-400 pb-3 pr-4">Nama Siswa</th>
                        <th class="text-left text-xs font-semibold text-slate-400 pb-3 pr-4">Kelas</th>
                        <th class="text-left text-xs font-semibold text-slate-400 pb-3">Jam Terlambat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @php
                        $terlambat = [
                            ['no' => 1, 'nama' => 'Andi Pratama', 'kelas' => 'XII RPL 1', 'jam' => '07:32'],
                            ['no' => 2, 'nama' => 'Siti Rahma', 'kelas' => 'XI TKJ 2', 'jam' => '07:45'],
                            ['no' => 3, 'nama' => 'Budi Santoso', 'kelas' => 'X MM 1', 'jam' => '08:02'],
                            ['no' => 4, 'nama' => 'Dewi Lestari', 'kelas' => 'XII AKL 1', 'jam' => '08:10'],
                            ['no' => 5, 'nama' => 'Fajar Nugroho', 'kelas' => 'XI RPL 2', 'jam' => '08:15'],
                        ];
                    @endphp

                    @foreach($terlambat as $item)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3 pr-4 text-slate-400 font-medium">{{ $item['no'] }}</td>
                        <td class="py-3 pr-4">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center flex-shrink-0">
                                    <span class="text-white text-xs font-bold">{{ strtoupper(substr($item['nama'], 0, 1)) }}</span>
                                </div>
                                <span class="font-medium text-slate-700">{{ $item['nama'] }}</span>
                            </div>
                        </td>
                        <td class="py-3 pr-4">
                            <span class="bg-blue-50 text-blue-700 text-xs font-semibold px-2.5 py-1 rounded-full">{{ $item['kelas'] }}</span>
                        </td>
                        <td class="py-3">
                            <span class="flex items-center gap-1 text-amber-600 font-semibold text-xs">
                                <i class='bx bxs-time text-sm'></i> {{ $item['jam'] }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pengumuman Terbaru -->
    <div class="xl:col-span-2 bg-white rounded-2xl p-5 shadow-sm border border-slate-100" data-aos="fade-up" data-aos-delay="100">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                    <i class='bx bxs-megaphone text-blue-600 text-lg'></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-800">Pengumuman</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Terbaru</p>
                </div>
            </div>
            <a href="#" class="text-xs text-blue-600 font-semibold hover:underline">Semua →</a>
        </div>

        <div class="space-y-3">
            @php
                $pengumuman = [
                    ['judul' => 'Ujian Akhir Semester Genap', 'isi' => 'Pelaksanaan UAS dimulai tanggal 20 Mei 2026.', 'tanggal' => '12 Mei 2026', 'warna' => 'blue'],
                    ['judul' => 'Libur Nasional', 'isi' => 'Sekolah libur pada tanggal 15 Mei 2026.', 'tanggal' => '10 Mei 2026', 'warna' => 'amber'],
                    ['judul' => 'Rapat Wali Kelas', 'isi' => 'Rapat wali kelas akan dilaksanakan pukul 13.00.', 'tanggal' => '9 Mei 2026', 'warna' => 'emerald'],
                    ['judul' => 'Pengumpulan Tugas Akhir', 'isi' => 'Batas pengumpulan tugas akhir semester ini.', 'tanggal' => '7 Mei 2026', 'warna' => 'red'],
                ];
            @endphp

            @foreach($pengumuman as $p)
            <div class="flex gap-3 p-3 rounded-xl hover:bg-slate-50 transition cursor-pointer group border border-transparent hover:border-slate-100">
                <div class="w-2 h-2 rounded-full bg-{{ $p['warna'] }}-500 mt-1.5 flex-shrink-0 shadow-sm shadow-{{ $p['warna'] }}-200"></div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-slate-800 group-hover:text-blue-600 transition truncate">{{ $p['judul'] }}</p>
                    <p class="text-xs text-slate-500 mt-0.5 line-clamp-1">{{ $p['isi'] }}</p>
                    <p class="text-xs text-slate-400 mt-1 flex items-center gap-1">
                        <i class='bx bx-calendar text-[10px]'></i>
                        {{ $p['tanggal'] }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

</div>

<!-- ===================== WELCOME SECTION (Pindah ke Bawah sebagai Footer Info) ===================== -->
<div class="bg-gradient-to-r from-slate-800 to-slate-900 rounded-2xl p-6 text-white shadow-lg" data-aos="fade-up">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-white/10 backdrop-blur flex items-center justify-center">
                <i class='bx bxs-graduation text-2xl text-blue-400'></i>
            </div>
            <div>
                <h3 class="text-lg font-bold">Selamat Datang di Dashboard Admin</h3>
                <p class="text-slate-400 text-sm mt-0.5">SMKN 8 Medan • Sistem Absensi Digital</p>
            </div>
        </div>
        <div class="flex items-center gap-4 text-sm text-slate-400">
            <div class="flex items-center gap-2">
                <i class='bx bx-calendar text-blue-400'></i>
                <span id="tanggalFooter">—</span>
            </div>
            <div class="w-px h-4 bg-slate-700"></div>
            <div class="flex items-center gap-2">
                <i class='bx bx-time text-blue-400'></i>
                <span id="jamFooter">—</span>
            </div>
        </div>
    </div>
</div>

<!-- Charts Script & Carousel -->
<script>
document.addEventListener('DOMContentLoaded', () => {

    // Tanggal & Jam Real-time
    const opts = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    document.getElementById('tanggalFooter').textContent = new Date().toLocaleDateString('id-ID', opts);
    
    function updateJam() {
        document.getElementById('jamFooter').textContent = new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit', second: '2-digit'});
    }
    updateJam();
    setInterval(updateJam, 1000);

    // ===================== CAROUSEL AUTO SCROLL =====================
    let currentSlide = 0;
    const slides = document.getElementById('carouselSlides');
    const dots = document.querySelectorAll('.carousel-dot');
    const totalSlides = 3;
    let autoPlayInterval;

    function updateSlide() {
        slides.style.transform = `translateX(-${currentSlide * 100}%)`;
        dots.forEach((dot, i) => {
            if (i === currentSlide) {
                dot.classList.add('bg-white', 'w-5');
                dot.classList.remove('bg-white/50');
            } else {
                dot.classList.remove('bg-white', 'w-5');
                dot.classList.add('bg-white/50');
            }
        });
    }

    window.goToSlide = function(index) {
        currentSlide = index;
        updateSlide();
        resetAutoPlay();
    };

    window.nextSlide = function() {
        currentSlide = (currentSlide + 1) % totalSlides;
        updateSlide();
        resetAutoPlay();
    };

    window.prevSlide = function() {
        currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
        updateSlide();
        resetAutoPlay();
    };

    function resetAutoPlay() {
        clearInterval(autoPlayInterval);
        autoPlayInterval = setInterval(() => {
            currentSlide = (currentSlide + 1) % totalSlides;
            updateSlide();
        }, 5000);
    }

    // Init carousel
    updateSlide();
    resetAutoPlay();

    // ===================== CHART =====================
    Chart.defaults.font.family = 'Poppins, sans-serif';
    Chart.defaults.color = '#94a3b8';

    // Donut Chart
    const donutCtx = document.getElementById('donutChart').getContext('2d');
    new Chart(donutCtx, {
        type: 'doughnut',
        data: {
            labels: ['Hadir', 'Terlambat', 'Alpha'],
            datasets: [{
                data: [89.2, 5.8, 5.0],
                backgroundColor: ['#1D4ED8', '#F59E0B', '#F87171'],
                borderWidth: 0,
                hoverOffset: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '72%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0F172A',
                    padding: 12,
                    cornerRadius: 12,
                    callbacks: {
                        label: (ctx) => ` ${ctx.label}: ${ctx.parsed}%`
                    }
                }
            }
        }
    });

});
</script>

@endsection