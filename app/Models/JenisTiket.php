<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisTiket extends Model
{
    protected $guarded = ['id'];

    public function hargaTikets()
    {
        return $this->hasMany(HargaTiket::class, 'id_jenis_tiket');
    }
}