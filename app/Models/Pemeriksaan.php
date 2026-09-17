<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pemeriksaan extends Model
{
    protected $table = 'pemeriksaans';

    protected $primaryKey = 'id_pemeriksaan';

    protected $fillable = [
        'pendonor_id',
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
}