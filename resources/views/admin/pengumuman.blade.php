@extends('layouts.admin')

@section('title', 'Manajemen Pengumuman')

@section('content')

{{-- ============================================================
     TOAST
     ============================================================ --}}
@if(session('success'))
<div id="toast-ok" class="fixed top-5 right-5 z-[9999] flex items-center gap-3 bg-white border border-emerald-200 text-emerald-800 px-5 py-3.5 rounded-2xl shadow-xl text-sm font-semibold" style="max-width:380px;animation:toastIn .35s ease forwards;">
    <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0"><i class='bx bx-check text-emerald-600 text-lg'></i></div>
    <span>{{ session('success') }}</span>
    <button onclick="this.parentElement.remove()" class="ml-auto text-emerald-400 hover:text-emerald-700"><i class='bx bx-x text-lg'></i></button>
</div>
@endif
@if(session('error'))
<div id="toast-err" class="fixed top-5 right-5 z-[9999] flex items-center gap-3 bg-white border border-red-200 text-red-800 px-5 py-3.5 rounded-2xl shadow-xl text-sm font-semibold" style="max-width:380px;animation:toastIn .35s ease forwards;">
    <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0"><i class='bx bx-x text-red-600 text-lg'></i></div>
    <span>{{ session('error') }}</span>
    <button onclick="this.parentElement.remove()" class="ml-auto text-red-400 hover:text-red-700"><i class='bx bx-x text-lg'></i></button>
</div>
@endif
@if($error ?? null)
<div class="mb-5 flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 px-5 py-3.5 rounded-2xl text-sm font-medium" data-aos="fade-down">
    <i class='bx bxs-error-circle text-xl flex-shrink-0'></i>
    <span>Gagal memuat data: {{ $error }}</span>
</div>
@endif

{{-- ============================================================
     HEADER
     ============================================================ --}}
@php
    $pengumumans  = $pengumumans ?? collect();
    $totalAktif   = $pengumumans->where('is_active', true)->count();
    $totalNonaktif = $pengumumans->where('is_active', false)->count();
    $totalPinned  = $pengumumans->where('is_pinned', true)->count();

    // Mapping icon enum → bx class
    $iconMap = [
        'fileText'          => 'bxs-file-doc',
        'clipboard'         => 'bxs-clipboard',
        'alertCircle'       => 'bxs-error-circle',
        'star'              => 'bxs-star',
        'calendar'          => 'bxs-calendar',
        'bell'              => 'bxs-bell',
        'book'              => 'bxs-book',
        'award'             => 'bxs-award',
        'checkCircle'       => 'bxs-check-circle',
        'info'              => 'bxs-info-circle',
        'bx_wallet'         => 'bxs-wallet',
        'bx_calendar_event' => 'bxs-calendar-event',
        'bx_news'           => 'bxs-news',
        'bx_book'           => 'bxs-book-open',
        'bx_bell'           => 'bxs-bell-ring',
    ];
    $iconLabels = [
        'fileText'          => 'Dokumen',
        'clipboard'         => 'Clipboard',
        'alertCircle'       => 'Peringatan',
        'star'              => 'Bintang',
        'calendar'          => 'Kalender',
        'bell'              => 'Notifikasi',
        'book'              => 'Buku',
        'award'             => 'Penghargaan',
        'checkCircle'       => 'Ceklis',
        'info'              => 'Informasi',
        'bx_wallet'         => 'Dompet / Keuangan',
        'bx_calendar_event' => 'Acara / Event',
        'bx_news'           => 'Berita',
        'bx_book'           => 'Buku Terbuka',
        'bx_bell'           => 'Lonceng',
    ];
@endphp

<div class="mb-6" data-aos="fade-down">
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Manajemen Pengumuman</h1>
            <p class="text-slate-500 text-sm mt-0.5">Kelola pengumuman yang tampil di aplikasi siswa secara real-time</p>
        </div>
        <button onclick="openModal('modalTambah')"
            class="flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-200 hover:bg-blue-700 transition-all active:scale-95">
            <i class='bx bx-plus text-lg'></i> Buat Pengumuman
        </button>
    </div>
</div>

{{-- ============================================================
     STAT CARDS
     ============================================================ --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm" data-aos="fade-up" data-aos-delay="0">
        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center mb-3">
            <i class='bx bxs-megaphone text-blue-600 text-xl'></i>
        </div>
        <p class="text-2xl font-bold text-slate-800">{{ $pengumumans->count() }}</p>
        <p class="text-xs text-slate-500 font-medium mt-0.5">Total Pengumuman</p>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm" data-aos="fade-up" data-aos-delay="50">
        <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center mb-3">
            <i class='bx bxs-news text-emerald-600 text-xl'></i>
        </div>
        <p class="text-2xl font-bold text-slate-800">{{ $totalAktif }}</p>
        <p class="text-xs text-slate-500 font-medium mt-0.5">Ditayangkan</p>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm" data-aos="fade-up" data-aos-delay="100">
        <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center mb-3">
            <i class='bx bxs-pin text-amber-500 text-xl'></i>
        </div>
        <p class="text-2xl font-bold text-slate-800">{{ $totalPinned }}</p>
        <p class="text-xs text-slate-500 font-medium mt-0.5">Disematkan</p>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm" data-aos="fade-up" data-aos-delay="150">
        <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center mb-3">
            <i class='bx bxs-hide text-slate-500 text-xl'></i>
        </div>
        <p class="text-2xl font-bold text-slate-800">{{ $totalNonaktif }}</p>
        <p class="text-xs text-slate-500 font-medium mt-0.5">Disembunyikan</p>
    </div>
</div>

{{-- ============================================================
     LAYOUT: DAFTAR KIRI + PREVIEW KANAN
     ============================================================ --}}
<div class="flex gap-5">

    {{-- ========================
         KOLOM KIRI — LIST
         ======================== --}}
    <div class="flex-1 min-w-0">

        {{-- Filter Bar --}}
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm mb-4" data-aos="fade-up">
            <div class="flex flex-wrap items-center gap-3">
                <div class="relative flex-1 min-w-[160px]">
                    <i class='bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400'></i>
                    <input type="text" id="searchInput" placeholder="Cari judul pengumuman..."
                        class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 text-xs font-medium rounded-xl outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                </div>
                <select id="filterStatus" class="bg-slate-50 border border-slate-200 text-xs font-bold rounded-xl px-3 py-2 outline-none focus:ring-2 focus:ring-blue-500/20 text-slate-600">
                    <option value="">Semua Status</option>
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
                </select>
                <select id="filterPinned" class="bg-slate-50 border border-slate-200 text-xs font-bold rounded-xl px-3 py-2 outline-none focus:ring-2 focus:ring-blue-500/20 text-slate-600">
                    <option value="">Semua</option>
                    <option value="1">Disematkan</option>
                    <option value="0">Tidak Disematkan</option>
                </select>
                <button onclick="resetFilter()" class="text-slate-400 hover:text-slate-700 text-xs font-medium flex items-center gap-1 transition-colors">
                    <i class='bx bx-reset'></i> Reset
                </button>
                <span class="ml-auto text-xs text-slate-400"><span id="rowCount">{{ $pengumumans->count() }}</span> pengumuman</span>
            </div>
        </div>

        {{-- List Pengumuman --}}
        <div class="space-y-3" data-aos="fade-up" data-aos-delay="50">
            @forelse($pengumumans as $p)
            @php
                $bxIcon = $iconMap[$p['icon'] ?? 'info'] ?? 'bxs-info-circle';
                $warna  = $p['warna'] ?? '#2563EB';
                $createdAt = \Carbon\Carbon::parse($p['created_at'])->diffForHumans();
                $isPinned  = $p['is_pinned'] ?? false;
                $isActive  = $p['is_active'] ?? true;
            @endphp

            <div class="p-card bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:border-blue-200 transition-all duration-200 cursor-pointer overflow-hidden"
                data-id="{{ $p['id'] }}"
                data-search="{{ strtolower($p['judul']) }}"
                data-active="{{ $isActive ? '1' : '0' }}"
                data-pinned="{{ $isPinned ? '1' : '0' }}"
                onclick="openPreview({{ json_encode($p) }})">

                <div class="flex">
                    {{-- Accent left bar with dynamic color --}}
                    <div class="w-1 flex-shrink-0 rounded-l-2xl" style="background-color: {{ $warna }}"></div>

                    <div class="flex-1 p-4">
                        <div class="flex items-start gap-3">
                            {{-- Icon --}}
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm"
                                style="background-color: {{ $warna }}20;">
                                <i class='bx {{ $bxIcon }} text-lg' style="color: {{ $warna }}"></i>
                            </div>

                            {{-- Content --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    @if($isPinned)
                                    <span class="inline-flex items-center gap-0.5 text-[9px] font-bold text-amber-600 bg-amber-50 border border-amber-200 px-1.5 py-0.5 rounded-md">
                                        <i class='bx bxs-pin text-[9px]'></i> PIN
                                    </span>
                                    @endif
                                    <h3 class="font-bold text-slate-800 text-sm truncate">{{ $p['judul'] }}</h3>
                                </div>
                                <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">{{ $p['isi'] }}</p>
                            </div>

                            {{-- Meta kanan --}}
                            <div class="flex flex-col items-end gap-1.5 flex-shrink-0 ml-2">
                                <span class="text-[10px] text-slate-400">{{ $createdAt }}</span>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-bold border {{ $isActive ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-100 text-slate-500 border-slate-200' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $isActive ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                    {{ $isActive ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                        </div>

                        {{-- Action row --}}
                        <div class="flex items-center gap-2 mt-3 pt-3 border-t border-slate-50" onclick="event.stopPropagation()">
                            {{-- Toggle Aktif --}}
                            <form action="{{ route('admin.pengumuman.toggle', $p['id']) }}" method="POST">
                                @csrf
                                <button type="submit" title="Toggle aktif/nonaktif"
                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 text-[10px] font-bold rounded-lg transition-all border {{ $isActive ? 'bg-emerald-50 text-emerald-600 border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-500 border-slate-200 hover:bg-slate-200' }}">
                                    <i class='bx {{ $isActive ? "bxs-hide" : "bxs-show" }} text-xs'></i>
                                    {{ $isActive ? 'Sembunyikan' : 'Tayangkan' }}
                                </button>
                            </form>

                            {{-- Edit --}}
                            <button onclick="openEditModal({{ json_encode($p) }})"
                                class="inline-flex items-center gap-1 px-2.5 py-1.5 text-[10px] font-bold rounded-lg border bg-slate-50 text-slate-600 border-slate-200 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 transition-all">
                                <i class='bx bx-edit-alt text-xs'></i> Edit
                            </button>

                            {{-- Hapus --}}
                            <button onclick="openDeleteModal({{ $p['id'] }}, '{{ addslashes($p['judul']) }}')"
                                class="inline-flex items-center gap-1 px-2.5 py-1.5 text-[10px] font-bold rounded-lg border bg-slate-50 text-slate-500 border-slate-200 hover:bg-red-50 hover:text-red-500 hover:border-red-200 transition-all">
                                <i class='bx bx-trash text-xs'></i> Hapus
                            </button>

                            {{-- Urutan --}}
                            <span class="ml-auto text-[10px] text-slate-400 font-medium">Urutan: {{ $p['urutan'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-2xl border border-dashed border-slate-200 py-20 text-center">
                <div class="flex flex-col items-center gap-3">
                    <div class="w-16 h-16 rounded-2xl bg-slate-50 flex items-center justify-center">
                        <i class='bx bx-megaphone text-3xl text-slate-300'></i>
                    </div>
                    <p class="text-slate-400 font-medium text-sm">Belum ada pengumuman</p>
                    <button onclick="openModal('modalTambah')" class="mt-1 px-4 py-2 bg-blue-600 text-white text-xs font-bold rounded-xl hover:bg-blue-700 transition-all active:scale-95">
                        + Buat Pengumuman Pertama
                    </button>
                </div>
            </div>
            @endforelse

            {{-- No results --}}
            <div id="noResult" class="hidden bg-white rounded-2xl border border-slate-100 py-14 text-center">
                <i class='bx bx-search text-3xl text-slate-300 block mb-2'></i>
                <p class="text-slate-400 text-sm font-medium">Tidak ada yang cocok dengan filter</p>
                <button onclick="resetFilter()" class="text-blue-600 text-xs font-bold hover:underline mt-1">Reset filter</button>
            </div>
        </div>
    </div>

    {{-- ========================
         PANEL PREVIEW (kanan)
         ======================== --}}
    <div id="previewPanel"
        class="hidden flex-col bg-white rounded-3xl border border-slate-100 shadow-lg overflow-hidden transition-all duration-300"
        style="width:360px; flex-shrink:0; height:fit-content; position:sticky; top:80px;">

        {{-- Header Preview --}}
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-slate-800 to-slate-900">
            <div class="flex items-center gap-2">
                <i class='bx bxs-mobile-alt text-slate-400 text-base'></i>
                <span class="text-xs font-bold text-slate-300">Preview — Tampilan Siswa</span>
            </div>
            <button onclick="closePreview()" class="w-7 h-7 rounded-lg bg-white/10 hover:bg-white/20 flex items-center justify-center text-white/60 hover:text-white transition-all">
                <i class='bx bx-x text-base'></i>
            </button>
        </div>

        {{-- Phone mockup --}}
        <div class="p-5 bg-slate-900/5">
            <div class="bg-slate-900 rounded-3xl p-3 shadow-2xl mx-auto" style="max-width:280px;">
                {{-- Status bar --}}
                <div class="flex items-center justify-between px-3 py-1 mb-2">
                    <span class="text-white/50 text-[10px] font-medium">09:41</span>
                    <div class="flex items-center gap-1">
                        <div class="flex gap-0.5">
                            <div class="w-1 h-2 bg-white/40 rounded-full"></div>
                            <div class="w-1 h-3 bg-white/60 rounded-full"></div>
                            <div class="w-1 h-4 bg-white rounded-full"></div>
                        </div>
                        <i class='bx bx-wifi text-white/70 text-sm'></i>
                        <i class='bx bxs-battery-full text-white/70 text-sm'></i>
                    </div>
                </div>

                {{-- App UI --}}
                <div class="bg-white rounded-2xl overflow-hidden">
                    {{-- App header --}}
                    <div class="bg-blue-600 px-4 py-3">
                        <p class="text-white text-[10px] font-medium opacity-70">SMKN 8 Medan</p>
                        <p class="text-white font-bold text-sm">Pengumuman</p>
                    </div>
                    {{-- Card preview --}}
                    <div class="p-3">
                        <div id="mockupCard" class="rounded-2xl p-3.5 border" style="border-color:#2563EB30; background-color:#2563EB08;">
                            <div class="flex items-center gap-2.5 mb-2">
                                <div id="mockupIconWrap" class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0" style="background-color:#2563EB20">
                                    <i id="mockupIcon" class='bx bxs-info-circle text-base' style="color:#2563EB"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p id="mockupJudul" class="font-bold text-slate-800 text-xs truncate">Judul Pengumuman</p>
                                    <p id="mockupPin" class="hidden text-[9px] text-amber-600 font-bold flex items-center gap-0.5 mt-0.5">
                                        <i class='bx bxs-pin text-[9px]'></i> Disematkan
                                    </p>
                                </div>
                            </div>
                            <p id="mockupIsi" class="text-[10px] text-slate-600 leading-relaxed line-clamp-4">Isi pengumuman akan tampil di sini...</p>
                            <div class="flex items-center justify-between mt-2.5 pt-2 border-t" id="mockupBorderColor" style="border-color:#2563EB20">
                                <span id="mockupWaktu" class="text-[9px] text-slate-400 font-medium">Baru saja</span>
                                <span id="mockupStatus" class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded-md">Aktif</span>
                            </div>
                        </div>

                        {{-- Skeleton next cards --}}
                        <div class="mt-2 space-y-2 opacity-30">
                            <div class="h-14 bg-slate-100 rounded-xl"></div>
                            <div class="h-14 bg-slate-100 rounded-xl"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Meta info --}}
        <div class="px-5 py-4 space-y-2 border-t border-slate-100">
            <div class="flex items-center justify-between text-xs">
                <span class="text-slate-400 font-medium">Dibuat oleh</span>
                <span id="previewAdmin" class="font-bold text-slate-700">—</span>
            </div>
            <div class="flex items-center justify-between text-xs">
                <span class="text-slate-400 font-medium">Tanggal</span>
                <span id="previewTanggal" class="font-medium text-slate-600">—</span>
            </div>
            <div class="flex items-center justify-between text-xs">
                <span class="text-slate-400 font-medium">Urutan tampil</span>
                <span id="previewUrutan" class="font-bold text-blue-600">—</span>
            </div>
        </div>
    </div>

</div>


{{-- ============================================================
     MODAL TAMBAH PENGUMUMAN
     ============================================================ --}}
<div id="modalTambah" class="modal-overlay fixed inset-0 z-[9998] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('modalTambah')"></div>
    <div class="modal-card relative bg-white rounded-3xl shadow-2xl w-full max-w-xl overflow-hidden z-10 max-h-[90vh] flex flex-col">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center gap-3 flex-shrink-0">
            <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
                <i class='bx bxs-megaphone text-blue-600 text-lg'></i>
            </div>
            <h3 class="font-bold text-slate-800">Buat Pengumuman Baru</h3>
            <button onclick="closeModal('modalTambah')" class="ml-auto w-8 h-8 rounded-xl flex items-center justify-center text-slate-400 hover:bg-slate-100 transition-all">
                <i class='bx bx-x text-xl'></i>
            </button>
        </div>
        <form action="{{ route('admin.pengumuman.store') }}" method="POST" class="overflow-y-auto flex-1">
            @csrf
            <div class="p-6 space-y-4">
                {{-- Judul --}}
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5">Judul <span class="text-red-500">*</span></label>
                    <input type="text" name="judul" required placeholder="Contoh: Pengumpulan Tugas Akhir Semester"
                        class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                </div>
                {{-- Isi --}}
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5">Isi Pengumuman <span class="text-red-500">*</span></label>
                    <textarea name="isi" required rows="4" placeholder="Tulis isi pengumuman yang jelas dan informatif..."
                        class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all resize-none"></textarea>
                </div>
                {{-- Icon & Warna --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5">Icon</label>
                        <select name="icon" id="tambahIcon"
                            class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all"
                            onchange="updateIconPreview(this, 'tambahIconPreview')">
                            @foreach($iconLabels as $val => $label)
                            <option value="{{ $val }}" {{ $val === 'info' ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5">Warna Aksen</label>
                        <div class="flex items-center gap-2">
                            <input type="color" name="warna" id="tambahWarna" value="#2563EB"
                                class="w-10 h-10 rounded-xl border border-slate-200 cursor-pointer bg-slate-50 p-1"
                                oninput="updateWarnaPreview(this, 'tambahWarnaBox')">
                            <div id="tambahWarnaBox" class="flex-1 h-10 rounded-xl border border-slate-200 flex items-center gap-2 px-3" style="background-color:#2563EB15; border-color:#2563EB50">
                                <i id="tambahIconPreview" class='bx bxs-info-circle text-lg' style="color:#2563EB"></i>
                                <span class="text-xs font-bold" style="color:#2563EB" id="tambahWarnaHex">#2563EB</span>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Urutan --}}
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5">Urutan Tampil</label>
                    <input type="number" name="urutan" value="0" min="0"
                        class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                    <p class="text-[10px] text-slate-400 mt-1">Angka kecil = tampil lebih awal. 0 = urutan default.</p>
                </div>
                {{-- Opsi --}}
                <div class="flex flex-col sm:flex-row gap-3 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <label class="flex items-center gap-3 cursor-pointer flex-1">
                        <div class="relative">
                            <input type="checkbox" name="is_active" id="tambahIsActive" value="1" checked class="sr-only peer">
                            <div class="w-10 h-5 bg-slate-200 rounded-full peer-checked:bg-blue-600 transition-colors"></div>
                            <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-all peer-checked:translate-x-5"></div>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-700">Tayangkan Sekarang</p>
                            <p class="text-[10px] text-slate-400">Langsung tampil di aplikasi siswa</p>
                        </div>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer flex-1">
                        <div class="relative">
                            <input type="checkbox" name="is_pinned" id="tambahIsPinned" value="1" class="sr-only peer">
                            <div class="w-10 h-5 bg-slate-200 rounded-full peer-checked:bg-amber-500 transition-colors"></div>
                            <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-all peer-checked:translate-x-5"></div>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-700">Sematkan di Atas</p>
                            <p class="text-[10px] text-slate-400">Tampil paling atas untuk siswa</p>
                        </div>
                    </label>
                </div>
            </div>
            <div class="px-6 pb-6 flex gap-3 flex-shrink-0">
                <button type="button" onclick="closeModal('modalTambah')" class="flex-1 py-3 bg-slate-100 text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-200 transition-all active:scale-95">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-blue-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-200 hover:bg-blue-700 transition-all active:scale-95">
                    <i class='bx bx-send mr-1'></i> Publikasikan
                </button>
            </div>
        </form>
    </div>
</div>


{{-- ============================================================
     MODAL EDIT PENGUMUMAN
     ============================================================ --}}
<div id="modalEdit" class="modal-overlay fixed inset-0 z-[9998] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('modalEdit')"></div>
    <div class="modal-card relative bg-white rounded-3xl shadow-2xl w-full max-w-xl overflow-hidden z-10 max-h-[90vh] flex flex-col">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center gap-3 flex-shrink-0">
            <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center">
                <i class='bx bx-edit text-amber-600 text-lg'></i>
            </div>
            <h3 class="font-bold text-slate-800">Edit Pengumuman</h3>
            <button onclick="closeModal('modalEdit')" class="ml-auto w-8 h-8 rounded-xl flex items-center justify-center text-slate-400 hover:bg-slate-100 transition-all">
                <i class='bx bx-x text-xl'></i>
            </button>
        </div>
        <form id="formEdit" action="" method="POST" class="overflow-y-auto flex-1">
            @csrf
            @method('PUT')
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5">Judul <span class="text-red-500">*</span></label>
                    <input type="text" name="judul" id="editJudul" required
                        class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5">Isi Pengumuman <span class="text-red-500">*</span></label>
                    <textarea name="isi" id="editIsi" required rows="4"
                        class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all resize-none"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5">Icon</label>
                        <select name="icon" id="editIcon"
                            class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all"
                            onchange="updateIconPreview(this, 'editIconPreview')">
                            @foreach($iconLabels as $val => $label)
                            <option value="{{ $val }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5">Warna Aksen</label>
                        <div class="flex items-center gap-2">
                            <input type="color" name="warna" id="editWarna" value="#2563EB"
                                class="w-10 h-10 rounded-xl border border-slate-200 cursor-pointer bg-slate-50 p-1"
                                oninput="updateWarnaPreview(this, 'editWarnaBox')">
                            <div id="editWarnaBox" class="flex-1 h-10 rounded-xl border flex items-center gap-2 px-3" style="background-color:#2563EB15; border-color:#2563EB50">
                                <i id="editIconPreview" class='bx bxs-info-circle text-lg' style="color:#2563EB"></i>
                                <span class="text-xs font-bold" style="color:#2563EB" id="editWarnaHex">#2563EB</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5">Urutan Tampil</label>
                    <input type="number" name="urutan" id="editUrutan" value="0" min="0"
                        class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all">
                </div>
                <div class="flex flex-col sm:flex-row gap-3 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <label class="flex items-center gap-3 cursor-pointer flex-1">
                        <div class="relative">
                            <input type="checkbox" name="is_active" id="editIsActive" value="1" class="sr-only peer">
                            <div class="w-10 h-5 bg-slate-200 rounded-full peer-checked:bg-blue-600 transition-colors"></div>
                            <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-all peer-checked:translate-x-5"></div>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-700">Tayangkan</p>
                            <p class="text-[10px] text-slate-400">Tampil di aplikasi siswa</p>
                        </div>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer flex-1">
                        <div class="relative">
                            <input type="checkbox" name="is_pinned" id="editIsPinned" value="1" class="sr-only peer">
                            <div class="w-10 h-5 bg-slate-200 rounded-full peer-checked:bg-amber-500 transition-colors"></div>
                            <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-all peer-checked:translate-x-5"></div>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-700">Sematkan</p>
                            <p class="text-[10px] text-slate-400">Tampil paling atas</p>
                        </div>
                    </label>
                </div>
            </div>
            <div class="px-6 pb-6 flex gap-3">
                <button type="button" onclick="closeModal('modalEdit')" class="flex-1 py-3 bg-slate-100 text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-200 transition-all active:scale-95">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-amber-500 text-white text-sm font-bold rounded-xl shadow-lg shadow-amber-200 hover:bg-amber-600 transition-all active:scale-95">
                    <i class='bx bx-save mr-1'></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>


{{-- ============================================================
     MODAL HAPUS
     ============================================================ --}}
<div id="modalHapus" class="modal-overlay fixed inset-0 z-[9998] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('modalHapus')"></div>
    <div class="modal-card relative bg-white rounded-3xl shadow-2xl w-full max-w-sm z-10">
        <div class="p-7 text-center">
            <div class="w-16 h-16 rounded-2xl bg-red-50 flex items-center justify-center mx-auto mb-4">
                <i class='bx bxs-trash text-red-500 text-3xl'></i>
            </div>
            <h3 class="font-bold text-slate-800 text-lg">Hapus Pengumuman?</h3>
            <p class="text-slate-500 text-sm mt-2 mb-6">
                <strong id="deleteNama" class="text-slate-700 block mb-1"></strong>
                Pengumuman ini akan dihapus permanen dari sistem dan tidak lagi tampil di aplikasi siswa.
            </p>
            <div class="flex gap-3">
                <button onclick="closeModal('modalHapus')" class="flex-1 py-3 bg-slate-100 text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-200 transition-all active:scale-95">Batal</button>
                <form id="formHapus" action="" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full py-3 bg-red-500 text-white text-sm font-bold rounded-xl shadow-lg shadow-red-200 hover:bg-red-600 transition-all active:scale-95">
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
    .modal-card  { animation: modalIn .3s cubic-bezier(.34,1.4,.64,1) forwards; }
    .modal-overlay.active { display:flex !important; }

    .p-card.active-preview {
        border-color: #3B82F6 !important;
        box-shadow: 0 0 0 2px rgba(59,130,246,.15), 0 4px 16px rgba(59,130,246,.1) !important;
    }
</style>

<script>
// ============================================================
// Icon map JS (sync dengan PHP)
// ============================================================
const ICON_MAP = {!! json_encode($iconMap) !!};

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
// ICON / WARNA PREVIEW helpers
// ============================================================
function updateIconPreview(selectEl, previewIconId) {
    const val    = selectEl.value;
    const bxCls  = ICON_MAP[val] || 'bxs-info-circle';
    const target = document.getElementById(previewIconId);
    if (target) {
        target.className = 'bx ' + bxCls + ' text-lg';
    }
}

function updateWarnaPreview(input, boxId) {
    const color = input.value;
    const box   = document.getElementById(boxId);
    if (!box) return;
    box.style.backgroundColor = color + '15';
    box.style.borderColor     = color + '50';
    const icon = box.querySelector('i');
    const hex  = box.querySelector('span');
    if (icon) icon.style.color = color;
    if (hex)  { hex.style.color = color; hex.textContent = color.toUpperCase(); }
}

// ============================================================
// EDIT MODAL — populate
// ============================================================
function openEditModal(p) {
    document.getElementById('editJudul').value        = p.judul  || '';
    document.getElementById('editIsi').value          = p.isi    || '';
    document.getElementById('editIcon').value         = p.icon   || 'info';
    document.getElementById('editUrutan').value       = p.urutan ?? 0;
    document.getElementById('editIsActive').checked   = !!p.is_active;
    document.getElementById('editIsPinned').checked   = !!p.is_pinned;
    document.getElementById('formEdit').action        = `/admin/pengumuman/${p.id}`;

    // Warna
    const warna = p.warna || '#2563EB';
    document.getElementById('editWarna').value = warna;

    // Trigger preview update
    const fakeInput = { value: warna };
    updateWarnaPreview({ value: warna }, 'editWarnaBox');
    updateIconPreview(document.getElementById('editIcon'), 'editIconPreview');

    openModal('modalEdit');
}

// ============================================================
// HAPUS MODAL
// ============================================================
function openDeleteModal(id, nama) {
    document.getElementById('deleteNama').textContent = nama;
    document.getElementById('formHapus').action       = `/admin/pengumuman/${id}`;
    openModal('modalHapus');
}

// ============================================================
// PREVIEW PANEL
// ============================================================
function openPreview(p) {
    // Highlight card
    document.querySelectorAll('.p-card').forEach(c => c.classList.remove('active-preview'));
    const card = document.querySelector(`.p-card[data-id="${p.id}"]`);
    if (card) card.classList.add('active-preview');

    // Show panel
    const panel = document.getElementById('previewPanel');
    panel.classList.remove('hidden');
    panel.classList.add('flex');

    const warna   = p.warna || '#2563EB';
    const bxCls   = ICON_MAP[p.icon || 'info'] || 'bxs-info-circle';
    const isActive = !!p.is_active;
    const isPinned = !!p.is_pinned;

    // Mockup card
    document.getElementById('mockupCard').style.backgroundColor = warna + '08';
    document.getElementById('mockupCard').style.borderColor     = warna + '30';
    document.getElementById('mockupIconWrap').style.backgroundColor = warna + '20';
    document.getElementById('mockupIcon').className  = 'bx ' + bxCls + ' text-base';
    document.getElementById('mockupIcon').style.color = warna;
    document.getElementById('mockupJudul').textContent = p.judul || '—';
    document.getElementById('mockupIsi').textContent   = p.isi   || '—';
    document.getElementById('mockupBorderColor').style.borderColor = warna + '20';

    const pinEl = document.getElementById('mockupPin');
    if (isPinned) { pinEl.classList.remove('hidden'); pinEl.classList.add('flex'); }
    else           { pinEl.classList.add('hidden'); pinEl.classList.remove('flex'); }

    document.getElementById('mockupWaktu').textContent = p.created_at
        ? new Date(p.created_at).toLocaleDateString('id-ID', { day:'numeric', month:'short', year:'numeric' })
        : 'Baru saja';

    const statusEl = document.getElementById('mockupStatus');
    statusEl.textContent  = isActive ? 'Aktif' : 'Tersembunyi';
    statusEl.className    = isActive
        ? 'text-[9px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded-md'
        : 'text-[9px] font-bold text-slate-400 bg-slate-100 px-1.5 py-0.5 rounded-md';

    // Meta
    document.getElementById('previewAdmin').textContent   = p.admin?.name || 'Admin';
    document.getElementById('previewTanggal').textContent = p.created_at
        ? new Date(p.created_at).toLocaleDateString('id-ID', { day:'numeric', month:'long', year:'numeric' })
        : '—';
    document.getElementById('previewUrutan').textContent  = p.urutan ?? 0;
}

function closePreview() {
    document.getElementById('previewPanel').classList.add('hidden');
    document.getElementById('previewPanel').classList.remove('flex');
    document.querySelectorAll('.p-card').forEach(c => c.classList.remove('active-preview'));
}

// ============================================================
// FILTER
// ============================================================
function applyFilter() {
    const search  = document.getElementById('searchInput').value.toLowerCase().trim();
    const status  = document.getElementById('filterStatus').value;
    const pinned  = document.getElementById('filterPinned').value;

    const cards = document.querySelectorAll('.p-card');
    let vis = 0;

    cards.forEach(c => {
        const s = !status || c.dataset.active === status;
        const pi = !pinned  || c.dataset.pinned === pinned;
        const t  = !search  || c.dataset.search.includes(search);
        const show = s && pi && t;
        c.style.display = show ? '' : 'none';
        if (show) vis++;
    });

    document.getElementById('noResult').classList.toggle('hidden', vis > 0 || cards.length === 0);
    document.getElementById('rowCount').textContent = vis;
}
function resetFilter() {
    document.getElementById('searchInput').value   = '';
    document.getElementById('filterStatus').value  = '';
    document.getElementById('filterPinned').value  = '';
    applyFilter();
}
document.getElementById('searchInput').addEventListener('input', applyFilter);
document.getElementById('filterStatus').addEventListener('change', applyFilter);
document.getElementById('filterPinned').addEventListener('change', applyFilter);

// ============================================================
// TOAST AUTO DISMISS
// ============================================================
setTimeout(() => {
    ['toast-ok','toast-err'].forEach(id => {
        const el = document.getElementById(id);
        if (el) { el.style.transition='opacity .4s'; el.style.opacity='0'; setTimeout(()=>el.remove(),400); }
    });
}, 5000);
</script>

@endsection
