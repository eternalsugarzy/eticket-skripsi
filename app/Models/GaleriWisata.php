<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GaleriWisata extends Model
{
    protected $guarded = ['id'];

    // Relasi ke ObjekWisata
    public function objekWisata()
    {
        return $this->belongsTo(ObjekWisata::class, 'id_objek');
    }
}