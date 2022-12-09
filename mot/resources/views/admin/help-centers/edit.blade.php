@extends('admin.layouts.app')

@section('content')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.help-centers') }}">{{ $section_title }}</a></li>
    <li class="breadcrumb-item active">{{ $title }}</li>
  </ol>
  <div class="container-fluid">
    <div class="animated fadeIn">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              {{ $title }}
              <x-admin.back-button :url="route('admin.help-centers')" />
            </div>
            <div class="card-body">
              <!-- alerts -->
              <x-alert class="alert-success" :status="session('success')" />
              <x-alert class="alert-danger" :status="session('error')" />
              <form action="{{ route('admin.help-centers.edit', ['helpCenter' => $row->id]) }}" method="POST" id="edit_form">
                @csrf
                @foreach(getLocaleList() as $val)
                    @php
                        $ctitle= $row->title;
                        if($row->help_center_translate){
                            foreach($row->help_center_translate as $r){
                                if($val->id == $r->language_id){
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
                @foreach(getLocaleList() as $val)
                    @php
                        $cdescription= $row->description;
                        if($row->help_center_translate){
                            foreach($row->help_center_translate as $r){
                                if($val->id == $r->language_id){
                                $cdescription = $r->description;
                                }
                            }
                        }
                    @endphp
                <div class="form-group">
                  <label for="description">{{ __('Description ('.$val->title.')') }}</label>
                  <textarea name="description[{{$val->id}}]" id="description{{$val->id}}" cols="30" rows="5" class="form-control TinyEditor">{!! old('description[$val->id]', $cdescription) !!}</textarea>
                </div>
                @endforeach
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
