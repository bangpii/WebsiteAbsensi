@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<style>
    .carousel-dot { transition: all 0.3s ease; }
    .carousel-dot.dot-active {
        background: white;
        width: 20px;
        border-radius: 4px;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 9px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .pill-hadir    { background: #DCFCE7; color: #166534; }
    .pill-terlambat { background: #FEF9C3; color: #854D0E; }
    .pill-izin     { background: #DBEAFE; color: #1E40AF; }
    .pill-alpa     { background: #FEE2E2; color: #991B1B; }

    .metode-badge {
        display: inline-flex; align-items: center; gap: 3px;
        padding: 2px 7px; border-radius: 20px; font-size: 10px; font-weight: 600;
    }
    .metode-camera  { background: #E0F2FE; color: #0369A1; }
    .metode-barcode { background: #EDE9FE; color: #5B21B6; }
    .metode-dash    { color: #CBD5E1; font-size: 12px; }
</style>

{{-- ===================== CAROUSEL EVENT BANNER ===================== --}}
<div class="mb-5" data-aos="fade-down">
    <div class="relative w-full h-44 sm:h-52 md:h-60 rounded-2xl overflow-hidden shadow-lg group">

        {{-- Slides Container --}}
        <div id="carouselSlides" class="flex transition-transform duration-700 ease-in-out h-full">

            @forelse($events as $idx => $event)
            <div class="min-w-full h-full relative flex-shrink-0">
                @if($event['gambar_url'])
                <img src="{{ $event['gambar_url'] }}"
                     alt="{{ $event['judul'] }}"
                     class="w-full h-full object-cover"
                     onerror="this.src='https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=1200&h=400&fit=crop'">
                @else
                <div class="w-full h-full" style="background: {{ $event['warna'] ?? '#1D4ED8' }}"></div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/20 to-transparent"></div>
                <div class="absolute bottom-4 left-4 sm:bottom-5 sm:left-5 text-white max-w-md">
                    <span class="inline-block px-2.5 py-1 text-xs font-bold rounded-full mb-2"
                          style="background: {{ $event['warna'] ?? '#1D4ED8' }}">
                        {{ $event['kategori'] ?? 'Event' }}
                    </span>
                    <h3 class="text-base sm:text-lg font-bold leading-snug">{{ $event['judul'] }}</h3>
                    <p class="text-sm text-white/75 mt-1">
                        {{ \Carbon\Carbon::parse($event['tanggal_mulai'])->locale('id')->isoFormat('D MMM YYYY') }}
                        @if($event['tanggal_selesai'] && $event['tanggal_selesai'] !== $event['tanggal_mulai'])
                            — {{ \Carbon\Carbon::parse($event['tanggal_selesai'])->locale('id')->isoFormat('D MMM YYYY') }}
                        @endif
                        &nbsp;•&nbsp; SMKN 8 Medan
                    </p>
                </div>
            </div>
            @empty
            {{-- Fallback kalau event kosong --}}
            <div class="min-w-full h-full relative flex-shrink-0">
                <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=1200&h=400&fit=crop"
                     alt="SMKN 8 Medan" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/20 to-transparent"></div>
                <div class="absolute bottom-4 left-4 sm:bottom-5 sm:left-5 text-white">
                    <span class="inline-block px-2.5 py-1 bg-blue-600 text-xs font-bold rounded-full mb-2">Selamat Datang</span>
                    <h3 class="text-base sm:text-lg font-bold">Dashboard Admin SMKN 8 Medan</h3>
                    <p class="text-sm text-white/75 mt-1">Sistem Absensi Digital</p>
                </div>
            </div>
            @endforelse

        </div>

        {{-- Navigation Dots --}}
        <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-1.5 z-10" id="carouselDots">
            @foreach($events as $idx => $e)
            <button onclick="goToSlide({{ $idx }})"
                    class="carousel-dot h-2 w-2 rounded-full bg-white/50 hover:bg-white transition-all {{ $idx === 0 ? 'dot-active' : '' }}"></button>
            @endforeach
            @if(count($events) === 0)
            <button class="carousel-dot h-2 w-2 rounded-full dot-active"></button>
            @endif
        </div>

        {{-- Prev/Next --}}
        @if(count($events) > 1)
        <button onclick="prevSlide()"
                class="absolute left-3 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-white/20 hover:bg-white/40 backdrop-blur flex items-center justify-center text-white transition-all opacity-0 group-hover:opacity-100">
            <i class='bx bx-chevron-left text-xl'></i>
        </button>
        <button onclick="nextSlide()"
                class="absolute right-3 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-white/20 hover:bg-white/40 backdrop-blur flex items-center justify-center text-white transition-all opacity-0 group-hover:opacity-100">
            <i class='bx bx-chevron-right text-xl'></i>
        </button>
        @endif

    </div>
</div>

{{-- ===================== STAT CARDS ===================== --}}
<div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-5">

    {{-- Card Total Siswa --}}
    <div class="card-stat bg-white rounded-2xl p-5 shadow-sm border border-slate-100" data-aos="fade-up" data-aos-delay="0">
        <div class="flex items-start justify-between mb-4">
            <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center">
                <i class='bx bxs-group text-blue-600 text-xl'></i>
            </div>
            <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-full">Terdaftar</span>
        </div>
        <p class="text-3xl font-black text-slate-800">{{ $summary['total_siswa'] ?? 0 }}</p>
        <p class="text-slate-500 text-sm font-medium mt-1">Total Siswa</p>
        <div class="mt-3 h-1.5 bg-slate-100 rounded-full overflow-hidden">
            <div class="h-full bg-blue-500 rounded-full" style="width: 85%"></div>
        </div>
    </div>

    {{-- Card Event Aktif --}}
    <div class="card-stat bg-white rounded-2xl p-5 shadow-sm border border-slate-100" data-aos="fade-up" data-aos-delay="80">
        <div class="flex items-start justify-between mb-4">
            <div class="w-11 h-11 rounded-xl bg-purple-50 flex items-center justify-center">
                <i class='bx bxs-calendar-event text-purple-600 text-xl'></i>
            </div>
            <span class="text-xs font-semibold text-purple-600 bg-purple-50 px-2 py-1 rounded-full">Aktif</span>
        </div>
        <p class="text-3xl font-black text-slate-800">{{ $summary['total_event_aktif'] ?? 0 }}</p>
        <p class="text-slate-500 text-sm font-medium mt-1">Event Aktif</p>
        <div class="mt-3 h-1.5 bg-slate-100 rounded-full overflow-hidden">
            <div class="h-full bg-purple-500 rounded-full" style="width: 65%"></div>
        </div>
    </div>

    {{-- Card Pengumuman Aktif --}}
    <div class="card-stat bg-white rounded-2xl p-5 shadow-sm border border-slate-100" data-aos="fade-up" data-aos-delay="160">
        <div class="flex items-start justify-between mb-4">
            <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center">
                <i class='bx bxs-megaphone text-amber-500 text-xl'></i>
            </div>
            <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-2 py-1 rounded-full">Aktif</span>
        </div>
        <p class="text-3xl font-black text-slate-800">{{ $summary['total_pengumuman_aktif'] ?? 0 }}</p>
        <p class="text-slate-500 text-sm font-medium mt-1">Pengumuman</p>
        <div class="mt-3 h-1.5 bg-slate-100 rounded-full overflow-hidden">
            <div class="h-full bg-amber-400 rounded-full" style="width: 45%"></div>
        </div>
    </div>

    {{-- Card Izin Hari Ini --}}
    <div class="card-stat bg-white rounded-2xl p-5 shadow-sm border border-slate-100" data-aos="fade-up" data-aos-delay="240">
        <div class="flex items-start justify-between mb-4">
            <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center relative">
                <i class='bx bxs-envelope text-emerald-600 text-xl'></i>
            </div>
            <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">Hari Ini</span>
        </div>
        <p class="text-3xl font-black text-slate-800">{{ $summary['total_izin_hari_ini'] ?? 0 }}</p>
        <p class="text-slate-500 text-sm font-medium mt-1">Izin Disetujui</p>
        <div class="mt-3 h-1.5 bg-slate-100 rounded-full overflow-hidden">
            <div class="h-full bg-emerald-500 rounded-full" style="width: 30%"></div>
        </div>
    </div>

</div>

{{-- ===================== MAIN ROW: TABEL + DONUT ===================== --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-4 mb-5">

    {{-- TABEL ABSENSI HARI INI --}}
    <div class="xl:col-span-2 bg-white rounded-2xl p-5 shadow-sm border border-slate-100" data-aos="fade-right">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                    <i class='bx bxs-calendar-check text-blue-600 text-lg'></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-800">Absensi Hari Ini</h2>
                    <p class="text-xs text-slate-400 mt-0.5">
                        {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                    </p>
                </div>
            </div>
            <a href="{{ route('admin.absensi') }}"
               class="text-xs text-blue-600 font-semibold hover:underline flex items-center gap-1">
                Lihat Semua <i class='bx bx-chevron-right'></i>
            </a>
        </div>

        @php
            // Filter hanya absensi hari ini dari data terbaru
            $today = \Carbon\Carbon::today()->toDateString();
            $absensiHariIni = collect($absensiTerbaru)->filter(function($a) use ($today) {
                $tgl = is_string($a['tanggal'])
                    ? \Carbon\Carbon::parse($a['tanggal'])->toDateString()
                    : ($a['tanggal'] ?? '');
                return $tgl === $today;
            })->values();
        @endphp

        <div class="overflow-x-auto" style="max-height: 280px; overflow-y: auto;">
            <table class="w-full text-sm">
                <thead class="sticky top-0 bg-white z-10">
                    <tr class="border-b border-slate-100">
                        <th class="text-left text-xs font-bold text-slate-400 pb-3 pr-3 w-8">#</th>
                        <th class="text-left text-xs font-bold text-slate-400 pb-3 pr-3">Nama Siswa</th>
                        <th class="text-left text-xs font-bold text-slate-400 pb-3 pr-3">Kelas</th>
                        <th class="text-left text-xs font-bold text-slate-400 pb-3 pr-3">Masuk</th>
                        <th class="text-left text-xs font-bold text-slate-400 pb-3 pr-3">Status</th>
                        <th class="text-left text-xs font-bold text-slate-400 pb-3">Metode</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($absensiHariIni as $idx => $a)
                    @php
                        $initials = strtoupper(substr($a['nama_siswa'] ?? '?', 0, 2));
                        $avatarColors = ['#EFF6FF,#1D4ED8', '#F0FDF4,#16A34A', '#FFF7ED,#EA580C', '#FDF4FF,#9333EA'];
                        $ac = $avatarColors[$idx % 4];
                        [$abg, $atxt] = explode(',', $ac);
                        $jamMasuk = $a['jam_masuk']
                            ? \Carbon\Carbon::parse($a['jam_masuk'])->format('H:i')
                            : '—';
                    @endphp
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-2.5 pr-3 text-xs font-semibold text-slate-400">{{ $idx + 1 }}</td>
                        <td class="py-2.5 pr-3">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold flex-shrink-0"
                                     style="background:{{ $abg }};color:{{ $atxt }}">
                                    {{ $initials }}
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-700 text-sm leading-tight">{{ $a['nama_siswa'] ?? '—' }}</p>
                                    <p class="text-xs text-slate-400">{{ $a['jurusan'] ?? '—' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-2.5 pr-3">
                            <span class="bg-blue-50 text-blue-700 text-xs font-semibold px-2 py-0.5 rounded-full whitespace-nowrap">
                                {{ $a['kelas'] ?? '—' }}
                            </span>
                        </td>
                        <td class="py-2.5 pr-3">
                            <span class="font-mono font-semibold text-sm {{ $a['jam_masuk'] ? 'text-emerald-700' : 'text-slate-300' }}">
                                {{ $jamMasuk }}
                            </span>
                        </td>
                        <td class="py-2.5 pr-3">
                            <span class="status-pill pill-{{ $a['status'] ?? 'alpa' }}">{{ $a['status'] ?? '—' }}</span>
                        </td>
                        <td class="py-2.5">
                            @if(($a['metode'] ?? '-') === 'camera')
                                <span class="metode-badge metode-camera">
                                    <i class='bx bx-camera' style="font-size:10px"></i> Kamera
                                </span>
                            @elseif(($a['metode'] ?? '-') === 'barcode')
                                <span class="metode-badge metode-barcode">
                                    <i class='bx bx-qr' style="font-size:10px"></i> Barcode
                                </span>
                            @else
                                <span class="metode-dash">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-10 text-center text-slate-400">
                            <i class='bx bxs-calendar-x text-3xl block mb-2 text-slate-200'></i>
                            <span class="text-sm font-semibold">Belum ada absensi hari ini</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($absensiHariIni->count() > 0)
        <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between">
            <p class="text-xs text-slate-400">
                {{ $absensiHariIni->count() }} absensi hari ini
            </p>
            <div class="flex items-center gap-3 text-xs">
                <span class="flex items-center gap-1 text-emerald-700 font-semibold">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    Hadir: {{ $absensiHariIni->where('status','hadir')->count() }}
                </span>
                <span class="flex items-center gap-1 text-amber-700 font-semibold">
                    <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                    Terlambat: {{ $absensiHariIni->where('status','terlambat')->count() }}
                </span>
                <span class="flex items-center gap-1 text-red-700 font-semibold">
                    <span class="w-2 h-2 rounded-full bg-red-400"></span>
                    Alpa: {{ $absensiHariIni->where('status','alpa')->count() }}
                </span>
            </div>
        </div>
        @endif

    </div>

    {{-- DONUT CHART KEHADIRAN --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100" data-aos="fade-left">
        <div class="mb-4">
            <h2 class="text-base font-bold text-slate-800">Rekap Absensi</h2>
            <p class="text-xs text-slate-400 mt-0.5">Semua data tersimpan</p>
        </div>

        @php
            // Hitung dari absensiTerbaru (semua data)
            $allAbsensi = collect($absensiTerbaru);
            $totalAll  = max($allAbsensi->count(), 1);
            $cHadir    = $allAbsensi->where('status','hadir')->count();
            $cTerlambat= $allAbsensi->where('status','terlambat')->count();
            $cIzin     = $allAbsensi->where('status','izin')->count();
            $cAlpa     = $allAbsensi->where('status','alpa')->count();
            $pctHadir    = $totalAll > 0 ? round(($cHadir / $totalAll) * 100) : 0;
            $pctTerlambat= $totalAll > 0 ? round(($cTerlambat / $totalAll) * 100) : 0;
            $pctIzin     = $totalAll > 0 ? round(($cIzin / $totalAll) * 100) : 0;
            $pctAlpa     = $totalAll > 0 ? round(($cAlpa / $totalAll) * 100) : 0;
        @endphp

        <div class="relative h-40 flex items-center justify-center">
            <canvas id="donutChart"></canvas>
            <div class="absolute text-center pointer-events-none">
                <p class="text-2xl font-black text-slate-800">{{ $pctHadir }}%</p>
                <p class="text-xs text-slate-400">Hadir</p>
            </div>
        </div>

        <div class="mt-4 space-y-2">
            <div class="flex items-center justify-between text-sm p-2 rounded-xl hover:bg-slate-50 transition cursor-default">
                <span class="flex items-center gap-2 text-slate-600 font-medium">
                    <span class="w-3 h-3 rounded-full bg-blue-600 inline-block shadow-sm"></span>Hadir
                </span>
                <span class="font-bold text-slate-800">{{ $cHadir }} <span class="text-slate-400 font-normal text-xs">({{ $pctHadir }}%)</span></span>
            </div>
            <div class="flex items-center justify-between text-sm p-2 rounded-xl hover:bg-slate-50 transition cursor-default">
                <span class="flex items-center gap-2 text-slate-600 font-medium">
                    <span class="w-3 h-3 rounded-full bg-amber-400 inline-block shadow-sm"></span>Terlambat
                </span>
                <span class="font-bold text-slate-800">{{ $cTerlambat }} <span class="text-slate-400 font-normal text-xs">({{ $pctTerlambat }}%)</span></span>
            </div>
            <div class="flex items-center justify-between text-sm p-2 rounded-xl hover:bg-slate-50 transition cursor-default">
                <span class="flex items-center gap-2 text-slate-600 font-medium">
                    <span class="w-3 h-3 rounded-full bg-blue-300 inline-block shadow-sm"></span>Izin
                </span>
                <span class="font-bold text-slate-800">{{ $cIzin }} <span class="text-slate-400 font-normal text-xs">({{ $pctIzin }}%)</span></span>
            </div>
            <div class="flex items-center justify-between text-sm p-2 rounded-xl hover:bg-slate-50 transition cursor-default">
                <span class="flex items-center gap-2 text-slate-600 font-medium">
                    <span class="w-3 h-3 rounded-full bg-red-400 inline-block shadow-sm"></span>Alpa
                </span>
                <span class="font-bold text-slate-800">{{ $cAlpa }} <span class="text-slate-400 font-normal text-xs">({{ $pctAlpa }}%)</span></span>
            </div>
        </div>
    </div>

</div>

{{-- ===================== BOTTOM ROW: INFO CARDS + PENGUMUMAN ===================== --}}
<div class="grid grid-cols-1 xl:grid-cols-5 gap-4 mb-5">

    {{-- Info Cards --}}
    <div class="xl:col-span-3 grid grid-cols-2 gap-4">

        {{-- Card 1: Gradient Blue --}}
        <div class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-2xl p-5 shadow-lg text-white" data-aos="fade-up" data-aos-delay="0">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                    <i class='bx bxs-graduation text-white text-xl'></i>
                </div>
                <span class="text-xs bg-white/20 px-2 py-1 rounded-full font-medium">Semester ini</span>
            </div>
            <p class="text-2xl font-black">{{ $summary['total_siswa'] ?? 0 }}</p>
            <p class="text-blue-200 text-sm mt-1">Total Siswa Aktif</p>
            <div class="mt-3 flex items-center gap-1 text-xs text-blue-200">
                <i class='bx bxs-school'></i>
                <span>SMKN 8 Medan</span>
            </div>
        </div>

        {{-- Card 2: Event --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100" data-aos="fade-up" data-aos-delay="80">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center">
                    <i class='bx bxs-calendar-event text-purple-600 text-xl'></i>
                </div>
                <span class="text-xs text-purple-600 bg-purple-50 px-2 py-1 rounded-full font-semibold">Aktif</span>
            </div>
            <p class="text-2xl font-black text-slate-800">{{ $summary['total_event_aktif'] ?? 0 }}</p>
            <p class="text-slate-500 text-sm mt-1">Event Berjalan</p>
            <a href="{{ route('admin.event.index') }}"
               class="mt-3 text-xs text-purple-600 font-semibold hover:underline flex items-center gap-1">
                Kelola Event <i class='bx bx-chevron-right'></i>
            </a>
        </div>

        {{-- Card 3: Izin Pending --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100" data-aos="fade-up" data-aos-delay="160">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
                    <i class='bx bxs-envelope text-amber-500 text-xl'></i>
                </div>
                <span class="text-xs text-amber-600 bg-amber-50 px-2 py-1 rounded-full font-semibold">Hari Ini</span>
            </div>
            <p class="text-2xl font-black text-slate-800">{{ $summary['total_izin_hari_ini'] ?? 0 }}</p>
            <p class="text-slate-500 text-sm mt-1">Izin Disetujui</p>
            <a href="{{ route('admin.izin') }}"
               class="mt-3 text-xs text-amber-600 font-semibold hover:underline flex items-center gap-1">
                Kelola Izin <i class='bx bx-chevron-right'></i>
            </a>
        </div>

        {{-- Card 4: System Status --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100" data-aos="fade-up" data-aos-delay="240">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
                    <i class='bx bxs-server text-emerald-600 text-xl'></i>
                </div>
                <span class="flex items-center gap-1 text-xs text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full font-semibold">
                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                    Online
                </span>
            </div>
            <p class="text-2xl font-black text-slate-800">100%</p>
            <p class="text-slate-500 text-sm mt-1">Server Uptime</p>
            <div class="mt-3 flex items-center gap-1 text-xs text-emerald-600 font-semibold">
                <i class='bx bx-check-circle'></i>
                <span>Semua sistem normal</span>
            </div>
        </div>

    </div>

    {{-- Pengumuman Terbaru --}}
    <div class="xl:col-span-2 bg-white rounded-2xl p-5 shadow-sm border border-slate-100" data-aos="fade-up" data-aos-delay="100">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                    <i class='bx bxs-megaphone text-blue-600 text-lg'></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-800">Pengumuman</h2>
                    <p class="text-xs text-slate-400 mt-0.5">{{ $summary['total_pengumuman_aktif'] ?? 0 }} aktif</p>
                </div>
            </div>
            <a href="{{ route('admin.pengumuman.index') }}" class="text-xs text-blue-600 font-semibold hover:underline">
                Semua →
            </a>
        </div>

        {{-- Daftar Event sebagai Pengumuman --}}
        <div class="space-y-2.5" style="max-height: 240px; overflow-y: auto;">
            @forelse($events as $ev)
            <div class="flex gap-3 p-2.5 rounded-xl hover:bg-slate-50 transition cursor-pointer group border border-transparent hover:border-slate-100">
                <div class="w-2 h-2 rounded-full mt-1.5 flex-shrink-0"
                     style="background: {{ $ev['warna'] ?? '#1D4ED8' }}; box-shadow: 0 0 0 3px {{ $ev['warna'] ?? '#1D4ED8' }}22"></div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-slate-800 group-hover:text-blue-600 transition truncate">
                        {{ $ev['judul'] }}
                    </p>
                    <p class="text-xs text-slate-500 mt-0.5 line-clamp-1">{{ $ev['deskripsi'] ?? '' }}</p>
                    <p class="text-xs text-slate-400 mt-1 flex items-center gap-1">
                        <i class='bx bx-calendar text-[10px]'></i>
                        {{ \Carbon\Carbon::parse($ev['tanggal_mulai'])->locale('id')->isoFormat('D MMM YYYY') }}
                        <span class="inline-block px-1.5 py-0.5 rounded text-[9px] font-bold ml-1"
                              style="background: {{ $ev['warna'] ?? '#1D4ED8' }}22; color: {{ $ev['warna'] ?? '#1D4ED8' }}">
                            {{ $ev['kategori'] ?? '' }}
                        </span>
                    </p>
                </div>
            </div>
            @empty
            <p class="text-sm text-slate-400 text-center py-6">Tidak ada event aktif</p>
            @endforelse
        </div>
    </div>

</div>

{{-- ===================== FOOTER INFO ===================== --}}
<div class="bg-gradient-to-r from-slate-800 to-slate-900 rounded-2xl p-5 text-white shadow-lg" data-aos="fade-up">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-xl bg-white/10 backdrop-blur flex items-center justify-center">
                <i class='bx bxs-graduation text-2xl text-blue-400'></i>
            </div>
            <div>
                <h3 class="text-base font-bold">Selamat Datang, {{ session('user')['name'] ?? 'Administrator' }}</h3>
                <p class="text-slate-400 text-xs mt-0.5">SMKN 8 Medan — Sistem Absensi Digital</p>
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

{{-- ===================== SCRIPTS ===================== --}}
<script>
document.addEventListener('DOMContentLoaded', () => {

    // ========== Tanggal & Jam ==========
    const optsDate = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
    document.getElementById('tanggalFooter').textContent =
        new Date().toLocaleDateString('id-ID', optsDate);

    function updateJam() {
        const t = new Date().toLocaleTimeString('id-ID', { hour:'2-digit', minute:'2-digit', second:'2-digit' });
        document.getElementById('jamFooter').textContent = t;
    }
    updateJam();
    setInterval(updateJam, 1000);

    // ========== CAROUSEL ==========
    let currentSlide = 0;
    const slidesEl = document.getElementById('carouselSlides');
    const dots     = document.querySelectorAll('.carousel-dot');
    const total    = dots.length;
    let autoPlay;

    function updateCarousel() {
        if (slidesEl) slidesEl.style.transform = `translateX(-${currentSlide * 100}%)`;
        dots.forEach((d, i) => {
            d.classList.toggle('dot-active', i === currentSlide);
            if (i === currentSlide) {
                d.style.background = 'white';
                d.style.width = '20px';
            } else {
                d.style.background = 'rgba(255,255,255,0.5)';
                d.style.width = '8px';
            }
        });
    }

    window.goToSlide = (i) => { currentSlide = i; updateCarousel(); resetAuto(); };
    window.nextSlide = () => { currentSlide = (currentSlide + 1) % total; updateCarousel(); resetAuto(); };
    window.prevSlide = () => { currentSlide = (currentSlide - 1 + total) % total; updateCarousel(); resetAuto(); };

    function resetAuto() {
        clearInterval(autoPlay);
        if (total > 1) autoPlay = setInterval(window.nextSlide, 5000);
    }

    updateCarousel();
    resetAuto();

    // ========== DONUT CHART ==========
    const ctx = document.getElementById('donutChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Hadir', 'Terlambat', 'Izin', 'Alpa'],
                datasets: [{
                    data: [
                        {{ $cHadir }},
                        {{ $cTerlambat }},
                        {{ $cIzin }},
                        {{ $cAlpa }}
                    ],
                    backgroundColor: ['#1D4ED8', '#F59E0B', '#93C5FD', '#F87171'],
                    borderWidth: 0,
                    hoverOffset: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0F172A',
                        padding: 12,
                        cornerRadius: 12,
                        callbacks: {
                            label: (ctx) => ` ${ctx.label}: ${ctx.parsed}`
                        }
                    }
                }
            }
        });
    }

});
</script>

@endsection