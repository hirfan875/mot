@extends('admin.layouts.app')

@section('content')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.categories') }}">{{ $section_title }}</a></li>
    <li class="breadcrumb-item active">{{ $title }}</li>
  </ol>
  <div class="container-fluid">
    <div class="animated fadeIn">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              {{ $title }}
              <x-admin.back-button :url="route('admin.categories')" />
            </div>
            <div class="card-body">
              <div class="dd">
                <ul class="dd_list">
                  @foreach ($categories as $row)
                  <li data-id="{{ $row->id }}">
                      @if($row->status == 1)
                        <div class="item">
                            <span>{{ $row->category_translates ? $row->category_translates->title : $row->title}}</span>
                        </div>
                      @endif
                      @include('admin.includes.subcategories-sorting', ['subcategories' => $row->subcategories])
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
    var sorting_post_url = "{{ route('admin.categories.sorting.update') }}";
  </script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="{{ asset('assets/backend') }}/js/sorting.js"></script>
@endpush
