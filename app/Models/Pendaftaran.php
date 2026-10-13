<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pendaftaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'pendonor_id',
        'jadwal_donor_id',
        'status',
        'keterangan_medis',
        'tanggal_selesai_donor',
    ];

    protected $casts = [
        'tanggal_selesai_donor' => 'date',
    ];

    public function pendonor(): BelongsTo
    {
        return $this->belongsTo(Pendonor::class);
    }

    public function jadwalDonor(): BelongsTo
    {
        return $this->belongsTo(JadwalDonor::class);
    }

    public function pemeriksaans(): HasMany
    {
        return $this->hasMany(Pemeriksaan::class);
    }

    public function riwayatDonor(): HasOne
    {
        return $this->hasOne(RiwayatDonor::class);
    }

    public function stokDarah(): HasOne
    {
        return $this->hasOne(StokDarah::class);
    }
}
