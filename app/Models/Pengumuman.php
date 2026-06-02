<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;

    protected $table = 'pengumuman';

    protected $fillable = [
        'judul',
        'isi_pengumuman',
        'status', // draft atau published
        'user_id', // pengirim/pembuat
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Get the user that owns the Pengumuman.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}