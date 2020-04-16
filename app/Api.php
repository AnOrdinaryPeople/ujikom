<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Api extends Model
{
    protected $fillable = ['user_id', 'access_token', 'secret_token'];
}
