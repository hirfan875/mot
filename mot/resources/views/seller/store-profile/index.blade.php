@extends('seller.layouts.app')

@section('content')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item active">{{ __($title) }}</li>
  </ol>
  <div class="container-fluid">
    <div class="animated fadeIn">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              {{ __($title) }}
            </div>
            <div class="card-body">
              <!-- alerts -->
              <x-alert class="alert-success" :status="session('success')" />
              <x-alert class="alert-danger" :status="session('error')" />
              @if ($row && $row->is_rejected())
              <x-alert class="alert-danger" :status="__('These changes are rejected by admin, please review & submit again.')" />
              @endif
              @if ($row && $row->is_pending())
              <x-alert class="alert-info" :status="__('Your changes are currently in waiting for approval.')" />
              @endif
              <form action="{{ route('seller.store.profile') }}" method="POST" id="edit_form" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="data_changed" id="data_changed" value="no">
                @php
                  $banner = $row ? $row->banner : '';
                  $banner_store = $store->store_data ? $store->store_data->banner : '';
                  $thumbnail = $row ? $row->getMedia('banner', 'thumbnail') : '';
                  $thumbnail_store = isset($store->store_data->banner) ? $store->store_data->getMedia('banner', 'thumbnail') : '' ;
                  $logo = $row ? $row->logo : '';
                  $logo_store = $store->store_data ? $store->store_data->logo : '';
                  $logo_thumbnail = isset($row->logo) ? $row->getMedia('logo', 'thumbnail')  : '' ;
                  $logo_thumbnail_store = isset($store->store_data->logo) ? $store->store_data->getMedia('logo', 'thumbnail') : '' ;
                  $description = $row ? $row->description : '';
                  $return_and_refunds = $row ? $row->return_and_refunds : '';
                  $policies = $row ? $row->policies : '';
                  $name = $store ? $store->name : '';
                  if($logo == null){
                    $logo = $logo_store;
                    $logo_thumbnail = $logo_thumbnail_store;
                  }
                  if($banner == null){
                    $banner = $banner_store;
                    $thumbnail = $thumbnail_store;
                  }
                @endphp
<!--                 file upload 
                <x-admin.file-upload :name="'banner'" :label="__('Banner')" :file="$banner" :thumbnail="$thumbnail" />
                 file upload 
                <x-admin.file-upload :name="'logo'" :label="__('Logo')" :file="$logo" :thumbnail="$logo_thumbnail" />-->
               @foreach(getLocaleList() as $val)
                    @php
                        if($store->store_profile_translate){
                            foreach($store->store_profile_translate as $r){
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
                        if($store->store_profile_translate){
                            foreach($store->store_profile_translate as $r){
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
                        if($store->store_profile_translate){
                            foreach($store->store_profile_translate as $r){
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
@push('header')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
@endpush
@push('footer')
  <x-validation />
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
    $(document).ready(function(){
      $("#edit_form").validate();
      $('input, textarea').change(function() {
        $('#data_changed').val('yes');
      });
    });
    function tinymceChanged(inst) {
      $('#data_changed').val('yes');
    }
  </script>
@endpush
