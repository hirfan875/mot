@extends('web.layouts.app')
@section('content')

@section('style')
@endsection
<!--=================
  Start breadcrumb
  ==================-->
<div class="breadcrumb-container">
    <h1>{{__('All Stores')}}</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('breadcrumb.home')}}</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{__('Stores')}}</li>
    </ol>
</div>
<!--=================
  End breadcrumb
  ==================-->
    <div class="container bg-white mt-minus all_vendors">
        <div class="row pt-4 pb-4">
            @foreach($stores as $key => $store)
            <div class="col-md-4">
                <a href="{{route('shop', $store->slug)}}" >
                <div class="cardblock d-flex align-items-center">
                    <div class="vendor_logo">  <img alt="Seller Logo" src="{{ $store->store_data->logo != null ? $store->resize_logo_url(100, 85) : asset('assets/frontend').'/assets/img/product-placeholder.jpg' }}"/></div>
                    <div class="vendor_details">
                        <h2>{{isset($store->store_profile_translates)? $store->store_profile_translates->name : $store->name}}</h2>
                        <div class="star-rating">
                            <span class="fa fa-star @if ($store->getRatingAttribute() >= 1) checked @endif "></span>
                            <span class="fa fa-star @if ($store->getRatingAttribute() >= 2) checked @endif"></span>
                            <span class="fa fa-star @if ($store->getRatingAttribute() >= 3) checked @endif"></span>
                            <span class="fa fa-star @if ($store->getRatingAttribute() >= 4) checked @endif"></span>
                            <span class="fa fa-star @if ($store->getRatingAttribute() == 5) checked @endif"></span>
                        </div>
                        <span class="v_store">  {{__('Visit Store')}}</span>
                    </div>
                </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
        <div class="col-md-12 mt-3 mb-3 mt-lg-5 mb-lg-5">
            <nav aria-label="Page navigation example">
                <div class="pagination justify-content-center">
                    {!! $stores->appends(request()->query())->links() !!}
                </div>
            </nav>
        </div>
</div>
@endsection
