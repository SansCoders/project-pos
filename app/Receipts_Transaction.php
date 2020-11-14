<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Receipts_Transaction extends Model
{
    protected $fillable = [
        'transaction_id', 'user_id', 'user_name', 'cashier_id', 'cashier_name', 'products_id', 'products_list', 'products_buyvalues', 'products_prices',
        'type', 'is_done', 'done_time', 'note', 'total_productsprices', 'order_via', 'diskon', 'custom_prices', 'status'
    ];

    public function buyer()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function facktur()
    {
        return $this->hasOne(Faktur::class, 'order_id', 'transaction_id');
    }
}
