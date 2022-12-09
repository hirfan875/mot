@extends('admin.layouts.app')

@section('content')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.languages') }}">{{ $section_title }}</a></li>
    <li class="breadcrumb-item active">{{ __($title) }}</li>
  </ol>
  <div class="container-fluid">
    <div class="animated fadeIn">
      <div class="row">
        <div class="col-lg-6">
          <div class="card">
            <div class="card-header">
              {{ __($title) }}
              <x-admin.back-button :url="route('admin.languages')" />
            </div>
            <div class="card-body">
              <!-- alerts -->
              <x-alert class="alert-success" :status="session('success')" />
              <x-alert class="alert-danger" :status="session('error')" />
              <form action="{{ route('admin.languages.add') }}" method="POST" enctype="multipart/form-data" id="add_form">
                @csrf
                <div class="form-group">
                  <label for="title">{{ __('Title') }}</label>
                  <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')" autofocus>
                  @error('title')
                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="title">{{ __('Native') }}</label>
                  <input type="text" name="native" id="native" class="form-control" value="{{ old('native') }}" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')" autofocus>
                  @error('native')
                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="code">{{ __('Code') }}</label>
                  <input type="text" name="code" id="code" class="form-control" value="{{ old('code') }}" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')">
                  @error('code')
                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="code">{{ __('Direction') }}</label>
                  <select name="direction" id="direction" class="form-control " required>
                      <option value="{{ old('direction','ltr') }}">{{ __('Left-to-right') }}</option>
                      <option value="{{ old('direction','rtl') }}">{{ __('Right-to-left') }}</option>
                  </select>
                  @error('direction')
                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="emoji">{{ __('Emoji') }}</label>
                  <input type="text" name="emoji" id="emoji" class="form-control" value="{{ old('emoji') }}">
                </div>
                <div class="form-group">
                  <label for="emoji_uc">{{ __('Emoji Unicode') }}</label>
                  <input type="text" name="emoji_uc" id="emoji_uc" class="form-control" value="{{ old('emoji_uc') }}">
                </div>
                <!-- file upload -->
                {{-- <x-admin.file-upload :label="__('Icon')" :name="'image'" /> --}}
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

@push('footer')
  <x-validation />
@endpush
