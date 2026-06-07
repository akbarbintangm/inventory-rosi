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
        Schema::create('bahan_bakus', function (Blueprint $table) {
            $table->id();
            $table->string('kodebahan');
            $table->foreignId('user_id');
           // $table->foreignId('category_id');
            //$table->foreignId('unit_id');
            $table->string('namabahan');
            $table->integer('stokbahan');
            $table->integer('stokperingatan');
            $table->string('jenisbahan'); // buat ditampian select kain/aksesoris
            $table->string('detailbahan');
            $table->date('tanggalmasuk');
            $table->integer('hargabeli');
            $table->string('fotobahan')->nullable();

            $table->foreignIdFor(\App\Models\Category::class)
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignIdFor(\App\Models\Unit::class)->constrained()
                ->cascadeOnDelete();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bahan_bakus');
    }
};
