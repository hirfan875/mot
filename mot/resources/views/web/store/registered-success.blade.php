@extends('web.layouts.app')
@section('content')
<!--================= 
  Start breadcrumb  
==================-->
<div class="breadcrumb-container">
    <h1>{{__('breadcrumb.thanks')}}</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('breadcrumb.home')}}</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{__('breadcrumb.thanks')}}</li>
    </ol>
 </div>
<!--================= 
  End breadcrumb  
==================-->

<div class="container-fluid">
    <!--================= 
        Start Thank You  
        ==================-->
    <div class="thank-you mt-minus bg-white">
        <div class="row text-center p-2 p-md-5">
            <div class="col-md-12">
                <img src="{{ asset('assets/frontend') }}/assets/img/smile.png" alt="smile"/>
                <h1 class="mt-4 mb-3">{{__('seller-register.registered-success.thanks_for_registration')}}</h1>
                <p>{{__('seller-register.registered-success.awaiting_for_admin_approval_to_your_application')}}</p>
            </div>
        </div>
    </div>
    <!--================= 
        End Thank You  
    ==================-->
</div>
@endsection