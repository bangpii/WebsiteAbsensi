<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Admin\PengumumanController;
// use App\Http\Controllers\Admin\EventController;
// use App\Http\Controllers\Admin\PengaturanController;
// use App\Http\Controllers\Admin\WaktuController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Landing Page
Route::get('/', function () {
    return view('landingpage');
});

// Contoh rute baru untuk mengatasi 404
Route::get('/dibuat', function () {
    return "Halaman ini berhasil dibuat!";
});

// ========================
// SISWA ROUTES
// ========================
Route::prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', function () {
        return view('siswa.dashboard');
    })->name('dashboard');

    Route::get('/absensi', function () {
        return view('siswa.absensi');
    })->name('absensi');

    Route::get('/izin', function () {
        return view('siswa.izin');
    })->name('izin');

    Route::get('/jadwal', function () {
        return view('siswa.jadwal');
    })->name('jadwal');

    Route::get('/pengumuman', function () {
        return view('siswa.pengumumansiswa');
    })->name('pengumuman');

    Route::get('/event', function () {
        return view('siswa.eventsiswa');
    })->name('event');

    Route::get('/mail', function () {
        return view('siswa.mail');
    })->name('mail');
});

// ========================
// ADMIN ROUTES
// ========================
Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/akun-siswa', function () {
        return view('admin.akun-siswa');
    })->name('akun-siswa');

    Route::get('/akun-admin', function () {
        return view('admin.akun-admin');
    })->name('akun-admin');

    Route::get('/absensi', function () {
        return view('admin.absensi');
    })->name('absensi');

    Route::get('/libur', function () {
        return view('admin.libur');
    })->name('libur');

    Route::post('/libur', function () {
        return back()->with('success', 'Hari libur sekolah berhasil ditambahkan (Simulasi)');
    })->name('libur.store');

    Route::get('/izin', function () {
        return view('admin.izin');
    })->name('izin');

    Route::get('/waktu', function () {
        // Mengirimkan array kosong agar variabel $settings di view tidak undefined
        return view('admin.waktu', ['settings' => []]);
    })->name('waktu');

    // Tambahkan rute POST untuk menangani update waktu
    Route::post('/waktu/update', function () {
        return back()->with('success', 'Pengaturan waktu berhasil diperbarui (Simulasi)');
    })->name('waktu.update');

    // Rute Pengumuman (Mockup)
    Route::get('/pengumuman', function () { return view('admin.pengumuman'); })->name('pengumuman.index');
    Route::get('/pengumuman/create', function () { return view('admin.create'); })->name('pengumuman.create');
    Route::post('/pengumuman', function () { return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil dibuat (Simulasi)'); })->name('pengumuman.store');
    Route::get('/pengumuman/{id}/edit', function ($id) { 
        $pengumuman = (object)['id' => $id, 'judul' => 'Contoh Judul Pengumuman', 'isi_pengumuman' => 'Isi pengumuman simulasi.', 'status' => 'published'];
        return view('admin.edit', compact('pengumuman')); 
    })->name('pengumuman.edit');
    Route::put('/pengumuman/{id}', function () { return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman diperbarui (Simulasi)'); })->name('pengumuman.update');
    Route::delete('/pengumuman/{id}', function () { return back()->with('success', 'Pengumuman dihapus (Simulasi)'); })->name('pengumuman.destroy');

    // Rute Event (Mockup)
    Route::get('/event', function () { 
        return view('admin.event'); 
    })->name('event.index');
    Route::get('/event/create', function () { 
        return "Halaman Tambah Event (Simulasi)"; 
    })->name('event.create');
    Route::post('/event', function () { 
        return redirect()->route('admin.event.index')->with('success', 'Event berhasil dibuat (Simulasi)'); 
    })->name('event.store');
    Route::get('/event/{id}/edit', function ($id) { 
        return "Halaman Edit Event ID: $id (Simulasi)"; 
    })->name('event.edit');
    Route::delete('/event/{id}', function () { 
        return back()->with('success', 'Event dihapus (Simulasi)'); 
    })->name('event.destroy');

    Route::get('/pengaturan', function () {
        return view('admin.pengaturan');
    })->name('pengaturan');

});