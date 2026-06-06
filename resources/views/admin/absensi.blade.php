@extends('layouts.admin')

@section('title', 'Manajemen Absensi')

@section('content')

{{-- jsPDF untuk export --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>

<style>
    .kelas-card {
        transition: all 0.2s ease;
        cursor: pointer;
        border: 2px solid transparent;
    }
    .kelas-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(29,78,216,0.12);
    }
    .kelas-card.active {
        border-color: #1D4ED8;
        background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%);
        box-shadow: 0 4px 16px rgba(29,78,216,0.2);
    }
    .kelas-card.active .kelas-num  { color: #1D4ED8; }
    .kelas-card.active .kelas-label { color: #1E3A8A; }

    .semester-btn { transition: all 0.2s ease; }
    .semester-btn.active {
        background: #1D4ED8;
        color: white;
        box-shadow: 0 4px 12px rgba(29,78,216,0.3);
        border-color: transparent;
    }

    .stat-badge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 9px; border-radius: 20px;
        font-size: 11px; font-weight: 700;
    }
    .badge-hadir     { background:#DCFCE7; color:#166534; }
    .badge-terlambat { background:#FEF9C3; color:#854D0E; }
    .badge-izin      { background:#DBEAFE; color:#1E40AF; }
    .badge-alpa      { background:#FEE2E2; color:#991B1B; }

    .status-pill {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 4px 10px; border-radius: 20px;
        font-size: 11px; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.04em;
    }
    .status-hadir     { background:#DCFCE7; color:#166534; }
    .status-terlambat { background:#FEF9C3; color:#854D0E; }
    .status-izin      { background:#DBEAFE; color:#1E40AF; }
    .status-alpa      { background:#FEE2E2; color:#991B1B; }

    .tbl-row { transition: background 0.15s ease; }
    .tbl-row:hover { background: #F8FAFC; }

    .btn-action {
        width:30px; height:30px; border-radius:8px;
        display:inline-flex; align-items:center; justify-content:center;
        transition:all 0.15s ease; cursor:pointer; border:none;
    }
    .btn-edit   { background:#EFF6FF; color:#1D4ED8; }
    .btn-edit:hover  { background:#DBEAFE; }
    .btn-del    { background:#FEF2F2; color:#DC2626; }
    .btn-del:hover   { background:#FEE2E2; }
    .btn-detail { background:#F0FDF4; color:#16A34A; }
    .btn-detail:hover { background:#DCFCE7; }

    /* Modal */
    .modal-backdrop {
        position:fixed; inset:0;
        background:rgba(15,23,42,0.55);
        backdrop-filter:blur(6px);
        z-index:1000; display:flex;
        align-items:center; justify-content:center;
        padding:1rem; opacity:0;
        transition:opacity 0.25s ease;
    }
    .modal-backdrop.show { opacity:1; }
    .modal-box {
        background:white; border-radius:20px;
        width:100%; max-width:520px;
        box-shadow:0 32px 80px rgba(0,0,0,0.2);
        transform:translateY(24px) scale(0.97);
        transition:transform 0.3s cubic-bezier(0.34,1.56,0.64,1), opacity 0.25s ease;
        opacity:0; max-height:90vh; overflow-y:auto;
    }
    .modal-backdrop.show .modal-box { transform:translateY(0) scale(1); opacity:1; }

    /* Realtime toast */
    #realtimeToast {
        position:fixed; bottom:24px; right:24px;
        z-index:9999; display:flex;
        flex-direction:column; gap:10px; pointer-events:none;
    }
    .toast-item {
        background:white; border-left:4px solid #1D4ED8;
        border-radius:12px; padding:12px 16px;
        box-shadow:0 8px 32px rgba(0,0,0,0.12);
        font-size:13px; font-weight:600; color:#1E3A8A;
        display:flex; align-items:center; gap:10px;
        animation:slideInToast 0.4s cubic-bezier(0.34,1.56,0.64,1) forwards;
        min-width:280px;
    }
    .toast-item.leaving { animation:slideOutToast 0.3s ease forwards; }
    @keyframes slideInToast { from{transform:translateX(100px);opacity:0} to{transform:translateX(0);opacity:1} }
    @keyframes slideOutToast { from{transform:translateX(0);opacity:1} to{transform:translateX(100px);opacity:0} }

    .realtime-dot {
        width:8px; height:8px; border-radius:50%;
        background:#22C55E; animation:blink 1.5s infinite; flex-shrink:0;
    }
    @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0.3} }

    .progress-bar-outer { height:6px; background:#E2E8F0; border-radius:10px; overflow:hidden; }
    .progress-bar-inner { height:100%; border-radius:10px; transition:width 0.8s ease; }

    @keyframes fadeIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }
    .fade-in { animation:fadeIn 0.35s ease forwards; }

    .skeleton {
        background:linear-gradient(90deg,#F1F5F9 25%,#E2E8F0 50%,#F1F5F9 75%);
        background-size:200% 100%;
        animation:shimmer 1.5s infinite; border-radius:8px;
    }
    @keyframes shimmer { 0%{background-position:200% 0} 100%{background-position:-200% 0} }

    @keyframes spin { to { transform:rotate(360deg); } }
    .animate-spin { animation:spin 0.7s linear infinite; display:inline-block; }
</style>

<div id="realtimeToast"></div>

{{-- ========================================================
     ALERT SESSION
     ======================================================== --}}
@if(session('success'))
<div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm font-semibold mb-4">
    <i class='bx bxs-check-circle text-emerald-500 text-lg'></i>{{ session('success') }}
</div>
@endif
@if(session('error') || ($error ?? false))
<div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm font-semibold mb-4">
    <i class='bx bxs-error-circle text-red-500 text-lg'></i>{{ session('error') ?? $error }}
</div>
@endif

<div class="space-y-5">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 flex items-center gap-2">
                <i class='bx bxs-calendar-check text-blue-600'></i>
                Manajemen Absensi
            </h1>
            <p class="text-slate-500 text-sm mt-0.5">Monitor dan kelola data kehadiran siswa per kelas &amp; semester</p>
        </div>
        <div class="flex items-center gap-2">
            <div class="flex items-center gap-2 text-xs bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl px-3 py-2 font-semibold">
                <span class="realtime-dot"></span>Live Realtime
            </div>
            <button onclick="exportPDF()"
                class="flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-all shadow-sm">
                <i class='bx bxs-file-pdf text-base'></i>Export PDF
            </button>
        </div>
    </div>

    {{-- KELAS CARDS --}}
    <div class="grid grid-cols-3 gap-4">
        @foreach([
            ['kelas'=>'X',   'color'=>'from-blue-500 to-blue-700',   'icon'=>'bxs-school'],
            ['kelas'=>'XI',  'color'=>'from-indigo-500 to-indigo-700','icon'=>'bxs-book-open'],
            ['kelas'=>'XII', 'color'=>'from-purple-500 to-purple-700','icon'=>'bxs-graduation'],
        ] as $k)
        <div class="kelas-card bg-white rounded-2xl p-5 shadow-sm border border-slate-100"
             data-kelas="{{ $k['kelas'] }}"
             onclick="pilihKelas('{{ $k['kelas'] }}')">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br {{ $k['color'] }} flex items-center justify-center shadow-md">
                    <i class='bx {{ $k['icon'] }} text-white text-xl'></i>
                </div>
                <span class="text-xs font-semibold text-slate-400 bg-slate-100 rounded-lg px-2 py-1">Klik pilih</span>
            </div>
            <div class="kelas-num text-3xl font-black text-slate-800 leading-none">{{ $k['kelas'] }}</div>
            <div class="kelas-label text-sm font-semibold text-slate-500 mt-1">Kelas {{ $k['kelas'] }}</div>
            <div class="mt-3 text-xs text-slate-400" id="count-kelas-{{ $k['kelas'] }}">— siswa</div>
        </div>
        @endforeach
    </div>

    {{-- FILTER BAR --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
        <div class="flex flex-wrap items-center gap-4">
            <div class="flex items-center gap-2">
                <span class="text-sm font-semibold text-slate-600 mr-1">Semester:</span>
                <button class="semester-btn active text-sm font-semibold px-4 py-2 rounded-xl border border-slate-200 bg-slate-50 text-slate-600"
                        data-semester="1" onclick="pilihSemester(1)">S1 — Jul–Des</button>
                <button class="semester-btn text-sm font-semibold px-4 py-2 rounded-xl border border-slate-200 bg-slate-50 text-slate-600"
                        data-semester="2" onclick="pilihSemester(2)">S2 — Jan–Jun</button>
            </div>
            <div class="h-6 w-px bg-slate-200 hidden sm:block"></div>
            <div class="flex items-center gap-2">
                <span class="text-sm font-semibold text-slate-600">Tanggal:</span>
                <input type="date" id="filterTanggal"
                    class="text-sm border border-slate-200 rounded-xl px-3 py-2 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
                <button onclick="clearTanggal()" class="text-xs text-slate-400 hover:text-slate-600 transition-colors">Reset</button>
            </div>
            <div class="h-6 w-px bg-slate-200 hidden sm:block"></div>
            <div class="flex items-center gap-2">
                <span class="text-sm font-semibold text-slate-600">Status:</span>
                <select id="filterStatus"
                    class="text-sm border border-slate-200 rounded-xl px-3 py-2 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
                    <option value="">Semua</option>
                    <option value="hadir">Hadir</option>
                    <option value="terlambat">Terlambat</option>
                    <option value="izin">Izin</option>
                    <option value="alpa">Alpa</option>
                </select>
            </div>
            <div class="ml-auto flex items-center gap-2">
                <button onclick="applyFilters()"
                    class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-xl transition-all">
                    <i class='bx bx-filter-alt'></i>Filter
                </button>
                <button onclick="resetFilter()"
                    class="flex items-center gap-2 border border-slate-200 text-slate-600 text-sm font-semibold px-4 py-2 rounded-xl hover:bg-slate-50 transition-all">
                    <i class='bx bx-reset'></i>Reset
                </button>
            </div>
        </div>
    </div>

    {{-- STAT CARDS --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach([
            ['id'=>'stat-hadir',     'label'=>'Total Hadir',   'icon'=>'bxs-check-circle', 'bg'=>'bg-emerald-50','text'=>'text-emerald-600','ring'=>'ring-emerald-100','bar'=>'bg-emerald-500'],
            ['id'=>'stat-terlambat', 'label'=>'Terlambat',     'icon'=>'bxs-time-five',    'bg'=>'bg-amber-50',  'text'=>'text-amber-600',  'ring'=>'ring-amber-100',  'bar'=>'bg-amber-400'],
            ['id'=>'stat-izin',      'label'=>'Izin / Sakit',  'icon'=>'bxs-envelope',     'bg'=>'bg-blue-50',   'text'=>'text-blue-600',   'ring'=>'ring-blue-100',   'bar'=>'bg-blue-500'],
            ['id'=>'stat-alpa',      'label'=>'Alpa',          'icon'=>'bxs-x-circle',     'bg'=>'bg-red-50',    'text'=>'text-red-600',    'ring'=>'ring-red-100',    'bar'=>'bg-red-500'],
        ] as $s)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 card-stat">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl {{ $s['bg'] }} ring-1 {{ $s['ring'] }} flex items-center justify-center">
                    <i class='bx {{ $s['icon'] }} {{ $s['text'] }} text-xl'></i>
                </div>
                <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-widest">{{ $s['label'] }}</span>
            </div>
            <div class="text-3xl font-black text-slate-800" id="{{ $s['id'] }}">—</div>
            <div class="mt-2 progress-bar-outer">
                <div class="progress-bar-inner {{ $s['bar'] }}" id="{{ $s['id'] }}-bar" style="width:0%"></div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- TABEL ABSENSI --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden" id="tableSection">
        <div class="px-5 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-bold text-slate-800 flex items-center gap-2">
                    <i class='bx bx-list-ul text-blue-600'></i>
                    <span id="tableTitle">Pilih kelas untuk menampilkan data</span>
                </h2>
                <p class="text-xs text-slate-400 mt-0.5" id="tableSubtitle">Klik salah satu kartu kelas di atas</p>
            </div>
            <div class="flex items-center gap-2">
                <div class="relative">
                    <i class='bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm'></i>
                    <input type="text" id="searchInput" oninput="searchTable()" placeholder="Cari nama siswa..."
                        class="pl-8 pr-4 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all w-52">
                </div>
                <span class="text-xs text-slate-500 font-semibold" id="totalRows">0 data</span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm" id="absensiTable">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="text-left px-4 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider w-8">#</th>
                        <th class="text-left px-4 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Siswa</th>
                        <th class="text-left px-4 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal</th>
                        <th class="text-left px-4 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Masuk</th>
                        <th class="text-left px-4 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Pulang</th>
                        <th class="text-left px-4 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="text-left px-4 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Metode</th>
                        <th class="text-left px-4 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Keterangan</th>
                        <th class="text-center px-4 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <tr>
                        <td colspan="9" class="text-center py-16 text-slate-400">
                            <i class='bx bxs-calendar-check text-4xl block mb-2 text-slate-200'></i>
                            <span class="text-sm font-semibold">Pilih kelas untuk memuat data absensi</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4 border-t border-slate-100 flex items-center justify-between" id="paginationSection">
            <p class="text-xs text-slate-500" id="paginationInfo"></p>
            <div class="flex items-center gap-1" id="paginationButtons"></div>
        </div>
    </div>

    {{-- REKAP PER SISWA --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center gap-3">
            <h2 class="font-bold text-slate-800 flex items-center gap-2">
                <i class='bx bxs-bar-chart-alt-2 text-blue-600'></i>
                Rekap Absensi per Siswa
            </h2>
            <span class="text-xs font-semibold bg-blue-100 text-blue-700 px-2 py-0.5 rounded-lg" id="siswaStatsKelas"></span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="text-left px-4 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Siswa</th>
                        <th class="text-left px-4 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Kelas</th>
                        <th class="text-center px-4 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Hadir</th>
                        <th class="text-center px-4 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Terlambat</th>
                        <th class="text-center px-4 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Izin</th>
                        <th class="text-center px-4 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Alpa</th>
                        <th class="text-center px-4 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Total</th>
                        <th class="text-center px-4 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">% Hadir</th>
                    </tr>
                </thead>
                <tbody id="siswaStatsBody">
                    <tr><td colspan="8" class="text-center py-10 text-slate-400 text-sm">Pilih kelas untuk menampilkan rekap</td></tr>
                </tbody>
            </table>
        </div>
    </div>

</div>


{{-- ===================== MODAL DETAIL ===================== --}}
<div class="modal-backdrop" id="modalDetail" style="display:none">
    <div class="modal-box">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <i class='bx bxs-detail text-blue-600'></i> Detail Absensi
            </h3>
            <button onclick="closeModal('modalDetail')" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-500 transition-all">
                <i class='bx bx-x text-lg'></i>
            </button>
        </div>
        <div class="p-6" id="modalDetailContent">
            <div class="space-y-3"><div class="skeleton h-5 w-3/4"></div><div class="skeleton h-5 w-1/2"></div></div>
        </div>
    </div>
</div>

{{-- ===================== MODAL EDIT ===================== --}}
<div class="modal-backdrop" id="modalEdit" style="display:none">
    <div class="modal-box">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <i class='bx bxs-edit text-blue-600'></i> Edit Absensi
            </h3>
            <button onclick="closeModal('modalEdit')" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-500 transition-all">
                <i class='bx bx-x text-lg'></i>
            </button>
        </div>
        <div class="p-6">
            <input type="hidden" id="editId">
            <div class="mb-4 p-3 bg-slate-50 rounded-xl border border-slate-100">
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider mb-1">Siswa</p>
                <p class="font-semibold text-slate-800" id="editNama">—</p>
                <p class="text-xs text-slate-400" id="editInfo">—</p>
            </div>
            <div class="mb-4">
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Status Absensi</label>
                <select id="editStatus" required
                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                    <option value="hadir">✅ Hadir</option>
                    <option value="terlambat">⏰ Terlambat</option>
                    <option value="izin">📋 Izin / Sakit</option>
                    <option value="alpa">❌ Alpa</option>
                </select>
            </div>
            <div class="mb-5">
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Keterangan</label>
                <textarea id="editKeterangan" rows="3" placeholder="Keterangan tambahan..."
                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all resize-none"></textarea>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('modalEdit')"
                    class="flex-1 py-3 border border-slate-200 text-slate-600 font-semibold rounded-xl hover:bg-slate-50 transition-all text-sm">Batal</button>
                <button type="button" onclick="submitEdit()" id="btnSimpanEdit"
                    class="flex-1 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all text-sm flex items-center justify-center gap-2 shadow-sm">
                    <i class='bx bxs-save'></i> Simpan
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ===================== MODAL HAPUS ===================== --}}
<div class="modal-backdrop" id="modalHapus" style="display:none">
    <div class="modal-box" style="max-width:400px">
        <div class="p-6 text-center">
            <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                <i class='bx bxs-trash text-red-500 text-3xl'></i>
            </div>
            <h3 class="font-bold text-slate-800 text-lg mb-2">Hapus Absensi?</h3>
            <p class="text-slate-500 text-sm mb-1">Data berikut akan dihapus permanen:</p>
            <p class="font-semibold text-slate-800 text-sm mb-6" id="hapusInfo">—</p>
            <div class="flex gap-3">
                <button onclick="closeModal('modalHapus')"
                    class="flex-1 py-3 border border-slate-200 text-slate-600 font-semibold rounded-xl hover:bg-slate-50 transition-all text-sm">Batal</button>
                <button onclick="submitHapus()" id="btnHapus"
                    class="flex-1 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl transition-all text-sm flex items-center justify-center gap-2">
                    <i class='bx bx-trash'></i> Ya, Hapus
                </button>
            </div>
        </div>
    </div>
</div>


{{-- ===================== JAVASCRIPT ===================== --}}
<script>
// ============================================================
// CONFIG — token & API base diambil dari Blade (server-side)
// Ini yang menyebabkan error sebelumnya: fetch ke route web
// Sekarang fetch langsung ke backend API dengan bearer token
// ============================================================
const ADMIN_TOKEN = '{{ session('token', '') }}';
const API_BASE    = '{{ rtrim(env('ABSENSI_API_URL', 'http://127.0.0.1:8000/api'), '/') }}';
const CSRF        = '{{ csrf_token() }}';

// Route web (untuk PUT/DELETE via form submit jika perlu)
const WEB_BASE    = '{{ url('/admin/absensi') }}';

// ============================================================
// STATE
// ============================================================
let state = {
    kelas: null,
    semester: 1,
    status: '',
    tanggal: '',
    search: '',
    page: 1,
    allData: [],
    filteredData: [],
    hapusId: null,
};

// Auto-detect semester
(function() {
    const m = new Date().getMonth() + 1;
    state.semester = (m >= 7) ? 1 : 2;
    document.querySelectorAll('.semester-btn').forEach(b => {
        b.classList.toggle('active', parseInt(b.dataset.semester) === state.semester);
    });
})();

// ============================================================
// API FETCH HELPER — gunakan backend API + bearer token
// ============================================================
async function apiFetch(path, options = {}) {
    const url = `${API_BASE}${path}`;
    const defaultHeaders = {
        'Accept':        'application/json',
        'Content-Type':  'application/json',
        'Authorization': `Bearer ${ADMIN_TOKEN}`,
        'X-CSRF-TOKEN':  CSRF,
    };
    const resp = await fetch(url, {
        ...options,
        headers: { ...defaultHeaders, ...(options.headers || {}) },
    });
    if (resp.status === 401) {
        showToast('⚠️ Sesi berakhir, silakan login ulang', 'error');
        setTimeout(() => window.location.href = '/admin/login', 1500);
        throw new Error('Unauthorized');
    }
    return resp;
}

// ============================================================
// PILIH KELAS
// ============================================================
function pilihKelas(kelas) {
    state.kelas = kelas;
    state.page  = 1;
    document.querySelectorAll('.kelas-card').forEach(c => c.classList.remove('active'));
    document.querySelector(`[data-kelas="${kelas}"]`).classList.add('active');
    loadAbsensi();
    loadStatistik();
}

function pilihSemester(s) {
    state.semester = s;
    document.querySelectorAll('.semester-btn').forEach(b => {
        b.classList.toggle('active', parseInt(b.dataset.semester) === s);
    });
    if (state.kelas) { state.page = 1; loadAbsensi(); loadStatistik(); }
}

// ============================================================
// SEMESTER RANGE
// ============================================================
function getSemesterRange(semester) {
    const now   = new Date();
    const month = now.getMonth() + 1;
    const year  = now.getFullYear();
    if (semester === 1) {
        const y = (month >= 7) ? year : year - 1;
        return { mulai: `${y}-07-01`, selesai: `${y}-12-31`, label: `Jul–Des ${y}` };
    }
    const y = (month >= 7) ? year + 1 : year;
    return { mulai: `${y}-01-01`, selesai: `${y}-06-30`, label: `Jan–Jun ${y}` };
}

// ============================================================
// LOAD ABSENSI — fetch ke backend API
// ============================================================
async function loadAbsensi() {
    if (!state.kelas) return;
    showTableLoading();

    const params = new URLSearchParams({ kelas: state.kelas, per_page: 500 });
    if (state.status)  params.append('status',  state.status);
    if (state.tanggal) params.append('tanggal', state.tanggal);

    try {
        const resp = await apiFetch(`/admin/absensi?${params}`);
        const json = await resp.json();
        console.log(json);

        // Handle paginated atau array biasa
        let raw = [];
        if (json.data && json.data.data && Array.isArray(json.data.data)) {
            raw = json.data.data; // Laravel paginate
        } else if (json.data && Array.isArray(json.data)) {
            raw = json.data;
        } else if (Array.isArray(json)) {
            raw = json;
        }

        // Filter semester di FE
        raw = raw.filter(a => {
            if (!a.tanggal) return false;
            const m = new Date(a.tanggal).getMonth() + 1;
            return state.semester === 1 ? (m >= 7 && m <= 12) : (m >= 1 && m <= 6);
        });

        state.allData = raw;
        applySearch();

    } catch (err) {
        if (err.message !== 'Unauthorized') {
            console.error('loadAbsensi error:', err);
            showTableError('Gagal memuat data. Periksa koneksi ke server API.');
        }
    }
}

// ============================================================
// LOAD STATISTIK PER SISWA
// ============================================================
async function loadStatistik() {
    if (!state.kelas) return;
    try {
        const resp = await apiFetch('/admin/absensi/statistik');
        const json = await resp.json();
        const data = (json.data || []).filter(s => s.kelas && s.kelas.startsWith(state.kelas));
        renderSiswaStats(data);
        updateCountKelas(data.length);
    } catch(e) {
        if (e.message !== 'Unauthorized') console.error('loadStatistik error:', e);
    }
}

// ============================================================
// FILTERS
// ============================================================
function applyFilters() {
    state.status  = document.getElementById('filterStatus').value;
    state.tanggal = document.getElementById('filterTanggal').value;
    state.page    = 1;
    if (state.kelas) loadAbsensi();
}
function resetFilter() {
    document.getElementById('filterStatus').value  = '';
    document.getElementById('filterTanggal').value = '';
    state.status = ''; state.tanggal = ''; state.page = 1;
    if (state.kelas) loadAbsensi();
}
function clearTanggal() {
    document.getElementById('filterTanggal').value = '';
    state.tanggal = '';
    if (state.kelas) loadAbsensi();
}

// ============================================================
// SEARCH
// ============================================================
function searchTable() {
    state.search = document.getElementById('searchInput').value.toLowerCase();
    state.page = 1;
    applySearch();
}
function applySearch() {
    if (!state.search) {
        state.filteredData = state.allData;
    } else {
        state.filteredData = state.allData.filter(a => {
            const nama  = (a.siswa?.name  || a.user?.name  || '').toLowerCase();
            const kelas = (a.siswa?.kelas || '').toLowerCase();
            const nisn  = (a.siswa?.nisn  || '').toLowerCase();
            return nama.includes(state.search) || kelas.includes(state.search) || nisn.includes(state.search);
        });
    }
    renderTable();
    updateStats();
}

// ============================================================
// RENDER TABLE
// ============================================================
const PER_PAGE = 20;

function renderTable() {
    const tbody    = document.getElementById('tableBody');
    const total    = state.filteredData.length;
    const start    = (state.page - 1) * PER_PAGE;
    const end      = Math.min(start + PER_PAGE, total);
    const pageData = state.filteredData.slice(start, end);

    document.getElementById('totalRows').textContent = `${total} data`;
    const range = getSemesterRange(state.semester);
    document.getElementById('tableTitle').textContent    = `Absensi Kelas ${state.kelas} — Semester ${state.semester} (${range.label})`;
    document.getElementById('tableSubtitle').textContent = `${total} record`;

    if (!pageData.length) {
        tbody.innerHTML = `<tr><td colspan="9" class="text-center py-12 text-slate-400">
            <i class='bx bxs-inbox text-4xl block mb-2 text-slate-200'></i>
            <span class="text-sm font-semibold">Tidak ada data untuk filter ini</span>
        </td></tr>`;
        renderPagination(0, 0); return;
    }

    const colors = ['#EFF6FF','#F0FDF4','#FFF7ED','#FDF4FF'];
    const txts   = ['#1D4ED8','#16A34A','#EA580C','#9333EA'];

    tbody.innerHTML = pageData.map((a, i) => {
        const nama   = a.siswa?.name  || a.user?.name  || '—';
        const kelas  = a.siswa?.kelas || '—';
        const nisn   = a.siswa?.nisn  || '—';
        const tgl    = a.tanggal ? formatTanggal(a.tanggal) : '—';
        const masuk  = a.jam_masuk  ? (a.jam_masuk  + '').substring(0,5) : '—';
        const pulang = a.jam_pulang ? (a.jam_pulang + '').substring(0,5) : '—';
        const ket    = (a.keterangan || '').substring(0, 30) + ((a.keterangan||'').length > 30 ? '…' : '');
        const metode = a.media?.tipe === 'barcode'
            ? '<span class="stat-badge" style="background:#EDE9FE;color:#5B21B6"><i class="bx bx-qr" style="font-size:11px"></i>Barcode</span>'
            : a.media?.tipe === 'camera'
                ? '<span class="stat-badge" style="background:#E0F2FE;color:#0369A1"><i class="bx bx-camera" style="font-size:11px"></i>Kamera</span>'
                : '<span class="text-slate-300 text-xs">—</span>';
        const initials = nama.split(' ').map(n=>n[0]||'').join('').substring(0,2).toUpperCase();
        const ci = (start + i) % 4;

        return `<tr class="tbl-row border-b border-slate-50 fade-in" data-id="${a.id}">
            <td class="px-4 py-3 text-xs font-semibold text-slate-400">${start+i+1}</td>
            <td class="px-4 py-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold flex-shrink-0"
                         style="background:${colors[ci]};color:${txts[ci]}">${initials}</div>
                    <div>
                        <p class="font-semibold text-slate-800 text-sm leading-tight">${nama}</p>
                        <p class="text-xs text-slate-400">${kelas} · NISN ${nisn}</p>
                    </div>
                </div>
            </td>
            <td class="px-4 py-3 text-sm text-slate-600 whitespace-nowrap">${tgl}</td>
            <td class="px-4 py-3 text-sm font-mono font-semibold ${masuk!=='—'?'text-emerald-700':'text-slate-300'}">${masuk}</td>
            <td class="px-4 py-3 text-sm font-mono font-semibold ${pulang!=='—'?'text-blue-700':'text-slate-300'}">${pulang}</td>
            <td class="px-4 py-3"><span class="status-pill status-${a.status}">${a.status}</span></td>
            <td class="px-4 py-3">${metode}</td>
            <td class="px-4 py-3 text-xs text-slate-500 max-w-[140px] truncate" title="${a.keterangan||''}">${ket||'—'}</td>
            <td class="px-4 py-3">
                <div class="flex items-center justify-center gap-1">
                    <button class="btn-action btn-detail" onclick="showDetail(${a.id})" title="Detail"><i class='bx bx-show text-sm'></i></button>
                    <button class="btn-action btn-edit"   onclick="openEdit(${a.id})"   title="Edit"><i class='bx bx-edit text-sm'></i></button>
                    <button class="btn-action btn-del"    onclick="openHapus(${a.id},'${nama.replace(/'/g,"\\'")}','${tgl}')" title="Hapus"><i class='bx bx-trash text-sm'></i></button>
                </div>
            </td>
        </tr>`;
    }).join('');

    renderPagination(total, state.page);
}

// ============================================================
// PAGINATION
// ============================================================
function renderPagination(total, current) {
    const totalPages = Math.ceil(total / PER_PAGE);
    const info = document.getElementById('paginationInfo');
    const btns = document.getElementById('paginationButtons');
    if (!total) { info.textContent = ''; btns.innerHTML = ''; return; }
    const s = (current-1)*PER_PAGE+1, e = Math.min(current*PER_PAGE, total);
    info.textContent = `Menampilkan ${s}–${e} dari ${total} data`;
    let html = '';
    for (let p = 1; p <= totalPages; p++) {
        if (p === current) {
            html += `<button class="w-8 h-8 rounded-lg bg-blue-600 text-white text-xs font-bold" disabled>${p}</button>`;
        } else if (p===1||p===totalPages||Math.abs(p-current)<=1) {
            html += `<button class="w-8 h-8 rounded-lg border border-slate-200 text-slate-600 text-xs font-semibold hover:bg-slate-50" onclick="goPage(${p})">${p}</button>`;
        } else if (Math.abs(p-current)===2) {
            html += `<span class="text-slate-300 text-xs px-1">…</span>`;
        }
    }
    btns.innerHTML = html;
}
function goPage(p) {
    state.page = p;
    renderTable();
    document.getElementById('tableSection').scrollIntoView({ behavior:'smooth', block:'nearest' });
}

// ============================================================
// STATS CARDS
// ============================================================
function updateStats() {
    const data = state.filteredData;
    const total = data.length || 1;
    const counts = { hadir:0, terlambat:0, izin:0, alpa:0 };
    data.forEach(a => { if (counts[a.status]!==undefined) counts[a.status]++; });
    ['hadir','terlambat','izin','alpa'].forEach(k => {
        const el  = document.getElementById(`stat-${k}`);
        const bar = document.getElementById(`stat-${k}-bar`);
        if (el)  el.textContent  = counts[k];
        if (bar) bar.style.width = Math.round((counts[k]/total)*100) + '%';
    });
}
function updateCountKelas(n) {
    if (!state.kelas) return;
    const el = document.getElementById(`count-kelas-${state.kelas}`);
    if (el) el.textContent = `${n} siswa terdaftar`;
}

// ============================================================
// SISWA STATS TABLE
// ============================================================
function renderSiswaStats(data) {
    const tbody = document.getElementById('siswaStatsBody');
    const badge = document.getElementById('siswaStatsKelas');
    if (badge) badge.textContent = `Kelas ${state.kelas}`;
    if (!data.length) {
        tbody.innerHTML = `<tr><td colspan="8" class="text-center py-8 text-slate-400 text-sm">Tidak ada data</td></tr>`;
        return;
    }
    tbody.innerHTML = data.map((s, i) => {
        const total = (s.hadir||0)+(s.terlambat||0)+(s.izin||0)+(s.alpa||0);
        const pct   = total > 0 ? Math.round(((s.hadir+s.terlambat)/total)*100) : 0;
        const pctCls = pct>=80 ? 'text-emerald-700 bg-emerald-50' : pct>=60 ? 'text-amber-700 bg-amber-50' : 'text-red-700 bg-red-50';
        return `<tr class="tbl-row border-b border-slate-50">
            <td class="px-4 py-3"><div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-500">${i+1}</div>
                <span class="font-semibold text-slate-800 text-sm">${s.nama||'—'}</span>
            </div></td>
            <td class="px-4 py-3 text-xs text-slate-500 font-semibold">${s.kelas||'—'}${s.jurusan?' · '+s.jurusan:''}</td>
            <td class="px-4 py-3 text-center"><span class="stat-badge badge-hadir">${s.hadir||0}</span></td>
            <td class="px-4 py-3 text-center"><span class="stat-badge badge-terlambat">${s.terlambat||0}</span></td>
            <td class="px-4 py-3 text-center"><span class="stat-badge badge-izin">${s.izin||0}</span></td>
            <td class="px-4 py-3 text-center"><span class="stat-badge badge-alpa">${s.alpa||0}</span></td>
            <td class="px-4 py-3 text-center text-sm font-bold text-slate-700">${total}</td>
            <td class="px-4 py-3 text-center"><span class="stat-badge ${pctCls}">${pct}%</span></td>
        </tr>`;
    }).join('');
}

// ============================================================
// TABLE HELPERS
// ============================================================
function showTableLoading() {
    document.getElementById('tableBody').innerHTML =
        Array(5).fill(0).map(() => `<tr class="border-b border-slate-50">
            ${Array(9).fill(0).map(()=>`<td class="px-4 py-4"><div class="skeleton h-4 w-full"></div></td>`).join('')}
        </tr>`).join('');
}
function showTableError(msg) {
    document.getElementById('tableBody').innerHTML =
        `<tr><td colspan="9" class="text-center py-12 text-red-400">
            <i class='bx bxs-error-circle text-3xl block mb-2'></i>
            <span class="text-sm font-semibold">${msg}</span>
        </td></tr>`;
}
function formatTanggal(str) {
    const d = new Date(str);
    const days   = ['Min','Sen','Sel','Rab','Kam','Jum','Sab'];
    const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];
    return `${days[d.getDay()]}, ${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear()}`;
}

// ============================================================
// DETAIL MODAL — fetch ke API
// ============================================================
async function showDetail(id) {
    openModal('modalDetail');
    document.getElementById('modalDetailContent').innerHTML =
        `<div class="space-y-3"><div class="skeleton h-5 w-3/4"></div><div class="skeleton h-5 w-1/2"></div><div class="skeleton h-5 w-2/3"></div></div>`;
    try {
        const resp = await apiFetch(`/admin/absensi/${id}`);
        const json = await resp.json();
        const a    = json.data;
        const fotoM = a.media?.media_url
            ? `<div class="mt-3"><p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">📸 Foto Masuk</p><img src="${a.media.media_url}" class="w-full rounded-xl object-cover max-h-52"></div>` : '';
        const fotoP = a.media_pulang?.media_url_pulang
            ? `<div class="mt-3"><p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">📸 Foto Pulang</p><img src="${a.media_pulang.media_url_pulang}" class="w-full rounded-xl object-cover max-h-52"></div>` : '';

        document.getElementById('modalDetailContent').innerHTML = `
        <div class="space-y-4">
            <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-xl border border-slate-100">
                <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center text-blue-700 font-black text-lg flex-shrink-0">
                    ${(a.siswa?.name||a.user?.name||'?').substring(0,2).toUpperCase()}
                </div>
                <div>
                    <p class="font-bold text-slate-800">${a.siswa?.name||a.user?.name||'—'}</p>
                    <p class="text-xs text-slate-400">${a.siswa?.kelas||'—'} · NISN ${a.siswa?.nisn||'—'} · ${a.siswa?.jurusan||'—'}</p>
                </div>
                <span class="status-pill status-${a.status} ml-auto">${a.status}</span>
            </div>
            <div class="grid grid-cols-2 gap-3 text-sm">
                <div class="p-3 bg-slate-50 rounded-xl">
                    <p class="text-xs text-slate-400 font-semibold mb-1">Tanggal</p>
                    <p class="font-semibold text-slate-800">${a.tanggal ? formatTanggal(a.tanggal) : '—'}</p>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl">
                    <p class="text-xs text-slate-400 font-semibold mb-1">Metode</p>
                    <p class="font-semibold text-slate-800 capitalize">${a.media?.tipe||'—'}</p>
                </div>
                <div class="p-3 bg-emerald-50 rounded-xl">
                    <p class="text-xs text-emerald-600 font-semibold mb-1">⬆ Jam Masuk</p>
                    <p class="font-bold text-emerald-800">${a.jam_masuk ? (a.jam_masuk+'').substring(0,5) : '—'}</p>
                    ${a.lat_masuk ? `<p class="text-xs text-slate-400 mt-1">${parseFloat(a.lat_masuk).toFixed(5)}, ${parseFloat(a.lng_masuk).toFixed(5)}</p>` : ''}
                </div>
                <div class="p-3 bg-blue-50 rounded-xl">
                    <p class="text-xs text-blue-600 font-semibold mb-1">⬇ Jam Pulang</p>
                    <p class="font-bold text-blue-800">${a.jam_pulang ? (a.jam_pulang+'').substring(0,5) : '—'}</p>
                    ${a.lat_pulang ? `<p class="text-xs text-slate-400 mt-1">${parseFloat(a.lat_pulang).toFixed(5)}, ${parseFloat(a.lng_pulang).toFixed(5)}</p>` : ''}
                </div>
            </div>
            ${a.keterangan ? `<div class="p-3 bg-amber-50 border border-amber-100 rounded-xl">
                <p class="text-xs text-amber-600 font-semibold mb-1">📝 Keterangan</p>
                <p class="text-sm text-amber-800">${a.keterangan}</p>
            </div>` : ''}
            ${fotoM}${fotoP}
        </div>`;
    } catch(e) {
        if (e.message !== 'Unauthorized')
            document.getElementById('modalDetailContent').innerHTML = `<p class="text-red-500 text-sm">Gagal memuat detail.</p>`;
    }
}

// ============================================================
// EDIT — fetch ke API
// ============================================================
function openEdit(id) {
    const a = state.allData.find(x => x.id === id);
    if (!a) return;
    document.getElementById('editId').value           = id;
    document.getElementById('editNama').textContent   = a.siswa?.name || a.user?.name || '—';
    document.getElementById('editInfo').textContent   = `${a.siswa?.kelas||'—'} · ${a.tanggal ? formatTanggal(a.tanggal) : '—'}`;
    document.getElementById('editStatus').value       = a.status;
    document.getElementById('editKeterangan').value   = a.keterangan || '';
    openModal('modalEdit');
}

async function submitEdit() {
    const id  = document.getElementById('editId').value;
    const btn = document.getElementById('btnSimpanEdit');
    btn.disabled = true;
    btn.innerHTML = `<i class='bx bx-loader-alt animate-spin'></i> Menyimpan...`;

    try {
        const resp = await apiFetch(`/admin/absensi/${id}`, {
            method: 'PUT',
            body: JSON.stringify({
                status:     document.getElementById('editStatus').value,
                keterangan: document.getElementById('editKeterangan').value,
            }),
        });
        const json = await resp.json();
        if (resp.ok) {
            closeModal('modalEdit');
            showToast('✅ Absensi berhasil diperbarui', 'success');
            // Update cache lokal
            const idx = state.allData.findIndex(x => x.id == id);
            if (idx >= 0) {
                state.allData[idx].status     = document.getElementById('editStatus').value;
                state.allData[idx].keterangan = document.getElementById('editKeterangan').value;
            }
            applySearch();
        } else {
            showToast(json.message || 'Gagal menyimpan', 'error');
        }
    } catch(err) {
        if (err.message !== 'Unauthorized') showToast('Terjadi kesalahan koneksi', 'error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = `<i class='bx bxs-save'></i> Simpan`;
    }
}

// ============================================================
// HAPUS — fetch ke API
// ============================================================
function openHapus(id, nama, tgl) {
    state.hapusId = id;
    document.getElementById('hapusInfo').textContent = `${nama} — ${tgl}`;
    openModal('modalHapus');
}

async function submitHapus() {
    const id  = state.hapusId;
    const btn = document.getElementById('btnHapus');
    btn.disabled = true;
    btn.innerHTML = `<i class='bx bx-loader-alt animate-spin'></i> Menghapus...`;

    try {
        const resp = await apiFetch(`/admin/absensi/${id}`, { method: 'DELETE' });
        const json = await resp.json();
        if (resp.ok) {
            closeModal('modalHapus');
            showToast(json.message || '🗑 Absensi berhasil dihapus', 'success');
            state.allData = state.allData.filter(x => x.id != id);
            applySearch();
        } else {
            showToast(json.message || 'Gagal menghapus', 'error');
        }
    } catch(err) {
        if (err.message !== 'Unauthorized') showToast('Terjadi kesalahan koneksi', 'error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = `<i class='bx bx-trash'></i> Ya, Hapus`;
    }
}

// ============================================================
// MODAL HELPERS
// ============================================================
function openModal(id) {
    const el = document.getElementById(id);
    el.style.display = 'flex';
    requestAnimationFrame(() => el.classList.add('show'));
    document.body.style.overflow = 'hidden';
}
function closeModal(id) {
    const el = document.getElementById(id);
    el.classList.remove('show');
    setTimeout(() => { el.style.display = 'none'; }, 250);
    document.body.style.overflow = '';
}
document.querySelectorAll('.modal-backdrop').forEach(m => {
    m.addEventListener('click', e => { if (e.target === m) closeModal(m.id); });
});
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') ['modalDetail','modalEdit','modalHapus'].forEach(closeModal);
});

// ============================================================
// TOAST
// ============================================================
function showToast(msg, type = 'info') {
    const container = document.getElementById('realtimeToast');
    const item = document.createElement('div');
    item.className = 'toast-item';
    const colors = { success:'#22C55E', error:'#EF4444', info:'#1D4ED8', masuk:'#22C55E', pulang:'#3B82F6' };
    const c = colors[type] || '#1D4ED8';
    item.style.borderLeftColor = c;
    item.innerHTML = `<div class="realtime-dot" style="background:${c}"></div> ${msg}`;
    container.appendChild(item);
    setTimeout(() => {
        item.classList.add('leaving');
        setTimeout(() => item.remove(), 300);
    }, 4000);
}

// ============================================================
// EXPORT PDF
// ============================================================
function exportPDF() {
    if (!state.kelas || !state.filteredData.length) {
        alert('Pilih kelas dan pastikan ada data terlebih dahulu.'); return;
    }
    const { jsPDF } = window.jspdf;
    const doc   = new jsPDF({ orientation:'landscape', unit:'mm', format:'a4' });
    const range = getSemesterRange(state.semester);
    const now   = new Date().toLocaleDateString('id-ID', { day:'2-digit', month:'long', year:'numeric' });

    doc.setFillColor(29,78,216);
    doc.rect(0,0,297,28,'F');
    doc.setTextColor(255,255,255);
    doc.setFontSize(14); doc.setFont('helvetica','bold');
    doc.text('REKAP ABSENSI SISWA — SMKN 8 MEDAN', 14, 11);
    doc.setFontSize(9); doc.setFont('helvetica','normal');
    doc.text(`Kelas ${state.kelas}  ·  Semester ${state.semester} (${range.label})  ·  Dicetak: ${now}`, 14, 20);

    const d = state.filteredData;
    const counts = { hadir:0, terlambat:0, izin:0, alpa:0 };
    d.forEach(a => { if (counts[a.status]!==undefined) counts[a.status]++; });

    const sumY = 34;
    [['Total',d.length,[240,249,255]],['Hadir',counts.hadir,[220,252,231]],
     ['Terlambat',counts.terlambat,[254,249,195]],['Izin',counts.izin,[219,234,254]],
     ['Alpa',counts.alpa,[254,226,226]]
    ].forEach(([label,val,rgb],i) => {
        const x = 14 + i*57;
        doc.setFillColor(...rgb); doc.roundedRect(x,sumY,54,14,2,2,'F');
        doc.setFont('helvetica','normal'); doc.setTextColor(100,100,100);
        doc.setFontSize(8); doc.text(label, x+4, sumY+5);
        doc.setFont('helvetica','bold'); doc.setTextColor(30,30,30);
        doc.setFontSize(12); doc.text(String(val), x+4, sumY+11);
        doc.setFontSize(8);
    });

    doc.autoTable({
        head:[['#','Nama','Kelas','NISN','Tanggal','Masuk','Pulang','Status','Metode','Keterangan']],
        body: d.map((a,i) => [
            i+1, a.siswa?.name||a.user?.name||'—', a.siswa?.kelas||'—',
            a.siswa?.nisn||'—', a.tanggal ? new Date(a.tanggal).toLocaleDateString('id-ID') : '—',
            a.jam_masuk  ? (a.jam_masuk +'').substring(0,5)  : '—',
            a.jam_pulang ? (a.jam_pulang+'').substring(0,5) : '—',
            (a.status||'').toUpperCase(), a.media?.tipe||'—',
            (a.keterangan||'').substring(0,35),
        ]),
        startY: sumY+18,
        styles:{ fontSize:7.5, cellPadding:2.5 },
        headStyles:{ fillColor:[29,78,216], textColor:255, fontStyle:'bold' },
        alternateRowStyles:{ fillColor:[248,250,252] },
        columnStyles:{ 0:{cellWidth:8},1:{cellWidth:45},2:{cellWidth:18},3:{cellWidth:25},4:{cellWidth:25},5:{cellWidth:15},6:{cellWidth:15},7:{cellWidth:20} },
        didParseCell: (data) => {
            if (data.section==='body' && data.column.index===7) {
                const v = data.cell.raw;
                if (v==='HADIR')     { data.cell.styles.textColor=[22,101,52];  data.cell.styles.fillColor=[220,252,231]; }
                if (v==='TERLAMBAT') { data.cell.styles.textColor=[133,77,14];  data.cell.styles.fillColor=[254,249,195]; }
                if (v==='IZIN')      { data.cell.styles.textColor=[30,64,175];  data.cell.styles.fillColor=[219,234,254]; }
                if (v==='ALPA')      { data.cell.styles.textColor=[153,27,27];  data.cell.styles.fillColor=[254,226,226]; }
            }
        },
        didDrawPage: (data) => {
            const n = doc.internal.getNumberOfPages();
            doc.setFontSize(7); doc.setTextColor(150);
            doc.text('SMKN 8 Medan — Sistem Absensi Digital', 14, doc.internal.pageSize.height - 6);
            doc.text(`Halaman ${data.pageNumber} / ${n}`, 270, doc.internal.pageSize.height - 6);
        }
    });

    const fname = `absensi_kelas${state.kelas}_s${state.semester}_${new Date().toISOString().slice(0,10)}.pdf`;
    doc.save(fname);
    showToast(`📄 PDF berhasil diekspor: ${fname}`, 'info');
}

// ============================================================
// REALTIME ECHO
// ============================================================
(function initRealtime() {
    if (typeof window.Echo === 'undefined') return;
    window.Echo.channel('absensi-channel').listen('.absensi.event', (e) => {
        const a    = e.absensi;
        const type = e.type;
        const waktu = type === 'masuk'
            ? ((a.jam_masuk  || '') + '').substring(0,5)
            : ((a.jam_pulang || '') + '').substring(0,5);
        showToast(`${type==='masuk'?'🟢':'🔵'} Absen ${type} — Siswa #${a.siswa_id||a.user_id} pukul ${waktu||'baru saja'}`, type);
        if (state.kelas) setTimeout(() => { loadAbsensi(); loadStatistik(); }, 800);
    });
})();

// Init
document.addEventListener('DOMContentLoaded', () => {
    const p = new URLSearchParams(window.location.search).get('kelas');
    if (p && ['X','XI','XII'].includes(p.toUpperCase())) pilihKelas(p.toUpperCase());
});
</script>

@endsection
