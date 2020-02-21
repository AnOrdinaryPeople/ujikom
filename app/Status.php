<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Status extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'post_id', 'title', 'desc'];

    public static function getWarn($id){
        return DB::table('statuses')
            ->select('statuses.id', 'title', 'desc', 'user_id', 'name')
            ->join('users', 'users.id', 'user_id')
            ->where('post_id', $id)
            ->first();
    }
}
