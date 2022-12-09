<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>{{ __('Forgot Password') }} | {{ __(config('app.name')) }}</title>
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
      <div class="text-center mb-3"><img src="{{ asset('assets/backend') }}/img/logo.svg" width="200" alt="{{__('logo')}}"></div>
      <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="card-group">
            <div class="card p-4">
              <div class="card-body">
                @if (session('status')) <div class="alert alert-success">{{ __(session('status')) }}</div> @endif
                <h1>{{ __('Forgot Password') }}</h1>
                <p>{{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}</p>
                <form action="{{ route('seller.password.email') }}" method="POST" id="forgot_form">
                  @csrf
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="icon-user"></i>
                      </span>
                    </div>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="{{ __('Email') }}" required autocomplete="email" autofocus>
                    @error('email')
                      <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                    @enderror
                  </div>
                  <button class="btn btn-primary btn-block" type="submit">{{ __('Email Password Reset Link') }}</button>
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
      $("#forgot_form").validate();
    </script>
  </body>
</html>