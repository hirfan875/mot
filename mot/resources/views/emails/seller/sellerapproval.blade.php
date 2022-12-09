@component('mail::message')
<h1 class="main_title">{{__('Approved Successfully!')}}</h1>
<h1 style="text-align: center; padding:0px 35px"> Welcome!</h1>
<p><strong>Seller:</strong> {!! $store->staff[0]->email !!}</p>
<p style="text-align:left !important;">You have successfully registered as a Seller <strong>"{!! $store->name !!}"</strong> of <span style="color:#E72128 !important;">MallofTurkeya.com</span></p>
<p style="text-align:left !important;">Your account has been setup and it is ready to be configured.</p>
<p style="text-align:left !important;">Kindly follow the link below to visit your dashboard and start selling.</p>
<div class="primary_botton">
    <a href="{{url('seller')}}">Dashboard : Visit Now </a>
</div>
<h3>Customer Support : +965 99732998 | +90 5355103999 </h3>

<p>
{{__('Thanks')}},<br>
{{ __(config('app.name')) }}</p>
@endcomponent
