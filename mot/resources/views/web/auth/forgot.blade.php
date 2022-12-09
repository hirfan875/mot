@extends('web.layouts.app')
@section('content')
<!--=================
  Start breadcrumb
  ==================-->
 <div class="breadcrumb-container">
    <h1>{{__('breadcrumb.forgot_password')}}</h1>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('breadcrumb.home')}}</a></li>
      <li class="breadcrumb-item active" aria-current="page">{{__('breadcrumb.forgot_password')}}</li>
    </ol>
 </div>
<!--=================
  End breadcrumb
  ==================-->
<div class="forgotpass_aa"> 
 <div class="container">
  <ul class="mt-minus customer nav nav-pills nav-pills-container nav-justified" id="pills-tab" role="tablist">
     @auth('customer')
      <li class="nav-item">
      <a class="nav-link active" id="forgotpass-tab" data-toggle="pill" href="#forgotpass" role="tab" aria-controls="forgotpass" aria-selected="true">{{__('breadcrumb.forgot_password')}}</a>
    </li>
<!--    <li class="nav-item">
      <a class="nav-link" id="athentication-tab" data-toggle="pill" href="#athentication" role="tab" aria-controls="athentication" aria-selected="false">{{__('Authentication')}}</a>
    </li>-->
   
    <li class="nav-item">
      <a class="nav-link" id="newpass-tab" data-toggle="pill" href="#newpass" role="tab" aria-controls="newpass" aria-selected="false">{{__('New Password')}}</a>
    </li>
    @endauth
  </ul>

  <div class="tab-content" id="pills-tabContent">
    <div class="tab-pane fade show active" id="forgotpass" role="tabpanel" aria-labelledby="forgotpass-tab">
      <!--=================
      Start Forgot Pass
      ==================-->
      <br/>
      <form class="form-verify m-5"  method="POST" action="{{ route('send-forgot-password-link') }}">
          @csrf
        <div class="row justify-content-center">
          <div class="col-md-5">
              @if (session('status')) <div class="alert alert-success">{{ session('status') }}</div> @endif
            <h2 class="text-center mb-3">{{__('breadcrumb.forgot_password')}}</h2>
           <div class="form-body">
            <p>{{__('Please enter your email address.')}}</p>
            <input type="email" class="form-control mt-3"  placeholder="{{__('Email')}}" name="email" value="{{ old('email') }}" required  >
               @error('email')
               <span class="invalid-feedback" role="alert">
                    <strong>{{__($message)}}</strong>
                </span>
               @enderror
           </div>
            <button type="submit" class="btn btn-primary delivery-here">{{__('CONTINUE')}}</button>
          </div>
        </div>
      </form>
      <!--=================
      End Forgot Pass
      ==================-->
    </div>

<!--    <div class="tab-pane fade" id="athentication" role="tabpanel" aria-labelledby="athentication-tab">
      =================
      Start Athentication
      ==================
      <form class="form-verify m-5">
        <div class="row justify-content-center">
          <div class="col-md-5">
            <h2 class="text-center mb-3">{{__('Authentication required')}}</h2>
           <div class="form-body">
            <p></p>
            <input type="text" class="form-control mt-3"  placeholder="{{__('OTP')}}">
           </div>
            <button type="submit" class="btn btn-primary delivery-here">{{__('CONTINUE')}}</button>
            <button type="submit" class="btn btn-default delivery-here">{{__('RESEND')}}</button>
          </div>
        </div>
      </form>
       =================
      End Athentication
      ==================
    </div>-->
    @auth('customer')
    <div class="tab-pane fade" id="newpass" role="tabpanel" aria-labelledby="newpass-tab">
      <!--=================
      Start Reset Pass
      ==================-->
      <form class="form-verify m-5" action="{{ route('password.store') }}" method="POST">
        <div class="row justify-content-center">
          <div class="col-md-5">

            <h2 class="text-center mb-3">{{__('Reset Password')}}</h2>
           <div class="form-body">
            <input id="email" type="hidden" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', isset($request->email)) }}" readonly>
               @error('email')
                 <span class="invalid-feedback d-block" role="alert"> <strong>{{__($message)}}</strong> </span>
               @enderror
               @csrf
               <input type="hidden" name="token" value=" @if(isset($request->route)) {{$request->route('token')}} @endif">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="{{__('Enter New Password')}}">
               @error('password')
               <span class="invalid-feedback d-block" role="alert"> <strong>{{__($message)}}</strong> </span>
               @enderror
           <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required  placeholder="{{__('Re-Enter New Password')}}">
           </div>
            <button type="submit" class="btn btn-primary delivery-here">{{__('RESET PASSWORD')}}</button>
          </div>
        </div>
      </form>
       <!--=================
      End Reset Pass
      ==================-->
    </div>
    @endauth
  </div>
</div></div>

@endsection
