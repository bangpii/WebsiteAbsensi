@extends('layouts.siswa')

@section('title', 'Event Mendatang')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100">
        <div class="h-40 bg-indigo-100 flex items-center justify-center text-indigo-300 text-5xl">
            <i class="fas fa-mask"></i>
        </div>
        <div class="p-6">
            <div class="flex justify-between items-center mb-2">
                <span class="text-xs font-bold text-indigo-600 uppercase">Seni & Budaya</span>
                <span class="text-xs text-gray-400 italic">10 Nov 2023</span>
            </div>
            <h4 class="font-bold text-gray-800 text-lg">Pentas Seni Tahunan</h4>
        </div>
    </div>
</div>
@endsection