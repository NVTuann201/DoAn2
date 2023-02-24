<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            $getId = \App\Role::query()->select('id')->whereCode('ND2019')->first();
            if(Auth::user()->role_id == $getId->id){
                return redirect('user/profile');
            }
            return redirect('users');
        }

        return $next($request);
    }
}
