<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class MobileRedirection
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
        $agent = new Agent();

        if ($agent->isMobile()) {
            $requestUri = $request->getRequestUri();
            return redirect('https://m.mallofturkeya.com' . $requestUri);
        }

        return $next($request);
    }
}
