@extends('web.layouts.app')
@section('content')
<?php $currentCurrency = getCurrency(); ?>
<!--=================
  Start breadcrumb
  ==================-->
 <div class="breadcrumb-container">
    <h1>{{__('breadcrumb.your_cart')}}</h1>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('breadcrumb.home')}}</a></li>
      <li class="breadcrumb-item active" aria-current="page">{{__('breadcrumb.your_cart')}}</li>
    </ol>
 </div>
<!--=================
  End breadcrumb
  ==================-->
<form action="{{route('place_order')}}" method="post" id="order-form" >
  @csrf
</form>
<!--=================
  Start cart
==================-->
<div class="container">
    <div class="cart-page">
        @if($cart_products->count() > 0)
            <ul class="nav nav-pills nav-pills-container mt-minus mb-3 nav-justified" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="checkout-tab" data-toggle="pill" href="#checkout" role="tab" aria-controls="checkout" aria-selected="true">{{__('mot-cart.checkout')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled {{$cart_products->count() > 0 ? null : 'disabled'}}" id="shipping-tab" data-toggle="pill" href="#shipping" role="tab" aria-controls="shipping" aria-selected="false">{{__('mot-cart.shipping_information')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled {{$cart_products->count() > 0 ? null : 'disabled'}}" id="payment-tab" data-toggle="pill" href="#payment" role="tab" aria-controls="payment" aria-selected="false">{{__('mot-cart.payment')}}</a>
                </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="checkout" role="tabpanel" aria-labelledby="checkout-tab">
                    @include('web.cart.cart')
                    <div class="row" id="next-step-check">
                        <div class="col-md-12">
                            <div class="next-step">
                                <a href="javascript:;" onclick="gotoShipping()" class="btn btn-primary w-auto text-white">{{__('mot-cart.next_step')}} <i class="fa fa-angle-right text-white"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="shipping" role="tabpanel" aria-labelledby="shipping-tab">
                    @include('web.cart.shipping')
                    <div class="row">
                        <div class="col-md-12">
                            <div class="next-step">
                                @if($customer == null)
                                    <a href="javascript:;" onclick="gotoCart()" class="btn w-auto back_btn_1"> <i class="fa fa-angle-left text-white"></i> {{__('Back')}}</a>
                                    <button type="button" class="btn btn-primary w-auto text-white" id="calculate_delivery_fee" onclick="return get_action(this)"><span id="spinner"></span>{{__('Calculate Shipping')}}</button>
                                    <a href="javascript:;" id="ship_next_step" onclick="gotoGuestPaymentPage()" class="btn btn-primary w-auto text-white" style="display: {{ $customer == null ? 'none' : 'block' }}" >{{__('mot-cart.next_step')}} <i class="fa fa-angle-right text-white"></i></a>
                                @else
                                    <a href="javascript:;" onclick="gotoCart()" class="btn back_btn_1 w-auto"> <i class="fa fa-angle-left text-white"></i> {{__('Back')}}</a>
                                    <a href="javascript:;" id="ship_next_step1" onclick="gotoPaymentPage()" class="btn btn-primary w-auto text-white">{{__('mot-cart.next_step')}} <i class="fa fa-angle-right text-white"></i></a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="payment" role="tabpanel" aria-labelledby="payment-tab">
                    @include('web.cart.payment')
                    <div class="row">
                        <div class="col-md-12">
                            <div class="next-step next-step_payment">
                                 <a href="javascript:;" onclick="gotoShipping()" class="btn w-auto back_btn_1 back_btn_1Payment"><i class="fa fa-angle-left text-white"></i> {{__('Back')}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="empty-cart_img"><img loading="lazy" src="{{ asset('assets/frontend') }}/assets/img/empty-cart.png" alt="empt-cart" height="300"></div>
                    <p class="p-3">{{__('mot-cart.your_cart_is_empty')}}</p>
                    <div class="text-center"> <a href="{{url('/')}}" class="btn-primary mt-1 ">{{__('order-success.continue_shopping')}}</a></div>
                </div>
            </div>
        @endif
    </div>
</div>
<!--=================
  End cart
==================-->
@php
$items = array();
@endphp
@foreach($cart_products as $cart_product)
     @php
$items[] = '{item_id: "'.$cart_product->id.'", item_name: "'.$cart_product->title.'", price: '.$cart_product->unit_price.', quantity: '.$cart_product->quantity.' }'
@endphp
@endforeach

@endsection
@section('scripts')
<link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/css/intlTelInput.css" rel="stylesheet" media="screen">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/intlTelInput.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/intlTelInput.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/utils.js"></script>
    <script>

        var telInput = $("#phone"),
          errorMsg = $("#error-msg"),
          validMsg = $("#valid-msg");

        // initialise plugin
        telInput.intlTelInput({

          allowExtensions: true,
          formatOnDisplay: true,
          autoFormat: true,
          autoHideDialCode: true,
          autoPlaceholder: true,
          defaultCountry: "kw",
          preferredCountries: ['sa', 'ae', 'qa','om','bh','kw','tr'],
          preventInvalidNumbers: true,
          separateDialCode: true,
          initialCountry: 'kw',
          hiddenInput: "phone",

           utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/utils.js"
        });

        var reset = function() {
          telInput.removeClass("error");
          errorMsg.addClass("hide");
          validMsg.addClass("hide");
        };

        // on blur: validate
        telInput.blur(function() {
          reset();
          if ($.trim(telInput.val())) {

            if (telInput.intlTelInput("isValidNumber")) {
              validMsg.removeClass("hide");
              $('#phone_number').val(1);
            } else {
                $('#phone_number').val(0);
              telInput.addClass("error");
              errorMsg.removeClass("hide");

            }
          }
        });

        telInput.on("keyup change", reset);

    </script>

<script>

    $(document).ready(function () {
        /* going to shipping tab on click checkout from mini cart */
        var locationValue = (new URL(location.href)).searchParams.get('tab');
        if (locationValue == 'checkout') {

            gotoShipping();

        }
    });

    function removeCartItem(id, title, unit_price, currency_code) {
        var item_id = id;
        let CartData = {'id': item_id};
        let removalCartMessage = $('#cart-removal-message').text();
        if (confirm(removalCartMessage)) {
            $('#loading-div-' + item_id).removeClass('d-none');
            $('#loading-div').removeClass('d-none');
            $.ajax({
                type: "POST",
                dataType: "json",
                data: CartData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{!! url('remove-cart-item') !!}',
                success: function (result) {
                    refreshCartTable();//update cart table
                    updateTopCartCount(result.data.cartItemsQuantity); //update top cart count
                    // refreshPriceCalculation(); //update price div
                    $("#cart_block_web").load(location.href + " #cart_block_web");
                    setTimeout(function () {
                        ShowSuccessModal("{{trans('Item removed successfully.')}}", 2000);
                        $('#loading-div-' + item_id).addClass('d-none');
                        $("#cart_block_mobile").load(location.href + " #cart_block_mobile");
                    }, 2000);
                }, error: function (error) {
                    console.log(error);
                }
            });
        }

          
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({ ecommerce: null });  
        window.dataLayer.push({
          event: "remove_from_cart",
          ecommerce: {
            items: [
            {
              item_id: item_id,
              item_name: title,
              currency: currency_code,
              price: item_id,
//              quantity: quantity
            }
            ]
          }
        });
    }

    //update cart quantity
    function updateTopCartCount(count)
    {
        $('#loading-div-cart-inc-'.id).removeClass('d-none');
        if (count == 0){
            $("#next-step-check").hide();
        }

        $('.top-cart-count').text(count);
    }

    //refresh cart table div
    function refreshCartTable(){
        // $("#cart-table").load(location.href + " #cart-table");
        $(".cart-page").load(location.href + " .cart-page");
    }

    // update cart price
    function refreshPriceCalculation(){
       $("#price-detail1").load(location.href + " #price-detail1");
       $("#price-detail").load(location.href + " #price-detail");
    }
</script>

<script>
    // trigger when click on + button
    function incrementValue(product_id){
        let qty = getQuantity(product_id);

        if(checkQuantityAvailability(product_id, qty)){
            qty = qty+1;
            updateCart(product_id, qty,'add');
        }
    }

    // trigger when click on - button
    var clicks = 0;

    function decrementValue(product_id){
        clicks += 1;

        let qty = getQuantity(product_id);
        // qty = qty-1;
        if(0 < qty){
            updateCart(product_id, qty,'remove');
        }

    }

    //get cart quantity
    function getQuantity(product_id){
        p_id = parseInt(product_id);
        let qty = $('#quantity-'+p_id).val();

        return parseInt(qty);
    }

    function checkQuantityAvailability(product_id, qty){
        //check if the selected quantity is available
        let stock = $('#stock-'+product_id).val();
        if(stock-qty <= 0)
        {
            ShowFailureModal("{{trans('More quantity of the product is not available.')}}");
            return false;
        }
        return true;
    }

    function updateCart(product_id, qty,status){

        let CartData = {'product_id': product_id, 'quantity': qty,'status':status};
        $('#loading-div-cart-inc-'+product_id).removeClass('d-none');
        $('#loading-div-cart').removeClass('d-none');
        $.ajax({
         type: "POST",
         dataType: "json",
         data: CartData,
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         url: '{!! url('add-to-cart') !!}',
         success: function(result){
             var filteredArray = result.data.cart_data.filter(function(itm){
                 if(itm.product_id == product_id){
                      return itm;
                 }
             });

             var unit_price = result.data.unit_price;
             var quantity = filteredArray[0].quantity;
             var sub_total = result.data.sub_total;

             $('#amount_'+product_id).text(unit_price);
             $('#quantity-'+product_id).val(quantity);
             $('#sub_total_'+product_id).text(sub_total);

             if (quantity < 2){
                 $('#pre-btn_'+product_id).prop('disabled', true);
             } else {
                 $('#pre-btn_'+product_id).prop('disabled', false);
             }

             // refreshCartTable();//update cart table
            // refreshPriceCalculation(); //update price div
             updateTopCartCount(result.data.cartItemsQuantity); //update top cart count
            $('#loading-div-cart-inc-'+product_id).addClass('d-none');
            window.location.reload();
         }, error: function(error){
            console.log(error);
         }
        });
        return false;
    }

    $("body").delegate("#btn-apply-coupon", "click", function(e){
        e.preventDefault();
        let couponCode = $('#coupon').val();

        $("#spinner").html("<i class='fa fa-refresh fa-spin'></i> &nbsp;");
        if($.trim(couponCode) == ''){
            ShowFailureModal("{{trans('Please enter a code first')}}");
            hideSpinner();
            return false;
        }
        $.ajax({
            type: "POST",
            data: $('#coupon-form').serialize(),
            url: '{!! url("apply-coupon") !!}',
            success: function(result){
                hideSpinner();
                if(result.success){
                    $('.getSubTotal').html( result.data.subTotal);
                    $('.getDeliveryFee').html('<b>'+result.data.deliveryFee+'<b>');
                    $('.getDiscountedAmount').html("<td>{{trans('mot-shippings.discount')}} <input type='hidden' name='discountamount' class='discountamount' value='"+result.data.discountedAmount+"'></td><td >"+result.data.discountedAmount+"</td>");
                    $('.getTotal').html( result.data.total);
                    $('#coupon-form').trigger('reset'); //reset coupon form
                    // $('#coupon').attr('disabled', true); //disabled coupon field
                    // $('#btn-apply-coupon').attr('disabled', true); //disable submit button
                    ShowSuccessModal("{{trans('Discount applied successfully!')}}");
                }

            }, error: function(error){
                hideSpinner();
                ShowFailureModal(error.responseJSON.message);
            }
        });
    });

    function hideSpinner(){
        $("#spinner").hide();
    }

    /* This method is trigger when user close cart message alert */
    $("#cart_message_alert").bind('closed.bs.alert', function(){
        $.ajax({
            type: "GET",
            dataType: "json",
            url: '{!! url("remove-cart-message") !!}',
            error: function(error){
                ShowFailureModal(error.responseJSON.message);
            }
        });
    });
</script>
<script>
    function gotoCart() {
        if($('#checkout-tab').hasClass('disabled')) {
            $('#checkout-tab').removeClass('disabled').addClass('disabledww');
            $('#shipping-tab').removeClass('disabledww').addClass('disabled');
        }
        $('a[href="#checkout"]').click();
        return;
    }

    function gotoShipping() {
        
         
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({ ecommerce: null });  
        window.dataLayer.push({
          event: "begin_checkout",
          ecommerce: {
            items: {!! json_encode($items, JSON_HEX_TAG) !!}
          }
        });
        
        if($('#shipping-tab').hasClass('disabled')) {
            $('#shipping-tab').removeClass('disabled').addClass('disabledww');
            $('#checkout-tab').removeClass('disabledww').addClass('disabled');
        }
        var address_id = $('input[name="address_id"]:checked').val();

        if(address_id) {
            getRate(address_id);
        }

        $('a[href="#shipping"]').click();

        var email = $('#guest_email').val();
        var address = $('#guest_address').val();
        var country = $('#country').val();
        var city = $('#cities').val();
        var aera = $('#aera').val();
        var block = $('#block').val();
        var street_number = $('#street_number').val();
        var house_apartment = $('#house_apartment').val();

        if(email !='' && country !='' && city !='' && address !=''){
            getGuestRate();
        }
        return;
    }

    function gotoPaymentPage() {

        if($('#payment-tab').hasClass('disabled')) {
            $('#payment-tab').removeClass('disabled').addClass('disabledww');
             $('#shipping-tab').removeClass('disabledww').addClass('disabled');
        }
        /* check if customer selected valid address */
        let address = $("input[name='address_id']:checked").val();
        var user = "{{ Auth::guard('customer')->user() }}";
        if(user==''){
            ShowFailureModal("{{trans('Please Login or register')}}");
            return false;
        }
        if(address == "" || address === undefined) {
            ShowFailureModal("{{trans('Please select address')}}");
            return false;
        }
        $('a[href="#payment"]').click();
    }

    function gotoGuestPaymentPage() {
        if($('#payment-tab').hasClass('disabled')) {
            $('#payment-tab').removeClass('disabled').addClass('disabledww');
        }
        $('a[href="#payment"]').click();
    }
</script>

<script>

    /*$("input[name='payment_method']").click(function() {
        checkIdentityNumberForIyzico();
    });*/

    /*function checkIdentityNumberForIyzico()
    {
        $("input[name='payment_method']").click(function() {
            if ($("#payu").is(":checked")) {
                $("#identity_number_payu").show();
                $('#identity_number').attr('required');
            } else {
                $("#identity_number_payu").hide();
                $('#identity_number').removeAttr('required');
            }
        });
    }*/

    $('#order-form').on('submit', function(e){
        e.preventDefault();
        /*check for identity number*/
        /*if ( $('input[name="identity_number"]').first().val() == '' && $('input[name="payment_method"]:checked').val() == 'payu' ) {
            $( "#ident_num" ).text( "{{trans('Customer identity number is required')}}" ).show().fadeOut( 3000 );
            return false;
        }*/


        $('#payment-btn-spinner').show().html("<i class='fa fa-refresh fa-spin'></i> &nbsp;");
        $.ajax({
            type: "POST",
            dataType: "json",
            data: $(this).serialize(),
            url: $(this).attr('action'),
            success: function(success){
                /* myfatoorah response start */
                if(success.data.checkoutForm.success){


                      window.dataLayer = window.dataLayer || [];
                       window.dataLayer.push({ ecommerce: null });
                      window.dataLayer.push({
                        event: "purchase",
                        ecommerce: {
                            transaction_id: "T_12345",
                            value: {!! $cart->getTotal() !!},
                            tax: 4.90,
                            shipping: {!! $cart->getDeliveryFee() !!},
                            currency: "TRY",
                            items: {!! json_encode($items, JSON_HEX_TAG) !!},

                        }
                      });

                    window.location.href = success.data.checkoutForm.paymentUrl;
                }
                /* myfatoorah response ends */

                /* hide iyzico payment response */
                // $('head').append(success.data.checkoutForm);
                $('#payment-btn-spinner').hide();
            },
            error: function(error){
                $('#payment-btn-spinner').hide();
                if(error.responseJSON.message != ''){
                    ShowFailureModal(error.responseJSON.message);
                    return false;
                }
                ShowFailureModal("{{trans('Something went wrong')}}");
            }
        });
    });

    /*open modal on click new address radio button*/
    function selectAddress(){
//        $('#address-form-modal').appendTo("body").modal('show');
        $("#address-form-modal").modal();
    }

    /* open edit shipping address form */
    function update_profile(id) {
        $.ajax({
            type: "GET",
            url: '{!! url('address/') !!}'+ '/' +id,
            success: function (data) {
                // console.log(data);
                let addressRow = data.address;

                $('#address-heading').html("{{__('Edit Address')}}")

                let path = '{!! url('edit-address') !!}' + '/' + addressRow.id;
                $('#add-edit-address').attr('action', path);

                let methodType = 'POST';
                $('#add-edit-address').attr('method', methodType);

                $('#name').val(addressRow.name);
                // $('#email').val(addressRow.email)
                $('#phone').val(addressRow.phone);

                var telInput = $("#phone");
                  // initialise plugin
                  telInput.intlTelInput({
                    allowExtensions: true,
                    formatOnDisplay: true,
                    autoFormat: true,
                    autoHideDialCode: true,
                    autoPlaceholder: true,
                    separateDialCode: true,
                     utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/utils.js"
                  });
                $('#zipcode').val(addressRow.zipcode);
                $('#address').val(addressRow.address);
//                $('#aera').val(addressRow.aera)
                $('#block').val(addressRow.block);
                $('#street_number').val(addressRow.street_number);
                $('#house_apartment').val(addressRow.house_apartment);
                getStates(addressRow.country, addressRow.state);
                getCities(addressRow.state, addressRow.city);
                $('#country').val(addressRow.country);
                $("#address-form-modal").modal();
//                $('#zipcode').attr("required", true);
                if (addressRow.country == '117') {
                    $('#zipcode').attr("required", false);
                }
//                if (addressRow.country == '229' || addressRow.country == '178') {
//                    $('#zipcode').attr("disabled", true);
//                }
            }
        });
        // $('#country').find("option[value*='" + addressRow[0].country + "']").show().prop('selected', true).prop('disabled', false);
    }

    /* delete shipping address */
    function deleteAddress(id) {
        let confirmBox = confirm("{{trans('Are you really want to delete')}}");
        if (confirmBox) {
            $.ajax({
                type: "POST",
                dataType: "json",
                url: `{{ route('delete-address') }}`,
                data: {addressid: id, _token: '{{csrf_token()}}'},
                success: function (data) {
//                    console.log(data.success);
                    if (data.success) {
                        // $("#delivery-address").load(location.href + " #delivery-address");
                        $('#address-li-'+id).fadeOut('right');
                    }
                }
            });
        }
    }

    /*get states*/
    function getStates(id, state_id) {
        $.ajax({
            type: "GET",
            dataType: 'json',
            url: `{{ route('get-states') }}`,
            data: { country : id }
        }).done(function(data){
            var options = '';
            for(var i=0; i<data.states.length; i++) { // Loop through the data & construct the options
                options += '<option value="'+data.states[i].id+'" '+ (data.states[i].id == state_id ? 'selected' : '')+'>'+data.states[i].title+'</option>';
            }
            // Append to the html
            $('#state').html(options);
        });
    }

    /* get cities */
    function getCities(id, city_id) {

        $.ajax({
            type: "GET",
            url: `{{ route('get-cities') }}`,
            data: { state : id }
        }).done(function(data){
            var options = '';
            for(var i=0; i<data.cities.length; i++) { // Loop through the data & construct the options
                options += '<option value="'+data.cities[i].id+'" '+ (data.cities[i].id == city_id ? 'selected' : '')+'>'+data.cities[i].title+'</option>';
            }
            // Append to the html
            $('#cities').html(options);
        });
    }

</script>
<script>
    $("body").delegate("#country", "change", function (e) {

        var selectedCountry = $(this).val();
        var selectedCountrycode = $("#country :selected").text();
        $('#zipcode').attr("required", false);
        if (selectedCountrycode.trim() == 'Kuwait') {
            $('#zipcode').attr("required", false);
        }
        $('#guest_zipcode').attr("required", false);

        if (selectedCountrycode.trim() == 'Kuwait') {
            $('#guest_zipcode').attr("required", false);
        }
        $('#zipcode').prop("disabled", false);
        $('#guest_zipcode').prop("disabled", false);

        if (selectedCountrycode.trim() == 'Qatar' || selectedCountrycode.trim() == 'United Arab Emirates') {
//            $('#zipcode').prop("disabled", true);
//            $('#guest_zipcode').prop("disabled", true);
        }


        $.ajax({
            type: "GET",
            dataType: 'json',
            url: `{{ route('get-states') }}`,
            data: {country: selectedCountry}
        }).done(function (data) {
            var options = '<option value="">Select State</option>';
            for (var i = 0; i < data.states.length; i++) { // Loop through the data & construct the options
                options += '<option value="' + data.states[i].id + '">' + data.states[i].title + '</option>';
            }
            // Append to the html
            $('#state').html(options);
        });
    });

    $("body").delegate("#state", "change", function (e) {
        var state = $(this).val();
        $.ajax({
            type: "GET",
            url: `{{ route('get-cities') }}`,
            data: {state: state}
        }).done(function (data) {
            var options = '<option value="">Select City</option>';
            for (var i = 0; i < data.cities.length; i++) { // Loop through the data & construct the options
                options += '<option value="' + data.cities[i].id + '">' + data.cities[i].title + '</option>';
            }
            // Append to the html
            $('#cities').html(options);
        });
    });

    $("body").delegate("#add-edit-address", "submit", function (e) {
        e.preventDefault();
        $("#save-address-spinner").html("<i class='fa fa-refresh fa-spin'></i> &nbsp;");
        $(':input[type="submit"]').prop('disabled', true);
        let path = e.currentTarget.action;
//        var full_number = phone.getNumber(intlTelInputUtils.numberFormat.E164);
        $("input[name='phone[phone]']").val($("#phone").intlTelInput("getNumber"));

        let zipcode = $('#zipcode').val();
        let country = $('#country').val();
        let country_name = $("#country option:selected").text();

        $('#zipcode_message').hide();

        if (zipcode != '' && zipcode.length < 3 && zipcode.length > 7 && country_name.trim() == 'Turkey') {
            $('#zipcode_message').html("<strong style='color:red;'>{{trans('Kindly provide valid postal code')}}</strong> ");
            $('#save-address-spinner').hide().html("<i class='fa fa-refresh fa-spin'></i> &nbsp;");
            $(':input[type="submit"]').prop('disabled', false);
            return false;
        }


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            dataType: "json",
            async: false,
            url: path,
            data: $('#add-edit-address').serialize(),
            success: function (result) {
                if (result.success) {
                     $("#address-form-modal").modal('hide');
                    /*reset form*/
//                    $('#add-edit-address').trigger("reset");
                    /*hide modal and spinner*/
                    $("#save-address-spinner").hide();
                    $(':input[type="submit"]').prop('disabled', false);
                    /*refresh addresses list*/
                    $("#delivery-address").load(location.href + " #delivery-address");

                    ShowSuccessModal("{{trans('Address Added Successfully')}}");
                    setTimeout(function(){
//                        $("#new-address-li").hide();
                    var address_id = $('input[name="address_id"]:checked').val();
                    getRate(result.data.addresses[0].id);
                    }, 2000)
                }
            },
            error: function (error) {
                ShowFailureModal(error.responseJSON.message);
            }
        });
    });

    $("body").delegate("input:radio[name='address_id']", "change", function () {
        if ($(this).is(':checked')) {
            var address_id = $('input[name="address_id"]:checked').val();
            getRate(address_id);
        }
    });

    function getRate(address_id) {

        if (address_id) {
            var postForm = { //Fetch form data
                'address_id': address_id
            };

            $('#ship_next_step1').hide();

            var discountamount = $('.discountamount').val();
            if (discountamount == '') {
                discountamount = 0;
            }
            $(".spinner").html("<i class='fa fa-refresh fa-spin'></i> &nbsp;");
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: `{{ route('create_order') }}`,
                data: postForm
            }).done(function (data) {
                $(".spinner").hide();
                if (data.success) {
                    $('.getSubTotal').html(data.data.subTotal);
                    $('.getDeliveryFee').html('<b>'+data.data.deliveryFee+'<b>');
                    if (data.data.discount > 0) {
                        $('.getDiscountedAmount').html("<td>{{trans('mot-shippings.discount')}} <input type='hidden' name='discountamount' class='discountamount' value='" + data.data.discountedAmount + "'></td><td >" + data.data.discountedAmount + "</td>");
                    }
//                    if (data.data.discount == 0) {
//                        $('.getDiscountedAmount').html("<td>{{trans('mot-shippings.discount')}} <input type='hidden' name='discountamount' class='discountamount' value='" + data.data.discountedAmount + "'></td><td >{{trans('Free Shipping')}}</td>");
//                    }
                    $('.getTotal').html(data.data.total);
                    ShowSuccessModal("{{trans('Delivery Fee Updated Successfully')}}");
                    $('#ship_next_step1').show();
                    
                    window.dataLayer = window.dataLayer || [];
                       window.dataLayer.push({ ecommerce: null });
                      window.dataLayer.push({
                          event: "add_shipping_info",
                            ecommerce: {
                              currency: "TRY",
                              value: {!! $cart->getDeliveryFee() !!},
                              items: {!! json_encode($items, JSON_HEX_TAG) !!}
                            }
                          });

                }
            })
                .fail(function (xhr, status, error) {
                    $(".spinner").hide();
                    var err = JSON.parse(xhr.responseText);
                    ShowFailureModal(err.message);
                });

        }
    }

    $("#calculate_delivery_fee").on("click", function () {

        if($('#phone_number').val() == 1) {
            getGuestRate();
        } else {
            ShowFailureModal("{{trans('Please enter valid phone number')}}");
            return false;
        }

    });

    /* email validation*/
    function validateEmail($email) {
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        return emailReg.test( $email );
    }

    function getGuestRate() {

        var email = $('#guest_email').val();
        var name = $('#guest_name').val();
        var phone_number = $("#phone").val();
        var phone = $("#phone").intlTelInput("getNumber");

        var zipcode = $('#guest_zipcode').val();
        var address = $('#guest_address').val();
//        var aera = $('#guest_aera').val();
        var block = $('#guest_block').val();
        var street_number = $('#guest_street_number').val();
        var house_apartment = $('#guest_house_apartment').val();
        var country = $('#country').val();
        var state = $('#state').val();
        var city = $('#cities').val();
        var register_guest = 0;

        if (email.trim() == "") {
            ShowFailureModal("{{trans('Please enter email')}}");
            return false;
        }

        if (!validateEmail(email)) {
            ShowFailureModal("{{trans('Please enter valid email')}}");
            return false;
        }

        if (name.trim() == "") {
            ShowFailureModal("{{trans('Please enter name')}}");
            return false;
        }
        if (phone_number == "") {
            ShowFailureModal("{{trans('Please enter phone number')}}");
            return false;
        }
         if (country.trim() == "") {
            ShowFailureModal("{{trans('Please enter country')}}");
            return false;
        }
        if (state.trim() == "") {
            ShowFailureModal("{{trans('Please enter state')}}");
            return false;
        }
         if (city.trim() == "") {
            ShowFailureModal("{{trans('Please enter city')}}");
            return false;
        }
        if (block.trim() == "") {
            ShowFailureModal("{{trans('Please enter block')}}");
            return false;
        }
        if (street_number.trim() == "") {
            ShowFailureModal("{{trans('Please enter street_number')}}");
            return false;
        }
        if (house_apartment.trim() == "") {
            ShowFailureModal("{{trans('Please enter house_apartment')}}");
            return false;
        }
        if (address.trim() == "") {
            ShowFailureModal("{{trans('Please enter address')}}");
            return false;
        }
//
//        if ($("#register_guest").is(':checked')) {
//            var register_guest = 1;
//        }

        $('#guest_zipcode_message').hide();
        let country_name = $("#country option:selected").text();

        if (zipcode != '' && zipcode.length < 3 && zipcode.length > 7 && country_name.trim() == 'Turkey') {
            $('#guest_zipcode_message').html("<strong style='color:red;'>{{trans('Kindly provide valid postal code')}}</strong> ");
            $('#save-address-spinner').hide().html("<i class='fa fa-refresh fa-spin'></i> &nbsp;");
            $(':input[type="submit"]').prop('disabled', false);
            return false;
        }

        var discountamount = $('.discountamount').val();
        if (discountamount == '') {
            discountamount = 0;
        }

        var postForm = { //Fetch form data
            'email': email,
            'name': name,
            'phone': phone,
            'zipcode': zipcode,
            'address': address,
//            'aera': aera,
            'block': block,
            'street_number': street_number,
            'house_apartment': house_apartment,
            'country': country,
            'state': state,
            'city': city,
            'register_guest': register_guest
        };
        $(".spinner").html("<i class='fa fa-refresh fa-spin'></i> &nbsp;");
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            url: `{{ route('create_order') }}`,
            data: postForm
        }).done(function (data) {
            $(".spinner").hide();
            if (data.success) {
                $('.getSubTotal').html(data.data.subTotal);
                $('.getDeliveryFee').html('<b>'+data.data.deliveryFee+'<b>');
                if (data.data.discount > 0) {
                    $('.getDiscountedAmount').html("<td>{{trans('mot-shippings.discount')}} <input type='hidden' name='discountamount' class='discountamount' value='" + data.data.discountedAmount + "'></td><td >" + data.data.discountedAmount + "</td>");
                }
//                if (data.data.discount == 0) {
//                    $('.getDiscountedAmount').html("<td>{{trans('mot-shippings.discount')}} <input type='hidden' name='discountamount' class='discountamount' value='" + data.data.discountedAmount + "'></td><td >{{trans('Free Shipping')}}</td>");
//                }
                $('.getTotal').html(data.data.total);
                ShowSuccessModal("{{trans('Delivery Fee Updated Successfully')}}");
                if (register_guest == 1) {
                    ShowSuccessModal("{{trans('Account created successfully')}}");
                }
                $('#ship_next_step').show();
                $('#calculate_delivery_fee').hide();
                

                  
                  window.dataLayer = window.dataLayer || [];
                       window.dataLayer.push({ ecommerce: null });
                      window.dataLayer.push({
                          event: "add_shipping_info",
                            ecommerce: {
                              currency: "TRY",
                              value: {!! $cart->getDeliveryFee() !!},
                              items: {!! json_encode($items, JSON_HEX_TAG) !!}
                            }
                          });
            }
        })
            .fail(function (xhr, status, error) {
                $(".spinner").hide();
                var err = JSON.parse(xhr.responseText);
                ShowFailureModal(err.message);
            });
    }

    function checkGuestUser(email) {

        if(email == ''){
            ShowFailureModal("{{trans('Please Insert Email Address')}}");
            $('#guest_email').css({ 'border': '1px solid red' });

            return false;
        }else{
           if(!validateEmail(email)){
               ShowFailureModal("{{trans('Please enter valid email')}}");
               $('#guest_email').css({ 'border': '1px solid red' });
               return false;
           }
            $('#guest_email').css({ 'border': '1px solid #d5d5d5' });
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            url: `{{ route('check-guest-account') }}`,
            data: {'email': email}
        }).done(function (data) {
            $(".spinner").hide();
            if (data.success) {
                ShowSuccessModal("{{trans('This email address already exists. Please use Forget Password feature to gain access.')}}");
                $('#reset_password').show();
                return;
            }
            $('#reset_password').hide();
            return;
        })
            .fail(function (xhr, status, error) {
                $(".spinner").hide();
                var err = JSON.parse(xhr.responseText);
                ShowFailureModal(err.message);
            });
    }

    function validateEmail($email) {
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        return emailReg.test( $email );
    }
 </script>

@endsection
