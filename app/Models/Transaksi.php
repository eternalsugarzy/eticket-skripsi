<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;

    class Transaksi extends Model
    {
        protected $guarded = ['id'];

        // Relasi ke User (Kasir)
        public function kasir()
        {
            return $this->belongsTo(User::class, 'id_kasir');
        }

        // Relasi ke Objek Wisata
        public function objekWisata()
        {
            return $this->belongsTo(ObjekWisata::class, 'id_objek');
        }

        // Satu transaksi punya banyak rincian item
        public function details()
        {
            return $this->hasMany(TransaksiDetail::class, 'id_transaksi');
        }

        // Satu transaksi punya banyak tiket fisik (QR)
        public function tikets()
        {
            return $this->hasMany(Tiket::class, 'id_transaksi');
        }
    }