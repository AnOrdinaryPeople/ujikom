<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class Post extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'title', 'desc', 'votes', 'type', 'location', 'status_id'];

    /**
     * Get feed data for homepage.
     *
     * @param int $skip
     * 
     * @return \Illuminate\Support\Facades\DB
     */
    public static function getHome($skip){
        return DB::table('posts')
            ->select('posts.id', 'title', 'posts.desc', 'posts.votes', 'type', 'location', 'posts.created_at', 'name', DB::raw('count(replies.desc) as total'), 'posts.user_id')
            ->join('users', 'users.id', '=', 'posts.user_id')
            ->leftJoin('replies', 'posts.id', '=', 'post_id')
            ->groupBy('posts.id')
            ->orderBy('posts.id', 'desc')
            ->limit(10)
            ->offset($skip)
            ->get();
    }

    /**
     * Get user post.
     *
     * @param int $user
     * @param int $skip
     * 
     * @return \Illuminate\Support\Facades\DB
     */
    public static function getMine($user, $skip){
        return DB::table('posts')
            ->select('posts.id', 'title', 'posts.desc', 'posts.votes', 'type', 'location', 'posts.created_at', 'name', DB::raw('count(replies.desc) as total'), 'posts.user_id')
            ->join('users', 'users.id', '=', 'posts.user_id')
            ->leftJoin('replies', 'posts.id', '=', 'post_id')
            ->where('posts.user_id', $user)
            ->groupBy('posts.id')
            ->orderBy('posts.id', 'desc')
            ->limit(10)
            ->offset($skip)
            ->get();
    }

    /**
     * Get random company recommendation and articles.
     *
     * @param int $type
     * @param int $take
     * 
     * @return \Illuminate\Support\Facades\DB
     */
    public static function sideRand($type, $take){
        return DB::table('posts')
            ->select('id', 'title')
            ->where('type', $type)
            ->inRandomOrder()
            ->limit($take)
            ->get();
    }

    /**
     * Get post detail.
     *
     * @param int $id
     * 
     * @return \Illuminate\Support\Facades\DB
     */
    public static function getDetail($id){
        return DB::table('posts')
            ->select('posts.id', 'title', 'desc', 'votes', 'type', 'location', 'posts.created_at', 'posts.updated_at', 'name', 'avatar', 'user_id')
            ->join('users', 'users.id', '=', 'posts.user_id')
            ->where('posts.id', $id)
            ->first();
    }

    /**
     * Update total vote.
     *
     * @param int $id
     * @param int $count
     */
    public static function updater($id, $count){
        DB::table('posts')
            ->where('id', $id)
            ->update(['votes' => $count]);
    }

    /**
     * Search post.
     *
     * @param int $type
     * @param string $q
     * 
     * @return \Illuminate\Support\Facades\DB
     */
    public static function search($type, $q){
        return DB::table('posts')
            ->select('posts.id', 'title', 'posts.created_at', 'name', 'posts.votes', DB::raw('count(replies.desc) as total'))
            ->join('users', 'users.id', '=', 'user_id')
            ->leftJoin('replies', 'posts.id', '=', 'post_id')
            ->where('type', $type)
            ->where('title', 'like', '%'.$q.'%')
            ->groupBy('posts.id')
            ->get();
    }

    /**
     * Show tables for dashboard.
     *
     * @param string $table
     * 
     * @return \Illuminate\Support\Facades\DB
     */
    public static function adminHome($table = 'posts'){
        return DB::table($table)
            ->select(DB::raw('DATE(created_at) as label'), DB::raw('count(id) as value'))
            ->whereBetween('created_at', [Carbon::parse('-30 days')->format('Y-m-d'), now()->format('Y-m-d')])
            ->groupBy('label')
            ->get();
    }

    /**
     * Comparing type post for pie chart.
     *
     * @return \Illuminate\Support\Facades\DB
     */
    public static function adminType(){
        return DB::table('posts')
            ->select('type as label', DB::raw('count(title) as value'))
            ->groupBy('type')
            ->get();
    }

    /**
     * Comparing type post for line chart.
     *
     * @param int $type
     * 
     * @return \Illuminate\Support\Facades\DB
     */
    public static function adminTypee($type){
        return DB::table('posts')
            ->select(DB::raw('DATE(created_at) as label'), DB::raw('count(title) as value'))
            ->whereBetween('created_at', [Carbon::parse('-30 days')->format('Y-m-d'), now()->format('Y-m-d')])
            ->where('type', $type)
            ->groupBy('label')
            ->get();
    }

    /**
     * Showing table posts.
     *
     * @return \Illuminate\Support\Facades\DB
     */
    public static function adminPost(){
        return DB::table('posts')
            ->select('posts.id', 'name', 'type', 'title', 'votes', 'user_id')
            ->join('users', 'users.id', '=', 'user_id')
            ->orderBy('posts.id', 'desc')
            ->paginate(10);
    }
}
