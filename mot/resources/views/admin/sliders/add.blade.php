@extends('admin.layouts.app')

@section('content')
<style>
    .modal-backdrop{
        display: none !important;
    }
</style>
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.sliders') }}">{{ __($section_title) }}</a></li>
    <li class="breadcrumb-item active">{{ __($title) }}</li>
</ol>
<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        {{ __($title) }}
                        <x-admin.back-button :url="route('admin.sliders')" />
                    </div>
                    <div class="card-body">
                        <!-- alerts -->
                        <x-alert class="alert-success" :status="session('success')" />
                        <x-alert class="alert-danger" :status="session('error')" />
                        <form action="{{ route('admin.sliders.add') }}" method="POST" enctype="multipart/form-data" id="add_form">
                            @csrf
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
                                        <input name="image[{{$val->id}}]" type="file" class="ps-file-input d-none" id="fUpload" onchange="ValidateSingleInput(this);" accept=".jpg,.jpeg,.png,.gif,.tif,.bmp,.svg" >
                                        <button onclick="ResetFile(this)" class="remove_img btn btn-danger btn-sm" type="button" style="display: {{  'none' }};"><i class="fa fa-times mr-1"></i> {{ __('Remove') }}</button>
                                    </span>
                                </div>
                                @error($name ?? '')
                                <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                @enderror
                            </div>
                            @endforeach
                            @foreach(getLocaleList() as $val)
                            <div class="form-group">
                                <label for="button_text">{{ __('Button Text ('.$val->title.')') }}</label>
                                <input type="text" name="button_text[{{$val->id}}]" id="button_text{{$val->id}}" class="form-control" value="{{ old('button_text'.$val->id) }}">
                            </div>
                            @endforeach
                            <div class="form-group">
                                <label for="button_url">{{ __('Button URL') }}</label>
                                <input type="url" name="button_url" id="button_url" class="form-control" value="{{ old('button_url') }}" title="{{__('Please enter a valid url')}}">
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
<script type="text/javascript" charset="utf-8">
    $(document).ready(function () {
        $("#add_form").validate();
    });
</script>

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
