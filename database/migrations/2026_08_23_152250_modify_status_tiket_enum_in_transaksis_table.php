<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah ENUM status_tiket untuk mengakomodasi 'pending_payment'
        DB::statement("ALTER TABLE transaksis MODIFY COLUMN status_tiket ENUM('active','used','batal','pending_payment') DEFAULT 'active'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE transaksis MODIFY COLUMN status_tiket ENUM('active','used','batal') DEFAULT 'active'");
    }
};
