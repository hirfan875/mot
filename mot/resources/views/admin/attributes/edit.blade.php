@extends('admin.layouts.app')

@section('content')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.attributes') }}">{{ $section_title }}</a></li>
    <li class="breadcrumb-item active">{{ $title }}</li>
  </ol>
  <div class="container-fluid">
    <div class="animated fadeIn">
      <div class="row">
        <div class="col-lg-6">
          <div class="card">
            <div class="card-header">
              {{ $title }}
              <x-admin.back-button :url="route('admin.attributes')" />
            </div>
            <div class="card-body">
              <!-- alerts -->
              <x-alert class="alert-success" :status="session('success')" />
              <x-alert class="alert-danger" :status="session('error')" />
              <form action="{{ route('admin.attributes.edit', ['attribute' => $row->id]) }}" method="POST" id="edit_form">
                @csrf
                @foreach(getLocaleList() as $val)
                    @php
                        $ctitle= $row->title;
                        if($row->attribute_translate){
                            foreach($row->attribute_translate as $r){
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
                @php
                  $type = old('type', $row->type);
                @endphp
                <div class="form-group">
                  <label>{{ __('Type') }}</label><br>
                  <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="type_swatches" name="type" class="custom-control-input" value="swatches" @if ( $type == 'swatches' ) checked @endif>
                    <label class="custom-control-label" for="type_swatches">{{__('Swatches')}}</label>
                  </div>
                  <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="type_colors" name="type" class="custom-control-input" value="colors" @if ( $type == 'colors' ) checked @endif>
                    <label class="custom-control-label" for="type_colors">{{__('Color Swatches')}}</label>
                  </div>
                  <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="type_select" name="type" class="custom-control-input" value="select" @if ( $type == 'select' ) checked @endif>
                    <label class="custom-control-label" for="type_select">{{__('Select')}}</label>
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
@endpush
