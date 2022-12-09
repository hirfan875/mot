$('#add-edit-address').on('submit', function(e){
    e.preventDefault();
    $('#save-address-spinner').show().html("<i class='fa fa-refresh fa-spin'></i> &nbsp;");
    $(':input[type="submit"]').prop('disabled', true);
    let path = e.currentTarget.action;
    $("#phone").val($("#phone").intlTelInput("getNumber"));
    let zipcodeturkey = $('#zipcode-turkey').text();
    let addressSuccessfully = $('#address-successfully').text();
    let zipcode = $('#zipcode').val();
    let country = $('#country').val();
    let country_name = $("#country option:selected").text();
    $('#zipcode_message').hide();
    if(zipcode != '' && zipcode.length < 3 && zipcode.length > 7 && country_name.trim() =='Turkey'){
        $('#zipcode_message').html("<strong style='color:red;'>"+zipcodeturkey+"</strong> ");
        $('#save-address-spinner').hide().html("<i class='fa fa-refresh fa-spin'></i> &nbsp;");
        $(':input[type="submit"]').prop('disabled', false);
        return false;
    }
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: "POST",
        dataType: "json",
        url: path,
        data: $('#add-edit-address').serialize(),
        success: function(data){
            if(data.success){
                $("#address-form-modal").modal('hide');
                $('#save-address-spinner').hide();
                $(':input[type="submit"]').prop('disabled', false);
                ShowSuccessModal(addressSuccessfully);
                location.reload();
            }
        }
    });
});










