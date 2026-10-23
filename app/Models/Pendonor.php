<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'tinggi_badan',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pemeriksaans(): HasMany
    {
        return $this->hasMany(
            Pemeriksaan::class,
            'pendonor_id',
            'id'
        );
    }

    public function pendaftarans(): HasMany
    {
        return $this->hasMany(Pendaftaran::class);
    }

    public function riwayatDonor(): HasMany
    {
        return $this->hasMany(
            RiwayatDonor::class,
            'pendonor_id',
            'id'
        );
    }

    public function stokDarahs(): HasMany
    {
        return $this->hasMany(
            StokDarah::class,
            'pendonor_id',
            'id'
        );
    }
}
