<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }

    protected function authenticate($request, array $guards)
    {
        if(empty($guards)) $guards = [null];

        foreach ($guards as $key) {
            if($this->auth->guard($key)->check())
                return $this->auth->shouldUse($key);
        }

        return 'auth_err';
    }

    public function handle($req, Closure $next, ...$guards){
        if($this->authenticate($req, $guards) === 'auth_err')
            return response()->json(['error' => 'Unauthorized']);

        return $next($req);
    }
}
