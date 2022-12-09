@extends('seller.layouts.app')

@section('content')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('seller.flash.deals') }}">{{ __($section_title) }}</a></li>
    <li class="breadcrumb-item active">{{ __($title) }}</li>
  </ol>
  <div class="container-fluid">
    <div class="animated fadeIn">
      <div class="row">
        <div class="col-lg-6">
          <div class="card">
            <div class="card-header">
              {{ __($title) }}
              <x-admin.back-button :url="route('seller.flash.deals')" />
            </div>
            <div class="card-body">
              <!-- alerts -->
              <x-alert class="alert-success" :status="session('success')" />
              <x-alert class="alert-danger" :status="session('error')" />
              <form action="{{ route('seller.flash.deals.add') }}" method="POST" enctype="multipart/form-data" id="add_form">
                @csrf
                <div class="row">
                  <div class="col-sm-8">
                    @php
                      $product = old('product');
                    @endphp
                    <div class="form-group">
                        <label for="product">{{ __('Products') }}</label>
                      <select name="product" id="product" class="custom-select select2" >
                        <option value="">--{{ __('select') }}--</option>
                        @foreach ($products as $r)
                        <option value="{{ $r->id }}" @if ($product == $r->id) selected @endif>{{ $r->product_translates ? $r->product_translates->title : $r->title }}</option>
                        @endforeach
                      </select>
                      @error('product')
                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                      @enderror
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-group">
                      <label for="discount">{{ __('Discount') }} %</label>
                      <input type="text" name="discount" id="discount" class="form-control number" value="{{ old('discount') }}" >
                      @error('discount')
                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                      @enderror
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="start_date">{{ __('Start Date') }}</label>
                      <input type="text" name="start_date" id="start_date" class="form-control loaddatepicker" value="{{ old('start_date') }}" autocomplete="off"  onkeypress="return false;">
                      @error('start_date')
                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                      @enderror
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="start_time">{{ __('Start Time') }}</label>
                      <input id="start_time" type="text" class="form-control loadtimpicker" name="start_time" autocomplete="off"  onkeypress="return false;">
                      @error('start_time')
                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                      @enderror
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="end_date">{{ __('End Date') }}</label>
                      <input type="text" name="end_date" id="end_date" class="form-control loaddatepicker" value="{{ old('end_date') }}" autocomplete="off"  onkeypress="return false;">
                      @error('end_date')
                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                      @enderror
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="end_time">{{ __('End Time') }}</label>
                      <input id="end_time" type="text" class="form-control loadtimpicker" name="end_time" autocomplete="off"  onkeypress="return false;">
                      @error('end_time')
                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                      @enderror
                    </div>
                  </div>
                </div>
                <!-- file upload -->
                <x-admin.file-upload :name="'image'" />
                <!-- submit button -->
                <div class="text-center">
                  <x-admin.publish-button />
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @php
    $media_size = collect(config('media.sizes.deal'))->first();
  @endphp
  @include('admin.includes.crop-modal', ['width' => $media_size['width'], 'height' => $media_size['height'], 'ratio' => $media_size['ratio']])
@endsection

@push('header')
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.min.css" />
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet">
  <link rel="stylesheet" href="//select2.github.io/select2-bootstrap-theme/css/select2-bootstrap.css" rel="stylesheet">
@endpush

@push('footer')
  <x-validation />
  <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
  <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js"></script>
  <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
  <script type="text/javascript" charset="utf-8">
    $.fn.select2.defaults.set( "theme", "bootstrap" );
    $(document).ready(function(){
      $("#add_form");
      $(".select2").select2({
        width: '100%'
      });
      $('.loaddatepicker').datepicker({
        format: "yyyy-mm-dd",
        startDate: new Date(),
        autoclose: true,
        todayHighlight: true,
        orientation: "bottom auto"
      });
      $('.loadtimpicker').timepicker({
        minuteStep: 1,
        icons: {
          up: 'fa fa-arrow-up',
          down: 'fa fa-arrow-down'
        }
      });
    });
  </script>
@endpush
