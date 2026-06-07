<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('bahan_bakus', function (Blueprint $table) {
            // Menghapus kolom stok_peringatan
            $table->dropColumn('stokperingatan');
        });
    }

    public function down(): void
    {
        Schema::table('bahan_bakus', function (Blueprint $table) {
            // Tambahkan ini agar migrasi bisa di-rollback (opsional)
            $table->integer('stokperingatan')->nullable();
        });
    }
};