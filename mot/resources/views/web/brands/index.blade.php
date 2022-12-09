@extends('web.layouts.app')
@section('content')

@section('style')
@endsection
<!--=================
  Start breadcrumb
  ==================-->
<div class="breadcrumb-container">
    <h1>{{__('All Brands')}}</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('breadcrumb.home')}}</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{__('Brands')}}</li>
    </ol>
</div>
<!--=================
  End breadcrumb
  ==================-->
<div class="container mt-minus">
    <div class="row mt-4">
        <div class="col-md-12 mb-4">
            <div class="seller_banner">
                <img loading="lazy" src="{{ cdn_url('/assets/frontend') }}/assets/img/seller.png" alt="seller_banner">
            </div>
        </div>
    </div>
    <div class="tab-content cate-page" id="pills-tabContent">
        <div class="tab-pane  active" id="tab4" role="tabpanel" aria-labelledby="payment-tab">
            <!-- Form Start From Here -->
            <div class="container">
                <div class="row products_container brands_container">
                    @foreach($brands as $brand)
                        <div class="col-md-4">
                            <div class="products_wrapper">
                                <a href="{{route('products', ['brands' => [$brand->slug]])}}">
                                    <figure>
                                        <img loading="lazy" src="{{ $brand->image != null ? route('resize', [286, 175, $brand->image]) : asset('assets/frontend').'/assets/img/product-placeholder.jpg' }}" alt="placeholder">
                                    </figure>
                                    <h2>{{$brand->brand_translates ? $brand->brand_translates->title : $brand->title}}</h2>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <!-- Form Ends From Here -->
            <div class="col-md-12 mt-3 mb-3 mt-lg-5 mb-lg-5">
                <nav aria-label="Page navigation example">
                    <div class="pagination justify-content-center">
                        {!! $brands->appends(request()->query())->links() !!}
                    </div>
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection
