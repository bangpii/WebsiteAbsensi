@extends('layouts.admin')

@section('title', 'Manajemen Event')

@section('content')

{{-- ================================================================
     TOAST NOTIFICATIONS
================================================================ --}}
@if(session('success'))
<div id="toast-ok" class="fixed top-5 right-5 z-[9999] flex items-center gap-3 bg-white border border-emerald-200 text-emerald-800 px-5 py-3.5 rounded-2xl shadow-2xl text-sm font-semibold" style="max-width:380px;animation:toastSlideIn .35s cubic-bezier(.34,1.56,.64,1) forwards;">
    <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
        <i class='bx bx-check text-emerald-600 text-lg'></i>
    </div>
    <span>{{ session('success') }}</span>
    <button onclick="this.parentElement.remove()" class="ml-auto text-emerald-400 hover:text-emerald-700 transition-colors"><i class='bx bx-x text-lg'></i></button>
</div>
@endif

@if(session('error'))
<div id="toast-err" class="fixed top-5 right-5 z-[9999] flex items-center gap-3 bg-white border border-red-200 text-red-800 px-5 py-3.5 rounded-2xl shadow-2xl text-sm font-semibold" style="max-width:380px;animation:toastSlideIn .35s cubic-bezier(.34,1.56,.64,1) forwards;">
    <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
        <i class='bx bx-x text-red-600 text-lg'></i>
    </div>
    <span>{{ session('error') }}</span>
    <button onclick="this.parentElement.remove()" class="ml-auto text-red-400 hover:text-red-700 transition-colors"><i class='bx bx-x text-lg'></i></button>
</div>
@endif

@if($error ?? null)
<div class="mb-5 flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 px-5 py-3.5 rounded-2xl text-sm font-medium" data-aos="fade-down">
    <i class='bx bxs-error-circle text-xl flex-shrink-0'></i>
    <span>Gagal memuat data dari server: {{ $error }}</span>
</div>
@endif

{{-- ================================================================
     PAGE HEADER
================================================================ --}}
@php
    $events       = $events ?? collect();
    $totalAktif   = collect($events)->where('is_active', true)->count();
    $totalNonaktif= collect($events)->where('is_active', false)->count();

    $kategoriList = ['Nasional','Kompetisi','Industri','Akademik','Olahraga','Seni','Sosial','Lainnya'];

    $warnaPreset  = [
        '#2563EB' => 'Biru',
        '#7C3AED' => 'Ungu',
        '#0891B2' => 'Cyan',
        '#16A34A' => 'Hijau',
        '#DC2626' => 'Merah',
        '#D97706' => 'Kuning',
        '#DB2777' => 'Pink',
        '#0F172A' => 'Hitam',
    ];
@endphp

<div class="mb-6" data-aos="fade-down">
    <div class="flex items-start justify-between flex-wrap gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <div class="w-1.5 h-6 bg-blue-600 rounded-full"></div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Manajemen Event Sekolah</h1>
            </div>
            <p class="text-slate-500 text-sm ml-3.5">Kelola agenda kegiatan & event yang tampil di aplikasi siswa secara real-time</p>
        </div>
        <button onclick="openModal('modalTambah')"
            class="group flex items-center gap-2.5 px-5 py-2.5 bg-blue-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-200 hover:bg-blue-700 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-blue-200 transition-all duration-200 active:scale-95">
            <div class="w-6 h-6 rounded-lg bg-white/20 flex items-center justify-center group-hover:rotate-90 transition-transform duration-300">
                <i class='bx bx-plus text-sm'></i>
            </div>
            Tambah Event
        </button>
    </div>
</div>

{{-- ================================================================
     STAT CARDS
================================================================ --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <!-- Total -->
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md hover:border-blue-200 transition-all duration-200 group" data-aos="fade-up" data-aos-delay="0">
        <div class="flex items-start justify-between">
            <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center shadow-md shadow-blue-200 group-hover:scale-110 transition-transform duration-200">
                <i class='bx bxs-calendar-event text-white text-xl'></i>
            </div>
            <span class="text-[10px] font-bold text-blue-500 bg-blue-50 px-2 py-0.5 rounded-lg">TOTAL</span>
        </div>
        <p class="text-3xl font-black text-slate-800 mt-3 leading-none">{{ collect($events)->count() }}</p>
        <p class="text-xs text-slate-500 font-medium mt-1">Event Terdaftar</p>
    </div>
    <!-- Aktif -->
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md hover:border-emerald-200 transition-all duration-200 group" data-aos="fade-up" data-aos-delay="60">
        <div class="flex items-start justify-between">
            <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center shadow-md shadow-emerald-200 group-hover:scale-110 transition-transform duration-200">
                <i class='bx bxs-show text-white text-xl'></i>
            </div>
            <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-lg">LIVE</span>
        </div>
        <p class="text-3xl font-black text-slate-800 mt-3 leading-none">{{ $totalAktif }}</p>
        <p class="text-xs text-slate-500 font-medium mt-1">Ditayangkan</p>
    </div>
    <!-- Nonaktif -->
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md hover:border-slate-300 transition-all duration-200 group" data-aos="fade-up" data-aos-delay="120">
        <div class="flex items-start justify-between">
            <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-slate-400 to-slate-600 flex items-center justify-center shadow-md shadow-slate-200 group-hover:scale-110 transition-transform duration-200">
                <i class='bx bxs-hide text-white text-xl'></i>
            </div>
            <span class="text-[10px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-lg">DRAFT</span>
        </div>
        <p class="text-3xl font-black text-slate-800 mt-3 leading-none">{{ $totalNonaktif }}</p>
        <p class="text-xs text-slate-500 font-medium mt-1">Disembunyikan</p>
    </div>
    <!-- Mendatang -->
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md hover:border-purple-200 transition-all duration-200 group" data-aos="fade-up" data-aos-delay="180">
        <div class="flex items-start justify-between">
            <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-purple-500 to-purple-700 flex items-center justify-center shadow-md shadow-purple-200 group-hover:scale-110 transition-transform duration-200">
                <i class='bx bxs-time text-white text-xl'></i>
            </div>
            <span class="text-[10px] font-bold text-purple-600 bg-purple-50 px-2 py-0.5 rounded-lg">SEGERA</span>
        </div>
        <p class="text-3xl font-black text-slate-800 mt-3 leading-none" id="statMendatang">
            {{ collect($events)->filter(fn($e) => isset($e['tanggal_mulai']) && $e['tanggal_mulai'] >= now()->toDateString())->count() }}
        </p>
        <p class="text-xs text-slate-500 font-medium mt-1">Mendatang</p>
    </div>
</div>

{{-- ================================================================
     MAIN LAYOUT: DAFTAR + DETAIL PANEL
================================================================ --}}
<div class="flex gap-5 items-start">

    {{-- ========================
         KOLOM KIRI — FILTER + LIST
    ======================== --}}
    <div class="flex-1 min-w-0">

        {{-- Filter Bar --}}
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm mb-4" data-aos="fade-up">
            <div class="flex flex-wrap items-center gap-3">
                <div class="relative flex-1 min-w-[180px]">
                    <i class='bx bx-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-base'></i>
                    <input type="text" id="searchInput" placeholder="Cari event..."
                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 text-xs font-medium rounded-xl outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                </div>
                <select id="filterStatus" class="bg-slate-50 border border-slate-200 text-xs font-bold rounded-xl px-3 py-2.5 outline-none focus:ring-2 focus:ring-blue-500/20 text-slate-600 cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
                </select>
                <select id="filterKategori" class="bg-slate-50 border border-slate-200 text-xs font-bold rounded-xl px-3 py-2.5 outline-none focus:ring-2 focus:ring-blue-500/20 text-slate-600 cursor-pointer">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoriList as $k)
                    <option value="{{ $k }}">{{ $k }}</option>
                    @endforeach
                </select>
                <button onclick="resetFilter()" class="text-slate-400 hover:text-slate-700 text-xs font-bold flex items-center gap-1 transition-colors px-2 py-2 rounded-lg hover:bg-slate-100">
                    <i class='bx bx-reset text-sm'></i> Reset
                </button>
                <span class="ml-auto text-xs text-slate-400 font-medium">
                    <span id="rowCount">{{ collect($events)->count() }}</span> event
                </span>
            </div>
        </div>

        {{-- ─── Grid Cards Event ─── --}}
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-4" id="eventsGrid" data-aos="fade-up" data-aos-delay="50">

            @forelse(collect($events) as $ev)
            @php
                $ev        = (array) $ev;
                $warna     = $ev['warna'] ?? '#2563EB';
                $isActive  = $ev['is_active'] ?? true;
                $kategori  = $ev['kategori'] ?? 'Lainnya';
                $tglMulai  = $ev['tanggal_mulai'] ?? null;
                $tglSelesai= $ev['tanggal_selesai'] ?? null;
                $isMendatang = $tglMulai && $tglMulai >= now()->toDateString();
            @endphp

            <div class="ev-card group bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl hover:border-blue-200 hover:-translate-y-0.5 transition-all duration-200 cursor-pointer overflow-hidden"
                data-id="{{ $ev['id'] }}"
                data-search="{{ strtolower($ev['judul']) }}"
                data-active="{{ $isActive ? '1' : '0' }}"
                data-kategori="{{ $kategori }}"
                onclick="openPreview({{ json_encode($ev) }})">

                {{-- Gambar --}}
                <div class="relative h-44 bg-slate-100 overflow-hidden">
                    @if(!empty($ev['gambar_url']))
                    <img src="{{ $ev['gambar_url'] }}" alt="{{ $ev['judul'] }}"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                    <div class="w-full h-full flex items-center justify-center" style="background: linear-gradient(135deg, {{ $warna }}20, {{ $warna }}08);">
                        <i class='bx bxs-calendar-event text-5xl' style="color: {{ $warna }}40"></i>
                    </div>
                    @endif

                    {{-- Overlay gradient --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>

                    {{-- Top badges --}}
                    <div class="absolute top-3 left-3 right-3 flex items-start justify-between">
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-white px-2 py-1 rounded-lg backdrop-blur-sm"
                            style="background-color: {{ $warna }}CC;">
                            {{ $kategori }}
                        </span>
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-1 rounded-lg backdrop-blur-sm border
                            {{ $isActive ? 'bg-emerald-500/90 text-white border-emerald-400/30' : 'bg-slate-800/80 text-slate-300 border-white/10' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $isActive ? 'bg-white animate-pulse' : 'bg-slate-400' }}"></span>
                            {{ $isActive ? 'Live' : 'Draft' }}
                        </span>
                    </div>

                    {{-- Bottom date overlay --}}
                    @if($tglMulai)
                    <div class="absolute bottom-3 left-3 flex items-center gap-1.5">
                        <div class="flex items-center gap-1 text-[10px] font-bold text-white bg-black/40 backdrop-blur-sm px-2.5 py-1 rounded-lg">
                            <i class='bx bx-calendar text-xs'></i>
                            {{ \Carbon\Carbon::parse($tglMulai)->format('d M Y') }}
                            @if($tglSelesai && $tglSelesai !== $tglMulai)
                            <span class="text-white/60 mx-0.5">→</span>
                            {{ \Carbon\Carbon::parse($tglSelesai)->format('d M Y') }}
                            @endif
                        </div>
                        @if($isMendatang)
                        <span class="text-[9px] font-black text-amber-300 bg-amber-500/20 border border-amber-400/30 px-2 py-0.5 rounded-lg backdrop-blur-sm">SEGERA</span>
                        @endif
                    </div>
                    @endif
                </div>

                {{-- Body --}}
                <div class="p-4">
                    {{-- Accent bar + title --}}
                    <div class="flex items-start gap-3 mb-3">
                        <div class="w-1 h-10 rounded-full flex-shrink-0 mt-0.5" style="background-color: {{ $warna }}"></div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-slate-800 text-sm leading-tight line-clamp-2 group-hover:text-blue-700 transition-colors">
                                {{ $ev['judul'] }}
                            </h3>
                            @if(!empty($ev['deskripsi']))
                            <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">{{ $ev['deskripsi'] }}</p>
                            @endif
                        </div>
                    </div>

                    {{-- Action row --}}
                    <div class="flex items-center gap-2 pt-3 border-t border-slate-50" onclick="event.stopPropagation()">

                        {{-- Toggle --}}
                        <form action="{{ route('admin.event.toggle', $ev['id']) }}" method="POST">
                            @csrf
                            <button type="submit" title="{{ $isActive ? 'Sembunyikan' : 'Tayangkan' }}"
                                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 text-[10px] font-bold rounded-lg border transition-all
                                {{ $isActive
                                    ? 'bg-emerald-50 text-emerald-600 border-emerald-200 hover:bg-emerald-100'
                                    : 'bg-slate-100 text-slate-500 border-slate-200 hover:bg-slate-200' }}">
                                <i class='bx {{ $isActive ? "bxs-hide" : "bxs-show" }} text-xs'></i>
                                {{ $isActive ? 'Sembunyikan' : 'Tayangkan' }}
                            </button>
                        </form>

                        {{-- Edit --}}
                        <button onclick="openEditModal({{ json_encode($ev) }})"
                            class="inline-flex items-center gap-1.5 px-2.5 py-1.5 text-[10px] font-bold rounded-lg border bg-slate-50 text-slate-600 border-slate-200 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 transition-all">
                            <i class='bx bx-edit-alt text-xs'></i> Edit
                        </button>

                        {{-- Hapus --}}
                        <button onclick="openDeleteModal({{ $ev['id'] }}, '{{ addslashes($ev['judul']) }}')"
                            class="inline-flex items-center gap-1.5 px-2.5 py-1.5 text-[10px] font-bold rounded-lg border bg-slate-50 text-slate-500 border-slate-200 hover:bg-red-50 hover:text-red-500 hover:border-red-200 transition-all">
                            <i class='bx bx-trash text-xs'></i> Hapus
                        </button>

                        {{-- Urutan --}}
                        <span class="ml-auto text-[10px] text-slate-300 font-medium">#{{ $ev['urutan'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
            @empty

            {{-- Empty State --}}
            <div class="col-span-2 bg-white rounded-3xl border border-dashed border-slate-200 py-24 text-center" data-aos="fade-up">
                <div class="flex flex-col items-center gap-4">
                    <div class="relative">
                        <div class="w-24 h-24 rounded-3xl bg-blue-50 flex items-center justify-center">
                            <i class='bx bxs-calendar-event text-5xl text-blue-300'></i>
                        </div>
                        <div class="absolute -top-1 -right-1 w-7 h-7 rounded-xl bg-blue-100 flex items-center justify-center">
                            <i class='bx bx-plus text-blue-500 text-sm'></i>
                        </div>
                    </div>
                    <div>
                        <p class="text-slate-600 font-bold text-lg">Belum ada event</p>
                        <p class="text-slate-400 text-sm mt-1">Tambahkan event pertama untuk ditampilkan ke siswa</p>
                    </div>
                    <button onclick="openModal('modalTambah')"
                        class="mt-1 px-6 py-3 bg-blue-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-200 hover:bg-blue-700 transition-all active:scale-95">
                        + Tambah Event Pertama
                    </button>
                </div>
            </div>
            @endforelse

        </div>

        {{-- No filter result --}}
        <div id="noResult" class="hidden bg-white rounded-2xl border border-slate-100 py-16 text-center mt-4">
            <i class='bx bx-search text-4xl text-slate-200 block mb-3'></i>
            <p class="text-slate-400 text-sm font-medium">Tidak ditemukan event yang cocok</p>
            <button onclick="resetFilter()" class="text-blue-600 text-xs font-bold hover:underline mt-2">Reset filter</button>
        </div>

    </div>

    {{-- ========================
         PANEL PREVIEW (kanan)
    ======================== --}}
    <div id="previewPanel"
        class="hidden flex-col bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden"
        style="width:340px; flex-shrink:0; position:sticky; top:80px; max-height:calc(100vh - 120px); overflow-y:auto;">

        {{-- Header preview --}}
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-slate-800 to-slate-900 flex-shrink-0">
            <div class="flex items-center gap-2">
                <i class='bx bxs-mobile-alt text-slate-400 text-sm'></i>
                <span class="text-xs font-bold text-slate-300 tracking-wide">Preview Aplikasi Siswa</span>
            </div>
            <button onclick="closePreview()" class="w-7 h-7 rounded-lg bg-white/10 hover:bg-white/20 flex items-center justify-center text-white/50 hover:text-white transition-all">
                <i class='bx bx-x text-base'></i>
            </button>
        </div>

        {{-- Phone mockup --}}
        <div class="p-5 bg-gradient-to-b from-slate-800 to-slate-900">
            <div class="bg-slate-900 rounded-[2rem] p-3 shadow-2xl mx-auto border border-white/5" style="max-width:260px;">

                {{-- Status bar --}}
                <div class="flex items-center justify-between px-3 py-1 mb-2">
                    <span class="text-white/50 text-[10px] font-medium">09:41</span>
                    <div class="flex items-center gap-1">
                        <div class="flex gap-0.5 items-end h-3">
                            <div class="w-0.5 h-1 bg-white/40 rounded-full"></div>
                            <div class="w-0.5 h-2 bg-white/60 rounded-full"></div>
                            <div class="w-0.5 h-3 bg-white rounded-full"></div>
                        </div>
                        <i class='bx bx-wifi text-white/70 text-xs ml-0.5'></i>
                        <i class='bx bxs-battery-full text-white/70 text-xs'></i>
                    </div>
                </div>

                {{-- App screen --}}
                <div class="bg-gray-50 rounded-2xl overflow-hidden">
                    {{-- App header --}}
                    <div class="bg-blue-600 px-4 py-3">
                        <p class="text-blue-200 text-[9px] font-semibold">SMKN 8 Medan</p>
                        <p class="text-white font-black text-sm tracking-tight">Event Sekolah</p>
                    </div>

                    {{-- Event card preview --}}
                    <div class="p-3">
                        <div id="mockupImageWrap" class="rounded-xl overflow-hidden mb-2.5 h-28 bg-slate-200 relative">
                            <img id="mockupImage" src="" alt="" class="w-full h-full object-cover hidden">
                            <div id="mockupImagePlaceholder" class="w-full h-full flex items-center justify-center">
                                <i class='bx bxs-calendar-event text-3xl text-slate-300'></i>
                            </div>
                            <div id="mockupKategoriBadge" class="absolute top-2 left-2 text-[8px] font-black text-white px-2 py-0.5 rounded-md" style="background-color:#2563EB">—</div>
                        </div>

                        <div id="mockupTitleWrap" class="mb-1.5 flex items-start gap-1.5">
                            <div id="mockupAccent" class="w-0.5 h-8 rounded-full flex-shrink-0 mt-0.5" style="background-color:#2563EB"></div>
                            <p id="mockupJudul" class="font-black text-slate-800 text-[11px] leading-tight line-clamp-2">Judul Event</p>
                        </div>

                        <p id="mockupDeskripsi" class="text-[9px] text-slate-500 leading-relaxed line-clamp-3 mb-2">Deskripsi event akan tampil di sini...</p>

                        <div id="mockupDateRow" class="flex items-center gap-1 text-[8px] text-slate-400 font-medium mb-2">
                            <i class='bx bx-calendar text-[8px]'></i>
                            <span id="mockupTanggal">—</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span id="mockupStatusBadge" class="text-[8px] font-black text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded-md">Aktif</span>
                            <span class="text-[8px] text-blue-600 font-bold">Lihat Detail →</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Meta info --}}
        <div class="px-5 py-4 space-y-2.5 border-t border-slate-100 flex-shrink-0">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Detail Event</p>

            <div class="space-y-2">
                <div class="flex items-center justify-between text-xs py-2 border-b border-slate-50">
                    <span class="text-slate-400 font-medium flex items-center gap-1.5"><i class='bx bx-user text-slate-300'></i> Admin</span>
                    <span id="previewAdmin" class="font-bold text-slate-700 truncate ml-2 max-w-[140px]">—</span>
                </div>
                <div class="flex items-center justify-between text-xs py-2 border-b border-slate-50">
                    <span class="text-slate-400 font-medium flex items-center gap-1.5"><i class='bx bx-tag text-slate-300'></i> Kategori</span>
                    <span id="previewKategori" class="font-bold text-slate-700">—</span>
                </div>
                <div class="flex items-center justify-between text-xs py-2 border-b border-slate-50">
                    <span class="text-slate-400 font-medium flex items-center gap-1.5"><i class='bx bx-calendar text-slate-300'></i> Mulai</span>
                    <span id="previewMulai" class="font-medium text-slate-600">—</span>
                </div>
                <div class="flex items-center justify-between text-xs py-2 border-b border-slate-50">
                    <span class="text-slate-400 font-medium flex items-center gap-1.5"><i class='bx bx-calendar-check text-slate-300'></i> Selesai</span>
                    <span id="previewSelesai" class="font-medium text-slate-600">—</span>
                </div>
                <div class="flex items-center justify-between text-xs py-2">
                    <span class="text-slate-400 font-medium flex items-center gap-1.5"><i class='bx bx-sort text-slate-300'></i> Urutan</span>
                    <span id="previewUrutan" class="font-black text-blue-600">—</span>
                </div>
            </div>

            {{-- Quick action buttons --}}
            <div id="previewActions" class="flex gap-2 mt-2 pt-2 border-t border-slate-100"></div>
        </div>

    </div>
</div>


{{-- ================================================================
     MODAL TAMBAH EVENT
================================================================ --}}
<div id="modalTambah" class="modal-overlay fixed inset-0 z-[9998] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md" onclick="closeModal('modalTambah')"></div>
    <div class="modal-card relative bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden z-10 max-h-[92vh] flex flex-col">

        {{-- Modal Header --}}
        <div class="px-6 py-5 border-b border-slate-100 flex items-center gap-3 flex-shrink-0 bg-gradient-to-r from-blue-600 to-blue-800">
            <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                <i class='bx bxs-calendar-event text-white text-xl'></i>
            </div>
            <div>
                <h3 class="font-bold text-white text-base">Tambah Event Baru</h3>
                <p class="text-blue-200 text-xs">Event akan langsung tampil di aplikasi siswa jika aktif</p>
            </div>
            <button onclick="closeModal('modalTambah')" class="ml-auto w-8 h-8 rounded-xl flex items-center justify-center text-white/60 hover:bg-white/20 hover:text-white transition-all">
                <i class='bx bx-x text-xl'></i>
            </button>
        </div>

        <form action="{{ route('admin.event.store') }}" method="POST" enctype="multipart/form-data" class="overflow-y-auto flex-1">
            @csrf
            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">

                {{-- Judul --}}
                <div class="sm:col-span-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">
                        Judul Event <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="judul" required placeholder="Contoh: Lomba Kompetensi Siswa 2026"
                        class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all font-medium placeholder-slate-300">
                </div>

                {{-- Deskripsi --}}
                <div class="sm:col-span-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Deskripsi</label>
                    <textarea name="deskripsi" rows="3" placeholder="Tulis deskripsi singkat event ini..."
                        class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all resize-none font-medium placeholder-slate-300"></textarea>
                </div>

                {{-- Kategori --}}
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Kategori</label>
                    <select name="kategori"
                        class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all font-medium text-slate-700 cursor-pointer">
                        @foreach($kategoriList as $k)
                        <option value="{{ $k }}">{{ $k }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Warna --}}
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Warna Tema</label>
                    <div class="flex items-center gap-2">
                        <input type="color" name="warna" id="tambahWarna" value="#2563EB"
                            class="w-11 h-11 rounded-xl border border-slate-200 cursor-pointer bg-slate-50 p-1.5 flex-shrink-0"
                            oninput="updateWarnaPreview(this, 'tambahWarnaPreview')">
                        <div id="tambahWarnaPreview" class="flex-1 h-11 rounded-xl border flex items-center px-3 gap-2 transition-all" style="background:#2563EB15;border-color:#2563EB40">
                            <div class="w-3 h-3 rounded-full flex-shrink-0" style="background:#2563EB" id="tambahWarnaDot"></div>
                            <div>
                                <p class="text-[10px] font-black" style="color:#2563EB" id="tambahWarnaHex">#2563EB</p>
                                <p class="text-[9px] text-slate-400">Warna aksen event</p>
                            </div>
                        </div>
                    </div>
                    {{-- Preset colors --}}
                    <div class="flex gap-1.5 mt-2 flex-wrap">
                        @foreach($warnaPreset as $hex => $label)
                        <button type="button"
                            onclick="setWarna('{{ $hex }}', 'tambahWarna', 'tambahWarnaPreview', 'tambahWarnaDot', 'tambahWarnaHex')"
                            title="{{ $label }}"
                            class="w-6 h-6 rounded-lg border-2 border-transparent hover:border-slate-300 hover:scale-110 transition-all flex-shrink-0"
                            style="background-color:{{ $hex }}">
                        </button>
                        @endforeach
                    </div>
                </div>

                {{-- Tanggal Mulai --}}
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">
                        Tanggal Mulai <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_mulai" required
                        class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all font-medium text-slate-700 cursor-pointer">
                </div>

                {{-- Tanggal Selesai --}}
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai"
                        class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all font-medium text-slate-700 cursor-pointer">
                </div>

                {{-- Gambar --}}
                <div class="sm:col-span-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Gambar Event</label>
                    <div id="tambahDropzone"
                        class="relative border-2 border-dashed border-slate-200 rounded-2xl transition-all duration-200 hover:border-blue-400 hover:bg-blue-50/30 cursor-pointer overflow-hidden"
                        onclick="document.getElementById('tambahGambarInput').click()"
                        ondragover="handleDragOver(event, 'tambahDropzone')"
                        ondragleave="handleDragLeave('tambahDropzone')"
                        ondrop="handleDrop(event, 'tambahGambarInput', 'tambahDropzone', 'tambahGambarPreview')">

                        <input type="file" name="gambar" id="tambahGambarInput" accept="image/jpeg,image/png,image/webp"
                            class="sr-only"
                            onchange="previewGambar(this, 'tambahDropzone', 'tambahGambarPreview')">

                        <div id="tambahGambarPreview" class="hidden">
                            <img src="" alt="" class="w-full h-48 object-cover rounded-xl">
                            <button type="button" onclick="clearGambar(event, 'tambahGambarInput', 'tambahDropzone', 'tambahGambarPreview')"
                                class="absolute top-2 right-2 w-7 h-7 bg-black/50 hover:bg-red-500 text-white rounded-lg flex items-center justify-center transition-all">
                                <i class='bx bx-x text-sm'></i>
                            </button>
                        </div>

                        <div id="tambahDropzoneContent" class="py-10 flex flex-col items-center gap-3">
                            <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center">
                                <i class='bx bx-image-add text-blue-400 text-2xl'></i>
                            </div>
                            <div class="text-center">
                                <p class="text-sm font-bold text-slate-600">Klik atau drag & drop gambar</p>
                                <p class="text-xs text-slate-400 mt-0.5">JPEG, PNG, WebP — Maks 5MB</p>
                            </div>
                        </div>
                    </div>
                    <p class="text-[10px] text-slate-400 mt-1.5 flex items-center gap-1">
                        <i class='bx bx-cloud-upload text-slate-300'></i>
                        Gambar akan diunggah otomatis ke Cloudinary saat disimpan
                    </p>
                </div>

                {{-- Urutan --}}
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Urutan Tampil</label>
                    <input type="number" name="urutan" value="0" min="0"
                        class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all font-medium">
                    <p class="text-[9px] text-slate-400 mt-1">Angka kecil = tampil lebih awal</p>
                </div>

                {{-- Toggle Aktif --}}
                <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-100 self-end">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <div class="relative">
                            <input type="checkbox" name="is_active" value="1" checked class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 rounded-full peer-checked:bg-blue-600 transition-colors"></div>
                            <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-all peer-checked:translate-x-5"></div>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-700">Tayangkan Sekarang</p>
                            <p class="text-[10px] text-slate-400">Langsung tampil di aplikasi</p>
                        </div>
                    </label>
                </div>

            </div>

            {{-- Footer actions --}}
            <div class="px-6 pb-6 flex gap-3 flex-shrink-0">
                <button type="button" onclick="closeModal('modalTambah')"
                    class="flex-1 py-3 bg-slate-100 text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-200 transition-all active:scale-95">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 py-3 bg-blue-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-200 hover:bg-blue-700 transition-all active:scale-95 flex items-center justify-center gap-2">
                    <i class='bx bx-cloud-upload text-lg'></i> Simpan & Publikasikan
                </button>
            </div>
        </form>
    </div>
</div>


{{-- ================================================================
     MODAL EDIT EVENT
================================================================ --}}
<div id="modalEdit" class="modal-overlay fixed inset-0 z-[9998] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md" onclick="closeModal('modalEdit')"></div>
    <div class="modal-card relative bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden z-10 max-h-[92vh] flex flex-col">

        {{-- Modal Header --}}
        <div class="px-6 py-5 border-b border-slate-100 flex items-center gap-3 flex-shrink-0 bg-gradient-to-r from-amber-500 to-amber-700">
            <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                <i class='bx bx-edit text-white text-xl'></i>
            </div>
            <div>
                <h3 class="font-bold text-white text-base">Edit Event</h3>
                <p class="text-amber-200 text-xs">Perubahan akan langsung disinkronkan secara real-time</p>
            </div>
            <button onclick="closeModal('modalEdit')" class="ml-auto w-8 h-8 rounded-xl flex items-center justify-center text-white/60 hover:bg-white/20 hover:text-white transition-all">
                <i class='bx bx-x text-xl'></i>
            </button>
        </div>

        <form id="formEdit" action="" method="POST" enctype="multipart/form-data" class="overflow-y-auto flex-1">
            @csrf
            <input type="hidden" name="_method" value="PUT">
            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">

                <div class="sm:col-span-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Judul Event <span class="text-red-500">*</span></label>
                    <input type="text" name="judul" id="editJudul" required
                        class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all font-medium">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Deskripsi</label>
                    <textarea name="deskripsi" id="editDeskripsi" rows="3"
                        class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all resize-none font-medium"></textarea>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Kategori</label>
                    <select name="kategori" id="editKategori"
                        class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all font-medium text-slate-700 cursor-pointer">
                        @foreach($kategoriList as $k)
                        <option value="{{ $k }}">{{ $k }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Warna Tema</label>
                    <div class="flex items-center gap-2">
                        <input type="color" name="warna" id="editWarna" value="#2563EB"
                            class="w-11 h-11 rounded-xl border border-slate-200 cursor-pointer bg-slate-50 p-1.5 flex-shrink-0"
                            oninput="updateWarnaPreview(this, 'editWarnaPreview')">
                        <div id="editWarnaPreview" class="flex-1 h-11 rounded-xl border flex items-center px-3 gap-2 transition-all" style="background:#2563EB15;border-color:#2563EB40">
                            <div class="w-3 h-3 rounded-full flex-shrink-0" style="background:#2563EB" id="editWarnaDot"></div>
                            <div>
                                <p class="text-[10px] font-black" style="color:#2563EB" id="editWarnaHex">#2563EB</p>
                                <p class="text-[9px] text-slate-400">Warna aksen event</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-1.5 mt-2 flex-wrap">
                        @foreach($warnaPreset as $hex => $label)
                        <button type="button"
                            onclick="setWarna('{{ $hex }}', 'editWarna', 'editWarnaPreview', 'editWarnaDot', 'editWarnaHex')"
                            title="{{ $label }}"
                            class="w-6 h-6 rounded-lg border-2 border-transparent hover:border-slate-300 hover:scale-110 transition-all flex-shrink-0"
                            style="background-color:{{ $hex }}">
                        </button>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Tanggal Mulai <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_mulai" id="editTanggalMulai" required
                        class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all font-medium text-slate-700 cursor-pointer">
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" id="editTanggalSelesai"
                        class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all font-medium text-slate-700 cursor-pointer">
                </div>

                {{-- Gambar edit --}}
                <div class="sm:col-span-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Gambar Event</label>

                    {{-- Preview gambar existing --}}
                    <div id="editExistingImageWrap" class="hidden mb-3 relative">
                        <img id="editExistingImage" src="" alt="" class="w-full h-36 object-cover rounded-xl">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent rounded-xl flex items-end p-2">
                            <span class="text-[10px] text-white/80 font-medium">Gambar saat ini — Upload baru untuk mengganti</span>
                        </div>
                    </div>

                    <div id="editDropzone"
                        class="relative border-2 border-dashed border-slate-200 rounded-2xl transition-all duration-200 hover:border-amber-400 hover:bg-amber-50/30 cursor-pointer overflow-hidden"
                        onclick="document.getElementById('editGambarInput').click()"
                        ondragover="handleDragOver(event, 'editDropzone')"
                        ondragleave="handleDragLeave('editDropzone')"
                        ondrop="handleDrop(event, 'editGambarInput', 'editDropzone', 'editGambarPreview')">

                        <input type="file" name="gambar" id="editGambarInput" accept="image/jpeg,image/png,image/webp"
                            class="sr-only"
                            onchange="previewGambar(this, 'editDropzone', 'editGambarPreview')">

                        <div id="editGambarPreview" class="hidden">
                            <img src="" alt="" class="w-full h-36 object-cover rounded-xl">
                            <button type="button" onclick="clearGambar(event, 'editGambarInput', 'editDropzone', 'editGambarPreview')"
                                class="absolute top-2 right-2 w-7 h-7 bg-black/50 hover:bg-red-500 text-white rounded-lg flex items-center justify-center transition-all">
                                <i class='bx bx-x text-sm'></i>
                            </button>
                        </div>

                        <div id="editDropzoneContent" class="py-7 flex flex-col items-center gap-2">
                            <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center">
                                <i class='bx bx-image-add text-amber-400 text-xl'></i>
                            </div>
                            <p class="text-xs font-bold text-slate-500">Ganti gambar (opsional)</p>
                            <p class="text-[10px] text-slate-400">JPEG, PNG, WebP — Maks 5MB</p>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Urutan Tampil</label>
                    <input type="number" name="urutan" id="editUrutan" value="0" min="0"
                        class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all font-medium">
                </div>

                <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-100 self-end">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <div class="relative">
                            <input type="checkbox" name="is_active" id="editIsActive" value="1" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 rounded-full peer-checked:bg-blue-600 transition-colors"></div>
                            <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-all peer-checked:translate-x-5"></div>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-700">Tayangkan</p>
                            <p class="text-[10px] text-slate-400">Tampil di aplikasi siswa</p>
                        </div>
                    </label>
                </div>

            </div>

            <div class="px-6 pb-6 flex gap-3 flex-shrink-0">
                <button type="button" onclick="closeModal('modalEdit')"
                    class="flex-1 py-3 bg-slate-100 text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-200 transition-all active:scale-95">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 py-3 bg-amber-500 text-white text-sm font-bold rounded-xl shadow-lg shadow-amber-200 hover:bg-amber-600 transition-all active:scale-95 flex items-center justify-center gap-2">
                    <i class='bx bx-save text-lg'></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>


{{-- ================================================================
     MODAL HAPUS
================================================================ --}}
<div id="modalHapus" class="modal-overlay fixed inset-0 z-[9998] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md" onclick="closeModal('modalHapus')"></div>
    <div class="modal-card relative bg-white rounded-3xl shadow-2xl w-full max-w-sm z-10">
        <div class="p-8 text-center">
            <div class="relative w-20 h-20 mx-auto mb-5">
                <div class="absolute inset-0 bg-red-100 rounded-3xl animate-pulse"></div>
                <div class="relative w-20 h-20 rounded-3xl bg-red-50 border border-red-100 flex items-center justify-center">
                    <i class='bx bxs-trash text-red-500 text-3xl'></i>
                </div>
            </div>
            <h3 class="font-black text-slate-800 text-lg">Hapus Event?</h3>
            <p class="text-slate-500 text-sm mt-2 leading-relaxed">
                Event <strong id="deleteNama" class="text-slate-700 font-bold"></strong> akan dihapus permanen dan tidak lagi tampil di aplikasi siswa.
            </p>
            <div class="flex gap-3 mt-7">
                <button onclick="closeModal('modalHapus')"
                    class="flex-1 py-3 bg-slate-100 text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-200 transition-all active:scale-95">
                    Batal
                </button>
                <form id="formHapus" action="" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="w-full py-3 bg-red-500 text-white text-sm font-bold rounded-xl shadow-lg shadow-red-200 hover:bg-red-600 transition-all active:scale-95">
                        <i class='bx bx-trash mr-1'></i> Hapus Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>


{{-- ================================================================
     STYLES
================================================================ --}}
<style>
    @keyframes toastSlideIn {
        from { opacity:0; transform:translateX(20px) scale(.95); }
        to   { opacity:1; transform:translateX(0) scale(1); }
    }
    @keyframes modalIn {
        from { opacity:0; transform:scale(.94) translateY(12px); }
        to   { opacity:1; transform:scale(1) translateY(0); }
    }

    .modal-card  { animation: modalIn .35s cubic-bezier(.34,1.4,.64,1) forwards; }
    .modal-overlay.active { display:flex !important; }

    .ev-card.active-preview {
        border-color: #3B82F6 !important;
        box-shadow: 0 0 0 2px rgba(59,130,246,.15), 0 8px 32px rgba(59,130,246,.12) !important;
        transform: translateY(-1px) !important;
    }

    #previewPanel {
        transition: opacity .2s ease;
    }

    /* Drag over state */
    .dropzone-active {
        border-color: #2563EB !important;
        background-color: #EFF6FF !important;
    }
</style>


{{-- ================================================================
     JAVASCRIPT
================================================================ --}}
<script>
// ── Modal helpers ──────────────────────────────────────────────
function openModal(id) {
    const el = document.getElementById(id);
    el.classList.remove('hidden');
    el.classList.add('active');
    document.body.style.overflow = 'hidden';
}
function closeModal(id) {
    const el = document.getElementById(id);
    el.classList.add('hidden');
    el.classList.remove('active');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') ['modalTambah','modalEdit','modalHapus'].forEach(closeModal);
});

// ── Warna helpers ─────────────────────────────────────────────
function updateWarnaPreview(input, previewId) {
    const color = input.value;
    const wrap  = document.getElementById(previewId);
    if (!wrap) return;
    wrap.style.backgroundColor = color + '15';
    wrap.style.borderColor     = color + '40';
    const dot = wrap.querySelector('[id$="Dot"]') || wrap.querySelector('[class~="rounded-full"]');
    const hex = wrap.querySelector('[id$="Hex"]');
    if (dot) dot.style.backgroundColor = color;
    if (hex) { hex.style.color = color; hex.textContent = color.toUpperCase(); }
}
function setWarna(hex, inputId, previewId, dotId, hexId) {
    const input = document.getElementById(inputId);
    input.value = hex;
    const wrap = document.getElementById(previewId);
    const dot  = document.getElementById(dotId);
    const hexEl= document.getElementById(hexId);
    if (wrap) { wrap.style.backgroundColor = hex + '15'; wrap.style.borderColor = hex + '40'; }
    if (dot)  dot.style.backgroundColor = hex;
    if (hexEl){ hexEl.style.color = hex; hexEl.textContent = hex.toUpperCase(); }
}

// ── Gambar upload / drag-drop ─────────────────────────────────
function previewGambar(input, dropzoneId, previewId) {
    const file = input.files[0];
    if (!file) return;

    const preview  = document.getElementById(previewId);
    const content  = document.getElementById(dropzoneId.replace('Dropzone','') + 'DropzoneContent')
                  || document.getElementById(dropzoneId + 'Content');
    const img      = preview.querySelector('img');

    const reader = new FileReader();
    reader.onload = e => {
        img.src = e.target.result;
        preview.classList.remove('hidden');
        // hide placeholder content
        const placeholderContent = document.querySelector(`#${dropzoneId} > div:not([id])`);
        if (placeholderContent) placeholderContent.classList.add('hidden');
    };
    reader.readAsDataURL(file);
}

function clearGambar(event, inputId, dropzoneId, previewId) {
    event.stopPropagation();
    document.getElementById(inputId).value = '';
    const preview = document.getElementById(previewId);
    const img = preview.querySelector('img');
    img.src = '';
    preview.classList.add('hidden');
}

function handleDragOver(e, dropzoneId) {
    e.preventDefault();
    document.getElementById(dropzoneId).classList.add('dropzone-active');
}
function handleDragLeave(dropzoneId) {
    document.getElementById(dropzoneId).classList.remove('dropzone-active');
}
function handleDrop(e, inputId, dropzoneId, previewId) {
    e.preventDefault();
    document.getElementById(dropzoneId).classList.remove('dropzone-active');
    const file = e.dataTransfer.files[0];
    if (!file || !file.type.startsWith('image/')) return;
    const input = document.getElementById(inputId);
    const dt = new DataTransfer();
    dt.items.add(file);
    input.files = dt.files;
    previewGambar(input, dropzoneId, previewId);
}

// ── Edit modal populate ───────────────────────────────────────
function openEditModal(ev) {
    // Set form action to FE route (FE forward to API via PUT)
    document.getElementById('formEdit').action = `/admin/event/${ev.id}`;

    document.getElementById('editJudul').value         = ev.judul        || '';
    document.getElementById('editDeskripsi').value     = ev.deskripsi    || '';
    document.getElementById('editKategori').value      = ev.kategori     || 'Lainnya';
    document.getElementById('editTanggalMulai').value  = ev.tanggal_mulai  || '';
    document.getElementById('editTanggalSelesai').value= ev.tanggal_selesai || '';
    document.getElementById('editUrutan').value        = ev.urutan        ?? 0;
    document.getElementById('editIsActive').checked   = !!ev.is_active;

    // Warna
    const warna = ev.warna || '#2563EB';
    document.getElementById('editWarna').value = warna;
    setWarna(warna, 'editWarna', 'editWarnaPreview', 'editWarnaDot', 'editWarnaHex');

    // Existing image
    const existWrap = document.getElementById('editExistingImageWrap');
    const existImg  = document.getElementById('editExistingImage');
    if (ev.gambar_url) {
        existImg.src = ev.gambar_url;
        existWrap.classList.remove('hidden');
    } else {
        existWrap.classList.add('hidden');
    }

    // Reset new file picker
    document.getElementById('editGambarInput').value = '';
    document.getElementById('editGambarPreview').classList.add('hidden');

    openModal('modalEdit');
}

// ── Delete modal ──────────────────────────────────────────────
function openDeleteModal(id, nama) {
    document.getElementById('deleteNama').textContent = nama;
    document.getElementById('formHapus').action = `/admin/event/${id}`;
    openModal('modalHapus');
}

// ── Preview panel ─────────────────────────────────────────────
function openPreview(ev) {
    // Highlight card
    document.querySelectorAll('.ev-card').forEach(c => c.classList.remove('active-preview'));
    const card = document.querySelector(`.ev-card[data-id="${ev.id}"]`);
    if (card) card.classList.add('active-preview');

    // Show panel
    const panel = document.getElementById('previewPanel');
    panel.classList.remove('hidden');
    panel.classList.add('flex');

    const warna    = ev.warna || '#2563EB';
    const isActive = !!ev.is_active;

    // Image
    const img   = document.getElementById('mockupImage');
    const ph    = document.getElementById('mockupImagePlaceholder');
    if (ev.gambar_url) {
        img.src = ev.gambar_url;
        img.classList.remove('hidden');
        ph.classList.add('hidden');
    } else {
        img.classList.add('hidden');
        ph.classList.remove('hidden');
    }

    // Kategori badge
    const badge = document.getElementById('mockupKategoriBadge');
    badge.textContent = ev.kategori || '—';
    badge.style.backgroundColor = warna;

    // Accent
    document.getElementById('mockupAccent').style.backgroundColor = warna;

    // Texts
    document.getElementById('mockupJudul').textContent    = ev.judul     || '—';
    document.getElementById('mockupDeskripsi').textContent= ev.deskripsi || '—';

    // Date
    const tgl = ev.tanggal_mulai
        ? formatTanggal(ev.tanggal_mulai) + (ev.tanggal_selesai && ev.tanggal_selesai !== ev.tanggal_mulai ? ' → ' + formatTanggal(ev.tanggal_selesai) : '')
        : '—';
    document.getElementById('mockupTanggal').textContent = tgl;

    // Status
    const statusEl = document.getElementById('mockupStatusBadge');
    statusEl.textContent = isActive ? 'Aktif' : 'Tersembunyi';
    statusEl.className   = isActive
        ? 'text-[8px] font-black text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded-md'
        : 'text-[8px] font-black text-slate-400 bg-slate-100 px-1.5 py-0.5 rounded-md';

    // Meta
    document.getElementById('previewAdmin').textContent   = ev.admin?.name || 'Admin';
    document.getElementById('previewKategori').textContent= ev.kategori    || '—';
    document.getElementById('previewMulai').textContent   = ev.tanggal_mulai   ? formatTanggal(ev.tanggal_mulai)   : '—';
    document.getElementById('previewSelesai').textContent = ev.tanggal_selesai ? formatTanggal(ev.tanggal_selesai) : '—';
    document.getElementById('previewUrutan').textContent  = ev.urutan ?? 0;

    // Quick actions
    const actionsEl = document.getElementById('previewActions');
    actionsEl.innerHTML = `
        <button onclick="openEditModal(${JSON.stringify(ev).replace(/"/g,'&quot;')})"
            class="flex-1 py-2 bg-amber-50 text-amber-600 text-xs font-bold rounded-xl border border-amber-200 hover:bg-amber-100 transition-all flex items-center justify-center gap-1.5">
            <i class='bx bx-edit-alt text-sm'></i> Edit
        </button>
        <button onclick="openDeleteModal(${ev.id}, '${(ev.judul || '').replace(/'/g,'\\\'')}')"
            class="flex-1 py-2 bg-red-50 text-red-500 text-xs font-bold rounded-xl border border-red-200 hover:bg-red-100 transition-all flex items-center justify-center gap-1.5">
            <i class='bx bx-trash text-sm'></i> Hapus
        </button>
    `;
}

function closePreview() {
    document.getElementById('previewPanel').classList.add('hidden');
    document.getElementById('previewPanel').classList.remove('flex');
    document.querySelectorAll('.ev-card').forEach(c => c.classList.remove('active-preview'));
}

function formatTanggal(dateStr) {
    if (!dateStr) return '—';
    const d = new Date(dateStr + 'T00:00:00');
    return d.toLocaleDateString('id-ID', { day:'numeric', month:'short', year:'numeric' });
}

// ── Filter ────────────────────────────────────────────────────
function applyFilter() {
    const search   = document.getElementById('searchInput').value.toLowerCase().trim();
    const status   = document.getElementById('filterStatus').value;
    const kategori = document.getElementById('filterKategori').value.toLowerCase();
    const cards    = document.querySelectorAll('.ev-card');
    let vis = 0;

    cards.forEach(c => {
        const s  = !status   || c.dataset.active   === status;
        const k  = !kategori || c.dataset.kategori.toLowerCase() === kategori;
        const t  = !search   || c.dataset.search.includes(search);
        const show = s && k && t;
        c.parentElement.style.display = show ? '' : 'none';
        if (show) vis++;
    });

    document.getElementById('noResult').classList.toggle('hidden', vis > 0 || cards.length === 0);
    document.getElementById('rowCount').textContent = vis;
}
function resetFilter() {
    ['searchInput','filterStatus','filterKategori'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.value = '';
    });
    applyFilter();
}
document.getElementById('searchInput').addEventListener('input', applyFilter);
document.getElementById('filterStatus').addEventListener('change', applyFilter);
document.getElementById('filterKategori').addEventListener('change', applyFilter);

// ── Toast auto dismiss ────────────────────────────────────────
setTimeout(() => {
    ['toast-ok','toast-err'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.style.transition = 'opacity .4s, transform .4s';
            el.style.opacity    = '0';
            el.style.transform  = 'translateX(20px)';
            setTimeout(() => el.remove(), 400);
        }
    });
}, 5000);
</script>

@endsection
