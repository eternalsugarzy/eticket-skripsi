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
    Schema::create('tikets', function (Blueprint $table) {
        $table->id();
        // Terhubung ke transaksi mana?
        $table->foreignId('id_transaksi')->constrained('transaksis')->onDelete('cascade');
        
        // Kode unik untuk QR Code (ex: TIKET-2023-001-X7Z)
        $table->string('kode_unik')->unique(); 
        
        // Status tiket
        $table->enum('status', ['valid', 'terpakai'])->default('valid');
        
        // Kapan tiket ini dipakai/discan? (Boleh kosong awalnya)
        $table->dateTime('waktu_validasi')->nullable();
        
        // Siapa petugas yang men-scan? (Boleh kosong awalnya)
        // Kita buat nullable karena saat baru dibeli, belum ada yang scan.
        $table->foreignId('id_petugas')->nullable()->constrained('users');
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tikets');
    }
};
