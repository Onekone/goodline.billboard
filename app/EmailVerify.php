<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailVerify extends Model
{
    protected $fillable = ['user_id', 'verify_token'];
}
