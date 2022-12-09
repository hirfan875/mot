<div class="price-detail price-detail" id="price-detail">
    <h2>{{__('Order Summary')}}</h2>
    <table class="table">
        <tbody>
            <tr>
                <td>{{__('mot-shippings.subtotal')}}</td>
                <td class="getSubTotal"> {{currency_format($cart->getSubTotal())}}</td>
            </tr>
            <tr>
                <td>{{__('mot-shippings.delivery_fee')}} <span class="spinner"></span></td>
                <td class="getDeliveryFee">
                   {{currency_format($cart->getDeliveryFee())}}
                </td>
            </tr>
            <tr class="getDiscountedAmount">
                @if($cart->getDiscountedAmount() > 0)
                <td>{{__('mot-shippings.discount')}}
                <input type="hidden" name="discountamount" class="discountamount" value="{{currency_format($cart->getDiscountedAmount())}}">
                </td>
                <td >                 
                    {{currency_format($cart->getDiscountedAmount())}}
                </td>
                @endif
            </tr>
        </tbody>
    </table>
    <table class="table">
        <tbody>
            <tr>
                <td>{{__('Estimated Total')}}</td>
                <td class="getTotal"> {{currency_format($cart->getTotal())}}</td>
            </tr>
        </tbody>
    </table>
</div>