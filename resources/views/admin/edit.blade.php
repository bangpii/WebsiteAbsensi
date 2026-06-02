@extends('layouts.admin')

@section('title', 'Edit Pengumuman')

@section('content')
<div class="mb-6" data-aos="fade-down">
    <h1 class="text-2xl font-bold text-slate-800">Edit Pengumuman</h1>
    <p class="text-slate-500 text-sm mt-0.5">Perbarui informasi pengumuman yang sudah ada</p>
</div>

<div class="max-w-4xl bg-white rounded-3xl shadow-sm border border-slate-100 p-8" data-aos="fade-up">
    <form action="{{ route('admin.pengumuman.update', $pengumuman->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Judul Pengumuman</label>
            <input type="text" name="judul" value="{{ $pengumuman->judul }}" class="w-full bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3.5 transition-all" required>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Isi Pengumuman</label>
            <textarea name="isi_pengumuman" rows="6" class="w-full bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3.5 transition-all" required>{{ $pengumuman->isi_pengumuman }}</textarea>
        </div>

        <div class="w-full md:w-1/3">
            <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Status Publikasi</label>
            <select name="status" class="w-full bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3.5 transition-all cursor-pointer">
                <option value="published" {{ $pengumuman->status == 'published' ? 'selected' : '' }}>Published</option>
                <option value="draft" {{ $pengumuman->status == 'draft' ? 'selected' : '' }}>Draft</option>
            </select>
        </div>

        <div class="pt-4 flex items-center gap-3">
            <a href="{{ route('admin.pengumuman.index') }}" class="px-8 py-3.5 text-sm font-bold text-slate-500 bg-slate-100 rounded-xl hover:bg-slate-200 transition-all">
                Batal
            </a>
            <button type="submit" class="inline-flex items-center gap-2 bg-amber-500 text-white text-sm font-bold px-10 py-3.5 rounded-xl shadow-lg shadow-amber-200 hover:bg-amber-600 hover:-translate-y-0.5 transition-all">
                <i class='bx bx-save text-lg'></i> Perbarui Pengumuman
            </button>
        </div>
    </form>
</div>
@endsection