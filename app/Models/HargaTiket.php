<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HargaTiket extends Model
{
    use HasFactory;

    protected $table = 'harga_tikets'; // Sesuaikan jika nama tabel Anda berbeda
    protected $guarded = ['id'];

    // Relasi ke tabel Jenis Tiket
    public function jenisTiket()
    {
        return $this->belongsTo(JenisTiket::class, 'id_jenis_tiket');
    }

    // Relasi ke tabel Objek Wisata
    public function objekWisata()
    {
        return $this->belongsTo(ObjekWisata::class, 'id_objek');
    }
}