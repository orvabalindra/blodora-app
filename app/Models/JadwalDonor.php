<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JadwalDonor extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kegiatan',
        'lokasi',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'kuota',
    ];

    public function pendaftarans(): HasMany
    {
        return $this->hasMany(Pendaftaran::class);
    }
}