<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    protected $table = 'ulasans';
    protected $guarded = ['id'];

    public function pengunjung()
    {
        return $this->belongsTo(Pengunjung::class, 'id_pengunjung');
    }

    public function objekWisata()
    {
        return $this->belongsTo(ObjekWisata::class, 'id_objek');
    }

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan');
    }

    /**
     * Cek apakah pengunjung boleh memberi ulasan untuk objek wisata tertentu.
     * Syarat: pernah punya pesanan LUNAS ke objek ini, dan belum pernah ulasan.
     */
    public static function bisaUlasan(int $idPengunjung, int $idObjek): bool
    {
        $sudahBeliLunas = Pesanan::where('id_pengunjung', $idPengunjung)
            ->where('id_objek', $idObjek)
            ->where('status_pembayaran', 'Paid')
            ->exists();

        $sudahUlasan = self::where('id_pengunjung', $idPengunjung)
            ->where('id_objek', $idObjek)
            ->exists();

        return $sudahBeliLunas && !$sudahUlasan;
    }
}