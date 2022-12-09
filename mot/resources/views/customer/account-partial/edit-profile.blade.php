<style>
    .input-container input {
        border: none;
        box-sizing: border-box;
        outline: 0;
        padding: .75rem;
        position: relative;
        width: 100%;
    }

    input[type="date"]::-webkit-calendar-picker-indicator {
        background: transparent;
        bottom: 0;
        color: transparent;
        cursor: pointer;
        height: auto;
        left: 0;
        position: absolute;
        right: 0;
        top: 0;
        width: auto;
    }
</style>

<div class="tab-pane fade active show" id="editaccount" role="tabpanel" aria-labelledby="user-communicate-tab">
    <!--=================
    Start Edit Account
    ==================-->
    <form method="post" action="{{route('update-profile')}}" class="account-form" id="editCustomerInfo">
        @csrf
        <div class="form-header p-3">
            <h2 class="mb-0">{{__('Edit Account')}}</h2>
        </div>
        <div class="form-body">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <input type="text" name="name" id="customer-name" value="{{$customer->name}}" class="form-control @error('name') is-invalid @enderror" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')" placeholder="{{__('Name')}}">
                </div>
                <div class="form-group col-md-6">
                    <div>
                        <input  type="tel" name="phone" id="phone" value="{{$customer->phone}}"  placeholder="{{__('Phone')}}" oninput="this.value = this.value.replace(/[^- +()xX,0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" class="form-control-phone @error('phone') is-invalid @enderror" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')"  >
                    </div>
                        <input type="hidden" name="phone_number" id="phone_number" value="1" />
                    <span id="error-msg" class="hide" style="color: red;">Please enter valid number</span>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <input type="email" name="email" id="customer-email" readonly value="{{$customer->email}}" class="form-control @error('email') is-invalid @enderror" placeholder="{{__('Email')}}">
                </div>
                <div class="form-group col-md-6 dobB">
                    <input placeholder="{{__('Date of Birth')}}" type="date" id="date" name="birthday" class="form-control" value="{{$customer->birthday}}">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <button type="button" id="form-submit" class="btn btn-primary delivery-here"><span id="spinner">&nbsp;</span>{{__('UPDATE INFORMATION')}}</button>
                </div>
            </div>
        </div>
    </form>
    <!--=================
    End Edit Account
    ==================-->
</div>


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
    <script>
            $('#editCustomerInfo').on('submit',(function(event) {
              event.preventDefault();
              var phone_number = $('#phone').val();
              var full_number =  $("#phone").intlTelInput("getNumber");
              $('#phone').val(full_number);
              var form = $(this);
              $.ajax({
                type: form.attr('method'),
                url: form.attr('action'),
                data: form.serialize()
              }).done(function(data) {
                   $('#phone').val(phone_number);
              }).fail(function(data) {
              });
            }));
          
            $("#form-submit").on("click", function() {
                if($('#phone_number').val() == 1) {
                    $("#editCustomerInfo").submit();
                } else {
                    return false;
                }
            });
    </script>
    <script>
        $('#editCustomerInfo').on('submit', function(e){
            e.preventDefault();
            $("#spinner").html("<i class='fa fa-refresh fa-spin'></i> &nbsp;");

            $.ajax({
                type: "POST",
                dataType: "json",
                url: `{{ route('update-profile') }}`,
                data: $('#editCustomerInfo').serialize(),
                success: function(data){
                    $("#spinner").html("&nbsp;");
                    if (data.message) {
                        // $('#costumModal9').removeClass('d-none');
                        // $("#costumModal9").show();
                        // setTimeout(function() {$('#costumModal9').addClass('d-none');}, 3000);

                        ShowSuccessModal("{{__('Record has been updated Successfully!')}}", null);
                        // console.log(data.message);
                        // return;
                    }
                },
                error: function(data) {
                    $("#spinner").html("&nbsp;");
                    if (data.message){
                        ShowFailureToaster(data.message);
                        return;
                    }
                }
            });
        });
    </script>
@endsection
