<?php

namespace App\Http\Middleware;

use App\Models\Currency;
use Closure;
use Illuminate\Http\Request;
use App;
use Session;

class DetectGeoLocation
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
//        $ip = '103.239.147.187'; //For static India IP
//        $ip = '149.0.112.16'; //For static Turkey IP
//        $ip = '194.54.192.0'; //For static Kuwait IP
        $ip = request()->ip(); //Dynamic IP address get
//        $ip2 = request()->server('SERVER_ADDR');
        $geoLocation = $this->getCountryByIp($ip);
//        dd($geoLocation);

        if($geoLocation){

            $currency = Session::get('currency');
            if ($currency == null) {
                $currency = Currency::where('code', 'like', '%' . $geoLocation->countryCode . '%')->where('status', 1)->first();
                if ($currency == null) {
                    $currency = Currency::where('code', 'USD')->where('status', 1)->first();
                }
                setCurrency($currency);
            }

            $middleEastCountries = $this->MiddleEastCountries();
            if(strtolower($geoLocation->countryName) == 'turkey') {
//                App::setLocale('tr');
                App::setLocale('ar');
            } else if(in_array( strtoupper($geoLocation->countryName), $middleEastCountries)) {
                App::setLocale('ar');
            } else {
                App::setLocale('en');
            }
        }

        return $next($request);
    }

    private function getCountryByIp($ip)
    {
        return \Location::get($ip);
    }

    private function MiddleEastCountries()
    {
        $countries = array(
            'ALGERIA',
            'BAHRAIN',
            'EGYPT',
            'IRAN',
            'IRAQ',
            'ISRAEL',
            'JORDAN',
            'KUWAIT',
            'LEBANON',
            'LIBYA',
            'MOROCCO',
            'OMAN',
            'PALESTINE',
            'QATAR',
            'SAUDI ARABIA',
            'SYRIA',
            'TUNISIA',
            'UNITED ARAB EMIRATES',
            'YEMEN',
            'ARMENIA',
            'AZERBAIJAN',
            'CYPRUS',
            'DJIBOUTI',
            'MALTA',
            'MAURITANIA',
            'SAHRAWI ARAB DEMOCRATIC REPUBLIC',
            'SOMALIA',
            'SUDAN',
        );

        return $countries;
    }
}
