@extends('layouts.admin')

@section('title', 'Tambah Pengumuman')

@section('content')
<div class="mb-6" data-aos="fade-down">
    <h1 class="text-2xl font-bold text-slate-800">Tambah Pengumuman Baru</h1>
    <p class="text-slate-500 text-sm mt-0.5">Buat informasi baru untuk warga sekolah</p>
</div>

<div class="max-w-4xl bg-white rounded-3xl shadow-sm border border-slate-100 p-8" data-aos="fade-up">
    <form action="{{ route('admin.pengumuman.store') }}" method="POST" class="space-y-6">
        @csrf
        <div>
            <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Judul Pengumuman</label>
            <input type="text" name="judul" class="w-full bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3.5 transition-all" placeholder="Masukkan judul..." required>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Isi Pengumuman</label>
            <textarea name="isi_pengumuman" rows="6" class="w-full bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3.5 transition-all" placeholder="Tulis isi pengumuman di sini..." required></textarea>
        </div>

        <div class="w-full md:w-1/3">
            <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Status Publikasi</label>
            <select name="status" class="w-full bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3.5 transition-all cursor-pointer">
                <option value="published">Published (Langsung Tampil)</option>
                <option value="draft">Draft (Simpan Saja)</option>
            </select>
        </div>

        <div class="pt-4 flex items-center gap-3">
            <a href="{{ route('admin.pengumuman.index') }}" class="px-8 py-3.5 text-sm font-bold text-slate-500 bg-slate-100 rounded-xl hover:bg-slate-200 transition-all">
                Batal
            </a>
            <button type="submit" class="inline-flex items-center gap-2 bg-blue-600 text-white text-sm font-bold px-10 py-3.5 rounded-xl shadow-lg shadow-blue-200 hover:bg-blue-700 hover:-translate-y-0.5 transition-all">
                <i class='bx bx-send text-lg'></i> Simpan & Posting
            </button>
        </div>
    </form>
</div>
@endsection