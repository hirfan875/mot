<div class="products_wrapper d-none d-md-block">
    <div class="hover_state">

            <div class="loading-div d-none" id="loading-div-{{$product_row->id}}">
                <div class="spinner-border text-danger" role="status">
                    <span class="sr-only">{{__('Loading...')}}</span>
                </div>
            </div>
            <div class="loading-div d-none" id="loading-div-cart-{{$product_row->id}}">
                <div class="spinner-border text-danger" role="status">
                    <span class="sr-only">{{__('Loading...')}}</span>
                </div>
            </div>
            <div class="loading-div d-none" id="loading-div-compare-{{$product_row->id}}">
                <div class="spinner-border text-danger" role="status">
                    <span class="sr-only">{{__('Loading...')}}</span>
                </div>
            </div>

            <div class="cart">
                @if(!$product_row->soldOut())
                    @if($product_row->attributes->count() > 0)
                        <a class="cart_list" href="{{$product_row->getViewRoute()}}"><i class="icon-basket"></i> {{__('mot-products.buy')}}</a>
                    @else
                        <a class="cart_list" href="javascript:;" onclick="addToCart({{$product_row->id}}, {{$product_row->stock}},  '{{$product_row->price}}')"><i class="icon-basket"></i> {{__('mot-products.buy')}}</a>
                    @endif
                @endif
                @if(Auth::guard('customer')->user() == null)
                    <a class="cart_list"  href="{{route('my-account')}}"><i class="icon-heart"></i></a>
                @else
                    @if ($product_row->IsWishlist())
                        <a href="javascript:;" id="remove-{{$product_row->id}}" data-id="{{$product_row->id}}" data-action="remove" class="cart_list wishlist">
                    @else
                        <a href="javascript:;" id="add-{{$product_row->id}}" data-id="{{$product_row->id}}" data-action="add" class="cart_list wishlist">
                    @endif
                        <i id="wishlist-sec-{{$product_row->id}}" class="{{$product_row->IsWishlist() ? 'fa fa-heart' : 'icon-heart'}}"></i></a>
                @endif
                   
                    <a href="javascript:;" id="addCompareProduct" data-id="{{$product_row->id}}" onclick="toggleSocialShare({{$product_row->id}})" class="compare"><i class="icon-share"></i></a>
                    <h2><a href="{{$product_row->store->getViewRoute()}}">{{$product_row->store->name}}</a></h2>
            </div>
            

    </div>
    @if(!$product_row->soldOut())
    <figure class="saleee">
        @if($product_row->isTop())
        <span class="top_offer">{{__('mot-products.top')}}</span>
        @endif
        @if($product_row->isNew())
        <span class="new_offer">{{__('mot-products.new')}}</span>
        @endif
        @if($product_row->isSale())
        <span class="sale_offer">{{ round((($product_row->price - $product_row->promo_price)  / $product_row->price) * 100) .  __('% OFF')}}</span>
        @endif
    </figure>
    @endif
    <figure>
        <a href="{{$product_row->getViewRoute()}}"><img loading="lazy" src="{{$product_row->product_listing()}}" alt="{{isset($product_row->product_translates)? $product_row->product_translates->title:$product_row->title}}" /></a>
            <!-- <img loading="lazy" src="{{$product_row->resize_image_url(286, 175)}}" alt="{{isset($product_row->product_translates)? $product_row->product_translates->title:$product_row->title}}"> -->
    </figure>
    <h2><a href="{{$product_row->getViewRoute()}}" title="{{isset($product_row->product_translates)? $product_row->product_translates->title:$product_row->title}}">{{\Illuminate\Support\Str::limit(isset($product_row->product_translates)? $product_row->product_translates->title:$product_row->title,35)  }}</a></h2>
    <h3 class="price mt-2">
        <span>{{currency_format($product_row->promo_price)}}</span>
        @if($product_row->promo_price < $product_row->price)
        <span class="offer_price" style="text-decoration: line-through;"> {{currency_format($product_row->price)}}</span>
        @endif
    </h3>
    <div class="cart_footers d-none" id="social-share-{{$product_row->id}}">
                        <div id="social-links">
                            @php
                                $similarSocialLinks = Share::page($product_row->getViewRoute(), $product_row->title)->facebook()->twitter()->whatsapp()->getRawLinks();
                            @endphp
                            <ul>
                            @foreach($similarSocialLinks as $similarSocialKey => $similarSocialLink)
                                @if($similarSocialKey == 'facebook')    

                                    <li>
                                        <a href="{{$similarSocialLink}}" class="social-button " id=""><img loading="lazy" alt="facebook" src="{{ asset('images') }}/facebook.png"></a>
                                    </li>
                                @endif
                                @if($similarSocialKey == 'twitter')    
                             
                                    <li>
                                        <a href="{{$similarSocialLink}}" class="social-button " id=""><img loading="lazy" alt="twitter" src="{{ asset('images') }}/twitter.png"></a>
                                    </li>
                                @endif
                                @if($similarSocialKey == 'whatsapp')    
                                    <li>
                                        <a href="{{$similarSocialLink}}" class="social-button " id=""><img loading="lazy" alt="whatsapp" src="{{ asset('images') }}/whatsapp.png"></span></a>
                                    </li>
                                @endif
                            @endforeach
                            </ul>
                        </div>
                    </div>
</div>

<div class="products_wrapper d-block d-md-none d-lg-none">

    <!-- @if(!$product_row->soldOut())
    <figure class="saleee">
        @if($product_row->isTop())
        <span class="top_offer">{{__('mot-products.top')}}</span>
        @endif
        @if($product_row->isNew())
        <span class="new_offer">{{__('mot-products.new')}}</span>
        @endif
        @if($product_row->isSale())
        <span class="sale_offer">{{__('mot-products.sale')}}</span>
        @endif
    </figure>
    @endif -->
    <figure>
        <a href="{{$product_row->getViewRoute()}}"><img loading="lazy" src="{{$product_row->product_listing()}}" alt="{{isset($product_row->product_translates)? $product_row->product_translates->title:$product_row->title}}" /></a>
            <!-- <img loading="lazy" src="{{$product_row->resize_image_url(286, 175)}}" alt="{{isset($product_row->product_translates)? $product_row->product_translates->title:$product_row->title}}"> -->
    </figure>

    <div class="hover_state1">
        <div class="loading-div d-none" id="loading-div-{{$product_row->id}}">
            <div class="spinner-border text-danger" role="status">
                <span class="sr-only">{{__('Loading...')}}</span>
            </div>
        </div>
        <div class="loading-div d-none" id="loading-div-cart-{{$product_row->id}}">
            <div class="spinner-border text-danger" role="status">
                <span class="sr-only">{{__('Loading...')}}</span>
            </div>
        </div>
        <div class="loading-div d-none" id="loading-div-compare-{{$product_row->id}}">
            <div class="spinner-border text-danger" role="status">
                <span class="sr-only">{{__('Loading...')}}</span>
            </div>
        </div>
        <h2><a href="{{$product_row->getViewRoute()}}" title="{{isset($product_row->product_translates)? $product_row->product_translates->title:$product_row->title}}">{{\Illuminate\Support\Str::limit(isset($product_row->product_translates)? $product_row->product_translates->title:$product_row->title, 50)  }}</a></h2>
        <h3 class="price mt-2">
            <span>{{currency_format($product_row->promo_price)}}</span>
            @if($product_row->promo_price < $product_row->price)
            <span class="offer_price" style="text-decoration: line-through;"> {{currency_format($product_row->price)}}</span>
            @endif
        </h3>
        <div class="cart_footers d-none" id="social-share-mobile-{{$product_row->id}}">
            <div id="social-links">
               
                @php
                    $similarSocialLinks = Share::page($product_row->getViewRoute(), $product_row->title)->facebook()->twitter()->whatsapp()->getRawLinks();
                @endphp
                <ul>
                    @foreach($similarSocialLinks as $similarSocialKey => $similarSocialLink)
                        @if($similarSocialKey == 'facebook')    
                        
                            <li>
                            <a href="{{$similarSocialLink}}" class="social-button " id=""><img loading="lazy" alt="facebook" src="{{ asset('images') }}/facebook.png"></a>
                                
                            </li>
                        @endif
                        @if($similarSocialKey == 'twitter')    
                            <li>
                            <a href="{{$similarSocialLink}}" class="social-button " id=""><img loading="lazy" alt="twitter" src="{{ asset('images') }}/twitter.png"></a>
                            </li>
                        @endif
                        @if($similarSocialKey == 'whatsapp')    
                            <li>
                            <a href="{{$similarSocialLink}}" class="social-button " id=""><img loading="lazy" alt="whatsapp" src="{{ asset('images') }}/whatsapp.png"></a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="cart">
            @if(!$product_row->soldOut())
                @if($product_row->attributes->count() > 0)
                    <a href="{{$product_row->getViewRoute()}}"><i class="icon-basket"></i> </a>
                @else
                    <a href="javascript:;" onclick="addToCart({{$product_row->id}}, {{$product_row->stock}}, '{{$product_row->title}}', '{{$product_row->price}}')"><i class="icon-basket"></i></a>
                @endif
            @endif
            @if(Auth::guard('customer')->user() == null)
                <a href="{{route('my-account')}}"><i class="icon-heart"></i></a>
            @else
                @if ($product_row->IsWishlist())
                    <a href="javascript:;" id="remove-{{$product_row->id}}" data-id="{{$product_row->id}}" data-action="remove" class="wishlist">
                @else
                    <a href="javascript:;" id="add-{{$product_row->id}}" data-id="{{$product_row->id}}" data-action="add" class="wishlist">
                @endif
                        <i id="wishlist-sec-{{$product_row->id}}" class="{{$product_row->IsWishlist() ? 'fa fa-heart' : 'icon-heart'}}"></i>
                    </a>
            @endif
                <a href="javascript:;" id="addCompareProduct" data-id="{{$product_row->id}}" onclick="toggleSocialShare({{$product_row->id}})" class="compare"><i class="icon-share"></i></a>
        </div>
    </div>
</div>
