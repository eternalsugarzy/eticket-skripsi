<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tiket extends Model
{
    protected $guarded = ['id'];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'id_transaksi');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'id_petugas');
    }
}