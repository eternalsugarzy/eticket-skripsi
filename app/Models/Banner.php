<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Banner extends Model
{
    protected $table = 'banners';
    protected $guarded = ['id'];

    // Relasi ke User (siapa yang upload banner ini)
    public function uploader()
    {
        return $this->belongsTo(User::class, 'id_user');
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