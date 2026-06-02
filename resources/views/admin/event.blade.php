@extends('layouts.admin')

@section('title', 'Manajemen Event')

@section('content')
<div class="mb-6" data-aos="fade-down">
    <h1 class="text-2xl font-bold text-slate-800">Manajemen Event Sekolah</h1>
    <p class="text-slate-500 text-sm mt-0.5">Posting dan kelola agenda kegiatan sekolah mendatang</p>
</div>

@php
    // Simulasi data statis karena database belum siap
    if (!isset($events) || (is_countable($events) && count($events) == 0)) {
        $events = collect([
            (object)[
                'id' => 1,
                'nama_event' => 'Ujian Akhir Semester Genap 2026',
                'deskripsi' => 'Pelaksanaan ujian akhir semester untuk seluruh tingkat.',
                'lokasi' => 'Ruang Kelas Masing-masing',
                'waktu_mulai' => now()->addDays(2),
                'waktu_selesai' => now()->addDays(10)
            ],
            (object)[
                'id' => 2,
                'nama_event' => 'Workshop Teknologi AI',
                'deskripsi' => 'Pelatihan pemanfaatan AI dalam proses belajar mengajar.',
                'lokasi' => 'Aula Utama Gedung B',
                'waktu_mulai' => now()->addDays(5)->setHour(9)->setMinute(0),
                'waktu_selesai' => now()->addDays(5)->setHour(15)->setMinute(0)
            ],
            (object)[
                'id' => 3,
                'nama_event' => 'Pentas Seni Tahunan',
                'deskripsi' => 'Menampilkan bakat kreativitas siswa-siswi SMKN 8 Medan.',
                'lokasi' => 'Lapangan Upacara',
                'waktu_mulai' => now()->addDays(15),
                'waktu_selesai' => now()->addDays(15)->addHours(8)
            ]
        ]);
    }
@endphp

<!-- Action Header -->
<div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 mb-6" data-aos="fade-up">
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center">
                <i class='bx bxs-calendar-event text-purple-600 text-xl'></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-800">Daftar Event</h2>
                <p class="text-xs text-slate-400">Total {{ count($events) }} event terdaftar</p>
            </div>
        </div>
        <a href="{{ route('admin.event.create') }}" class="inline-flex items-center gap-2 bg-blue-600 text-white text-sm font-bold px-6 py-3 rounded-xl shadow-lg shadow-blue-200 hover:bg-blue-700 hover:-translate-y-0.5 transition-all w-full sm:w-auto justify-center">
            <i class='bx bx-plus text-lg'></i> Tambah Event Baru
        </a>
    </div>
</div>

@if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm font-bold rounded-2xl" data-aos="fade-left">
        <i class='bx bx-check-circle mr-1'></i> {{ session('success') }}
    </div>
@endif

<!-- Table Section -->
<div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden min-h-[40vh]" data-aos="fade-up" data-aos-delay="100">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50/50 border-b border-slate-100 text-slate-500 font-bold uppercase text-[10px] tracking-wider">
                <tr>
                    <th class="px-6 py-5 text-center w-16">No</th>
                    <th class="px-6 py-5">Nama Event</th>
                    <th class="px-6 py-5">Lokasi</th>
                    <th class="px-6 py-5">Waktu Mulai</th>
                    <th class="px-6 py-5">Waktu Selesai</th>
                    <th class="px-6 py-5 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($events as $event)
                <tr class="hover:bg-slate-50/80 transition-all">
                    <td class="px-6 py-4 text-center font-medium text-slate-400">
                        {{ $loop->iteration }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-bold text-slate-700">{{ $event->nama_event }}</span>
                        <p class="text-[10px] text-slate-400 truncate max-w-xs">{{ \Illuminate\Support\Str::limit($event->deskripsi, 60) }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-1.5 text-slate-600 font-medium">
                            <i class='bx bx-map-pin text-slate-400'></i> {{ $event->lokasi }}
                        </div>
                    </td>
                    <td class="px-6 py-4 text-slate-600 font-medium">
                        {{ $event->waktu_mulai->format('d M Y, H:i') }} WIB
                    </td>
                    <td class="px-6 py-4 text-slate-600 font-medium">
                        {{ $event->waktu_selesai->format('d M Y, H:i') }} WIB
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.event.edit', $event->id) }}" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-amber-500 hover:bg-amber-50 rounded-lg transition-all">
                                <i class='bx bxs-edit-alt text-lg'></i>
                            </a>
                            <form action="{{ route('admin.event.destroy', $event->id) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all" onclick="return confirm('Hapus event ini?')">
                                    <i class='bx bxs-trash text-lg'></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-20 text-center">
                        <div class="flex flex-col items-center justify-center text-slate-400">
                            <div class="w-16 h-16 rounded-2xl bg-slate-50 flex items-center justify-center mb-3">
                                <i class='bx bx-calendar-event text-3xl'></i>
                            </div>
                            <p class="text-sm font-medium">Belum ada event yang diposting</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@if(method_exists($events, 'hasPages') && $events->hasPages())
<div class="mt-6">
    {{ $events->links() }}
</div>
@endif
@endsection
