<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Kabupaten extends Model
{
    protected $guarded = ['id'];

    // Satu kabupaten punya banyak objek wisata
    public function objekWisatas()
    {
        return $this->hasMany(ObjekWisata::class, 'id_kabupaten');
    }

    // Daftar 13 kabupaten/kota — hampir tidak pernah berubah, jadi di-cache 12 jam.
    public static function cached()
    {
        return Cache::remember('kabupaten_all', now()->addHours(12), function () {
            return static::orderBy('nama_kabupaten')->get();
        });
    }

    // Hapus cache daftar kabupaten setiap kali data disimpan/diubah/dihapus,
    // supaya admin langsung melihat perubahan tanpa menunggu TTL.
    protected static function booted()
    {
        static::saved(fn () => Cache::forget('kabupaten_all'));
        static::deleted(fn () => Cache::forget('kabupaten_all'));
    }
}