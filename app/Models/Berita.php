<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    protected $table = 'beritas';
    protected $guarded = ['id'];

    // Relasi ke Kabupaten (nullable — null berarti berita cakupan provinsi/umum)
    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class, 'id_kabupaten');
    }

    // Relasi ke User (penulis/admin yang membuat berita)
    public function penulis()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Scope: hanya berita yang sudah dipublikasikan dan tanggal publish sudah lewat/hari ini
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                      ->where('tanggal_publish', '<=', now()->toDateString());
    }
}