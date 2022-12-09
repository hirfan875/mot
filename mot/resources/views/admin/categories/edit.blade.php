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
                        <form action="{{ route('admin.categories.edit', ['category' => $row->id]) }}" method="POST" enctype="multipart/form-data" id="edit_form">
                            @csrf
                            @foreach(getLocaleList() as $val)
                                @php
                                $ctitle= $row->title;
                                if($row->category_translate){
                                    foreach($row->category_translate as $r){
                                        if($val->code == $r->language_code){
                                            $ctitle = $r->title;
                                        }
                                    }
                                }
                                @endphp
                                <div class="form-group">
                                    <label for="title">{{ __('Title ('.$val->title.')') }}</label>
                                    <input type="text" name="title[{{$val->id}}]" id="title{{$val->id}}" class="form-control" value="{{ old('title'.$val->id, $ctitle) }}" {{$val->is_default == 'Yes' ? 'required':'' }} oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')">
                                    @error('title[{{$val->id}}]')
                                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                    @enderror
                                </div>
                            @endforeach
                            <div class="form-group">
                                <label for="slug">{{ __('Slug') }}</label>
                                <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', $row->slug) }}" >
                                @error('slug')
                                <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                @enderror
                            </div>
                            @php
                            $parent_id = old('parent_id', $row->parent_id)
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
                                <input type="text" name="commission" id="commission" class="form-control number" value="{{ old('commission', $row->commission) }}">
                                @error('commission')
                                <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="google_category">{{ __('Google Category') }}</label>
                                <input type="text" name="google_category" id="google_category" class="form-control" value="{{ old('google_category', $row->google_category) }}">
                                @error('google_category')
                                <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                @enderror
                            </div>
                            <!-- file upload -->
                            @foreach(getLocaleList() as $val)
                                <div class="form-group">
                                    <input type="hidden" name="new_crop_{{ $name ?? '' }}" class="new_crop_image">
                                    <label>{{ __('Image ('.$val->title.')') }}</label>
                                    <div class="main-img-preview mb-2" style="display:block">
                                        @if($row->category_translate)
                                        @foreach($row->category_translate as $r)
                                        @if($val->id == $r->language_id)
                                        <img class="img-thumbnail img-preview" src="{{ asset($r->getMedia('image', 'thumbnail')) }}">
                                        @endif
                                        @endforeach
                                        @else
                                        <img class="img-thumbnail img-preview" src="{{ asset($row->getMedia('image', 'thumbnail')) }}">
                                        @endif
                                    </div>
                                    <div>
                                        <span class="input-group-prepend">
                                            <button class="btn btn-primary btn-sm ps-trigger-file" type="button" style="display: block;"><i class="fa fa-upload mr-1"></i> {{ __('Upload') }}</button>
                                            <input name="image[{{$val->id}}]" type="file" class="ps-file-input d-none" id="fUpload" onchange="ValidateSingleInput(this);" accept=".jpg,.jpeg,.png,.gif,.tif,.bmp,.svg" >
                                            <button onclick="ResetFile(this)" class="remove_img btn btn-danger btn-sm" type="button" style="display: none;"><i class="fa fa-times mr-1"></i> {{ __('Remove') }}</button>
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
                                    <input type="hidden" name="new_crop_{{ $name ?? '' }}" class="new_crop_image">
                                    <label>{{ __('Banner ('.$val->title.')') }}</label>
                                    <div class="main-img-preview mb-2" style="display:block">
                                        @if($row->category_translate)
                                        @foreach($row->category_translate as $r)
                                        @if($val->id == $r->language_id)
                                        <img class="img-thumbnail img-preview" src="{{ asset($r->getMedia('banner', 'thumbnail')) }}">
                                        @endif
                                        @endforeach
                                        @else
                                        <img class="img-thumbnail img-preview" src="{{ asset($row->getMedia('banner', 'thumbnail')) }}">
                                        @endif
                                    </div>
                                    <div>
                                        <span class="input-group-prepend">
                                            <button class="btn btn-primary btn-sm ps-trigger-file" type="button" style="display: block;"><i class="fa fa-upload mr-1"></i> {{ __('Upload') }}</button>
                                            <input name="banner[{{$val->id}}]" type="file" class="ps-file-input d-none" id="fUpload" onchange="ValidateSingleInput(this);" accept=".jpg,.jpeg,.png,.gif,.tif,.bmp,.svg" >
                                            <button onclick="ResetFile(this)" class="remove_img btn btn-danger btn-sm" type="button" style="display: none;"><i class="fa fa-times mr-1"></i> {{ __('Remove') }}</button>
                                        </span>
                                    </div>
                                    @error($name ?? '')
                                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                    @enderror
                                </div>
                            @endforeach
                            <!--<x-admin.file-upload :name="'image'" :file="$row->image" :thumbnail="$row->getMedia('image', 'thumbnail')" />-->
                            <!-- banner upload -->
                            <!--<x-admin.file-upload :name="'banner'" :label="__('Banner')" :file="$row->banner" :thumbnail="$row->getMedia('banner', 'thumbnail')" />-->
                            @foreach(getLocaleList() as $val)
                            @php
                            $cdata= $row->data;
                            if($row->category_translate){
                                foreach($row->category_translate as $r){
                                    if($val->code == $r->language_code){
                                    $cdata = $r->data;
                                    }
                                }
                            }
                            @endphp
                            <div class="form-group">
                                <label for="data">{{ __('Description ('.$val->title.')') }}</label>
                                <textarea name="data[{{$val->id}}]" id="data{{$val->id}}" cols="30" rows="5" class="form-control TinyEditor">{{ old('data'.$val->id, $cdata) }}</textarea>
                            </div>
                            @endforeach
                            <!-- seo fields -->
                            <x-seo-category-form-fields :row="$row" />
                            <div class="text-center">
                                <!-- submit button -->
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
<x-tinymce />
@endpush
