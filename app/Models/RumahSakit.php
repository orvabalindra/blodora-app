<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RumahSakit extends Model
{
    protected $table = 'rumah_sakits';

    protected $primaryKey = 'id_rs';

    protected $fillable = [
        'nama_rs',
        'alamat',
        'no_telp',
    ];

    public function permintaanDarah(): HasMany
    {
        return $this->hasMany(
            PermintaanDarah::class,
            'rumah_sakit_id',
            'id_rs'
        );
    }
}