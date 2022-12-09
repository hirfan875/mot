@extends('web.layouts.app')
@section('content')
<!--=================
    Start breadcrumb
    ==================-->
<div class="breadcrumb-container">
    <h1>{{__('breadcrumb.daily_deals')}}</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('breadcrumb.home')}}</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{__('breadcrumb.daily_deals')}}</li>
    </ol>
</div>
<!--=================
    End breadcrumb
    ==================-->
<!--=================
    Products add Banners
    ==================-->
<!--=================
    Products add Banners  Ends
    ==================-->
<!--=================
Products Area
==================-->
<div class="container products_block">
    <div class="row no-gutters">
        <!--=================
                Product list View
                ==================-->
        <div class="col-md-12">
            <div class="row today_deals">
                @foreach($deals as $deal)
                <div class="col-md-3">
                    <a href="{{$deal->product->getViewRoute()}}">
                        <span class="timer"><span id="demos{{$deal->id}}" data-countdown="{{$deal->formatedEndingDate()}}"></span></span>
                        <div class="offers">
                            <h3>{{$deal->discount}} % {{__('Off')}}</h3>
                            <h4>{{\Illuminate\Support\Str::limit(isset($deal->product->product_translates)? $deal->product->product_translates->title:$deal->product->title, 50) }}</h4>
                            <h5>{{currency_format($deal->product->promo_price, $currency)}}</h5>
                        </div>
                        <img loading="lazy" src="{{\App\Helpers\UtilityHelpers::getCdnUrl('/storage/original/'. $deal->image)}}" alt="Deals">
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-md-12 mt-3 mb-3 mt-lg-5 mb-lg-5">
        <nav aria-label="Page navigation example">
            <div class="pagination justify-content-center">
                {!! $deals->appends(request()->query())->links() !!}
            </div>
        </nav>
    </div>
</div>
<!--=================
Products Area
  ==================-->

@endsection
