@extends('layouts.admin')

@section('title', 'Absensi')

@section('content')

<div class="mb-6" data-aos="fade-down">
    <h1 class="text-2xl font-bold text-slate-800">Absensi</h1>
    <p class="text-slate-500 text-sm mt-0.5">Rekam dan pantau kehadiran siswa</p>
</div>

<!-- Search & Filter Card -->
<div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 mb-6" data-aos="fade-up">
    <div class="flex flex-col xl:flex-row items-end justify-between gap-6">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 w-full flex-1">
            <div class="md:col-span-2">
                <label for="filterTingkat" class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Tingkat</label>
                <select id="filterTingkat" class="w-full bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3 transition-all cursor-pointer">
                    <option value="" selected disabled>-- Pilih --</option>
                    <option value="X">Kelas X</option>
                    <option value="XI">Kelas XI</option>
                    <option value="XII">Kelas XII</option>
                </select>
            </div>
            <div class="md:col-span-4">
                <label for="filterJurusan" class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Jurusan</label>
                <select id="filterJurusan" class="w-full bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3 transition-all cursor-pointer">
                    <option value="" selected disabled>-- Pilih Jurusan --</option>
                    <option value="RPL 1">RPL 1</option>
                    <option value="RPL 2">RPL 2</option>
                    <option value="TKJ 1">TKJ 1</option>
                    <option value="TKJ 2">TKJ 2</option>
                    <option value="MM 1">MM 1</option>
                    <option value="AKL 1">AKL 1</option>
                </select>
            </div>
            <div class="relative md:col-span-6">
                <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Pencarian</label>
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class='bx bx-search text-slate-400 text-lg mt-6'></i>
                </div>
                <input type="text" id="searchSiswaAbsen" class="block w-full p-3 pl-10 text-sm text-slate-900 border border-slate-200 rounded-xl bg-slate-50 focus:ring-blue-500 focus:border-blue-500 transition-all" placeholder="Cari Nama atau NISN...">
            </div>
        </div>
        <div class="w-full xl:w-auto">
            <button class="w-full xl:w-auto inline-flex items-center justify-center gap-2 bg-blue-600 text-white text-sm font-bold px-8 py-3.5 rounded-xl shadow-lg shadow-blue-200 hover:bg-blue-700 hover:-translate-y-0.5 transition-all whitespace-nowrap">
                <i class='bx bx-qr-scan'></i> Mulai Absensi
            </button>
        </div>
    </div>
</div>

<!-- Table Section -->
<div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden min-h-[40vh]" data-aos="fade-up" data-aos-delay="100">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50/50 border-b border-slate-100 text-slate-500 font-bold uppercase text-[10px] tracking-wider">
                <tr>
                    <th class="px-6 py-5 text-center w-16">No</th>
                    <th class="px-6 py-5">Nama Siswa</th>
                    <th class="px-6 py-5">NISN</th>
                    <th class="px-6 py-5">Tingkat</th>
                    <th class="px-6 py-5">Jurusan</th>
                    <th class="px-6 py-5">Waktu Absen</th>
                    <th class="px-6 py-5">Status Absen</th>
                    <th class="px-6 py-5 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="absensiTableBody" class="divide-y divide-slate-50">
                @php
                    $siswaAbsenList = [
                        ['no' => 1, 'nama' => 'Andi Pratama', 'nisn' => '0054321001', 'tingkat' => 'XII', 'jurusan' => 'RPL 1', 'waktu_absen' => '07:05 WIB', 'status_absen' => 'Hadir'],
                        ['no' => 2, 'nama' => 'Siti Rahma', 'nisn' => '0065432102', 'tingkat' => 'XI', 'jurusan' => 'TKJ 2', 'waktu_absen' => '07:10 WIB', 'status_absen' => 'Hadir'],
                        ['no' => 3, 'nama' => 'Budi Santoso', 'nisn' => '0076543203', 'tingkat' => 'X', 'jurusan' => 'MM 1', 'waktu_absen' => '07:15 WIB', 'status_absen' => 'Hadir'],
                        ['no' => 4, 'nama' => 'Dewi Lestari', 'nisn' => '0053210454', 'tingkat' => 'XII', 'jurusan' => 'AKL 1', 'waktu_absen' => '07:08 WIB', 'status_absen' => 'Hadir'],
                        ['no' => 5, 'nama' => 'Fajar Nugroho', 'nisn' => '0061234567', 'tingkat' => 'XI', 'jurusan' => 'RPL 2', 'waktu_absen' => '07:20 WIB', 'status_absen' => 'Terlambat'],
                        ['no' => 6, 'nama' => 'Rina Wulandari', 'nisn' => '0059876543', 'tingkat' => 'XII', 'jurusan' => 'RPL 1', 'waktu_absen' => '07:02 WIB', 'status_absen' => 'Hadir'],
                        ['no' => 7, 'nama' => 'Ahmad Fauzi', 'nisn' => '0062345678', 'tingkat' => 'XI', 'jurusan' => 'MM 2', 'waktu_absen' => '07:25 WIB', 'status_absen' => 'Terlambat'],
                        ['no' => 8, 'nama' => 'Maya Sari', 'nisn' => '0078765432', 'tingkat' => 'X', 'jurusan' => 'RPL 1', 'waktu_absen' => '07:00 WIB', 'status_absen' => 'Hadir'],
                        ['no' => 9, 'nama' => 'Dodi Kurniawan', 'nisn' => '0051112223', 'tingkat' => 'XII', 'jurusan' => 'RPL 1', 'waktu_absen' => '07:12 WIB', 'status_absen' => 'Hadir'],
                        ['no' => 10, 'nama' => 'Lina Marlina', 'nisn' => '0062223334', 'tingkat' => 'XI', 'jurusan' => 'TKJ 2', 'waktu_absen' => '07:30 WIB', 'status_absen' => 'Terlambat'],
                        ['no' => 11, 'nama' => 'Gilang Ramadhan', 'nisn' => '0051112224', 'tingkat' => 'X', 'jurusan' => 'RPL 1', 'waktu_absen' => '07:07 WIB', 'status_absen' => 'Hadir'],
                        ['no' => 12, 'nama' => 'Putri Ayu', 'nisn' => '0062223335', 'tingkat' => 'XI', 'jurusan' => 'AKL 1', 'waktu_absen' => '07:18 WIB', 'status_absen' => 'Hadir'],
                    ];
                @endphp

                <!-- Empty State Row -->
                <tr id="emptyStateAbsensi" class="hover:bg-transparent">
                    <td colspan="8" class="py-20 text-center">
                        <div class="flex flex-col items-center justify-center text-slate-400">
                            <div class="w-16 h-16 rounded-2xl bg-slate-50 flex items-center justify-center mb-3">
                                <i class='bx bx-filter-alt text-3xl'></i>
                            </div>
                            <p class="text-sm font-medium">Silahkan pilih kelas dan jurusan untuk menampilkan data absensi</p>
                        </div>
                    </td>
                </tr>

                @foreach($siswaAbsenList as $siswa)
                <tr class="hover:bg-slate-50/80 transition-all group absensi-row hidden" data-tingkat="{{ $siswa['tingkat'] }}" data-jurusan="{{ $siswa['jurusan'] }}">
                    <td class="px-6 py-4 text-center font-medium text-slate-400">{{ $siswa['no'] }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-xs shadow-sm">
                                {{ strtoupper(substr($siswa['nama'], 0, 1)) }}
                            </div>
                            <div>
                                <span class="block font-bold text-slate-700">{{ $siswa['nama'] }}</span>
                                <span class="block text-[10px] text-slate-400 uppercase tracking-tighter">NISN: {{ $siswa['nisn'] }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-mono text-xs text-slate-600 bg-slate-100 px-2 py-1 rounded-md">{{ $siswa['nisn'] }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 bg-slate-100 text-slate-700 text-[11px] font-bold rounded-full border border-slate-200">
                            Kelas {{ $siswa['tingkat'] }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 bg-blue-50 text-blue-700 text-[11px] font-bold rounded-full border border-blue-100">
                            {{ $siswa['jurusan'] }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-slate-600 font-medium">
                        {{ $siswa['waktu_absen'] }}
                    </td>
                    <td class="px-6 py-4">
                        @if($siswa['status_absen'] == 'Hadir')
                            <span class="px-3 py-1 bg-emerald-50 text-emerald-700 text-[11px] font-bold rounded-full border border-emerald-100">
                                Hadir
                            </span>
                        @elseif($siswa['status_absen'] == 'Terlambat')
                            <span class="px-3 py-1 bg-amber-50 text-amber-700 text-[11px] font-bold rounded-full border border-amber-100">
                                Terlambat
                            </span>
                        @else
                            <span class="px-3 py-1 bg-red-50 text-red-700 text-[11px] font-bold rounded-full border border-red-100">
                                {{ $siswa['status_absen'] }}
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-1">
                            <button class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-blue-500 hover:bg-blue-50 rounded-lg transition-all" title="Lihat Detail">
                                <i class='bx bx-info-circle text-lg'></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterTingkat = document.getElementById('filterTingkat');
        const filterJurusan = document.getElementById('filterJurusan');
        const searchSiswaAbsen = document.getElementById('searchSiswaAbsen');
        const rows = document.querySelectorAll('.absensi-row');
        const emptyState = document.getElementById('emptyStateAbsensi');

        function filterTable() {
            const selectedTingkat = filterTingkat.value;
            const selectedJurusan = filterJurusan.value;
            const searchText = searchSiswaAbsen.value.toLowerCase();

            let anyRowVisible = false;

            // If no filter is selected, show empty state and hide all rows
            if (!selectedTingkat && !selectedJurusan) {
                emptyState.classList.remove('hidden');
                rows.forEach(row => row.classList.add('hidden'));
                return; // Exit function early
            }
            
            // If filters are selected, hide empty state initially
            emptyState.classList.add('hidden');

            rows.forEach(row => {
                const rowTingkat = row.getAttribute('data-tingkat');
                const rowJurusan = row.getAttribute('data-jurusan');
                const rowText = row.innerText.toLowerCase(); // Get all text content for search

                const matchesTingkat = !selectedTingkat || rowTingkat === selectedTingkat;
                const matchesJurusan = !selectedJurusan || rowJurusan === selectedJurusan;
                const matchesSearch = rowText.includes(searchText);

                if (matchesTingkat && matchesJurusan && matchesSearch) {
                    row.classList.remove('hidden');
                    anyRowVisible = true;
                } else {
                    row.classList.add('hidden');
                }
            });

            // If after filtering, no rows are visible, show empty state
            if (!anyRowVisible) {
                emptyState.classList.remove('hidden');
            }
        }

        // Initial call to hide all rows until a filter is selected
        filterTable();

        filterTingkat.addEventListener('change', filterTable);
        filterJurusan.addEventListener('change', filterTable);
        searchSiswaAbsen.addEventListener('input', filterTable);
    });
</script>