<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kabupaten;
use App\Models\ObjekWisata;
use App\Models\JenisTiket;
use App\Models\HargaTiket;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Akun ADMIN
        User::create([
            'nama' => 'Administrator',
            'username' => 'admin',
            'password' => Hash::make('password'), // Passwordnya: password
            'role' => 'admin',
        ]);

        // 2. Buat Akun KASIR
        User::create([
            'nama' => 'Budi Kasir',
            'username' => 'kasir',
            'password' => Hash::make('password'),
            'role' => 'kasir',
        ]);

        // 3. Buat Data Kabupaten
        $kab = Kabupaten::create([
            'nama_kabupaten' => 'Kabupaten Badung'
        ]);

        // 4. Buat Data Objek Wisata (Nyambung ke Kabupaten)
        $wisata = ObjekWisata::create([
            'id_kabupaten' => $kab->id,
            'nama_objek' => 'Pantai Pandawa',
            'alamat' => 'Desa Kutuh, Kuta Selatan',
        ]);

        // 5. Buat Jenis Tiket
        $jenisDewasa = JenisTiket::create(['nama_jenis' => 'Dewasa Domestik']);
        $jenisAnak = JenisTiket::create(['nama_jenis' => 'Anak-anak']);
        $jenisAsing = JenisTiket::create(['nama_jenis' => 'Wisatawan Asing']);

        // 6. Atur Harga Tiket (Nyambung ke Wisata & Jenis)
        HargaTiket::create([
            'id_objek' => $wisata->id,
            'id_jenis_tiket' => $jenisDewasa->id,
            'harga' => 15000,
        ]);

        HargaTiket::create([
            'id_objek' => $wisata->id,
            'id_jenis_tiket' => $jenisAnak->id,
            'harga' => 10000,
        ]);
        
        HargaTiket::create([
            'id_objek' => $wisata->id,
            'id_jenis_tiket' => $jenisAsing->id,
            'harga' => 50000,
        ]);
    }
}