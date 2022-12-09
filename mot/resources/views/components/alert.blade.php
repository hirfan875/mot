@props(['status'])

@if ($status)
  <div {{ $attributes->merge(['class' => 'alert']) }}>
    {{ __($status) }}
  </div>
@endif