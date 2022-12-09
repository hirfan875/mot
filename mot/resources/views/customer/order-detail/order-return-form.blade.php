@extends('web.layouts.app')
@section('content')
<!--=================
Start breadcrumb
==================-->
<div class="breadcrumb-container">
    <h1>{{__('breadcrumb.order_return_request')}}</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('breadcrumb.home')}}</a></li>
        <li class="breadcrumb-item"><a href="{{route('order-history')}}">{{__('breadcrumb.order_history')}}</a></li>
        <li class="breadcrumb-item"><a href="{{route('order-detail', $orderItems[0]->store_order_id) }}">{{__('breadcrumb.order_detail')}}</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{__('breadcrumb.order_return_request')}}</li>
    </ol>
</div>
<!--=================
      End breadcrumb
      ==================-->
<div class="container">
    <ul class="nav nav-pills nav-pills-container mt-minus mb-3 nav-justified" id="pills-tab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="checkout-tab" data-toggle="pill" href="#tab1" role="tab" aria-controls="tab1" aria-selected="true">{{__('Select Product')}}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link disabled enterdetails" id="shipping-tab" data-toggle="pill" href="#tab2" role="tab" aria-controls="tab2" aria-selected="false">{{__('Enter Detail')}}</a>
        </li>
    </ul>
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="checkout-tab">
                <form method="POST" action="{{route('order-return-request')}}" id="ReturnrequestForm" name="returnform">
                @csrf
                <!-- Form Start From Here -->
                <div class="seller_forms seller_forms1 table-content">
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th class="product-thumbnail">{{__('Select')}}</th>
                                    <th class="product-name">{{__('Product Title')}}</th>
                                    <th class="product-price">{{__('Price')}}</th>
                                    <th class="product-quantity">{{__('Action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orderItems as $key => $orderItem)
                                <div class="loading-div d-none" id="loading-div-{{$orderItem->id}}">
                                    <div class="spinner-border text-danger" role="status">
                                        <span class="sr-only">{{__('Loading...')}}</span>
                                    </div>
                                </div>
                                <input type="hidden" name="store_order_id" value="{{$orderItem->store_order_id}}">
                                <input type="hidden" name="status" value="0">
                                <input type="hidden" name="request_note" value="note">
                                <tr>
                                    @if( isset($orderItem->return_order_items[0]->order_item_id) )
                                    @php
                                    $status = false;
                                    @endphp
                                    <td><p>{{ __('You have already requested' )}}</p></td>
                                    @else
                                    @php
                                    $status = true;
                                    @endphp
                                    <td class="product-thumbnail" id="CheckBoxList">
                                        @if($orderItems->count()==1)
                                        <input type="checkbox" onclick="goto({{$orderItem->id}})" id="checkbok_{{$orderItem->id}}" name="order_item_id[]" value="{{$orderItem->id}}" checked="checked">
                                        @else
                                        <input type="checkbox" onclick="goto({{$orderItem->id}})" id="checkbok_{{$orderItem->id}}" name="order_item_id[]" value="{{$orderItem->id}}">
                                        @endif
                                    </td>
                                    @endif
                                    <td class="product-name" id="product_title" name="product_title">
                                        <img src="{{$orderItem->product->product_listing()}}" alt="{{$orderItem->product->title}}"/>
                                        <a href="#.">
                                            <div class="p-title-{{$orderItem->product_id}}">
                                                @if($orderItem->product->parent_id != null)
                                                    @if(isset($orderItem->product->parent))
                                                        {{\Illuminate\Support\Str::limit(isset($orderItem->product->parent->product_translates)? $orderItem->product->parent->product_translates->title : $orderItem->product->parent->title, 35)}}
                                                        <br>
                                                        <span class="order-detail-arbt">{{ count(getAttributeWithOption($orderItem->product)) > 0 ? getAttrbiuteString(getAttributeWithOption($orderItem->product)) : null}}</span>
                                                    @endif
                                                @else
                                                    {{\Illuminate\Support\Str::limit(isset($orderItem->product->product_translates)? $orderItem->product->product_translates->title : $orderItem->product->title, 35)}}
                                                @endif
                                            </div>
                                        </a>
                                        <a href="{{route('shop', $orderItem->product->store->slug)}}">{{isset($orderItem->product->store->store_profile_translates)? $orderItem->product->store->store_profile_translates->name : $orderItem->product->store->name}}</a>
                                    </td>
                                    <td class="product-price" name="product_price">{{__($orderItem->store_order->order->currency->code)}}&nbsp;{{convertTryForexRate($orderItem->product->price , $orderItem->store_order->order->forex_rate, $orderItem->store_order->order->base_forex_rate, $orderItem->store_order->order->currency->code)}}</td>
                                    <td class="product-quantity">
                                        <label>{{__('Qty')}}: </label> <input class="form-control" type="text" id="quantity[]" name="quantity[]" value="{{ $orderItem->quantity }}" readonly><br>
                                        <label>{{__('Reason')}}: </label> <select class="form-control" name="reason[]" required>
                                            <option value="1">{{__('order-return.product_defective')}}</option>
                                            <option value="2">{{__('order-return.cheaper_alternative')}}</option>
                                            <option value="3">{{__('order-return.product_damage_shipping')}}</option>
                                            <option value="4">{{__('order-return.incorrect_item_arrived')}}</option>
                                        </select>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($status)
                    <div class="col-md-12 col-sm-12 text-center mt-5 mb-5">
                        <a href="javascript:;" onclick="gotoDetail()" class="btn btn-return" id="submit-next-request">{{__('Next')}}</a>
                    </div>
                    @endif
                </div>
            </div>
        <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="shipping-tab">
                <!-- Form Start From Here -->
                <div class="seller_forms bg-white p-3 mb-3">
                    @foreach($orderItems as $key => $orderItem)
                    @if($orderItems->count()==1)
                        <div class="row"  id="dropzone_{{$orderItem->id}}" style="display:flex;">
                    @else
                        <div class="row"  id="dropzone_{{$orderItem->id}}" style="display:none;">
                            @endif
                            <div class="col-md-3">
                                <div class="text-note text-note2"><b>{{__('For Selected Product:')}}</b></div>
                                <div class="text-note text-note2">{{isset($orderItem->product->product_translates)? $orderItem->product->product_translates->title : $orderItem->product->title}}</div>

                            </div>
                            <div class="col-md-3 mt-3 mb-3 mb-lg-0 mt-lg-0">
                                <div class="text-note"><textarea name="note[]" placeholder="Note" value="" id="note" class="form-control"></textarea></div>
                            </div>
                            <div class="col-md-6">
                                <div class="product-subtotal">
                                    <!-- gallery -->
                                    <!-- gallery -->
                                    <input type="hidden" name="gallery[]" id="gallery-{{$loop->iteration}}" value="">
                                    <div id="dropzone" class="dropzone needsclick mb-3">
                                        <div class="dz-message needsclick">
                                            <button type="button" class="dz-button btn-primary">{{ __('Drop files here or click to upload.') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                    <div class="row mt-4">
                        <div class="col-md-12 col-sm-12 text-center">
                            <button type="submit" class="btn btn-return" id="submit-retrun-request">{{__('Return')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script type="text/javascript">
    $(function() {
        $('[data-toggle="popover"]').popover()
    });
    $('#example').popover('show');
</script>
<script type="text/javascript" src="{{ asset('assets/backend') }}/js/dropzone.min.js"></script>
<script>
    function gotoDetail() {
        if ($('#shipping-tab').hasClass('disabled')) {
            $('#shipping-tab').removeClass('disabled').addClass('disabledww');
        }
        // alert($("input[name*='order_item_id']:checked").length);
        if (($("input[name*='order_item_id']:checked").length) <= 0) {
            ShowFailureModal("{{trans('You must check at least 1 box')}}", 2000);
            return false;
        } else{
            $('a[href="#tab2"]').click();
        }
        return;
    }
    function goto(id) {
        if (!$("#checkbok_" + id).is(':checked')){
            $("#dropzone_" + id).hide();
        }
        else {
            $("#dropzone_" + id).show();
        }
        return;
    }
</script>
<script>
    Dropzone.autoDiscover = false;
    var store_order_id = <?php echo $orderItem->store_order_id; ?>;
    $(document).ready(() => {
        const dropzones = []
        $('.dropzone').each(function(i, el) {
            const name = 'g_' + $(el).data('field')
            var myDropzone = new Dropzone(el, {
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
                renameFile: function(file) {
                    let newName = new Date().getTime() + '_' + file.name.replace("=", "").replace(/\s+/g, "_").replace(/[^a-z0-9\_\-\.]/i, "");
                    return newName;
                }
            });
            dropzones.push(myDropzone)
        });
        document.querySelector("#submit-retrun-request").addEventListener("click", function(e) {
            e.preventDefault();
            e.stopPropagation();
            var count = 1;
            dropzones.forEach(dropzone => {
                var fileList = [];
                dropzone.files.forEach((file, i) => {
                    console.log(file, i);
                    fileList.push(file.upload.filename);
                })
                $('#gallery-' + count).val(fileList.toString());
                count = count + 1;
            });
            let form = $('#ReturnrequestForm');
            let form_data = new FormData($('#ReturnrequestForm')[0]);
            var id = <?php echo $orderItem->store_order_id; ?>;
            $('#loading-div-' + id).removeClass('d-none');
            $('#loading-div').removeClass('d-none');
            var album_text = [];
            $("textarea[name='note[]']").each(function() {
                var value = $(this).val();
                if (value) {
                    album_text.push(value);
                }
            });
            if (($("input[name*='order_item_id']:checked").length) <= 0) {
                ShowFailureModal("{{trans('You must check at least 1 box')}}", 2000);
                return false;
            } else if (!confirm("{{trans('Are you sure you want to submit request?')}}")) {
                return true;
            }
            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: form_data,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function(response) {
                    $('#loading-div-' + id).addClass('d-none');
                    ShowSuccessModal("{{trans('Return request has been submitted successfully')}}", 2000);
                    window.location.href = "{{ url('order') }}/" + id;
                }
            });
        });
    });
</script>
@endsection
