@extends('admin.layouts.app')

@section('content')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.currencies') }}">{{ __($section_title) }}</a></li>
    <li class="breadcrumb-item active">{{ __($title) }}</li>
  </ol>
  <div class="container-fluid">
    <div class="animated fadeIn">
      <div class="row">
        <div class="col-lg-6">
          <div class="card">
            <div class="card-header">
              {{ __($title) }}
              <x-admin.back-button :url="route('admin.currencies')" />
            </div>
            <div class="card-body">
              <!-- alerts -->
              <x-alert class="alert-success" :status="session('success')" />
              <x-alert class="alert-danger" :status="session('error')" />
              <form action="{{ route('admin.currencies.edit', ['currency' => $row->id]) }}" method="POST" id="edit_form">
                @csrf
                <div class="form-group">
                  <label for="title">{{ __('Title') }}</label>
                  <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $row->title) }}" oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')" required>
                  @error('title')
                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="code">{{ __('Currency Code') }}</label>
                  <input type="text" name="code" id="code" class="form-control" value="{{ old('code', $row->code) }}" oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')" required>
                  @error('code')
                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="symbol">{{ __('Currency Symbol') }}</label>
                  <input type="text" name="symbol" id="symbol" class="form-control" value="{{ old('symbol', $row->symbol) }}" oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')" required>
                  @error('symbol')
                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                  @enderror
                </div>
                @php
                  $symbol_position = old('symbol_position', $row->symbol_position);
                @endphp
                <div class="form-group">
                  <label>{{ __('Symbol Position') }}</label><br>
                  <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="symbol_position_left" name="symbol_position" class="custom-control-input" value="left" @if ( $symbol_position == 'left' ) checked @endif>
                    <label class="custom-control-label" for="symbol_position_left">{{ __('Left') }}</label>
                  </div>
                  <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="symbol_position_right" name="symbol_position" class="custom-control-input" value="right" @if ( $symbol_position == 'right' ) checked @endif>
                    <label class="custom-control-label" for="symbol_position_right">{{ __('Right') }}</label>
                  </div>
                </div>
                <div class="form-group">
                  <label for="base_rate">{{ __('Base Rate') }}</label>
                  <input type="text" name="base_rate" id="base_rate" class="form-control" value="{{ old('base_rate', $row->base_rate) }}" oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')" required>
                  @error('base_rate')
                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="thousand_separator">{{ __('Thousand Separator') }}</label>
                  <input type="text" name="thousand_separator" id="thousand_separator" class="form-control" value="{{ old('thousand_separator', $row->thousand_separator) }}" oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')" required>
                </div>
                <div class="form-group">
                  <label for="decimal_separator">{{ __('Decimal Separator') }}</label>
                  <input type="text" name="decimal_separator" id="decimal_separator" class="form-control" value="{{ old('decimal_separator', $row->decimal_separator) }}" oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')" required>
                </div>
                <div class="form-group">
                  <label for="emoji">{{ __('Emoji') }}</label>
                  <input type="text" name="emoji" id="emoji" class="form-control" value="{{ old('emoji', $row->emoji) }}">
                </div>
                <div class="form-group">
                  <label for="emoji_uc">{{ __('Emoji Unicode') }}</label>
                  <input type="text" name="emoji_uc" id="emoji_uc" class="form-control" value="{{ old('emoji_uc', $row->emoji_uc) }}">
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
