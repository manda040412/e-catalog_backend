<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('match_cars', function (Blueprint $table) {
            $table->id('id_match');
            $table->string('product_id', 15);        // FK ke products.id_produk (VARCHAR)
            $table->string('car_brand');
            $table->string('car_type');
            $table->string('year', 20);              // "2008 - 2018" atau "2015 - ON"
            $table->string('engine_code')->nullable();
            $table->string('chassis_code')->nullable();
            $table->string('car_body')->nullable();
            $table->timestamps();

            $table->foreign('product_id')
                  ->references('id_produk')
                  ->on('products')
                  ->onDelete('cascade');

            $table->index('product_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('match_cars');
    }
};