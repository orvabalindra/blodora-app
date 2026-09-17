<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PermintaanDarah extends Model
{
    protected $table = 'permintaan__darahs';

    protected $primaryKey = 'id_permintaan_darah';

    protected $fillable = [
        'rumah_sakit_id',
        'tanggal_permintaan',
        'golongan_darah',
        'rhesus',
        'jumlah_minta',
        'status',
    ];

    protected $casts = [
        'tanggal_permintaan' => 'date',
        'jumlah_minta' => 'integer',
    ];

    public function rumahSakit(): BelongsTo
    {
        return $this->belongsTo(
            RumahSakit::class,
            'rumah_sakit_id',
            'id_rs'
        );
    }
}