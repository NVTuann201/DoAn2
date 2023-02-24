<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use App;
use Config;
use Session;

class Locale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        App::setLocale(Session::has('locale')?Session::get('locale'):Config::get('app.locale'));

        return $next($request);
    }
}