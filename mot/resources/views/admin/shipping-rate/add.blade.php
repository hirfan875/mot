@extends('admin.layouts.app')

@section('content')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.countries') }}">{{ $country->title }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.shipping.rates', ['country' => $country->id]) }}">{{ $section_title }}</a></li>
    <li class="breadcrumb-item active">{{ $title }}</li>
  </ol>
  <div class="container-fluid">
    <div class="animated fadeIn">
      <div class="row">
        <div class="col-lg-6">
          <div class="card">
            <div class="card-header">
              {{ $title }}
              <x-admin.back-button :url="route('admin.shipping.rates', ['country' => $country->id])" />
            </div>
            <div class="card-body">
              <!-- alerts -->
              <x-alert class="alert-success" :status="session('success')" />
              <x-alert class="alert-danger" :status="session('error')" />
              <form action="{{ route('admin.shipping.rates.add', ['country' => $country->id]) }}" method="POST" id="add_form">
                @csrf
                <div class="form-group">
                  <label for="weight">{{ __('weight') }}</label>
                  <input type="text" name="weight" id="weight" class="form-control" value="{{ old('weight') }}" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')" autofocus>
                  @error('weight')
                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="rate">{{ __('rate') }}</label>
                  <input type="text" name="rate" id="rate" class="form-control" value="{{ old('rate') }}" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')" autofocus>
                  @error('weight')
                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                  @enderror
                </div>
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
