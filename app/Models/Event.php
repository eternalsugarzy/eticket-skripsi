<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'events';
    protected $guarded = ['id'];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function objekWisata()
    {
        return $this->belongsTo(ObjekWisata::class, 'id_objek');
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }
}