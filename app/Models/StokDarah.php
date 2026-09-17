<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokDarah extends Model
{
    protected $table = 'stok__darahs';

    protected $primaryKey = 'id_stok_darah';

    protected $fillable = [
        'pendonor_id',
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

    public function pendonor()
    {
        return $this->belongsTo(
            Pendonor::class,
            'pendonor_id',
            'id'
        );
    }
}