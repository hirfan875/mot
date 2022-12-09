@extends('seller.layouts.app')

@section('content')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('seller.staff') }}">{{ __($section_title) }}</a></li>
    <li class="breadcrumb-item active">{{ __($title) }}</li>
  </ol>
  <div class="container-fluid">
    <div class="animated fadeIn">
      <div class="row">
        <div class="col-lg-6">
          <div class="card">
            <div class="card-header">
              {{ __($title) }}
              <x-admin.back-button :url="route('seller.staff')" />
            </div>
            <div class="card-body">
              <!-- alerts -->
              <x-alert class="alert-success" :status="session('success')" />
              <x-alert class="alert-danger" :status="session('error')" />
              <form action="{{ route('seller.staff.edit', ['staff' => $row->id]) }}" method="POST" id="edit_form">
                @csrf
                <div class="form-group">
                  <label for="name">{{ __('Name') }}</label>
                  <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $row->name) }}" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')">
                  @error('name')
                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="phone">{{ __('Phone') }}</label>
                  <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $row->phone) }}" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')">
                  @error('phone')
                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="email">{{ __('Email') }}</label>
                  <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $row->email) }}" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')">
                  @error('email')
                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="password">{{ __('Password') }}</label>
                  <input type="password" name="password" id="password" minlength="6" maxlength="20" class="form-control" autocomplete="new-password">
                  @error('password')
                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                  @enderror
                </div>
                <!-- submit button -->
                <div class="text-center">
                  <x-admin.save-changes-button />
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
  <x-validation-password />
@endpush
