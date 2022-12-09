@component('mail::message', ['headerImageName' => 'rejected.png'])
<h2 class="secondry_title">{!! __('Dear') !!} {!! $store->staff[0]->name !!},</h2>
<p> {!! __('Your store') !!} "{!! $store->name !!}" {!! __(' has been rejected') !!}</p>
<p>{{__('Thanks')}},<br>
{{ __(config('app.name')) }}</p>
@endcomponent
