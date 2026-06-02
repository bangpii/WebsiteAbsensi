<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    protected $table = 'pengaturan';

    protected $fillable = [
        'kunci', // misal: 'nama_aplikasi', 'jam_masuk'
        'nilai', // misal: 'Sistem Absensi v1', '08:00'
        'keterangan',
    ];

    public $timestamps = false;
}