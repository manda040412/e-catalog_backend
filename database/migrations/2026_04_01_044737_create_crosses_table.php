<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create("crosses", function (Blueprint $table) {
            $table->id("id_cross");
            $table->string("product_id", 15)->charset("utf8mb4")->collation("utf8mb4_unicode_ci");
            $table->string("cross_brand");
            $table->string("cross_item_code");
            $table->string("cross_nama_produk")->nullable();
            $table->timestamps();

            $table->foreign("product_id")
                  ->references("id_produk")
                  ->on("products")
                  ->onDelete("cascade");

            $table->index("cross_item_code");
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("crosses");
    }
};