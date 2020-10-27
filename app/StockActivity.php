<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockActivity extends Model
{
    protected $table = 'a_stock';
    protected $fillable = [
        'stocks_id', 'product_id', 'users_id', 'user_type_id', 'type_activity', 'stock'
    ];
}
