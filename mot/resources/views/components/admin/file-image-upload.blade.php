<style>
    .modal-backdrop{display: none !important;}
</style>
@props(['label' => 'Image', 'name', 'file' => '', 'thumbnail' => '', 'croproute' => '', 'croptype' => '', 'imageid' => ''])
<div class="form-group">
  <input type="hidden" name="new_crop_{{ $name }}" class="new_crop_image">
  @if ( $label != '' )
  <label>{{ __($label) }}</label>
  @endif
  <div class="main-img-preview mb-2" style="display: {{ $file != '' ? 'block' : 'none' }};">
      <img alt="thumbnail" class="img-thumbnail img-preview" src="{{ asset($thumbnail) }}">
  </div>
  <div>
    <span class="input-group-prepend">
      <button class="btn btn-primary btn-sm ps-trigger-file" type="button" style="display: {{ $file != '' ? 'none' : 'block' }};"><i class="fa fa-upload mr-1"></i> {{ __('Upload') }}</button>
      <input name="{{ $name }}" type="file" class="ps-file-input d-none" id="fUpload"  >
      <button onclick="ResetFile(this)" class="remove_img btn btn-danger btn-sm" type="button" style="display: {{ $file != '' ? 'block' : 'none' }};"><i class="fa fa-times mr-1"></i> {{ __('Remove') }}</button>
      @if ($file != '' && $croptype != '')
      <a href="{{ route($croproute.'.media.crop') }}?type={{ $croptype }}&image_id={{ $imageid }}" target="_blank" class="btn btn-primary btn-sm ml-1" style="display: {{ $file != '' ? 'block' : 'none' }};"><i class="fa fa-crop mr-1"></i> {{ __('Crop') }}</a>
      @endif
      @if ( $file != '' )
      <input type="hidden" class="remove_image_field" name="remove_{{ $name }}" value="No">
      @endif
    </span>
  </div>
  @error($name)
    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
  @enderror
</div>

<script type="text/javascript" charset="utf-8">

    var _validFileExtensions = [".jpg", ".jpeg", ".png", ".gif", ".tif", ".bmp", ".svg"];
    function ValidateSingleInput(oInput) {
        if (oInput.type == "file") {
            var sFileName = oInput.value;
            if (sFileName.length > 0) {
                var blnValid = false;
                for (var j = 0; j < _validFileExtensions.length; j++) {
                    var sCurExtension = _validFileExtensions[j];
                    if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
                        blnValid = true;
                        break;
                    }
                }

                if (!blnValid) {
                    alert("Sorry, " + sFileName + " is invalid, allowed extensions are: " + _validFileExtensions.join(", "));
                    oInput.value = "";
                    $('#cropImageModal').addClass('d-none');
                    $(".ps-file-input").load(location.href + " .ps-file-input");
                    return false;
                }
            }
        }
        $('#cropImageModal').removeClass('d-none');
        return true;
    }
</script>
