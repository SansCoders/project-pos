<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AboutUs extends Model
{
    protected $fillable = [
        'name', 'img_company', 'phone', 'address', 'about'
    ];
}
