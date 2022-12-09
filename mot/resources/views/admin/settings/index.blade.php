@extends('admin.layouts.app')

@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
        <li class="breadcrumb-item active">{{ __($title) }}</li>
    </ol>
    <div class="container-fluid">
        <div class="animated fadeIn">
            <!-- alerts -->
            <x-alert class="alert-success" :status="session('success')"/>
            <x-alert class="alert-danger" :status="session('error')"/>
            <form action="{{ route('admin.settings') }}" method="POST" enctype="multipart/form-data" id="settings_form">
                @csrf
                <div class="row">
                    <div class="col-lg-12">
                        {{-- general information --}}
                        <div class="card">
                            <div class="card-header">
                                {{ __('General Settings') }}
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="targetemail">{{ __('Form Target Email') }}</label>
                                            <input type="text" name="targetemail" id="targetemail" class="form-control"
                                                   value="{{ get_option('targetemail') }}" required
                                                   oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"
                                                   oninput="this.setCustomValidity('')"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="currency_api">{{ __('Open Exchange Rates API Key') }}, <a
                                                    href="https://openexchangerates.org/"
                                                    target="_blank">{{ __('Click here') }}</a> {{ __('to get API Key') }}
                                            </label>
                                            <input type="text" name="currency_api" id="currency_api" class="form-control"
                                                   value="{{ get_option('currency_api') }}"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="contact_no">{{ __('Contact Number') }}</label>
                                            <input type="text" name="contact_no" id="contact_no" class="form-control"
                                                   value="{{ get_option('contact_no') }}"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="address">{{ __('Address') }}</label>
                                            <input type="text" name="address" id="address" class="form-control"
                                                   value="{{ get_option('address') }}"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        @php
                                            $logo = get_option('logo');
                                        @endphp
                                        <div class="form-group">
                                            <label>{{__('Logo')}} <small>{{__('(upload jpg, jpeg, png)')}}</small></label>
                                            <div class="main-img-preview mb-2 @if( !empty($logo) ) d-block @endif">
                                                <img class="img-thumbnail img-preview"
                                                     @if( !empty($logo) ) src="{{ UtilityHelper::getCdnUrl(route('resize', [190, 75, $logo])) }} @endif">
                                            </div>
                                            <div class="input-group">
                                                <span class="input-group-prepend">
                                                  <button class="btn btn-primary ps-trigger-file" type="button"><i class="fa fa-upload mr-1"></i> Upload</button>
                                                  <input name="logo" type="file" class="ps-file-input d-none" accept=".jpg, .jpeg, .png">
                                                </span>
                                                <input class="form-control" type="text" placeholder="Choose File" disabled>
                                            </div>
                                            @error('logo')
                                            <span class="invalid-feedback d-block"
                                                  role="alert"> <strong>{{ $message }}</strong> </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        @php
                                            $app_splash_banner = get_option('app_splash_banner');
                                        @endphp
                                        <div class="form-group">
                                            <label>{{__('Application Splash Banner')}}
                                                <small>{{__('(upload jpg, jpeg, png)')}}</small></label>
                                            <div class="main-img-preview mb-2 @if( !empty($logo) ) d-block @endif">
                                                <img class="img-thumbnail img-preview"
                                                     @if( !empty($app_splash_banner) ) src="{{ UtilityHelper::getCdnUrl(route('resize', [190, 75, $app_splash_banner])) }} @endif">
                                            </div>
                                            <div class="input-group">
                                                <span class="input-group-prepend">
                                                  <button class="btn btn-primary ps-trigger-file" type="button"><i
                                                          class="fa fa-upload mr-1"></i> Upload</button>
                                                  <input name="app_splash_banner" type="file"
                                                         class="ps-file-input d-none" accept=".jpg, .jpeg, .png">
                                                </span>
                                                <input class="form-control" type="text" placeholder="Choose File"
                                                       disabled>
                                            </div>
                                            @error('app_splash_banner')
                                            <span class="invalid-feedback d-block"
                                                  role="alert"> <strong>{{ $message }}</strong> </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="shipping_days">{{ __('Shipping Days') }}</label>
                                            <input type="number" name="shipping_days" id="shipping_days" class="form-control" value="{{ get_option('shipping_days') }}"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="return_days">{{ __('Return Days') }}</label>
                                            <input type="number" name="return_days" id="return_days" class="form-control" value="{{ get_option('return_days') }}"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="shipping_flat_rate">{{ __('Flat Rate USD') }}</label>
                                            <input type="number" name="shipping_flat_rate" id="shipping_flat_rate" class="form-control" value="{{ get_option('shipping_flat_rate') }}"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="app_home_template">{{ __('Application Home Page') }}</label>
                                            <select name="app_home_template" class="form-control" id="app_home_template">
                                                <option value="home" {{ (get_option('app_home_template') == 'home') ? 'selected':'' }}>{{ __('Home') }}</option>
                                                <option value="home1" {{ (get_option('app_home_template') == 'home1') ? 'selected':'' }}>{{ __('Home 1') }}</option>
                                                <!--<option value="home2" {{ (get_option('app_home_template') == 'home2') ? 'selected':'' }}>{{ __('Home 2') }}</option>-->
                                            </select>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- payment gateway information --}}
                        <div class="card">
                            <div class="card-header">
                                {{ __('Payment Information') }}
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="myfatoorah_test_key">{{ __('MyFatoorah Test Key') }}</label>
                                            <input type="text" name="myfatoorah_test_key" id="myfatoorah_test_key" class="form-control" value="{{ get_option('myfatoorah_test_key') }}"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="myfatoorah_production_key">{{ __('MyFatoorah Production Key') }}</label>
                                            <input type="text" name="myfatoorah_production_key" id="myfatoorah_production_key" class="form-control" value="{{ get_option('myfatoorah_production_key') }}"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- social media information --}}
                        <div class="card">
                            <div class="card-header">
                                {{ __('Social Media Icons') }}
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="social_facebook">{{ __('Facebook') }}</label>
                                            <input type="text" name="social_facebook" id="social_facebook" class="form-control" value="{{ get_option('social_facebook') }}"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="social_instagram">{{ __('Instagram') }}</label>
                                            <input type="text" name="social_instagram" id="social_instagram" class="form-control"
                                                   value="{{ get_option('social_instagram') }}"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="social_twitter">{{ __('Twitter') }}</label>
                                            <input type="text" name="social_twitter" id="social_twitter" class="form-control"
                                                   value="{{ get_option('social_twitter') }}"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="social_pinterest">{{ __('Pinterest') }}</label>
                                            <input type="text" name="social_pinterest" id="social_pinterest" class="form-control"
                                                   value="{{ get_option('social_pinterest') }}"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="social_snapchat">{{ __('Snapchat') }}</label>
                                            <input type="text" name="social_snapchat" id="social_snapchat" class="form-control"
                                                   value="{{ get_option('social_snapchat') }}"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="social_youtube">{{ __('Youtube') }}</label>
                                            <input type="text" name="social_youtube" id="social_youtube" class="form-control"
                                                   value="{{ get_option('social_youtube') }}"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="social_linkedin">{{ __('LinkendIn') }}</label>
                                            <input type="text" name="social_linkedin" id="social_linkedin" class="form-control"
                                                   value="{{ get_option('social_linkedin') }}"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Top Header Notification Alert information --}}
                        <div class="card">
                            <div class="card-header">
                                {{ __('Top Header Notification') }}
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach(getLocaleList() as $row)
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label
                                                    for="top_notification_{{$row->code}}">{{ __('Notification Content ('.$row->title.')') }}</label>
                                                <input type="text" name="top_notification_{{$row->code}}"
                                                       id="top_notification_{{$row->code}}" class="form-control"
                                                       value="{{ get_option('top_notification_'.$row->code) }}" />
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                        {{-- meta tags information --}}
                        <div class="card">
                            <div class="card-header">
                                {{ __('Meta Tags') }}
                            </div>
                            <div class="card-body">
                                <div class="row">
                                @foreach(getLocaleList() as $row)
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label
                                                for="meta_title_{{$row->code}}">{{ __('Meta Title ('.$row->title.')') }}</label>
                                            <input type="text" name="meta_title_{{$row->code}}"
                                                   id="meta_title_{{$row->code}}" class="form-control"
                                                   value="{{ get_option('meta_title_'.$row->code) }}" required/>
                                        </div>
                                    </div>
                                @endforeach
                                @foreach(getLocaleList() as $row)
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label
                                                for="meta_description_{{$row->code}}">{{ __('Meta Description ('.$row->title.')') }}</label>
                                            <textarea class="form-control" name="meta_description_{{$row->code}}" id=""
                                                      cols="30" rows="5"
                                                      required>{{ get_option('meta_description_'.$row->code) }}</textarea>
                                        </div>
                                    </div>
                                @endforeach
                                @foreach(getLocaleList() as $row)
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="meta_keywords_{{$row->code}}">{{ __('Meta Keywords ('.$row->title.')') }}</label>
                                            <input type="text" name="meta_keywords_{{$row->code}}"
                                                   id="meta_keywords_{{$row->code}}" class="form-control"
                                                   value="{{ get_option('meta_keywords_'.$row->code) }}" />
                                        </div>
                                    </div>
                                @endforeach
                                </div>
                            </div>
                        </div>
                        <!-- submit button -->
                        <div class="text-center mb-3">
                            <x-admin.save-changes-button/>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('footer')
    <x-validation/>
@endpush
