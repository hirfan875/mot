@extends('web.layouts.app')
@section('content')
<!--=================
  Start breadcrumb
==================-->
<div class="breadcrumb-container">
    <h1>{{__('order-success.breadcrumb_title')}}</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('site.home')}}</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{__('order-success.breadcrumb_title')}}</li>
    </ol>
</div>
<!--=================
  End breadcrumb
==================-->

<div class="container">
    <!--=================
        Start Thank You
        ==================-->
    <div class="thank-you mt-minus bg-white">
        <div class="row text-center p-5">
            <div class="col-md-12">
                <img src="{{ asset('assets/frontend') }}/assets/img/smile.png" alt="smile">
                <h1 class="mt-4 mb-3">{{__('order-success.thanks_for_shopping')}}</h1>
                @auth('customer')
                <p>
                    <span><a class="vieworder" href="{{route('order-history')}}">{{__('View Order')}}</a></span><br>
                </p>
                @endauth
                <a href="{{url('/')}}" class="text-secondary continue_shopping mt-1 btn"><i class="fa fa-chevron-left"></i> {{__('order-success.continue_shopping')}}</a>
            </div>
        </div>
    </div>
    <!--=================
        End Thank You
    ==================-->
</div>
@endsection
