@extends('web.layouts.app')
@section('content')
 <style>
    #imageUpload
{
    display: none;
}

#profileImage
{
    cursor: pointer;
}

#profile-container {
   -webkit-border-radius: 50%;
    -moz-border-radius: 50%;
    -ms-border-radius: 50%;
    -o-border-radius: 50%;
    border-radius: 50%;
    position: absolute;
    top: 126px;
    right: 100px;
}
#profileImage1 {
    width: 100px;
        height:100px;
    -webkit-border-radius: 50%;
    -moz-border-radius: 50%;
    -ms-border-radius: 50%;
    -o-border-radius: 50%;
    border-radius: 50%;
}

#profile-container img {
    width: 20px;
    height: 20px;
}
#profileImage {
    font-size: 20px;
}
</style>
    <!--=================
  Start breadcrumb
  ==================-->
    <div class="breadcrumb-container">
        <h1>{{__('breadcrumb.address_book')}}</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('breadcrumb.home')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('breadcrumb.address_book')}}</li>
        </ol>
    </div>
    <!--=================
      End breadcrumb
      ==================-->

    <div class="container">
        <div class="my-account white-bg mt-minus">
            <div class="row">
                <div class="col-sm-3 user-sidebar">
                    <div class="nav flex-column nav-pills text-center user-nav pt-5" id="sidebar-admin" role="tablist" aria-orientation="vertical">
                       
                        <div class="avatar">
                            @if($customerInfo->image)
                            <img  id="profileImage1" src="{{asset('storage/'.$customerInfo->image)}}" height="100" alt="{{ getAvatarCode($customerInfo->name) }}" />
                            @else
                                <span class="avatar-in" > {{ getAvatarCode($customerInfo->name) }}</span>
                            @endif
                            <form  enctype="multipart/form-data" id="imageUploadForm" >
                                @csrf
                                <div id="profile-container">
                                    <span id="profileImage"><i class="fa fa-edit"></i></span>
                                    <input id="imageUpload" type="file"  name="image" placeholder="Choose image" required="" >
                                    @error('image')
                                            <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </form>
                        </div>
                        <div class="content mt-2 mb-4">
                            <h2>{{$customerInfo->name}}</h2>
                            <span>{{$customerInfo->phone}}</span>
                        </div>

                        @include('customer.account-partial.navigation', ['active'=>'address'])
                    </div>
                </div>

                <div class="col-sm-9 brder-left">
                    @include('customer.account-partial.feedback')
                    <div>
                        <!-- Book Address -->

                        @include('customer.account-partial.address-book')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Address Popup -->

    @include('customer.account-partial.address-add-edit')

    <div id="zipcode-turkey" style="display: none">{{trans('Kindly provide valid postal code')}}</div>
    <div id="zipcode-kuwait" style="display: none">{{trans('Kindly provide valid postal code')}}</div>
    <div id="zipcode-Bahrain" style="display: none">{{trans('Kindly provide valid postal code (101 to 1216)')}}</div>
    <div id="zipcode-Egypt" style="display: none">{{trans('Kindly provide valid postal code')}}</div>
    <div id="zipcode-Jordan" style="display: none">{{trans('Kindly provide valid postal code')}}</div>
    <div id="zipcode-Saudi-Arabia" style="display: none">{{trans('Kindly provide valid postal code')}}</div>
    <div id="Oman" style="display: none">{{trans('Kindly provide valid postal code')}}</div>
    <div id="address-successfully" style="display: none">{{trans('Address Added Successfully')}}</div>

@endsection

@section('scripts')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/css/intlTelInput.css" rel="stylesheet" media="screen">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/intlTelInput.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/intlTelInput.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/utils.js"></script>
    <script>
        
        var telInput = $("#phone"),
          errorMsg = $("#error-msg"),
          validMsg = $("#valid-msg");

        // initialise plugin
        telInput.intlTelInput({

          allowExtensions: true,
          formatOnDisplay: true,
          autoFormat: true,
          autoHideDialCode: true,
          autoPlaceholder: true,
          defaultCountry: "kw",
//          ipinfoToken: "yolo",

//          nationalMode: false,
//          numberType: "MOBILE",
          //onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
          preferredCountries: ['sa', 'ae', 'qa','om','bh','kw','tr'],
          preventInvalidNumbers: true,
          separateDialCode: true,
          initialCountry: 'kw',
          hiddenInput: "phone",

           utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/utils.js"
        });

        var reset = function() {
          telInput.removeClass("error");
          errorMsg.addClass("hide");
          validMsg.addClass("hide");
           
        };

        // on blur: validate
        telInput.blur(function() {
          reset();
          if ($.trim(telInput.val())) {
            if (telInput.intlTelInput("isValidNumber")) {
              validMsg.removeClass("hide");
              $('#phone_number').val(1);
            } else {
                $('#phone_number').val(0);
              telInput.addClass("error");
              errorMsg.removeClass("hide");
            }
          }
        });
        
        telInput.on("keyup change", reset);
        
    </script>
    <script src="{{ asset('assets/frontend') }}/assets/js/zipcode.js"></script>
    <script>
        @error('*')
        $("#address-form-modal").modal();
        @enderror

        function update_profile(id)
        {
            let Addressess = {!! $addresses !!};
            let addId = id;
            let addressRow = Addressess.filter(function(obj) {
                return (obj.id === addId);
            });

            $('#address-heading').html("{{__('Edit Address')}}")

            let path = '{!! url('edit-address') !!}'+'/'+addressRow[0].id ;
            $('#add-edit-address').attr('action',path) ;

            let methodType = 'POST' ;
            $('#add-edit-address').attr('method',methodType);

            $('#name').val(addressRow[0].name);
            // $('#email').val(addressRow[0].email);
            $('#phone').val(addressRow[0].phone);
            
            var telInput = $("#phone");
              // initialise plugin
              telInput.intlTelInput({
                allowExtensions: true,
                formatOnDisplay: true,
                autoFormat: true,
                autoHideDialCode: true,
                autoPlaceholder: true,
                separateDialCode: true,
                 utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/utils.js"
              });
            
            $('#zipcode').val(addressRow[0].zipcode);
            $('#address').val(addressRow[0].address);
            $('#block').val(addressRow[0].block);
            $('#street_number').val(addressRow[0].street_number);
            $('#house_apartment').val(addressRow[0].house_apartment);
            getStates(addressRow[0].country,addressRow[0].state);
            getCities(addressRow[0].state,addressRow[0].city);
            $('#country').val(addressRow[0].country);
            $("#address-form-modal").modal();
            
            $('#zipcode').attr("required", true);
            if (addressRow[0].country == '117') {
                $('#zipcode').attr("required", false);
            }
            if (addressRow[0].country == '229' || addressRow[0].country ==  '178') {
                $('#zipcode').attr("disabled", true);
            }
            
            
            
            // $('#country').find("option[value*='" + addressRow[0].country + "']").show().prop('selected', true).prop('disabled', false);
        }

        function deleteAddress(id){
            let confirmBox = confirm("{{trans('Are you really want to delete')}}");
            if(confirmBox){
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: `{{ route('delete-address') }}`,
                    data: { addressid: id, _token: '{{csrf_token()}}' },
                    success: function(data){
                        console.log(data.success);
                        if(data.success){
                            location.reload();
                        }
                    }
                });
            }
        }

        $('#editCustomerInfo').on('submit', function(e){
            e.preventDefault();
            let name = $('#customer-name').val();
            let phone = $('#customer-phone').val();
            let email = $('#customer-email').val();
            $.ajax({
                type: "POST",
                dataType: "json",
                url: `{{ route('update-profile') }}`,
                data: $('#editCustomerInfo').serialize(),
                success: function(data){
                    console.log(data)
                }
            });
        });

    </script>
    <script type="text/javascript">
        $(function () {
            $('[data-toggle="popover"]').popover()
        })
        $('#example').popover('show');

    </script>

    <script>
        $("#country").on("change", function () {
            var selectedCountry = $(this).val();
            var selectedCountrycode = $("#country :selected").text();
            $('#zipcode').attr("required", true);
            if (selectedCountrycode.trim() == 'Kuwait') {
                $('#zipcode').attr("required", false);
            }
            $('#zipcode').prop("disabled", false);
            if (selectedCountrycode.trim() == 'Qatar' || selectedCountrycode.trim() == 'United Arab Emirates') {
                $('#zipcode').prop("disabled", true);
            }

            $.ajax({
                type: "GET",
                dataType: 'json',
                url: `{{ route('get-states') }}`,
                data: { country : selectedCountry }
            }).done(function(data){
                var options = '<option value="">Select State</option>';
                for(var i=0; i<data.states.length; i++) { // Loop through the data & construct the options
                    options += '<option value="'+data.states[i].id+'">'+data.states[i].title+'</option>';
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
                data: { state : state }
            }).done(function(data){
                var options = '<option value="">Select City</option>';
                for(var i=0; i<data.cities.length; i++) { // Loop through the data & construct the options
                    options += '<option value="'+data.cities[i].id+'">'+data.cities[i].title+'</option>';
                }
                // Append to the html
                $('#cities').html(options);
            });
        });

        function getCities(id, city_id)
        {

            $.ajax({
                type: "GET",
                url: `{{ route('get-cities') }}`,
                data: { state : id }
            }).done(function(data){
                var options = '';
                for(var i=0; i<data.cities.length; i++) { // Loop through the data & construct the options
                    options += '<option value="'+data.cities[i].id+'" '+ (data.cities[i].id == city_id ? 'selected' : '')+'>'+data.cities[i].title+'</option>';
                }
                // Append to the html
                $('#cities').html(options);
            });
        }

        function getStates(id, state_id)
        {
            $.ajax({
                type: "GET",
                dataType: 'json',
                url: `{{ route('get-states') }}`,
                data: { country : id }
            }).done(function(data){
                var options = '';
                for(var i=0; i<data.states.length; i++) { // Loop through the data & construct the options
                    options += '<option value="'+data.states[i].id+'" '+ (data.states[i].id == state_id ? 'selected' : '')+'>'+data.states[i].title+'</option>';
                }
                // Append to the html
                $('#state').html(options);
            });
        }
    </script>
@endsection
