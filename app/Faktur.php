<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Faktur extends Model
{
    protected $fillable = [
        'order_id', 'faktur_number'
    ];

    public function ReceiptData()
    {
        return $this->hasOne(Receipts_Transaction::class, 'transaction_id', 'order_id');
    }
}
