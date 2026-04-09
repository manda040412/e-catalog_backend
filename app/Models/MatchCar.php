<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchCar extends Model
{
    protected $primaryKey = 'id_match';

    protected $fillable = [
        'item_code',
        'car_brand',
        'car_type',
        'year',
        'engine_code',
        'chassis_code',
        'car_body'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'item_code', 'id_produk');
    }
}