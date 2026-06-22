<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom validasi ke tabel transaksis (offline/kasir)
        Schema::table('transaksis', function (Blueprint $table) {
            $table->enum('status_tiket', ['active', 'used', 'batal'])->default('active')->after('id_objek');
            $table->timestamp('waktu_validasi')->nullable()->after('status_tiket');
        });

        // Tambah kolom validasi ke tabel pesanans (online)
        Schema::table('pesanans', function (Blueprint $table) {
            $table->enum('status_tiket', ['active', 'used'])->default('active')->after('status_pembayaran');
            $table->timestamp('waktu_validasi')->nullable()->after('status_tiket');
        });
    }

    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn(['status_tiket', 'waktu_validasi']);
        });

        Schema::table('pesanans', function (Blueprint $table) {
            $table->dropColumn(['status_tiket', 'waktu_validasi']);
        });
    }
};