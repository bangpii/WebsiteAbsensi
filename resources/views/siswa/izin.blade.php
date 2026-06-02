@extends('layouts.siswa')

@section('title', 'Pengajuan Izin')

@section('content')
<div class="max-w-xl bg-white rounded-xl shadow-sm border border-gray-100 p-8">
<div class="max-w-2xl bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="bg-gray-50 px-8 py-4 border-b border-gray-100">
        <p class="text-sm text-gray-500 font-medium">Lengkapi formulir di bawah ini untuk mengajukan izin absen.</p>
    </div>
    <div class="p-8">
    <form action="#" method="POST">
        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2 text-sm">Alasan Izin</label>
            <select class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
            <select class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition appearance-none">
                <option>Sakit</option>
                <option>Keperluan Keluarga</option>
                <option>Lainnya</option>
            </select>
        </div>
        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2 text-sm">Keterangan / Detail</label>
            <textarea class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" rows="4" placeholder="Jelaskan alasan Anda secara detail..."></textarea>
        </div>
        <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-3 rounded-lg hover:bg-indigo-700 shadow-md transition">
            Kirim Permohonan Izin
        </button>
    </form>
</div>
@endsection