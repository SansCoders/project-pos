<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = [
        'product_id', 'stock'
    ];
    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo(Product::class, 'id', 'product_id');
    }
}
