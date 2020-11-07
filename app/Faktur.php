<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Faktur extends Model
{
    protected $fillable = [
        'order_id', 'faktur_number'
    ];
}
