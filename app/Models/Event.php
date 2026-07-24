<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Event extends Model
{
    protected $table = 'events';
    protected $guarded = ['id'];

    // Event tampil di homepage yang di-cache — hapus cache begitu ada perubahan
    // supaya event baru (dari role manapun) langsung muncul, tanpa menunggu TTL.
    protected static function booted()
    {
        static::saved(fn () => Cache::forget('landing_home_bundle'));
        static::deleted(fn () => Cache::forget('landing_home_bundle'));
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function objekWisata()
    {
        return $this->belongsTo(ObjekWisata::class, 'id_objek');
    }

    // Admin/kadis_provinsi boleh edit semua; kadis_kabkota hanya wilayahnya sendiri
    // (via kabupaten objek wisata terkait, fallback ke kabupaten pengupload)
    public function bisaDieditOleh(User $user): bool
    {
        if (in_array($user->role, ['admin', 'kadis_provinsi'])) {
            return true;
        }

        if ($user->role !== 'kadis_kabkota') {
            return false;
        }

        $idKabupatenEvent = $this->objekWisata->id_kabupaten ?? optional($this->uploader)->id_kabupaten;

        return $idKabupatenEvent == $user->id_kabupaten;
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }
}