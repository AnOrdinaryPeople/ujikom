<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Notification extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'desc', 'read', 'sauce', 'report_id'];

    /**
     * Showing messages.
     *
     * @return \Illuminate\Support\Facades\DB
     */
    public static function adminNotif(){
        return DB::table('notifications')
            ->select('notifications.id', 'name', 'desc', 'user_id')
            ->join('users', 'users.id', '=', 'user_id')
            ->where('sauce', null)
            ->orderBy('notifications.id', 'desc')
            ->paginate(10);
    }
}
