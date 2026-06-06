@extends('layouts.admin')

@section('title', 'Pengaturan Waktu Absensi')

@section('content')

{{-- ============================================================
     TOAST NOTIFICATION
     ============================================================ --}}
@if(session('success'))
<div id="toast-ok" class="fixed top-5 right-5 z-[9999] flex items-center gap-3 bg-white border border-emerald-200 text-emerald-800 px-5 py-3.5 rounded-2xl shadow-xl text-sm font-semibold" style="max-width:380px;animation:toastIn .35s ease forwards;">
    <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
        <i class='bx bx-check text-emerald-600 text-lg'></i>
    </div>
    <span>{{ session('success') }}</span>
    <button onclick="this.parentElement.remove()" class="ml-auto text-emerald-400 hover:text-emerald-700 transition-colors">
        <i class='bx bx-x text-lg'></i>
    </button>
</div>
@endif

@if(session('error'))
<div id="toast-err" class="fixed top-5 right-5 z-[9999] flex items-center gap-3 bg-white border border-red-200 text-red-800 px-5 py-3.5 rounded-2xl shadow-xl text-sm font-semibold" style="max-width:380px;animation:toastIn .35s ease forwards;">
    <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
        <i class='bx bx-x text-red-600 text-lg'></i>
    </div>
    <span>{{ session('error') }}</span>
    <button onclick="this.parentElement.remove()" class="ml-auto text-red-400 hover:text-red-700 transition-colors">
        <i class='bx bx-x text-lg'></i>
    </button>
</div>
@endif

@if($error ?? null)
<div class="mb-5 flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 px-5 py-3.5 rounded-2xl text-sm font-medium" data-aos="fade-down">
    <i class='bx bxs-error-circle text-xl flex-shrink-0'></i>
    <span>Gagal memuat data: {{ $error }}</span>
</div>
@endif

{{-- ============================================================
     PAGE HEADER
     ============================================================ --}}
<div class="mb-6" data-aos="fade-down">
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Pengaturan Waktu Absensi</h1>
            <p class="text-slate-500 text-sm mt-0.5">Atur jam masuk, jam pulang, dan batas terlambat per hari</p>
        </div>
        <button onclick="openModal('modalTambah')"
            class="flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-200 hover:bg-blue-700 transition-all active:scale-95">
            <i class='bx bx-plus text-lg'></i>
            Tambah Jadwal
        </button>
    </div>
</div>

{{-- ============================================================
     STAT CARDS
     ============================================================ --}}
@php
    $waktus       = $waktus ?? collect();
    $totalAktif   = $waktus->where('is_active', true)->count();
    $totalNonaktif = $waktus->where('is_active', false)->count();

    $hariOrder = ['senin','selasa','rabu','kamis','jumat','sabtu','minggu'];
    $waktus = $waktus->sortBy(fn($w) => array_search($w['hari'], $hariOrder))->values();
@endphp

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm" data-aos="fade-up" data-aos-delay="0">
        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center mb-3">
            <i class='bx bxs-time text-blue-600 text-xl'></i>
        </div>
        <p class="text-2xl font-bold text-slate-800">{{ $waktus->count() }}</p>
        <p class="text-xs text-slate-500 font-medium mt-0.5">Total Jadwal</p>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm" data-aos="fade-up" data-aos-delay="50">
        <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center mb-3">
            <i class='bx bxs-check-shield text-emerald-600 text-xl'></i>
        </div>
        <p class="text-2xl font-bold text-slate-800">{{ $totalAktif }}</p>
        <p class="text-xs text-slate-500 font-medium mt-0.5">Aktif</p>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm" data-aos="fade-up" data-aos-delay="100">
        <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center mb-3">
            <i class='bx bxs-shield-x text-slate-500 text-xl'></i>
        </div>
        <p class="text-2xl font-bold text-slate-800">{{ $totalNonaktif }}</p>
        <p class="text-xs text-slate-500 font-medium mt-0.5">Nonaktif</p>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm" data-aos="fade-up" data-aos-delay="150">
        <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center mb-3">
            <i class='bx bxs-calendar-week text-amber-600 text-xl'></i>
        </div>
        <p class="text-2xl font-bold text-slate-800">{{ 7 - $waktus->count() < 0 ? 0 : 7 - $waktus->count() }}</p>
        <p class="text-xs text-slate-500 font-medium mt-0.5">Hari Belum Diatur</p>
    </div>
</div>

{{-- ============================================================
     GRID HARI — VISUAL TIMELINE
     ============================================================ --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 mb-6">
    @php
        $hariMeta = [
            'senin'   => ['icon' => 'bxs-sun',           'color' => 'from-blue-500 to-indigo-600',    'light' => 'bg-blue-50 text-blue-700 border-blue-200'],
            'selasa'  => ['icon' => 'bxs-briefcase',     'color' => 'from-indigo-500 to-purple-600',  'light' => 'bg-indigo-50 text-indigo-700 border-indigo-200'],
            'rabu'    => ['icon' => 'bxs-book-open',     'color' => 'from-violet-500 to-purple-600',  'light' => 'bg-violet-50 text-violet-700 border-violet-200'],
            'kamis'   => ['icon' => 'bxs-graduation',    'color' => 'from-blue-600 to-cyan-500',      'light' => 'bg-cyan-50 text-cyan-700 border-cyan-200'],
            'jumat'   => ['icon' => 'bxs-star',          'color' => 'from-emerald-500 to-teal-600',   'light' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
            'sabtu'   => ['icon' => 'bxs-coffee',        'color' => 'from-amber-500 to-orange-500',   'light' => 'bg-amber-50 text-amber-700 border-amber-200'],
            'minggu'  => ['icon' => 'bxs-home-heart',    'color' => 'from-slate-500 to-slate-700',    'light' => 'bg-slate-50 text-slate-600 border-slate-200'],
        ];
    @endphp

    @forelse($waktus as $waktu)
    @php
        $meta = $hariMeta[$waktu['hari']] ?? $hariMeta['senin'];

        // Hitung persentase jam (06:00 — 18:00 = 720 menit baseline)
        $baseStart = 6 * 60;
        $baseEnd   = 18 * 60;
        $baseRange = $baseEnd - $baseStart;

        $toMin = function($t) { [$h,$m] = array_pad(explode(':', substr($t,0,5)), 2, 0); return intval($h)*60 + intval($m); };

        $masukMin   = $toMin($waktu['jam_masuk_mulai']);
        $pulangMin  = $toMin($waktu['jam_pulang_mulai']);
        $terlambatMin = $toMin($waktu['batas_terlambat']);

        $masukPct   = max(0, min(100, round(($masukMin - $baseStart) / $baseRange * 100)));
        $pulangPct  = max(0, min(100, round(($pulangMin - $baseStart) / $baseRange * 100)));
        $terlambatPct = max(0, min(100, round(($terlambatMin - $baseStart) / $baseRange * 100)));

        $durasi = $pulangMin - $masukMin;
        $durasiStr = floor($durasi/60) . 'j ' . ($durasi%60) . 'm';
    @endphp

    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden transition-all duration-200 hover:shadow-md hover:border-blue-200 {{ !$waktu['is_active'] ? 'opacity-60' : '' }}"
        data-aos="fade-up" data-id="{{ $waktu['id'] }}">

        {{-- Header card --}}
        <div class="p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br {{ $meta['color'] }} flex items-center justify-center shadow-lg flex-shrink-0">
                <i class='bx {{ $meta['icon'] }} text-white text-xl'></i>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2">
                    <h3 class="font-bold text-slate-800 capitalize text-base">{{ $waktu['nama'] }}</h3>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-md border capitalize {{ $meta['light'] }}">{{ $waktu['hari'] }}</span>
                </div>
                <p class="text-xs text-slate-400 mt-0.5">Durasi aktif: <strong class="text-slate-600">{{ $durasiStr }}</strong></p>
            </div>
            {{-- Status toggle --}}
            <form action="{{ route('admin.waktu.toggle', $waktu['id']) }}" method="POST" class="flex-shrink-0">
                @csrf
                <button type="submit" title="Toggle status"
                    class="relative w-11 h-6 rounded-full transition-all duration-300 focus:outline-none {{ $waktu['is_active'] ? 'bg-blue-600 shadow-md shadow-blue-200' : 'bg-slate-200' }}">
                    <span class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-all duration-300 {{ $waktu['is_active'] ? 'translate-x-5' : 'translate-x-0' }}"></span>
                </button>
            </form>
        </div>

        {{-- Timeline bar --}}
        <div class="px-5 pb-2">
            <div class="relative h-3 bg-slate-100 rounded-full overflow-hidden">
                {{-- Aktif band (masuk → pulang) --}}
                <div class="absolute top-0 h-full rounded-full bg-gradient-to-r {{ $meta['color'] }} opacity-25"
                    style="left:{{ $masukPct }}%; width:{{ $pulangPct - $masukPct }}%;"></div>
                {{-- Aktif band solid --}}
                <div class="absolute top-0 h-full rounded-full bg-gradient-to-r {{ $meta['color'] }}"
                    style="left:{{ $masukPct }}%; width:{{ $pulangPct - $masukPct }}%; opacity:.7"></div>
                {{-- Marker masuk --}}
                <div class="absolute top-0 h-full w-0.5 bg-blue-700"
                    style="left:{{ $masukPct }}%;"></div>
                {{-- Marker terlambat --}}
                <div class="absolute top-0 h-full w-0.5 bg-amber-500"
                    style="left:{{ $terlambatPct }}%;"></div>
            </div>
            <div class="flex justify-between text-[9px] text-slate-400 mt-1 font-medium">
                <span>06:00</span>
                <span>12:00</span>
                <span>18:00</span>
            </div>
        </div>

        {{-- Info jam --}}
        <div class="grid grid-cols-3 gap-0 border-t border-slate-50 mt-1">
            <div class="px-4 py-3 text-center border-r border-slate-50">
                <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wide mb-0.5">Masuk</p>
                <p class="text-sm font-bold text-slate-800">{{ substr($waktu['jam_masuk_mulai'], 0, 5) }}</p>
            </div>
            <div class="px-4 py-3 text-center border-r border-slate-50">
                <p class="text-[10px] text-amber-500 font-medium uppercase tracking-wide mb-0.5">Terlambat</p>
                <p class="text-sm font-bold text-amber-600">{{ substr($waktu['batas_terlambat'], 0, 5) }}</p>
            </div>
            <div class="px-4 py-3 text-center">
                <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wide mb-0.5">Pulang</p>
                <p class="text-sm font-bold text-slate-800">{{ substr($waktu['jam_pulang_mulai'], 0, 5) }}</p>
            </div>
        </div>

        {{-- Aksi --}}
        <div class="px-5 py-3 border-t border-slate-50 flex items-center gap-2">
            <button onclick="openEditModal({{ json_encode($waktu) }})"
                class="flex-1 flex items-center justify-center gap-1.5 py-2 bg-slate-50 hover:bg-blue-50 border border-slate-200 hover:border-blue-200 text-slate-600 hover:text-blue-600 text-xs font-bold rounded-xl transition-all active:scale-95">
                <i class='bx bx-edit-alt'></i> Edit
            </button>
            <button onclick="openDeleteModal({{ $waktu['id'] }}, '{{ addslashes($waktu['nama']) }}')"
                class="flex-1 flex items-center justify-center gap-1.5 py-2 bg-slate-50 hover:bg-red-50 border border-slate-200 hover:border-red-200 text-slate-600 hover:text-red-500 text-xs font-bold rounded-xl transition-all active:scale-95">
                <i class='bx bx-trash'></i> Hapus
            </button>
        </div>
    </div>
    @empty
    {{-- Empty state --}}
    <div class="col-span-full bg-white rounded-3xl border border-dashed border-slate-200 py-20 text-center" data-aos="fade-up">
        <div class="flex flex-col items-center gap-3">
            <div class="w-16 h-16 rounded-2xl bg-slate-50 flex items-center justify-center">
                <i class='bx bx-time-five text-3xl text-slate-300'></i>
            </div>
            <p class="text-slate-400 font-medium text-sm">Belum ada jadwal waktu</p>
            <button onclick="openModal('modalTambah')"
                class="mt-1 px-4 py-2 bg-blue-600 text-white text-xs font-bold rounded-xl hover:bg-blue-700 transition-all active:scale-95">
                + Tambah Jadwal Pertama
            </button>
        </div>
    </div>
    @endforelse
</div>

{{-- ============================================================
     TABEL RINGKASAN (View Tabel)
     ============================================================ --}}
<div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden" data-aos="fade-up">
    <div class="px-6 py-4 border-b border-slate-50 flex items-center justify-between">
        <h3 class="font-bold text-slate-800 flex items-center gap-2">
            <i class='bx bx-table text-slate-400'></i>
            Ringkasan Semua Jadwal
        </h3>
        <span class="text-xs text-slate-400">{{ $waktus->count() }} jadwal terdaftar</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50/60 border-b border-slate-100 text-slate-500 font-bold uppercase text-[10px] tracking-wider">
                <tr>
                    <th class="px-6 py-4">Hari</th>
                    <th class="px-6 py-4">Nama</th>
                    <th class="px-6 py-4 text-center">Jam Masuk</th>
                    <th class="px-6 py-4 text-center">Batas Terlambat</th>
                    <th class="px-6 py-4 text-center">Jam Pulang</th>
                    <th class="px-6 py-4 text-center">Durasi</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($waktus as $w)
                @php
                    $m2 = $hariMeta[$w['hari']] ?? $hariMeta['senin'];
                    $dur2 = $toMin($w['jam_pulang_mulai']) - $toMin($w['jam_masuk_mulai']);
                    $dur2Str = floor($dur2/60) . 'j ' . ($dur2%60) . 'm';
                @endphp
                <tr class="hover:bg-slate-50/50 transition-colors {{ !$w['is_active'] ? 'opacity-60' : '' }}">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl bg-gradient-to-br {{ $m2['color'] }} flex items-center justify-center flex-shrink-0">
                                <i class='bx {{ $m2['icon'] }} text-white text-sm'></i>
                            </div>
                            <span class="font-semibold text-slate-700 capitalize">{{ $w['hari'] }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-slate-600 font-medium">{{ $w['nama'] }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 text-blue-700 border border-blue-100 text-xs font-bold rounded-lg">
                            <i class='bx bx-log-in-circle text-sm'></i>
                            {{ substr($w['jam_masuk_mulai'], 0, 5) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-100 text-xs font-bold rounded-lg">
                            <i class='bx bx-alarm text-sm'></i>
                            {{ substr($w['batas_terlambat'], 0, 5) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-indigo-50 text-indigo-700 border border-indigo-100 text-xs font-bold rounded-lg">
                            <i class='bx bx-log-out-circle text-sm'></i>
                            {{ substr($w['jam_pulang_mulai'], 0, 5) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="text-xs font-bold text-slate-600">{{ $dur2Str }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <form action="{{ route('admin.waktu.toggle', $w['id']) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[10px] font-bold transition-all {{ $w['is_active'] ? 'bg-emerald-50 text-emerald-600 border border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-500 border border-slate-200 hover:bg-slate-200' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $w['is_active'] ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                {{ $w['is_active'] ? 'Aktif' : 'Nonaktif' }}
                            </button>
                        </form>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-1.5">
                            <button onclick="openEditModal({{ json_encode($w) }})"
                                class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="Edit">
                                <i class='bx bx-edit-alt text-base'></i>
                            </button>
                            <button onclick="openDeleteModal({{ $w['id'] }}, '{{ addslashes($w['nama']) }}')"
                                class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all" title="Hapus">
                                <i class='bx bx-trash text-base'></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-14 text-center text-slate-400 text-sm">Belum ada jadwal waktu</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Info bottom --}}
    <div class="p-5 bg-blue-50/40 border-t border-slate-100">
        <div class="flex items-start gap-3">
            <div class="w-9 h-9 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600 flex-shrink-0 mt-0.5">
                <i class='bx bx-info-circle text-lg'></i>
            </div>
            <div>
                <p class="text-sm font-bold text-blue-900">Cara Kerja Waktu Absensi</p>
                <p class="text-xs text-blue-700/70 mt-0.5 leading-relaxed">
                    Sistem akan menggunakan jadwal yang <strong>aktif</strong> sesuai hari berjalan. Jika siswa absensi masuk setelah <strong>Batas Terlambat</strong>, sistem otomatis mencatat status <strong>Terlambat</strong>. Jadwal yang <strong>Nonaktif</strong> tidak akan digunakan meski terdaftar.
                </p>
            </div>
        </div>
    </div>
</div>


{{-- ============================================================
     MODAL TAMBAH WAKTU
     ============================================================ --}}
<div id="modalTambah" class="modal-overlay fixed inset-0 z-[9998] hidden items-center justify-center p-4">
    <div class="modal-backdrop absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('modalTambah')"></div>
    <div class="modal-card relative bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden z-10">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
                <i class='bx bx-plus-circle text-blue-600 text-lg'></i>
            </div>
            <h3 class="font-bold text-slate-800">Tambah Jadwal Waktu</h3>
            <button onclick="closeModal('modalTambah')" class="ml-auto w-8 h-8 rounded-xl flex items-center justify-center text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-all">
                <i class='bx bx-x text-xl'></i>
            </button>
        </div>
        <form action="{{ route('admin.waktu.store') }}" method="POST" class="p-6">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5">Nama Jadwal <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" placeholder="Contoh: Senin Reguler" required
                        class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5">Hari <span class="text-red-500">*</span></label>
                    <select name="hari" required
                        class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                        <option value="">— Pilih Hari —</option>
                        <option value="senin">Senin</option>
                        <option value="selasa">Selasa</option>
                        <option value="rabu">Rabu</option>
                        <option value="kamis">Kamis</option>
                        <option value="jumat">Jumat</option>
                        <option value="sabtu">Sabtu</option>
                        <option value="minggu">Minggu</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5">Jam Masuk Mulai <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <i class='bx bx-log-in-circle absolute left-3 top-1/2 -translate-y-1/2 text-blue-500 text-base'></i>
                        <input type="time" name="jam_masuk_mulai" required
                            class="w-full pl-9 bg-slate-50 border border-slate-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5">Batas Terlambat <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <i class='bx bx-alarm absolute left-3 top-1/2 -translate-y-1/2 text-amber-500 text-base'></i>
                        <input type="time" name="batas_terlambat" required
                            class="w-full pl-9 bg-slate-50 border border-slate-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all">
                    </div>
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5">Jam Pulang Mulai <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <i class='bx bx-log-out-circle absolute left-3 top-1/2 -translate-y-1/2 text-indigo-500 text-base'></i>
                        <input type="time" name="jam_pulang_mulai" required
                            class="w-full pl-9 bg-slate-50 border border-slate-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                    </div>
                </div>
            </div>

            {{-- Preview mini --}}
            <div id="previewTambah" class="hidden mt-4 p-4 bg-blue-50 rounded-2xl border border-blue-100">
                <p class="text-xs font-bold text-blue-700 mb-2 flex items-center gap-1.5">
                    <i class='bx bx-show'></i> Preview Timeline
                </p>
                <div class="relative h-3 bg-blue-100 rounded-full overflow-hidden">
                    <div id="previewBar" class="absolute top-0 h-full rounded-full bg-gradient-to-r from-blue-500 to-indigo-500" style="left:0%;width:0%"></div>
                    <div id="previewMasukLine" class="absolute top-0 h-full w-0.5 bg-blue-800" style="left:0%"></div>
                    <div id="previewTerlambatLine" class="absolute top-0 h-full w-0.5 bg-amber-500" style="left:0%"></div>
                </div>
                <div class="flex justify-between text-[9px] text-blue-500 mt-1">
                    <span>06:00</span><span>12:00</span><span>18:00</span>
                </div>
                <div class="flex flex-wrap items-center gap-3 mt-2 text-[10px] text-blue-700 font-medium">
                    <span id="prevMasuk" class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-blue-700 inline-block"></span>Masuk —</span>
                    <span id="prevTerlambat" class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-amber-500 inline-block"></span>Terlambat —</span>
                    <span id="prevPulang" class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-indigo-500 inline-block"></span>Pulang —</span>
                    <span id="prevDurasi" class="ml-auto font-bold text-blue-600">—</span>
                </div>
            </div>

            <div class="flex gap-3 mt-5">
                <button type="button" onclick="closeModal('modalTambah')"
                    class="flex-1 py-3 bg-slate-100 text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-200 transition-all active:scale-95">Batal</button>
                <button type="submit"
                    class="flex-1 py-3 bg-blue-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-200 hover:bg-blue-700 transition-all active:scale-95">
                    <i class='bx bx-save mr-1'></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>


{{-- ============================================================
     MODAL EDIT WAKTU
     ============================================================ --}}
<div id="modalEdit" class="modal-overlay fixed inset-0 z-[9998] hidden items-center justify-center p-4">
    <div class="modal-backdrop absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('modalEdit')"></div>
    <div class="modal-card relative bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden z-10">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center">
                <i class='bx bx-edit text-amber-600 text-lg'></i>
            </div>
            <h3 class="font-bold text-slate-800">Edit Jadwal Waktu</h3>
            <button onclick="closeModal('modalEdit')" class="ml-auto w-8 h-8 rounded-xl flex items-center justify-center text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-all">
                <i class='bx bx-x text-xl'></i>
            </button>
        </div>
        <form id="formEdit" action="" method="POST" class="p-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5">Nama Jadwal <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" id="editNama" required
                        class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all">
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5">Hari <span class="text-red-500">*</span></label>
                    <select name="hari" id="editHari" required
                        class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all">
                        <option value="senin">Senin</option>
                        <option value="selasa">Selasa</option>
                        <option value="rabu">Rabu</option>
                        <option value="kamis">Kamis</option>
                        <option value="jumat">Jumat</option>
                        <option value="sabtu">Sabtu</option>
                        <option value="minggu">Minggu</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5">Jam Masuk Mulai <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <i class='bx bx-log-in-circle absolute left-3 top-1/2 -translate-y-1/2 text-blue-500 text-base'></i>
                        <input type="time" name="jam_masuk_mulai" id="editMasuk" required
                            class="w-full pl-9 bg-slate-50 border border-slate-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5">Batas Terlambat <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <i class='bx bx-alarm absolute left-3 top-1/2 -translate-y-1/2 text-amber-500 text-base'></i>
                        <input type="time" name="batas_terlambat" id="editTerlambat" required
                            class="w-full pl-9 bg-slate-50 border border-slate-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all">
                    </div>
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5">Jam Pulang Mulai <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <i class='bx bx-log-out-circle absolute left-3 top-1/2 -translate-y-1/2 text-indigo-500 text-base'></i>
                        <input type="time" name="jam_pulang_mulai" id="editPulang" required
                            class="w-full pl-9 bg-slate-50 border border-slate-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                    </div>
                </div>
            </div>
            <div class="flex gap-3 mt-5">
                <button type="button" onclick="closeModal('modalEdit')"
                    class="flex-1 py-3 bg-slate-100 text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-200 transition-all active:scale-95">Batal</button>
                <button type="submit"
                    class="flex-1 py-3 bg-amber-500 text-white text-sm font-bold rounded-xl shadow-lg shadow-amber-200 hover:bg-amber-600 transition-all active:scale-95">
                    <i class='bx bx-save mr-1'></i> Update
                </button>
            </div>
        </form>
    </div>
</div>


{{-- ============================================================
     MODAL HAPUS KONFIRMASI
     ============================================================ --}}
<div id="modalHapus" class="modal-overlay fixed inset-0 z-[9998] hidden items-center justify-center p-4">
    <div class="modal-backdrop absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('modalHapus')"></div>
    <div class="modal-card relative bg-white rounded-3xl shadow-2xl w-full max-w-sm z-10">
        <div class="p-7 text-center">
            <div class="w-16 h-16 rounded-2xl bg-red-50 flex items-center justify-center mx-auto mb-4">
                <i class='bx bxs-trash text-red-500 text-3xl'></i>
            </div>
            <h3 class="font-bold text-slate-800 text-lg">Hapus Jadwal?</h3>
            <p class="text-slate-500 text-sm mt-2 mb-6">
                Jadwal <strong id="deleteNama" class="text-slate-700"></strong> akan dihapus permanen dan tidak dapat dikembalikan.
            </p>
            <div class="flex gap-3">
                <button onclick="closeModal('modalHapus')"
                    class="flex-1 py-3 bg-slate-100 text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-200 transition-all active:scale-95">Batal</button>
                <form id="formHapus" action="" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="w-full py-3 bg-red-500 text-white text-sm font-bold rounded-xl shadow-lg shadow-red-200 hover:bg-red-600 transition-all active:scale-95">
                        <i class='bx bx-trash mr-1'></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>


<style>
    @keyframes toastIn {
        from { opacity:0; transform:translateX(20px); }
        to   { opacity:1; transform:translateX(0); }
    }
    @keyframes modalIn {
        from { opacity:0; transform:scale(.95) translateY(8px); }
        to   { opacity:1; transform:scale(1) translateY(0); }
    }
    .modal-card { animation: modalIn .3s cubic-bezier(.34,1.4,.64,1) forwards; }
    .modal-overlay.active { display:flex !important; }
</style>

<script>
// ============================================================
// MODAL HELPERS
// ============================================================
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
    if (e.key === 'Escape') {
        ['modalTambah','modalEdit','modalHapus'].forEach(closeModal);
    }
});

// ============================================================
// EDIT MODAL — populate
// ============================================================
function openEditModal(w) {
    document.getElementById('editNama').value      = w.nama || '';
    document.getElementById('editHari').value      = w.hari || 'senin';
    document.getElementById('editMasuk').value     = (w.jam_masuk_mulai  || '').substring(0, 5);
    document.getElementById('editTerlambat').value = (w.batas_terlambat  || '').substring(0, 5);
    document.getElementById('editPulang').value    = (w.jam_pulang_mulai || '').substring(0, 5);
    document.getElementById('formEdit').action     = `/admin/waktu/${w.id}`;
    openModal('modalEdit');
}

// ============================================================
// HAPUS MODAL
// ============================================================
function openDeleteModal(id, nama) {
    document.getElementById('deleteNama').textContent = nama;
    document.getElementById('formHapus').action       = `/admin/waktu/${id}`;
    openModal('modalHapus');
}

// ============================================================
// PREVIEW TIMELINE (form tambah)
// ============================================================
(function() {
    const BASE_START = 6 * 60;
    const BASE_RANGE = (18 - 6) * 60;

    function toMin(val) {
        if (!val) return null;
        const [h, m] = val.split(':').map(Number);
        return h * 60 + (m || 0);
    }
    function toPct(min) {
        return Math.max(0, Math.min(100, Math.round((min - BASE_START) / BASE_RANGE * 100)));
    }
    function fmt(val) {
        return val ? val.substring(0,5) : '—';
    }

    function updatePreview() {
        const masuk     = document.querySelector('[name="jam_masuk_mulai"]')?.value;
        const pulang    = document.querySelector('[name="jam_pulang_mulai"]')?.value;
        const terlambat = document.querySelector('[name="batas_terlambat"]')?.value;

        const mMin = toMin(masuk);
        const pMin = toMin(pulang);
        const tMin = toMin(terlambat);

        const preview = document.getElementById('previewTambah');

        if (!mMin || !pMin) { preview.classList.add('hidden'); return; }
        preview.classList.remove('hidden');

        const mPct = toPct(mMin);
        const pPct = toPct(pMin);
        const tPct = tMin ? toPct(tMin) : null;

        document.getElementById('previewBar').style.left  = mPct + '%';
        document.getElementById('previewBar').style.width = Math.max(0, pPct - mPct) + '%';
        document.getElementById('previewMasukLine').style.left = mPct + '%';
        if (tPct !== null) {
            document.getElementById('previewTerlambatLine').style.left = tPct + '%';
        }

        const dur = pMin - mMin;
        document.getElementById('prevMasuk').innerHTML     = `<span class="w-2 h-2 rounded-full bg-blue-700 inline-block"></span>Masuk <strong>${fmt(masuk)}</strong>`;
        document.getElementById('prevTerlambat').innerHTML = `<span class="w-2 h-2 rounded-full bg-amber-500 inline-block"></span>Terlambat <strong>${fmt(terlambat)}</strong>`;
        document.getElementById('prevPulang').innerHTML    = `<span class="w-2 h-2 rounded-full bg-indigo-500 inline-block"></span>Pulang <strong>${fmt(pulang)}</strong>`;
        document.getElementById('prevDurasi').textContent  = dur > 0 ? `${Math.floor(dur/60)}j ${dur%60}m` : '—';
    }

    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('modalTambah');
        if (!form) return;
        form.querySelectorAll('input[type="time"]').forEach(el => {
            el.addEventListener('change', updatePreview);
            el.addEventListener('input',  updatePreview);
        });
    });
})();

// ============================================================
// TOAST AUTO DISMISS
// ============================================================
setTimeout(() => {
    ['toast-ok','toast-err'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.style.transition = 'opacity .4s ease';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 400);
        }
    });
}, 5000);
</script>

@endsection
