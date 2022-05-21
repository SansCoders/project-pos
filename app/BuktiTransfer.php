<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BuktiTransfer extends Model
{
    protected $fillable = [
        'invoices_id', 'user_id', 'user_type', 'bank_info_id', 'bukti_transfer_image_path', 'keterangan'
    ];

    public static function getBuktiTF($invoiceId)
    {
        return self::where('invoices_id', $invoiceId)->get();
    }
}
