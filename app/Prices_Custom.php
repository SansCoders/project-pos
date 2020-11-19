<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prices_Custom extends Model
{

    protected $fillable = [
        'user_id', 'user_type', 'product_id', 'prices_c'
    ];
    public $timestamps = false;
}
