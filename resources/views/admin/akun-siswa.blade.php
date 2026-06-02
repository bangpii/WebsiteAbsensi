@extends('layouts.admin')

@section('title', 'Data Siswa')

@section('content')

<div class="mb-6" data-aos="fade-down">
    <h1 class="text-2xl font-bold text-slate-800">Data Siswa</h1>
    <p class="text-slate-500 text-sm mt-0.5">Manajemen data siswa SMKN 8 Medan</p>
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
                <input type="text" id="searchSiswa" class="block w-full p-3 pl-10 text-sm text-slate-900 border border-slate-200 rounded-xl bg-slate-50 focus:ring-blue-500 focus:border-blue-500 transition-all" placeholder="Cari Nama atau NISN...">
            </div>
        </div>
        <div class="w-full xl:w-auto">
            <button onclick="openModal()" class="w-full xl:w-auto inline-flex items-center justify-center gap-2 bg-blue-600 text-white text-sm font-bold px-8 py-3.5 rounded-xl shadow-lg shadow-blue-200 hover:bg-blue-700 hover:-translate-y-0.5 transition-all whitespace-nowrap">
                <i class='bx bx-plus'></i> Tambah Siswa
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
                    <th class="px-6 py-5 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="studentTableBody" class="divide-y divide-slate-50">
                @php
                    $siswaList = [
                        ['no' => 1, 'nama' => 'Andi Pratama', 'nisn' => '0054321001', 'tingkat' => 'XII', 'jurusan' => 'RPL 1'],
                        ['no' => 2, 'nama' => 'Siti Rahma', 'nisn' => '0065432102', 'tingkat' => 'XI', 'jurusan' => 'TKJ 2'],
                        ['no' => 3, 'nama' => 'Budi Santoso', 'nisn' => '0076543203', 'tingkat' => 'X', 'jurusan' => 'MM 1'],
                        ['no' => 4, 'nama' => 'Dewi Lestari', 'nisn' => '0053210454', 'tingkat' => 'XII', 'jurusan' => 'AKL 1'],
                        ['no' => 5, 'nama' => 'Fajar Nugroho', 'nisn' => '0061234567', 'tingkat' => 'XI', 'jurusan' => 'RPL 2'],
                        ['no' => 6, 'nama' => 'Rina Wulandari', 'nisn' => '0059876543', 'tingkat' => 'XII', 'jurusan' => 'RPL 1'],
                        ['no' => 7, 'nama' => 'Ahmad Fauzi', 'nisn' => '0062345678', 'tingkat' => 'XI', 'jurusan' => 'MM 2'],
                        ['no' => 8, 'nama' => 'Maya Sari', 'nisn' => '0078765432', 'tingkat' => 'X', 'jurusan' => 'RPL 1'],
                        ['no' => 9, 'nama' => 'Dodi Kurniawan', 'nisn' => '0051112223', 'tingkat' => 'XII', 'jurusan' => 'RPL 1'],
                        ['no' => 10, 'nama' => 'Lina Marlina', 'nisn' => '0062223334', 'tingkat' => 'XI', 'jurusan' => 'TKJ 2'],
                    ];
                @endphp

                <!-- Empty State Row -->
                <tr id="emptyState" class="hover:bg-transparent">
                    <td colspan="6" class="py-20 text-center">
                        <div class="flex flex-col items-center justify-center text-slate-400">
                            <div class="w-16 h-16 rounded-2xl bg-slate-50 flex items-center justify-center mb-3">
                                <i class='bx bx-filter-alt text-3xl'></i>
                            </div>
                            <p class="text-sm font-medium">Silahkan pilih kelas untuk menampilkan data siswa</p>
                        </div>
                    </td>
                </tr>

                @foreach($siswaList as $siswa)
                <tr class="hover:bg-slate-50/80 transition-all group student-row hidden" data-tingkat="{{ $siswa['tingkat'] }}" data-jurusan="{{ $siswa['jurusan'] }}">
                    <td class="px-6 py-4 text-center font-medium text-slate-400">{{ $siswa['no'] }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-xs shadow-sm">
                                {{ strtoupper(substr($siswa['nama'], 0, 1)) }}
                            </div>
                            <div>
                                <span class="block font-bold text-slate-700">{{ $siswa['nama'] }}</span>
                                <span class="block text-[10px] text-slate-400 uppercase tracking-tighter">Siswa Aktif</span>
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
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-1">
                            <button class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-amber-500 hover:bg-amber-50 rounded-lg transition-all" title="Edit">
                                <i class='bx bxs-edit-alt text-lg'></i>
                            </button>
                            <button class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all" title="Hapus">
                                <i class='bx bxs-trash text-lg'></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Siswa -->
<div id="modalTambah" class="fixed inset-0 z-50 hidden">
    <!-- Overlay: Dipisah agar tidak menutupi konten -->
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>

    <!-- Container Konten: Diberi z-index agar di atas overlay -->
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Modal Content -->
        <div class="relative inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-3xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="px-6 py-6 bg-white">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-slate-800">Tambah Data Siswa</h3>
                    <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <i class='bx bx-x text-2xl'></i>
                    </button>
                </div>

                <form action="#" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Nama Lengkap</label>
                        <input type="text" name="nama" class="w-full bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3 transition-all" placeholder="Masukkan nama siswa..." required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">NISN</label>
                        <input type="text" name="nisn" class="w-full bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3 transition-all" placeholder="Masukkan 10 digit NISN..." required>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Tingkat</label>
                            <select name="tingkat" class="w-full bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3 transition-all cursor-pointer" required>
                                <option value="" selected disabled>Pilih</option>
                                <option value="X">Kelas X</option>
                                <option value="XI">Kelas XI</option>
                                <option value="XII">Kelas XII</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Jurusan</label>
                            <select name="jurusan" class="w-full bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3 transition-all cursor-pointer" required>
                                <option value="" selected disabled>Pilih</option>
                                <option value="RPL 1">RPL 1</option>
                                <option value="RPL 2">RPL 2</option>
                                <option value="TKJ 1">TKJ 1</option>
                                <option value="TKJ 2">TKJ 2</option>
                                <option value="MM 1">MM 1</option>
                                <option value="AKL 1">AKL 1</option>
                            </select>
                        </div>
                    </div>

                    <div class="pt-4 flex gap-3">
                        <button type="button" onclick="closeModal()" class="flex-1 px-6 py-3 text-sm font-bold text-slate-500 bg-slate-100 rounded-xl hover:bg-slate-200 transition-all">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 px-6 py-3 text-sm font-bold text-white bg-blue-600 rounded-xl shadow-lg shadow-blue-200 hover:bg-blue-700 transition-all">
                            Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterTingkat = document.getElementById('filterTingkat');
        const filterJurusan = document.getElementById('filterJurusan');
        const searchSiswa = document.getElementById('searchSiswa');
        const rows = document.querySelectorAll('.student-row');
        const emptyState = document.getElementById('emptyState');

        function filterTable() {
            const selectedTingkat = filterTingkat.value;
            const selectedJurusan = filterJurusan.value;
            const searchText = searchSiswa.value.toLowerCase();

            if (!selectedTingkat && !selectedJurusan) {
                emptyState.classList.remove('hidden');
                rows.forEach(row => row.classList.add('hidden'));
                return;
            }
            
            emptyState.classList.add('hidden');

            rows.forEach(row => {
                const rowTingkat = row.getAttribute('data-tingkat');
                const rowJurusan = row.getAttribute('data-jurusan');
                const rowText = row.innerText.toLowerCase();
                
                const matchesTingkat = !selectedTingkat || rowTingkat === selectedTingkat;
                const matchesJurusan = !selectedJurusan || rowJurusan === selectedJurusan;
                const matchesSearch = rowText.includes(searchText);

                if (matchesTingkat && matchesJurusan && matchesSearch) {
                    row.classList.remove('hidden');
                } else {
                    row.classList.add('hidden');
                }
            });
        }

        filterTingkat.addEventListener('change', filterTable);
        filterJurusan.addEventListener('change', filterTable);
        searchSiswa.addEventListener('input', filterTable);

        window.openModal = function() {
            document.getElementById('modalTambah').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        window.closeModal = function() {
            document.getElementById('modalTambah').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    });
</script>

@endsection