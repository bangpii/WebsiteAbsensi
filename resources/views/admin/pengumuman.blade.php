@extends('layouts.admin')

@section('title', 'Manajemen Pengumuman')

@section('content')

@php
    // Simulasi data pengumuman jika database belum siap
    if (!isset($pengumumans) || count($pengumumans) == 0) {
        $pengumumans = collect([
            (object)[
                'id' => 1,
                'judul' => 'Pengumuman Libur Semester Genap',
                'isi_pengumuman' => 'Diberitahukan kepada seluruh siswa bahwa libur semester akan dimulai...',
                'status' => 'published',
                'created_at' => now(),
                'user' => (object)['name' => 'Admin Sekolah']
            ],
            (object)[
                'id' => 2,
                'judul' => 'Persiapan Ujian Akhir Sekolah',
                'isi_pengumuman' => 'Seluruh siswa kelas XII diwajibkan mengikuti pemantapan materi...',
                'status' => 'draft',
                'created_at' => now()->subDays(2),
                'user' => (object)['name' => 'Kurikulum']
            ]
        ]);
    }
@endphp

<div class="mb-6" data-aos="fade-down">
    <h1 class="text-2xl font-bold text-slate-800">Manajemen Pengumuman</h1>
    <p class="text-slate-500 text-sm mt-0.5">Kelola informasi dan pengumuman untuk seluruh warga sekolah</p>
</div>

<!-- Header Action Card -->
<div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 mb-6" data-aos="fade-up">
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-slate-800">Daftar Pengumuman</h2>
                <p class="text-xs text-slate-400">Total {{ isset($pengumumans) ? count($pengumumans) : 0 }} pengumuman terdaftar</p>
        </div>
        <a href="{{ route('admin.pengumuman.create') }}" class="inline-flex items-center gap-2 bg-blue-600 text-white text-sm font-bold px-6 py-3 rounded-xl shadow-lg shadow-blue-200 hover:bg-blue-700 hover:-translate-y-0.5 transition-all w-full sm:w-auto justify-center">
            <i class='bx bx-plus text-lg'></i> Tambah Pengumuman
        </a>
    </div>
</div>

@if (session('success'))
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
                    <th class="px-6 py-5">Judul Pengumuman</th>
                    <th class="px-6 py-5">Status</th>
                    <th class="px-6 py-5">Penulis</th>
                    <th class="px-6 py-5">Tanggal Dibuat</th>
                    <th class="px-6 py-5 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse ($pengumumans as $index => $p)
                <tr class="hover:bg-slate-50/80 transition-all group">
                    <td class="px-6 py-4 text-center font-medium text-slate-400">
                        {{ method_exists($pengumumans, 'currentPage') ? ($pengumumans->currentPage() - 1) * $pengumumans->perPage() + $loop->iteration : $loop->iteration }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col">
                            <span class="font-bold text-slate-700">{{ $p->judul }}</span>
                            <span class="text-[11px] text-slate-400 line-clamp-1 mt-0.5">{{ \Illuminate\Support\Str::limit($p->isi_pengumuman, 60) }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($p->status == 'published')
                            <span class="px-3 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-bold rounded-full border border-emerald-100 uppercase tracking-wider">
                                Published
                            </span>
                        @else
                            <span class="px-3 py-1 bg-slate-100 text-slate-500 text-[10px] font-bold rounded-full border border-slate-200 uppercase tracking-wider">
                                Draft
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 text-[10px] font-bold uppercase">
                                {{ substr($p->user->name ?? 'A', 0, 1) }}
                            </div>
                            <span class="text-slate-600 font-medium">{{ $p->user->name ?? 'Admin' }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-slate-500 font-medium">
                        <span class="flex items-center gap-1.5">
                            <i class='bx bx-calendar text-slate-400'></i>
                            {{ $p->created_at->format('d M Y') }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-1">
                            <a href="{{ route('admin.pengumuman.edit', $p->id) }}" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-amber-500 hover:bg-amber-50 rounded-lg transition-all" title="Edit">
                                <i class='bx bxs-edit-alt text-lg'></i>
                            </a>
                            <form action="{{ route('admin.pengumuman.destroy', $p->id) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all" 
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini?')" title="Hapus">
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
                                <i class='bx bx-megaphone text-3xl'></i>
                            </div>
                            <p class="text-sm font-medium">Belum ada pengumuman yang dibuat</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if(isset($pengumumans) && method_exists($pengumumans, 'hasPages') && $pengumumans->hasPages())
<div class="mt-6">
    {{ $pengumumans->links() }}
</div>
@endif

@endsection