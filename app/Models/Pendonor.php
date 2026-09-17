<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pemeriksaan;
use App\Models\RiwayatDonor;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pendonor extends Model
{
    protected $table = 'pendonors';

    protected $fillable = [
        'user_id',
        'nik',
        'nama_lengkap',
        'no_hp',
        'alamat',
        'golongan_darah',
        'rhesus',
        'berat_badan',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pemeriksaans()
    {
        return $this->hasMany(
            Pemeriksaan::class,
            'pendonor_id',
            'id'
        );
    }

    public function riwayatDonor()
{
    return $this->hasMany(
        RiwayatDonor::class,
        'pendonor_id',
        'id'
    );
}
}