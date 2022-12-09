@extends('web.layouts.app')
@section('content')
<style>

.social .fbtn {
    width: 50px;
    display: inline-block;
    color: #fff;
    text-align: center;
    line-height:18px;
    float: left;
}
.social .fa{padding:15px 0px}
.facebook {
    background-color: #3b5998;
}

.gplus {
    background-color: #dd4b39;
}

.twitter {
    background-color: #55acee;
}

.stumbleupon {
    background-color: #eb4924;
}

.pinterest {
    background-color: #cc2127;
}

.linkedin {
    background-color: #0077b5;
}

.buffer {
    background-color: #323b43;
}

.share-button.sharer {
  height: 20px;
  padding: 100px;
}
.social.active.top {
  transform: scale(1) translateY(-10px);
}
.social.active {
  opacity: 1;
  transition: all 0.4s ease 0s;
  visibility: visible;
}
.social.networks-5 {

}
.social.top {
  margin-top: -80px;
  transform-origin: 0 0 0;
}
.social {
  margin-left: -65px;
  opacity: 0;
  transition: all 0.4s ease 0s;
  visibility: hidden;
}
</style>
<!--=================
  Start breadcrumb
  ==================-->
<div class="breadcrumb-container">
    <h1>{{isset($store->store_profile_translates)? $store->store_profile_translates->name : $store->name}}</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('breadcrumb.home')}}</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{__('breadcrumb.seller')}}</li>
        <li class="breadcrumb-item active" aria-current="page">{{isset($store->store_profile_translates)? $store->store_profile_translates->name : $store->name}}</li>
    </ol>
</div>
<!--=================
  End breadcrumb
  ==================-->

<div class="container seller-rof mt-minus">

<div class="row">
    <div class="col-md-12">
    
      <div class=" seller_banner22 " >
      <!-- <img loading="lazy" src="{{$store->resize_image_url()}}" alt="" class="w-100"> -->
   <div class=" call_center mb-3">
    <div class="vendor_profile">
      <div class="seller_logo">

          <img alt="seller_logo" loading="lazy" src="{{ 
            $store->store_data->logo != null ? 
            $store->resize_logo_url(100, 85) : 
            asset('assets/frontend').'/assets/img/product-placeholder.jpg' 
        }}">

      </div>
      <div class="seller_details"> 
      <h2 class="store_name">{{isset($store->store_profile_translates)? $store->store_profile_translates->name : $store->name}}</h2>
      <div class="star-rating mb-2 mt-2">
                    <span class="fa fa-star @if ($store->getRatingAttribute() >= 1 ) checked @endif "></span>
                    <span class="fa fa-star @if ($store->getRatingAttribute() >= 2  ) checked @endif"></span>
                    <span class="fa fa-star @if ($store->getRatingAttribute() >= 3) checked @endif"></span>
                    <span class="fa fa-star @if ($store->getRatingAttribute() >= 4) checked @endif"></span>
                    <span class="fa fa-star @if ($store->getRatingAttribute() == 5) checked @endif"></span>
                </div><small>{{$store->getPositiveRatingPercent()}}% {{__('mot-products.positive_review')}} ({{$store->lifetimeRatingCount()}} {{__('mot-products.lifetime_ratings')}})</small>
      </div>
      <button class=" askquestion" data-toggle="modal" data-target="#askquestion"> <i class="fa fa-phone"></i> Any Query?</button>
    </div>
    </div>
      </div>
    </div>
  </div>

  @include('web.partials.ask-seller-modal')
  
       
    <ul class="nav nav-pills nav-pills-container mb-3 nav-justified" id="pills-tab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="payment-tab" data-toggle="pill" href="#tab4" role="tab" aria-controls="tab4" aria-selected="false">{{__('products')}}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link " id="checkout-tab" data-toggle="pill" href="#tab1" role="tab" aria-controls="tab1" aria-selected="true">{{__('feedback')}}</a>
        </li>
<!--        <li class="nav-item">
            <a class="nav-link" id="payment-tab" data-toggle="pill" href="#tab3" role="tab" aria-controls="tab3" aria-selected="false">{{__('policies')}}</a>
        </li>-->
        <li class="nav-item">
            <a class="nav-link" id="payment-tab" data-toggle="pill" href="#tabAbout" role="tab" aria-controls="tabAbout" aria-selected="false">{{__('aboutus')}}</a>
        </li>

    </ul>
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane  active" id="tab4" role="tabpanel" aria-labelledby="payment-tab">
            <!-- Form Start From Here -->
            <div class="">
                <div class="row products_container">
                    @foreach($products as $product)
                    <div class="col-6 col-md-3">
                        @if($product->soldOut())
                        <div class="badg-sold"><span class="badge badge-danger">{{__('Sold Out')}}</span></div>
                        @endif
                        @include('web.partials.product' , ['product_row' => $product])
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-12 mt-3 mb-3 mt-lg-5 mb-lg-5">
                <nav aria-label="Page navigation example">
                    <div class="pagination justify-content-center">
                        {!! $products->appends(request()->query())->links() !!}
                    </div>
                </nav>
            </div>
            <!-- Form Ends From Here -->
        </div>

        <div class="tab-pane fade show " id="tab1" role="tabpanel" aria-labelledby="checkout-tab">

            <!-- Form Start From Here -->
            <div class="">
                <div class="container">
                    <div class="row ">
                        <div class="col-md-3 border text-center">
                            <div class="card-body">
                                <h1 class="text-danger">{{$store->getRatingAttribute()}}</h1>
<!--                                <div class="sub-row text-warning">
                                    <i class="fa fa-star @if ($store->getRatingAttribute() >= 1 ) checked @endif "></i>
                                    <i class="fa fa-star @if ($store->getRatingAttribute() >= 2  ) checked @endif"></i>
                                    <i class="fa fa-star @if ($store->getRatingAttribute() >= 3) checked @endif"></i>
                                    <i class="fa fa-star @if ($store->getRatingAttribute() >= 4) checked @endif"></i>
                                    <i class="fa fa-star @if ($store->getRatingAttribute() == 5) checked @endif"></i>
                                </div>-->
                                <div class="star-rating">
                                    <span class="fa fa-star @if ($store->getRatingAttribute() >= 1) checked @endif "></span>
                                    <span class="fa fa-star @if ($store->getRatingAttribute() >= 2) checked @endif"></span>
                                    <span class="fa fa-star @if ($store->getRatingAttribute() >= 3) checked @endif"></span>
                                    <span class="fa fa-star @if ($store->getRatingAttribute() >= 4) checked @endif"></span>
                                    <span class="fa fa-star @if ($store->getRatingAttribute() == 5) checked @endif"></span>
                                </div>
                                <p>{{$store->lifetimeRatingCount()}} {{__('rating')}}</p>
                            </div>
                        </div>
                        <div class="col-md-5 border">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4 col-md-3">
                                        <h6>{{__('5 Stars')}}</h6>
                                    </div>
                                    <div class="col-6 col-md-7 pt-1">
                                        <div class="progress">
                                            <div class="progress-bar bg-success" style="width:{{ isset($store_reviews[5]) ? $store_reviews[5]->count('rating')/$store->lifetimeRatingCount()*100 : 0}}%"></div>
                                        </div>
                                    </div>
                                    <div class="col-2 col-md-2">
                                        <h6>({{ isset($store_reviews[5]) ? $store_reviews[5]->count('rating') : 0}})</h6>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4 col-md-3">
                                        <h6>{{__('4 Stars')}}</h6>
                                    </div>
                                    <div class="col-6 col-md-7 pt-1">
                                        <div class="progress">
                                            <div class="progress-bar bg-success" style="width:{{ isset($store_reviews[4]) ? $store_reviews[4]->count('rating')/$store->lifetimeRatingCount()*100 : 0}}%"></div>
                                        </div>
                                    </div>
                                    <div class="col-2 col-md-2">
                                        <h6>({{ isset($store_reviews[4]) ? $store_reviews[4]->count('rating') : 0}})</h6>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4 col-md-3">
                                        <h6>{{__('3 Stars')}}</h6>
                                    </div>
                                    <div class="col-6 col-md-7 pt-1">
                                        <div class="progress">
                                            <div class="progress-bar bg-warning" style="width:{{ isset($store_reviews[3]) ? $store_reviews[3]->count('rating')/$store->lifetimeRatingCount()*100 : 0}}%"></div>
                                        </div>
                                    </div>
                                    <div class="col-2 col-md-2">
                                        <h6>({{ isset($store_reviews[3]) ? $store_reviews[3]->count('rating') : 0}})</h6>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4 col-md-3">
                                        <h6>{{__('2 Stars')}}</h6>
                                    </div>
                                    <div class="col-6 col-md-7 pt-1">
                                        <div class="progress">
                                            <div class="progress-bar bg-danger" style="width:{{ isset($store_reviews[2]) ? $store_reviews[2]->count('rating')/$store->lifetimeRatingCount()*100 : 0}}%"></div>
                                        </div>
                                    </div>
                                    <div class="col-2 col-md-2">
                                        <h6>({{ isset($store_reviews[2]) ? $store_reviews[2]->count('rating') : 0}})</h6>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4 col-md-3">
                                        <h6>{{__('1 Star')}}</h6>
                                    </div>
                                    <div class="col-6 col-md-7 pt-1">
                                        <div class="progress">
                                            <div class="progress-bar bg-danger" style="width:{{ isset($store_reviews[1]) ? $store_reviews[1]->count('rating')/$store->lifetimeRatingCount()*100 : 0}}%"></div>
                                        </div>
                                    </div>
                                    <div class="col-2 col-md-2">
                                        <h6>({{ isset($store_reviews[1]) ? $store_reviews[1]->count('rating') : 0}})</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 border text-center">
                            <div class="card-body">
                                <i class="fa fa-pencil-square fa-3x text-success"></i>
                                @auth('customer')
                                    @if($storeOrders > 0)
                                    <a href="{{route('store-order-review' ,$store->id)}}">
                                        <h4>{{__('Write Your Reviews')}}</h4>
                                    </a>
                                    @else
                                    <h4>{{__('Write Your Reviews')}}</h4>
                                    @endif
                                @else
                                    <h4>{{__('Write Your Reviews')}}</h4>
                                @endauth
                                <small>{{__('Share your experience with us')}}</small>
                            </div>
                        </div>
                        
                    </div>

                </div>
                @foreach($store->reviews as $reviews)

                <div class="container feedback_d">
                    <div class="one-review">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="comment_img">
                                    @if($reviews->customer->image)
                                    <img alt="{{$reviews->customer->image}}" id="profileImage1" loading="lazy" src="{{asset('storage/'.$reviews->customer->image)}}" height="50" width="50" />
                                    @else
                                    <span class="avatar-in" > 
                                    <img src="https://cdn1.iconfinder.com/data/icons/avatar-2-2/512/Biologist-512.png" width="40" alt="avtar_img" class="mr-2 avtar_img  "/>    
                                    <!--{{ getAvatarCode($reviews->customer->name) }} --> </span>
                                    @endif
                                </div>
                                <div class="feedback_right_content">
                                <p>{{$reviews->customer->name}}</p>
                                <div class="ratingsStar text-success">
                                <div class="r_block">
                                @for ($i=1; $i<=$reviews->rating; $i++)
                                    <i class="fa fa-star"></i>
                                    @endfor
                                </div>
                                <div class="user_rev">
                                
                                    <h6>{{$reviews->getReviewTitleAttribute()}}</h6>
                                    <p>{{$reviews->comment}}</p>
                               
                               </div>
                        </div>
                            </div>
                          
                        </div>
                        <div class="col-md-6  text-right">
                                <small class="text-success reviews_date">{{$reviews->created_at->format('d/m/Y')}}</small>
                                <button type="button" class="feedback_sharte_btn "><i class="fa fa-share-alt"></i> </button>
                                <div class="social top center networks-5 ">
        <!-- Facebook Share Button -->
        <a class="fbtn share facebook" href="https://www.facebook.com/sharer/sharer.php?u={{  Request::url() }}" target="_blank"><i class="fa fa-facebook"></i></a>
        <!-- Google Plus Share Button -->
        <a class="fbtn share gplus" href="https://plus.google.com/share?url={{  Request::url() }}" target="_blank"><i class="fa fa-google-plus"></i></a>
        <!-- Twitter Share Button -->
        <a class="fbtn share twitter" href="https://twitter.com/intent/tweet?text=title&amp;url={{  Request::url() }}&amp;via=creativedevs" target="_blank"><i class="fa fa-twitter"></i></a>
        <!-- Pinterest Share Button -->
        <a class="fbtn share pinterest" href="https://pinterest.com/pin/create/button/?url={{  Request::url() }}&amp;description=data&amp;media=image" target="_blank"><i class="fa fa-pinterest"></i></a>
        <!-- LinkedIn Share Button -->
        <a class="fbtn share linkedin" href="https://www.linkedin.com/shareArticle?mini=true&amp;url=url&amp;title=title&amp;source={{  Request::url() }}" target="_blank"><i class="fa fa-linkedin"></i></a>
        </a>
    </div>
<div class="helpfull_block">
<div class="dropdown">
    <button type="button" class="dots" data-toggle="dropdown"><span></span><span></span><span></span></button>
    <ul class="dropdown-menu p-3">
      <li><a href="#">{{__('  Not Useful?')}}</a></li>
      <li><a href="#">{{__('Yes, it was helpful.')}}</a></li>
      <li><a href="#">{{__('Report?')}}</a></li>
    </ul>
  </div>
</div>

                            </div>
                        </div>
                        
                       
                        
                    </div>
                </div>
                @endforeach
            </div>
            <!-- Form Ends From Here -->
        </div>
        <div class="tab-pane fade" id="tab3" role="tabpanel" aria-labelledby="payment-tab">
            <!-- Form Start From Here -->
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <p> {!! isset($store->store_profile_translates)? $store->store_profile_translates->policies:$store->getPoliciesAttribute() !!} </p>
                    </div>
                </div>
            </div>
            <!-- Form Ends From Here -->
        </div>
        <div class="tab-pane fade" id="tabAbout" role="tabpanel" aria-labelledby="payment-tab">
            <!-- Form Start From Here -->
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <p>{!! isset($store->store_profile_translates)? $store->store_profile_translates->description:$store->getDescriptionAttribute() !!}</p>
                     </div>
                </div>
            </div>
            <!-- Form Ends From Here -->
        </div>

    </div>
</div>


<div class="backtotop mt-2"><a href="#" id="toTop">{{__('BACK TO TOP')}}</a></div>
<div class="up-btn" style="display: none;">
    <i class="fa fa-chevron-up"></i>
</div>
@endsection
@section('scripts')
<!-- JavaScript Libraries -->

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
                    ShowSuccessModal("{{trans('Product has been added to compare list')}}", 2000);
                    $(".compare-btn").css("display", "block");
                }
            }
        });
    }
</script>

<script>
    function swapStyleSheet(sheet) {
        document.getElementById('pagestyle').setAttribute('href', sheet);
    }
</script>
<script type="text/javascript">
    //select product
    jQuery('.prev').on('click', function(e) {
        e.stopImmediatePropagation();
        var btn_group_parent = $(this).closest('.btn-group');
        var number = 0;
        var show_number = btn_group_parent.find('.show-number');
        var a = show_number.text();
        a = parseInt(a);
        if (a > 1) {
            number = a - 1;
        } else {
            number = 1;
        }
        show_number.text(number);

    });

    jQuery('.next').on('click', function(e) {
        e.stopImmediatePropagation();
        var btn_group_parent = $(this).closest('.btn-group');
        var number = 0;
        var show_number = btn_group_parent.find('.show-number');
        var a = show_number.text();
        a = parseInt(a);
        if (a > 0) {
            number = a + 1;
        }
        show_number.text(number);
    });
</script>
<script src="{{ asset('assets/frontend') }}/assets/js/intlTelInput.js"></script>
<script>
    $('#per_page').on('change', function() {
        submitFilterForm();
    });
</script>
<script>
    var input = document.querySelector("#phone");
    window.intlTelInput(input, {
        // allowDropdown: false,
        // autoHideDialCode: false,
        // autoPlaceholder: "off",
        // dropdownContainer: document.body,
        // excludeCountries: ["us"],
        // formatOnDisplay: false,
        // geoIpLookup: function(callback) {
        //   $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
        //     var countryCode = (resp && resp.country) ? resp.country : "";
        //     callback(countryCode);
        //   });
        // },
        // hiddenInput: "full_number",
        // initialCountry: "auto",
        // localizedCountries: { 'de': 'Deutschland' },
        // nationalMode: false,
        // onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
        // placeholderNumberType: "MOBILE",
        // preferredCountries: ['cn', 'jp'],
        // separateDialCode: true,
        utilsScript: "assets/js/utils.js",
    });
</script>
<script>
    var input = document.querySelector("#phone1");
    window.intlTelInput(input, {
        // allowDropdown: false,
        // autoHideDialCode: false,
        // autoPlaceholder: "off",
        // dropdownContainer: document.body,
        // excludeCountries: ["us"],
        // formatOnDisplay: false,
        // geoIpLookup: function(callback) {
        //   $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
        //     var countryCode = (resp && resp.country) ? resp.country : "";
        //     callback(countryCode);
        //   });
        // },
        // hiddenInput: "full_number",
        // initialCountry: "auto",
        // localizedCountries: { 'de': 'Deutschland' },
        // nationalMode: false,
        // onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
        // placeholderNumberType: "MOBILE",
        // preferredCountries: ['cn', 'jp'],
        // separateDialCode: true,
        utilsScript: "assets/js/utils.js",
    });
</script>


<script>
    $(document).ready(function() {
        //custom button for homepage
        $(".share-btn").click(function(e) {
            $('.networks-5').not($(this).next(".networks-5")).each(function() {
                $(this).removeClass("active");
            });
            $(this).next(".networks-5").toggleClass("active");
        });
    });

</script>
@endsection
