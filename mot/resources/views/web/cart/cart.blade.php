<div class="cart-main-area pr-md-4 pl-md-4">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <!-- Table Content Start -->

            <div class="table-content table-responsive mb-45" id="cart-table">
                <table>
                    <thead>
                        <tr>
                            <th class="product-thumbnail">{{__('mot-cart.image')}}</th>
                            <th class="product-name">{{__('mot-cart.product')}}</th>
                            <th class="product-price">{{__('mot-cart.price')}}</th>
                            <th class="product-quantity">{{__('mot-cart.quantity')}}</th>
                            <th class="product-subtotal">{{__('mot-cart.total')}}</th>
                            <th class="product-remove">{{__('mot-cart.remove')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                    <!-- cart messages alert -->
                    @if(count($cart_messages) > 0)
                    <div class="alert alert-danger" id="cart_message_alert">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        @foreach($cart_messages as $cart_message)
                            <p>{{$cart_message}}</p>
                        @endforeach
                    </div>
                    @endif
                    
                    @foreach($cart_products as $cart_product)
                        @include('web.cart.product' , $cart_product)
                    @endforeach
                    
                  
                  
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
