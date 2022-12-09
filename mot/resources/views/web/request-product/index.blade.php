@extends('web.layouts.app')
@section('content')
<!--=================
  Start breadcrumb
  ==================-->
<div class="breadcrumb-container">
    <h1>{{__('REQUEST A PRODUCT')}}</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('breadcrumb.home')}}</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{__('REQUEST A PRODUCT')}}</li>
    </ol>
</div>
<!--================= End breadcrumb ==================-->
<div class="container">
    <!--================= Start About Us  ==================-->
    <div class=" bg-white p-5 mt-minus">
        <div class="regForm">
            <form method="post" id="upload-image-form" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>{{__('Name')}}</label>
                        <input type="text" name="name" id="req_name" class="form-control" required/>
                    </div>
                    <div class="form-group col-md-6">
                        <label>{{__('Email')}}</label>
                        <input type="email" name="email" id="req_email" class="form-control" required/>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>{{__('Phone')}} </label>
                        <input type="tel" name="phone" id="req_phone" class="form-control" pattern="[+0-9]{8,15}" oninput="if (typeof this.reportValidity === 'function') {this.reportValidity();}">
                    </div>
                    <div class="form-group col-md-6">
                        <label>{{__('Product Link')}}</label>
                        <input type="link" name="link" id="req_link" class="form-control">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6 photoUplaod">
                        <label>{{__('Photo If Available')}}</label>
                        <input type="file" id="image-input" name="image"  accept="image/*" class="form-control">
                        <span class="text-danger" id="image-input-error"></span>
                    </div>
                    <div class="form-group col-md-6">
                        <div class="form-group col-md-12">
                            <label>{{__('Products name')}} </label>
                            <input type="text" name="prod_name" id="prod_name" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row ml-1">
                        <div class="form-check col-md-2">
                            <input class="form-check-input" name="type" type="radio" id="gridCheck" value="Exact" checked="checked">
                            <label class="form-check-label" for="gridCheck">
                                {{__('Exact Same Product')}}
                            </label>
                        </div>
                        <div class="form-check col-md-6">
                            <input class="form-check-input" name="type" type="radio" id="gridCheck" value="Similar">
                            <label class="form-check-label" for="gridCheck">
                                {{__('Similar Product')}}
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label>{{__('Product Description')}} </label>
                        <textarea class="form-control" name="prod_desc" rows="9"></textarea>
                    </div>
                </div>
                <div class="form-row">
                    <span class="alert-success" id="image-input-success"></span>
                </div>
                <div class="form-group col-md-12">
                    <div class="g-recaptcha" id="rcaptcha" data-sitekey="6LeAmd4fAAAAAK-va6ixlJLpvpy0JX1uhUOcAdez"></div>
                    <span id="captcha" style="margin-left:100px;color:red" />
                </div>
                <div class="modal-footer">
                    <div class="form-group col-md-3">
                        <button type="submit" class="btn btn-primary d-inline-block" onclick="return get_action(this)">{{__('Submit')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!--================= End About Us ==================-->          
</div>
@endsection
@section('scripts')
<script type="text/javascript">
    function InvalidMsg(textbox) {

        if (textbox.value == '') {
            textbox.setCustomValidity('{{__('Required email address')}}');
        } else if (textbox.validity.typeMismatch) {
            textbox.setCustomValidity('{{__('please enter a valid email address')}}');
        } else {
            textbox.setCustomValidity('');
        }
        return true;
    }
</script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

   $('#upload-image-form').submit(function(e) {
       e.preventDefault();
       let formData = new FormData(this);
       $('#image-input-error').text('');

       $.ajax({
          type:'POST',
          url: `{{ url('request-product') }}`,
           data: formData,
           contentType: false,
           processData: false,
           success: (response) => {
             if (response) {
               this.reset();
               ShowSuccessModal(response.message);
//               $('#image-input-success').text(response.message);
               setTimeout(function(){
               $('#exampleModalCenter22').modal('hide');
               }, 3000);
             }
           },
           error: function(response){
              console.log(response);
                $('#image-input-error').text(response.responseJSON.errors.file);
           }
       });
  });

</script>
@endsection
