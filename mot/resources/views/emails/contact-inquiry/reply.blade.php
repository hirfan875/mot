@component('mail::message')

{!! $message !!}

{{__('Thanks')}},<br>
{{ __(config('app.name')) }}
@endcomponent
