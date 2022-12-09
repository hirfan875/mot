@extends('admin.layouts.app')

@section('content')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.stores') }}">{{ __($store->name) }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.stores.profile', ['store' => $store->id]) }}">{{ __($section_title) }}</a></li>
    <li class="breadcrumb-item active">{{ __($title) }}</li>
  </ol>
  <div class="container-fluid">
    <div class="animated fadeIn">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              {{ __($title) }}
              <x-admin.back-button :url="route('admin.stores.profile', ['store' => $store->id])" />
            </div>
            <div class="card-body">
              <!-- alerts -->
              <x-alert class="alert-success" :status="session('success')" />
              <x-alert class="alert-danger" :status="session('error')" />
              <form action="{{ route('admin.stores.profile.edit', ['store' => $store->id, 'item' => $row->id]) }}" enctype="multipart/form-data" method="POST" id="edit_form">
                @csrf
                <!-- file upload -->
                <x-admin.file-upload :name="'banner'" :label="__('Banner')" :file="$row->banner" :thumbnail="$row->getMedia('banner', 'thumbnail')" />
                <!-- file upload -->
                <x-admin.file-upload :name="'logo'" :label="__('Logo')" :file="$row->logo" :thumbnail="$row->getMedia('logo', 'thumbnail')" />
               @foreach(getLocaleList() as $val)
                    @php
                        $name = $store->name;
                        if($row->store->store_profile_translate){
                            foreach($row->store->store_profile_translate as $r){
                                if($val->code == $r->language_code){
                                $name = $r->name;
                                }
                            }
                        }
                    @endphp
                <div class="form-group">
                  <label for="name">{{ __('Name ('.$val->title.')') }}</label>
                  <input name="name[{{$val->id}}]" id="name{{$val->id}}" class="form-control TinyEditor" value="{{ old('name[$val->id]', $name) }}" />
                  @error('name[{{$val->id}}]')
                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                  @enderror
                </div>
                @endforeach
                @foreach(getLocaleList() as $val)
                    @php
                        $description = $row->description;
                        if($row->store->store_profile_translate){
                            foreach($row->store->store_profile_translate as $r){
                                if($val->code == $r->language_code){
                                $description = $r->description;
                                }
                            }
                        }
                    @endphp
                <div class="form-group">
                  <label for="description">{{ __('Description ('.$val->title.')') }}</label>
                  <textarea name="description[{{$val->id}}]" id="description{{$val->id}}" cols="30" rows="5" class="form-control TinyEditor">{!! old('description['.$val->id.']', $description) !!}</textarea>
                  @error('description[{{$val->id}}]')
                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                  @enderror
                </div>
                @endforeach
                @foreach(getLocaleList() as $val)
                    @php
                        $policies = $row->policies;
                        if($row->store->store_profile_translate){
                            foreach($row->store->store_profile_translate as $r){
                                if($val->code == $r->language_code){
                                $policies = $r->policies;
                                }
                            }
                        }
                    @endphp
                <div class="form-group">
                  <label for="policies">{{ __('Policies ('.$val->title.')') }}</label>
                  <textarea name="policies[{{$val->id}}]" id="policies{{$val->id}}" cols="30" rows="5" class="form-control TinyEditor">{!! old('policies['.$val->id.']', $policies) !!}</textarea>
                  @error('policies[{{$val->id}}]')
                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                  @enderror
                </div>
                @endforeach
                <!-- seo fields -->
                    <x-seo-store-form-fields :row="$row"/>
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
  <x-tinymce />
  <script type="text/javascript" charset="utf-8">
    $(document).ready(function(){
      $("#edit_form").validate();
    });
  </script>
@endpush
