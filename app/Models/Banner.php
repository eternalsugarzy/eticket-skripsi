<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class Banner extends Model
{
    protected $table = 'banners';
    protected $guarded = ['id'];

    // Banner tampil di homepage yang di-cache — hapus cache begitu ada perubahan
    // supaya banner baru (dari role manapun) langsung muncul, tanpa menunggu TTL.
    protected static function booted()
    {
        static::saved(fn () => Cache::forget('landing_home_bundle'));
        static::deleted(fn () => Cache::forget('landing_home_bundle'));
    }

    // Relasi ke User (siapa yang upload banner ini)
    public function uploader()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Admin/kadis_provinsi boleh edit semua; kadis_kabkota hanya wilayahnya sendiri (via kabupaten pengupload)
    public function bisaDieditOleh(User $user): bool
    {
        if (in_array($user->role, ['admin', 'kadis_provinsi'])) {
            return true;
        }

        return $user->role === 'kadis_kabkota'
            && optional($this->uploader)->id_kabupaten == $user->id_kabupaten;
    }

    // Scope: banner yang aktif DAN dalam masa tayang (kalau ada jadwal)
    public function scopeAktifSaatIni($query)
    {
        $hariIni = Carbon::now()->toDateString();

        return $query->where('status', 'aktif')
            ->where(function ($q) use ($hariIni) {
                $q->whereNull('tanggal_mulai')->orWhere('tanggal_mulai', '<=', $hariIni);
            })
            ->where(function ($q) use ($hariIni) {
                $q->whereNull('tanggal_selesai')->orWhere('tanggal_selesai', '>=', $hariIni);
            });
    }
}