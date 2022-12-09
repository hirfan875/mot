<!--=================
   Trending Products
   ==================-->
   <div class="trending_products mt-4">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class=" float-left">{{__($section->sortable->title)}}</h2>
                <ul class="cat_list">
                    <li><a href="{{route('trending' ,$section->sortable->id)}}" class="active">{{__("VIEW ALL")}}</a></li>
                </ul>
            </div>
            <div class="col-md-12">
                <div id="owl-carousel" class="owl-carousel  owl-theme trending-products">
                    @foreach($section->sortable->get_products(12) as $product)

                    <div class="item">
                        <div class="trend_pro_box">
                            <div class="loading-div d-none" id="loading-div-{{$product->id}}">
                                <div class="spinner-border text-danger" role="status">
                                    <span class="sr-only">{{__('Loading...')}}</span>
                                </div>
                            </div>
                            <div class="loading-div-cart d-none" id="loading-div-cart-{{$product->id}}">
                                <div class="spinner-border text-danger" role="status">
                                    <span class="sr-only">{{__('Loading...')}}</span>
                                </div>
                            </div>
                            <div class="saleee">
                                @if($product->isNew())<span class="new_offer">{{__('New')}}</span>@endif
                                @if($product->isSale())<span class="sale_offer">{{ round((($product->price - $product->promo_price)  / $product->price) * 100)  .  __('% OFF')}}</span>@endif
                                @if($product->isTop())<span class="top_offer">{{__('Top')}}</span>@endif
                                @if($product->soldOut())<span class="sale_offer">{{__('Out Of Stock')}}</span>@endif
                            </div>
                            <div class="product_image_block"><a href="{{$product->getViewRoute()}}">
                                @if(isMobileDevice())
                                    <img src="{{$product->product_thumbnail()}}" alt="{{isset($product->product_translates)? $product->product_translates->title:$product->title}}"/>
                                @else
                                    <img src="{{$product->product_listing()}}" alt="{{isset($product->product_translates)? $product->product_translates->title:$product->title}}"/>
                                @endif
                                    
                                </a></div>
                            <h4><a href="{{$product->getViewRoute()}}" title="{{isset($product->product_translates)? $product->product_translates->title:$product->title}}">{{\Illuminate\Support\Str::limit( isset($product->product_translates)? $product->product_translates->title:$product->title , 35)}}</a></h4>
                            <h3>{{$product->store->title}}</h3>
                            <div class="mm_price">
                                <span>{{currency_format($product->promo_price, $currency)}}</span>
                                @if($product->promo_price < $product->price)
                                <span class="offer_price">{{currency_format($product->price, $currency)}}</span>
                                @endif
                            </div>
                            <div class="cart_footers d-none" id="social-share-{{$product->id}}">
                                <div id="social-links">
                                    <span class="b_closer">X</span>
                                    @php
                                        $similarSocialLinks = Share::page($product->getViewRoute(), $product->title)->facebook()->twitter()->whatsapp()->getRawLinks();
                                    @endphp
                                    <ul>
                                        @foreach($similarSocialLinks as $similarSocialKey => $similarSocialLink)
                                            @if($similarSocialKey == 'facebook')    
                                                <li>
                                                    <a href="{{$similarSocialLink}}" class="social-button " id=""><img  alt="facebook" src="{{ asset('images') }}/facebook.png"></a>
                                                </li>
                                            @endif
                                            @if($similarSocialKey == 'twitter')    
                                                <li>
                                                    <a href="{{$similarSocialLink}}" class="social-button " id=""><img  alt="twitter" src="{{ asset('images') }}/twitter.png"></a>
                                                </li>
                                            @endif
                                            @if($similarSocialKey == 'whatsapp')    
                                                <li>
                                                    <a href="{{$similarSocialLink}}" class="social-button " id=""><img alt="whatsapp" src="{{ asset('images') }}/whatsapp.png"></a>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div class="cart_footer">
                                <ul>
                                    @if(!$product->soldOut())
                                        @if($product->attributes->count() > 0)
                                        <li> <a href="{{$product->getViewRoute()}}"><i class="icon-basket"></i></a></li>
                                        @else
                                        <li><a href="javascript:;" onclick="addToCart({{$product->id}}, {{$product->stock}}, `{!! $product->title!!}`, '{{$product->price}}')"><i class="icon-basket"></i></a></li>
                                        @endif
                                    @endif
                                    <li class="wishlist-area">
                                        @php
                                        $rand = rand();
                                        @endphp
                                        @if(Auth::guard('customer')->user() == null)
                                        <a href="{{route('my-account')}}"><i class="icon-heart"></i></a>
                                        @else
                                        @if ($product->IsWishlist())
                                        <a href="javascript:;" id="remove-{{$product->id}}" data-uuid="{{$rand}}" data-id="{{$product->id}}" data-action="remove" class="wishlist">
                                            @else
                                            <a href="javascript:;" id="add-{{$product->id}}" data-uuid="{{$rand}}" data-id="{{$product->id}}" data-action="add"  class="wishlist">
                                                @endif
                                                <i id="wishlist-sec-{{$product->id}}" class="{{$product->IsWishlist() ? 'fa fa-heart' : 'icon-heart'}}"></i></a>
                                            @endif
                                    </li>
                                    <li><a href="javascript:;" id="addCompareProduct" data-id="{{$product->id}}" onclick="toggleSocialShare({{$product->id}})" class="compare" style="display: inline;"><i class="icon-share"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
