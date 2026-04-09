<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $primaryKey = 'id_produk';

    protected $fillable = [
        'category_id',
        'brand_produk',
        'nama_produk'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function crosses()
    {
        return $this->hasMany(Cross::class, 'item_code', 'id_produk');
    }

    public function matchCars()
    {
        return $this->hasMany(MatchCar::class, 'item_code', 'id_produk');
    }
}