<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $guarded = ['id']; // Semua kolom boleh diisi kecuali ID

    // Relasi ke kabupaten (khusus role kadis_kabkota)
    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class, 'id_kabupaten');
    }

    // Helper cek role — biar controller/view lebih rapi
    public function isAdmin()        { return $this->role === 'admin'; }
    public function isKadisProvinsi(){ return $this->role === 'kadis_provinsi'; }
    public function isKadisKabkota() { return $this->role === 'kadis_kabkota'; }
    public function isKasir()        { return $this->role === 'kasir'; }
    public function isPetugas()      { return $this->role === 'petugas'; }
}