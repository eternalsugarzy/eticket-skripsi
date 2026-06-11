<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MasterDataSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        // ==========================================
        // 1. DATA 13 KABUPATEN / KOTA (KAL-SEL)
        // ==========================================
        $kabupatens = [
            ['id' => 1, 'nama_kabupaten' => 'Kota Banjarmasin'],
            ['id' => 2, 'nama_kabupaten' => 'Kota Banjarbaru'],
            ['id' => 3, 'nama_kabupaten' => 'Kab. Banjar'],
            ['id' => 4, 'nama_kabupaten' => 'Kab. Tanah Laut'],
            ['id' => 5, 'nama_kabupaten' => 'Kab. Barito Kuala'],
            ['id' => 6, 'nama_kabupaten' => 'Kab. Tapin'],
            ['id' => 7, 'nama_kabupaten' => 'Kab. Hulu Sungai Selatan (HSS)'],
            ['id' => 8, 'nama_kabupaten' => 'Kab. Hulu Sungai Tengah (HST)'],
            ['id' => 9, 'nama_kabupaten' => 'Kab. Hulu Sungai Utara (HSU)'],
            ['id' => 10, 'nama_kabupaten' => 'Kab. Balangan'],
            ['id' => 11, 'nama_kabupaten' => 'Kab. Tabalong'],
            ['id' => 12, 'nama_kabupaten' => 'Kab. Tanah Bumbu'],
            ['id' => 13, 'nama_kabupaten' => 'Kab. Kotabaru'],
        ];

        foreach ($kabupatens as $data) {
            DB::table('kabupatens')->updateOrInsert(
                ['id' => $data['id']],
                array_merge($data, ['created_at' => $now, 'updated_at' => $now])
            );
        }

        // ==========================================
        // 2. DATA JENIS TIKET
        // ==========================================
        $jenisTikets = [
            ['id' => 1, 'nama_jenis' => 'Dewasa'],
            ['id' => 2, 'nama_jenis' => 'Anak-anak'],
            ['id' => 3, 'nama_jenis' => 'Mancanegara'],
        ];

        foreach ($jenisTikets as $data) {
            DB::table('jenis_tikets')->updateOrInsert(
                ['id' => $data['id']],
                array_merge($data, ['created_at' => $now, 'updated_at' => $now])
            );
        }

        // ==========================================
        // 3. DATA 20 OBJEK WISATA (UPDATE FRONT-END)
        // ==========================================
        $objekWisatas = [
            // --- Banjarmasin ---
            [
                'id' => 1, 'id_kabupaten' => 1, 'nama_objek' => 'Menara Pandang Siring', 
                'foto' => 'default.jpg', 'alamat' => 'Jl. Kapten Pierre Tendean, Gadang, Kec. Banjarmasin Tengah',
                'latitude' => '-3.3194', 'longitude' => '114.5936', 'jam_operasional' => '06:00 - 22:00 WITA', 'status' => 'buka', 'is_populer' => true,
                'deskripsi' => 'Ikon wisata sungai di tengah kota Banjarmasin dengan view sungai Martapura.'
            ],
            [
                'id' => 2, 'id_kabupaten' => 1, 'nama_objek' => 'Pasar Terapung Siring', 
                'foto' => 'default.jpg', 'alamat' => 'Jl. Kapten Pierre Tendean, Sungai Martapura',
                'latitude' => '-3.3188', 'longitude' => '114.5939', 'jam_operasional' => '06:00 - 10:00 WITA (Sabtu-Minggu)', 'status' => 'buka', 'is_populer' => true,
                'deskripsi' => 'Pasar tradisional unik di atas jukung yang beroperasi setiap akhir pekan.'
            ],
            
            // --- Banjarbaru ---
            [
                'id' => 3, 'id_kabupaten' => 2, 'nama_objek' => 'Amanah Borneo Park', 
                'foto' => 'default.jpg', 'alamat' => 'Jl. Taruna Bhakti, Palam, Kec. Cempaka',
                'latitude' => '-3.4731', 'longitude' => '114.8115', 'jam_operasional' => '09:00 - 17:00 WITA', 'status' => 'buka', 'is_populer' => true,
                'deskripsi' => 'Wahana rekreasi dan edukasi keluarga terbesar dengan fasilitas agrowisata.'
            ],
            [
                'id' => 4, 'id_kabupaten' => 2, 'nama_objek' => 'Kebun Raya Banjarbaru', 
                'foto' => 'default.jpg', 'alamat' => 'Kawasan Perkantoran Pemprov Kalsel, Cempaka',
                'latitude' => '-3.4522', 'longitude' => '114.8389', 'jam_operasional' => '08:00 - 18:00 WITA', 'status' => 'buka', 'is_populer' => false,
                'deskripsi' => 'Kawasan konservasi tumbuhan, area jogging, dan taman labirin.'
            ],
            [
                'id' => 5, 'id_kabupaten' => 2, 'nama_objek' => 'Danau Seran', 
                'foto' => 'default.jpg', 'alamat' => 'Jl. Danau Seran, Guntung Manggis, Kec. Landasan Ulin',
                'latitude' => '-3.4678', 'longitude' => '114.7944', 'jam_operasional' => '08:00 - 18:00 WITA', 'status' => 'buka', 'is_populer' => false,
                'deskripsi' => 'Danau eks galian tambang intan dengan air jernih dan pulau buatan.'
            ],

            // --- Kab. Banjar ---
            [
                'id' => 6, 'id_kabupaten' => 3, 'nama_objek' => 'Tahura Sultan Adam', 
                'foto' => 'default.jpg', 'alamat' => 'Jl. Ir. P. M. Noor, Mandiangin Timur, Karang Intan',
                'latitude' => '-3.5042', 'longitude' => '114.9083', 'jam_operasional' => '08:00 - 17:00 WITA', 'status' => 'buka', 'is_populer' => true,
                'deskripsi' => 'Taman hutan raya dengan pemandangan perbukitan dan kolam Belanda.'
            ],
            [
                'id' => 7, 'id_kabupaten' => 3, 'nama_objek' => 'Kiram Park', 
                'foto' => 'default.jpg', 'alamat' => 'Desa Kiram, Kec. Karang Intan',
                'latitude' => '-3.5283', 'longitude' => '114.8964', 'jam_operasional' => '24 Jam', 'status' => 'buka', 'is_populer' => false,
                'deskripsi' => 'Wisata alam pegunungan dengan spot foto instagramable dan villa.'
            ],
            [
                'id' => 8, 'id_kabupaten' => 3, 'nama_objek' => 'Pasar Terapung Lok Baintan', 
                'foto' => 'default.jpg', 'alamat' => 'Desa Lok Baintan, Kec. Sungai Tabuk',
                'latitude' => '-3.2981', 'longitude' => '114.6642', 'jam_operasional' => '05:00 - 09:00 WITA', 'status' => 'buka', 'is_populer' => true,
                'deskripsi' => 'Pasar terapung alami dan legendaris yang beroperasi saat subuh hari.'
            ],

            // --- Tanah Laut ---
            [
                'id' => 9, 'id_kabupaten' => 4, 'nama_objek' => 'Pantai Takisung', 
                'foto' => 'default.jpg', 'alamat' => 'Desa Takisung, Kec. Takisung',
                'latitude' => '-3.8794', 'longitude' => '114.6542', 'jam_operasional' => '24 Jam', 'status' => 'buka', 'is_populer' => false,
                'deskripsi' => 'Pantai populer dengan pemandangan sunset yang indah dan wahana banana boat.'
            ],
            [
                'id' => 10, 'id_kabupaten' => 4, 'nama_objek' => 'Pantai Batakan Baru', 
                'foto' => 'default.jpg', 'alamat' => 'Desa Batakan, Kec. Panyipatan',
                'latitude' => '-4.0019', 'longitude' => '114.6853', 'jam_operasional' => '24 Jam', 'status' => 'buka', 'is_populer' => true,
                'deskripsi' => 'Pantai luas dengan fasilitas camping ground dan dermaga.'
            ],
            [
                'id' => 11, 'id_kabupaten' => 4, 'nama_objek' => 'Air Terjun Bajuin', 
                'foto' => 'default.jpg', 'alamat' => 'Desa Sungai Bakar, Kec. Bajuin',
                'latitude' => '-3.9011', 'longitude' => '114.8524', 'jam_operasional' => '08:00 - 17:00 WITA', 'status' => 'buka', 'is_populer' => false,
                'deskripsi' => 'Air terjun alami di kaki pegunungan Meratus dengan suasana sejuk.'
            ],

            // --- Batola ---
            [
                'id' => 12, 'id_kabupaten' => 5, 'nama_objek' => 'Pulau Kembang', 
                'foto' => 'default.jpg', 'alamat' => 'Kec. Alalak, Tengah Sungai Barito',
                'latitude' => '-3.3031', 'longitude' => '114.5622', 'jam_operasional' => '08:00 - 17:00 WITA', 'status' => 'buka', 'is_populer' => false,
                'deskripsi' => 'Habitat kera ekor panjang dan bekantan di tengah delta sungai Barito.'
            ],

            // --- Tapin ---
            [
                'id' => 13, 'id_kabupaten' => 6, 'nama_objek' => 'Goa Batu Hapu', 
                'foto' => 'default.jpg', 'alamat' => 'Desa Batu Hapu, Kec. Hatungun',
                'latitude' => '-3.1114', 'longitude' => '115.1236', 'jam_operasional' => '08:00 - 16:30 WITA', 'status' => 'buka', 'is_populer' => false,
                'deskripsi' => 'Wisata goa alam dengan ornamen stalaktit dan stalagmit yang memukau.'
            ],

            // --- HSS ---
            [
                'id' => 14, 'id_kabupaten' => 7, 'nama_objek' => 'Air Panas Tanuhi', 
                'foto' => 'default.jpg', 'alamat' => 'Desa Hulu Banyu, Kec. Loksado',
                'latitude' => '-2.7936', 'longitude' => '115.4853', 'jam_operasional' => '07:00 - 18:00 WITA', 'status' => 'buka', 'is_populer' => false,
                'deskripsi' => 'Pemandian air panas alami di kawasan pegunungan Meratus.'
            ],
            [
                'id' => 15, 'id_kabupaten' => 7, 'nama_objek' => 'Bamboo Rafting Loksado', 
                'foto' => 'default.jpg', 'alamat' => 'Sungai Amandit, Kec. Loksado',
                'latitude' => '-2.7911', 'longitude' => '115.5022', 'jam_operasional' => '08:00 - 16:00 WITA', 'status' => 'buka', 'is_populer' => true,
                'deskripsi' => 'Arung jeram menggunakan rakit bambu tradisional menyusuri sungai Amandit.'
            ],

            // --- HST ---
            [
                'id' => 16, 'id_kabupaten' => 8, 'nama_objek' => 'Pagat Batu Benawa', 
                'foto' => 'default.jpg', 'alamat' => 'Desa Pagat, Kec. Batu Benawa',
                'latitude' => '-2.6531', 'longitude' => '115.4214', 'jam_operasional' => '08:00 - 17:00 WITA', 'status' => 'buka', 'is_populer' => false,
                'deskripsi' => 'Wisata alam sungai jernih dan gua di kaki bukit batu.'
            ],

            // --- Tanah Bumbu ---
            [
                'id' => 17, 'id_kabupaten' => 12, 'nama_objek' => 'Pantai Pagatan', 
                'foto' => 'default.jpg', 'alamat' => 'Kel. Kota Pagatan, Kec. Kusan Hilir',
                'latitude' => '-3.5936', 'longitude' => '115.9872', 'jam_operasional' => '24 Jam', 'status' => 'buka', 'is_populer' => false,
                'deskripsi' => 'Pantai panjang yang menjadi pusat pesta adat laut Mappanretasi.'
            ],
            [
                'id' => 18, 'id_kabupaten' => 12, 'nama_objek' => 'Goa Liang Bangkai', 
                'foto' => 'default.jpg', 'alamat' => 'Desa Dukuh Rejo, Kec. Mantewe',
                'latitude' => '-3.2842', 'longitude' => '115.7124', 'jam_operasional' => '08:00 - 17:00 WITA', 'status' => 'buka', 'is_populer' => false,
                'deskripsi' => 'Situs goa prasejarah dengan pemandangan eksotis dan jejak manusia purba.'
            ],

            // --- Kotabaru ---
            [
                'id' => 19, 'id_kabupaten' => 13, 'nama_objek' => 'Pantai Gedambaan', 
                'foto' => 'default.jpg', 'alamat' => 'Desa Gedambaan, Kec. Pulau Laut Utara',
                'latitude' => '-3.3214', 'longitude' => '116.2942', 'jam_operasional' => '07:00 - 18:00 WITA', 'status' => 'buka', 'is_populer' => false,
                'deskripsi' => 'Pantai pasir putih dengan fasilitas resort dan kolam renang.'
            ],
            [
                'id' => 20, 'id_kabupaten' => 13, 'nama_objek' => 'Bukit Mamake', 
                'foto' => 'default.jpg', 'alamat' => 'Desa Sarang Tiung, Kec. Pulau Laut Sigam',
                'latitude' => '-3.2872', 'longitude' => '116.2711', 'jam_operasional' => '24 Jam', 'status' => 'buka', 'is_populer' => true,
                'deskripsi' => 'Bukit paralayang dengan pemandangan laut dan pulau-pulau kecil.'
            ],
        ];

        foreach ($objekWisatas as $data) {
            DB::table('objek_wisatas')->updateOrInsert(
                ['id' => $data['id']],
                array_merge($data, ['created_at' => $now, 'updated_at' => $now])
            );
        }

        // ==========================================
        // 4. DATA HARGA TIKET (GENERATE OTOMATIS)
        // ==========================================
        $listHarga = [];
        
        foreach ($objekWisatas as $objek) {
            $basePrice = ($objek['id'] <= 10) ? 10000 : 5000; 

            // Harga Dewasa
            $listHarga[] = [
                'id_objek' => $objek['id'],
                'id_jenis_tiket' => 1,
                'harga' => $basePrice
            ];

            // Harga Anak
            $listHarga[] = [
                'id_objek' => $objek['id'],
                'id_jenis_tiket' => 2,
                'harga' => max(3000, $basePrice - 2000) 
            ];

            // Harga Mancanegara
            if (in_array($objek['id'], [2, 8, 12, 15])) {
                $listHarga[] = [
                    'id_objek' => $objek['id'],
                    'id_jenis_tiket' => 3,
                    'harga' => 50000 
                ];
            }
        }

        foreach ($listHarga as $data) {
            DB::table('harga_tikets')->updateOrInsert(
                [
                    'id_objek' => $data['id_objek'],
                    'id_jenis_tiket' => $data['id_jenis_tiket']
                ],
                array_merge($data, ['created_at' => $now, 'updated_at' => $now])
            );
        }
    }
}