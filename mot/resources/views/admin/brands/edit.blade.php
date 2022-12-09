@extends('admin.layouts.app')

@section('content')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.brands') }}">{{ $section_title }}</a></li>
    <li class="breadcrumb-item active">{{ $title }}</li>
</ol>
<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        {{ $title }}
                        <x-admin.back-button :url="route('admin.brands')" />
                    </div>
                    <div class="card-body">
                        <!-- alerts -->
                        <x-alert class="alert-success" :status="session('success')" />
                        <x-alert class="alert-danger" :status="session('error')" />
                        <form action="{{ route('admin.brands.edit', ['brand' => $row->id]) }}" method="POST" enctype="multipart/form-data" id="edit_form">
                            @csrf
                            @foreach(getLocaleList() as $val)
                            @php
                                $ctitle= $row->title;
                                if($row->brand_translate){
                                    foreach($row->brand_translate as $r){
                                        if($val->code == $r->language_code){
                                        $ctitle = $r->title;
                                        }
                                    }
                                }
                            @endphp
                            <div class="form-group">
                                <label for="title">{{ __('Title ('.$val->title.')') }}</label>
                                <input type="text" name="title[{{$val->id}}]" id="title{{$val->id}}" class="form-control" value="{{ old('title[$val->id]', $ctitle) }}" {{$val->is_default == 'Yes' ? 'required':'' }} oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')">
                                @error('title[{{$val->id}}]')
                                <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                @enderror
                            </div>
                            @endforeach
                            <div class="form-group">
                                <label for="slug">{{ __('Slug') }}</label>
                                <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', $row->slug )}}" >
                                @error('slug')
                                <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                @enderror
                            </div>
                            @php
                                $store = old('store', $row->store_id);
                              @endphp
                              <div class="col-sm-4" id="store_box" >
                                <div class="form-group">
                                  <label for="store">{{ __('Specific Store') }}</label>
                                  <select name="store" id="store" class="custom-select" onchange="calculateMotFee()" >
                                    <option value="">--{{ __('select') }}--</option>
                                    @foreach ($stores as $r)
                                    <option value="{{ $r->id }}" @if ( $store == $r->id ) selected @endif >{{isset($r->store_profile_translates)? $r->store_profile_translates->name : $r->name}}</option>
                                    @endforeach
                                  </select>
                                  @error('store')
                                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                  @enderror
                                </div>
                              </div>
                             <!-- file upload -->
                            <x-admin.file-upload :name="'image'" :file="$row->image" :thumbnail="$row->getMedia('image', 'thumbnail')" />

                            @foreach(getLocaleList() as $val)
                            @php
                                $cdata= $row->data;
                                if($row->brand_translate){
                                    foreach($row->brand_translate as $r){
                                        if($val->code == $r->language_code){
                                        $cdata = $r->data;
                                        }
                                    }
                                }
                            @endphp
                            <div class="form-group">
                                <label for="data">{{ __('Description ('.$val->title.')') }}</label>
                                <textarea name="data[{{$val->id}}]" id="data{{$val->id}}" cols="30" rows="5" class="form-control TinyEditor">{!! old('data[$val->id]', $cdata) !!}</textarea>
                            </div>
                            @endforeach
                           <!-- seo fields -->
                            <x-seo-brand-form-fields :row="$row" />
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
