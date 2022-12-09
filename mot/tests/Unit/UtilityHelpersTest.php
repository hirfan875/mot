<?php


namespace Tests\Unit;

use App\Helpers\UtilityHelpers;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Config;

class UtilityHelpersTest  extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    public function testGetCdnUrl()
    {
        $path = 'http://staging.mallofturkeya.com/assets/frontend/assets/img/placeholder-cart-prod.jpg';
        $cdnPath = Config::get('app.cdn_url');
        $cdnUrl = UtilityHelpers::getCdnUrl($path);
        $this->assertEquals($cdnPath .'/assets/frontend/assets/img/placeholder-cart-prod.jpg'  , $cdnUrl);
    }

    public function testGetCdnUrlWithDoubleSlash()
    {
        $path = 'http://staging.mallofturkeya.com//assets/frontend/assets/img/placeholder-cart-prod.jpg';
        $cdnPath = Config::get('app.cdn_url');
        $cdnUrl = UtilityHelpers::getCdnUrl($path);
        $this->assertEquals($cdnPath .'//assets/frontend/assets/img/placeholder-cart-prod.jpg'  , $cdnUrl);
    }

    public function testGetCdnUrlSecure()
    {
        $path = 'https://staging.mallofturkeya.com/assets/frontend/assets/img/placeholder-cart-prod.jpg';
        $cdnPath = Config::get('app.cdn_url');
        $cdnUrl = UtilityHelpers::getCdnUrl($path);
        $this->assertEquals($cdnPath .'/assets/frontend/assets/img/placeholder-cart-prod.jpg'  , $cdnUrl);
    }
}
