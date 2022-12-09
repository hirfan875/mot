@extends('admin.layouts.app')

@section('content')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.sliders') }}">{{ __($section_title) }}</a></li>
    <li class="breadcrumb-item active">{{ __($title) }}</li>
  </ol>
  <div class="container-fluid">
    <div class="animated fadeIn">
      <div class="row">
        <div class="col-lg-6">
          <div class="card">
            <div class="card-header">
              {{ __($title) }}
              <x-admin.back-button :url="route('admin.sliders')" />
            </div>
            <div class="card-body">
              <div class="dd">
                <ul class="dd_list">
                  @foreach ($sliders as $key=>$row)
                  <li data-id="{{ $row->id }}">
                    <div class="item">
                      <span>{{__('Slide')}} {{ $key+1 }}</span>
                    </div>
                  </li>
                  @endforeach
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('footer')
  <script type="text/javascript">
    var sorting_post_url = "{{ route('admin.sliders.sorting.update') }}";
  </script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="{{ asset('assets/backend') }}/js/sorting.js"></script>
@endpush