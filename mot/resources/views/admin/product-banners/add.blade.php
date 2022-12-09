@extends('admin.layouts.app')

@section('content')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.product.banners') }}">{{ __($section_title) }}</a></li>
    <li class="breadcrumb-item active">{{ __($title) }}</li>
  </ol>
  <div class="container-fluid">
    <div class="animated fadeIn">
      <div class="row">
        <div class="col-lg-8">
          <div class="card">
            <div class="card-header">
              {{ __($title) }}
              <x-admin.back-button :url="route('admin.product.banners')" />
            </div>
            <div class="card-body">
              <!-- alerts -->
              <x-alert class="alert-success" :status="session('success')" />
              <x-alert class="alert-danger" :status="session('error')" />
              <form action="{{ route('admin.product.banners.add') }}" method="POST" enctype="multipart/form-data" id="add_form">
                @csrf
                <!-- file upload -->
                <x-admin.file-upload :name="'banner_1'" :label="__('Banner 1')" />
                <div class="form-group">
                  <label for="banner_1_url">{{ __('Banner 1 URL') }}</label>
                  <input type="url" name="banner_1_url" id="banner_1_url" class="form-control" value="{{ old('banner_1_url') }}" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')">
                  @error('banner_1_url')
                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                  @enderror
                </div>
                <!-- file upload -->
                <x-admin.file-upload :name="'banner_2'" :label="__('Banner 2')" />
                <div class="form-group">
                  <label for="banner_2_url">{{ __('Banner 2 URL') }}</label>
                  <input type="url" name="banner_2_url" id="banner_2_url" class="form-control" value="{{ old('banner_2_url') }}">
                </div>
                @php
                  $selected_categories = old('categories', []);
                @endphp
                <div class="form-group">
                  <label for="categories">{{ __('Choose Categories') }}</label>
                  <select name="categories[]" class="custom-select select2-multiple" multiple required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')" >
                    <option value="">--{{ __('choose') }}--</option>
                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @if (in_array($category->id, $selected_categories)) selected @endif>{{ $category->title }}</option>
                    @if ($category->subcategories)
                    @foreach ($category->subcategories as $subcategory)
                    <option value="{{ $subcategory->id }}" @if (in_array($subcategory->id, $selected_categories)) selected @endif>&nbsp;&nbsp;&nbsp;&nbsp;{{ $subcategory->title }}</option>
                    @endforeach
                    @endif
                    @endforeach
                  </select>
                </div>
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
@endsection

@push('header')
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet">
  <link rel="stylesheet" href="//select2.github.io/select2-bootstrap-theme/css/select2-bootstrap.css" rel="stylesheet">
@endpush

@push('footer')
  <x-validation />
  <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
  <script type="text/javascript" charset="utf-8">
    $(document).ready(function(){
      $("#add_form");
      $(".select2-multiple").select2({
        width: '100%'
      });
    });
  </script>
@endpush
