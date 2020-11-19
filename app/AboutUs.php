<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AboutUs extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name', 'img_company', 'phone', 'address', 'about', 'notelp'
    ];
}
