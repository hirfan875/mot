<div class="row">
    <div class="col-md-6">
        <div class="select-payment">
            <h2>{{__('mot-payment.select_a_payment_method')}}</h2>
            <div class="form-cont">
                <div class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" id="myfatoorah" value="myfatoorah" name="payment_method" form="order-form" required checked>
                    <label class="custom-control-label" for="myfatoorah"><!--{{__('mot-payment.my_fatoorah')}}-->  <img src="https://www.myfatoorah.com/assets/img/logo.png" alt="my_fatoorah" width="60"> </label>
                </div>
            </div>
            
        </div>
    </div>
    <div class="col-md-6">
        <div class="coupans-container">
            <!-- <h2>{{__('mot-shippings.payable_amount')}}</h2> -->
            <div class="pricedetail" id="pricedetail">
                @include('web.cart.price-detail')
            </div>
            <button type="submit" class="btn btn-primary delivery-here" form="order-form"><span id="payment-btn-spinner"></span>{{__('mot-payment.pay_now')}}</button>
        </div>
    </div>
</div>
