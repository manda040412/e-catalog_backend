<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cross extends Model
{
    protected $primaryKey = 'id_cross';

    protected $fillable = [
        'item_code',
        'owner',
        'oem_number'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'item_code', 'id_produk');
    }
}