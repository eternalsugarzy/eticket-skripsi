<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// Tambahkan semua model yang direlasikan di sini
use App\Models\GaleriWisata;
use App\Models\Kabupaten;
use App\Models\HargaTiket;

class ObjekWisata extends Model
{
    protected $guarded = ['id'];

    // Tambahkan ini agar kolom fasilitas otomatis jadi array PHP <-> JSON
    protected $casts = [
        'fasilitas' => 'array',
    ];

    // Relasi ke Kabupaten (BelongsTo)
    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class, 'id_kabupaten');
    }

    // Relasi ke Galeri (HasMany)
    public function galeri()
    {
        return $this->hasMany(GaleriWisata::class, 'id_objek');
    }

    // Relasi ke Harga Tiket (HasMany)
    public function hargaTikets()
    {
        return $this->hasMany(HargaTiket::class, 'id_objek');
    }
}