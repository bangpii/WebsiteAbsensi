@extends('layouts.admin')

@section('title', 'Pengaturan Sistem')

@section('content')
<div class="mb-6" data-aos="fade-down">
    <h1 class="text-2xl font-bold text-slate-800">Pengaturan Sistem</h1>
    <p class="text-slate-500 text-sm mt-0.5">Kelola konfigurasi aplikasi dan informasi sekolah</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Sidebar Pengaturan -->
    <div class="lg:col-span-1 space-y-4">
        <div class="bg-white rounded-3xl p-4 shadow-sm border border-slate-100" data-aos="fade-right">
            <nav class="space-y-1">
                <a href="#umum" class="flex items-center gap-3 px-4 py-3 bg-blue-50 text-blue-600 rounded-2xl font-bold transition-all">
                    <i class='bx bx-cog text-xl'></i>
                    <span>Umum</span>
                </a>
                <a href="#keamanan" class="flex items-center gap-3 px-4 py-3 text-slate-500 hover:bg-slate-50 rounded-2xl font-semibold transition-all">
                    <i class='bx bx-shield-quarter text-xl'></i>
                    <span>Keamanan</span>
                </a>
                <a href="#notifikasi" class="flex items-center gap-3 px-4 py-3 text-slate-500 hover:bg-slate-50 rounded-2xl font-semibold transition-all">
                    <i class='bx bx-bell text-xl'></i>
                    <span>Notifikasi</span>
                </a>
            </nav>
        </div>

        <div class="bg-gradient-to-br from-indigo-600 to-blue-700 rounded-3xl p-6 text-white shadow-lg shadow-blue-200" data-aos="fade-right" data-aos-delay="100">
            <h4 class="font-bold mb-2">Pusat Bantuan</h4>
            <p class="text-xs text-blue-100 mb-4">Hubungi tim pengembang jika Anda mengalami kendala teknis pada sistem absensi.</p>
            <a href="#" class="inline-block w-full text-center py-2.5 bg-white/20 backdrop-blur-md rounded-xl text-sm font-bold hover:bg-white/30 transition-all">
                Hubungi Support
            </a>
        </div>
    </div>

    <!-- Konten Pengaturan -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Form Informasi Sekolah -->
        <div id="umum" class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden" data-aos="fade-up">
            <div class="p-6 border-b border-slate-50 flex items-center justify-between">
                <h3 class="font-bold text-slate-800">Informasi Sekolah</h3>
                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-bold rounded-full border border-emerald-100 uppercase">Identitas</span>
            </div>
            <div class="p-6">
                <form action="#" method="POST" class="space-y-5">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2 flex items-center gap-6 mb-2">
                            <div class="relative group">
                                <div class="w-24 h-24 rounded-2xl bg-slate-100 flex items-center justify-center border-2 border-dashed border-slate-200 overflow-hidden group-hover:border-blue-400 transition-all">
                                    <i class='bx bx-image-add text-3xl text-slate-300 group-hover:text-blue-400'></i>
                                </div>
                                <button type="button" class="absolute -bottom-2 -right-2 w-8 h-8 bg-blue-600 text-white rounded-lg shadow-lg flex items-center justify-center hover:bg-blue-700 transition-transform active:scale-90">
                                    <i class='bx bx-camera'></i>
                                </button>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-700">Logo Instansi</h4>
                                <p class="text-[11px] text-slate-400 mt-1 max-w-[200px]">Gunakan gambar dengan latar belakang transparan (PNG) untuk hasil terbaik.</p>
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Nama Sekolah</label>
                            <input type="text" value="SMKN 8 Medan" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Email Sekolah</label>
                            <input type="email" value="info@smkn8medan.sch.id" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Nomor WhatsApp</label>
                            <input type="text" value="081234567890" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Alamat Lengkap</label>
                            <textarea rows="3" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">Jl. Karya Dalam No.1, Medan, Sumatera Utara</textarea>
                        </div>
                    </div>
                    
                    <div class="pt-4 border-t border-slate-50 flex justify-end">
                        <button type="button" onclick="alert('Simulasi: Perubahan disimpan!')" class="bg-blue-600 text-white text-sm font-bold px-8 py-3 rounded-xl shadow-lg shadow-blue-200 hover:bg-blue-700 transition-all active:scale-95">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Form Keamanan -->
        <div id="keamanan" class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden" data-aos="fade-up">
            <div class="p-6 border-b border-slate-50 flex items-center justify-between">
                <h3 class="font-bold text-slate-800">Keamanan Akun</h3>
                <i class='bx bxs-lock-alt text-slate-300 text-xl'></i>
            </div>
            <div class="p-6">
                <form action="#" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Password Saat Ini</label>
                        <div class="relative">
                            <input type="password" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl p-3 pr-10 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" placeholder="Masukkan password lama">
                            <i class='bx bx-hide absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 cursor-pointer hover:text-slate-600'></i>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Password Baru</label>
                            <input type="password" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" placeholder="Min. 8 karakter">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Konfirmasi Password Baru</label>
                            <input type="password" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" placeholder="Ulangi password baru">
                        </div>
                    </div>
                    
                    <div class="pt-4 border-t border-slate-50 flex justify-end">
                        <button type="button" onclick="alert('Simulasi: Password berhasil diperbarui!')" class="bg-slate-800 text-white text-sm font-bold px-8 py-3 rounded-xl shadow-lg shadow-slate-200 hover:bg-slate-900 transition-all active:scale-95">
                            Perbarui Keamanan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- System Preferences (Quick Info) -->
        <div class="bg-blue-50/50 rounded-3xl p-6 border border-blue-100 flex items-center justify-between" data-aos="fade-up">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center text-white text-xl shadow-lg shadow-blue-100">
                    <i class='bx bx-info-circle'></i>
                </div>
                <div>
                    <h4 class="font-bold text-blue-900">Versi Sistem 2.0.4</h4>
                    <p class="text-xs text-blue-600">Terakhir diperbarui pada 15 Mei 2026</p>
                </div>
            </div>
            <span class="text-[10px] font-black text-blue-400 uppercase tracking-widest">Stable Release</span>
        </div>

    </div>
</div>
@endsection