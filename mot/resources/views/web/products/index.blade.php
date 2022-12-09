@extends('web.layouts.app')
@section('content')

@section('style')
<style>
    .read-more-state {
        display: none;
    }

    .side_bar_close {
    display:none;
}
    .products_sidebar_menu .brands .form-group.read-more-target {
        opacity: 0;
        max-height: 0;
        font-size: 0;
        transition: .25s ease;
        display: none;
    }

    .read-more-state:checked~.read-more-wrap .read-more-target {
        opacity: 1;
        font-size: inherit;
        max-height: 999em;
        display: block;
    }

    .read-more-state~.read-more-trigger:before {
        content: 'Show More';
    }

    .read-more-state:checked~.read-more-trigger:before {
        content: 'Show Less';
    }

    .read-more-trigger {
        cursor: pointer;
        display: inline-block;
        padding: 0 .5em;
        color: #fff;
        font-size: .9em;
        line-height: 2;
        border: 1px solid #E11E26;
        border-radius: .25em;
        background: #E11E26;
    }

    .filter-price-validation {
        font-size: 12px;
    }
</style>
@endsection

<form id="filter-form">
    <input type="hidden" id="view_type" name="view_type" value="{{request()->view_type}}">
    <input type="hidden" name="keyword" value="{{request()->keyword}}">
    <input type="hidden" name="category_id" value="{{request()->category_id}}">
</form>
<!--=================
    Start breadcrumb
    ==================-->
<div class="breadcrumb-container">
    <h1>{{__('breadcrumb.products')}}</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('breadcrumb.home')}}</a></li>
        @if(isset($category->title))
        <li class="breadcrumb-item"><a href="{{route('categories')}}">{{__('breadcrumb.categories')}}</a></li>
        @endif
        <li class="breadcrumb-item active" aria-current="page">{{isset($breadcrumb) ? $breadcrumb : 'All Categories'}}</li>
    </ol>
</div>
<!--=================
    End breadcrumb
    ==================-->
<!--=================
    Products add Banners
    ==================-->
@if($products->count() > 0)
<div class="container product_banners">
    <div class="row no-gutters">
        <div class="col-md-12">
            @if($category != null && $category->banner != null)
                <img loading="lazy" src="{{ isset($category->category_translates->banner) ? cdn_url('/storage/original/' . $category->category_translates->banner) :  cdn_url('/storage/original/' . $category->banner)}}" alt="{{$category->banner}}"/>
            @elseif($top_parent_category != null && $top_parent_category->banner != null)
                <img loading="lazy" src="{{cdn_url('/storage/original/' . $top_parent_category->banner)}}" alt="{{$top_parent_category->banner}}"/>
            @else
            <img loading="lazy" src="{{ asset('assets/frontend') }}/assets/img/all-products-main-banner.jpg" alt="banner"/>
            @endif
        </div>
    </div>
</div>
@endif
<!--=================
    Products add Banners  Ends
    ==================-->
<!--=================
    Filters
    ==================-->
<!-- <div class="container products_filters-main d-none d-md-none d-lg-none">
    <a class="btn btn-primary btn-block  mt-4 mb-4">Filters <i class="fa fa-filter" aria-hidden="true"></i></a>
</div> -->
@if($products->count() > 0)
<div class="container products_filters1 mt-lg-0 mt-2">
<div class="row no-gutters">
    <div class="col-md-12">
<div  class="justify-content-between filters_block">
<div class="d-flex justify-content-between refresh align-items-center">
                <h5 class="filterin2 d-none">{{__('mot-products.filters_heading')}} <i class="fa fa-filter d-inline-block d-lg-none"></i></h5>

            </div>
            <div class="sortby">
                <select class="form-control" name="sort_by" id="sort_by" form="filter-form">
                    <option value="">{{__('mot-products.filters.sort_by')}}</option>
                    <option value="new">{{__('mot-products.filters.new')}}</option>
                    <option value="old" {{request()->sort_by == 'asc' ? 'selected' : null}}>{{__('mot-products.filters.old')}}</option>
                    <option value="price_low" {{request()->sort_by == 'price_low' ? 'selected' : null}}>{{__('mot-products.filters.Price_low_to_high')}}</option>
                    <option value="price_high" {{request()->sort_by == 'price_high' ? 'selected' : null}}>{{__('mot-products.filters.Price_high_to_low')}}</option>
                </select>
            </div>
            <div class="showPro ">
                <select class="form-control" name="per_page" id="per_page" form="filter-form">
                    <option value="15">
                        {{__('mot-products.filters.pagination_show')}} : 15
                    </option>
                    <option value="30" {{request()->per_page == 30 ? 'selected' : null}}>
                        {{__('mot-products.filters.pagination_show')}} : 30
                    </option>
                    <option value="50" {{request()->per_page == 50 ? 'selected' : null}}>
                        {{__('mot-products.filters.pagination_show')}} : 50
                    </option>
                </select>
            </div>
            <div class="views ">
                <a href="javascript:;" id="gridViewBtn" class="thumbview {{request()->view_type != 'list' ? 'active' : null}}"><i class="fa fa-th"></i></a>
                <a href="javascript:;" id="listViewBtn" class="listview {{request()->view_type == 'list' ? 'active' : null}}"><i class="fa fa-align-left"></i></a>
            </div>
            </div>
            </div>
</div>
</div>
@endif
<!--=================
    Filters Ends
==================-->
<!--=================
Products Area
==================-->
<div class="container products_block">
    <div class="row ">
        <div class="col-md-3">
            @if($products->count() > 0)
            <!-- Left Area Menus -->
            <div class="products_sidebar_menu">
                <span class="side_bar_close"><i class="icon-close"></i></span>
                <!--Accordion wrapper-->
                <div class="accordion md-accordion" id="accordionEx" role="tablist" aria-multiselectable="true">
                    <!-- Filter Categories start -->
                    <div class="card">
                        <!-- Card header -->
                        <div class="card-header" role="tab" id="headingOne1">
                            <a data-toggle="collapse" data-parent="#accordionEx" href="#collapseOne1" aria-expanded="true" aria-controls="collapseOne1">
                                <h5 class="mb-0">
                                    {{isset($category) ? $category->title : __('Categories')}}<i class="fa fa-angle-down rotate-icon"></i>
                                </h5>
                            </a>
                        </div>
                        <!-- Card body -->
                        <div id="collapseOne1" class="collapse show" role="tabpanel" aria-labelledby="headingOne1" data-parent="#accordionEx">
                            <div class="card-body">
                                <div class="brands">
                                    @if($categories->count() > 0)
                                    @foreach($categories as $category)
                                        <div class="form-group">
                                            <input type="checkbox" name="categories[]" form="filter-form" id="{{$category->slug}}" value="{{$category->id}}" onChange="submitFilterForm()" {{in_array($category->id, $filtered_categories) ? 'checked' : null}}>
                                            <label for="{{$category->slug}}">{{$category->category_translates ? $category->category_translates->title : $category->title}}</label>
                                        </div>
                                    @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Filter Categories end -->
                    <!-- Filter Price range start -->
                    <div class="card">
                        <!-- Card header -->
                        <div class="card-header price_tag" role="tab" id="headingTwo2">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordionEx" href="#collapseTwo2" aria-expanded="false" aria-controls="collapseTwo2">
                                <h5 class="mb-0">
                                    {{__('mot-products.filters.price')}} <i class="fa fa-angle-down rotate-icon"></i>
                                </h5>
                            </a>
                        </div>
                        <!-- Card body -->
                        <div id="collapseTwo2" class="collapse show" role="tabpanel" aria-labelledby="headingTwo2" data-parent="#accordionEx">
                            <div class="card-body slider_range">
                                <div class="min-max-input row no-gutters w-100">
                                    <div class="form-group col">
                                        <input type="number" name="min_price" class="form-control form-control-sm" id="min_price" placeholder="{{__('Min')}}" min="1" value="{{request()->min_price}}" form="filter-form">
                                    </div>
                                    <div class="divider col-1 text-center" style="font-size: 20px; margin-top:10px;">-</div>
                                    <div class="form-group col">
                                        <input type="number" name="max_price" class="form-control form-control-sm" id="max_price" placeholder="{{__('Max')}}" min="1" value="{{request()->max_price}}" form="filter-form">
                                    </div>
                                    <div class="divider col-1 text-center" style="font-size: 20px;"></div>
                                    <p class="text-danger filter-price-validation"></p>
                                    <div class="form-group col-3">
                                        <button type="submit" class="btn btn-primary delivery-here" id="apply_price_filter">{{__('OK')}}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Filter Price range end -->
                    <!-- Filter brands start -->
                    <!-- hiding brands filter for time being -->
                    {{--@if($brands->count() > 0)
                    <div class="card">
                        <!-- Card header -->
                        <div class="card-header" role="tab" id="headingThree3">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordionEx" href="#collapseThree3" aria-expanded="false" aria-controls="collapseThree3">
                                <h5 class="mb-0">
                                    {{ __('mot-products.filters.brand') }} <i class="fa fa-angle-down rotate-icon"></i>
                                </h5>
                            </a>
                        </div>
                        <!-- Card body -->
                        <div id="collapseThree3" class="collapse show" role="tabpanel" aria-labelledby="headingThree3" data-parent="#accordionEx">
                            <div class="card-body">
                                <div class="brands">
                                    <input type="checkbox" class="read-more-state" id="post-2" />
                                    <form class="">
                                        @foreach($brands as $brand)
                                        <div class="form-group">
                                            <input type="checkbox" name="brands[]" form="filter-form" id="{{$brand->slug}}" value="{{$brand->slug}}" onChange="submitFilterForm()" {{in_array($brand->id, $filtered_brands) ? 'checked' : null}}>
                                            <label for="{{$brand->slug}}">{{ $brand->brand_translates ? $brand->brand_translates->title : $brand->title}}</label>
                                        </div>
                                        @endforeach
                                    </form>
                                    <!-- @if($brands->count() >= 10)
                                        <label for="post-2" class="read-more-trigger"></label>
                                    @endif -->
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif--}}
                    <!-- Filter brands end -->

                    <!-- Filter attributes start -->
                    @if($attributes->count() > 0)
                    @foreach($attributes as $attribute)
                    <div class="card">
                        <!-- Card header -->
                        <div class="card-header" role="tab" id="headingThree3">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordionEx" href="#{{$attribute[0]->slug}}" aria-expanded="false" aria-controls="{{$attribute[0]->slug}}">
                                <h5 class="mb-0">
                                    {{$attribute[0]->attribute_translates ? $attribute[0]->attribute_translates->title : $attribute[0]->title}} <i class="fa fa-angle-down rotate-icon"></i>
                                </h5>
                            </a>
                        </div>

                        <!-- Card body -->
                        <div id="{{$attribute[0]->slug}}" class="collapse show" role="tabpanel" aria-labelledby="headingThree3" data-parent="#accordionEx">
                            <div class="card-body ">
                                <div class="brands">
                                    @foreach($attribute[0]->options as $option)

                                    <div class="form-group">
                                        <input type="checkbox" name="variants[]" form="filter-form" value="{{$option->slug}}" id="{{$option->slug}}" onChange="submitFilterForm(this.name, this.value)" {{in_array($option->id, $filtered_attributes) ? 'checked' : null}}>
                                        <label class="green" for="{{$option->slug}}">{{$option->attribute_translates ? $option->attribute_translates->title : $option->title}}</label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    @endforeach
                    @endif
                    <!-- Filter attributes end -->
                    <div class="btns-rr mt-3 reset-btn-rr text-center">
                        <a href="{{request()->url()}}" class="reset_btn ml-3">Reset</a>
                        <!--<a href="#" class="done_btn">Done</a>-->
                    </div>
                </div>
                <!-- Accordion wrapper -->
            </div>
            @endif
            <!-- Left Area Menus Ends-->
        </div>
        <!--=================
		Product list View
		==================-->
        @if($products->count() == 0)
                    <div class="col-md-12 mt-3 text-center">
                        <div class="no_product_found_img"><img loading="lazy" alt="norf" src="{{ asset('images') }}/norf.png"></div>
                        <!--<h5>{{__('No product found')}}</h5>-->
                    </div>
                @endif
        <div class="col-md-9 {{request()->view_type == 'list' ? 'd-none' : null}}" id="listView">
            <div class="row products_container">

                @foreach($products as $product_row)
                <div class="col-6 col-md-4">
                    @if($product_row->soldOut())
                    <div class="badg-sold"><span class="badge badge-danger">{{__('Out of Stock')}}</span></div>
                    @endif
                    @include('web.partials.product')
                </div>
                @endforeach
            </div>
        </div>
                
        <!--=================
		Product grid View
		==================-->
        <div class="col-md-9 {{request()->view_type != 'list' ? 'd-none' : null}}" id="gridView">
            <div class="row products_container">
                <div class="col-md-12">
                    <div class="products_wrapper">
                        @if($products->count() == 0)
                            <div class="mt-3 text-center">
                                <h5>{{__('No product found')}}</h5>
                            </div>
                        @endif
                        @foreach($products as $product)
                        
                        <div class="row border-top p-4">

                            <div class="col-md-4">
                                @if($product->soldOut())
                                <div class="badg-sold"><span class="badge badge-danger">{{__('Sold Out')}}</span></div>
                                @endif
                                <figure>
                                    @if(!$product->soldOut())
                                    <div class="seller-tags">
                                        @if($product->isTop())
                                        <span class="top_offer">{{__('mot-products.top')}}</span>
                                        @endif
                                        @if($product->isNew())
                                        <span class="new_offer">{{__('mot-products.new')}}</span>
                                        @endif
                                        @if($product->isSale())
                                        <span class="sale_offer">{{ round((($product->price - $product->promo_price)  / $product->price) * 100) .  __('% OFF')}}</span>
                                       @endif
                                    </div>
                                    @endif
                                    <a href="{{$product->getViewRoute()}}"><img loading="lazy" src="{{$product->product_listing()}}" alt="{{$product->title}}"/></a>
                                </figure>
                            </div>
                            <div class="col-md-8">
                                <div class="loading-div d-none" id="loading-div-{{$product_row->id}}">
                                    <div class="spinner-border text-danger" role="status">
                                        <span class="sr-only">{{__('Loading...')}}</span>
                                    </div>
                                </div>

                                <div class="cart_footers d-none" id="social-share-list-{{$product->id}}">
                                    <div id="social-links">
                                        @php
                                            $similarSocialLinks = Share::page($product->getViewRoute(), $product->title)->facebook()->twitter()->whatsapp()->getRawLinks();
                                        @endphp
                                        <ul>
                                            @foreach($similarSocialLinks as $similarSocialKey => $similarSocialLink)
                                                @if($similarSocialLink == 'facebook')    
                                                    <li>
                                                    <a href="{{$similarSocialLink}}" class="social-button " id=""><img loading="lazy" alt="facebook" src="{{ asset('images') }}/facebook.png"></a>
                                                    </li>
                                                @endif
                                                @if($similarSocialLink == 'twitter')    
                                                    <li>
                                                    <a href="{{$similarSocialLink}}" class="social-button " id=""><img loading="lazy" alt="twitter" src="{{ asset('images') }}/twitter.png"></a>
                                                    </li>
                                                @endif
                                                @if($similarSocialLink == 'whatsapp')    
                                                    <li>
                                                    <a href="{{$similarSocialLink}}" class="social-button " id=""><img loading="lazy" alt="whatsapp" src="{{ asset('images') }}/whatsapp.png"></a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>

                                <div class="list_cart">
                                    <h2><a href="{{$product->getViewRoute()}}" title="{{isset($product->product_translates)? $product->product_translates->title:$product->title}}">{{\Illuminate\Support\Str::limit(isset($product->product_translates)? $product->product_translates->title:$product->title, 35)  }}</a></h2>
                                    <h3 class="price mt-2">
                                        <span class="mm_price1">{{currency_format($product->promo_price, $currency)}}</span>
                                        @if($product->promo_price < $product->price)
                                            <span class="offer_price" style="text-decoration: line-through;">{{currency_format($product->price, $currency)}}</span>
                                            @endif
                                    </h3>
                                    <p class="pb-4 pt-2">
                                        {!! Str::limit(strip_tags(isset($product->product_translates)? $product->product_translates->data:$product->data), 200) !!}
                                    </p>
                                    @if($product->attributes->count() > 0)
                                    <a class="buynow mt1" href="{{$product->getViewRoute()}}"><i class="icon-basket" ></i> {{__('mot-products.buy')}}</a>
                                    @else
                                    <a class="buynow mt1" href="javascript:;" onclick="addToCart({{$product->id}}, {{$product->stock}}, {{$product->title}}, {{$product->price}})"><i class="icon-basket"></i> {{__('mot-products.buy')}}</a>
                                    @endif
                                    @if(Auth::guard('customer')->user() == null)
                                    <a class="mt1" href="{{route('my-account')}}"><i class="icon-heart"></i></a>
                                    @else
                                    @if ($product->IsWishlist())
                                    <a href="javascript:;" id="remove-{{$product->id}}" data-id="{{$product->id}}" data-action="remove" class="wishlist">
                                        @else
                                        <a href="javascript:;" id="add-{{$product->id}}" data-id="{{$product->id}}" data-action="add" class="wishlist">
                                            @endif
                                            <i id="wishlist-sec-{{$product->id}}" class="{{$product->IsWishlist() ? 'fa fa-heart' : 'icon-heart'}}"></i></a>
                                        @endif
                                        <!-- <a href="#wish" class="wishlist"><i class="icon-heart"></i></a> -->
                                        <a href="javascript:;" id="addCompareProduct" data-id="{{$product->id}}" onclick="toggleSocialShare({{$product->id}})" class="compare"><i class="icon-share"></i></a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="col-md-12 mt-3 mb-3 mt-lg-5 mb-lg-5">
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <li class="page-item">
                    <a class="page-link" href="#" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                    <span class="sr-only">{{__('Previous')}}</span>
                    </a>
                </li>
                <li class="page-item"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">4</a></li>
                <li class="page-item"><a class="page-link" href="#">5</a></li>
                <li class="page-item"><a class="page-link" href="#">6</a></li>
                <li class="page-item"><a class="page-link" href="#">7</a></li>
                <li class="page-item"><a class="page-link" href="#">8</a></li>
                <li class="page-item"><a class="page-link" href="#">9</a></li>
                <li class="page-item"><a class="page-link" href="#">10</a></li>
                <li class="page-item">
                    <a class="page-link" href="#" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                    <span class="sr-only">{{__('Next')}}</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div> --}}

    <div class="col-md-12 mt-3 mb-3 mt-lg-5 mb-lg-5">
        <nav aria-label="Page navigation example">
            <div class="pagination justify-content-center">
                {!! $products->appends(request()->query())->links() !!}
            </div>
        </nav>
    </div>
</div>
<!--=================
Products Area
  ==================-->

@endsection
@section('scripts')
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

    $('#sort_by').on('change', function() {
        submitFilterForm();
    });

    $('#per_page').on('change', function() {
        submitFilterForm();
    });

    $('#listViewBtn').on('click', function() {
        $('#gridViewBtn').removeClass('active');

        $(this).removeClass('active');
        $(this).addClass('active');
        showListView();
    });

    $('#gridViewBtn').on('click', function() {
        $('#listViewBtn').removeClass('active');

        $(this).removeClass('active');
        $(this).addClass('active');
        showGridView();
    });

    function showListView() {
        $('#gridView').removeClass('d-none');
        $('#listView').addClass('d-none');

        $('#view_type').val('list');
    }

    function showGridView() {
        $('#gridView').addClass('d-none');
        $('#listView').removeClass('d-none');

        $('#view_type').val('grid');
    }

    function submitFilterForm() {
        $('#filter-form').submit();
    }

    function resetFilterForm() {
        /* get current url without query(filtered) params and redirect */
        window.location.href = window.location.href.split('?')[0];
    }

    $('#apply_price_filter').on('click', function() {
        validateFilterPrice();
    });

    function validateFilterPrice() {
        let min_price = parseInt($('#min_price').val()) || 0;
        let max_price = parseInt($('#max_price').val()) || 0;
        console.log(min_price, max_price);

        if (min_price == 0 && max_price == 0) {
            $('.filter-price-validation').text("{{trans('Please insert minimum or maximum price.')}}");
            return false;
        }

        if (min_price > 0 && max_price > 0) {
            if (min_price > max_price) {
                $('.filter-price-validation').text("{{trans('Maximum value must be greater than minimum value.')}}");
                return false;
            }
        }

        $('.filter-price-validation').text('');

        submitFilterForm();
    }





</script>

@endsection
