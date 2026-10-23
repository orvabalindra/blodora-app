<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StokDarah extends Model
{
    protected $table = 'stok__darahs';

    protected $primaryKey = 'id_stok_darah';

    protected $fillable = [
        'pendonor_id',
        'lokasi',
        'pendaftaran_id',
        'golongan_darah',
        'rhesus',
        'jumlah_kantong',
        'tanggal_masuk',
        'tanggal_kadaluarsa',
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
        'tanggal_kadaluarsa' => 'date',
    ];

    public function pendonor(): BelongsTo
    {
        return $this->belongsTo(Pendonor::class, 'pendonor_id', 'id');
    }

    public function pendaftaran(): BelongsTo
    {
        return $this->belongsTo(Pendaftaran::class);
    }

    public function penyalurans(): HasMany
    {
        return $this->hasMany(
            PermintaanDarahPenyaluran::class,
            'stok_darah_id',
            'id_stok_darah'
        );
    }
}
