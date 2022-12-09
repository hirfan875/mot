@extends('web.layouts.app')
@section('content')
<!--=================
  Start breadcrumb
  ==================-->
<div class="breadcrumb-container">
    <h1>{{__('breadcrumb.contact_us')}}</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('breadcrumb.home')}}</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{__('breadcrumb.contact_us')}}</li>
    </ol>
</div>
<!--=================
  End breadcrumb
  ==================-->

<div class="container">
    <!--=================
  Start About Us
  ==================-->
    <div class="contact_us bg-white p-5 mt-minus">
        <div class="main_content">

            <!-- START SECTION CONTACT -->
            <div class="section pb_70">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-4 col-md-6">
                            <div class="contact_wrap contact_style3">
                                <div class="contact_icon">
                                    <i class="icon-map"></i>
                                </div>
                                <div class="contact_text">
                                    <span>{{__('Address')}}</span>
                                    <p>{{get_option('address')}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6">
                            <div class="contact_wrap contact_style3">
                                <div class="contact_icon">
                                    <i class="icon-envelope-open"></i>
                                </div>
                                <div class="contact_text">
                                    <span>{{__('Email Address')}}</span>
                                    <a href="mailto:{{get_option('targetemail')}}">{{get_option('targetemail')}}</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6">
                            <div class="contact_wrap contact_style3">
                                <div class="contact_icon">
                                    <i class="icon-call-out"></i>
                                </div>
                                <div class="contact_text">
                                    <span>{{__('Phone')}}</span>

                                    @if (get_option('contact_no') != "")
                                        @foreach(explode(',', get_option('contact_no')) as $contact_no)
                                            <a href="tel:{{$contact_no}}">{{$contact_no}}</a>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END SECTION CONTACT -->
            <!-- START SECTION CONTACT -->
            <div class="section pt-0">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="heading_s1 mb-3">
                                <h2>{{__('Get In touch')}}</h2>
                            </div>
                           <div class="field_form">
                                <form method="POST" action="{{ route('add-contact-us') }}" name="enq">
                                    @csrf
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')" placeholder="{{__('Enter Name')}} *" id="first-name" class="form-control" name="name" autofocus>
                                            @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{__($message)}}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <input id="email"  class="form-control @error('email') is-invalid @enderror" placeholder="{{__('Enter Email')}} *" oninvalid="InvalidMsg(this);" name="email" oninput="InvalidMsg(this);"  type="email" required autofocus>
                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{__($message)}}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <input type="phone" class="form-control @error('phone') is-invalid @enderror" minlength="11" maxlength="15" placeholder="{{__('Enter Phone No.')}} *" id="phone" class="form-control" name="phone" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')">
                                            @error('phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{__($message)}}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <input class="form-control @error('subject') is-invalid @enderror" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')" placeholder="{{__('Enter Subject')}}" id="subject" class="form-control" name="subject">
                                            @error('subject')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{__($message)}}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-12">
                                            <textarea class="textareaF form-control @error('message') is-invalid @enderror" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')" placeholder="{{__('Message')}} *" id="description" class="form-control" name="message" rows="4"></textarea>
                                            @error('message')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{__($message)}}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-12">
                                            <div class="g-recaptcha" id="rcaptcha" data-sitekey="6LeAmd4fAAAAAK-va6ixlJLpvpy0JX1uhUOcAdez"></div>
                                            <span id="captcha" style="margin-left:100px;color:red" />
                                        </div>
                                        <div class="col-md-12 text-right">
                                            <button type="submit" title="{{__('Submit Your Message!')}}" class="btn btn-fill-out" id="submitButton" name="submit" value="Submit" onclick="return get_action(this)">{{__('Send Message')}}</button>
                                        </div>
                                        <div class="col-md-12">
                                            <div id="alert-msg" class="alert-msg text-center"></div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- END SECTION CONTACT -->


        </div>
    </div>
    <!--=================
  End About Us
  ==================-->
</div>

@endsection
@section('scripts')
<script type="text/javascript">
    function InvalidMsg(textbox) {

        if (textbox.value == '') {
            textbox.setCustomValidity('{{__('Required email address')}}');
        } else if (textbox.validity.typeMismatch) {
            textbox.setCustomValidity('{{__('please enter a valid email address')}}');
        } else {
            textbox.setCustomValidity('');
        }
        return true;
    }
</script>
@endsection
