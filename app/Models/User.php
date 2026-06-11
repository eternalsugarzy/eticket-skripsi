<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $guarded = ['id']; // Semua kolom boleh diisi kecuali ID

    // Helper untuk cek role (nanti berguna di Middleware)
    public function isAdmin() { return $this->role === 'admin'; }
    public function isKasir() { return $this->role === 'kasir'; }
    public function isPetugas() { return $this->role === 'petugas'; }
}