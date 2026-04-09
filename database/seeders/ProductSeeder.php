<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::create([
            'category_id' => null,
            'brand_produk' => 'Toyota',
            'nama_produk' => 'Brake Pad Avanza'
        ]);

        Product::create([
            'category_id' => null,
            'brand_produk' => 'Honda',
            'nama_produk' => 'Brake Pad Brio'
        ]);
    }
}