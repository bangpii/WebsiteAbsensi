@extends('layouts.admin')

@section('title', 'Manajemen Hari Libur')

@section('content')

{{-- ============================================================
     TOAST NOTIFICATION
     ============================================================ --}}
@if(session('success'))
<div id="toast-success" class="fixed top-5 right-5 z-[9999] flex items-center gap-3 bg-white border border-emerald-200 text-emerald-800 px-5 py-3.5 rounded-2xl shadow-xl text-sm font-semibold animate-toast" style="max-width:380px;">
    <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
        <i class='bx bx-check text-emerald-600 text-lg'></i>
    </div>
    <span>{{ session('success') }}</span>
    <button onclick="this.parentElement.remove()" class="ml-auto text-emerald-400 hover:text-emerald-700 transition-colors"><i class='bx bx-x text-lg'></i></button>
</div>
@endif

@if(session('error'))
<div id="toast-error" class="fixed top-5 right-5 z-[9999] flex items-center gap-3 bg-white border border-red-200 text-red-800 px-5 py-3.5 rounded-2xl shadow-xl text-sm font-semibold animate-toast" style="max-width:380px;">
    <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
        <i class='bx bx-x text-red-600 text-lg'></i>
    </div>
    <span>{{ session('error') }}</span>
    <button onclick="this.parentElement.remove()" class="ml-auto text-red-400 hover:text-red-700 transition-colors"><i class='bx bx-x text-lg'></i></button>
</div>
@endif

@if($error ?? null)
<div class="mb-5 flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 px-5 py-3.5 rounded-2xl text-sm font-medium" data-aos="fade-down">
    <i class='bx bxs-error-circle text-xl'></i>
    <span>Gagal memuat data: {{ $error }}</span>
</div>
@endif

{{-- ============================================================
     PAGE HEADER
     ============================================================ --}}
<div class="mb-6" data-aos="fade-down">
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Manajemen Hari Libur</h1>
            <p class="text-slate-500 text-sm mt-0.5">Kelola hari libur, tanggal merah nasional, dan libur sekolah</p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="openModal('modalImportNasional')" class="flex items-center gap-2 px-4 py-2.5 bg-amber-500 text-white text-sm font-bold rounded-xl shadow-lg shadow-amber-200 hover:bg-amber-600 transition-all active:scale-95">
                <i class='bx bx-calendar-star'></i>
                Import Libur Nasional
            </button>
            <button onclick="openModal('modalTambah')" class="flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-200 hover:bg-blue-700 transition-all active:scale-95">
                <i class='bx bx-plus'></i>
                Tambah Libur
            </button>
        </div>
    </div>
</div>

{{-- ============================================================
     STAT CARDS
     ============================================================ --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @php
        $liburs = $liburs ?? collect();
        $totalAktif   = $liburs->where('is_active', true)->count();
        $totalNasional = $liburs->where('tipe', 'nasional')->count();
        $totalSekolah  = $liburs->where('tipe', 'sekolah')->count();
        $totalCustom   = $liburs->where('tipe', 'custom')->count();
    @endphp

    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm" data-aos="fade-up" data-aos-delay="0">
        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center mb-3">
            <i class='bx bxs-calendar text-blue-600 text-xl'></i>
        </div>
        <p class="text-2xl font-bold text-slate-800">{{ $liburs->count() }}</p>
        <p class="text-xs text-slate-500 font-medium mt-0.5">Total Entri</p>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm" data-aos="fade-up" data-aos-delay="50">
        <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center mb-3">
            <i class='bx bxs-flag text-red-500 text-xl'></i>
        </div>
        <p class="text-2xl font-bold text-slate-800">{{ $totalNasional }}</p>
        <p class="text-xs text-slate-500 font-medium mt-0.5">Libur Nasional</p>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm" data-aos="fade-up" data-aos-delay="100">
        <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center mb-3">
            <i class='bx bxs-school text-indigo-600 text-xl'></i>
        </div>
        <p class="text-2xl font-bold text-slate-800">{{ $totalSekolah + $totalCustom }}</p>
        <p class="text-xs text-slate-500 font-medium mt-0.5">Libur Sekolah</p>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm" data-aos="fade-up" data-aos-delay="150">
        <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center mb-3">
            <i class='bx bxs-check-shield text-emerald-600 text-xl'></i>
        </div>
        <p class="text-2xl font-bold text-slate-800">{{ $totalAktif }}</p>
        <p class="text-xs text-slate-500 font-medium mt-0.5">Aktif</p>
    </div>
</div>

{{-- ============================================================
     TABEL + FILTER
     ============================================================ --}}
<div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden" data-aos="fade-up">

    {{-- Filter Bar --}}
    <div class="px-6 py-4 border-b border-slate-50 flex flex-wrap items-center gap-3">
        <h3 class="font-bold text-slate-800 flex-1 min-w-0">Daftar Hari Libur</h3>

        {{-- Search --}}
        <div class="relative">
            <i class='bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400'></i>
            <input type="text" id="searchInput" placeholder="Cari libur..." class="pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 text-xs font-medium rounded-xl outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all w-48">
        </div>

        {{-- Filter Tipe --}}
        <select id="filterTipe" class="bg-slate-50 border border-slate-200 text-xs font-bold rounded-xl px-3 py-2 outline-none focus:ring-2 focus:ring-blue-500/20">
            <option value="">Semua Tipe</option>
            <option value="nasional">Nasional</option>
            <option value="sekolah">Sekolah</option>
            <option value="custom">Custom</option>
        </select>

        {{-- Filter Bulan --}}
        <select id="filterBulan" class="bg-slate-50 border border-slate-200 text-xs font-bold rounded-xl px-3 py-2 outline-none focus:ring-2 focus:ring-blue-500/20">
            <option value="">Semua Bulan</option>
            @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $i => $bln)
            <option value="{{ $i+1 }}" {{ ($i+1) == date('n') ? 'selected' : '' }}>{{ $bln }}</option>
            @endforeach
        </select>

        {{-- Filter Tahun --}}
        <select id="filterTahun" class="bg-slate-50 border border-slate-200 text-xs font-bold rounded-xl px-3 py-2 outline-none focus:ring-2 focus:ring-blue-500/20">
            @for($y = date('Y')-1; $y <= date('Y')+2; $y++)
            <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
        </select>

        {{-- Reset Filter --}}
        <button onclick="resetFilter()" class="text-slate-400 hover:text-slate-700 text-xs font-medium flex items-center gap-1 transition-colors">
            <i class='bx bx-reset'></i> Reset
        </button>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50/60 border-b border-slate-100 text-slate-500 font-bold uppercase text-[10px] tracking-wider">
                <tr>
                    <th class="px-6 py-4 w-8">#</th>
                    <th class="px-6 py-4">Nama Libur</th>
                    <th class="px-6 py-4">Tanggal</th>
                    <th class="px-6 py-4">Tipe</th>
                    <th class="px-6 py-4">Keterangan</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="liburTableBody" class="divide-y divide-slate-50">
                @forelse($liburs as $idx => $libur)
                <tr class="libur-row hover:bg-slate-50/50 transition-colors"
                    data-nama="{{ strtolower($libur['nama']) }}"
                    data-tipe="{{ $libur['tipe'] }}"
                    data-bulan="{{ date('n', strtotime($libur['tanggal'])) }}"
                    data-tahun="{{ date('Y', strtotime($libur['tanggal'])) }}">
                    <td class="px-6 py-4 text-slate-400 text-xs font-medium">{{ $idx + 1 }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2.5">
                            @php
                                $tipeColor = match($libur['tipe']) {
                                    'nasional' => 'bg-red-100 text-red-600',
                                    'sekolah'  => 'bg-indigo-100 text-indigo-600',
                                    default    => 'bg-amber-100 text-amber-600'
                                };
                                $tipeIcon = match($libur['tipe']) {
                                    'nasional' => 'bxs-flag',
                                    'sekolah'  => 'bxs-school',
                                    default    => 'bxs-star'
                                };
                            @endphp
                            <div class="w-8 h-8 rounded-lg {{ $tipeColor }} flex items-center justify-center flex-shrink-0">
                                <i class='bx {{ $tipeIcon }} text-sm'></i>
                            </div>
                            <span class="font-semibold text-slate-700">{{ $libur['nama'] }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2 text-slate-600 font-medium">
                            <i class='bx bx-calendar text-slate-400'></i>
                            {{ \Carbon\Carbon::parse($libur['tanggal'])->translatedFormat('d F Y') }}
                            <span class="text-slate-400 text-xs">({{ \Carbon\Carbon::parse($libur['tanggal'])->translatedFormat('l') }})</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $badge = match($libur['tipe']) {
                                'nasional' => 'bg-red-50 text-red-600 border-red-100',
                                'sekolah'  => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                                default    => 'bg-amber-50 text-amber-600 border-amber-100'
                            };
                            $label = match($libur['tipe']) {
                                'nasional' => 'Nasional',
                                'sekolah'  => 'Sekolah',
                                default    => 'Custom'
                            };
                        @endphp
                        <span class="px-2.5 py-1 {{ $badge }} text-[10px] font-bold rounded-lg border uppercase tracking-wide">{{ $label }}</span>
                    </td>
                    <td class="px-6 py-4 text-slate-500 text-xs">
                        {{ $libur['keterangan'] ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <form action="{{ route('admin.libur.toggle', $libur['id']) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" title="Klik untuk toggle status"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[10px] font-bold transition-all {{ $libur['is_active'] ? 'bg-emerald-50 text-emerald-600 border border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-500 border border-slate-200 hover:bg-slate-200' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $libur['is_active'] ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                {{ $libur['is_active'] ? 'Aktif' : 'Nonaktif' }}
                            </button>
                        </form>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-1.5">
                            {{-- Edit --}}
                            <button onclick="openEditModal({{ json_encode($libur) }})"
                                class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all"
                                title="Edit">
                                <i class='bx bx-edit-alt text-base'></i>
                            </button>
                            {{-- Hapus --}}
                            <button onclick="openDeleteModal({{ $libur['id'] }}, '{{ addslashes($libur['nama']) }}')"
                                class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all"
                                title="Hapus">
                                <i class='bx bx-trash text-base'></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr id="emptyRow">
                    <td colspan="7" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-16 h-16 rounded-2xl bg-slate-50 flex items-center justify-center">
                                <i class='bx bx-calendar-x text-3xl text-slate-300'></i>
                            </div>
                            <p class="text-slate-400 font-medium text-sm">Belum ada data libur</p>
                            <button onclick="openModal('modalTambah')" class="text-blue-600 text-xs font-bold hover:underline">+ Tambah Libur Sekarang</button>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- No result saat filter --}}
    <div id="noFilterResult" class="hidden px-6 py-12 text-center">
        <div class="flex flex-col items-center gap-2">
            <i class='bx bx-search text-3xl text-slate-300'></i>
            <p class="text-slate-400 text-sm font-medium">Tidak ada libur yang cocok dengan filter</p>
            <button onclick="resetFilter()" class="text-blue-600 text-xs font-bold hover:underline mt-1">Reset Filter</button>
        </div>
    </div>

    {{-- Catatan Sistem --}}
    <div class="p-5 bg-blue-50/50 border-t border-slate-100">
        <div class="flex items-start gap-3">
            <div class="w-9 h-9 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600 flex-shrink-0 mt-0.5">
                <i class='bx bx-info-circle text-lg'></i>
            </div>
            <div>
                <p class="text-sm font-bold text-blue-900">Catatan Sistem</p>
                <p class="text-xs text-blue-700/70 mt-0.5 leading-relaxed">Siswa tidak dapat absensi pada hari yang terdaftar di atas. Hari <strong>Sabtu & Minggu</strong> otomatis dianggap libur oleh sistem tanpa perlu didaftarkan di sini. Status <strong>Nonaktif</strong> berarti tanggal tersebut tidak lagi dianggap libur meski terdaftar.</p>
            </div>
        </div>
    </div>
</div>


{{-- ============================================================
     MODAL TAMBAH LIBUR
     ============================================================ --}}
<div id="modalTambah" class="modal-overlay fixed inset-0 z-[9998] hidden items-center justify-center p-4">
    <div class="modal-backdrop absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('modalTambah')"></div>
    <div class="modal-card relative bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden z-10">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
                    <i class='bx bx-plus-circle text-blue-600 text-lg'></i>
                </div>
                <h3 class="font-bold text-slate-800">Tambah Hari Libur</h3>
            </div>
            <button onclick="closeModal('modalTambah')" class="w-8 h-8 rounded-xl flex items-center justify-center text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-all">
                <i class='bx bx-x text-xl'></i>
            </button>
        </div>
        <form action="{{ route('admin.libur.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5 ml-0.5">Tanggal <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal" required class="libur-input w-full bg-slate-50 border border-slate-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5 ml-0.5">Nama Hari Libur <span class="text-red-500">*</span></label>
                <input type="text" name="nama" placeholder="Contoh: Hari Raya Idul Fitri" required class="libur-input w-full bg-slate-50 border border-slate-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5 ml-0.5">Tipe <span class="text-red-500">*</span></label>
                <select name="tipe" required class="libur-input w-full bg-slate-50 border border-slate-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                    <option value="nasional">🚩 Nasional (Tanggal Merah)</option>
                    <option value="sekolah" selected>🏫 Sekolah (Agenda Sekolah)</option>
                    <option value="custom">⭐ Custom</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5 ml-0.5">Keterangan</label>
                <textarea name="keterangan" rows="2" placeholder="Opsional — keterangan tambahan..." class="libur-input w-full bg-slate-50 border border-slate-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all resize-none"></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeModal('modalTambah')" class="flex-1 py-3 bg-slate-100 text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-200 transition-all active:scale-95">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-blue-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-200 hover:bg-blue-700 transition-all active:scale-95">
                    <i class='bx bx-save mr-1'></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>


{{-- ============================================================
     MODAL EDIT LIBUR
     ============================================================ --}}
<div id="modalEdit" class="modal-overlay fixed inset-0 z-[9998] hidden items-center justify-center p-4">
    <div class="modal-backdrop absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('modalEdit')"></div>
    <div class="modal-card relative bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden z-10">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center">
                    <i class='bx bx-edit text-amber-600 text-lg'></i>
                </div>
                <h3 class="font-bold text-slate-800">Edit Hari Libur</h3>
            </div>
            <button onclick="closeModal('modalEdit')" class="w-8 h-8 rounded-xl flex items-center justify-center text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-all">
                <i class='bx bx-x text-xl'></i>
            </button>
        </div>
        <form id="formEdit" action="" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5 ml-0.5">Tanggal <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal" id="editTanggal" required class="libur-input w-full bg-slate-50 border border-slate-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5 ml-0.5">Nama Hari Libur <span class="text-red-500">*</span></label>
                <input type="text" name="nama" id="editNama" required class="libur-input w-full bg-slate-50 border border-slate-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5 ml-0.5">Tipe <span class="text-red-500">*</span></label>
                <select name="tipe" id="editTipe" required class="libur-input w-full bg-slate-50 border border-slate-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all">
                    <option value="nasional">🚩 Nasional (Tanggal Merah)</option>
                    <option value="sekolah">🏫 Sekolah (Agenda Sekolah)</option>
                    <option value="custom">⭐ Custom</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5 ml-0.5">Keterangan</label>
                <textarea name="keterangan" id="editKeterangan" rows="2" class="libur-input w-full bg-slate-50 border border-slate-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all resize-none"></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeModal('modalEdit')" class="flex-1 py-3 bg-slate-100 text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-200 transition-all active:scale-95">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-amber-500 text-white text-sm font-bold rounded-xl shadow-lg shadow-amber-200 hover:bg-amber-600 transition-all active:scale-95">
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
    <div class="modal-card relative bg-white rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden z-10">
        <div class="p-6 text-center">
            <div class="w-16 h-16 rounded-2xl bg-red-50 flex items-center justify-center mx-auto mb-4">
                <i class='bx bxs-trash text-red-500 text-3xl'></i>
            </div>
            <h3 class="font-bold text-slate-800 text-lg">Hapus Hari Libur?</h3>
            <p class="text-slate-500 text-sm mt-2 mb-6">
                Data <strong id="deleteNama" class="text-slate-700"></strong> akan dihapus permanen dan tidak bisa dikembalikan.
            </p>
            <div class="flex gap-3">
                <button onclick="closeModal('modalHapus')" class="flex-1 py-3 bg-slate-100 text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-200 transition-all active:scale-95">Batal</button>
                <form id="formHapus" action="" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full py-3 bg-red-500 text-white text-sm font-bold rounded-xl shadow-lg shadow-red-200 hover:bg-red-600 transition-all active:scale-95">
                        <i class='bx bx-trash mr-1'></i> Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>


{{-- ============================================================
     MODAL IMPORT LIBUR NASIONAL
     ============================================================ --}}
<div id="modalImportNasional" class="modal-overlay fixed inset-0 z-[9998] hidden items-center justify-center p-4">
    <div class="modal-backdrop absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('modalImportNasional')"></div>
    <div class="modal-card relative bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden z-10 max-h-[90vh] flex flex-col">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center">
                    <i class='bx bxs-calendar-star text-amber-600 text-lg'></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800">Import Libur Nasional Indonesia</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Centang tanggal yang ingin ditambahkan ke database</p>
                </div>
            </div>
            <button onclick="closeModal('modalImportNasional')" class="w-8 h-8 rounded-xl flex items-center justify-center text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-all">
                <i class='bx bx-x text-xl'></i>
            </button>
        </div>

        {{-- Filter Tahun Import --}}
        <div class="px-6 py-3 border-b border-slate-50 flex items-center gap-3 flex-shrink-0">
            <span class="text-xs font-bold text-slate-500 uppercase">Tahun:</span>
            @for($y = date('Y'); $y <= date('Y')+1; $y++)
            <button onclick="switchImportYear({{ $y }})" id="importYearBtn{{ $y }}"
                class="import-year-btn px-4 py-1.5 text-xs font-bold rounded-lg transition-all {{ $y == date('Y') ? 'bg-amber-500 text-white shadow-md shadow-amber-200' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                {{ $y }}
            </button>
            @endfor
            <label class="ml-auto flex items-center gap-2 text-xs text-slate-500 font-medium cursor-pointer">
                <input type="checkbox" id="checkAll" onchange="toggleCheckAll()" class="rounded">
                Pilih Semua
            </label>
        </div>

        {{-- List Libur Nasional --}}
        <div class="overflow-y-auto flex-1 px-6 py-4" id="importListContainer">
            {{-- Diisi JS --}}
        </div>

        {{-- Footer --}}
        <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-between flex-shrink-0 bg-slate-50/50">
            <p class="text-xs text-slate-500"><span id="selectedCount" class="font-bold text-amber-600">0</span> tanggal dipilih</p>
            <div class="flex gap-3">
                <button onclick="closeModal('modalImportNasional')" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-100 transition-all active:scale-95">Batal</button>
                <button onclick="submitImportNasional()" id="btnImportSubmit" class="px-5 py-2.5 bg-amber-500 text-white text-sm font-bold rounded-xl shadow-lg shadow-amber-200 hover:bg-amber-600 transition-all active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class='bx bx-import mr-1'></i> Import
                </button>
            </div>
        </div>
    </div>
</div>


{{-- ============================================================
     LOADING OVERLAY (untuk import)
     ============================================================ --}}
<div id="loadingOverlay" class="fixed inset-0 z-[9999] hidden items-center justify-center">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"></div>
    <div class="relative bg-white rounded-2xl px-8 py-6 text-center shadow-2xl">
        <div class="w-12 h-12 border-4 border-amber-500 border-t-transparent rounded-full animate-spin mx-auto mb-3"></div>
        <p class="text-sm font-bold text-slate-700">Memproses import...</p>
        <p class="text-xs text-slate-400 mt-1" id="loadingText">Harap tunggu sebentar</p>
    </div>
</div>


<style>
    @keyframes toastIn {
        from { opacity: 0; transform: translateX(20px); }
        to   { opacity: 1; transform: translateX(0); }
    }
    .animate-toast { animation: toastIn 0.35s ease forwards; }

    @keyframes modalIn {
        from { opacity: 0; transform: scale(0.95) translateY(10px); }
        to   { opacity: 1; transform: scale(1) translateY(0); }
    }
    .modal-card { animation: modalIn 0.3s cubic-bezier(0.34, 1.4, 0.64, 1) forwards; }

    .libur-input:focus { border-color: #3B82F6 !important; }

    .modal-overlay.active { display: flex !important; }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }
</style>

<script>
// ============================================================
// DATA LIBUR NASIONAL INDONESIA (daftar lengkap per tahun)
// ============================================================
const LIBUR_NASIONAL = {
    {{ date('Y') }}: [
        { tanggal: '{{ date('Y') }}-01-01', nama: 'Tahun Baru Masehi', tipe: 'nasional' },
        { tanggal: '{{ date('Y') }}-01-27', nama: 'Isra Miraj Nabi Muhammad SAW', tipe: 'nasional' },
        { tanggal: '{{ date('Y') }}-01-29', nama: 'Tahun Baru Imlek', tipe: 'nasional' },
        { tanggal: '{{ date('Y') }}-03-20', nama: 'Hari Raya Nyepi', tipe: 'nasional' },
        { tanggal: '{{ date('Y') }}-03-30', nama: 'Hari Raya Idul Fitri 1447 H', tipe: 'nasional' },
        { tanggal: '{{ date('Y') }}-03-31', nama: 'Hari Raya Idul Fitri 1447 H (Kedua)', tipe: 'nasional' },
        { tanggal: '{{ date('Y') }}-04-03', nama: 'Wafat Isa Al-Masih', tipe: 'nasional' },
        { tanggal: '{{ date('Y') }}-05-01', nama: 'Hari Buruh Internasional', tipe: 'nasional' },
        { tanggal: '{{ date('Y') }}-05-12', nama: 'Hari Raya Waisak', tipe: 'nasional' },
        { tanggal: '{{ date('Y') }}-05-29', nama: 'Kenaikan Isa Al-Masih', tipe: 'nasional' },
        { tanggal: '{{ date('Y') }}-06-01', nama: 'Hari Lahir Pancasila', tipe: 'nasional' },
        { tanggal: '{{ date('Y') }}-06-06', nama: 'Hari Raya Idul Adha 1447 H', tipe: 'nasional' },
        { tanggal: '{{ date('Y') }}-06-26', nama: 'Tahun Baru Hijriah 1448 H', tipe: 'nasional' },
        { tanggal: '{{ date('Y') }}-08-17', nama: 'Hari Kemerdekaan RI', tipe: 'nasional' },
        { tanggal: '{{ date('Y') }}-09-04', nama: 'Maulid Nabi Muhammad SAW', tipe: 'nasional' },
        { tanggal: '{{ date('Y') }}-12-25', nama: 'Hari Raya Natal', tipe: 'nasional' },
        { tanggal: '{{ date('Y') }}-12-26', nama: 'Cuti Bersama Natal', tipe: 'nasional' },
    ],
    {{ date('Y')+1 }}: [
        { tanggal: '{{ date('Y')+1 }}-01-01', nama: 'Tahun Baru Masehi', tipe: 'nasional' },
        { tanggal: '{{ date('Y')+1 }}-01-17', nama: 'Isra Miraj Nabi Muhammad SAW', tipe: 'nasional' },
        { tanggal: '{{ date('Y')+1 }}-01-18', nama: 'Tahun Baru Imlek', tipe: 'nasional' },
        { tanggal: '{{ date('Y')+1 }}-03-09', nama: 'Hari Raya Nyepi', tipe: 'nasional' },
        { tanggal: '{{ date('Y')+1 }}-03-20', nama: 'Hari Raya Idul Fitri 1448 H', tipe: 'nasional' },
        { tanggal: '{{ date('Y')+1 }}-03-21', nama: 'Hari Raya Idul Fitri 1448 H (Kedua)', tipe: 'nasional' },
        { tanggal: '{{ date('Y')+1 }}-04-03', nama: 'Wafat Isa Al-Masih', tipe: 'nasional' },
        { tanggal: '{{ date('Y')+1 }}-05-01', nama: 'Hari Buruh Internasional', tipe: 'nasional' },
        { tanggal: '{{ date('Y')+1 }}-05-01', nama: 'Hari Raya Waisak', tipe: 'nasional' },
        { tanggal: '{{ date('Y')+1 }}-05-14', nama: 'Kenaikan Isa Al-Masih', tipe: 'nasional' },
        { tanggal: '{{ date('Y')+1 }}-06-01', nama: 'Hari Lahir Pancasila', tipe: 'nasional' },
        { tanggal: '{{ date('Y')+1 }}-05-27', nama: 'Hari Raya Idul Adha 1448 H', tipe: 'nasional' },
        { tanggal: '{{ date('Y')+1 }}-06-16', nama: 'Tahun Baru Hijriah 1449 H', tipe: 'nasional' },
        { tanggal: '{{ date('Y')+1 }}-08-17', nama: 'Hari Kemerdekaan RI', tipe: 'nasional' },
        { tanggal: '{{ date('Y')+1 }}-08-24', nama: 'Maulid Nabi Muhammad SAW', tipe: 'nasional' },
        { tanggal: '{{ date('Y')+1 }}-12-25', nama: 'Hari Raya Natal', tipe: 'nasional' },
        { tanggal: '{{ date('Y')+1 }}-12-26', nama: 'Cuti Bersama Natal', tipe: 'nasional' },
    ]
};

// Tanggal yang sudah ada di DB (untuk marking)
const existingDates = {!! json_encode($liburs->pluck('tanggal')->map(fn($t) => substr($t, 0, 10))->toArray()) !!};

let currentImportYear = {{ date('Y') }};

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

// Tutup modal dengan Escape
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        ['modalTambah','modalEdit','modalHapus','modalImportNasional'].forEach(closeModal);
    }
});

// ============================================================
// MODAL EDIT — populate form
// ============================================================
function openEditModal(libur) {
    document.getElementById('editTanggal').value   = libur.tanggal ? libur.tanggal.substring(0,10) : '';
    document.getElementById('editNama').value       = libur.nama || '';
    document.getElementById('editTipe').value       = libur.tipe || 'sekolah';
    document.getElementById('editKeterangan').value = libur.keterangan || '';
    document.getElementById('formEdit').action      = `/admin/libur/${libur.id}`;
    openModal('modalEdit');
}

// ============================================================
// MODAL HAPUS — set id & nama
// ============================================================
function openDeleteModal(id, nama) {
    document.getElementById('deleteNama').textContent = nama;
    document.getElementById('formHapus').action       = `/admin/libur/${id}`;
    openModal('modalHapus');
}

// ============================================================
// FILTER TABLE
// ============================================================
function applyFilter() {
    const search  = document.getElementById('searchInput').value.toLowerCase().trim();
    const tipe    = document.getElementById('filterTipe').value;
    const bulan   = document.getElementById('filterBulan').value;
    const tahun   = document.getElementById('filterTahun').value;

    const rows    = document.querySelectorAll('.libur-row');
    let visibleCount = 0;

    rows.forEach(row => {
        const namaCocok  = !search || row.dataset.nama.includes(search);
        const tipeCocok  = !tipe   || row.dataset.tipe === tipe;
        const bulanCocok = !bulan  || row.dataset.bulan === bulan;
        const tahunCocok = !tahun  || row.dataset.tahun === tahun;

        const visible = namaCocok && tipeCocok && bulanCocok && tahunCocok;
        row.style.display = visible ? '' : 'none';
        if (visible) visibleCount++;
    });

    document.getElementById('noFilterResult').classList.toggle('hidden', visibleCount > 0 || rows.length === 0);
}

function resetFilter() {
    document.getElementById('searchInput').value = '';
    document.getElementById('filterTipe').value  = '';
    document.getElementById('filterBulan').value = '';
    document.getElementById('filterTahun').value = '{{ date('Y') }}';
    applyFilter();
}

document.getElementById('searchInput').addEventListener('input', applyFilter);
document.getElementById('filterTipe').addEventListener('change', applyFilter);
document.getElementById('filterBulan').addEventListener('change', applyFilter);
document.getElementById('filterTahun').addEventListener('change', applyFilter);

// Jalankan filter awal (bulan ini saja)
window.addEventListener('DOMContentLoaded', () => {
    applyFilter();
    renderImportList(currentImportYear);
});

// ============================================================
// IMPORT LIBUR NASIONAL — render list
// ============================================================
function switchImportYear(year) {
    currentImportYear = year;
    document.querySelectorAll('.import-year-btn').forEach(btn => {
        btn.classList.remove('bg-amber-500', 'text-white', 'shadow-md', 'shadow-amber-200');
        btn.classList.add('bg-slate-100', 'text-slate-600');
    });
    const activeBtn = document.getElementById('importYearBtn' + year);
    if (activeBtn) {
        activeBtn.classList.add('bg-amber-500', 'text-white', 'shadow-md', 'shadow-amber-200');
        activeBtn.classList.remove('bg-slate-100', 'text-slate-600');
    }
    renderImportList(year);
}

function renderImportList(year) {
    const container = document.getElementById('importListContainer');
    const list      = LIBUR_NASIONAL[year] || [];

    const months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

    // Kelompokkan per bulan
    const grouped = {};
    list.forEach(item => {
        const m = parseInt(item.tanggal.split('-')[1]) - 1;
        if (!grouped[m]) grouped[m] = [];
        grouped[m].push(item);
    });

    let html = '';
    Object.keys(grouped).sort((a,b) => a-b).forEach(m => {
        html += `<div class="mb-4">
            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">${months[m]}</p>
            <div class="space-y-1.5">`;
        grouped[m].forEach(item => {
            const alreadyExist = existingDates.includes(item.tanggal);
            const d            = new Date(item.tanggal);
            const dayNames     = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
            const dayName      = dayNames[d.getDay()];
            const dateStr      = d.toLocaleDateString('id-ID', { day:'numeric', month:'long', year:'numeric' });

            html += `
            <label class="flex items-center gap-3 p-3 rounded-xl cursor-pointer transition-all hover:bg-slate-50 ${alreadyExist ? 'opacity-50 cursor-not-allowed' : ''}" ${alreadyExist ? 'title="Sudah ada di database"' : ''}>
                <input type="checkbox" class="import-checkbox rounded w-4 h-4 accent-amber-500" 
                    data-tanggal="${item.tanggal}" 
                    data-nama="${item.nama}" 
                    data-tipe="${item.tipe}"
                    ${alreadyExist ? 'disabled' : ''}
                    onchange="updateSelectedCount()">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-slate-700 ${alreadyExist ? 'line-through' : ''}">${item.nama}</p>
                    <p class="text-xs text-slate-400">${dayName}, ${dateStr}</p>
                </div>
                ${alreadyExist ? '<span class="text-[10px] bg-emerald-50 text-emerald-600 border border-emerald-100 px-2 py-0.5 rounded-md font-bold flex-shrink-0">Sudah ada</span>' : ''}
            </label>`;
        });
        html += `</div></div>`;
    });

    container.innerHTML = html;
    updateSelectedCount();
}

function updateSelectedCount() {
    const checked = document.querySelectorAll('.import-checkbox:checked').length;
    document.getElementById('selectedCount').textContent = checked;
    document.getElementById('btnImportSubmit').disabled  = checked === 0;
}

function toggleCheckAll() {
    const checked   = document.getElementById('checkAll').checked;
    const checkboxes = document.querySelectorAll('.import-checkbox:not(:disabled)');
    checkboxes.forEach(cb => cb.checked = checked);
    updateSelectedCount();
}

// ============================================================
// SUBMIT IMPORT NASIONAL — kirim satu-per-satu via fetch
// ============================================================
async function submitImportNasional() {
    const selected = document.querySelectorAll('.import-checkbox:checked');
    if (!selected.length) return;

    closeModal('modalImportNasional');

    const loading = document.getElementById('loadingOverlay');
    const loadingText = document.getElementById('loadingText');
    loading.classList.remove('hidden');
    loading.classList.add('flex');

    let success = 0;
    let fail    = 0;
    const total = selected.length;

    for (let i = 0; i < selected.length; i++) {
        const cb   = selected[i];
        loadingText.textContent = `Mengimpor ${i+1} dari ${total}...`;

        try {
            const form  = new FormData();
            form.append('tanggal',    cb.dataset.tanggal);
            form.append('nama',       cb.dataset.nama);
            form.append('tipe',       cb.dataset.tipe);
            form.append('keterangan', 'Import otomatis libur nasional');
            form.append('_token',     document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}');

            const resp = await fetch('{{ route('admin.libur.store') }}', {
                method: 'POST',
                body:   form,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            if (resp.ok || resp.redirected) {
                success++;
            } else {
                fail++;
            }
        } catch (e) {
            fail++;
        }
    }

    loading.classList.add('hidden');
    loading.classList.remove('flex');

    // Reload halaman setelah import selesai
    const msg = success > 0
        ? `${success} libur nasional berhasil diimport${fail > 0 ? `, ${fail} gagal` : ''}`
        : `Import gagal (${fail} error)`;

    // Simpan flash ke sessionStorage lalu reload
    sessionStorage.setItem('flash_success', msg);
    window.location.reload();
}

// Tampilkan flash dari sessionStorage setelah reload
window.addEventListener('DOMContentLoaded', () => {
    const msg = sessionStorage.getItem('flash_success');
    if (msg) {
        sessionStorage.removeItem('flash_success');
        showFlashToast(msg);
    }
});

function showFlashToast(msg) {
    const toast = document.createElement('div');
    toast.className = 'fixed top-5 right-5 z-[9999] flex items-center gap-3 bg-white border border-emerald-200 text-emerald-800 px-5 py-3.5 rounded-2xl shadow-xl text-sm font-semibold animate-toast';
    toast.style.maxWidth = '380px';
    toast.innerHTML = `
        <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
            <i class='bx bx-check text-emerald-600 text-lg'></i>
        </div>
        <span>${msg}</span>
        <button onclick="this.parentElement.remove()" class="ml-auto text-emerald-400 hover:text-emerald-700 transition-colors"><i class='bx bx-x text-lg'></i></button>
    `;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 5000);
}

// Auto-hilangkan toast setelah 5 detik
setTimeout(() => {
    ['toast-success','toast-error'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.remove();
    });
}, 5000);
</script>

@endsection