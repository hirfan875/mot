@extends('admin.layouts.app')

@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.customers') }}">{{ __($customer->name) }}</a></li>
        <li class="breadcrumb-item"><a
                href="{{ route('admin.addresses', ['customer' => $customer->id]) }}">{{ __($section_title) }}</a></li>
        <li class="breadcrumb-item active">{{ __($title) }}</li>
    </ol>
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            {{ __($title) }}
                            <x-admin.back-button :url="route('admin.addresses', ['customer' => $customer->id])"/>
                        </div>
                        <div class="card-body">
                            <!-- alerts -->
                            <x-alert class="alert-success" :status="session('success')"/>
                            <x-alert class="alert-danger" :status="session('error')"/>
                            <form
                                action="{{ route('admin.addresses.edit', ['customer' => $customer->id, 'address' => $row->id]) }}"
                                method="POST" id="edit_form">
                                @csrf
                                <div class="form-group">
                                    <label for="name">{{ __('Address Name') }}</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                           value="{{ old('name', $row->name) }}" required
                                           oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"
                                           oninput="this.setCustomValidity('')">
                                    @error('name')
                                    <span class="invalid-feedback d-block"
                                          role="alert"> <strong>{{ __($message) }}</strong> </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="phone">{{ __('Phone') }}</label>
                                    <input type="text" name="phone" id="phone" class="form-control"
                                           value="{{ old('phone', $row->phone) }}" required
                                           oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"
                                           oninput="this.setCustomValidity('')">
                                    @error('phone')
                                    <span class="invalid-feedback d-block"
                                          role="alert"> <strong>{{ __($message) }}</strong> </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="country">{{ __('Country') }}</label>
                                    <select name="country" id="country"
                                            class="custom-select form-control @error('country') is-invalid @enderror"
                                            required
                                            oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"
                                            oninput="this.setCustomValidity('')" placeholder="{{__('Country')}}">
                                        <option value="" selected>Select Country</option>
                                        @foreach ($countries as $r)
                                            <option
                                                value="{{ $r->id }}" {{ $r->id ==$row->country ? 'Selected':'' }}>{{ $r->title }} </option>
                                        @endforeach
                                    </select>
                                    @error('country')
                                    <span class="invalid-feedback d-block"
                                          role="alert"> <strong>{{ __($message) }}</strong> </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="state">{{ __('State') }}</label>
                                    <select name="state" id="state"
                                            class="custom-select form-control @error('state') is-invalid @enderror"
                                            required
                                            oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"
                                            oninput="this.setCustomValidity('')" placeholder="{{__('State')}}">
                                        <option value="" selected>Select State</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="city">{{ __('City') }}</label>
                                    <select name="city" id="cities"
                                            class="custom-select form-control @error('city') is-invalid @enderror"
                                            required
                                            oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"
                                            oninput="this.setCustomValidity('')" placeholder="{{__('City')}}">
                                        <option value="" selected>Select City</option>
                                    </select>
                                    @error('city')
                                    <span class="invalid-feedback d-block"
                                          role="alert"> <strong>{{ __($message) }}</strong> </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="address">{{ __('Block') }}</label>
                                    <input type="text" name="block" id="block" class="form-control"
                                           value="{{ old('block', $row->block) }}" required
                                           oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"
                                           oninput="this.setCustomValidity('')">
                                    @error('block')
                                    <span class="invalid-feedback d-block"
                                          role="alert"> <strong>{{ __($message) }}</strong> </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="address">{{ __('Street Number') }}</label>
                                    <input type="text" name="street_number" id="street_number" class="form-control"
                                           value="{{ old('street_number', $row->street_number) }}" required
                                           oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"
                                           oninput="this.setCustomValidity('')">
                                    @error('street_number')
                                    <span class="invalid-feedback d-block"
                                          role="alert"> <strong>{{ __($message) }}</strong> </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="address">{{ __('House Apartment') }}</label>
                                    <input type="text" name="house_apartment" id="house_apartment" class="form-control"
                                           value="{{ old('house_apartment', $row->house_apartment) }}" required
                                           oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"
                                           oninput="this.setCustomValidity('')">
                                    @error('house_apartment')
                                    <span class="invalid-feedback d-block"
                                          role="alert"> <strong>{{ __($message) }}</strong> </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="address">{{ __('Address') }}</label>
                                    <input type="text" name="address" id="address" class="form-control"
                                           value="{{ old('address', $row->address) }}" required
                                           oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"
                                           oninput="this.setCustomValidity('')">
                                    @error('address')
                                    <span class="invalid-feedback d-block"
                                          role="alert"> <strong>{{ __($message) }}</strong> </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="zipcode">{{ __('Zipcode') }}</label>
                                    <input type="text" name="zipcode" id="zipcode" class="form-control"
                                           value="{{ old('zipcode', $row->zipcode) }}"
                                           oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"
                                           oninput="this.setCustomValidity('')">
                                    @error('zipcode')
                                    <span class="invalid-feedback d-block"
                                          role="alert"> <strong>{{ __($message) }}</strong> </span>
                                    @enderror
                                </div>

                                <!-- submit button -->
                                <div class="text-center">
                                    <x-admin.save-changes-button/>
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
    <x-validation/>
    <script type="text/javascript" charset="utf-8">
        function InvalidMsg(textbox) {

            if (textbox.value == '') {
                textbox.setCustomValidity('{{__('Please fill out this field')}}');
            } else if (textbox.validity.typeMismatch) {
                textbox.setCustomValidity('{{__('please enter a valid email address')}}');
            } else {
                textbox.setCustomValidity('');
            }
            return true;
        }
    </script>
    <script>
        $("#country").on("change", function () {
            var selectedCountry = $(this).val();
            var selectedCountrycode = $("#country :selected").text();
            $('#zipcode').attr("required", true);
            if (selectedCountrycode.trim() == 'Kuwait') {
                $('#zipcode').attr("required", false);
            }
            $.ajax({
                type: "GET",
                dataType: 'json',
                url: `{{ route('get-states') }}`,
                data: {country: selectedCountry}
            }).done(function (data) {
                var options = '';
                for (var i = 0; i < data.states.length; i++) { // Loop through the data & construct the options
                    options += '<option value="' + data.states[i].id + '">' + data.states[i].title + '</option>';
                }
                // Append to the html
                $('#state').html(options);
            });
        });

        $("#state").on("change", function () {
            var state = $(this).val();
            $.ajax({
                type: "GET",
                url: `{{ route('get-cities') }}`,
                data: {state: state}
            }).done(function (data) {
                var options = '';
                for (var i = 0; i < data.cities.length; i++) { // Loop through the data & construct the options
                    options += '<option value="' + data.cities[i].id + '">' + data.cities[i].title + '</option>';
                }
                // Append to the html
                $('#cities').html(options);
            });
        });

        $(document).ready(function () {
            var country_id = "{{ $row->country }}";
            var state_id = "{{ $row->state }}";
            var city_id = "{{ $row->city }}";
            getStates(country_id, state_id);
            getCities(state_id, city_id);

        });

        function getCities(id, city_id) {
            $.ajax({
                type: "GET",
                url: `{{ route('get-cities') }}`,
                data: {state: id}
            }).done(function (data) {
                var options = '';
                for (var i = 0; i < data.cities.length; i++) { // Loop through the data & construct the options
                    options += '<option value="' + data.cities[i].id + '" ' + (data.cities[i].id == city_id ? 'selected' : '') + '>' + data.cities[i].title + '</option>';
                }
                // Append to the html
                $('#cities').html(options);
            });
        }

        function getStates(id, state_id) {
            $.ajax({
                type: "GET",
                dataType: 'json',
                url: `{{ route('get-states') }}`,
                data: {country: id}
            }).done(function (data) {
                var options = '';
                for (var i = 0; i < data.states.length; i++) { // Loop through the data & construct the options
                    options += '<option value="' + data.states[i].id + '" ' + (data.states[i].id == state_id ? 'selected' : '') + '>' + data.states[i].title + '</option>';
                }
                // Append to the html
                $('#state').html(options);
            });
        }
    </script>
@endpush
