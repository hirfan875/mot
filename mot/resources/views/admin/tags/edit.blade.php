@extends('admin.layouts.app')

@section('content')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.tags') }}">{{ $section_title }}</a></li>
    <li class="breadcrumb-item active">{{ $title }}</li>
  </ol>
  <div class="container-fluid">
    <div class="animated fadeIn">
      <div class="row">
        <div class="col-lg-6">
          <div class="card">
            <div class="card-header">
              {{ $title }}
              <x-admin.back-button :url="route('admin.tags')" />
            </div>
            <div class="card-body">
              <!-- alerts -->
              <x-alert class="alert-success" :status="session('success')" />
              <x-alert class="alert-danger" :status="session('error')" />
              <form action="{{ route('admin.tags.edit', ['tag' => $row->id]) }}" method="POST" id="edit_form">
                @csrf
                <div class="custom-control custom-checkbox mb-3">
                  <input type="checkbox" class="custom-control-input" id="is_admin" name="is_admin" @if (old('is_admin', $row->is_admin)) checked @endif>
                  <label class="custom-control-label" for="is_admin">{{ __('For Admin Only') }}</label>
                </div>
                @foreach(getLocaleList() as $val)
                    @php
                        $ctitle= $row->title;
                        if($row->tag_translate){
                            foreach($row->tag_translate as $r){
                                if($val->code == $r->language_code){
                                $ctitle = $r->title;
                                }
                            }
                        }
                    @endphp
                <div class="form-group">
                  <label for="title">{{ __('Title ('.$val->title.')') }}</label>
                  <input type="text" name="title[{{$val->id}}]" id="title{{$val->id}}" class="form-control" value="{{ old('title['.$val->id.']', $ctitle )}}" {{$val->is_default == 'Yes' ? 'required':'' }} oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')">
                  @error('title[{{$val->id}}]')
                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                  @enderror
                </div>
                @endforeach
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
@endpush
