<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Cashier extends Authenticatable
{
    use Notifiable;

    protected $guard = 'cashier';

    protected $fillable = [
        'name', 'username', 'password', 'status',
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];
}
