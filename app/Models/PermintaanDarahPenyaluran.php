<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PermintaanDarahPenyaluran extends Model
{
    protected $table = 'permintaan_darah_penyalurans';

    protected $fillable = [
        'permintaan_darah_id',
        'stok_darah_id',
        'jumlah_kantong',
        'diserahkan_oleh',
        'diserahkan_pada',
    ];

    protected $casts = [
        'jumlah_kantong' => 'integer',
        'diserahkan_pada' => 'datetime',
    ];

    public function permintaanDarah(): BelongsTo
    {
        return $this->belongsTo(
            PermintaanDarah::class,
            'permintaan_darah_id',
            'id_permintaan_darah'
        );
    }

    public function stokDarah(): BelongsTo
    {
        return $this->belongsTo(
            StokDarah::class,
            'stok_darah_id',
            'id_stok_darah'
        );
    }

    public function diserahkanOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diserahkan_oleh');
    }
}
