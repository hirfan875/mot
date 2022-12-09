@extends('admin.layouts.app')

@section('content')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.translation', ['language' => $language->id]) }}">{{ __($section_title) }}</a></li>
    <li class="breadcrumb-item active">{{ __($title) }}</li>
</ol>
<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        {{ __($title) }}
                        <x-admin.back-button :url="route('admin.translation', ['language' => $language->id])" />
                    </div>
                    <div class="card-body">
                        <!-- alerts -->
                        <x-alert class="alert-success" :status="session('success')" />
                        <x-alert class="alert-danger" :status="session('error')" />
                        <form action="{{ route('admin.translation.add', ['language' => $language->id]) }}" method="POST" enctype="multipart/form-data" id="add_form">
                            @csrf
                            <div class="form-group">
                                <label for="title">{{ __('Keyword') }}</label>
                                <input type="text" name="key" id="key" class="form-control" value="{{ old('key') }}" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')" autofocus>
                                @error('title')
                                <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="title">{{ __('Translate') }}</label>
                                <input type="text" name="translate" id="translate" class="form-control" value="{{ old('translate') }}" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')" autofocus>
                                @error('translate')
                                <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                 <label for="type">{{ __('State') }}</label>
                                    <select name="state" id="state" class="custom-select">
                                        <option value="bot">{{ __('Bot') }}</option>
                                        <option value="professional">{{ __('Professional') }}</option>
                                        <option value="default">{{ __('Default') }}</option>
                                        <option value="verified">{{ __('Verified') }}</option>
                                    </select>
                                    @error('type')
                                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                    @enderror
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
@endpush
