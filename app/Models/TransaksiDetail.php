<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiDetail extends Model
{
    // TAMBAHAN PENTING: Beritahu Laravel nama tabel yang benar
    protected $table = 'detail_transaksis'; 
    
    protected $guarded = ['id'];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'id_transaksi');
    }

    // Perbaiki relasi ini agar sesuai dengan Controller yang pakai 'id_jenis_tiket'
    public function jenisTiket() 
    {
        return $this->belongsTo(JenisTiket::class, 'id_jenis_tiket');
    }
}