<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatDonor extends Model
{
    protected $table = 'riwayat__donors';

    protected $primaryKey = 'id_riwayat_donor';

    protected $fillable = [
        'pendonor_id',
        'pemeriksaan_id',
        'tanggal_donor',
        'jumlah_donor',
        'status',
    ];

    protected $casts = [
        'tanggal_donor' => 'date',
        'jumlah_donor' => 'integer',
    ];

    public function pendonor(): BelongsTo
    {
        return $this->belongsTo(
            Pendonor::class,
            'pendonor_id',
            'id'
        );
    }

    public function pemeriksaan(): BelongsTo
    {
        return $this->belongsTo(
            Pemeriksaan::class,
            'pemeriksaan_id',
            'id_pemeriksaan'
        );
    }
}