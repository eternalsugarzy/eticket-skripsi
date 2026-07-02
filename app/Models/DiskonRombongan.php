<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiskonRombongan extends Model
{
    protected $table = 'diskon_rombongans';
    protected $guarded = ['id'];

    /**
     * Cari tier diskon tertinggi yang berlaku untuk sejumlah orang.
     * Mengembalikan object DiskonRombongan atau null.
     */
    public static function cariDiskon(int $totalOrang): ?self
    {
        return self::where('aktif', 1)
            ->where('min_orang', '<=', $totalOrang)
            ->orderByDesc('min_orang') // ambil tier tertinggi yang berlaku
            ->first();
    }
}