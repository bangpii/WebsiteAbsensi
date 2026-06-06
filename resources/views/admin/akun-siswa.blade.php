@extends('layouts.admin')

@section('title', 'Data Siswa')

@section('content')

<div class="mb-6" data-aos="fade-down">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Data Siswa</h1>
            <p class="text-slate-500 text-sm mt-0.5">Manajemen data siswa SMKN 8 Medan</p>
        </div>
        <!-- Badge total -->
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 text-xs font-bold px-3 py-1.5 rounded-full border border-blue-100">
                <i class='bx bxs-group text-sm'></i>
                Total: {{ $total }} Siswa
            </span>
        </div>
    </div>
</div>

{{-- ===================== ALERT MESSAGES ===================== --}}
@if(session('success'))
<div class="mb-4 flex items-start gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium px-5 py-4 rounded-2xl" data-aos="fade-down" id="alertSuccess">
    <i class='bx bxs-check-circle text-emerald-500 text-xl flex-shrink-0 mt-0.5'></i>
    <span>{{ session('success') }}</span>
    <button onclick="document.getElementById('alertSuccess').remove()" class="ml-auto text-emerald-400 hover:text-emerald-600">
        <i class='bx bx-x text-lg'></i>
    </button>
</div>
@endif

@if(session('error') || $error)
<div class="mb-4 flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 text-sm font-medium px-5 py-4 rounded-2xl" data-aos="fade-down" id="alertError">
    <i class='bx bxs-error-circle text-red-500 text-xl flex-shrink-0 mt-0.5'></i>
    <span>{{ session('error') ?? $error }}</span>
    <button onclick="document.getElementById('alertError').remove()" class="ml-auto text-red-400 hover:text-red-600">
        <i class='bx bx-x text-lg'></i>
    </button>
</div>
@endif

{{-- ===================== SEARCH & FILTER ===================== --}}
<div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 mb-6" data-aos="fade-up">
    <div class="flex flex-col xl:flex-row items-end justify-between gap-6">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 w-full flex-1">

            {{-- Filter Tingkat --}}
            <div class="md:col-span-2">
                <label for="filterTingkat" class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Tingkat</label>
                <select id="filterTingkat" class="w-full bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3 transition-all cursor-pointer">
                    <option value="">Semua</option>
                    @foreach($siswaList->pluck('tingkat')->unique()->filter()->sort()->values() as $t)
                        <option value="{{ $t }}">Kelas {{ $t }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Jurusan --}}
            <div class="md:col-span-4">
                <label for="filterJurusan" class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Jurusan</label>
                <select id="filterJurusan" class="w-full bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3 transition-all cursor-pointer">
                    <option value="">Semua Jurusan</option>
                    @foreach($siswaList->pluck('jurusan')->unique()->filter()->sort()->values() as $j)
                        <option value="{{ $j }}">{{ $j }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Search --}}
            <div class="relative md:col-span-6">
                <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Pencarian</label>
                <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                    <i class='bx bx-search text-slate-400 text-lg mt-6'></i>
                </div>
                <input type="text" id="searchSiswa"
                    class="block w-full p-3 pl-10 text-sm text-slate-900 border border-slate-200 rounded-xl bg-slate-50 focus:ring-blue-500 focus:border-blue-500 transition-all"
                    placeholder="Cari Nama, NISN, atau Kelas...">
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="w-full xl:w-auto flex flex-col sm:flex-row gap-3">
            {{-- Sync Button --}}
            <form action="{{ route('admin.akun-siswa.sync') }}" method="POST" id="syncForm">
                @csrf
                <button type="submit"
                    onclick="return confirmSync()"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-emerald-500 text-white text-sm font-bold px-6 py-3.5 rounded-xl shadow-lg shadow-emerald-200 hover:bg-emerald-600 hover:-translate-y-0.5 transition-all whitespace-nowrap"
                    id="syncBtn">
                    <i class='bx bx-sync' id="syncIcon"></i>
                    Sinkron LMS
                </button>
            </form>

            {{-- Tombol Tambah (Optional manual) --}}
            {{-- Nonaktifkan jika data hanya dari LMS --}}
            {{-- <button onclick="openModal()" class="..."> Tambah Siswa </button> --}}
        </div>
    </div>
</div>

{{-- ===================== TABLE ===================== --}}
<div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden" data-aos="fade-up" data-aos-delay="100">

    {{-- Table Stats Bar --}}
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between gap-4 flex-wrap">
        <div class="flex items-center gap-4">
            <span class="text-sm font-semibold text-slate-700">
                Menampilkan <span id="visibleCount" class="text-blue-600">{{ $siswaList->count() }}</span> siswa
            </span>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1 text-xs text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">
                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full inline-block"></span>
                    Online: {{ $siswaList->where('is_online', true)->count() }}
                </span>
                <span class="inline-flex items-center gap-1 text-xs text-slate-500 bg-slate-50 px-2 py-1 rounded-full">
                    <span class="w-1.5 h-1.5 bg-slate-400 rounded-full inline-block"></span>
                    Offline: {{ $siswaList->where('is_online', false)->count() }}
                </span>
            </div>
        </div>
        <span class="text-xs text-slate-400">
            <i class='bx bx-time-five'></i>
            Data diperbarui: {{ now()->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50/50 border-b border-slate-100 text-slate-500 font-bold uppercase text-[10px] tracking-wider">
                <tr>
                    <th class="px-6 py-5 text-center w-16">No</th>
                    <th class="px-6 py-5">Nama Siswa</th>
                    <th class="px-6 py-5">NISN</th>
                    <th class="px-6 py-5">Kelas</th>
                    <th class="px-6 py-5">Tingkat</th>
                    <th class="px-6 py-5">Jurusan</th>
                    <th class="px-6 py-5 text-center">Status</th>
                    <th class="px-6 py-5 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="studentTableBody" class="divide-y divide-slate-50">

                {{-- Data Real dari Backend --}}
                @forelse($siswaList as $siswa)
                <tr class="hover:bg-slate-50/80 transition-all group student-row"
                    data-tingkat="{{ $siswa['tingkat'] }}"
                    data-jurusan="{{ $siswa['jurusan'] }}"
                    data-search="{{ strtolower($siswa['nama'] . ' ' . $siswa['nisn'] . ' ' . $siswa['kelas']) }}">

                    <td class="px-6 py-4 text-center font-medium text-slate-400">{{ $siswa['no'] }}</td>

                    {{-- Nama --}}
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            {{-- Avatar --}}
                            @if($siswa['photo'])
                                <img src="{{ $siswa['photo'] }}" alt="{{ $siswa['nama'] }}"
                                    class="w-9 h-9 rounded-xl object-cover shadow-sm">
                            @else
                                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-xs shadow-sm flex-shrink-0">
                                    {{ strtoupper(substr($siswa['nama'], 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <span class="block font-bold text-slate-700">{{ $siswa['nama'] }}</span>
                                <span class="block text-[10px] text-slate-400">{{ $siswa['email'] }}</span>
                            </div>
                        </div>
                    </td>

                    {{-- NISN --}}
                    <td class="px-6 py-4">
                        <span class="font-mono text-xs text-slate-600 bg-slate-100 px-2 py-1 rounded-md">
                            {{ $siswa['nisn'] ?? '-' }}
                        </span>
                    </td>

                    {{-- Kelas --}}
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 bg-slate-100 text-slate-700 text-[11px] font-bold rounded-full border border-slate-200">
                            {{ $siswa['kelas'] ?? '-' }}
                        </span>
                    </td>

                    {{-- Tingkat --}}
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 bg-indigo-50 text-indigo-700 text-[11px] font-bold rounded-full border border-indigo-100">
                            Kelas {{ $siswa['tingkat'] ?? '-' }}
                        </span>
                    </td>

                    {{-- Jurusan --}}
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 bg-blue-50 text-blue-700 text-[11px] font-bold rounded-full border border-blue-100">
                            {{ $siswa['jurusan'] ?? '-' }}
                        </span>
                    </td>

                    {{-- Status Online --}}
                    <td class="px-6 py-4 text-center">
                        @if($siswa['is_online'])
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-700 text-[11px] font-bold rounded-full border border-emerald-100">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                                Online
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-100 text-slate-500 text-[11px] font-bold rounded-full border border-slate-200">
                                <span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span>
                                Offline
                            </span>
                        @endif
                    </td>

                    {{-- Aksi --}}
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-1">
                            <button
                                onclick="loadDetail({{ $siswa['user_id'] }})"
                                class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-blue-500 hover:bg-blue-50 rounded-lg transition-all"
                                title="Detail Siswa">
                                <i class='bx bxs-show text-lg'></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                {{-- Empty State --}}
                <tr>
                    <td colspan="8" class="py-20 text-center">
                        <div class="flex flex-col items-center justify-center text-slate-400">
                            <div class="w-16 h-16 rounded-2xl bg-slate-50 flex items-center justify-center mb-3">
                                <i class='bx bx-user-x text-3xl'></i>
                            </div>
                            <p class="text-sm font-semibold text-slate-500 mb-1">Belum ada data siswa</p>
                            <p class="text-xs text-slate-400 mb-4">Klik "Sinkron LMS" untuk mengambil data dari server LMS</p>
                            <form action="{{ route('admin.akun-siswa.sync') }}" method="POST">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-2 bg-blue-600 text-white text-sm font-bold px-5 py-2.5 rounded-xl hover:bg-blue-700 transition-all">
                                    <i class='bx bx-sync'></i> Sinkronkan Sekarang
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforelse

                {{-- JS Empty State (saat filter tidak ketemu) --}}
                <tr id="jsEmptyState" class="hidden">
                    <td colspan="8" class="py-16 text-center">
                        <div class="flex flex-col items-center justify-center text-slate-400">
                            <i class='bx bx-search text-4xl mb-2'></i>
                            <p class="text-sm font-medium">Tidak ada siswa yang cocok dengan filter</p>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Pagination info --}}
    @if($siswaList->count() > 0)
    <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
        <span>Total {{ $total }} siswa terdaftar</span>
        <span>Terakhir sync dari LMS</span>
    </div>
    @endif
</div>

{{-- ===================== MODAL DETAIL SISWA ===================== --}}
<div id="modalDetail" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closeModalDetail()"></div>
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden">

                {{-- Header Modal --}}
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-5">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-white">Detail Siswa</h3>
                        <button onclick="closeModalDetail()" class="text-white/70 hover:text-white transition-colors">
                            <i class='bx bx-x text-2xl'></i>
                        </button>
                    </div>
                </div>

                {{-- Loading State --}}
                <div id="modalLoading" class="px-6 py-12 text-center">
                    <div class="inline-flex items-center gap-3 text-slate-500">
                        <svg class="animate-spin w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        <span class="text-sm font-medium">Memuat data...</span>
                    </div>
                </div>

                {{-- Content --}}
                <div id="modalContent" class="hidden px-6 py-6 space-y-4">
                    {{-- Avatar + Nama --}}
                    <div class="flex items-center gap-4">
                        <div id="detailAvatar" class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-2xl shadow-md flex-shrink-0"></div>
                        <div>
                            <p id="detailNama" class="text-lg font-bold text-slate-800"></p>
                            <p id="detailEmail" class="text-sm text-slate-400"></p>
                            <div id="detailOnline"></div>
                        </div>
                    </div>
                    <hr class="border-slate-100">
                    {{-- Data Fields --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-slate-50 rounded-xl p-4">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">NISN</p>
                            <p id="detailNisn" class="text-sm font-bold text-slate-700 font-mono"></p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-4">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">Tingkat</p>
                            <p id="detailTingkat" class="text-sm font-bold text-slate-700"></p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-4">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">Kelas</p>
                            <p id="detailKelas" class="text-sm font-bold text-slate-700"></p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-4">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">Jurusan</p>
                            <p id="detailJurusan" class="text-sm font-bold text-slate-700 text-xs leading-tight"></p>
                        </div>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-4">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">Terakhir Aktif</p>
                        <p id="detailLastSeen" class="text-sm font-medium text-slate-600"></p>
                    </div>
                </div>

                {{-- Error State --}}
                <div id="modalError" class="hidden px-6 py-12 text-center">
                    <i class='bx bxs-error text-4xl text-red-400 mb-2'></i>
                    <p class="text-sm text-red-500 font-medium">Gagal memuat data siswa.</p>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Filter & Search ──────────────────────────────────────────
    const filterTingkat = document.getElementById('filterTingkat');
    const filterJurusan = document.getElementById('filterJurusan');
    const searchSiswa   = document.getElementById('searchSiswa');
    const rows          = document.querySelectorAll('.student-row');
    const jsEmpty       = document.getElementById('jsEmptyState');
    const visibleCount  = document.getElementById('visibleCount');

    function applyFilter() {
        const tingkat = filterTingkat.value.toLowerCase();
        const jurusan = filterJurusan.value.toLowerCase();
        const search  = searchSiswa.value.toLowerCase().trim();
        let visible   = 0;

        rows.forEach(row => {
            const rowTingkat = (row.dataset.tingkat || '').toLowerCase();
            const rowJurusan = (row.dataset.jurusan || '').toLowerCase();
            const rowSearch  = (row.dataset.search  || '').toLowerCase();

            const matchT = !tingkat || rowTingkat === tingkat;
            const matchJ = !jurusan || rowJurusan === jurusan;
            const matchS = !search  || rowSearch.includes(search);

            if (matchT && matchJ && matchS) {
                row.classList.remove('hidden');
                visible++;
            } else {
                row.classList.add('hidden');
            }
        });

        jsEmpty.classList.toggle('hidden', visible > 0);
        if (visibleCount) visibleCount.textContent = visible;
    }

    filterTingkat.addEventListener('change', applyFilter);
    filterJurusan.addEventListener('change', applyFilter);
    searchSiswa.addEventListener('input',    applyFilter);

    // ── Sync Button Spinner ───────────────────────────────────────
    const syncForm = document.getElementById('syncForm');
    const syncBtn  = document.getElementById('syncBtn');
    const syncIcon = document.getElementById('syncIcon');

    if (syncForm) {
        syncForm.addEventListener('submit', function () {
            if (syncBtn) {
                syncBtn.disabled = true;
                syncBtn.classList.add('opacity-70', 'cursor-not-allowed');
                syncIcon.classList.add('bx-spin');
                syncBtn.innerHTML = '<i class="bx bx-sync bx-spin"></i> Menyinkronkan...';
            }
        });
    }
});

// ── Confirm Sync ──────────────────────────────────────────────
function confirmSync() {
    return confirm('Sinkronisasi data siswa dari LMS sekarang?\nProses ini akan memperbarui semua data siswa.');
}

// ── Modal Detail ──────────────────────────────────────────────
function openModalDetail() {
    document.getElementById('modalDetail').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeModalDetail() {
    document.getElementById('modalDetail').classList.add('hidden');
    document.body.style.overflow = 'auto';
    // Reset
    document.getElementById('modalLoading').classList.remove('hidden');
    document.getElementById('modalContent').classList.add('hidden');
    document.getElementById('modalError').classList.add('hidden');
}

async function loadDetail(userId) {
    openModalDetail();

    try {
        const res  = await fetch(`/admin/akun-siswa/${userId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        });
        const json = await res.json();

        if (!res.ok || !json.status) throw new Error('Gagal');

        const d     = json.data;
        const siswa = d.siswa ?? {};

        document.getElementById('detailAvatar').textContent  = (d.name || '?')[0].toUpperCase();
        document.getElementById('detailNama').textContent    = d.name    || '-';
        document.getElementById('detailEmail').textContent   = d.email   || '-';
        document.getElementById('detailNisn').textContent    = siswa.nisn    || '-';
        document.getElementById('detailTingkat').textContent = siswa.tingkat ? `Kelas ${siswa.tingkat}` : '-';
        document.getElementById('detailKelas').textContent   = siswa.kelas   || '-';
        document.getElementById('detailJurusan').textContent = siswa.jurusan || '-';

        // Format last seen
        const ls = d.last_seen ? new Date(d.last_seen).toLocaleString('id-ID', {
            day: '2-digit', month: 'short', year: 'numeric',
            hour: '2-digit', minute: '2-digit'
        }) : '-';
        document.getElementById('detailLastSeen').textContent = ls + ' WIB';

        // Online badge
        document.getElementById('detailOnline').innerHTML = d.is_online
            ? '<span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full mt-1"><span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span> Online</span>'
            : '<span class="inline-flex items-center gap-1 text-[10px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-full mt-1"><span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span> Offline</span>';

        document.getElementById('modalLoading').classList.add('hidden');
        document.getElementById('modalContent').classList.remove('hidden');

    } catch (e) {
        document.getElementById('modalLoading').classList.add('hidden');
        document.getElementById('modalError').classList.remove('hidden');
    }
}
</script>

@endsection
