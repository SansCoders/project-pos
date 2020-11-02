<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Receipts_Transaction extends Model
{
    protected $fillable = [
        'transaction_id', 'user_id', 'user_fullname', 'cashier_name', 'products_id', 'products_list', 'products_buyvalues', 'products_prices',
        'type', 'is_done', 'done_time'
    ];

    public function buyer()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
