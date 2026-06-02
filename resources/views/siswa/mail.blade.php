@extends('layouts.siswa')

@section('title', 'Kotak Pesan')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-4 border-b border-gray-100 flex justify-between bg-gray-50">
        <span class="font-bold text-gray-700">Semua Pesan</span>
        <button class="text-xs bg-indigo-600 text-white px-3 py-1 rounded-md font-bold">Tulis Pesan</button>
    </div>
    <div class="divide-y divide-gray-100">
        <div class="p-4 hover:bg-slate-50 cursor-pointer transition">
            <div class="flex justify-between mb-1">
                <span class="text-sm font-bold text-gray-800">Admin Sekolah</span>
                <span class="text-xs text-gray-400">Hari ini</span>
            </div>
            <p class="text-xs text-gray-500 truncate mt-1">Mohon segera melengkapi berkas untuk pendaftaran ujian semester ganjil sebelum akhir pekan ini.</p>
        </div>
        <div class="p-4 hover:bg-slate-50 cursor-pointer transition border-l-4 border-transparent hover:border-indigo-500">
            <div class="flex justify-between mb-1">
                <span class="text-sm font-bold text-gray-800">Wali Kelas</span>
                <span class="text-xs text-gray-400">Kemarin</span>
            </div>
            <p class="text-xs text-gray-500 truncate mt-1">Informasi terkait rapat wali murid yang akan dilaksanakan secara daring.</p>
        </div>
    </div>
</div>
@endsection