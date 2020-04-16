<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role', 'provider_id', 'avatar', 'email_verified_at', 'token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    /**
     * Get JWT identifier.
     *
     * @return void
     */
    public function getJWTIdentifier(){
        return $this->getKey();
    }

    /**
     * get JWT custom claims.
     *
     * @return array
     */
    public function getJWTCustomClaims(){
        return [];
    }

    /**
     * Locate has token from $email is email or token.
     *
     * @param mixed $email
     * 
     * @return \Illuminate\Support\Facades\DB
     */
    public static function findForgot($email){
        return DB::table('password_resets')
            ->where('email', $email)
            ->orWhere('token', $email)
            ->first();
    }

    /**
     * Store request reset password.
     *
     * @param string $e
     * @param string $t
     * 
     * @return \Illuminate\Support\Facades\DB
     */
    public static function makeForgot($e, $t){
        $p = 'password_resets';
        $obj = [
                'email' => $e,
                'token' => $t,
                'created_at' => now()
        ];

        if(self::findForgot($e)) DB::table($p)
            ->where('email', $e)
            ->update($obj);
        else DB::table($p)
            ->insert($obj);
    }

    /**
     * Destroy request reset password if expired.
     *
     * @param string $token
     * 
     * @return \Illuminate\Support\Facades\DB
     */
    public static function deleteForgot($token){
        DB::table('password_resets')
            ->where('token', $token)
            ->delete();
    }
    
    /**
     * Showing table users.
     *
     * @return void
     */
    public static function adminUser(){
        return DB::table('users')
            ->select('users.id', 'name', 'email', 'avatar', 'email_verified_at', DB::raw('count(posts.id) as post'), 'users.created_at')
            ->leftJoin('posts', 'posts.user_id', '=', 'users.id')
            ->where('users.id', '!=', 1)
            ->groupBy('users.id')
            ->orderBy('users.id', 'desc')
            ->paginate(9);
    }
    public static function apiGet($limit = 10, $skip = 0, $sort = 'desc', $sort_by = 'id'){
        return DB::table('users')
            ->select('id', 'name', 'email', 'email_verified_at', 'avatar', 'created_at')
            ->where('role', '!=', 1)
            ->limit($limit)
            ->offset($skip)
            ->orderBy($sort_by, $sort)
            ->get();
    }
    public static function apiGetRand($limit = 10, $skip = 0){
        return DB::table('users')
            ->select('id', 'name', 'email', 'email_verified_at', 'avatar', 'created_at')
            ->where('role', '!=', 1)
            ->limit($limit)
            ->offset($skip)
            ->inRandomOrder()
            ->get();
    }
    public static function apiGetPag($count = 10, $sort = 'desc', $sort_by = 'id'){
        return DB::table('users')
            ->select('id', 'name', 'email', 'email_verified_at', 'avatar', 'created_at')
            ->where('role', '!=', 1)
            ->orderBy($sort_by, $sort)
            ->paginate($count);
    }
}
