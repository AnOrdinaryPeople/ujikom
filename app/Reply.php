<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Reply extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['post_id', 'user_id', 'desc', 'votes', 'is_best', 'has_child_id'];

    /**
     * Get replies data from selected post.
     *
     * @param int $id
     * 
     * @return \Illuminate\Support\Facades\DB
     */
    public static function getReply($id){
        return DB::table('replies')
            ->select('replies.id', 'desc', 'votes', 'replies.created_at', 'replies.updated_at', 'name', 'avatar', 'user_id')
            ->join('users', 'users.id', '=', 'user_id')
            ->where('post_id', $id)
            ->where('is_best', 0)
            ->whereNull('has_child_id')
            ->orderBy('replies.id', 'desc')
            ->paginate(5);
    }

    /**
     * Get child replies from reply.
     *
     * @param int $id
     * @param int $skip
     * 
     * @return \Illuminate\Support\Facades\DB
     */
    public static function getChildReply($id, $skip = 0){
        return DB::table('replies')
            ->select('replies.id', 'desc', 'votes', 'replies.created_at', 'replies.updated_at', 'name', 'avatar', 'user_id')
            ->join('users', 'users.id', '=', 'user_id')
            ->where('has_child_id', $id)
            ->orderBy('replies.id', 'desc')
            ->limit(5)
            ->offset($skip)
            ->get();
    }

    /**
     * Find the best answer for post the type is 1.
     *
     * @param int $id
     * 
     * @return \Illuminate\Support\Facades\DB
     */
    public static function best($id){
        return DB::table('replies')
            ->select('replies.id', 'desc', 'votes', 'replies.created_at', 'replies.updated_at', 'name', 'avatar', 'user_id')
            ->join('users', 'users.id', '=', 'user_id')
            ->where('post_id', $id)
            ->where('is_best', 1)
            ->first();
    }

    /**
     * Update total vote.
     *
     * @param int $id
     * @param int $count
     */
    public static function updater($id, $count){
        DB::table('replies')
            ->where('id', $id)
            ->update(['votes' => $count]);
    }

    /**
     * Set best answer.
     *
     * @param int $id
     * @param int $val
     */
    public static function setBest($id, $val){
        DB::table('replies')
            ->where('id', $id)
            ->update(['is_best' => $val]);
    }
    
    /**
     * Show user who reply the post
     * for report user.
     *
     * @param int $id
     * @param int $user
     * 
     * @return \Illuminate\Support\Facades\DB
     */
    public static function detailChild($id, $user){
        return DB::table('replies')
            ->select('users.id', 'name')
            ->join('users', 'users.id', '=', 'user_id')
            ->where('post_id', $id)
            ->where('user_id', '!=', $user)
            ->get();
    }
    public static function apiGet($id, $limit, $skip, $sort, $sort_by, $user_id = null){
        $db = DB::table('replies')
            ->select('id', 'user_id', 'desc', 'votes', 'created_at')
            ->where('post_id', $id)
            ->limit($limit)
            ->offset($skip)
            ->orderBy($sort_by, $sort);
        
        if($user_id) return $db->where('user_id', $user_id)->get();

        return $db->get();
    }
    public static function apiGetRand($id, $limit, $skip){
        return DB::table('replies')
            ->select('id', 'user_id', 'desc', 'votes', 'created_at')
            ->where('post_id', $id)
            ->limit($limit)
            ->offset($skip)
            ->inRandomOrder()
            ->get();
    }
    public static function apiGetPag($count = 10, $id, $sort = 'desc', $sort_by = 'id'){
        return DB::table('replies')
            ->select('id', 'user_id', 'desc', 'votes', 'created_at')
            ->where('post_id', $id)
            ->orderBy($sort_by, $sort)
            ->paginate($count);
    }
}
