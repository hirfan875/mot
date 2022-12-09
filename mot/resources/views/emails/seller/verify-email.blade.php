@component('mail::message')
<h2 class="main_title">{{__('Verify your email address')}}</h2>
<p style="text-align: left !important;">{!! __("Please click the link below to verify your email address") !!}</p>
<div class="link"><a href="{{$url}}">{{__('Verify Your Email')}}</a></div>
<p style="text-align: left !important;">{{__('If you did not create an account, no further action is required.')}}</p>
@endcomponent
