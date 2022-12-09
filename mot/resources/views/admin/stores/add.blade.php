@extends('admin.layouts.app')

@section('content')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.stores') }}">{{ $section_title }}</a></li>
    <li class="breadcrumb-item active">{{ $title }}</li>
</ol>
<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header">
                        {{ $title }}
                        <x-admin.back-button :url="route('admin.stores')" />
                    </div>
                    <div class="card-body">
                        <!-- alerts -->
                        <x-alert class="alert-success" :status="session('success')" />
                        <x-alert class="alert-danger" :status="session('error')" />
                        <form action="{{ route('admin.stores.add') }}" method="POST" id="add_form">
                            @csrf
                            @php
                            $type = old('type', 1);
                            @endphp
                            <div class="form-group">
                                <label>{{ __('Type') }}</label><br>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="type_business" name="type" class="custom-control-input" value="1" @if ($type == 1) checked @endif onchange="accountType(this.value)">
                                    <label class="custom-control-label" for="type_business">{{ __('Private Company') }}</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="type_limited_stock_company" name="type" class="custom-control-input" value="2" @if ($type == 2) checked @endif onchange="accountType(this.value)">
                                    <label class="custom-control-label" for="type_limited_stock_company">{{ __('Limited Stock Company') }}</label>
                                </div>
                                @error('type')
                                <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="name">{{ __('Name') }}</label>
                                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')">
                                        @error('name')
                                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="legal_name">{{ __('Legal Name') }}</label>
                                        <input type="text" name="legal_name" id="legal_name" class="form-control" value="{{ old('legal_name') }}" oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')">
                                        @error('legal_name')
                                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="phone">{{ __('Phone') }}</label>
                                        <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}" onkeypress="return event.charCode & gt; = 48 & amp; & amp; event.charCode & lt; = 57" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')">
                                        @error('phone')
                                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="email">{{ __('Email') }}</label>
                                        <input type="email" oninvalid="InvalidMsg(this);" name="email" id="email" class="form-control" value="{{ old('email') }}"  oninput="InvalidMsg(this);" required="required">
                                        @error('email')
                                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="password">{{ __('Password') }}</label>
                                        <input type="password" name="password" id="password" class="form-control" minlength="6" maxlength="20" autocomplete="new-password" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')">
                                        @error('password')
                                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                        @enderror
                                    </div>
                                </div>

                                @php
                                $country = old('country');
                                @endphp
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="country">{{ __('Country') }}</label>
                                        @foreach ($countries as $r)
                                        @if($r->is_default=='Yes')
                                        <input type="text" name="country-name" id="country-name" class="form-control" value="{{ old('country',$r->title) }}" readonly>
                                        <input type="hidden" name="country" id="country" class="form-control" value="{{ old('country',$r->id) }}" >
                                        @endif
                                        @endforeach
                                        @error('country')
                                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="state">{{ __('State') }}</label>
                                        <select name="state" id="state" class="form-control @error('state') is-invalid @enderror" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')">
                                            @foreach ($states as $r)
                                            <option value="{{ $r->title }}" >{{ $r->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="city">{{ __('City') }}</label>
                                        <select name="city" id="city" class="form-control @error('city') is-invalid @enderror" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')">
                                            @foreach ($cities as $r)
                                            <option value="{{ $r->title }}" >{{ $r->title }}</option>
                                            @endforeach
                                        </select>
                                        @error('city')
                                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="address">{{ __('Address') }}</label>
                                        <input type="text" name="address" id="address" class="form-control" value="{{ old('address') }}" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')">
                                        @error('address')
                                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="zipcode">{{ __('Postal Code') }}</label>
                                        <input type="text" name="zipcode" id="zipcode" class="form-control" value="{{ old('zipcode') }}" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')">
                                        @error('zipcode')
                                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="commission">{{ __('Commission') }}</label>
                                        <input type="text" name="commission" id="commission" class="form-control number" value="{{ old('commission') }}">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="tax_office">{{ __('Tax Office') }}</label>
                                        <input type="text" name="tax_office" id="tax_office" class="form-control" value="{{ old('tax_office') }}" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')">
                                        @error('tax_office')
                                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="iban">{{ __('Iban') }}</label>
                                        <input type="text" name="iban" id="iban" class="form-control" value="{{ old('iban') }}" maxlength="36">
                                        @error('iban')
                                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="identity_no">{{ __('Identity Number') }}</label>
                                        <input type="text" name="identity_no" id="identity_no" class="form-control" value="{{ old('identity_no') }}" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')">
                                        @error('identity_no')
                                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="tax_info_box" style="display: {{ $type == 0 ? 'none' : '' }}">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="tax_id">{{ __('Tax ID') }}</label>
                                        <input type="text" name="tax_id" id="tax_id" class="form-control" value="{{ old('tax_id') }}" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')">
                                        @error('tax_id')
                                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <!-- submit button -->
                            <div class="text-center">
                                <x-admin.publish-button />
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
    function accountType(type) {
    if (type == 0) {
    $('#tax_info_box').hide();
    return;
    }

    $('#tax_info_box').show();
    }

    function InvalidMsg(textbox) {

    if (textbox.value == '') {
    textbox.setCustomValidity('{{__('Please fill out this field')}}');
    }
    else if (textbox.validity.typeMismatch){
    textbox.setCustomValidity('{{__('please enter a valid email address')}}');
    }
    else {
    textbox.setCustomValidity('');
    }
    return true;
    }
</script>
@endpush
