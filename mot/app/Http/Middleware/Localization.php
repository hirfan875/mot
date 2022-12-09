<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        /* change language on API call*/
        if (isset($request->lang) && $request->lang != null) {
            App::setLocale($request->lang);
        }

        if (session()->has('locale')) {
            App::setLocale(session()->get('locale'));
        }
        return $next($request);
    }
}
