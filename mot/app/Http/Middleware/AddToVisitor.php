<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\LogActivity;

class AddToVisitor {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {
        $response = $next($request);
        $count = 0;

        function ip_details($IPaddress) {
            $details='';
            if (filter_var($IPaddress, FILTER_VALIDATE_IP)) {
            $json = file_get_contents("http://ipinfo.io/{$IPaddress}");
            $details = json_decode($json);
            }
            return $details;
        }

        $details = ip_details(request()->ip());
        if (preg_match('/bot|crawl|curl|dataprovider|search|get|spider|find|java|majesticsEO|google|yahoo|teoma|contaxe|yandex|libwww-perl|facebookexternalhit/i', $_SERVER['HTTP_USER_AGENT'])) {
            // is bot
        } else {
            LogActivity::create([
                    'url' => request()->fullUrl(),
                    'ip' => request()->ip(),
                    'total' => $count,
                    'devices' => $_SERVER['HTTP_USER_AGENT'],
                    'country' => isset($details->country) ? $details->country : null ,
        //            'customer_id' => auth()->user()->id,
                ]);
        }
        

        return $response;
    }

}
