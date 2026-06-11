<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HargaTiket extends Model
{
    protected $guarded = ['id'];

    public function objekWisata()
    {
        return $this->belongsTo(ObjekWisata::class, 'id_objek');
    }

    public function jenisTiket()
    {
        return $this->belongsTo(JenisTiket::class, 'id_jenis_tiket');
    }
}