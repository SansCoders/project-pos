<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class cartTransaction extends Model
{
    protected $fillable = [
        'buyer_id', 'products_id', 'buy_values', 'status'
    ];
}
