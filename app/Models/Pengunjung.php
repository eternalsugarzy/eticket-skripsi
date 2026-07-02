<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pengunjung extends Authenticatable
{
    use Notifiable;

    protected $table = 'pengunjungs';

    protected $guarded = ['id'];

    protected $hidden = ['password', 'remember_token'];

    // Relasi ke riwayat pesanan tiket miliknya
    public function pesanans()
    {
        return $this->hasMany(Pesanan::class, 'id_pengunjung');
    }
}