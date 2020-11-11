<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfileUser extends Model
{
    protected $table = 'profile_users';
    protected $fillable = [
        'fullname', 'gender', 'birth_date', 'photo', 'user_id', 'user_type'
    ];
    public $timestamps = false;
}
