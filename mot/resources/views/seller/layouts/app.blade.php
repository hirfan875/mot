<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <title>{{ $title }} | {{ __(config('app.name')) }}</title>
  <link rel="shortcut icon" href="{{ asset('assets/backend/img') }}/favicon-ad.png" type="image/x-icon">
  <link rel="icon" href="{{ asset('assets/backend/img') }}/favicon-ad.png" type="image/x-icon">
  <!-- Icons-->
  <link href="{{ asset('assets/backend') }}/node_modules/@coreui/icons/css/coreui-icons.min.css" rel="stylesheet">
  <link href="{{ asset('assets/backend') }}/node_modules/flag-icon-css/css/flag-icon.min.css" rel="stylesheet">
  <link href="{{ asset('assets/backend') }}/node_modules/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="{{ asset('assets/backend') }}/node_modules/simple-line-icons/css/simple-line-icons.css" rel="stylesheet">
  <!-- Main styles for this application-->
  <link href="{{ asset('assets/backend') }}/css/style.css" rel="stylesheet">
  <style type="text/css">
    .pull-right{ float: right;}
  </style>
  @stack('header')
</head>
<body class="app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show">
<?php $currentCurrency = getCurrency(); ?>
<?php $currentLanguage = getlanglist(app()->getLocale()) ?>
  <header class="app-header navbar">
    <button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" type="button" data-toggle="sidebar-show">
      <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="{{ route('seller.dashboard') }}">
      <img class="navbar-brand-full" src="{{ asset('assets/backend') }}/img/brand/logo.svg" width="75" alt="Logo">
      <img class="navbar-brand-minimized" src="{{ asset('assets/backend') }}/img/brand/logo.svg" width="45" alt="Logo">
    </a>
    <ul class="nav navbar-nav d-md-down-none">
      <li class="ml-2">
        <a href="{{ env('APP_URL') }}" target="_blank" class="btn btn-primary btn-sm">{{ __('View Website') }}</a>
      </li>
    </ul>
    <ul class="nav navbar-nav ml-auto">
    <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                <!-- {{__('Languages')}} --> <img height="15" src="{{ asset('/assets/frontend') }}/assets/flags/{{$currentLanguage->emoji_uc}}.svg">  {{strtoupper($currentLanguage->code)}}
             </a>
        <div class="dropdown-menu dropdown-menu-right">
         @foreach(getLocaleList() as $row)
              <a class="dropdown-item {{ (app()->getLocale() == $row->code) ? 'active_lang' : '' }}" href="{{ url('seller/locale/'.$row->code) }}"> <img height="15" src="{{ asset('/assets/frontend') }}/assets/flags/{{$row->emoji_uc}}.svg">  {{__($row->native)}}</a>
          @endforeach
        </div>
       </li>
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
          <!--<div class="default-profile-pic">{{ substr(auth()->user()->name,0,1) }}</div>-->

          @if ( auth()->user()->image )
          <img class="img-avatar" src="{{ asset('storage/original/'.auth()->user()->image) }}" alt="{{ auth()->user()->name }}">
          @else
          <div class="default-profile-pic">{{ substr(auth()->user()->name,0,1) }}</div>
          @endif
        </a>
        <div class="dropdown-menu dropdown-menu-right">
          <a class="dropdown-item" href="{{ route('seller.profile') }}">
            <i class="fa fa-user"></i> {{__('User Profile')}}
          </a>
          <a class="dropdown-item" href="{{ route('seller.logout') }}">
            <i class="fa fa-lock"></i> {{ __('Logout') }}
          </a>
        </div>
      </li>
    </ul>
  </header>
  <div class="app-body">
    <div class="sidebar">
      <nav class="sidebar-nav">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="{{ route('seller.dashboard') }}">
              <i class="nav-icon icon-speedometer"></i> {{ __('Dashboard') }}
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ (request()->is('seller/products*')) ? 'active' : '' }}" href="{{ route('seller.products') }}">
              <i class="nav-icon fa fa-shopping-bag"></i> {{ __('Products') }}
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ (request()->is('seller/pending-products*')) ? 'active' : '' }}" href="{{ route('seller.pending.products') }}">
            <i class="nav-icon fa fa-shopping-bag"></i> {{ __('Pending Products') }}
            </a>
          </li>
           <li class="nav-item">
                <a class="nav-link {{ (request()->is('seller/promotions/bundled-products*')) ? 'active' : '' }}" href="{{ route('seller.bundled.products') }}">
                  <i class="nav-icon fa fa-shopping-bag"></i> {{ __('Bundled Products') }}
                </a>
              </li>
          <li class="nav-item">
            <a class="nav-link {{ (request()->is('seller/orders*')) ? 'active' : '' }}" href="{{ route('seller.orders') }}">
              <i class="nav-icon icon-note"></i> {{ __('Orders') }}
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ (request()->is('seller/pending-orders*')) ? 'active' : '' }}" href="{{ route('seller.pending.orders') }}">
              <i class="nav-icon icon-note"></i> {{ __('Pending Orders') }}
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ (request()->is('seller/product-reviews*')) ? 'active' : '' }}" href="{{ route('seller.product.reviews') }}">
              <i class="nav-icon fa fa-star"></i> {{ __('Product Reviews') }}
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ (request()->is('seller/return-requests*')) ? 'active' : '' }}" href="{{ route('seller.return.requests') }}">
              <i class="nav-icon icon-note"></i> {{ __('Return Requests') }}
            </a>
          </li>
          {{-- <li class="nav-item">
            <a class="nav-link {{ (request()->is('seller/cancel-requests*')) ? 'active' : '' }}" href="{{ route('seller.cancel.requests') }}">
              <i class="nav-icon icon-note"></i> {{ __('Cancel Requests') }}
            </a>
          </li> --}}
          <li class="nav-item nav-dropdown {{ (request()->is('seller/promotions*')) ? 'open' : '' }}">
            <a class="nav-link nav-dropdown-toggle {{ (request()->is('seller/promotions*')) ? 'active' : '' }}" href="#">
              <i class="nav-icon fa fa-percent"></i> {{ __('Promotions') }}</a>
            <ul class="nav-dropdown-items">
              <!-- <li class="nav-item">
                <a class="nav-link {{ (request()->is('seller/promotions/coupons*')) ? 'active' : '' }}" href="{{ route('seller.coupons') }}">
                  <i class="nav-icon fa fa-percent"></i> {{ __('Coupons') }}
                </a>
              </li> -->
              <li class="nav-item">
                <a class="nav-link {{ (request()->is('seller/promotions/free-delivery*')) ? 'active' : '' }}" href="{{ route('seller.free.delivery') }}">
                  <i class="nav-icon fa fa-truck"></i> {{ __('Free Delivery') }}
                </a>
              </li>
                <li class="nav-item">
                <a class="nav-link {{ (request()->is('seller/flash-deals*')) ? 'active' : '' }}" href="{{ route('seller.flash.deals') }}">
                  <i class="nav-icon fa fa-shopping-bag"></i> {{ __('Flash Deals') }}
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item nav-dropdown {{ (request()->is('seller/report*')) ? 'open' : '' }}">
            <a class="nav-link nav-dropdown-toggle {{ (request()->is('seller/report*')) ? 'active' : '' }}" href="#">
              <i class="nav-icon fa fa-home"></i> {{ __('Reports') }}</a>
                <ul class="nav-dropdown-items">
                  <li class="nav-item">
                    <a class="nav-link {{ (request()->is('seller/report/group-sales-products*')) ? 'active' : '' }}" href="{{ route('seller.report.group.sales.products') }}">
                      <i class="nav-icon fa fa-picture-o"></i> {{ __('Group Sales By Products') }}
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link {{ (request()->is('seller/report/group-sales-customers*')) ? 'active' : '' }}" href="{{ route('seller.report.group.sales.customers') }}">
                      <i class="nav-icon fa fa-picture-o"></i> {{ __('Group Sales By Customers') }}
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link {{ (request()->is('seller/report/group-sale*')) ? 'active' : '' }}" href="{{ route('seller.report.group.sale') }}">
                      <i class="nav-icon fa fa-picture-o"></i> {{ __('Group Sales') }}
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link {{ (request()->is('seller/report/coupon-usage*')) ? 'active' : '' }}" href="{{ route('seller.report.coupon.usage') }}">
                      <i class="nav-icon fa fa-picture-o"></i> {{ __('Coupon Usage') }}
                    </a>
                  </li>
                </ul>
          </li>
          {{-- <li class="nav-item">
            <a class="nav-link {{ (request()->is('seller/daily-deals*')) ? 'active' : '' }}" href="{{ route('seller.daily.deals') }}">
              <i class="nav-icon fa fa-shopping-bag"></i> {{ __('Daily Deals') }}
            </a>
          </li> --}}

          <li class="nav-item">
            <a class="nav-link {{ (request()->is('seller/staff*')) ? 'active' : '' }}" href="{{ route('seller.staff') }}">
              <i class="nav-icon fa fa-users"></i> {{ __('Staff') }}
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ (request()->is('seller/store-questions*')) ? 'active' : '' }}" href="{{ route('seller.store.questions') }}">
              <i class="nav-icon fa fa-question"></i> {{ __('Store Questions') }}
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ (request()->is('seller/return-address*')) ? 'active' : '' }}" href="{{ route('seller.return.address') }}">
              <i class="nav-icon fa fa-address-card"></i> {{ __('Return Address') }}
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ (request()->is('seller/store-profile*')) ? 'active' : '' }}" href="{{ route('seller.store.profile') }}">
              <i class="nav-icon fa fa-user"></i> {{ __('Store Profile') }}
            </a>
          </li>
            @php
                $_seller = auth()->guard('seller')->user();
                $_store = \App\Models\Store::find($_seller->store_id);
            @endphp
            @if(!$_store->isApproved())
            @endif
            <li class="nav-item">
                <a class="nav-link {{ (request()->is('seller/store-detail*')) ? 'active' : '' }}" href="{{ route('seller.store.store-detail') }}">
                    <i class="nav-icon fa fa-user"></i> {{ __('Store Detail') }}
                </a>
            </li>

          <li class="nav-item">
            <a class="nav-link" href="{{ route('seller.logout') }}">
              <i class="nav-icon cui-account-logout"></i> {{ __('Logout') }}
            </a>
          </li>
        </ul>
      </nav>
      <button class="sidebar-minimizer brand-minimizer" type="button"></button>
    </div>
    <main class="main">
      @yield('content')
    </main>
  </div>
  <!-- CoreUI and necessary plugins-->
  <script src="{{ asset('assets/backend') }}/node_modules/jquery/dist/jquery.min.js"></script>
  <script src="{{ asset('assets/backend') }}/node_modules/popper.js/dist/umd/popper.min.js"></script>
  <script src="{{ asset('assets/backend') }}/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
  <script src="{{ asset('assets/backend') }}/node_modules/perfect-scrollbar/dist/perfect-scrollbar.min.js"></script>
  <script src="{{ asset('assets/backend') }}/node_modules/@coreui/coreui/dist/js/coreui.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.0/axios.min.js"></script>
  <script>
    $(document).on("keydown", ".number", function(e){

      if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
        (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
        (e.keyCode === 67 && (e.ctrlKey === true || e.metaKey === true)) ||
        (e.keyCode === 86 && (e.ctrlKey === true || e.metaKey === true)) ||
        (e.keyCode >= 35 && e.keyCode <= 40)) {
        return;
      }

      if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
        e.preventDefault();
      }
    });
    $(function(){
      $('form').submit(function(){
        if( $(this).valid() ) {
          $("button[type='submit']")
          .html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> {{ __("Processing...") }}')
          .prop('disabled', true);
          return true;
        }
      });
    });
  </script>
  @stack('footer')
  <script src="{{ asset('assets/backend') }}/js/custom.js?ver=1.0.1"></script>
</body>
</html>
