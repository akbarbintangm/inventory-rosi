<?php

use App\Models\BahanBaku;
use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchase_details', function (Blueprint $table) {
            $table->foreignIdFor(BahanBaku::class)
                ->nullable()
                ->after('product_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('item_type')
                ->default('product')
                ->after('bahan_baku_id');
        });

        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            return;
        }

        DB::statement('ALTER TABLE purchase_details DROP FOREIGN KEY purchase_details_product_id_foreign');
        DB::statement('ALTER TABLE purchase_details MODIFY product_id BIGINT UNSIGNED NULL');

        Schema::table('purchase_details', function (Blueprint $table) {
            $table->foreign('product_id')
                ->references('id')
                ->on((new Product())->getTable())
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            Schema::table('purchase_details', function (Blueprint $table) {
                $table->dropColumn(['bahan_baku_id', 'item_type']);
            });

            return;
        }

        Schema::table('purchase_details', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropForeign(['bahan_baku_id']);
            $table->dropColumn(['bahan_baku_id', 'item_type']);
        });

        DB::statement('ALTER TABLE purchase_details MODIFY product_id BIGINT UNSIGNED NOT NULL');

        Schema::table('purchase_details', function (Blueprint $table) {
            $table->foreign('product_id')
                ->references('id')
                ->on((new Product())->getTable())
                ->cascadeOnDelete();
        });
    }
};
