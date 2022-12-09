<!-- Cropping Modal -->
<div class="modal fade" id="cropImageModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ __('Crop image before upload') }}</h5>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-6">
            <div class="img-container mb-3">
              <img id="crop_image" src="" alt="">
            </div>
          </div>
          <div class="col-sm-6">
            <div class="preview" style="width: {{ $width }}px; height: {{ $height }}px"></div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="saveCropImageData()">{{ __('Crop') }}</button>
      </div>
    </div>
  </div>
</div>

@push('header')
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.7/cropper.min.css" type="text/css" />
@endpush

@push('footer')
  <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.7/cropper.min.js"></script>
  <script>
    var crop_image_before_upload = true;
    var width = {{ $width }};
    var height = {{ $height }};
    var ratio = {{ $ratio }};
  </script>
@endpush