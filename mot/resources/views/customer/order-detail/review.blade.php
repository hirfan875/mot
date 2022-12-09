@section('style')
<link rel="stylesheet" href="{{ asset('assets/backend') }}/css/dropzone.min.css" type="text/css" />
   @endsection
<div class="review_form field_form">
    <form class="row mt-3 review-form" method="post" action="{{route('create-customer-product-reviews')}}" enctype="multipart/form-data" data-message="{{__('Your Review is submitted.')}}">
         <h5>{{__('mot-products.add_your_review')}}</h5>
        @csrf
        <input type="hidden" name="order_item_id" value={{$orderItem->id}}>
        <div class="form-group col-12">
            <div class="star_rating">
                <fieldset class="rating">
                    <input type="radio" id="star5" name="rating" value="5" /><label class="full" for="star5" title="{{__('Awesome - 5 stars')}}"></label>
                    <input type="radio" id="star4" name="rating" value="4" /><label class="full" for="star4" title="{{__('Pretty good - 4 stars')}}"></label>
                    <input type="radio" id="star3" name="rating" value="3" /><label class="full" for="star3" title="{{__('Meh - 3 stars')}}"></label>
                    <input type="radio" id="star2" name="rating" value="2" /><label class="full" for="star2" title="{{__('Kinda bad - 2 stars')}}"></label>
                    <input type="radio" id="star1" name="rating" value="1" checked /><label class="full" for="star1" title="{{__('Sucks big time - 1 star')}}"></label>
                </fieldset>
            </div>
        </div>
        <div class="col-md-12 mb-3">
            <label for="image">{{__('mot-products.review_image')}}</label>
            <input type="hidden" name="gallery" id="gallery" value="">
                <div id="dropzone" class="dropzone needsclick mb-3">
                  <div class="dz-message needsclick">
                    <button type="button" class="dz-button">{{ __('Drop files here or click to upload.') }}</button>
                  </div>
                </div>
        </div>
        <div class="form-group col-12">
            <label for="comment">{{__('mot-products.review_comment')}}</label>
            <textarea id="comment" required="required" placeholder="{{__('Your review ')}}" class="form-control" name="comment" rows="4"></textarea>
        </div>
        <div class="form-group col-12">
            <button type="submit" class="btn btn-fill-out" name="submit" value="Submit"><span id="spinner" class="spinner"></span> {{__('mot-products.submit_review')}}</button>
        </div>
    </form>
</div>

@section('scripts')
<script type="text/javascript" src="{{ asset('assets/backend') }}/js/dropzone.min.js"></script>
<script>
   // dropzone
   var fileList = [];
    Dropzone.autoDiscover = false;
    var myDropzone = new Dropzone("#dropzone", {
      url: "{{ route('gallery.upload') }}",
      method: "post",
      addRemoveLinks: true,
      parallelUploads: 4,
      uploadMultiple: true,
      maxFiles: 4,
      maxFilesize: 2, // MB,
      acceptedFiles: ".jpeg,.jpg,.png",
      headers: {
        'X-CSRF-TOKEN': "{{ csrf_token() }}"
      },
      renameFile: function (file) {
        let newName = new Date().getTime() + '_' + file.name.replace("=", "").replace(/\s+/g,"_").replace(/[^a-z0-9\_\-\.]/i, "");
        return newName;
      }
    });

    myDropzone.on("sending", function(file, xhr, formData) {
      $('button#SaveBtn').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> {{ __('Processing...') }}').prop('disabled', true);
    });

    myDropzone.on("complete", function(file) {

      if ( file.status != 'error' ) {
        fileList.push(file.upload.filename);
        $('#gallery').val(fileList.toString());
      }

      $('button#SaveBtn').html( $('button#SaveBtn').data('original') ).prop('disabled', false);
    });

    myDropzone.on("removedfile", function(file) {

      let token = '{{ csrf_token() }}';
      let filename = '';

      if ( typeof(file.upload) != "undefined" && file.upload !== null ) {
        filename = file.upload.filename;
        fileList.splice($.inArray(file.upload.filename, fileList),1);
      } else {
        filename = file.name;
        fileList.splice($.inArray(file.name, fileList),1);
      }

      $('#gallery').val(fileList.toString());

      $.ajax({
        url: "{{ route('gallery.delete') }}",
        type: "POST",
        data: "filename="+filename+"&_token="+token,
        cache: false,
        dataType:  'json',
        success : function (response) {
        }
      });
    });
      </script>

@endsection
