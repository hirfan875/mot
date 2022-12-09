@component('mail::message', ['headerImageName' => 'forgot_password.png'])
<h2 class="main_title">{{__('Forgot Password')}}</h2>
<h2 class="secondry_title">{{__('Hello!')}}</h2>
<p style="text-align: left !important;">{!! __("If you've lost your password or wish to reset it, use the link below to get started") !!}</p>
<div class="link"><a href="{{$url}}">{{__('Reset Password')}}</a></div>
<p style="text-align: left !important;">This password reset link will expire in {{config('auth.passwords.customer.expire')}} minutes.</p>
<p style="text-align: left !important;">{{__('If you did not request a password reset, you can safely ignore this email. Only a person with access to your email can reset your account password')}}</p>
@endcomponent
