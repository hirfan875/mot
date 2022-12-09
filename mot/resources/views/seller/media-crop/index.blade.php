@extends('seller.layouts.app')

@section('content')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item active">{{ __($title) }}</li>
  </ol>
  <div class="container-fluid">
    <div class="animated fadeIn">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              {{ __($title) }}
            </div>
            <div class="card-body">
              <!-- alerts -->
              <x-alert class="alert-success" :status="session('success')" />
              <x-alert class="alert-danger" :status="session('error')" />
              @if ($errors->any())
                <div class="alert alert-danger">
                  <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ __($error) }}</li>
                    @endforeach
                  </ul>
                </div>
              @endif
              @if (count($all_sizes) > 1)
              <form action="{{ route('seller.media.crop') }}" method="get">
                <input type="hidden" name="type" value="{{ $request['type'] }}">
                <input type="hidden" name="foreign_id" value="{{ isset($request['foreign_id']) ? $request['foreign_id'] : '' }}">
                <input type="hidden" name="image_id" value="{{ $request['image_id'] }}">
                <div class="form-group row">
                  <label for="size" class="col-sm-2 col-form-label col-form-label-sm">Select Size:</label>
                  <div class="col-sm-4">
                    <select name="size" id="size" class="custom-select" onchange="this.form.submit()">
                      @foreach ($all_sizes as $row_size)
                      <option value="{{ $row_size['slug'] }}" @if ($selected_size === $row_size['slug']) selected @endif>{{ $row_size['title'] }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </form>
              @endif
              <form action="{{ route('seller.media.crop') }}" method="POST" enctype="multipart/form-data" id="crop_image_form">
                @csrf
                <input type="hidden" name="type" value="{{ $request['type'] }}">
                <input type="hidden" name="foreign_id" value="{{ isset($request['foreign_id']) ? $request['foreign_id'] : '' }}">
                <input type="hidden" name="image_id" value="{{ $request['image_id'] }}">
                <input type="hidden" name="size" value="{{ $selected_size }}">
                <input type="hidden" name="image_name" value="{{ $imageData->image }}">
                <input type="hidden" name="new_image" id="new_image" value="">
                <div class="row">
                  <div class="col-sm-6">
                    <div class="img-container mb-3">
                      <img id="image" src="{{ asset($imageData->getMedia('image')) }}" alt="">
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="preview" style="width: {{ $filter_size['width'] }}px; height: {{ $filter_size['height'] }}px"></div>
                  </div>
                </div>
                <!-- submit button -->
                <button type="button" class="btn btn-success" onclick="saveCropImage()">{{ __('Crop & Save') }}</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('header')
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.7/cropper.min.css" type="text/css" />
  <style>
    img { max-width: 100%;}
    .preview { overflow: hidden;}
  </style>
@endpush

@push('footer')
  <x-validation />
  <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.7/cropper.min.js"></script>
  <script type="text/javascript" charset="utf-8">
    var cropper;
    let width = {{ $filter_size['width'] }};
    let height = {{ $filter_size['height'] }};
    let ratio = {{ $filter_size['ratio'] }};
    window.addEventListener('DOMContentLoaded', function () {
      cropper = new Cropper(document.getElementById('image'), {
        aspectRatio: ratio,
        preview: '.preview'
      });
    });

    function saveCropImage() {
      let croppedCanvas = cropper.getCroppedCanvas({
        width: width,
        height: height
      });
      $('#new_image').val(croppedCanvas.toDataURL());
      $('#crop_image_form').submit();
    }
    $(document).ready(function(){
      $("#crop_image_form").validate();
    });
  </script>
@endpush