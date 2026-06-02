@extends('layouts.admin')

@section('title', 'Data Pengguna')

@section('content')

<div class="mb-6" data-aos="fade-down">
    <h1 class="text-2xl font-bold text-slate-800">Data Pengguna</h1>
    <p class="text-slate-500 text-sm mt-0.5">Manajemen akun administrator sistem</p>
</div>

<div class="flex items-center justify-center min-h-[60vh]" data-aos="zoom-in">
    <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-14 text-center max-w-md w-full">
        <div class="w-20 h-20 rounded-2xl bg-blue-50 flex items-center justify-center mx-auto mb-5 shadow-sm">
            <i class='bx bxs-shield-alt-2 text-blue-600 text-4xl'></i>
        </div>
        <h2 class="text-2xl font-bold text-slate-800 mb-2">Hello World</h2>
        <p class="text-slate-500 text-sm">Halaman <span class="text-blue-600 font-semibold">Data Pengguna</span> sedang dalam pengembangan.</p>
        <div class="mt-6 inline-flex items-center gap-2 bg-blue-600 text-white text-sm font-semibold px-5 py-2.5 rounded-xl shadow hover:bg-blue-700 transition cursor-pointer">
            <i class='bx bx-plus'></i> Tambah Admin
        </div>
    </div>
</div>

@endsection