<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryProduct extends Model
{
    protected $table = 'category_products';
    protected $fillable = [
        'name', 'status',
    ];
    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo(Product::class, 'category_id', 'id');
    }
}
