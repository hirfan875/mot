@props(['url', 'default', 'title'])
@if ( $default != 'Yes' )
<a href="{{ $url }}" class="btn btn-dark btn-sm mb-1" onclick="return window.confirm('{{ __('Are you sure you want to set :title as default?', ['title' => addslashes($title)]) }}');">{{ __('Set as Default') }}</a>
@else
<a href="javascript:;" class="btn btn-success btn-sm mb-1">{{ __('Default') }}</a>
@endif