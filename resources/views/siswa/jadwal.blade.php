@extends('layouts.siswa')

@section('title', 'Jadwal Pelajaran')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-indigo-600 p-4">
            <h3 class="text-white font-bold text-center">SENIN</h3>
        </div>
        <div class="p-4 space-y-4">
            <div class="flex justify-between items-center pb-2 border-b border-gray-50">
                <span class="text-xs text-gray-400">07:30 - 09:00</span>
                <span class="text-sm font-bold text-gray-700">Matematika</span>
            </div>
            <div class="flex justify-between items-center pb-2 border-b border-gray-50">
                <span class="text-xs text-gray-400">09:15 - 10:45</span>
                <span class="text-sm font-bold text-gray-700">Bahasa Indonesia</span>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden opacity-75">
        <div class="bg-slate-700 p-4">
            <h3 class="text-white font-bold text-center">SELASA</h3>
        </div>
        <div class="p-4 space-y-4">
            <p class="text-center text-gray-400 text-sm italic">Jadwal belum tersedia</p>
        </div>
    </div>
</div>
@endsection