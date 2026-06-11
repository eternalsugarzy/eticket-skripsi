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
    Schema::create('objek_wisatas', function (Blueprint $table) {
        $table->id();
        // INI PENTING: Relasi ke tabel kabupatens
        // onDelete('cascade') artinya jika kabupaten dihapus, objek wisatanya ikut terhapus
        $table->foreignId('id_kabupaten')->constrained('kabupatens')->onDelete('cascade');
        
        $table->string('nama_objek');
        $table->text('alamat')->nullable(); // Boleh kosong
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('objek_wisatas');
    }
};
