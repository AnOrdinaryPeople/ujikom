<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ApiLog extends Model
{
    protected $fillable = ['api_id', 'description', 'status'];

    public static function dashboard($id, $status = null){
        if($status !== null) return DB::table('api_logs')
            ->select(DB::raw('DATE(created_at) as label'), DB::raw('count(id) as value'))
            ->where('api_id', $id)
            ->where('status', $status)
            ->whereBetween('created_at', [Carbon::parse('-30 days')->format('Y-m-d'), Carbon::parse('+1 days')->format('Y-m-d')])
            ->groupBy('label')
            ->get();
        else return DB::table('api_logs')
            ->select(DB::raw('DATE(created_at) as label'), DB::raw('count(id) as value'))
            ->where('api_id', $id)
            ->whereBetween('created_at', [Carbon::parse('-30 days')->format('Y-m-d'), Carbon::parse('+1 days')->format('Y-m-d')])
            ->groupBy('label')
            ->get();
    }
}
