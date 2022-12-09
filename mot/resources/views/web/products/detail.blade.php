@extends('web.layouts.app')
@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/frontend') }}/assets/css/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/frontend') }}/assets/css/slick/slick-theme.css"/>
    <style>
        .prdct_slider_thumbnail {
            margin: 5px;
        }
    </style>
<link href="//cdn.jsdelivr.net/jquery.slick/1.6.0/slick.css" rel="stylesheet" />
@endsection
@section('content')

<!--=================
  Start breadcrumb
  ==================-->
<div class="breadcrumb-container">
   <h1>{{__('breadcrumb.product_detail')}}</h1>
   <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('breadcrumb.home')}}</a></li>
      <li class="breadcrumb-item"><a href="{{route('categories')}}">{{__('breadcrumb.categories')}}</a></li>
      @if (isset($product->categories[0]))
         <li class="breadcrumb-item"><a href="#">{{isset($product->categories[0]->category_translates) ? $product->categories[0]->category_translates->title : $product->categories[0]->title}}</a></li>
      @endif
       @if($product->flash_deal != null)
       <li class="breadcrumb-item"><a href="{{url('flash-deals')}}">{{__('Flash Deal')}}</a></li>
       @endif
      <li class="breadcrumb-item active" aria-current="page">{{isset($product->product_translates)? substr($product->product_translates->title, 0, 30) : substr($product->title, 0, 30)}}</li>
   </ol>
</div>
<!--=================
  End breadcrumb
  ==================-->
<!--=================
   Product Details
   ==================-->
<form id="attributes-choice-form">
</form>
<div class="container product_detail">
   <div class="bg-white p-4">
      <div class="row no-gutters">
         <div class="col-md-12">
            <div class="wrapper_details row">

                <div class=" col-md-5" >
                   <div class="d-block d-md-none mobleSlider">
                       @if($product->gallery->count() > 0)
                        <div class = "product-imgs">
                         <div class = "img-display">
                           <div class = "img-showcase">
                               @foreach($product->gallery as $key => $image)
                                 <img src = "{{$product->product_detail($key)}}" alt = "shoe image">
                               @endforeach
                           </div>
                         </div>
                         <div class = "img-select">
                             @foreach($product->gallery as $key => $image)
                             <div class = "img-item">
                                 <a href = "#" data-id = "{{$key + 1}}">
                                   <img src = "{{$product->product_thumbnail($key)}}" alt = "shoe image" class="thumb">
                                 </a>
                               </div>
                             @endforeach
                         </div>
                       </div>
                        <!-- partial -->
                        <script type="text/javascript">
                          const imgs = document.querySelectorAll('.img-select a');
                          const imgBtns = [...imgs];
                          let imgId = 1;
                          imgBtns.forEach((imgItem) => {
                              imgItem.addEventListener('click', (event) => {
                                  event.preventDefault();
                                  imgId = imgItem.dataset.id;
                                  slideImage();
                              });
                          });
                          function slideImage(){
                              const displayWidth = document.querySelector('.img-showcase img:first-child').clientWidth;

                              document.querySelector('.img-showcase').style.transform = `translateX(${- (imgId - 1) * displayWidth}px)`;
                          }
                        window.addEventListener('resize', slideImage);
                        </script>
                        @else
                        <div class="zoomed_image">
                            <img class="listing-img" style="max-height: 390px; height: 390px; max-width: 500px;"  loading="lazy" src="{{ isset($product->image) ? $product->product_listing() : 'https://dummyimage.com/515x320/'}}" alt="slider"/>
                        </div>
                       @endif
                   </div>
                    <div class="preview_gallery d-none d-md-block">
                        @if($product->gallery->count() > 0)
                            @foreach($product->gallery as $key => $image)
                                @if($key == 0)
                                <div class="zoomed_image">
                                    <img id="zoom_03" alt="zoom_03" loading="lazy" src="{{$product->product_detail($key)}}" data-zoom-image="{{$product->product_original($key)}}"/>
                                </div>
                                @endif
                            @endforeach
                            <div id="gallery_01">
                                 @foreach($product->gallery as $key => $image)
                                    <a href="#" class="normal_image" data-image="{{$product->product_detail($key)}}" data-zoom-image="{{$product->product_original($key)}}">
                                        <img id="img_0{{$key}}" alt="thumb" class="thumb" loading="lazy" src="{{$product->product_thumbnail($key)}}"/>
                                    </a>
                                 @endforeach
                            </div>
                        @else
                        <div class="zoomed_image">
                            <img  class="listing-img" style="max-height: 390px; height: 390px; max-width: 500px;" loading="lazy" src="{{ isset($product->image) ? $product->product_listing() : 'https://dummyimage.com/515x320/'}}" alt="slider"/>
                        </div>
                        @endif
                    </div>
                </div>
               <div class="details col-md-4">
                  @if($product->daily_deal != null)
                    @if($product->daily_deal->status == true)
                       <div class="detail-pagetime" id="detail_demos{{$product->daily_deal->id}}">
                           <label>{{__('Daily Deal')}} :</label>
                           <span class="timer"> <span id="demos{{$product->daily_deal->id}}" data-countdown="{{$product->daily_deal->formatedEndingDate()}}"></span></span>
                       </div>
                    @endif
                   @endif
                      @if($product->flash_deal != null)
                        @if($product->flash_deal->status == true)
{{--                          <div class="detail-pagetime" id="detail_demos{{$product->flash_deal->id}}">--}}
                          <div class="detail-pagetime" id="detail_demos">
                              <label>{{__('Flash Deal')}} :</label>
                              <span class="timer"> <span id="demos{{$product->flash_deal->id}}" data-countdown="{{$product->flash_deal->formatedEndingDate()}}"></span></span>
                          </div>
                        @endif
                      @endif
                  <h3 class="product-title ">{{isset($product->product_translates)? $product->product_translates->title : $product->title}}</h3>
                  @if ($product->soldOut())
                  <h5 class="mb-3 text-danger">{{__('Out of Stock')}}</h5>
                  @endif
                  <div class="rating mt-2">
                     <div class="stars">
                        <span class="fa fa-star {{$product->rating >= 1 ? 'checked' : null}}"></span>
                        <span class="fa fa-star {{$product->rating >= 2 ? 'checked' : null}}"></span>
                        <span class="fa fa-star {{$product->rating >= 3 ? 'checked' : null}}"></span>
                        <span class="fa fa-star {{$product->rating >= 4 ? 'checked' : null}}"></span>
                        <span class="fa fa-star {{$product->rating >= 5 ? 'checked' : null}}"></span>
                     </div>
                     <span class="review-no">{{$review->count()}} {{__('mot-products.reviews')}}</span>
                  </div>
                  <hr>
                  <div class="vprofile ">
                  {{__('Seller')}}: <a href="{{route('shop', $store->slug)}}">{{isset($store->store_profile_translates)? $store->store_profile_translates->name : $store->name}}</a>
                  <p class="sku_aa">{{__('SKU')}}: {{ $product->sku}}</p>
                  @if(isset($product->brand))
                   {{__('Brand:')}} {{isset($product->brand) ? $product->brand->title : ''}}
                   @endif
                     <!-- <a href="{{route('shop', $store->slug)}}" class="view_profile">{{__('mot-products.view_profle')}}</a> -->
                  </div>
                  <hr>
                  <p class="product-description2">
                      @if($product->short_description)
                        @if(isset($product->product_translates))
                            {!! isset($product->product_translates->short_description) ? strip_tags($product->product_translates->short_description) : Str::limit(strip_tags($product->product_translates), 50) !!}
                         @endif
                      @else
                          {!! isset($product->product_translates) ? isset($product->product_translates->short_description) ? strip_tags($product->product_translates->short_description) : Str::limit(strip_tags($product->product_translates->data), 50) : Str::limit(strip_tags($product->data), 50) !!}
                      @endif
                  </p>
                  <hr>
                  <h4 class="price">
                     <span id="product_price">{{currency_format($product->promo_price,$currency ?? null)}}</span>&nbsp;
                     <span id="discounted_price_span">
                         <span class="offer_price" style="text-decoration: line-through;">
                        @if($product->promo_price < $product->price)
                           {{currency_format($product->price,$currency ?? null)}}
                        @endif
                        </span>
                    </span>
                  </h4>
                  @if($product->type == 'variable' && count($product->attributes) > 0)
                  <div class="variations">
                     @foreach($product->attributes as $attr_key => $attribute)
                     @if($attribute['type'] == 'select')
                        <div class="form-group d-flex quantity_selector align-items-center ">
                           <h5 class="colors m-0 mr-4">{{$attribute['name']}}:</h5>
                           <select class="form-control custom-select" onchange="changePrice()" name="{{$attribute['slug']}}" data-id="attribute-{{$attribute['slug']}}" form="attributes-choice-form">
                              <option value="">{{__('Select')}} {{$attribute['name']}}</option>
                              @foreach($attribute['options'] as $option)
                              <option value="{{$option['slug']}}" data-id="{{$option['id']}}">{{$option['title']}}</option>
                              @endforeach
                           </select>
                        </div>
                     @else
                        <h5 class="{{$attribute['type'] == 'swatches' ? 'sizes' : 'colors'}}">{{$attribute['name']}}
                            :
                            @foreach($attribute['options'] as $option)
                                <input class="options {{$attribute['type'] == 'swatches' ? 'size' : 'color'}}"
                                       type="radio" id="{{$option['id']}}" value="{{$option['slug']}}"
                                       data-id="attribute-{{$attribute['slug']}}" form="attributes-choice-form"
                                       name="{{$attribute['slug']}}">
                                    <label for="{{$option['id']}}">
                                        <span title="{{$option['title']}}" class="{{$attribute['type'] == 'swatches' ? 'size' : 'color'}}" @if($attribute['type']=='colors' ) style='background:{{$option['code']}}' @endif>
                                            @if($attribute['type'] != 'colors') {{$option['title']}} @endif
                                        </span>
                                    </label>
                            @endforeach
                        </h5>
                     @endif
                     @endforeach
                  </div>
                  @endif
                  @if($product->stock > 0)
                  <div class="range_nunber d-flex justify-content-start">
                     <input type="number" id="quantity" value="1" min="1" oninput="validity.valid||(value='');" max="9999" step="1" />
                     <a href="javascript:;" class="addtocart_d" id="addtocart" type="button"><i class="icon-handbag"></i> {{__('mot-products.add_to_cart')}}</a>
                     <div class="loading-div d-none" id="loading-div-cart-{{$product->id}}">
                        <div class="spinner-border text-danger" role="status">
                           <span class="sr-only">{{__('Loading...')}}</span>
                        </div>
                     </div>

                  </div>
                  @endif
                  <div class="action">
<!--                     <div class="loading-div-compare-{{$product->id}} d-none" id="loading-div-compare-{{$product->id}}">
                        <div class="spinner-border text-danger" role="status">
                           <span class="sr-only">{{__('Loading...')}}</span>
                        </div>
                     </div>-->
                     <a href="javascript:;" id="addCompareProductDetail" data-id="{{$product->id}}" class="add-to-cart "><span class="icon-share"></span> {{__('share')}}</a>
                     @if(Auth::guard('customer')->user() == null)
                     <a href="{{route('my-account')}}"><i class="icon-heart"></i>{{__('mot-products.add_to_wishlist')}}</a>
                     @else
                     @if ($product->IsWishlist())
                     <a href="javascript:;" id="remove-{{$product->id}}" data-id="{{$product->id}}" data-action="remove" class="like wishlist">
                        @else
                        <a href="javascript:;" id="add-{{$product->id}}" data-id="{{$product->id}}" data-action="add" class="like wishlist">
                           @endif
                           <i id="wishlist-sec-{{$product->id}}" class="{{$product->IsWishlist() ? 'fa fa-heart' : 'icon-heart'}}"></i>{{__('mot-products.add_to_wishlist')}}</a>
                        <div class="loading-div d-none" id="loading-div-{{$product->id}}">
                           <div class="spinner-border text-danger" role="status">
                              <span class="sr-only">{{__('Loading...')}}</span>
                           </div>
                        </div>
                        @endif
                    <div id="social-links" class="d-none">
                        <ul>
                            @foreach($socialShare as $socialKey => $socialLink)
                              @if($socialKey == 'facebook')    
                                       <li>
                                          <a href="{{$socialLink}}" class="social-button " id=""><img loading="lazy" alt="facebook" src="{{ asset('images') }}/facebook.png"></a>
                                       </li>
                                 @endif
                                 @if($socialKey == 'twitter')    
                                       <li>
                                          <a href="{{$socialLink}}" class="social-button " id=""><img loading="lazy" alt="twitter" src="{{ asset('images') }}/twitter.png"></a>
                                       </li>
                                 @endif
                                 @if($socialKey == 'whatsapp')    
                                       <li>
                                          <a href="{{$socialLink}}" class="social-button " id=""><img loading="lazy" alt="whatsapp" src="{{ asset('images') }}/whatsapp.png"></span></a>
                                       </li>
                              @endif
                            @endforeach
                        </ul>
                    </div>
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="member_area mt-4 mt-md-0 mt-lg-0">
                     <div class="mm_photo text-center">
                        <span>
                            <a href="{{route('shop', $store->slug)}}"><img loading="lazy" src="{{$store->resize_logo_url(120, 120)}}" alt="shop"/></a>
                        </span>
                         <h6 class="font-weight-bold text-uppercase"><a href="{{route('shop', $store->slug)}}">{{isset($store->store_profile_translates)? $store->store_profile_translates->name : $store->name}}</a></h6>
                        <hr>
                     </div>
                     <div class="d-flex justify-content-between">
                        <span>{{__('mot-products.member_since')}}:</span>
                        <span class="primary-color">{{date('d / m / Y', strtotime($store->created_at))}}</span>
                     </div>
                     <!--<button class="btn btn-dark-border mt-4" data-toggle="modal" data-target="#askquestion">{{__('mot-products.ask_question')}}</button>-->
                     <h5 class=" mt-4 d-none d-md-block d-lg-block">{{__('mot-products.other_products')}}:</h5>
                      <div class="mm_products d-none d-md-block d-lg-block">
                          @foreach($other_products as $other_product)
                          <div class="row">
                                  <div class="col-md-4" style="float: left;">
                                      <figure>
                                          <a href="{{$other_product->getViewRoute()}}"><img
                                                  loading="lazy" src="{{$other_product->product_thumbnail()}}"
                                                  alt="{{isset($other_product->product_translates)? $other_product->product_translates->title:$other_product->title}}"></a>
                                      </figure>
                                  </div>
                                  <div class="col-md-8" style="float: left;">
                                      <h4 class="mm_title"><a
                                              href="{{$other_product->getViewRoute()}}">{{isset($other_product->product_translates)? $other_product->product_translates->title:$other_product->title}}</a>
                                      </h4>
                                      <h6 class="mm_price mt-2">
                                          <span
                                              id="product_price">{{currency_format($other_product->promo_price)}}</span>&nbsp;
                                          <span id="discounted_price_span">
                                          @if($other_product->promo_price < $other_product->price)<br>
                                              <span class="offer_price" style="text-decoration: line-through;">{{currency_format($other_product->price)}}</span>
                                              @endif
                                      </span>
                                      </h6>
                                  </div>
                          </div>
                          @endforeach
                      </div>
                     <a href="{{route('shop', $store->slug)}}" class="btn btn-block btn-primary mt-3 mb-3">{{__('mot-products.view_list')}}</a>
                  </div>
               </div>
               @include('web.partials.ask-seller-modal')
            </div>
         </div>
      </div>
   </div>
   <!--=================
      Product Details  Ends
      ==================-->
   <!--=================
      Reviews Start
      ==================-->
   <div class="container">
      <div class="row mt-4 bg-white pt-3 pb-3">
         <div class="col-md-12">
            <div class="tab-style3">
               <ul class="nav nav-tabs" role="tablist">
                  <li class="nav-item">
                     <a class="nav-link active" id="Description-tab" data-toggle="tab" href="#Description" role="tab" aria-controls="Description" aria-selected="true">{{__('mot-products.description')}}</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" id="Additional-info-tab" data-toggle="tab" href="#Additional-info" role="tab" aria-controls="Additional-info" aria-selected="false">{{__('mot-products.additional_info')}}</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" id="Reviews-tab" data-toggle="tab" href="#Reviews" role="tab" aria-controls="Reviews" aria-selected="false">{{__('mot-products.reviews')}} ({{$reviews->count()}})</a>
                  </li>
               </ul>
               <div class="tab-content shop_info_tab w-100">
                  <div class="tab-pane fade active show" id="Description" role="tabpanel" aria-labelledby="Description-tab">
                     <div class="dec_border">
                     <p>{!! isset($product->product_translates)? $product->product_translates->data:$product->data !!}</p>
                     </div>
                  </div>
                  <div class="tab-pane fade" id="Additional-info" role="tabpanel" aria-labelledby="Additional-info-tab">
                     <table class="table table-bordered">
                        <tbody>
                           @if($product->additional_information)
                               {!! $product->additional_information !!}
                           @else
                              <!-- <tr>
                                  <td colspan="2">
                                     {{__('mot-products.not_available')}}
                                     <ul class="product_list">
                                        <li><i class="fa fa-chevron-right"></i> <strong>Color</strong> Black</li>
                                        <li><i class="fa fa-chevron-right"></i>  <strong>Size</strong> Medium</li>
                                        <li><i class="fa fa-chevron-right"></i> <strong>Color</strong> Red</li>
                                        <li><i class="fa fa-chevron-right"></i> <strong>Color</strong> Green</li>
                                        <li><i class="fa fa-chevron-right"></i> <strong>Color</strong> Black</li>
                                     </ul>
                                  </td>
                               </tr>-->
                           @endif
                        </tbody>
                     </table>
                  </div>
                  <div class="tab-pane fade" id="Reviews" role="tabpanel" aria-labelledby="Reviews-tab">
                     <div class="product_review_sec">
                     <div class="d-flex justify-content-between align-items-center">
                        <span class="products-r">Products Reviews</span>
                        <div class="sort_filter">
                           <i class="fa fa-sort"></i> <label for="">Sort:</label>
                           <select name="" id="" class="sort"><option>A-Z</option><option>High to Low</option></select>
                           <i class="fa fa-filter"></i> <label for="">Filter:</label>
                           <select name="" id="" class="sort"><option>All Star</option><option>Star</option></select>
                        </div>
                     </div>
                     </div>
                     <div class="comments dec_border">

                         @if($review->count() > 0)
                         <h5 class="product_tab_title">{{$review->count()}} {{__('mot-products.review_for')}} <span>{{$product->product_translates ? $product->product_translates->title : $product->title}}  </span></h5>
                         @if($reviews->count()>0)
                         <span>{{__('More')}} {{$reviews->count() - $review->count()}} {{__('review in other languages')}}</span>
                         @endif
                         <ul class="list_none comment_list mt-4">
                           @foreach($review as $review)
                           @include('web.partials.review' , $review)
                           @endforeach
                        </ul>
                         @else
                               <span>
                                     {{__('mot-products.not_available')}}
                               </span>
                         @endif
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!--=================
   Reviews Ends
   ==================-->

<!--=================
   Similar Products
   ==================-->
<div class="similar_products">
    <div class="container">
        <div class="row bg-white pb-4">
            <div class="col-md-12">
                <h2 class="text-center mb-lg-4 mt-lg-4 mt-4 mt-md-4 mt-lg-4">
                    <span class="tag_sm"><i class="icon-tag"></i></span>
                    {{__('mot-products.similar_products')}}
                </h2>
                <div class="row products_container border-top border-right">
                    <div class="col-md-12">
                        <div id="owl-carousel" class="owl-carousel  owl-theme trending-products">
                            @foreach($similar_products as $similarProduct)
                                <div class="item">
                                    <div class="trend_pro_box">
                                        <div class="loading-div d-none" id="loading-div-{{$similarProduct->id}}">
                                            <div class="spinner-border text-danger" role="status">
                                                <span class="sr-only">{{__('Loading...')}}</span>
                                            </div>
                                        </div>
                                        <div class="loading-div-cart d-none" id="loading-div-cart-{{$similarProduct->id}}">
                                            <div class="spinner-border text-danger" role="status">
                                                <span class="sr-only">{{__('Loading...')}}</span>
                                            </div>
                                        </div>
                                        <div class="saleee">
                                            @if($similarProduct->isNew())<span class="new_offer">{{__('New')}}</span>@endif
                                            @if($similarProduct->isSale())<span class="sale_offer">
                                                
                                                {{ round((($similarProduct->price - $similarProduct->promo_price)  / $similarProduct->price) * 100) .  __('% OFF')}}
                                            </span>@endif
                                            @if($similarProduct->isTop())<span class="top_offer">{{__('Top')}}</span>@endif
                                        </div>
                                        <div class="product_image_block"><a href="{{$similarProduct->getViewRoute()}}"><img
                                                    loading="lazy" src="{{$similarProduct->product_listing()}}"
                                                    alt="{{isset($similarProduct->product_translates)? $similarProduct->product_translates->title:$similarProduct->title}}"></a>
                                        </div>
                                        <h4>
                                            <a href="{{$similarProduct->getViewRoute()}}">{{\Illuminate\Support\Str::limit(isset($similarProduct->product_translates)? $similarProduct->product_translates->title:$similarProduct->title, 35)}}</a>
                                        </h4>
                                        <h3>{{$similarProduct->store->title}}</h3>
                                        <div class="">
                                            <span>{{currency_format($similarProduct->promo_price, $currency)}}</span>
                                            @if($similarProduct->promo_price < $similarProduct->price)
                                                <span
                                                    class="offer_price">{{currency_format($similarProduct->price, $currency)}}</span>
                                            @endif
                                        </div>
                                        <div class="cart_footers d-none" id="social-share-{{$similarProduct->id}}">
                                            <div id="social-links">
                                                @php
                                                    $similarSocialLinks = Share::page($similarProduct->getViewRoute(), $similarProduct->title)->facebook()->twitter()->whatsapp()->getRawLinks();
                                                @endphp
                                                <ul>
                                                    @foreach($similarSocialLinks as $similarSocialKey => $similarSocialLink)
                                                        @if($similarSocialKey == 'facebook')    
                                                         <img loading="lazy" alt="norf" src="{{ asset('images') }}/norf.png">
                                                               <li>
                                                                  <a href="{{$similarSocialKey}}" class="social-button " id=""><span class="fa fa-{{$similarSocialKey}}"></span></a>
                                                               </li>
                                                         @endif
                                                         @if($similarSocialKey == 'twitter')    
                                                         <img loading="lazy" alt="norf" src="{{ asset('images') }}/norf.png">
                                                               <li>
                                                                  <a href="{{$similarSocialKey}}" class="social-button " id=""><span class="fa fa-{{$similarSocialKey}}"></span></a>
                                                               </li>
                                                         @endif
                                                         @if($similarSocialKey == 'whatsapp')    
                                                               <li>
                                                                  <a href="{{$similarSocialKey}}" class="social-button " id=""><span class="fa fa-{{$similarSocialKey}}"></span></a>
                                                               </li>
                                                      @endif
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="cart_footer">

                                            <ul>
                                                @if($similarProduct->attributes->count() > 0)
                                                    <li><a href="{{$similarProduct->getViewRoute()}}"><i
                                                                class="icon-basket"></i></a></li>
                                                @else
                                                    <li><a href="javascript:;"
                                                           onclick="addToCart({{$similarProduct->id}}, {{$similarProduct->stock}}, '{{$similarProduct->title}}', '{{$similarProduct->price}}')"><i
                                                                class="icon-basket"></i></a></li>
                                                @endif

                                                <li class="wishlist-area">
                                                    @php
                                                        $rand = rand();
                                                    @endphp
                                                    @if(Auth::guard('customer')->user() == null)
                                                        <a href="{{route('my-account')}}"><i class="icon-heart"></i></a>
                                                    @else
                                                        @if ($similarProduct->IsWishlist())
                                                            <a href="javascript:;" id="remove-{{$similarProduct->id}}"
                                                               data-uuid="{{$rand}}" data-id="{{$similarProduct->id}}"
                                                               data-action="remove" class="wishlist">
                                                                @else
                                                                    <a href="javascript:;" id="add-{{$similarProduct->id}}"
                                                                       data-uuid="{{$rand}}" data-id="{{$similarProduct->id}}"
                                                                       data-action="add" class="wishlist">
                                                                        @endif
                                                                        <i id="wishlist-sec-{{$similarProduct->id}}"
                                                                           class="{{$similarProduct->IsWishlist() ? 'fa fa-heart' : 'icon-heart'}}"></i></a>
                                                        @endif
                                                </li>
                                                <li><a href="javascript:;" id="addCompareProduct" data-id="{{$similarProduct->id}}" onclick="toggleSocialShare({{$similarProduct->id}})" class="compare" style="display: inline;"><i class="icon-share"></i></a></li>
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
    </div>
</div>
<!--=================
   Similar Products Ends
   ==================-->

@endsection
@section('scripts')
<script>
   var attributes = <?php echo json_encode($product->attributes); ?>;
</script>
<script type="text/javascript" loading="lazy" src="{{ asset('assets/frontend') }}/assets/js/slick.min.js"></script>
<script loading="lazy" src="{{ asset('assets/frontend') }}/assets/js/jquery.ez-plus.js"></script>
   <link rel="stylesheet" href="{{ asset('assets/frontend') }}/assets/css/ez-plus.css"/>
<script>
   function addCompareProduct(id) {
      let product_id = id;
      $('#loading-div-compare-' + id).removeClass('d-none');
      $('#loading-div-compare').removeClass('d-none');
      $.ajax({
         type: "GET",
         dataType: "json",
         url: "{!! url('add-compare-product') !!}" + '/' + product_id,
         success: function(data) {
            if (data['success'] == true) {
               $('#loading-div-compare-' + id).addClass('d-none');
               ShowSuccessModal("{{trans('Product has been added to compare list')}}", 4000);
               $(".compare-btn").css("display", "block");
            }
         }
      });
   }

   $('#addtocart').on('click', function(e) {
      var currentProduct = <?php echo json_encode($product); ?>;
      let quantity = parseInt($('#quantity').val());
      let product = [];

      if (quantity == '' || quantity == 0) {
         ShowFailureModal("{{trans('Please select quantity')}}");
         return false;
      }

      if (currentProduct.type == 'variable') {
         /* validate attributes options */
         $.each(attributes, function(key, value) {
            var input = $(`[data-id=attribute-${value.name.toLowerCase()}]`);
            if (!input.is('select') && !input.is(':checked')) {
               ShowFailureModal(`{{trans('Please select')}} ${value.name}`);
               return false;
            } else {
                if(!input.is('select') && !input.is(':checked')){
                    ShowFailureModal(`{{trans('Please select')}} ${value.name}`);
                    return false;
                }
            }
         });

         //get product according to selected options
         product = getVariant();
         //check if any selection remaining
         if (!product) {
            return false;
         }

      } else {
         product = currentProduct;
      }
      
      

      //check if the selected quantity is available
      if (product.stock - quantity == 0) {
         ShowFailureModal("{{trans('No more stock available')}}" );
         return false;
      }
      
      if (product.stock - quantity < 0) {
         ShowFailureModal("{{trans('Quantity is available only ')}}"+ product.stock );
         return false;
      }

      let CartData = {
         'product_id': product.id,
         'quantity': quantity
      };
      $('#loading-div-cart-' + product.id).removeClass('d-none');
      $('#loading-div-cart').removeClass('d-none');
      $.ajax({
         type: "POST",
         dataType: "json",
         data: CartData,
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         url: '{!! url('add-to-cart') !!}',
         success: function(result) {
            $('#loading-div-cart-' + product.id).addClass('d-none');
            // console.log(result);
            if (result['success'] == true) {
               ShowSuccessModal("{{trans('mot-products.item_has_been_added_to_your_cart')}}");
               updateTopCart(); //update top cart
            } else {
               ShowFailureModal(result.message);
            }
         },
         error: function(error) {
//             console.log(error);
//            ShowFailureModal("{{trans('Something went wrong')}}");

            $('#loading-div-cart-' + product.id).addClass('d-none');
            if(error.responseJSON.message != ''){
                ShowFailureModal(error.responseJSON.message);
            } else {
               ShowFailureModal(error);
            }
         }
      });
   });
</script>

<script>
   $('input.size').on('click', function(e) {
      $(this).addClass('active');

      changePrice();
   });
   $('input.color').on('click', function(e) {
      $(this).addClass('not-available');

      changePrice();
   });
</script>

<script>
   //get selected options
   function getSelectedAttributes() {
      /*let selectedOptions = $(".variations option:selected, input:checked").map(function() {
         return $(this).val();
      }).get();*/

       let selectedOptions = $(".variations").find("option:selected, input:checked").map(function() {
           return $(this).val();
       }).get();

      return selectedOptions;
   }

   //get all variants related to this product
   function getAllVariants() {
      let all_variants = <?php echo json_encode($product->variants); ?>;

      return all_variants;
   }

   //get combinational product
   function getVariant() {
      let variants = getAllVariants();
      let selectedOptions = getSelectedAttributes();
      let variantRow = jQuery.grep(variants, function(variant) {

         sortedVariants = variant.options.sort();
         sortedselectedOptions = selectedOptions.sort();

         // console.log(sortedVariants, sortedselectedOptions);
         if (JSON.stringify(sortedVariants) == JSON.stringify(sortedselectedOptions)) {
            return variant;
         }

      })[0];
      console.log('All variants: ', variants);
      console.log('Selected options: ', selectedOptions);
      console.log('Variant row: ', variantRow);
       if (variantRow != null && variantRow.image != null) {
           let baseUrl = "{!! url('/storage/original/') !!}";
           $('#zoom_03').attr('src', `${baseUrl}/${variantRow.image}`).attr('data-zoom-image', `${baseUrl}/${variantRow.image}`);
           $('.zoomWindow').css('background-image', `url(${baseUrl}/${variantRow.image})`);
       }
      return variantRow;
   }

   function changePrice() {
       let variantRow = getVariant();
       if (variantRow) {

           let price_with_currency = variantRow.price;
           let promo_price_with_currency = variantRow.promo_price;

           /*let price = parseFloat(price_with_currency.slice(3));
           let promo_price = parseFloat(promo_price_with_currency.slice(3));*/

           let price = price_with_currency.slice(3);
           let promo_price = promo_price_with_currency.slice(3);

           price = parseFloat(price.replace(/,/g, ''), 10);
           promo_price = parseFloat(promo_price.replace(/,/g, ''), 10);
           console.log(price, promo_price);

           $('#product_price').text(promo_price_with_currency);
           // $('#product_price').text(variantRow.promo_price);
           // $('#offer_price').text(variantRow.promo_price);

           if (promo_price < price) {
               showDiscountedPrice();
               $('.offer_price').text(price_with_currency);
           } else {
               hideDiscountedPrice();
           }

           if (variantRow.stock == 0) {
               hideAddToCartButton();
           } else {
               showAddToCartButton();
           }
       }
   }

   function showDiscountedPrice() {
      $('.offer_price').removeClass('d-none');
   }

   function hideDiscountedPrice() {
      $('.offer_price').addClass('d-none');
   }

   /* show add to cart button */
   function showAddToCartButton() {
      $('#add-to-cart').removeClass('d-none');
   }

   /* hide add to cart button */
   function hideAddToCartButton() {
      $('#add-to-cart').addClass('d-none');
   }
</script>
<script>
    /* other products scripts */
    $('.mm_products').slick({
        dots: true,
        infinite: true,
        autoplay: true,
        autoplaySpeed: 2000,
        // centerMode: true,
        // variableWidth: true
    });
    /* main slider scripts */
    $(".prdct_img_slider").slick({
        dots: false,
        arrows: true,
        fade: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        asNavFor: '.prdct_thumbnails_slider'
    });
    $(".prdct_thumbnails_slider").slick({
        dots: false,
        arrows: true,
        slidesToShow: 4,
        slidesToScroll: 1,
        isThumbs: true,
        centerMode: true,
//         infinite: true,
        asNavFor: '.prdct_img_slider',
        focusOnSelect: true,

        responsive: [
            { breakpoint: 1024,
                settings: {
                    slidesToShow:2,
//                    slidesToScroll: 1,
//                    dots: true
                }
            },
            { breakpoint: 600,
                settings: {
                    slidesToShow: 2,
//                    slidesToScroll: 1
                }
            },
            { breakpoint: 480,
                settings: {
                    slidesToShow: 2,
//                    slidesToScroll: 1
                }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ]
    });

</script>
<script type="text/javascript">
//    $(document).ready(function () {
//        $(".tab-pane.active img").ezPlus({
//            gallery: 'gallery_01',
//            cursor: 'pointer',
//            galleryActiveClass: "active",
//            imageCrossfade: true,
//            loadingIcon: "images/spinner.gif",
//            responsive: true,
//        });
//        $(".tab-pane.active img").bind("click", function (e) {
//            var ez = $('.tab-pane.active img').data('ezPlus');
//            ez.closeAll(); //NEW: This function force hides the lens, tint and window
//            $.fancybox(ez.getGalleryList());
//            return false;
//        });
//    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#zoom_03").ezPlus({
            gallery: 'gallery_01',
            cursor: 'pointer',
            galleryActiveClass: "active",
            imageCrossfade: true,
            loadingIcon: "images/spinner.gif",
            responsive: true,
        });
        $("#zoom_03").bind("click", function (e) {
            var ez = $('#zoom_03').data('ezPlus');
            ez.closeAll(); //NEW: This function force hides the lens, tint and window
            $.fancybox(ez.getGalleryList());
            return false;
        });
    });

    $('#addCompareProductDetail').on('click', function (e) {
        $('#social-links').toggleClass('d-none');
    });
</script>
@endsection
