@extends('web.layouts.app')
@section('content')
<!--=================
  Start breadcrumb
  ==================-->
<div class="breadcrumb-container">
    <h1>{{__('breadcrumb.login_register')}}</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('breadcrumb.home')}}</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{__('breadcrumb.login_register')}}</li>
    </ol>
</div>
<!--=================
  End breadcrumb
  ==================-->
<div class="container">
    <div class="login-reg-cont bg-white mt-minus mb-5">
        <div class="row">
            <div class="col-md-6">
                <!-- Start Login -->
                <form class="form-verify or" id="customer-login" method="POST" action="{{ route('customer-login') }}">
                    @csrf
                    <h2 class="text-center mb-3">{{__('Login')}}</h2>
                    <div class="form-body">
                        <input type="email" oninvalid="InvalidMsg(this);" class="form-control @error('email') is-invalid @enderror" placeholder="{{__('Email')}}" name="email" value="{{ old('email') }}" autocomplete="email" oninput="InvalidMsg(this);" required="required">
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{__($message)}}</strong>
                        </span>
                        @enderror
                        <input type="password" class="form-control mt-3 @error('password') is-invalid @enderror" placeholder="{{__('Password')}}" name="password" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')" >
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{__($message)}}</strong>
                        </span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary delivery-here">{{__('LOGIN')}}</button>
                    <a href="{{ route('customer-forgot-password') }}" class="btn forgotpass ">{{__('Forgot Password')}}</a>
                    <a href="{{route('cart')}}" class="btn btn-dark-border mt-3">{{__('Checkout as Guest')}}</a>
                </form>
                <!-- End Login -->
            </div>
            <div class="col-md-6">
                <!-- Start Register -->
                <form class="form-verify" method="POST" action="{{ route('customer-register') }}">
                    @csrf
                    <h2 class="text-center mb-3">{{__('Register')}}</h2>
                    <div class="form-body">
                        <input type="text" class="form-control @error('register_name') is-invalid @enderror"  name="register_name" value="{{ old('register_name') }}" autocomplete="name" placeholder="{{__('Full Name')}}" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')">
                        @error('register_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ __($message) }}</strong>
                        </span>
                        @enderror
                        <input type="email" oninvalid="InvalidMsg(this);" class="form-control mt-3 @error('register_email') is-invalid @enderror" name="register_email" value="{{ old('register_email') }}" autocomplete="email" placeholder="{{__('Email')}}" oninput="InvalidMsg(this);" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" required="required">
                        @error('register_email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{__($message)}}</strong>
                        </span>
                        @enderror
                        <input type="password" class="form-control mt-3 @error('register_password') is-invalid @enderror" name="register_password" placeholder="{{__('Password')}}" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')">
                        @error('register_password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{__($message)}}</strong>
                        </span>
                        @enderror
                        <input type="password" class="form-control mt-3" name="register_password_confirmation" placeholder="{{__('Re-Enter Password')}}" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')">
                        <div class="custom-control custom-checkbox mt-4">
                            <input type="checkbox" class="custom-control-input" id="customCheck" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')">
                            <label class="custom-control-label" for="customCheck"><a href="/terms-conditions" target="_blank">{{__('You agree to MOT')}}</a></label>
                        </div>
                        <div class="form-group col-md-12">
                            <br/>
                            <div class="g-recaptcha" id="rcaptcha" data-sitekey="6LeAmd4fAAAAAK-va6ixlJLpvpy0JX1uhUOcAdez"></div>
                            <span id="captcha" style="margin-left:100px;color:red" />
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary delivery-here"  onclick="return get_action(this)">{{__('REGISTER')}}</button>
                </form>
                <!-- End Register -->
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script type="text/javascript" charset="utf-8">
    function InvalidMsg(textbox) {

    if (textbox.value == '') {
    textbox.setCustomValidity('{{__('Please fill out this field')}}');
    }
    else if (textbox.validity.typeMismatch){
    textbox.setCustomValidity('{{__('please enter a valid email address')}}');
    }
    else {
    textbox.setCustomValidity('');
    }
    return true;
    }
</script>

<script>
//    $.ajaxSetup({
//        headers: {
//            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//        }
//    });
//
//   $('#customer-login').submit(function(e) {
//       e.preventDefault();
//       let formData = new FormData(this);
//       $('#image-input-error').text('');
//
//       $.ajax({
//          type:'POST',
//          url: `{{ route('customer-login') }}`,
//           data: formData,
//           contentType: false,
//           processData: false,
//           success: (response) => {
//               console.log(response);
//             if (response) {
//               this.reset();
//               ShowSuccessModal(response.message);
////               $('#image-input-success').text(response.message);
//               setTimeout(function(){
//               $('#exampleModalCenter22').modal('hide');
//               }, 3000);
//             }
//           },
//           error: function(response){
//              console.log(response);
////              ShowFailureToaster(response.message);
//                ShowSuccessModal(response.responseJSON.errors.file);
//                $('#image-input-error').text(response.responseJSON.errors.file);
//           }
//       });
//  });

</script>
@endsection
