<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankInfo extends Model
{
    protected $fillable = [
        'bank_name', 'rekening_number', 'rekening_owner_name', 'qr_code'
    ];
}
