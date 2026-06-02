@extends('layouts.siswa')

@section('title', 'Pengumuman Sekolah')

@section('content')
<div class="space-y-4">
    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-orange-500">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Ujian Tengah Semester</h3>
                <p class="text-xs text-gray-400">Diposting: 25 Okt 2023</p>
            </div>
            <span class="bg-orange-50 text-orange-600 px-2 py-1 rounded text-xs font-bold uppercase">Penting</span>
        </div>
        <p class="text-gray-600 text-sm leading-relaxed">Diberitahukan kepada seluruh siswa bahwa UTS akan dilaksanakan pada...</p>
        <a href="#" class="inline-block mt-4 text-indigo-600 text-sm font-bold hover:underline">Baca Selengkapnya &rarr;</a>
    </div>
</div>
@endsection