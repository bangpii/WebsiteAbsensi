@extends('layouts.siswa')

@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div class="flex items-center">
            <div class="p-3 rounded-lg bg-indigo-50 text-indigo-600 mr-4">
                <i class="fas fa-user-clock fa-2x"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Status Hari Ini</p>
                <p class="text-lg font-bold text-gray-800 text-green-600">Sudah Absen</p>
            </div>
        </div>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div class="flex items-center">
            <div class="p-3 rounded-lg bg-blue-50 text-blue-600 mr-4">
                <i class="fas fa-calendar-check fa-2x"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Kehadiran</p>
                <p class="text-lg font-bold text-gray-800">18 Hari</p>
            </div>
        </div>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div class="flex items-center">
            <div class="p-3 rounded-lg bg-orange-50 text-orange-600 mr-4">
                <i class="fas fa-envelope-open-text fa-2x"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Pesan Baru</p>
                <p class="text-lg font-bold text-gray-800">3 Pesan</p>
            </div>
        </div>
    </div>
</div>

<div class="mt-8 bg-indigo-600 rounded-2xl p-8 text-white shadow-lg">
    <h1 class="text-2xl font-bold mb-2">Selamat Datang, John!</h1>
    <p class="text-indigo-100">Jangan lupa untuk selalu memantau jadwal dan pengumuman terbaru dari sekolah.</p>
</div>
@endsection