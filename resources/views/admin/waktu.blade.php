@extends('layouts.admin')

@section('title', 'Manajemen Waktu')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden" data-aos="fade-up">
    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <div>
            <h1 class="text-xl font-bold text-slate-800">Manajemen Waktu Absensi</h1>
            <p class="text-sm text-slate-500 mt-1">Atur jadwal jam masuk dan jam pulang sekolah.</p>
        </div>
        <div class="flex gap-2">
            <span class="px-3 py-1 bg-blue-50 text-blue-600 text-xs font-semibold rounded-full border border-blue-100 flex items-center gap-1">
                <i class='bx bx-info-circle'></i> Perubahan akan langsung diterapkan
            </span>
        </div>
    </div>
    
    <div class="p-6">
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl flex items-center gap-3">
                <i class='bx bxs-check-circle text-xl'></i>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <form action="{{ route('admin.waktu.update') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <!-- Kolom Kiri: Jam Operasional -->
                <div class="space-y-6">
                    <h2 class="text-sm font-semibold text-slate-400 uppercase tracking-wider">Jam Operasional Dasar</h2>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Jam Masuk (Absensi Awal)</label>
                        <input type="time" name="jam_masuk" value="{{ $settings['jam_masuk'] ?? '07:00' }}" 
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Jam Mulai Belajar</label>
                        <input type="time" name="jam_mulai_belajar" value="{{ $settings['jam_mulai_belajar'] ?? '07:30' }}" 
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Jam Pulang</label>
                        <input type="time" name="jam_pulang" value="{{ $settings['jam_pulang'] ?? '15:00' }}" 
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                    </div>
                </div>

                <!-- Kolom Kanan: Durasi & Istirahat -->
                <div class="space-y-6">
                    <h2 class="text-sm font-semibold text-slate-400 uppercase tracking-wider">Durasi & Istirahat</h2>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Durasi 1 Jam Pelajaran (Menit)</label>
                        <div class="relative">
                            <input type="number" name="durasi_pelajaran" value="{{ $settings['durasi_pelajaran'] ?? '45' }}" 
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-sm text-slate-400 font-medium">menit</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Jam Istirahat</label>
                        <input type="time" name="jam_istirahat" value="{{ $settings['jam_istirahat'] ?? '10:00' }}" 
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                    </div>
                </div>

            </div>

            <div class="mt-10 pt-6 border-t border-slate-100 flex justify-end">
                <button type="submit" class="flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-200 transition-all active:scale-95">
                    <i class='bx bxs-save text-lg'></i>
                    Simpan Pengaturan Waktu
                </button>
            </div>
        </form>
    </div>
</div>
@endsection