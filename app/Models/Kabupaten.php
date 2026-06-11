<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kabupaten extends Model
{
    protected $guarded = ['id'];

    // Satu kabupaten punya banyak objek wisata
    public function objekWisatas()
    {
        return $this->hasMany(ObjekWisata::class, 'id_kabupaten');
    }
}