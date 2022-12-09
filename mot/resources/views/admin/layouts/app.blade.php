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
            .pull-right{
                float: right;
            }
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
            {{-- <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
            <span style="color: #23282c; display: inline-grid; font-weight: 600;">{{ __(config('app.name')) }}</span>
        </a> --}}
        <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
            <img class="navbar-brand-full" src="{{ asset('assets/backend') }}/img/brand/logo.svg" width="75" alt="Logo">
            <img class="navbar-brand-minimized" src="{{ asset('assets/backend') }}/img/brand/logo.svg" width="45" alt="Logo">
        </a>
        <ul class="nav navbar-nav d-md-down-none">
            @can('settings')
            <li class="nav-item px-3">
                <a class="nav-link" href="{{ route('admin.settings') }}">{{ __('Settings') }}</a>
            </li>
            @endcan
            @can('media-settings')
            <li class="nav-item px-3">
                <a class="nav-link" href="{{ route('admin.media.settings') }}">{{ __('Media') }}</a>
            </li>
            @endcan
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
{{--                    <a class="dropdown-item {{ (app()->getLocale() == $row->code) ? 'active_lang' : '' }}" href="{{ url('locale/'.$row->code) }}"> <img height="15" src="{{ asset('/assets/frontend') }}/assets/flags/{{$row->emoji_uc}}.svg">  {{__($row->native)}}</a>--}}
                    <a class="dropdown-item {{ (app()->getLocale() == $row->code) ? 'active_lang' : '' }}" href="{{ url('admin/locale/'.$row->code) }}"> <img height="15" src="{{ asset('/assets/frontend') }}/assets/flags/{{$row->emoji_uc}}.svg">  {{__($row->native)}}</a>
                    @endforeach
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                   @if ( auth()->user())
                    @if ( auth()->user()->image )
                    <img class="img-avatar" src="{{ asset('storage/original'.auth()->user()->image) }}" alt="{{ auth()->user()->name }}">
                    @else
                    <div class="default-profile-pic">{{ substr(auth()->user()->name,0,1) }}</div>
                    @endif
                    @endif
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    @can('profile')
                    <a class="dropdown-item" href="{{ route('admin.profile') }}">
                        <i class="fa fa-user"></i> {{ __('Profile') }}
                    </a>
                    @endcan
                    <a class="dropdown-item" href="{{ route('admin.logout') }}">
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
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                            <i class="nav-icon icon-speedometer"></i> {{ __('Dashboard') }}
                        </a>
                    </li>
                    <li class="nav-item nav-dropdown {{ ( request()->is('admin/products*') || request()->is('admin/pending-products*') || request()->is('admin/categories*') || request()->is('admin/brands*') || request()->is('admin/attributes*') || request()->is('admin/tags*') ) ? 'open' : '' }}">
                        <a class="nav-link nav-dropdown-toggle {{ ( request()->is('admin/products*') || request()->is('admin/categories*') || request()->is('admin/brands*') || request()->is('admin/attributes*') || request()->is('admin/tags*') ) ? 'active' : '' }}" href="#">
                            <i class="nav-icon fa fa-home"></i> {{ __('Products') }}</a>
                        <ul class="nav-dropdown-items">
                            @can('product-list')
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('admin/products*')) ? 'active' : '' }}" href="{{ route('admin.products') }}">
                                    <i class="nav-icon fa fa-shopping-bag"></i> {{ __('All Products') }}
                                </a>
                            </li>
                            @endcan
                            @can('pending-products-list')
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('admin/pending-products*')) ? 'active' : '' }}" href="{{ route('admin.pending.products') }}">
                                    <i class="nav-icon fa fa-shopping-bag"></i> {{ __('Pending Products') }}
                                </a>
                            </li>
                            @endcan
                            @can('bundled-products-list')
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('admin/promotions/bundled-products*')) ? 'active' : '' }}" href="{{ route('admin.bundled.products') }}">
                                    <i class="nav-icon fa fa-shopping-bag"></i> {{ __('Bundled Products') }}
                                </a>
                            </li>
                            @endcan
                            @can('categories-list')
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('admin/categories*')) ? 'active' : '' }}" href="{{ route('admin.categories') }}">
                                    <i class="nav-icon icon-menu"></i> {{ __('Categories') }}
                                </a>
                            </li>
                            @endcan
                            @can('categories-list')
                            @endcan
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('admin/trendyol-categories*')) ? 'active' : '' }}" href="{{ route('admin.trendyol.categories') }}">
                                    <i class="nav-icon icon-menu"></i> {{ __('Trendyol Categories') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('admin/trendyol-categories-parent*')) ? 'active' : '' }}" href="{{ route('admin.trendyol.categories.parent') }}">
                                    <i class="nav-icon icon-menu"></i> {{ __('Trendyol Product Categories') }}
                                </a>
                            </li>
                            @can('brands-list')
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('admin/brands*')) ? 'active' : '' }}" href="{{ route('admin.brands') }}">
                                    <i class="nav-icon icon-menu"></i> {{ __('Brands') }}
                                </a>
                            </li>
                            @endcan
                            @can('pending-brands-list')
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('admin/pending-brands*')) ? 'active' : '' }}" href="{{ route('admin.pending.brands') }}">
                                    <i class="nav-icon fa fa-shopping-bag"></i> {{ __('Pending Brands') }}
                                </a>
                            </li>
                            @endcan
                            @can('attributes-list')
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('admin/attributes*')) ? 'active' : '' }}" href="{{ route('admin.attributes') }}">
                                    <i class="nav-icon icon-menu"></i> {{ __('Attributes') }}
                                </a>
                            </li>
                            @endcan
                            @can('tags-list')
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('admin/tags*')) ? 'active' : '' }}" href="{{ route('admin.tags') }}">
                                    <i class="nav-icon fa fa-tags"></i> {{ __('Tags') }}
                                </a>
                            </li>
                            @endcan
                            @can('product-banners-list')
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('admin/product-banners*')) ? 'active' : '' }}" href="{{ route('admin.product.banners') }}">
                                    <i class="nav-icon fa fa-picture-o"></i> {{ __('Product List Page Banners') }}
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                    @can('orders-list')
                    <li class="nav-item">
                        <a class="nav-link {{ (request()->is('admin/orders*')) ? 'active' : '' }}" href="{{ route('admin.orders') }}">
                            <i class="nav-icon icon-note"></i> {{ __('Orders') }}
                        </a>
                    </li>
                    @endcan
                    @can('pending-orders-list')
                    <li class="nav-item">
                        <a class="nav-link {{ (request()->is('admin/pending-orders*')) ? 'active' : '' }}" href="{{ route('admin.pending.orders') }}">
                            <i class="nav-icon icon-note"></i> {{ __('Uninitiated Orders') }}
                        </a>
                    </li>
                    @endcan
                    @can('product-reviews-list')
                    <li class="nav-item">
                        <a class="nav-link {{ (request()->is('admin/product-reviews*')) ? 'active' : '' }}" href="{{ route('admin.product.reviews') }}">
                            <i class="nav-icon fa fa-star"></i> {{ __('Product Reviews') }}
                        </a>
                    </li>
                    @endcan
                    @can('Returns')
                    <li class="nav-item nav-dropdown {{ (request()->is('admin/returns*')) ? 'open' : '' }}">
                        <a class="nav-link nav-dropdown-toggle {{ (request()->is('admin/returns*')) ? 'active' : '' }}" href="#">
                            <i class="nav-icon fa fa-percent"></i> {{ __('Returns') }}</a>
                        <ul class="nav-dropdown-items">

                            @can('return-requests-list')
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('admin/return-requests*')) ? 'active' : '' }}" href="{{ route('admin.return.requests') }}">
                                    <i class="nav-icon icon-note"></i> {{ __('Return Requests') }}
                                </a>
                            </li>
                            @endcan
                            @can('cancel-requests-list')
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('admin/cancel-requests*')) ? 'active' : '' }}" href="{{ route('admin.cancel.requests') }}">
                                    <i class="nav-icon icon-note"></i> {{ __('Cancel Requests') }}
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                    @endcan
                    @can('customers-list')
                    <li class="nav-item">
                        <a class="nav-link {{ (request()->is('admin/customers*')) ? 'active' : '' }}" href="{{ route('admin.customers') }}">
                            <i class="nav-icon fa fa-users"></i> {{ __('Customers') }}
                        </a>
                    </li>
                    @endcan
                    @can('stores-list')
                    <li class="nav-item">
                        <a class="nav-link {{ (request()->is('admin/stores*')) ? 'active' : '' }}" href="{{ route('admin.stores') }}">
                            <i class="nav-icon fa fa-shopping-basket"></i> {{ __('Stores') }}
                        </a>
                    </li>
                    @endcan
                    @can('pending-stores-list')
                    <li class="nav-item">
                        <a class="nav-link {{ (request()->is('admin/pending-stores*')) ? 'active' : '' }}" href="{{ route('admin.pending.stores') }}">
                            <i class="nav-icon fa fa-shopping-basket"></i> {{ __('Pending Stores') }}
                        </a>
                    </li>
                    @endcan
                    @can('promotions-list')
                    <li class="nav-item nav-dropdown {{ (request()->is('admin/promotions*')) ? 'open' : '' }}">
                        <a class="nav-link nav-dropdown-toggle {{ (request()->is('admin/promotions*')) ? 'active' : '' }}" href="#">
                            <i class="nav-icon fa fa-percent"></i> {{ __('Promotions') }}</a>
                        <ul class="nav-dropdown-items">

                            @can('coupons-list')
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('admin/coupons*')) ? 'active' : '' }}" href="{{ route('admin.coupons') }}">
                                    <i class="nav-icon fa fa-percent"></i> {{ __('Discount Campaigns') }}
                                </a>
                            </li>
                            @endcan
                            @can('free-delivery-list')
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('admin/promotions/free-delivery*')) ? 'active' : '' }}" href="{{ route('admin.free.delivery') }}">
                                    <i class="nav-icon fa fa-truck"></i> {{ __('Free Delivery') }}
                                </a>
                            </li>
                            @endcan
                            @can('flash-deals-list')
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('admin/flash-deals*')) ? 'active' : '' }}" href="{{ route('admin.flash.deals') }}">
                                    <i class="nav-icon fa fa-shopping-bag"></i> {{ __('Flash Deals') }}
                                </a>
                            </li>
                            @endcan
                             <!--          <li class="nav-item">
                                <a class="nav-link {{ (request()->is('admin/daily-deals*')) ? 'active' : '' }}" href="{{ route('admin.daily.deals') }}">
                                  <i class="nav-icon fa fa-shopping-bag"></i> {{ __('Daily Deals') }}
                                </a>
                              </li> -->
                        </ul>
                    </li>
                    @endcan

                    @can('home-page-setup')
                    <li class="nav-item nav-dropdown {{ (request()->is('admin/home-page*')) ? 'open' : '' }}">
                        <a class="nav-link nav-dropdown-toggle {{ (request()->is('admin/home-page*')) ? 'active' : '' }}" href="#">
                            <i class="nav-icon fa fa-home"></i> {{ __('Home Page Setup') }}</a>
                        <ul class="nav-dropdown-items">
                            @can('sliders-list')
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('admin/home-page/sliders*')) ? 'active' : '' }}" href="{{ route('admin.sliders') }}">
                                    <i class="nav-icon fa fa-picture-o"></i> {{ __('Home Page Sliders') }}
                                </a>
                            </li>
                            @endcan
                            @can('sponsored-categories-list')
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('admin/home-page/sponsored-categories*')) ? 'active' : '' }}" href="{{ route('admin.sponsored.categories') }}">
                                    <i class="nav-icon icon-menu"></i> {{ __('Sponsored Banners') }}
                                </a>
                            </li>
                            @endcan
                            @can('tabbed-products-list')
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('admin/home-page/tabbed-products*')) ? 'active' : '' }}" href="{{ route('admin.tabbed.products') }}">
                                    <i class="nav-icon icon-menu"></i> {{ __('Tabbed Products') }}
                                </a>
                            </li>
                            @endcan
                            @can('trending-products-list')
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('admin/home-page/trending-products*')) ? 'active' : '' }}" href="{{ route('admin.trending.products') }}">
                                    <i class="nav-icon icon-menu"></i> {{ __('Trending Products') }}
                                </a>
                            </li>
                            @endcan
                            @can('banners-list')
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('admin/home-page/banners*')) ? 'active' : '' }}" href="{{ route('admin.banners') }}">
                                    <i class="nav-icon fa fa-picture-o"></i> {{ __('Footer Banners') }}
                                </a>
                            </li>
                            @endcan
                            @can('sections-sorting-list')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.sections.sorting') }}">
                                    <i class="nav-icon fa fa-sort"></i> {{ __('Sections Sorting') }}
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                    @endcan
                    @can('reports-list')
                    <li class="nav-item nav-dropdown {{ (request()->is('admin/reports*')) ? 'open' : '' }}">
                        <a class="nav-link nav-dropdown-toggle {{ (request()->is('admin/reports*')) ? 'active' : '' }}" href="#">
                            <i class="nav-icon fa fa-home"></i> {{ __('Reports') }}</a>
                        <ul class="nav-dropdown-items">
                            @can('group-sales-stores')
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('admin/reports/group-sales-stores*')) ? 'active' : '' }}" href="{{ route('admin.reports.group.sales.stores') }}">
                                    <i class="nav-icon fa fa-picture-o"></i> {{ __('Group Sales By Store') }}
                                </a>
                            </li>
                            @endcan
                            @can('group-sales-products')
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('admin/reports/group-sales-products*')) ? 'active' : '' }}" href="{{ route('admin.reports.group.sales.products') }}">
                                    <i class="nav-icon fa fa-picture-o"></i> {{ __('Group Sales By Products') }}
                                </a>
                            </li>
                            @endcan
                            @can('group-sales-customers')
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('admin/reports/group-sales-customers*')) ? 'active' : '' }}" href="{{ route('admin.reports.group.sales.customers') }}">
                                    <i class="nav-icon fa fa-picture-o"></i> {{ __('Group Sales By Customers') }}
                                </a>
                            </li>
                            @endcan
                            @can('group-sale')
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('admin/reports/group-sale*')) ? 'active' : '' }}" href="{{ route('admin.reports.group.sale') }}">
                                    <i class="nav-icon fa fa-picture-o"></i> {{ __('Group Sales') }}
                                </a>
                            </li>
                            @endcan
                            @can('sales')
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('admin/reports/sales*')) ? 'active' : '' }}" href="{{ route('admin.reports.sales') }}">
                                    <i class="nav-icon fa fa-picture-o"></i> {{ __('Sales') }}
                                </a>
                            </li>
                            @endcan
                            @can('coupon-usage')
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('admin/reports/coupon-usage*')) ? 'active' : '' }}" href="{{ route('admin.reports.coupon.usage') }}">
                                    <i class="nav-icon fa fa-picture-o"></i> {{ __('Coupon Usage') }}
                                </a>
                            </li>
                            @endcan
                            @can('most-searches')
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('admin/reports/most-searches*')) ? 'active' : '' }}" href="{{ route('admin.reports.most-searches') }}">
                                    <i class="nav-icon fa fa-picture-o"></i> {{ __('Most Searches') }}
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                    @endcan
                    @can('contact-inquiries-list')
                    <li class="nav-item">
                        <a class="nav-link {{ (request()->is('admin/contact-inquiries*')) ? 'active' : '' }}" href="{{ route('admin.contact.inquiries') }}">
                            <i class="nav-icon fa fa-bell-o"></i> {{ __('Contact Inquiries') }}
                        </a>
                    </li>
                    @endcan
                    @can('activity-logs')
                    
                    @endcan
                    <li class="nav-item">
                        <a class="nav-link {{ (request()->is('admin/activity-logs*')) ? 'active' : '' }}" href="{{ route('admin.activity.logs') }}">
                            <i class="nav-icon fa fa-bell-o"></i> {{ __('activity-logs') }}
                        </a>
                    </li>
                    @can('request-products-list')

                    @endcan
                    <li class="nav-item">
                        <a class="nav-link {{ (request()->is('admin/request-products*')) ? 'active' : '' }}" href="{{ route('admin.request.products') }}">
                            <i class="nav-icon fa fa-bell-o"></i> {{ __('Request Products') }}
                        </a>
                    </li>
                    @can('user-setup')
                    <li class="nav-item nav-dropdown {{ (request()->is('admin/users*')) ? 'open' : '' }}">
                        <a class="nav-link nav-dropdown-toggle {{ (request()->is('admin/users*')) ? 'active' : '' }}" href="#">
                            <i class="nav-icon fa fa-home"></i> {{ __('System Users') }}</a>
                        <ul class="nav-dropdown-items">
                            @can('user-list')
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('admin/users*')) ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                                    <i class="nav-icon fa fa-picture-o"></i> {{ __('Staff') }}
                                </a>
                            </li>
                            @endcan
                            @can('role-list')
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('admin/roles*')) ? 'active' : '' }}" href="{{ route('admin.roles.index') }}">
                                    <i class="nav-icon fa fa-picture-o"></i> {{ __('Staff Permissions') }}
                                </a>
                            </li>
                            @endcan
                            @can('permission-list')
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('admin/permission*')) ? 'active' : '' }}" href="{{ route('admin.permission.index') }}">
                                    <i class="nav-icon fa fa-picture-o"></i> {{ __('Permissions') }}
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                    @endcan
                    @can('configuration')
                    <li class="nav-item nav-dropdown {{ (request()->is('admin/configuration*')) ? 'open' : '' }}">
                        <a class="nav-link nav-dropdown-toggle {{ (request()->is('admin/configuration*')) ? 'active' : '' }}" href="#">
                            <i class="nav-icon fa fa-home"></i> {{ __('Configuration') }}</a>
                        <ul class="nav-dropdown-items">
                            @can('currencies-list')
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('admin/currencies*')) ? 'active' : '' }}" href="{{ route('admin.currencies') }}">
                                    <i class="nav-icon fa fa-money"></i> {{ __('Currencies') }}
                                </a>
                            </li>
                            @endcan
                            @can('languages-list')
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('admin/languages*')) ? 'active' : '' }}" href="{{ route('admin.languages') }}">
                                    <i class="nav-icon fa fa-language"></i> {{ __('Languages') }}
                                </a>
                            </li>
                            @endcan
                            @can('countries-list')
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('admin/countries*')) ? 'active' : '' }}" href="{{ route('admin.countries') }}">
                                    <i class="nav-icon fa fa-globe"></i> {{ __('Countries') }}
                                </a>
                            </li>
                            @endcan
                            @can('pages-list')
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('admin/pages*')) ? 'active' : '' }}" href="{{ route('admin.pages') }}">
                                    <i class="nav-icon fa fa-file-text-o"></i> {{ __('MOT Services') }}
                                </a>
                            </li>
                            @endcan
{{--                            @can('help-center')--}}
                                <li class="nav-item">
                                    <a class="nav-link {{ (request()->is('admin/help-centers*')) ? 'active' : '' }}" href="{{ route('admin.help-centers') }}">
                                        <i class="nav-icon fa fa-file-text-o"></i> {{ __('Help Centers') }}
                                    </a>
                                </li>
{{--                            @endcan--}}
                        </ul>
                    </li>
                    @endcan
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.logout') }}">
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
    $(document).on("keydown", ".number", function (e) {
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
    $(function () {
        $('form').submit(function () {
            if ($(this).valid()) {
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
