<?php

namespace App\Service;

use App\Models\ShippingRate;
use Illuminate\Support\Collection;


class ShipmentRateService
{
    /**
     * create new shippingRate
     *
     * @param array $request
     * @return ShippingRate
     */
    public function getShipmentRate($deliveryFee, $address)
    {
        $shippingRate = 19;
        if ($deliveryFee <= 0.5) {
            $deliveryFee = 0.5;
        } else {
            $deliveryFee = (int)ceil($deliveryFee);
        }

        /*$shippingRate = ShippingRate::where('weight',$deliveryFee)->where('country_id',$address->country)->first();
        if($shippingRate == null) {
            $shippingRate = ShippingRate::where('weight',$deliveryFee)->first();
        }*/
        
        $shippingRate =  (float) get_option('shipping_flat_rate');

        return $shippingRate;
    }
}
