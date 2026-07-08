<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// Tambahkan semua model yang direlasikan di sini
use App\Models\GaleriWisata;
use App\Models\Kabupaten;
use App\Models\HargaTiket;
use App\Models\Ulasan;

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

    // Relasi ke Ulasan (HasMany)
    public function ulasans()
    {
        return $this->hasMany(Ulasan::class, 'id_objek')->latest();
    }

    // Accessor: rata-rata rating (dibulatkan 1 desimal), 0 kalau belum ada ulasan
    public function getRatingRataRataAttribute()
    {
        return round($this->ulasans()->avg('rating') ?? 0, 1);
    }

    // Accessor: jumlah total ulasan
    public function getJumlahUlasanAttribute()
    {
        return $this->ulasans()->count();
    }
}