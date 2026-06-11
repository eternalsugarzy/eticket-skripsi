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
            // Menambahkan kolom deskripsi setelah nama_objek
            // nullable() artinya boleh kosong (biar aman untuk data lama)
            $table->text('deskripsi')->nullable()->after('nama_objek');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('objek_wisatas', function (Blueprint $table) {
            $table->dropColumn('deskripsi');
        });
    }
};