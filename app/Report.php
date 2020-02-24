<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Report extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['type', 'suspect_id', 'desc', 'user_id', 'read'];

    /**
     * Showing table reports.
     *
     * @return \Illuminate\Support\Facades\DB
     */
    public static function adminReport(){
        return DB::table('reports')
            ->select('reports.id', 'name', 'type', 'reports.desc', 'reports.read', 'reports.user_id', 'notifications.id as notif_id')
            ->join('users', 'users.id', '=', 'reports.user_id')
            ->leftJoin('notifications', 'report_id', '=', 'reports.id')
            ->orderBy('reports.id', 'desc')
            ->paginate(10);
    }
}
