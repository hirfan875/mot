<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageMiddleware
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


      if($request->route()->getPrefix() == '/admin')
      {
            $locale = \Session::get('locale_admin');
      }else if($request->route()->getPrefix() == '/seller')
      {
            $locale = \Session::get('locale_seller');
      }else{
          $locale = \Session::get('locale_web');
          if(\Session::get('locale_web') == null){
              $locale = app()->getLocale();
          }

      }

        App::setLocale($locale);
        return $next($request);
    }
}
