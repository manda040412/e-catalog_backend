<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->string('id_produk', 15)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->primary(); // PROD1, PROD2, dst
            $table->string('category_id', 10)->nullable();
            $table->string('item_code')->unique();
            $table->string('brand_produk');
            $table->string('nama_produk');
            $table->timestamps();

            $table->foreign('category_id')
                  ->references('id_category')
                  ->on('categories')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
