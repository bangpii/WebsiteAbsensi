<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AkunSiswaController;
use App\Http\Controllers\Admin\LiburControllerAdmin;
use App\Http\Controllers\Admin\IzinAdminController;
use App\Http\Controllers\Admin\WaktuAdminController;
use App\Http\Controllers\Admin\CmsPengumumanAdminController;
use App\Http\Controllers\Admin\CmsEventAdminController;
use App\Http\Controllers\Login\AuthLoginAdminController;
use App\Http\Controllers\Admin\LokasiAdminController;
use App\Http\Controllers\Admin\AbsensiAdminController;
use App\Http\Controllers\Admin\DashboardAdminController;

/*
|--------------------------------------------------------------------------
| ROOT — Redirect ke Siswa Landing
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('siswa.landingpage');
});

/*
|--------------------------------------------------------------------------
| SISWA — Landing Page & Halaman Siswa
|--------------------------------------------------------------------------
*/

Route::prefix('siswa')
    ->name('siswa.')
    ->group(function () {

        Route::get('/landingpage', function () {
            return view('landingpage');
        })->name('landingpage');

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

/*
|--------------------------------------------------------------------------
| AUTH ADMIN — Login & Logout
|--------------------------------------------------------------------------
*/

Route::get('/admin/login', [AuthLoginAdminController::class, 'index'])
    ->name('login');

Route::post('/admin/login', [AuthLoginAdminController::class, 'login'])
    ->name('login.post');

Route::post('/admin/logout', [AuthLoginAdminController::class, 'logout'])
    ->name('logout');

/*
|--------------------------------------------------------------------------
| ADMIN — Dilindungi Middleware admin.session
|--------------------------------------------------------------------------
*/

Route::middleware('admin.session')
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        /*
        |------------------------------------------------------------------
        | Dashboard
        |------------------------------------------------------------------
        */

        Route::get(
            '/dashboard',
            [DashboardAdminController::class, 'index']
        )->name('dashboard');

        /*
        |------------------------------------------------------------------
        | Akun Siswa
        |------------------------------------------------------------------
        */

        Route::get('/akun-siswa', [AkunSiswaController::class, 'index'])
            ->name('akun-siswa');

        Route::post('/akun-siswa/sync', [AkunSiswaController::class, 'sync'])
            ->name('akun-siswa.sync');

        Route::get('/akun-siswa/{id}', [AkunSiswaController::class, 'show'])
            ->name('akun-siswa.show');

        /*
        |------------------------------------------------------------------
        | Akun Admin
        |------------------------------------------------------------------
        */

        Route::get('/akun-admin', function () {
            return view('admin.akun-admin');
        })->name('akun-admin');

        /*
        |--------------------------------------------------------------------------
        | ABSENSI
        |--------------------------------------------------------------------------
        */

        Route::get('/absensi', [AbsensiAdminController::class, 'index'])
            ->name('absensi');
        Route::get('/absensi/statistik', [AbsensiAdminController::class, 'statistik'])
            ->name('absensi.statistik');
        Route::get('/absensi/{id}', [AbsensiAdminController::class, 'show'])
            ->name('absensi.show');
        Route::put('/absensi/{id}', [AbsensiAdminController::class, 'update'])
            ->name('absensi.update');
        Route::delete('/absensi/{id}', [AbsensiAdminController::class, 'destroy'])
            ->name('absensi.destroy');

       /*
        |--------------------------------------------------------------------------
        | Libur
        |--------------------------------------------------------------------------
        */

        Route::get('/libur', [LiburControllerAdmin::class, 'index'])
        ->name('libur');

        Route::post('/libur', [LiburControllerAdmin::class, 'store'])
        ->name('libur.store');

        Route::put('/libur/{id}', [LiburControllerAdmin::class, 'update'])
        ->name('libur.update');

        Route::delete('/libur/{id}', [LiburControllerAdmin::class, 'destroy'])
        ->name('libur.destroy');

        Route::post('/libur/{id}/toggle', [LiburControllerAdmin::class, 'toggle'])
        ->name('libur.toggle');

        Route::get('/libur/{id}', [LiburControllerAdmin::class, 'show'])
        ->name('libur.show');

        /*
        |--------------------------------------------------------------------------
        | Izin & Sakit
        |--------------------------------------------------------------------------
        */

        Route::get('/izin', [IzinAdminController::class, 'index'])
        ->name('izin');

        Route::get('/izin/{id}', [IzinAdminController::class, 'show'])
            ->name('izin.show');

        Route::post('/izin/{id}/approve', [IzinAdminController::class, 'approve'])
            ->name('izin.approve');

        Route::post('/izin/{id}/pesan', [IzinAdminController::class, 'kirimPesan'])
            ->name('izin.pesan');

        Route::post('/izin/{id}/read', [IzinAdminController::class, 'markAsRead'])
            ->name('izin.read');
      /*
    |--------------------------------------------------------------------------
    | Pengaturan Waktu
    |--------------------------------------------------------------------------
    */

    Route::get('/waktu', [WaktuAdminController::class, 'index'])
    ->name('waktu');

    Route::post('/waktu', [WaktuAdminController::class, 'store'])
    ->name('waktu.store');

    Route::get('/waktu/{id}', [WaktuAdminController::class, 'show'])
    ->name('waktu.show');

    Route::put('/waktu/{id}', [WaktuAdminController::class, 'update'])
    ->name('waktu.update');

    Route::delete('/waktu/{id}', [WaktuAdminController::class, 'destroy'])
    ->name('waktu.destroy');

    Route::post('/waktu/{id}/toggle', [WaktuAdminController::class, 'toggle'])
    ->name('waktu.toggle');

        /*
        |--------------------------------------------------------------------------
        | Pengumuman
        |--------------------------------------------------------------------------
        */

        Route::get('/pengumuman', [CmsPengumumanAdminController::class, 'index'])
        ->name('pengumuman.index');

        Route::post('/pengumuman', [CmsPengumumanAdminController::class, 'store'])
        ->name('pengumuman.store');

        Route::get('/pengumuman/{id}', [CmsPengumumanAdminController::class, 'show'])
        ->name('pengumuman.show');

        Route::put('/pengumuman/{id}', [CmsPengumumanAdminController::class, 'update'])
        ->name('pengumuman.update');

        Route::delete('/pengumuman/{id}', [CmsPengumumanAdminController::class, 'destroy'])
        ->name('pengumuman.destroy');

        Route::post('/pengumuman/{id}/toggle', [CmsPengumumanAdminController::class, 'toggle'])
        ->name('pengumuman.toggle');

       /*
        |--------------------------------------------------------------------------
        | Event
        |--------------------------------------------------------------------------
        */

        Route::get('/event', [CmsEventAdminController::class, 'index'])
        ->name('event.index');

        Route::post('/event', [CmsEventAdminController::class, 'store'])
        ->name('event.store');

        Route::get('/event/{id}', [CmsEventAdminController::class, 'show'])
        ->name('event.show');

        Route::put('/event/{id}', [CmsEventAdminController::class, 'update'])
        ->name('event.update');

        Route::delete('/event/{id}', [CmsEventAdminController::class, 'destroy'])
        ->name('event.destroy');

        Route::post('/event/{id}/toggle', [CmsEventAdminController::class, 'toggle'])
        ->name('event.toggle');

        /*
        |--------------------------------------------------------------------------
        | Lokasi
        |--------------------------------------------------------------------------
        */

        Route::get('/lokasi', [LokasiAdminController::class, 'index'])
        ->name('lokasi');

        Route::get('/lokasi/detail', [LokasiAdminController::class, 'show'])
            ->name('lokasi.show');

        Route::put('/lokasi', [LokasiAdminController::class, 'update'])
            ->name('lokasi.update');

        /*
        |------------------------------------------------------------------
        | Pengaturan
        |------------------------------------------------------------------
        */

        Route::get('/pengaturan', function () {
            return view('admin.pengaturan');
        })->name('pengaturan');
    });
