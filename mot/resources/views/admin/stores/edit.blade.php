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
                        <a href="{{ route('admin.stores.profile', ['store' => $row->id]) }}" class="btn btn-outline-warning btn-sm mb-1 text-dark pull-right">{{__('Profile')}}</a>
                    </div>
                    <div class="card-body">
                        <!-- alerts -->
                        <x-alert class="alert-success" :status="session('success')" />
                        <x-alert class="alert-danger" :status="session('error')" />
                        <form action="{{ route('admin.stores.edit', ['store' => $row->id]) }}" method="POST" id="edit_form">
                            @csrf
                            @php
                            $type = old('type', $row->type);
                            @endphp
                            <div class="form-group">
                                <label>{{ __('Type') }} <em style="color: red">*</em> </label><br>
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
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="name">{{ __('Name') }} <em style="color: red">*</em> </label>
                                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $row->name) }}" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')">
                                        @error('name')
                                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="legal_name">{{ __('Legal Name') }} <em style="color: red">*</em> </label>
                                        <input type="text" name="legal_name" id="legal_name" class="form-control" value="{{ old('legal_name', $row->legal_name) }}" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')">
                                        @error('legal_name')
                                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="phone">{{ __('Office Phone') }}</label>
                                        <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $row->phone) }}" onkeypress="return event.charCode & gt; = 48 & amp; & amp; event.charCode & lt; = 57" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')">
                                        @error('phone')
                                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="mobile">{{ __('Mobile Authorized Person') }}</label>
                                        <input type="text" name="mobile" id="mobile" class="form-control" value="{{ old('mobile', $row->mobile) }}" onkeypress="return event.charCode & gt; = 48 & amp; & amp; event.charCode & lt; = 57" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')">
                                        @error('mobile')
                                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="store_email">{{ __('Store Email') }} <em style="color: red">*</em> </label>
                                        <input type="email" oninvalid="InvalidMsg(this);" name="store_email" id="store_email" class="form-control" value="{{ old('store_email', $row->email) }}" oninput="InvalidMsg(this);" required="required">
                                        @error('store_email')
                                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="company_website">{{ __('Company Website') }} <em style="color: red">*</em> </label>
                                        <input type="text" oninvalid="InvalidMsg(this);" name="company_website" id="company_website" class="form-control" value="{{ old('company_website', $row->company_website) }}" oninput="InvalidMsg(this);" required="required">
                                        @error('company_website')
                                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="social_media">{{ __('Social media Facebook/Instagram') }} <em style="color: red">*</em> </label>
                                        <input type="text" oninvalid="InvalidMsg(this);" name="social_media" id="social_media" class="form-control" value="{{ old('social_media', $row->social_media) }}" oninput="InvalidMsg(this);" required="required">
                                        @error('social_media')
                                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                        @enderror
                                    </div>
                                </div>
                                @php
                                $country = old('country', $row->country_id);
                                @endphp
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="country">{{ __('Country') }} <em style="color: red">*</em> </label>
                                        @foreach ($countries as $r)
                                        @if($r->is_default=='Yes')
                                        <input type="text" name="country-name" id="country-name" class="form-control" value="{{ old('country-name',$r->title) }}" readonly>
                                        <input type="hidden" name="country" id="country" class="form-control" value="{{ old('country',$r->id) }}" >
                                        @endif
                                        @endforeach
                                        @error('country')
                                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="state">{{ __('State') }} <em style="color: red">*</em> </label>
                                        <select name="state" id="state" class="form-control @error('state') is-invalid @enderror" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')">
                                            @foreach ($states as $r)
                                            <option value="{{ $r->title }}" {{ ($row->state == $r->title)? 'Selected':'' }}>{{ $r->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="city">{{ __('City') }} <em style="color: red">*</em> </label>
                                        <select name="city" id="city" class="form-control @error('city') is-invalid @enderror" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')">
                                            @foreach ($cities as $r)
                                            <option value="{{ $r->title }}" {{ ($row->city == $r->title)? 'Selected':'' }}>{{ $r->title }}</option>
                                            @endforeach
                                        </select>
                                        @error('city')
                                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="address">{{ __('Address') }} <em style="color: red">*</em> </label>
                                        <input type="text" name="address" id="address" class="form-control" value="{{ old('address', $row->address) }}" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')">
                                        @error('address')
                                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="zipcode">{{ __('Postal Code') }} <em style="color: red">*</em> </label>
                                        <input type="text" name="zipcode" id="zipcode" class="form-control" value="{{ old('zipcode', $row->zipcode) }}" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')">
                                        @error('zipcode')
                                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="commission">{{ __('Commission') }}</label>
                                        <input type="text" name="commission" id="commission" class="form-control number" value="{{ old('commission', $row->commission) }}">
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="bank_name">{{ __('Bank Name') }}</label>
                                        <input type="text" name="bank_name" id="bank_name" class="form-control" value="{{ old('bank_name', $row->bank_name) }}" >
                                        @error('bank_name')
                                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="account_title">{{ __('Account Name/Tittle') }}</label>
                                        <input type="text" name="account_title" id="account_title" class="form-control" value="{{ old('account_title', $row->account_title) }}" >
                                        @error('account_title')
                                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="iban">{{ __('Account IBAN') }}</label>
                                        <input type="text" name="iban" id="iban" class="form-control" value="{{ old('iban', $row->iban) }}" maxlength="36">
                                        @error('iban')
                                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="identity_no">{{ __('Identity Number') }}</label>
                                        <input type="text" name="identity_no" id="identity_no" class="form-control" value="{{ old('identity_no', $row->identity_no) }}"  oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')">
                                        @error('identity_no')
                                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="tax_office">{{ __('Tax Office') }} <em style="color: red">*</em> </label>
                                    <input type="text" name="tax_office" id="tax_office" class="form-control" value="{{ old('tax_office', $row->tax_office) }}"  oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')">
                                    @error('tax_office')
                                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                    @enderror
                                </div>
                            </div>
                            </div>
                           
                            <div class="row" id="tax_info_box" style="display: {{ $type == 0 ? 'none' : '' }}">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="tax_id">{{ __('Tax ID') }}</label>
                                        <input type="text" name="tax_id" id="tax_id" class="form-control" value="{{ old('tax_id', $row->tax_id) }}" oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')">
                                        @error('tax_id')
                                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="tax_info_box">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="signature">{{ __('Company Signature Circular') }}</label>
                                        <input type="text" name="signature" id="signature" class="form-control" value="{{ old('signature', $row->signature) }}" >
                                        @error('signature')
                                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="goods_services">{{ __('goods/services to be provided') }}</label>
                                        <input type="text" name="goods_services" id="goods_services" class="form-control" value="{{ old('goods_services', $row->goods_services) }}" required>
                                        @error('goods_services')
                                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="seller_id">{{ __('Trendyol Seller Id') }}</label>
                                        <input type="text" name="seller_id" id="seller_id" class="form-control" value="{{ old('seller_id', $row->seller_id) }}" >
                                        @error('seller_id')
                                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="trendyol_approved">{{ __('Trendyol Approved') }}</label>
                                        <select name="trendyol_approved" id="trendyol_approved" class="custom-select">
                                            <option value="0" @if ($row->trendyol_approved == 0) selected @endif>{{ __('Unapproved') }}</option>
                                            <option value="1" @if ($row->trendyol_approved == 1) selected @endif>{{ __('Approved') }}</option>
                                        </select>
                                        @error('trendyol_approved')
                                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="tax_info_box">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="trendyol_key">{{ __('Trendyol Key') }}</label>
                                        <input type="text" name="trendyol_key" id="Trendyol Key" class="form-control" value="{{ old('trendyol_key', $row->trendyol_key) }}" >
                                        @error('trendyol_key')
                                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="trendyol_secret">{{ __('Trendyol Secret') }}</label>
                                        <input type="text" name="trendyol_secret" id="trendyol_secret" class="form-control" value="{{ old('trendyol_secret', $row->trendyol_secret) }}" >
                                        @error('trendyol_secret')
                                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                        @enderror
                                    </div>
                                </div>
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
