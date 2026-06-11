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
    Schema::create('harga_tikets', function (Blueprint $table) {
        $table->id();
        // Relasi ke Objek Wisata
        $table->foreignId('id_objek')->constrained('objek_wisatas')->onDelete('cascade');
        // Relasi ke Jenis Tiket
        $table->foreignId('id_jenis_tiket')->constrained('jenis_tikets')->onDelete('cascade');
        
        // Harga pakai decimal biar presisi (10 digit, 2 desimal)
        $table->decimal('harga', 10, 2); 
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('harga_tikets');
    }
};
