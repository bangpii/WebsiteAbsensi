@extends('layouts.admin')

@section('title', 'Manajemen Hari Libur')

@section('content')
<div class="mb-6" data-aos="fade-down">
    <h1 class="text-2xl font-bold text-slate-800">Manajemen Hari Libur</h1>
    <p class="text-slate-500 text-sm mt-0.5">Kelola hari libur sekolah dan sinkronisasi tanggal merah</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Form Tambah Libur -->
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100" data-aos="fade-right">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center">
                    <i class='bx bxs-calendar-minus text-red-600 text-xl'></i>
                </div>
                <h2 class="text-lg font-bold text-slate-800">Tambah Libur</h2>
            </div>

            <form action="{{ route('admin.libur.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Nama Hari Libur</label>
                    <input type="text" name="nama" placeholder="Contoh: Libur Akhir Semester" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" required>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Mulai</label>
                        <input type="date" name="mulai" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Selesai</label>
                        <input type="date" name="selesai" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" required>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Keterangan (Opsional)</label>
                    <textarea name="keterangan" rows="3" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all"></textarea>
                </div>
                <button type="submit" class="w-full py-3.5 bg-blue-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-200 hover:bg-blue-700 transition-all active:scale-95">
                    Simpan Hari Libur
                </button>
            </form>
        </div>

        <!-- Sinkronisasi Card -->
        <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-3xl p-6 text-white shadow-lg" data-aos="fade-right" data-aos-delay="100">
            <h4 class="font-bold mb-2 flex items-center gap-2">
                <i class='bx bx-refresh text-blue-400 animate-spin-slow'></i>
                Libur Otomatis
            </h4>
            <p class="text-xs text-slate-400 mb-4">Sistem akan otomatis meliburkan absensi pada tanggal merah kalender Indonesia tahun {{ date('Y') }}.</p>
            <div class="flex items-center justify-between p-3 bg-white/10 rounded-2xl">
                <span class="text-xs font-semibold">Status Sync</span>
                <span class="px-2 py-1 bg-emerald-500/20 text-emerald-400 text-[10px] font-bold rounded-lg uppercase">Aktif</span>
            </div>
        </div>

        <!-- Weekend Info Card -->
        <div class="bg-blue-50 rounded-3xl p-6 border border-blue-100" data-aos="fade-right" data-aos-delay="200">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center text-white">
                    <i class='bx bx-check-double'></i>
                </div>
                <h4 class="font-bold text-blue-900 text-sm">Libur Mingguan</h4>
            </div>
            <p class="text-xs text-blue-700/70 leading-relaxed">
                Sistem secara otomatis mendeteksi hari <strong>Sabtu</strong> dan <strong>Minggu</strong> sebagai hari libur tetap.
            </p>
        </div>
    </div>

    <!-- Daftar Libur -->
    <div class="lg:col-span-2">
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm font-bold rounded-2xl" data-aos="fade-left">
                <i class='bx bx-check-circle mr-1'></i> {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden" data-aos="fade-up">
            <div class="p-6 border-b border-slate-50 flex items-center justify-between">
                <h3 class="font-bold text-slate-800">Daftar Hari Libur & Tanggal Merah</h3>
                <div class="flex items-center gap-3">
                    <select id="filterMonth" class="bg-slate-50 border border-slate-200 text-xs font-bold rounded-xl px-3 py-2 outline-none focus:ring-2 focus:ring-blue-500/20">
                        @foreach(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $index => $month)
                            <option value="{{ $index }}" {{ $index == date('n')-1 ? 'selected' : '' }}>{{ $month }}</option>
                        @endforeach
                    </select>
                    <select id="filterYear" class="bg-slate-50 border border-slate-200 text-xs font-bold rounded-xl px-3 py-2 outline-none focus:ring-2 focus:ring-blue-500/20">
                        @for($y = date('Y')-1; $y <= date('Y')+2; $y++)
                            <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50/50 border-b border-slate-100 text-slate-500 font-bold uppercase text-[10px] tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Nama Libur</th>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">Tipe</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="holidayTableBody" class="divide-y divide-slate-50"></tbody>
                </table>
            </div>
            
            <!-- Empty State Helper -->
            <div class="p-8 bg-blue-50/30 border-t border-slate-50">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600 flex-shrink-0">
                        <i class='bx bx-info-circle text-xl'></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-blue-900">Catatan Sistem</h4>
                        <p class="text-xs text-blue-700/70 mt-1 leading-relaxed">Siswa tidak dapat melakukan absensi pada hari-hari yang terdaftar di atas. Absensi akan secara otomatis ditandai sebagai "Libur" tanpa memengaruhi persentase kehadiran.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes spin-slow {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .animate-spin-slow {
        animation: spin-slow 8s linear infinite;
    }
</style>

<script>
    // Simulasi data libur (biasanya berasal dari database)
    const staticHolidays = [
        { nama: 'Hari Buruh Internasional', mulai: '2026-05-01', selesai: '2026-05-01', tipe: 'Nasional' },
        { nama: 'Hari Raya Waisak', mulai: '2026-05-12', selesai: '2026-05-12', tipe: 'Nasional' },
        { nama: 'Libur Kenaikan Kelas', mulai: '2026-06-20', selesai: '2026-07-05', tipe: 'Sekolah' },
        { nama: 'Tahun Baru Hijriah', mulai: '2026-07-17', selesai: '2026-07-17', tipe: 'Nasional' },
        { nama: 'Hari Kemerdekaan RI', mulai: '2026-08-17', selesai: '2026-08-17', tipe: 'Nasional' },
    ];

    document.addEventListener('DOMContentLoaded', function() {
        const filterMonth = document.getElementById('filterMonth');
        const filterYear = document.getElementById('filterYear');

        renderHolidayTable();

        filterMonth.addEventListener('change', renderHolidayTable);
        filterYear.addEventListener('change', renderHolidayTable);
    });

    function renderHolidayTable() {
        const tableBody = document.getElementById('holidayTableBody');
        const selectedMonth = parseInt(document.getElementById('filterMonth').value);
        const selectedYear = parseInt(document.getElementById('filterYear').value);
        
        tableBody.innerHTML = '';

        // 1. Render Hari Libur Statis yang sesuai dengan bulan/tahun terpilih
        staticHolidays.forEach(h => {
            const dateMulai = new Date(h.mulai);
            const dateSelesai = new Date(h.selesai);
            
            const isInMonth = (dateMulai.getMonth() === selectedMonth && dateMulai.getFullYear() === selectedYear) || 
                              (dateSelesai.getMonth() === selectedMonth && dateSelesai.getFullYear() === selectedYear);

            if (isInMonth) {
                appendHolidayRow(h.nama, formatDateRange(dateMulai, dateSelesai), h.tipe, false);
            }
        });

        // 2. Render Akhir Pekan (Sabtu & Minggu) secara otomatis
        const daysInMonth = new Date(selectedYear, selectedMonth + 1, 0).getDate();
        for (let i = 1; i <= daysInMonth; i++) {
            const date = new Date(selectedYear, selectedMonth, i);
            const dayName = date.toLocaleDateString('id-ID', { weekday: 'long' });
            
            if (date.getDay() === 0 || date.getDay() === 6) {
                appendHolidayRow(`Libur Rutin: ${dayName}`, `${i} ${date.toLocaleDateString('id-ID', { month: 'short', year: 'numeric' })}`, 'Sistem', true);
            }
        }
    }

    function appendHolidayRow(nama, tanggal, tipe, isAuto) {
        const tableBody = document.getElementById('holidayTableBody');
        const row = document.createElement('tr');
        
        if (isAuto) {
            row.className = 'bg-slate-50/30 text-slate-400 italic';
            row.innerHTML = `
                <td class="px-6 py-4"><span class="font-medium">${nama}</span></td>
                <td class="px-6 py-4"><div class="flex items-center gap-2"><i class='bx bx-calendar-check'></i>${tanggal}</div></td>
                <td class="px-6 py-4"><span class="text-[9px] font-bold uppercase tracking-wider opacity-60">Otomatis</span></td>
                <td class="px-6 py-4 text-center"><i class='bx bx-lock-alt'></i></td>
            `;
        } else {
            const isNasional = tipe === 'Nasional';
            row.className = 'hover:bg-slate-50 transition-colors group';
            row.innerHTML = `
                <td class="px-6 py-4"><span class="font-bold text-slate-700">${nama}</span></td>
                <td class="px-6 py-4"><div class="flex items-center gap-2 text-slate-500 font-medium"><i class='bx bx-calendar text-slate-400'></i>${tanggal}</div></td>
                <td class="px-6 py-4">
                    <span class="px-2.5 py-1 ${isNasional ? 'bg-red-50 text-red-600 border-red-100' : 'bg-blue-50 text-blue-600 border-blue-100'} text-[10px] font-bold rounded-lg border uppercase">
                        ${isNasional ? 'Tanggal Merah' : 'Agenda Sekolah'}
                    </span>
                </td>
                <td class="px-6 py-4 text-center">
                    ${!isNasional ? `
                        <div class="flex items-center justify-center gap-2">
                            <button class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-amber-500 hover:bg-amber-50 rounded-lg transition-all"><i class='bx bx-edit-alt text-lg'></i></button>
                            <button class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all"><i class='bx bx-trash text-lg'></i></button>
                        </div>
                    ` : '<span class="text-[10px] text-slate-300 italic">Locked</span>'}
                </td>
            `;
        }
        tableBody.appendChild(row);
    }

    function formatDateRange(start, end) {
        const options = { day: 'numeric', month: 'short', year: 'numeric' };
        if (start.getTime() === end.getTime()) {
            return start.toLocaleDateString('id-ID', options);
        }
        return `${start.toLocaleDateString('id-ID', {day: 'numeric'})} - ${end.toLocaleDateString('id-ID', options)}`;
    }

    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.getElementById(id).classList.add('flex');
    }

    // logic tambahan untuk filter atau sync API bisa ditambahkan di sini
</script>
@endsection