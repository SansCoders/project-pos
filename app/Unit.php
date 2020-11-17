<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = [
        'unit', 'status',
    ];
    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo(Product::class, 'unit_id', 'id');
    }
}
