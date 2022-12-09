@extends('admin.layouts.app')

@section('content')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.stores') }}">{{ __($store->name) }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.stores.staff', ['store' => $store->id]) }}">{{ __($section_title) }}</a></li>
    <li class="breadcrumb-item active">{{ __($title) }}</li>
  </ol>
  <div class="container-fluid">
    <div class="animated fadeIn">
      <div class="row">
        <div class="col-lg-6">
          <div class="card">
            <div class="card-header">
              {{ __($title) }}
              <x-admin.back-button :url="route('admin.stores.staff', ['store' => $store->id])" />
            </div>
            <div class="card-body">
              <!-- alerts -->
              <x-alert class="alert-success" :status="session('success')" />
              <x-alert class="alert-danger" :status="session('error')" />
              <form action="{{ route('admin.stores.staff.edit', ['store' => $store->id, 'staff' => $row->id]) }}" method="POST" id="edit_form">
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
                  <input type="email" oninvalid="InvalidMsg(this);" name="email" id="email" class="form-control" value="{{ old('email', $row->email) }}" oninput="InvalidMsg(this);" required="required">
                  @error('email')
                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="password">{{ __('Password') }}</label>
                  <input type="password" name="password" id="password" class="form-control" autocomplete="new-password">
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
  <script type="text/javascript" charset="utf-8">
      function InvalidMsg(textbox) {

          if (textbox.value == '') {
              textbox.setCustomValidity('{{__('Please fill out this field')}}');
          }
          else if(textbox.validity.typeMismatch){
              textbox.setCustomValidity('{{__('please enter a valid email address')}}');
          }
          else {
              textbox.setCustomValidity('');
          }
          return true;
      }
  </script>
@endpush
