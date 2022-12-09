<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>{{ __('Reset Password') }} | {{ __(config('app.name')) }}</title>
    <link rel="shortcut icon" href="{{ asset('assets/backend/img') }}/favicon.png" type="image/x-icon">
    <link rel="icon" href="{{ asset('assets/backend/img') }}/favicon.png" type="image/x-icon">
    <!-- Icons-->
    <link href="{{ asset('assets/backend') }}/node_modules/@coreui/icons/css/coreui-icons.min.css" rel="stylesheet">
    <link href="{{ asset('assets/backend') }}/node_modules/flag-icon-css/css/flag-icon.min.css" rel="stylesheet">
    <link href="{{ asset('assets/backend') }}/node_modules/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="{{ asset('assets/backend') }}/node_modules/simple-line-icons/css/simple-line-icons.css" rel="stylesheet">
    <!-- Main styles for this application-->
    <link href="{{ asset('assets/backend') }}/css/style.css" rel="stylesheet">
    <style>
    label.error{display:none !important;}
    </style>
  </head>
  <body class="app flex-row align-items-center">
    <div class="container">
      <div class="text-center mb-3"><img src="{{ asset('assets/backend') }}/img/logo.svg" width="200" alt="logo"></div>
      <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="card-group">
            <div class="card p-4">
              <div class="card-body">
                <h1>{{ __('Reset Password') }}</h1>
                <form action="{{ route('password.update') }}" method="POST" id="reset_form">
                  @csrf
                  <input type="hidden" name="token" value="{{ $request->route('token') }}">
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="icon-user"></i>
                      </span>
                    </div>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $request->email) }}" placeholder="{{ __('Email') }}" required autocomplete="email" autofocus>
                    @error('email')
                      <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                    @enderror
                  </div>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="icon-lock"></i>
                      </span>
                    </div>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="{{ __('Password') }}" required autocomplete="new-password">
                    @error('password')
                      <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                    @enderror
                  </div>
                  <div class="input-group mb-4">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="icon-lock"></i>
                      </span>
                    </div>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="{{ __('Confirm Password') }}" required autocomplete="new-password">
                  </div>
                  <button class="btn btn-primary btn-block" type="submit">{{ __('Reset Password') }}</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- CoreUI and necessary plugins-->
    <script src="{{ asset('assets/backend') }}/node_modules/jquery/dist/jquery.min.js"></script>
    <script src="{{ asset('assets/backend') }}/node_modules/popper.js/dist/umd/popper.min.js"></script>
    <script src="{{ asset('assets/backend') }}/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="{{ asset('assets/backend') }}/node_modules/perfect-scrollbar/dist/perfect-scrollbar.min.js"></script>
    <script src="{{ asset('assets/backend') }}/node_modules/@coreui/coreui/dist/js/coreui.min.js"></script>
    <x-validation />
    <script>
      $("#reset_form").validate({
        rules: {
          password_confirmation: {
            required: true,
            equalTo: "#password"
          }
        }
      });
    </script>
  </body>
</html>