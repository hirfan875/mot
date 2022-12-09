<div class="row">
    <div class="col-md-8">
        <div class="delivery-address">
            @if($customer == null)
            <div class="form-row">
                <div class="form-group col-md-12">
                    <a href="{{route('login-register')}}" class="btn btn-login">{{__('Continue as a Guest')}}{{__(' OR ')}} <span> {{__('login')}}.</span></a>
                </div>
            </div>
            <div class="form-row" id="reset_password" style="display: none">
                <div class="form-group col-md-12">
                    <a href="{{route('customer-forgot-password')}}" class="btn btn-login">{{__('This email address already exists. Please use')}} <span>{{__('Forgot Password')}}</span> {{__('feature to gain access')}}.</a>
                </div>
            </div>
            @include('customer.guest-address')
            @endif
            @if($customer != null)
            <ul id="delivery-address">
           

                @include('customer.account-partial.address-add-edit')
                @if($customer_addresses != null)
                @if(count($customer_addresses) > 0)
                @foreach($customer_addresses as $address)
                <li id="address-li-{{$address->id}}">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="custom-control-input address-radio"
                               id="address-{{$address->id}}" name="address_id" form="order-form"
                               value="{{$address->id}}" {{$loop->index == 0 ? 'checked' : null}}>
                        <label class="custom-control-label"
                               for="address-{{$address->id}}">{{$address->name}}</label>
                    </div>
                    {{-- <span class="bg-secondary p-1 pr-2 pl- text-white rounded ml-2">{{__('Home')}}</span> --}}
                    <p class="mt-2">{{ $address->getFormatedAddress()}} </p>
                    <span class="add_edit_address_log">
                        <a href="javascript:;" onclick="update_profile({{$address->id}})"><i class="fa fa-pencil"></i></a>
                        <a href="javascript:;" onclick="deleteAddress({{ $address->id }})"><i class="fa fa-trash-o"></i></a>
                    </span>
                </li>
               
                @endforeach
                @else

                <!--<h1 class="no_address_found">{{__('Please Add your address to proceed Further')}}</h1>-->
                <div class="no_shipping_address  mt-3">
                    <div class="no_adrress_found text-center"><img alt="sd" src="{{ cdn_url('/assets/frontend/assets/img') }}/no-address_found.svg"> </div>
                  <p class="text-center pt-2">{{__('Seems like you have not saved any addresses yet!')}}</p>
                </div>
                @endif
                @endif
                <li id="new-address-li">
                    <div class="custom-control p-0 custom-radio custom-control-inline d-block text-center mt-3">
                        <button class="add_new_address" onclick="return selectAddress()" type="button" name="address_id" id="add-new-address" form="order-form"
                                value="">{{__('+ Add Address')}}</button>
                    </div>
                </li>
            </ul>
            @endif
        </div>
    </div>
    <div class="col-md-4">
        <div class="coupans-container">
            <h2>{{__('Discount Codes')}} </h2>
            <form id="coupon-form">
                @csrf
                <input type="text" name="coupon" id="coupon" placeholder="{{__('mot-shippings.apply_coupons')}}"  value="{{old('coupon')}}" {{$cart->getDiscountedAmount() > 0 ? 'null' : null}}>
                <button type="submit" class="btn btn-apply" id="btn-apply-coupon" {{$cart->getDiscountedAmount() > 0 ? 'null' : null}}><span id="spinner"></span>{{__('mot-shippings.apply')}}</button>
            </form>
            <div class="pricedetail" id="pricedetail">
                @include('web.cart.price-detail')
            </div>
        </div>
    </div>
</div>
