 <div class="form-body">

    <div class="form-row">
        <div class="form-group col-md-12">
            <input type="email" name="email" id="guest_email" class="form-control @error('email') is-invalid @enderror" oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')" oninput="this.setCustomValidity('')" placeholder="{{__('Email')}}" form="order-form" onfocusout="checkGuestUser(this.value)" />
            @error('email')
            <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
            @enderror
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-12">
            <input type="text" name="name" id="guest_name" class="form-control @error('name') is-invalid @enderror" oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')" oninput="this.setCustomValidity('')" placeholder="{{__('Name')}}" required form="order-form">
            @error('name')
            <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
            @enderror
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6 guest-number">
            <div>
                <input type="tel" name="phone" id="phone" class="form-control-phone @error('phone') is-invalid @enderror"  placeholder="{{__('Phone')}}" required form="order-form">
            </div>
            <input type="hidden" name="phone_number" id="phone_number" value="1" />
            @error('phone')
            <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
            @enderror
            <!--<span id="valid-msg" class="hide">Valid</span>-->
            <span id="error-msg" class="hide" style="color: red;">Please enter valid number</span>
        </div>
        <div class="form-group col-md-6">
            @if(isset($countries))
                <select form="order-form" name="country" id="country" class="custom-select form-control @error('country') is-invalid @enderror" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')" placeholder="{{__('Country')}}">
                   <option value="" selected>{{ __('Select Country') }}</option>
                    @foreach ($countries as $r)
                        <option value="{{ $r->id }}">{{ $r->title }}</option>
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
            <select form="order-form" name="state" id="state" class="custom-select form-control @error('state') is-invalid @enderror" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')" placeholder="{{__('State')}}">
                <option value="" selected>{{ __('Select State') }}</option>
            </select>
        </div>
        <div class="form-group col-md-6">
            <select form="order-form" name="city" id="cities" class="custom-select form-control @error('city') is-invalid @enderror" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')" placeholder="{{__('City')}}">
                <option value="" selected>{{ __('Select City') }}</option>
            </select>
        </div>
    </div>
     <div class="form-row">
        <div class="form-group col-md-6">
            <input type="text" name="block" id="guest_block" class="form-control @error('block') is-invalid @enderror" oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')" oninput="this.setCustomValidity('')" placeholder="{{__('Block')}}" form="order-form">
            @error('block')
            <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
            @enderror
        </div>
        <div class="form-group col-md-6">
            <input type="text" name="street_number" id="guest_street_number" class="form-control @error('street_number') is-invalid @enderror" oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')" oninput="this.setCustomValidity('')" placeholder="{{__('Street Number')}}" form="order-form">
            @error('street_number')
            <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
            @enderror
        </div>
    </div>
     <div class="form-row">
        <div class="form-group col-md-6">
            <input type="text" name="house_apartment" id="guest_house_apartment" class="form-control @error('house_apartment') is-invalid @enderror" oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')" oninput="this.setCustomValidity('')" placeholder="{{__('House / Apartment')}}" form="order-form">
            @error('house_apartment')
            <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
            @enderror
        </div>
        <div class="form-group col-md-6">
            <input type="text" name="address" id="guest_address" class="form-control @error('address') is-invalid @enderror" oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')" oninput="this.setCustomValidity('')" placeholder="{{__('Address')}}" form="order-form">
            @error('address')
            <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
            @enderror
        </div>
    </div>
    <div class="form-row">

        <div class="form-group col-md-6">
            <input type="text" name="zipcode" id="guest_zipcode" class="form-control @error('zipcode') is-invalid @enderror" placeholder="{{__('Zipcode')}}" form="order-form">
            <span id="guest_zipcode_message"> </span>
            @error('zipcode')
            <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
            @enderror
        </div>
    </div>
     <div class="form-group col-md-12">
        <div class="g-recaptcha" id="rcaptcha" data-sitekey="6LeAmd4fAAAAAK-va6ixlJLpvpy0JX1uhUOcAdez"></div>
        <span id="captcha" style="margin-left:100px;color:red" />
    </div>

</div>
<div id="addressid"></div>

