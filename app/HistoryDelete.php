<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoryDelete extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'desc'];
}
