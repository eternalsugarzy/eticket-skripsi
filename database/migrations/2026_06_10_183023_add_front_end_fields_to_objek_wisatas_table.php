<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('objek_wisatas', function (Blueprint $table) {
            // Kolom Foto (menyimpan nama/path file gambar)
            $table->string('foto')->nullable()->after('nama_objek');
            
            // Kolom Koordinat Map (string agar fleksibel dengan tanda minus/titik)
            $table->string('latitude')->nullable()->after('alamat');
            $table->string('longitude')->nullable()->after('latitude');
            
            // Kolom Rekomendasi Tambahan untuk Front-End
            $table->string('jam_operasional')->nullable()->after('longitude'); 
            $table->enum('status', ['buka', 'tutup'])->default('buka')->after('jam_operasional');
            $table->boolean('is_populer')->default(false)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('objek_wisatas', function (Blueprint $table) {
            // Menghapus kolom jika di-rollback
            $table->dropColumn([
                'foto', 
                'latitude', 
                'longitude', 
                'jam_operasional', 
                'status', 
                'is_populer'
            ]);
        });
    }
};