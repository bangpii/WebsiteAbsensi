@extends('layouts.siswa')

@section('title', 'Riwayat Absensi')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
        <h3 class="font-bold text-gray-700">Daftar Kehadiran Anda</h3>
        <span class="text-sm bg-blue-50 text-blue-600 px-3 py-1 rounded-full font-medium">Oktober 2023</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Jam Masuk</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <tr>
                    <td class="px-6 py-4 text-sm text-gray-700 font-medium">27 Okt 2023</td>
                    <td class="px-6 py-4 text-sm text-gray-500">07:15:22</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">Hadir Tepat Waktu</span>
                    </td>
                </tr>
                <tr>
                    <td class="px-6 py-4 text-sm text-gray-700 font-medium">26 Okt 2023</td>
                    <td class="px-6 py-4 text-sm text-gray-500">07:05:10</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">Hadir</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection