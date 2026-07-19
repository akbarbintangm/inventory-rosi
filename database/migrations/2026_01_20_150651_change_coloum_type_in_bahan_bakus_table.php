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
        // SQLite already gets this column as a string from the create migration and
        // cannot run change() without Doctrine DBAL.
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            return;
        }

        Schema::table('bahan_bakus', function (Blueprint $table) {
            //ganti tipe data kodebahan int ke string
            $table->string('kodebahan')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            return;
        }

        Schema::table('bahan_bakus', function (Blueprint $table) {
            $table->integer('kodebahan')->change();
        });
    }
};
