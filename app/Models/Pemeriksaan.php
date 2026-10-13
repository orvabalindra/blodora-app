<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pemeriksaan extends Model
{
    protected $table = 'pemeriksaans';

    protected $primaryKey = 'id_pemeriksaan';

    protected $fillable = [
        'pendonor_id',
        'pendaftaran_id',
        'screened_by',
        'tanggal_pemeriksaan',
        'berat_badan',
        'tekanan_darah',
        'hemoglobin',
        'hasil',
    ];

    protected $casts = [
        'tanggal_pemeriksaan' => 'date',
        'berat_badan' => 'integer',
        'hemoglobin' => 'decimal:1',
    ];

    public function pendonor(): BelongsTo
    {
        return $this->belongsTo(
            Pendonor::class,
            'pendonor_id',
            'id'
        );
    }

    public function pendaftaran(): BelongsTo
    {
        return $this->belongsTo(Pendaftaran::class);
    }

    public function petugasScreening(): BelongsTo
    {
        return $this->belongsTo(User::class, 'screened_by');
    }

    public function riwayatDonors(): HasMany
    {
        return $this->hasMany(
            RiwayatDonor::class,
            'pemeriksaan_id',
            'id_pemeriksaan'
        );
    }
}
