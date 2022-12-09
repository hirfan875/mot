@extends('admin.layouts.app')

@section('content')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.pages') }}">{{ $section_title }}</a></li>
    <li class="breadcrumb-item active">{{ $title }}</li>
  </ol>
  <div class="container-fluid">
    <div class="animated fadeIn">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              {{ $title }}
              <x-admin.back-button :url="route('admin.pages')" />
            </div>
            <div class="card-body">
              <!-- alerts -->
              <x-alert class="alert-success" :status="session('success')" />
              <x-alert class="alert-danger" :status="session('error')" />
              <form action="{{ route('admin.pages.add') }}" method="POST" id="add_form">
                @csrf
                @foreach(getLocaleList() as $val)
                <div class="form-group">
                  <label for="title">{{ __('Title ('.$val->title.')') }}</label>
                  <input type="text" name="title[{{$val->id}}]" id="title{{$val->id}}" class="form-control" value="{{ old('title'.$val->id) }}" {{$val->is_default == 'Yes' ? 'required':'' }} oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')" autofocus>
                  @error('title[{{$val->id}}]')
                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                  @enderror
                </div>
                @endforeach
                @foreach(getLocaleList() as $val)
                <div class="form-group">
                  <label for="data">{{ __('Description ('.$val->title.')') }}</label>
                  <textarea name="data[{{$val->id}}]" id="data{{$val->id}}" cols="30" rows="5" class="form-control TinyEditor">{{ old('data'.$val->id) }}</textarea>
                </div>
                @endforeach
                <!-- seo fields -->
                <x-seo-page-form-fields />
                <div class="text-center">
                  <!-- submit button -->
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
  <x-tinymce />
@endpush
