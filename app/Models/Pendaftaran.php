<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pendaftaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'pendonor_id',
        'jadwal_donor_id',
        'status',
        'keterangan_medis',
    ];

    public function pendonor(): BelongsTo
    {
        return $this->belongsTo(Pendonor::class);
    }

    public function jadwalDonor(): BelongsTo
    {
        return $this->belongsTo(JadwalDonor::class);
    }
}