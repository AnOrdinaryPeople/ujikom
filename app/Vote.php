<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Vote extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['type', 'type_id', 'user_id', 'vote', 'parent_id'];

    /**
     * Find if user has vote or not.
     *
     * @param int $id
     * @param int $type
     * @param int $user
     * @param bool $first
     * 
     * @return \Illuminate\Support\Facades\DB
     */
    public static function checker($id, $type, $user, $first = false){
        $db = DB::table('votes')
            ->where('type', $type)
            ->where('type_id', $id)
            ->where('user_id', $user);
        
        return $first ? $db : $db->first();
    }

    /**
     * Count votes for post or reply.
     *
     * @param int $type
     * @param int $id
     * 
     * @return \Illuminate\Support\Facades\DB
     */
    public static function countAll($type, $id){
        return DB::table('votes')
            ->where('type', $type)
            ->where('type_id', $id)
            ->sum('vote');
    }
    /**
     * Find if user is voted.
     *
     * @param int $id
     * 
     * @return \Illuminate\Support\Facades\DB
     */
    public static function findVoted($id){
        return DB::table('votes')
            ->select('type_id', 'vote', 'user_id')
            ->whereIn('type', [1, 2, 3])
            ->where('user_id', $id)
            ->get();
    }

    /**
     * Find more detail if user is voted.
     *
     * @param int $user
     * @param int $type
     * @param int $id
     * @param int $parent
     * 
     * @return \Illuminate\Support\Facades\DB
     */
    public static function votedDetail($user, $type, $id = 0, $parent = 0){
        return DB::table('votes')
            ->select('type_id', 'vote', 'type', 'user_id')
            ->where('user_id', $user)
            ->where('type', $type)
            ->where('type_id', $id)
            ->orWhere('parent_id', $parent)
            ->get();
    }
}
