<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityHistory extends Model
{
    use HasFactory;

    // Buka gerbang keamanan agar kita bisa insert data riwayat
    protected $guarded = ['id'];

    // Opsional: Relasi kembali ke tabel utama Kegiatan
    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}