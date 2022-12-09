<div class="fashion_products">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="mb-3 float-left">{{__($section->sortable->title)}}</h2>
                <ul class="cat_list">
                    <li><a href="{{route('tabbed' ,$section->sortable->id)}}" class="active">{{__('View All')}}</a></li>
                </ul>
            </div>
            <!-- Tabs Area Star-->
            <div class="col-md-12">
                <div class="tabs_container">
                    <div class="tab-content w-100">
                        <div class="tab-pane active" id="home" role="tabpanel">
                            <!-- Products Start here -->
                            <div class="row">
                                @foreach($section->sortable->get_products(8) as $product)
                                <div class="item">
                                    <div class="trend_pro_box">
                                        <div class="loading-div d-none" id="loading-div-{{$product->id}}">
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
                                        <div class="product_image_block"><a href="{{$product->getViewRoute()}}"><img loading="lazy" src="{{$product->product_listing()}}" alt="{{isset($product->product_translates)? $product->product_translates->title:$product->title}}"/></a></div>
                                      <div class="pp_item">
                                          <h2><a href="{{$product->getViewRoute()}}" title="{{isset($product->product_translates)? $product->product_translates->title:$product->title}}">{{\Illuminate\Support\Str::limit(isset($product->product_translates)? $product->product_translates->title:$product->title, 35) }}</a>
                                                <span class="vendors float-right"><a href="{{$product->store->getViewRoute()}}">{{$product->store->name}}</a></span>
                                            </h2>
                                            <div class="price"><span>{{currency_format($product->promo_price, $currency)}}</span><br>
                                                @if($product->promo_price < $product->price)
                                                <span class="offer_price">{{currency_format($product->price, $currency)}}</span>
                                                @endif
                                            </div>
                                            <div class="loading-div d-none" id="loading-div-cart-{{$product->id}}">
                                                <div class="spinner-border text-danger" role="status">
                                                    <span class="sr-only">{{__('Loading...')}}</span>
                                                </div>
                                            </div>
                                            <div class="actions-item float-right">
                                                @if(!$product->soldOut())
                                                @if($product->attributes->count() > 0)
                                                <a href="{{$product->getViewRoute()}}"><i class="icon-basket"></i></a>
                                                @else
                                                <a href="javascript:;" onclick="addToCart({{$product->id}}, {{$product->stock}}, `{!! $product->title!!}`, '{{$product->price}}')"><i class="icon-basket"></i></a>
                                                @endif
                                                @endif
                                                @if(Auth::guard('customer')->user() == null)
                                                <a href="{{route('my-account')}}"><i class="icon-heart"></i></a>
                                                @else
                                                @if ($product->IsWishlist())
                                                <a href="javascript:;" id="remove-{{$product->id}}"  data-id="{{$product->id}}" data-action="remove" class="wishlist">
                                                    @else
                                                    <a href="javascript:;" id="add-{{$product->id}}" data-id="{{$product->id}}" data-action="add"  class="wishlist">
                                                        @endif
                                                        <i id="wishlist-sec-{{$product->id}}" class="clas {{$product->IsWishlist() ? 'fa fa-heart' : 'icon-heart'}}"></i></a>
                                                    @endif
                                                    <li><a href="javascript:;" id="addCompareProduct" data-id="{{$product->id}}" onclick="addCompareProduct({{$product->id}})" class="compare" style="display: inline;"><i class="icon-shuffle"></i></a></li>
                                            </div>
                                        </div>
                                        <!-- <a href="#"><span class="add_cart"><i class="icon-heart"></i></span></a> -->
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <!-- Products Ends here -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- tabs Area Ends -->
        </div>
    </div>
</div>
