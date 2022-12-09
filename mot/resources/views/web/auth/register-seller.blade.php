@extends('web.layouts.app')
@section('content')
@section('style')
    <link href="{{ asset('assets/frontend') }}/assets/css/seller.css" rel="stylesheet">
    <link href="{{ asset('assets/common') }}/css/select2.min.css" rel="stylesheet">
    <link href="{{ asset('assets/common') }}/css/select2-bootstrap.css" rel="stylesheet">
@endsection
<!--=================
  Start breadcrumb
==================-->
 <div class="breadcrumb-container">
    <h1>{{__('breadcrumb.sell_with_us')}}</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('breadcrumb.home')}}</a></li>
      	<li class="breadcrumb-item active" aria-current="page">{{__('breadcrumb.sell')}}</li>
    </ol>
 </div>
<!--=================
  End breadcrumb
==================-->
<div class="container">
<!--=================
Start About Us
==================-->
    <div class="bg-white p-2 p-md-5 mt-minus">
        <p class="text-center">{{__('seller-register.form-title')}}</p>
        <div class="seller_profile">
            <form action="{{route('seller-register')}}" method="post" onsubmit="return check_checkbox()">
            	@csrf
                <div class="form-group row">
                    <label  class="col-sm-3 col-form-label">{{__('seller-register.store_name')}} </label>
                    <div class="col-sm-9">
                        <input type="text" name="name" class="form-control {{$errors->has('name') ? 'is-invalid' : null}}" id="name" value="{{old('name')}}">
                        @error('name')
                            <span class="invalid-feedback" role="alert"><strong>{{__($message)}}</strong></span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label  class="col-sm-3 col-form-label">{{__('seller-register.email')}}</label>
                    <div class="col-sm-9">
                        <input type="email" name="email" class="form-control {{$errors->has('email') ? 'is-invalid' : null}}" id="email" placeholder="" value="{{old('email')}}" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" />
                        @error('email')
                            <span class="invalid-feedback" role="alert"><strong>{{__($message)}}</strong></span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label  class="col-sm-3 col-form-label">{{__('seller-register.password')}}</label>
                    <div class="col-sm-9">
                        <input type="password" name="password" class="form-control {{$errors->has('password') ? 'is-invalid' : null}}" id="password">
                        @error('password')
                            <span class="invalid-feedback" role="alert"><strong>{{__($message)}}</strong></span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label  class="col-sm-3 col-form-label">{{__('seller-register.confirm_password')}}</label>
                    <div class="col-sm-9">
                        <input type="password" name="password_confirmation" class="form-control" id="password_confirmation">
                        @error('password_confirmation')
                            <span class="invalid-feedback" role="alert"><strong>{{__($message)}}</strong></span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{__('seller-register.mobile_number')}}</label>
                    <div class="col-sm-9">
                        <input class="form-control {{$errors->has('phone') ? 'is-invalid' : null}}" id="phone" name="phone" minlength="8" maxlength="16" type="text" value="{{old('phone')}}"  oninput="this.value = this.value.replace(/[^- +()xX,0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" />
                        @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{__($message)}}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
<!--                <div class="form-group row">
                    <label  class="col-sm-3 col-form-label">{{__('seller-register.categories')}}</label>
                    <div class="col-sm-9">
                        <select name="categories[]" id="categories" class="form-control select2" multiple>
                            @foreach($categories as $category)
                                <option value="{{$category->id}}">{{isset($category->category_translates)? $category->category_translates->title : $category->title}}</option>
                            @endforeach
                        </select>
                        @error('categories')
                        <span class="invalid-feedback" role="alert"><strong>{{__($message)}}</strong></span>
                        @enderror
                    </div>
                </div>-->
                <div class="form-group row">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-9">
                        <div class="custom-control custom-checkbox mt-4">
                            <input type="checkbox" class="custom-control-input" id="customCheck" required oninvalid="this.setCustomValidity('{{__('Kindly accept our terms and conditions')}}')" name="accept" oninput="this.setCustomValidity('')">
                            <label class="custom-control-label" for="customCheck"><a href="/distance-sales-agreement" target="_blank">{{__('seller-register.i_accept_aggrement')}}</a></label>
                            @error('accept')
                            <span class="invalid-feedback" role="alert"><strong>{{__($message)}}</strong></span>
                            @enderror
                           
                        </div>
                    </div>
                </div>
                <div class="form-group col-sm-9 offset-lg-3">
                    <div class="g-recaptcha" id="rcaptcha" data-sitekey="6LeAmd4fAAAAAK-va6ixlJLpvpy0JX1uhUOcAdez"></div>
                    <span id="captcha" style="margin-left:100px;color:red" />
                </div>
                <div class="form-group row">
                    <div class="col-sm-9 offset-lg-3">
                        <button type="submit" class="btn btn-primary"  onclick="return get_action(this)">{{__('seller-register.sign_up')}}</button>
                    </div>
                </div>
                <div class="form-group row">
                <div class="col-sm-9 offset-lg-3">
                    <a href="{{url('seller/login')}}" class="btn btn-login"><span>{{__('Login!')}} </span>{{__('If Already Registered')}} </a>
                     <!--<a href="" class="btn btn-login mt-3" data-toggle="modal" data-target="#exampleModalCenter">Note:</a>-->
                </div>
               
                </div>
            </form>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{__('Before you Begin')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ul class="seller_verification">
                        <li>{{__('Please Verify Your email first.')}} </li>
                        <li>{{__('Set Up your store profile and add an address to get approved by mallofturkeya.com')}} </li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                </div>
            </div>
        </div>
    </div>

<!--=================
End About Us
==================-->
</div>
@endsection
@section('scripts')
<script src="{{ asset('assets/frontend') }}/assets/common/js/register-seller.js"></script> <!-- will delete this file later -->
<script src="{{ asset('assets/common') }}/js/select2.min.js"></script>
<script>
/*select2 multiple dropdown script*/
    $(".select2").select2({
        placeholder: '{{__('Select Categories')}}',
        width: '100%'
    });

function check_checkbox()
{
    if($('#customCheck').is(':checked')) {
        return true;
    }
    ShowFailureModal("{{trans('Kindly accept our terms and conditions.')}}");
    return false;
}
</script>
@endsection
