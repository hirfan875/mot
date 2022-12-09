<!-- The Modal -->
<div class="modal mt-5" id="address-form-modal">
    <div class="modal-dialog mt-5 mm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{__('Add Address')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- address book Modal body -->
            <div class="modal-body">
                <form id="add-edit-address" method="POST" action="{{route('add-address')}}">
                    @csrf
                    <div class="form-body">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')" placeholder="{{__('Name')}}">
                                @error('name')
                                <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <input type="tel" name="phone" id="phone" class="form-control-phone @error('phone') is-invalid @enderror" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')" placeholder="{{__('Phone')}}" >
                                <input type="hidden" name="phone_number" id="phone_number" value="1" />
                                @error('phone')
                                <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                @enderror
                                <!--<span id="valid-msg" class="hide">Valid</span>-->
                                <span id="error-msg" class="hide" style="color: red;">Please enter valid number</span>
                            </div>
                            <div class="form-group col-md-6">
                                @if(isset($countries))
                                    <select name="country" id="country" class="custom-select form-control @error('country') is-invalid @enderror" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')" placeholder="{{__('Country')}}">
                                        <option value="" selected>Select Country</option>
                                        @foreach ($countries as $r)
                                            <option value="{{ $r->id }}">{{ $r->title }} </option>
                                        @endforeach
                                    </select>
                                    @error('country')
                                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                    @enderror
                                @endif
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <select name="state" id="state" class="custom-select form-control @error('state') is-invalid @enderror" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')" placeholder="{{__('State')}}">
                                    <option value="" selected>Select State</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <select name="city" id="cities" class="custom-select form-control @error('city') is-invalid @enderror" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')" placeholder="{{__('City')}}">
                                    <option value="" selected>Select City</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <input type="text" name="block" id="block" class="form-control @error('block') is-invalid @enderror" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')" placeholder="{{__('Block')}}">
                                @error('block')
                                <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <input type="text" name="street_number" id="street_number" class="form-control @error('street_number') is-invalid @enderror" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')" placeholder="{{__('Street Number')}}">
                                @error('street_number')
                                <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <input type="text" name="house_apartment" id="house_apartment" class="form-control @error('house_apartment') is-invalid @enderror" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')" placeholder="{{__('House / Apartment')}}">
                                @error('house_apartment')
                                <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <input type="text" name="address" id="address" class="form-control @error('address') is-invalid @enderror" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')" placeholder="{{__('Address')}}">
                                @error('address')
                                <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            
                            <div class="form-group col-md-6">
                                <input type="text" name="zipcode" id="zipcode" class="form-control @error('zipcode') is-invalid @enderror" placeholder="{{__('Zip/Postal Code')}}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                <span id="zipcode_message">  </span>
                                @error('zipcode')
                                <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 m-auto">
                                <button type="submit" class="btn btn-primary delivery-here"><span id="save-address-spinner"></span>{{__('Save')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
