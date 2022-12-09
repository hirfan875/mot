@extends('admin.layouts.app')

@section('content')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.categories') }}">{{ $section_title }}</a></li>
    <li class="breadcrumb-item active">{{ $title }}</li>
</ol>
<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        {{ $title }}
                        <x-admin.back-button :url="route('admin.categories')" />
                    </div>
                    <div class="card-body">
                        <!-- alerts -->
                        <x-alert class="alert-success" :status="session('success')" />
                        <x-alert class="alert-danger" :status="session('error')" />
                        <form action="{{ route('admin.categories.add') }}" method="POST" enctype="multipart/form-data" id="add_form">
                            @csrf
                            <!-- file upload -->
                            @foreach(getLocaleList() as $row)
                            <div class="form-group">
                                <label for="title">{{ __('Title ('.$row->title.')') }}</label>
                                <input type="text" name="title[{{$row->id}}]" id="title{{$row->id}}" class="form-control" value="{{ old('title'.$row->id) }}" {{$row->is_default == 'Yes' ? 'required':'' }} oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')" autofocus>
                                @error('title[{{$row->id}}]')
                                <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                @enderror
                            </div>
                            @endforeach
                            @php
                            $parent_id = old('parent_id')
                            @endphp
                            <div class="form-group">
                                <label for="parent_id">{{ __('Parent Category') }}</label>
                                <select name="parent_id" id="parent_id" class="custom-select">
                                    <option value="">--{{ __('none') }}--</option>
                                    @foreach ($categories as $r)
                                    <option value="{{ $r->id }}" @if ($r->id == $parent_id) selected @endif>{{ $r->title }}</option>
                                    @include('admin.includes.subcategories-options', ['subcategories' => $r->subcategories, 'level' => 1, 'parent_id' => $parent_id]);
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="commission">{{ __('Commission') }}</label>
                                <input type="text" name="commission" id="commission" class="form-control number" value="{{ old('commission') }}">
                                @error('commission')
                                <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="google_category">{{ __('Google Category') }}</label>
                                <input type="text" name="google_category" id="google_category" class="form-control" value="{{ old('google_category') }}">
                                @error('google_category')
                                <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                @enderror
                            </div>
                            <!-- file upload -->
                            @foreach(getLocaleList() as $val)
                                <div class="form-group">
                                    <input type="hidden" name="new_crop_image[{{$val->id}}]" class="new_crop_image">
                                    <label>{{ __('Image ('.$val->title.')') }}</label>
                                    <div class="main-img-preview mb-2" style="display: none;">
                                        <img class="img-thumbnail img-preview" src="">
                                    </div>
                                    <div>
                                        <span class="input-group-prepend">
                                            <button class="btn btn-primary btn-sm ps-trigger-file" type="button" style="display: {{  'block' }};"><i class="fa fa-upload mr-1"></i> {{ __('Upload') }}</button>
                                            <input name="image[{{$val->id}}]" type="file" class="ps-file-input d-none" id="fUpload" onchange="ValidateSingleInput(this);" accept=".jpg,.jpeg,.png,.gif,.tif,.bmp,.svg" >
                                            <button onclick="ResetFile(this)" class="remove_img btn btn-danger btn-sm" type="button" style="display: {{  'none' }};"><i class="fa fa-times mr-1"></i> {{ __('Remove') }}</button>
                                        </span>
                                    </div>
                                    @error($name ?? '')
                                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                    @enderror
                                </div>
                            @endforeach
                            <!-- banner upload -->
                            @foreach(getLocaleList() as $val)
                                <div class="form-group">
                                    <input type="hidden" name="new_crop_banner[{{$val->id}}]" class="new_crop_image">
                                    <label>{{ __('Banner ('.$val->title.')') }}</label>
                                    <div class="main-img-preview mb-2" style="display: none;">
                                        <img class="img-thumbnail img-preview" src="">
                                    </div>
                                    <div>
                                        <span class="input-group-prepend">
                                            <button class="btn btn-primary btn-sm ps-trigger-file" type="button" style="display: {{  'block' }};"><i class="fa fa-upload mr-1"></i> {{ __('Upload') }}</button>
                                            <input name="banner[{{$val->id}}]" type="file" class="ps-file-input d-none" id="fUpload" onchange="ValidateSingleInput(this);" accept=".jpg,.jpeg,.png,.gif,.tif,.bmp,.svg" >
                                            <button onclick="ResetFile(this)" class="remove_img btn btn-danger btn-sm" type="button" style="display: {{  'none' }};"><i class="fa fa-times mr-1"></i> {{ __('Remove') }}</button>
                                        </span>
                                    </div>
                                    @error($name ?? '')
                                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                    @enderror
                                </div>
                            @endforeach
                            <!-- file upload -->
                            <!--<x-admin.file-upload :name="'image'" />-->
                            <!-- banner upload -->
                            <!--<x-admin.file-upload :name="'banner'" :label="__('Banner')"/>-->


                            @foreach(getLocaleList() as $row)
                                <div class="form-group">
                                    <label for="data">{{ __('Description ('.$row->title.')') }}</label>
                                    <textarea name="data[{{$row->id}}]" id="data{{$row->id}}" cols="30" rows="5" class="form-control TinyEditor">{{ old('data'.$row->id) }}</textarea>
                                </div>
                            @endforeach
                            <!-- seo fields -->
                            <x-seo-category-form-fields />
                            <div class="text-center">
                                <!-- submit button -->
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
<x-tinymce />

<script type="text/javascript" charset="utf-8">

    var _validFileExtensions = [".jpg", ".jpeg", ".png", ".gif", ".tif", ".bmp", ".svg"];
    function ValidateSingleInput(oInput) {
        if (oInput.type == "file") {
            var sFileName = oInput.value;
            if (sFileName.length > 0) {
                var blnValid = false;
                for (var j = 0; j < _validFileExtensions.length; j++) {
                    var sCurExtension = _validFileExtensions[j];
                    if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
                        blnValid = true;
                        break;
                    }
                }

                if (!blnValid) {
                    alert("Sorry, " + sFileName + " is invalid, allowed extensions are: " + _validFileExtensions.join(", "));
                    oInput.value = "";
                    $('#cropImageModal').addClass('d-none');
                    $(".ps-file-input").load(location.href + " .ps-file-input");
                    return false;
                }
            }
        }
        $('#cropImageModal').removeClass('d-none');
        return true;
    }
</script>
@endpush
