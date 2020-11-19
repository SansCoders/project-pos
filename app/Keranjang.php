<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'user_id', 'product_id', 'buy_value', 'user_type', 'custom_price'
    ];
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
