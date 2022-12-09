@extends('seller.layouts.app')

@section('content')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item active">{{ __($title) }}</li>
  </ol>
  <div class="container-fluid">
    <div class="animated fadeIn">
      <div class="row">
        <div class="col-lg-6">
          <div class="card">
            <div class="card-header">
              {{ __($title) }}
            </div>
            <div class="card-body">
              <!-- alerts -->
              <x-alert class="alert-success" :status="session('success')" />
              <x-alert class="alert-danger" :status="session('error')" />
              <form action="{{ route('seller.return.address') }}" method="POST" id="edit_form">
                @csrf
                <div class="form-group">
                  <label for="name">{{ __('Name') }}</label>
                  <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $row->name) }}" required>
                  @error('name')
                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="phone">{{ __('Phone') }}</label>
                  <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $row->phone) }}" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  minlength="8" maxlength="16" oninput="this.value = this.value.replace(/[^- +()xX,0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1'); this.setCustomValidity('');" >
                  @error('phone')
                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="address">{{ __('Address') }}</label>
                  <input type="text" name="address" id="address" class="form-control" value="{{ old('address', $row->address) }}" required>
                  @error('address')
                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="address2">{{ __('Address 2') }}</label>
                  <input type="text" name="address2" id="address2" class="form-control" value="{{ old('address2', $row->address2) }}">
                </div>
                <div class="form-group">
                  <label for="address3">{{ __('Address 3') }}</label>
                  <input type="text" name="address3" id="address3" class="form-control" value="{{ old('address3', $row->address3) }}">
                </div>
                <div class="form-group">
                  <label for="city">{{ __('City') }}</label>
                  <select name="city" id="city" class="form-control @error('city') is-invalid @enderror" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')">
                        @foreach ($cities as $r)
                            <option value="{{ $r->title }}" {{ ($row->city == $r->title)? 'Selected':'' }}>{{ $r->title }}</option>
                        @endforeach
                    </select>
                  @error('city')
                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="state">{{ __('State') }}</label>
                  <select name="state" id="state" class="form-control @error('state') is-invalid @enderror" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')">
                        @foreach ($states as $r)
                            <option value="{{ $r->title }}" {{ ($row->state == $r->title)? 'Selected':'' }}>{{ $r->title }}</option>
                        @endforeach
                    </select>
                  
                </div>
                <div class="form-group">
                  <label for="zipcode">{{ __('Zipcode') }}</label>
                  <input type="text" name="zipcode" id="zipcode" class="form-control" value="{{ old('zipcode', $row->zipcode) }}" required>
                  @error('zipcode')
                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="country">{{ __('Country') }}</label>
                  @foreach ($countries as $r)
                        @if($r->is_default=='Yes')
                        <input type="text" name="country-name" id="country-name" class="form-control" value="{{ old('country',$r->title) }}" readonly>
                        <input type="hidden" name="country" id="country" class="form-control" value="{{ old('country',$r->id) }}" >
                        @endif
                    @endforeach
                  @error('country')
                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                  @enderror
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
  <script type="text/javascript">
    $(document).ready(function(){
      $("#edit_form").validate();
    });
  </script>
@endpush
