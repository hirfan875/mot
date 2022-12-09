@props(['url', 'records'])
@if ( count($records) > 0 )
<a class="btn btn-dark btn-sm pull-right ml-2" href="{{ $url }}"><i class="fa fa-sort"></i> {{ __('Sorting') }}</a>
@endif