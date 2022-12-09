@extends('seller.layouts.app')

@section('content')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">{{ __('Dashboard') }}</a></li>
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
              <form action="{{ route('seller.profile') }}" method="POST" id="profile_form" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                  <label for="name">{{ __('Name') }}</label>
                  <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $row->name) }}" required />
                  @error('name')
                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="email">{{ __('Email') }}</label>
                  <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $row->email) }}" required />
                  @error('email')
                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="phone">{{ __('Phone') }}</label>
                  <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $row->phone) }}" required />
                  @error('phone')
                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="password">{{ __('Password') }}</label>
                  <input type="password" name="password" id="password" class="form-control" autocomplete="new-password" />
                  @error('password')
                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="password-confirm">{{ __('Confirm Password') }}</label>
                  <input type="password" name="password_confirmation" id="password-confirm" class="form-control" autocomplete="new-password" />
                </div>
                 <!-- file upload -->
                <x-admin.file-upload :name="'image'" :file="$row->image" :thumbnail="$row->getMedia('image', 'thumbnail')" />
                
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
  <script type="text/javascript">
    $(document).ready(function(){
      $("#profile_form").validate({
        rules: {
          password: {
            passwordcheck: true,
            minlength: 6,
            maxlength: 20
          },
          password_confirmation: {
            equalTo: "#password"
          }
        }
      });
    });
  </script>
@endpush
