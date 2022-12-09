@extends('seller.layouts.app')

@section('content')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item active">{{ __($title) }}</li>
</ol>
<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header">
                        {{ __($title) }}
                    </div>
                    <div class="card-body">
                        <div class="container">
                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#home" class="active"> {{__('Store')}} </a></li>
                                <li><a data-toggle="tab" href="#menu1"> {{__('Store Data')}} </a></li>
                            </ul>
                            <div class="tab-content">
                                <div id="home" class="tab-pane fade in active show">
                                    <!-- alerts -->
                                    <x-alert class="alert-success" :status="session('success')" />
                                    <x-alert class="alert-danger" :status="session('error')" />
                                    <form action="{{ route('seller.store.store-detail-update', ['store' => $row->id]) }}" enctype="multipart/form-data" method="POST" id="edit_form">
                                        @csrf
                                        @php
                                        $banner = $storeData ? $storeData->banner : '';
                                        $banner_store = $storeData ? $storeData->banner : '';
                                        $thumbnail = $storeData ? $storeData->getMedia('banner', 'thumbnail') : '';
                                        $thumbnail_store = isset($storeData->banner) ? $storeData->getMedia('banner', 'thumbnail') : '' ;
                                        $logo = $storeData ? $storeData->logo : '';
                                        $logo_store = $storeData ? $storeData->logo : '';
                                        $logo_thumbnail = isset($storeData->logo) ? $storeData->getMedia('logo', 'thumbnail')  : '' ;
                                        $logo_thumbnail_store = isset($storeData->logo) ? $storeData->getMedia('logo', 'thumbnail') : '' ;
                                        if($logo == null){
                                        $logo = $logo_store;
                                        $logo_thumbnail = $logo_thumbnail_store;
                                        }
                                        if($banner == null){
                                        $banner = $banner_store;
                                        $thumbnail = $thumbnail_store;
                                        }

                                        $legal_papers = $row ? $row->legal_papers : '';
                                        @endphp
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="name">{{ __('Name') }}</label>
                                                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $row->name) }}" required>
                                                    @error('name')
                                                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="legal_name">{{ __('Legal Name') }}</label>
                                                    <input type="text" name="legal_name" id="legal_name" class="form-control" value="{{ old('legal_name', $row->legal_name) }}" required>
                                                    @error('legal_name')
                                                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <!-- file upload -->
                                                    <x-admin.file-upload :name="'banner'" :label="__('Banner')" :file="$banner" :thumbnail="$thumbnail" />
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <!-- file upload -->
                                                    <x-admin.file-upload :name="'logo'" :label="__('Logo')" :file="$logo" :thumbnail="$logo_thumbnail" />
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="phone">{{ __('Office Phone') }}</label>
                                                    <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $row->phone) }}" required>
                                                    @error('phone')
                                                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="mobile">{{ __('Mobile Authorized Person') }}</label>
                                                    <input type="text" name="mobile" id="mobile" class="form-control" value="{{ old('mobile', $row->mobile) }}" required>
                                                    @error('mobile')
                                                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="store_email">{{ __('Store Email') }}</label>
                                                    <input type="email" name="store_email" id="store_email" class="form-control" value="{{ old('store_email', $row->email) }}" required>
                                                    @error('store_email')
                                                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="company_website">{{ __('Company Website') }}</label>
                                                    <input type="link" name="company_website" id="company_website" class="form-control" value="{{ old('company_website', $row->company_website) }}" required>
                                                    @error('company_website')
                                                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="social_media">{{ __('Social media Facebook/Instagram') }}</label>
                                                    <input type="link" name="social_media" id="social_media" class="form-control" value="{{ old('social_media', $row->social_media) }}" required>
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
                                                    <label for="country">{{ __('Country') }}</label>
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
                                                    <label for="state">{{ __('State') }}</label>
                                                    <select name="state" id="state" class="form-control @error('state') is-invalid @enderror" required oninvalid="this.setCustomValidity('{{__(`Please fill out this field`)}}')"  oninput="this.setCustomValidity('')">
                                                        @foreach ($states as $r)
                                                        <option value="{{ $r->title }}" {{ ($row->state == $r->title)? 'Selected':'' }}>{{ $r->title }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="city">{{ __('City') }}</label>
                                                    <select name="city" id="city" class="form-control @error('city') is-invalid @enderror" required oninvalid="this.setCustomValidity('{{__(`Please fill out this field`)}}')"  oninput="this.setCustomValidity('')">
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
                                                    <label for="address">{{ __('Address') }}</label>
                                                    <input type="text" name="address" id="address" class="form-control" value="{{ old('address', $row->address) }}" required>
                                                    @error('address')
                                                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="zipcode">{{ __('Zipcode') }}</label>
                                                    <input type="text" name="zipcode" id="zipcode" class="form-control" value="{{ old('zipcode', $row->zipcode) }}" required>
                                                    @error('zipcode')
                                                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="bank_name">{{ __('Bank Name') }}</label>
                                                    <input type="text" name="bank_name" id="bank_name" class="form-control" value="{{ old('bank_name', $row->bank_name) }}">
                                                    @error('bank_name')
                                                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="account_title">{{ __('Account Name/Tittle') }}</label>
                                                    <input type="text" name="account_title" id="account_title" class="form-control" value="{{ old('account_title', $row->account_title) }}">
                                                    @error('account_title')
                                                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="iban">{{ __('Account IBAN') }}</label>
                                                    <input type="text" name="iban" id="iban" class="form-control" value="{{ old('iban', $row->iban) }}">
                                                    @error('iban')
                                                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="identity_no">{{ __('Identity Number') }}</label>
                                                    <input type="text" name="identity_no" id="identity_no" class="form-control" value="{{ old('identity_no', $row->identity_no) }}" >
                                                    @error('identity_no')
                                                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" id="tax_info_box">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="tax_office">{{ __('Tax Office') }}</label>
                                                    <input type="text" name="tax_office" id="tax_office" class="form-control" value="{{ old('tax_office', $row->tax_office) }}" >
                                                    @error('tax_office')
                                                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="tax_id">{{ __('Tax ID') }}</label>
                                                    <input type="text" name="tax_id" id="tax_id" class="form-control" value="{{ old('tax_id', $row->tax_id) }}" >
                                                    @error('tax_id')
                                                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="tax_id_type">{{ __('Tax ID Type') }}</label>
                                                    <input type="text" name="tax_id_type" id="tax_id_type" class="form-control" value="{{ old('tax_id_type', $row->tax_id_type) }}" >
                                                    @error('tax_id_type')
                                                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <!-- file upload -->
                                                    <x-admin.file-image-upload :name="'legal_papers'" :label="__('Company Legal Papers')" :file="$legal_papers" :thumbnail="$legal_papers" />
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
                                <div id="menu1" class="tab-pane fade">
                                    <!-- alerts -->
                                    <x-alert class="alert-success" :status="session('success')" />
                                    <x-alert class="alert-danger" :status="session('error')" />
                                    @if ($storeData && $storeData->is_rejected())
                                    <x-alert class="alert-danger" :status="__('These changes are rejected by admin, please review & submit again.')" />
                                    @endif
                                    @if ($storeData && $storeData->is_pending())
                                    <x-alert class="alert-info" :status="__('Your changes are currently in waiting for approval.')" />
                                    @endif
                                    <form action="{{ route('seller.store.profile') }}" method="POST" id="edit_form" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="data_changed" id="data_changed" value="no">
                                        @php
                                        $description = $storeData ? $storeData->description : '';
                                        $return_and_refunds = $storeData ? $storeData->return_and_refunds : '';
                                        $policies = $storeData ? $storeData->policies : '';
                                        $name = $row ? $row->name : '';
                                        @endphp
                                        @foreach(getLocaleList() as $val)
                                        @php
                                        if($row->store_profile_translate){
                                        foreach($row->store_profile_translate as $r){
                                        if($val->code == $r->language_code){
                                        $name = $r->name;
                                        }
                                        }
                                        }
                                        @endphp
                                        <div class="form-group">
                                            <label for="name">{{ __('Name') }} {{$val->native}}</label>
                                            <input name="name[{{$val->id}}]" id="name{{$val->id}}" class="form-control TinyEditor" value="{{ old('name[$val->id]', $name) }}" />
                                            @error('name[{{$val->id}}]')
                                            <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                            @enderror
                                        </div>
                                        @endforeach
                                        @foreach(getLocaleList() as $val)
                                        @php
                                        if($row->store_profile_translate){
                                        foreach($row->store_profile_translate as $r){
                                        if($val->code == $r->language_code){
                                        $description = $r->description;
                                        }
                                        }
                                        }
                                        @endphp
                                        <div class="form-group">
                                            <label for="description">{{ __('Description') }} {{$val->native}}</label>
                                            <textarea name="description[{{$val->id}}]" id="description{{$val->id}}" cols="30" rows="5" class="form-control TinyEditor">{!! old('description['.$val->id.']', $description) !!}</textarea>
                                            @error('description[{{$val->id}}]')
                                            <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                            @enderror
                                        </div>
                                        @endforeach
                                        @foreach(getLocaleList() as $val)
                                        @php
                                        if($row->store_profile_translate){
                                        foreach($row->store_profile_translate as $r){
                                        if($val->code == $r->language_code){
                                        $policies = $r->policies;
                                        }
                                        }
                                        }
                                        @endphp
                                        <div class="form-group">
                                            <label for="policies">{{ __('Policies') }} {{$val->native}}</label>
                                            <textarea name="policies[{{$val->id}}]" id="policies{{$val->id}}" cols="30" rows="5" class="form-control TinyEditor">{!! old('policies['.$val->id.']', $policies) !!}</textarea>
                                            @error('policies[{{$val->id}}]')
                                            <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                            @enderror
                                        </div>
                                        @endforeach
                                        <!-- seo fields -->
                                        <x-seo-store-form-fields :row="$storeData"/>
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
        </div>
    </div>
</div>
@endsection

@push('footer')
<x-validation />
<x-validation-password />
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/tinymce/5.4.2/tinymce.min.js"></script>
<script type="text/javascript">
tinymce.init({
    selector: 'textarea.TinyEditor',
    height: 300,
    menubar: false,
    plugins: [
        'advlist autolink lists link image charmap print preview anchor',
        'searchreplace visualblocks code fullscreen',
        'insertdatetime media table paste code help wordcount'
    ],
    toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link unlink | image media | preview code",
    setup: function (ed) {
        ed.on("change", function () {
            tinymceChanged(ed);
        })
    }
});
$(document).ready(function () {
    $("#edit_form").validate();
    $('input, textarea').change(function () {
        $('#data_changed').val('yes');
    });
});
function tinymceChanged(inst) {
    $('#data_changed').val('yes');
}
</script>
<script type="text/javascript" charset="utf-8">
    $(document).ready(function () {
        $("#edit_form").validate({
            rules: {
                password: {
                    passwordcheck: true,
                    minlength: 6,
                    maxlength: 20
                }
            }
        });
    });
</script>
@endpush
