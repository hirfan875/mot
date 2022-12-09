@if (session()->has('success'))
<h1 style="display:none;">{{ session('success') }}</h1>
@endif
@php
    $logo = get_option('logo');
@endphp
@if(isMobileDevice())
<header class="pt-4 d-block d-md-none d-lg-none mobileHeader">
   <div class="container">
      <div class="row">
      <nav class="menu menu-right">
         <a href="#" class="backBtn"><i class="icon-close"></i></a>
                    <div class="w-100 d-block">
                        @auth('customer')
                        <a href="{{ route('my-account') }}" class="btn btn-secondary w-auto d-inline-block m-auto"><i class="icon-user"></i> <span>{{__('Account')}}</a>
                        @else
                        <a href="{{ route('login-register') }}" class="btn btn-secondary w-auto d-inline-block m-auto"><i class="icon-user"></i> <span>{{__('Login')}} {{__('or signup')}}</span></a>
                        @endauth
                    </div>
                    <a href="{{route('flash-deals')}}" class="active">{{__('Weekend Sale')}}</a>
                    <a href="{{route('new-arrival')}}">{{__('New Arrivals')}}</a>
                    <a href="{{route('price-under-one')}}">{{__('One')}} {{__(getCurrency()->code)}} {{__('Sale')}}</a>
                    <a href="{{ url('/made-in-turkey') }}">{{__('Made in Turkeya')}}</a>
                    @if(Auth::guard('customer')->user() != null)
                    <div class="account_links">
                        <a  href="{{route('my-account')}}">{{__('Edit Account')}}</a>
                        <a  href="{{route('change-password')}}" >{{__('Change Password')}}</a>
                        <a  href="{{route('list-address')}}" >{{__('Address Book')}}</a>

                        <a  href="{{route('wishlist')}}" >{{__('Wish List')}}</a>
                        <a  href="{{route('order-history')}}" >{{__('Order History')}}</a>
                        <a  href="{{route('logout')}}" >{{__('Logout')}}</a>
                    </div>
                    @endif
                    <div class="sell_on ">
                        <a href="{{route('seller-register')}}" class="text-secondary">{{__('Sell on MOT')}}</a>
                    </div>
                    <span class="phone">{{__('Support')}} :
                        @if ($header_contact_no != "")
                            @foreach(explode(',', $header_contact_no) as $contact_no)
                                <a href="tel:{{$contact_no}}">{{$contact_no}}</a>
                            @endforeach
                        @endif

                        {{__('Or Email us')}} : <a class="mailto" href="mailto:{{$header_email}}">{{$header_email}}</a></span>
                    </nav>
                    <div class="col-5 col-md-2">
                        <a href="{{url('/')}}" class="brand">
                            @if($logo != null)
                                <img loading="lazy" src="{{ UtilityHelper::getCdnUrl(route('resize', [190, 75, $logo])) }}" alt="{{__(config('app.name'))}}"/>
                            @else
                                <img loading="lazy" src="{{ cdn_url('/assets/frontend') }}/assets/img/logo.svg" alt="{{__(config('app.name'))}}"/>
                            @endif
                        </a>
                    </div>
                    <div class="col-7 col-md-2">
                        <div class="cart_block mt-3 text-right" id="cart_block_mobile">
                            <ul>
                                <li class="mini-search m-0"><a href="#"><i class="icon-magnifier"></i></a></li>
                                @auth('customer')
                                <li class="mini-cart"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="icon-basket"></i><span class="badge rounded-circle badge-danger top-cart-count" style="margin: -13px 0 0 0">{{$cartCount ? $cartCount : ""}}</span></a>
                                    @else
                                <li class="mini-cart"><a href="{{ route('my-account') }}"><i class="icon-basket"></i><span class="badge rounded-circle badge-danger top-cart-count" style="margin: -13px 0 0 0">{{$cartCount ? $cartCount : ""}}</span></a>
                                    @endauth
                                    <ul class="dropdown-menu dropdown-cart" role="menu">
                                        @if($topCartItems)
                                            <a class="emtyCart" id="empty-cart" href="javascript:;"
                                               data-confirm-message="{{trans('Are you sure you want to empty cart?')}}"
                                               data-success-message="{{trans('Cart has been cleared successfully')}}"
                                               onClick="emptyCart()">{{__('clear')}}</a>
                                        @endif
                                        <div class="scroller_area">
                                        @if($topCartItems)
                                        @foreach($topCartItems as $cart_product)
                                        <li class="cart-item-{{$cart_product->id}}">
                                            <span class="item">
                                                <span class="item-left">
                                                    <img class="cart_img" loading="lazy" src="{{$cart_product->product->parent_id != null ? $cart_product->product->parent->product_thumbnail() : $cart_product->product->product_thumbnail()}}" alt="{{$cart_product->title}}" />
                                                    <span class="item-info">
                                                        <span>{{\Illuminate\Support\Str::limit(isset($cart_product->product->product_translates)? $cart_product->product->product_translates->title:$cart_product->title, 50) }}</span>
                                                        <small class="text-secondary">{{currency_format($cart_product->unit_price)}}</small>
                                                        <small class="qty_cart"> {{__('QTY')}}: {{$cart_product->quantity}}</small>
                                                    </span>
                                                </span>
                                                @if (collect(request()->segments())->last() != "cart")
                                                <span class="item-right">
                                                    <a href="javascript:;" onclick="return removeTopCartItem({{$cart_product->id}});" id="remove-mini-cart-item-{{$cart_product->id}}" class="fa fa-trash"></a>
                                                </span>
                                                @endif
                                            </span>
                                        </li>
                                        @endforeach
                                        @endif
                                        @if($cartCount > 0)
                                        </div>
                                        <li class="d-flex justify-content-between align-items-center totalAmount pt-2 pb-2 pr-4 pl-4 ">
                                            <span class="ttl">{{__('Total')}}</span>
                                            <span class="pric">{{currency_format($cartSubtotal)}}</span>
                                        </li>
                                        <li class="d-flex justify-content-between align-items-cente">
                                            <a class="btn btn-danger btn-sm vcart mr-1" href="{{route('cart')}}">{{__('View Cart')}}</a>
                                            <a class="btn chckout btn-sm vcart ml-1" href="{{route('cart')}}">{{__('Checkout')}}</a>
                                        </li>
                                        @else
                                        <li>{{__('mot-cart.your_cart_is_empty')}}</li>
                                        @endif
                                    </ul>
                                </li>
                                @auth('customer')
                                <li id="wshlst-mob"><a href="{{route('wishlist')}}"><i class="icon-heart"></i><span class="badge rounded-circle badge-danger top-wishlist-count" style="margin: -13px 0 0 0">{{$wishListCount ? $wishListCount : "" }}</span></a></li>
                                @else
                                <li><a href="{{ route('login-register') }}"><i class="icon-heart"></i><span class="badge rounded-circle badge-danger top-wishlist-count" style="margin: -13px 0 0 0">{{$wishListCount ? $wishListCount : "" }}</span></a></li>
                                @endauth
                                <li><a href="#" id="showRight"><i class="icon-menu"></i></a></li>
                            </ul>
                        </div>
                    </div>
            </div>
            <form action="{{route('products')}}">
                <div class="search-fm mt-4" style="display: none;">
                    <input list="browsers"  type="search" class="form-control2" placeholder="{{__('Search here...')}}" name="keyword" id="mobile_autocomplete" value="{{$keyword ?? ''}}">
                    <datalist id="browsers">
                        @if(isset($_SESSION['key']))
                        @foreach($_SESSION['key'] as $val)
                        <option value="{{$val}}">
                            @endforeach
                            @endif
                    </datalist>
                </div>
            </form>
        <div class="mobile_categories_menu d-block d-md-none">
            <div id="accordian">
                <ul>
                    <li class="active">
                        <h3><a href="javascript:void(0);">{{__('All Categories')}}</a></h3>
                        <ul>
                            <li>
                                @include('web.partials.mobile-header-all-categories')
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
   </div>
</header>
@else
<header class="pt-4 d-none d-md-block">
    <div class="container">
        <div class="row">
            <div class="col-md-2">
                <a href="{{url('/')}}" class="brand">
                    @if($logo != null)
                        <img loading="lazy" src="{{ UtilityHelper::getCdnUrl(route('resize', [190, 75, $logo])) }}" alt="{{__(config('app.name'))}}"/>
                    @else
                        <img loading="lazy" src="{{ cdn_url('/assets/frontend') }}/assets/img/logo.svg" alt="{{__(config('app.name'))}}"/>
                    @endif
                </a>
            </div>
            <div class="col-md-7">
                <!-- Search Area -->
                <div class="search_area">
                    <form action="{{route('products')}}">
                        <select name="category_id">
                            <option>{{__('All Categories')}}</option>
                            @foreach($headerCategories as $headerCategory)
                                <option value="{{ $headerCategory->id }}" {{  isset($selected_category_id) && ($selected_category_id == $headerCategory->id) ? "selected" : ""}}>{{$headerCategory->category_translates ? $headerCategory->category_translates->title : $headerCategory->title}}</option>
                            @endforeach
                        </select>
                        <input list="browsers"  type="search" placeholder="{{__('Type here...')}}" name="keyword" id="autocomplete" autocomplete="off" value="{{$keyword ?? ''}}">
                        <datalist id="browsers">
                            @if(isset($_SESSION['key']))
                            @foreach($_SESSION['key'] as $val)
                            <option value="{{$val}}">
                                @endforeach
                                @endif
                        </datalist>
                        <button class="search_btn bg-secondary" type="submit"><i class="icon-magnifier"></i></button>
                    </form>
                </div>
                <!-- Search Area Ends-->
            </div>
            <div class="col-md-3">
                <div class="cart_block mt-3 text-right" id="cart_block_web">
                    <ul>
                        @auth('customer')
                        <li><a href="{{ route('my-account') }}"><i class="icon-user"></i> <span>{{__('Account')}}<br> <small>{{__('Profile')}}</small> </span></a>
                        </li>
                        @else
                        <li class="user_acount"><a href="{{ route('login-register') }}"><i class="icon-user"></i> <span>{{__('Login')}}<br> <small>{{__('or signup')}}</small> </span></a>
                        </li>
                        @endauth
                        <li class="mini-cart"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="icon-basket"></i><span class="badge rounded-circle badge-danger top-cart-count" style="margin: -13px 0 0 0">{{$cartCount ? $cartCount : ""}}</span> <span class="mycart">{{__('My Cart')}}</span></a>
                            <ul class="dropdown-menu dropdown-cart" role="menu">
                            @if($cartCount > 0)
                                <a class="emtyCart" id="empty-cart" href="javascript:;" data-confirm-message="{{trans('Are you sure you want to empty cart?')}}" data-success-message="{{trans('Cart has been cleared successfully')}}" onClick="emptyCart()">{{__('clear')}}</a>
                            @endif
                            <div class="scroller_area">
                            @if($topCartItems)
                                @foreach($topCartItems as $cart_product)

                                <li class="cart-item-{{$cart_product->id}}">
                                    <span class="item">
                                        <span class="item-left">
                                            <img class="cart_img" loading="lazy" src="{{$cart_product->product->parent_id != null ? $cart_product->product->parent->product_thumbnail() : $cart_product->product->product_thumbnail()}}" alt="{{$cart_product->title}}" />

                                            <span class="item-info">
                                                <span>
                                                    @if($cart_product->product->parent_id != null)
                                                    {{\Illuminate\Support\Str::limit(isset($cart_product->product->parent->product_translates)? $cart_product->product->parent->product_translates->title : $cart_product->product->parent->title, 35)}} 
                                                    @else
                                                    {{\Illuminate\Support\Str::limit(isset($cart_product->product->product_translates)? $cart_product->product->product_translates->title : $cart_product->product->title, 35)}}
                                                    @endif
                                                </span>
                                                @if($cart_product->product->parent_id != null)
                                                <span class="arbt">{{count( getAttributeWithOption($cart_product->product) ) > 0 ? getAttrbiuteString(getAttributeWithOption($cart_product->product)) : null}}</span>
                                                @endif
                                                <small class="qty_cart"> {{__('QTY')}}: {{$cart_product->quantity}}</small>
                                                <small class="text-secondary">{{currency_format($cart_product->unit_price)}}</small>
                                                <small class="qty_cart" style="font-weight: bolder; color: red">  {{ $cart_product->discounted_at != null ? ' - '. __('Get Free') : null}} </small>
                                            </span>
                                        </span>
                                        @if (collect(request()->segments())->last() != "cart")
                                        <span class="item-right">
                                            <a href="javascript:;" onclick="return removeTopCartItem({{$cart_product->id}});" id="remove-mini-cart-item-{{$cart_product->id}}" class="fa fa-trash"></a>
                                        </span>
                                        @endif
                                    </span>
                                </li>
                                @endforeach
                                @endif
                                @if($cartCount > 0)
                                </div>
                                <li class="d-flex justify-content-between align-items-center totalAmount pt-2 pb-2 pr-4 pl-4 ">
                                    <span class="ttl">{{__('Total')}}</span>
                                    <span class="pric">{{currency_format($cartTotal)}}</span>
                                </li>
                                <li class="d-flex justify-content-between align-items-center ">
                                    <a class="btn btn-danger btn-sm vcart mr-1" href="{{route('cart')}}">{{__('View Cart')}}</a>
                                    <a class="btn chckout btn-sm vcart ml-1" href="{{route('cart').'?tab=checkout'}}">{{__('Checkout')}}</a>

                                </li>
                                @else
                                <li>{{__('mot-cart.your_cart_is_empty')}}</li>
                                @endif
                            </ul>
                        </li>
                        @auth('customer')
                        <li id="wshlst"><a href="{{route('wishlist')}}"><i class="icon-heart"></i><span class="badge rounded-circle badge-danger top-wishlist-count" style="margin: -13px 0 0 0">{{$wishListCount ? $wishListCount : "" }}</span> <span class="mywhish_list">{{__('My Wishlist')}}</span> </a>
                        </li>
                        @else
                        <li class="mwhishlist"><a href="{{ route('login-register') }}"><i class="icon-heart"></i><span class="badge rounded-circle badge-danger top-wishlist-count" style="margin: -13px 0 0 0">{{$wishListCount ? $wishListCount : "" }}</span> <span class="mywhish_list">{{__('My Wishlist')}}</span></a>
                        </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </div>
        <div id="wishlist-removal-message" style="display: none">{{ trans("Are you sure you want to remove this item from your wishlist ?") }}</div>
        <div id="cart-removal-message" style="display: none">{{ trans("Are you sure you want to remove this item?") }}</div>
        <div id="out-of-stock" style="display: none">{{ trans("This item is out of stock !") }}</div>
        <div id="add-to-cart" style="display: none">{{ trans("Item has been added to your cart !") }}</div>
        <div id="remove-from-cart" style="display: none">{{ trans("Item removed successfully.") }}</div>
        <div id="expired-message" style="display: none">{{ trans("EXPIRED") }}</div>
        <div class="row mt-3">
            <div class="col-md-8 offset-md-2">
                <ul class="list_items_block quick-linksh">
                    @if(request()->is('home') || request()->is('/'))
                    <li>{{__('Go Quick To')}} <i class="fa fa-chevron-circle-right text-danger" aria-hidden="true"></i></li>
                    @else
                    <li class="inner_categories_aaa">
                        <a href="#" class="cat-opener">{{__('All Categories')}}<span> <i class="icon-menu"></i></span></a>
                        @include('web.partials.header-all-categories')
                    </li>
                    @endif
                    <!--<li class="{{request()->is('flash-deals') ? 'active' : null}}"><a href="{{route('flash-deals')}}">{{__('Weekend Sale')}}</a></li>-->
                    <li class="{{request()->is('new-arrival') ? 'active' : null}}"><a href="{{route('new-arrival')}}">{{__('New Arrivals')}}</a></li>
                    <li class="{{request()->is('price-under-one') ? 'active' : null}}"><a href="{{route('price-under-one')}}">{{__('One')}} {{__(getCurrency()->code)}} {{__('Sale')}}</a></li>
                    
                    <li class="{{request()->is('made-in-turkey') ? 'active' : null}}"><a href="{{ url('/made-in-turkey') }}">{{__('Made in Turkeya')}}</a></li>
            </ul>
         </div>
      </div>
</div>
</header>
@endif
<script>
    function myFunction() {
        var x = document.getElementById("myTopnav");
        if (x.className === "topnav") {
            x.className += " responsive";
        } else {
            x.className = "topnav";
        }
    }
</script>
