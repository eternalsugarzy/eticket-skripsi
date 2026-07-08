<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $table = 'wishlists';
    protected $guarded = ['id'];

    public function pengunjung()
    {
        return $this->belongsTo(Pengunjung::class, 'id_pengunjung');
    }

    public function objekWisata()
    {
        return $this->belongsTo(ObjekWisata::class, 'id_objek');
    }
}