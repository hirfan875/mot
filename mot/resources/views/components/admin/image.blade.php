@props(['file', 'thumbnail'])
@if ( $file )
<img src="{{ asset($thumbnail) }}" alt="thumbnail" width="60">
@endif