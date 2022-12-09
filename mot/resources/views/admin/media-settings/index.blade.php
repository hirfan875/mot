@extends('admin.layouts.app')

@section('content')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item active">{{ __($title) }}</li>
  </ol>
  <div class="container-fluid">
    <div class="animated fadeIn">
      <div class="row">
        <div class="col-lg-6">
          <div class="card">
            <div class="card-header">
              {{ __($title) }}
            </div>
            <div class="card-body">
              <!-- alerts -->
              <x-alert class="alert-success" :status="session('success')" />
              <x-alert class="alert-danger" :status="session('error')" />
              <form action="{{ route('admin.media.settings') }}" method="POST" enctype="multipart/form-data" id="settings_form">
                @csrf
                <h4>{{ __('Thumbnail size') }}</h4>
                <div class="form-group row">
                  <label class="col-md-3 col-form-label" for="thumbnail_size_width">{{ __('Width') }}</label>
                  <div class="col-md-2">
                    <input type="text" name="thumbnail_size_width" id="thumbnail_size_width" class="form-control number" value="{{ get_option('thumbnail_size_width') ?: '150' }}" required>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 col-form-label" for="thumbnail_size_height">{{ __('Height') }}</label>
                  <div class="col-md-2">
                    <input type="text" name="thumbnail_size_height" id="thumbnail_size_height" class="form-control number" value="{{ get_option('thumbnail_size_height') ?: '150' }}" required>
                  </div>
                </div>
                <h4>{{ __('Medium size') }}</h4>
                <div class="form-group row">
                  <label class="col-md-3 col-form-label" for="medium_size_width">{{ __('Width') }}</label>
                  <div class="col-md-2">
                    <input type="text" name="medium_size_width" id="medium_size_width" class="form-control number" value="{{ get_option('medium_size_width') ?: '350' }}" required>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 col-form-label" for="medium_size_height">{{ __('Height') }}</label>
                  <div class="col-md-2">
                    <input type="text" name="medium_size_height" id="medium_size_height" class="form-control number" value="{{ get_option('medium_size_height') ?: '350' }}" required>
                  </div>
                </div>
                <h4>{{ __('Large size') }}</h4>
                <div class="form-group row">
                  <label class="col-md-3 col-form-label" for="large_size_width">{{ __('Width') }}</label>
                  <div class="col-md-2">
                    <input type="text" name="large_size_width" id="large_size_width" class="form-control number" value="{{ get_option('large_size_width') ?: '700' }}" required>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 col-form-label" for="large_size_height">{{ __('Height') }}</label>
                  <div class="col-md-2">
                    <input type="text" name="large_size_height" id="large_size_height" class="form-control number" value="{{ get_option('large_size_height') ?: '700' }}" required>
                  </div>
                </div>
                @php
                  $media_placeholder = get_option('media_placeholder');
                  $media_placeholder = ( $media_placeholder != '' ? 'storage/original/'.$media_placeholder : null );
                @endphp
                <div class="form-group row">
                  <label class="col-md-3 col-form-label">{{ __('Placeholder') }}</label>
                  <div class="col-md-9">
                    <!-- file upload -->
                    <x-admin.file-upload :name="'media_placeholder'" :file="$media_placeholder" :thumbnail="$media_placeholder" :label="''" />
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-9 offset-md-3">
                    <!-- submit button -->
                    <x-admin.save-changes-button />
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('footer')
  <x-validation />
  <script type="text/javascript" charset="utf-8">
    $(document).ready(function(){
      $("#settings_form").validate();
    });
  </script>
@endpush