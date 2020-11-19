<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'category_id', 'kodebrg', 'nama_product', 'description', 'price', 'img', 'unit_id', 'slug', 'product_status',
    ];

    public function category()
    {
        return $this->hasOne(CategoryProduct::class, 'id', 'category_id');
    }
    public function unit()
    {
        return $this->hasOne(Unit::class, 'id', 'unit_id');
    }

    public function stocks()
    {
        return $this->hasOne(Stock::class, 'product_id', 'id');
    }
}
