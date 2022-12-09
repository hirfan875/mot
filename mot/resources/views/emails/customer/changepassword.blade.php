@component('mail::message')
<h2 class="secondry_title">{{__('Dear')}}# {{ $customer->name }}</h2>
<p>{!! $customer->name !!}</p>
<p>{!! $customer->email !!}</p>

<p>{{__('Thanks')}},<br>
{{ __(config('app.name')) }}</p>
@endcomponent
