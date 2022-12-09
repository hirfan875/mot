<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>{{ __(config('app.name')) }}</title>
    <link rel="shortcut icon" href="{{ asset('assets/backend/img') }}/favicon.png" type="image/x-icon">
    <link rel="icon" href="{{ asset('assets/backend/img') }}/favicon.png" type="image/x-icon">
    <!-- Icons-->
    <link href="{{ asset('assets/backend') }}/node_modules/@coreui/icons/css/coreui-icons.min.css" rel="stylesheet">
    <link href="{{ asset('assets/backend') }}/node_modules/flag-icon-css/css/flag-icon.min.css" rel="stylesheet">
    <link href="{{ asset('assets/backend') }}/node_modules/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="{{ asset('assets/backend') }}/node_modules/simple-line-icons/css/simple-line-icons.css" rel="stylesheet">
    <!-- Main styles for this application-->
    <link href="{{ asset('assets/backend') }}/css/style.css" rel="stylesheet">
  </head>
  <body class="app flex-row align-items-center">
    <div class="container">
      <div class="text-center mb-3"><img src="{{ asset('assets/backend') }}/img/logo.svg" width="200" alt="logo"></div>
      <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="card-group">
            <div class="card p-4">
              <div class="card-body">
                @if (session('status') == 'verification-link-sent')
                <div class="alert alert-success">
                  {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                </div>
                @endif
                <p>{{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}</p>
                <form class="d-inline" method="POST" action="{{ route('seller.verification.send') }}">
                  @csrf
                  <button type="submit" class="btn btn-primary">{{ __('Resend Verification Email') }}</button>.
                </form>
                <form class="d-inline" method="POST" action="{{ route('seller.logout') }}">
                  @csrf
                  <button type="submit" class="btn btn-danger">{{ __('Logout') }}</button>.
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
  </body>
</html>