<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ... $roles)
    {   
        if(empty($request->user())){
            return redirect('login');
        }
        if($request->user()->role_id == \App\Role::query()->select('id')->whereCode('ND2019')->first()->id){
            return redirect('/');
        }
        foreach($roles as $role){
            if($request->user()->role->code == $role){
                return $next($request);
            }  
        }
        return redirect()->back();
    }
    /*Cường setup lại nhé*/
}
