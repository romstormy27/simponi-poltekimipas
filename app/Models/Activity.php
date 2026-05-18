<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    // Tambahkan baris ini untuk membuka gerbang penyimpanan data
    protected $guarded = ['id']; 

    // Opsional: Relasi kembali ke tabel User (Dosen)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function members()
    {
        return $this->hasMany(ActivityMember::class);
    }
}