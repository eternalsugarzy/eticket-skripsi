<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanans';
    protected $guarded = ['id']; // Membuka semua kolom untuk diisi kecuali ID

    // Relasi ke Objek Wisata
    public function objekWisata()
    {
        return $this->belongsTo(ObjekWisata::class, 'id_objek');
    }

    // Relasi ke Detail Pesanan
    public function details()
    {
        return $this->hasMany(PesananDetail::class, 'id_pesanan');
    }
}