<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>{{ __('Login') }} | {{ __(config('app.name')) }}</title>
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
                @if (session('status')) <div class="alert alert-success">{{ __(session('status')) }}</div> @endif
                <h1>{{ __('Login') }}</h1>
                <p class="text-muted">{{ __('Sign in to your account') }}</p>
                <form action="{{ route('seller.login') }}" method="POST" id="login_form">
                  @csrf
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="icon-user"></i>
                      </span>
                    </div>
                    <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="{{ __('Email') }}" required autocomplete="email" autofocus>
                    @error('email')
                      <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                    @enderror
                  </div>
                  <div class="input-group mb-2">
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
                  <div class="form-check mb-3 text-muted">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">{{ __('Remember me') }}</label>
                  </div>
                  <div class="row">
                    <div class="col-6">
                      <button class="btn btn-primary px-4" type="submit">{{ __('Login') }}</button>
                    </div>
                    <div class="col-6 text-right">
                      <a href="{{ route('seller.password.request') }}" class="btn btn-link px-0">{{ __('Reset your password?') }}</a>
                    </div>
                  </div>
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
      $("#login_form").validate();
    </script>
  </body>
</html>