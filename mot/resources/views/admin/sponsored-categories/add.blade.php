@extends('admin.layouts.app')

@section('content')
 <style>
    .modal-backdrop{
        display: none !important;
    }
</style>
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.sponsored.categories') }}">{{ __($section_title) }}</a></li>
    <li class="breadcrumb-item active">{{ __($title) }}</li>
</ol>
<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        {{ __($title) }}
                        <x-admin.back-button :url="route('admin.sponsored.categories')" />
                    </div>
                    <div class="card-body">
                        <!-- alerts -->
                        <x-alert class="alert-success" :status="session('success')" />
                        <x-alert class="alert-danger" :status="session('error')" />
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <form action="{{ route('admin.sponsored.categories.add') }}" method="POST" enctype="multipart/form-data" id="add_form">
                            @csrf
                            <div class="form-group">
                                <label for="title">{{ __('Section Title') }}</label>
                                <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')" autofocus>
                                @error('title')
                                <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                @enderror
                            </div>
                            @for ($i = 1; $i <= 3; $i++)
                            <h4>{{ __('Box') }} {{ $i }}</h4>
                            @foreach(getLocaleList() as $val)
                            <div class="form-group">
                              <label for="button_text">{{ __('Title ('.$val->title.')') }}</label>
                              <input type="text" name="categories[{{$i}}][title][{{$val->id}}]" id="title_{{ $i }}_{{$val->id}}" class="form-control" value="{{ old('categories.'.$i.'.title'.$val->id) }}">
                            </div>
                            @endforeach
                            @foreach(getLocaleList() as $val)
                            <div class="form-group">
                              <label for="button_text">{{ __('Button Text ('.$val->title.')') }}</label>
                              <input type="text" name="categories[{{$i}}][button_text][{{$val->id}}]" id="button_text_{{ $i }}_{{$val->id}}" class="form-control" value="{{ old('categories.'.$i.'.button_text'.$val->id) }}">
                            </div>
                            @endforeach
<!--                            <div class="form-group">
                                <label for="title_{{ $i }}">{{ __('Title') }}</label>
                                <input type="text" name="categories[{{$i}}][title]" id="title_{{ $i }}" class="form-control" value="{{ old('categories.'.$i.'.title') }}">
                            </div>-->
                           
<!--                            <div class="form-group">
                                <label for="button_text_{{ $i }}">{{ __('Button Text') }}</label>
                                <input type="text" name="categories[{{$i}}][button_text]" id="button_text_{{ $i }}" class="form-control" value="{{ old('categories.'.$i.'.button_text') }}">
                            </div>-->
                            <div class="form-group">
                                <label for="button_url_{{ $i }}">{{ __('Button URL') }}</label>
                                <input type="text" name="categories[{{$i}}][button_url]" id="button_url_{{ $i }}" class="form-control" value="{{ old('categories.'.$i.'.button_url') }}"  oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')">
                            </div>
                            <!-- file upload -->
                            @php
                            $field_name = "categories[$i][image]";
                            @endphp
                            <!-- file upload -->
                            @foreach(getLocaleList() as $val)
                            <div class="form-group">
                                <input type="hidden" name="new_crop_{{ $name ?? '' }}" class="new_crop_image">
                                <label>{{ __('Image ('.$val->title.')') }}</label>
                                <div class="main-img-preview mb-2" style="display: none;">
                                    <img class="img-thumbnail img-preview" src="">
                                </div>
                                <div>
                                    <span class="input-group-prepend">
                                        <button class="btn btn-primary btn-sm ps-trigger-file" type="button" style="display: {{  'block' }};"><i class="fa fa-upload mr-1"></i> {{ __('Upload') }}</button>
                                        <input name="categories[{{$i}}][image][{{$val->id}}]" type="file" class="ps-file-input d-none" id="fUpload_{{ $i }}_{{$val->id}}" onchange="ValidateSingleInput(this);" accept=".jpg,.jpeg,.png,.gif,.tif,.bmp,.svg" >
                                        <button onclick="ResetFile(this)" class="remove_img btn btn-danger btn-sm" type="button" style="display: {{  'none' }};"><i class="fa fa-times mr-1"></i> {{ __('Remove') }}</button>
                                    </span>
                                </div>
                                @error($name ?? '')
                                <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                @enderror
                            </div>
                            @endforeach
                            <!--<x-admin.file-upload :name="$field_name" />-->
                            @endfor
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
@php
$media_size = collect(config('media.sizes.sponsor_category'))->first();
@endphp
@include('admin.includes.crop-modal', ['width' => $media_size['width'], 'height' => $media_size['height'], 'ratio' => $media_size['ratio']])
@endsection

@push('footer')
<x-validation />
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
