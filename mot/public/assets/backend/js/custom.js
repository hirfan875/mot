"use strict";

var new_crop_field;
function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function (e) {
      $(input).parent().next('input').val(input.value.substring(12));
      $(input).parent().parent().prev('div').show().find('.img-preview').attr('src', e.target.result);
      if (typeof crop_image_before_upload !== 'undefined' && crop_image_before_upload) {
        $('#crop_image').prop('src', e.target.result);
      }
      $(input).closest('.form-group').find('div.main-img-preview').fadeIn(650);
    };
    reader.readAsDataURL(input.files[0]);
  }
}

// remove file
function ResetFile(btn) {
  $(btn).parent().find('input:file').val('');
  $(btn).closest('.form-group').find('div.main-img-preview').hide();
  $(btn).hide();
  $(btn).next('a').hide();
  $(btn).prev().prev().show();
  $(btn).closest("span.input-group-prepend").find(".remove_image_field").val('Yes');
  $(btn).closest('div.form-group').find('input.new_crop_image').val('');
  if ($('#data_changed').length) {
    $('#data_changed').val('yes');
  }
}

$(document).on('change', '.ps-file-input', function () {
  readURL(this);

  // show cropper
  if (typeof crop_image_before_upload !== 'undefined' && crop_image_before_upload) {
    new_crop_field = $(this).closest('div.form-group').find('input.new_crop_image');
    $('#cropImageModal').modal('show');
  }

  $(this).prev('.ps-trigger-file').hide();
  $(this).closest("span.input-group-prepend").find(".remove_image_field").val('No');
  $(this).next('.remove_img').show();
});

$(document).on('click', '.ps-trigger-file', function () {
  $(this).next('input').trigger('click');
});

$(document).on('click', '.img-preview', function () {
  $(this).parent().next('div').find('input').trigger('click');
});

var cropper;
$('#cropImageModal').on('shown.bs.modal', function () {
  cropper = new Cropper(document.getElementById('crop_image'), {
    aspectRatio: ratio,
    preview: '.preview'
  });
}).on('hidden.bs.modal', function () {
  cropper.destroy();
  cropper = null;
});

function saveCropImageData() {
  let croppedCanvas = cropper.getCroppedCanvas({
    width: width,
    height: height
  });
  $(new_crop_field).val(croppedCanvas.toDataURL());
  $('#cropImageModal').modal('hide');
}
