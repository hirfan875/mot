@extends('web.layouts.app')
@section('content')
@section('style')
<link rel="stylesheet" href="{{ asset('assets/backend') }}/css/dropzone.min.css" type="text/css"/>
@endsection
<!--=================
  Start breadcrumb
  ==================-->
<div class="breadcrumb-container">
    <h1>{{__('breadcrumb.order_detail')}}</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('breadcrumb.home')}}</a></li>
        <li class="breadcrumb-item"><a href="{{route('order-history')}}">{{__('breadcrumb.order_history')}}</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{__('breadcrumb.order_detail')}}</li>
    </ol>
</div>
<!--=================
      End breadcrumb
      ==================-->

<div class="container order-detailss">
    <!-- order details -->
    <div class="order_history_detail_outer">
        <div class="item-detail d-flex align-items-center justify-content-between">
            <!--<div class="item_number"><input  type="checkbox" id="chckBox" class="parent" onchange="fnTest(this);"><span>{{__('Order Items')}} : <strong>{{$storeOrder->getTotalQty()}}</strong></span></div>-->
            <div class="item_number_date">
                <span class="date_item">{{__('Order Date')}} : <strong>{{$storeOrder->getLastStatusUpdateDate()->format('d/m/Y')}}</strong></span>
                <span class="number_item">{{__('Order Number')}} : <strong>{{$storeOrder->order_number}}</strong></span>
            </div>
        </div>

        <div class=" ">
            <h1 class="mb-3">{{__('Ordered Items')}}</h1>
            @foreach($storeOrder->order_items as $order_item)
            <div class="order_history_detail">
                <div class="seller_block">
                    <span>{{__('Seller Name')}} : <strong><a href="{{route('shop', $order_item->product->store->slug)}}"> {{isset($order_item->product->store->store_profile_translates)? $order_item->product->store->store_profile_translates->name : $order_item->product->store->name}}</a></strong></span>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                            <tr class="order_items" >
                                <!--<td width="20" ><input type="checkbox" _id="chckBox" class="child" name="order_items[{{ $order_item->product_id }}]" value="{{ $order_item->product_id }}" checked></td>-->
                                <td width="700" class="border-0">
                                    <div class="product_d">
                                        @if( $order_item->product->parent_id != null )
                                        @if(isset($order_item->product->parent))
                                            <a href="{{$order_item->product->parent->deleted_at == null ? $order_item->product->parent->getViewRoute() : $order_item->product->getViewRoute()}}" >
                                                <img loading="lazy" class="img-fluid"  src="{{$order_item->product->parent->deleted_at == null ?  $order_item->product->parent->product_thumbnail() : $order_item->product->product_thumbnail()}}" width="69px" alt="{{$order_item->product->title}}"/>
                                            </a>
                                        @endif
                                        @else
                                            <a href="{{ $order_item->product->getViewRoute() }}" >
                                                <img loading="lazy" class="img-fluid"  src="{{ $order_item->product->product_thumbnail() }}" width="69px" alt="{{$order_item->product->title}}"/>
                                            </a>
                                        @endif
                                        
                                        <div class="ml-3 d-inline-block align-middle">
                                            <h5 class="mb-0">
                                                <a href="{{$order_item->product->getViewRoute()}}" class="text-dark d-inline-block align-middle">
                                                    @if($order_item->product->parent_id != null)
                                                        @if(isset($order_item->product->parent))
                                                            {{\Illuminate\Support\Str::limit(isset($order_item->product->parent->product_translates)? $order_item->product->parent->product_translates->title : $order_item->product->parent->title, 35)}}
                                                            <br>
                                                            <span class="order-detail-arbt">{{ count(getAttributeWithOption($order_item->product)) > 0 ? getAttrbiuteString(getAttributeWithOption($order_item->product)) : null}}</span>
                                                        @endif
                                                    @else
                                                    {{\Illuminate\Support\Str::limit(isset($order_item->product->product_translates)? $order_item->product->product_translates->title : $order_item->product->title, 35)}}
                                                    @endif
                                                </a>
                                            </h5>
                                            <span style="font-weight: bolder; color: red">{{ $order_item->discounted_at != null ?  __('Get Free') : null}}</span>
                                            <div class="skuNumber">{{__('SKU')}} : {{$order_item->product->sku}}</div>
                                            <div class="info_block">
                                                <div class="ratings"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></div>
                                                <div class="quantity_a">{{__('QTY')}} x {{$order_item->quantity}}</div>
                                                <div class="saved-shipping">{!! $order_item->discounted_at !!}</div>
                                            </div>
                                            <!--<div class="priceTag">KWD 190</div>-->
                                        </div>
                                    </div>
                                </td>
                                <td class="border-0 align-middle" width="220">
                                    @if(!in_array($storeOrder->status, [\App\Models\Order::PAID_ID, \App\Models\Order::READY_ID]))
                                    @if($order_item->isAbleToReview())
                                    <div id="review_button_{{$order_item->id}}">
                                        <a href="#" class="btn btn-outline-danger"
                                           class="seller-feedback_{{$order_item->id}}" data-toggle="modal"
                                           data-target="#sellerfeed_{{$order_item->id}}">{{__('order-success.product_review')}}</a>
                                    </div>
                                    @endif
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>


            <div class="table-responsive d-block d-md-none d-lg-none">
                <div class="p-2">
                    <a href="{{$order_item->product->getViewRoute()}}">
                        <img src="{{$order_item->product->product_listing()}}" width="69px" alt="{{$order_item->product->title}}"/></a>
                    <div class="mt-3 d-inline-block align-middle">
                        <h6 class="mb-0">
                            <a href="{{$order_item->product->getViewRoute()}}" class="text-dark d-inline-block align-middle">{{isset($order_item->product->product_translates)? $order_item->product->product_translates->title : $order_item->product->title}} <span class="order-detail-arbt">{{count(getAttributeWithOption($order_item->product)) > 0 ? getAttrbiuteString(getAttributeWithOption($order_item->product)) : null}}</span> <b>{!! $order_item->discounted_at !!}</b> </a>
                        </h6>
                        <span class="text-muted font-weight-normal  d-block text-left"><strong>{{__('Seller')}}: </strong>
                            <a href="{{route('shop', $order_item->product->store->slug)}}"> {{isset($order_item->product->store->store_profile_translates)? $order_item->product->store->store_profile_translates->name : $order_item->product->store->name}}</a></span>
                    </div>
                    <div class="mt-3 mb-3">{{__('QTY')}}: {{$order_item->quantity}}</div>
                    @if(!in_array($storeOrder->status, [\App\Models\Order::PAID_ID, \App\Models\Order::READY_ID]))
                        @if(!$order_item->hasProductReview())
                        <div id="review_button_{{$order_item->id}}">
                            <a href="#" class="btn btn-outline-danger" class="seller-feedback_{{$order_item->id}}" data-toggle="modal" data-target="#sellerfeed_{{$order_item->id}}">{{__('order-success.product_review')}}</a>
                        </div>
                        @endif
                    @endif
                </div>
            </div>
            <!-- The Modal -->
            <div class="modal fade" id="sellerfeed_{{$order_item->id}}">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"
                                id="exampleModalLabel">{{__('order-success.product_review')}}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="review-form" method="POST" id="ReviewForm_{{$order_item->id}}"
                                  action="{{route('create-customer-product-reviews')}}"
                                  enctype="multipart/form-data"
                                  data-message="{{__('Your Review is submitted.')}}">
                                @csrf
                                <input type="hidden" name="order_item_id" value="{{$order_item->id}}">
                                <input type="hidden" name="language_id"
                                       value="{{getLocaleId(app()->getLocale())}}">
                                <h5>{{__('order-success.product_name')}}
                                    : {{$order_item->product->title}}</h5>
                                <div class="form-group w-100 d-inline-block">
                                    <label for="recipient-name"
                                           class="col-form-label">{{__('order-success.rating')}} </label>
                                    <div class="star_rating">
                                        <fieldset class="rating">
                                            <input type="radio" id="star5_{{$order_item->id}}" name="rating"
                                                   value="5"/><label class="full"
                                                   for="star5_{{$order_item->id}}"
                                                   title="{{__('Awesome - 5 stars')}}"></label>
                                            <input type="radio" id="star4_{{$order_item->id}}" name="rating"
                                                   value="4"/><label class="full"
                                                   for="star4_{{$order_item->id}}"
                                                   title="{{__('Pretty good - 4 stars')}}"></label>
                                            <input type="radio" id="star3_{{$order_item->id}}" name="rating"
                                                   value="3"/><label class="full"
                                                   for="star3_{{$order_item->id}}"
                                                   title="{{__('Meh - 3 stars')}}"></label>
                                            <input type="radio" id="star2_{{$order_item->id}}" name="rating"
                                                   value="2"/><label class="full"
                                                   for="star2_{{$order_item->id}}"
                                                   title="{{__('Kinda bad - 2 stars')}}"></label>
                                            <input type="radio" id="star1_{{$order_item->id}}" name="rating"
                                                   value="1" checked/><label class="full"
                                                   for="star1_{{$order_item->id}}"
                                                   title="{{__('Sucks big time - 1 star')}}"></label>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="form-group w-100 d-inline-block">
                                    <div class="mb-3">
                                        <label for="image">{{__('mot-products.review_image')}}</label>
                                        <input type="hidden" name="gallery"
                                               id="gallery-{{$loop->iteration}}" value="">
                                        <div id="dropzone" class="dropzone needsclick mb-3">
                                            <div class="dz-message needsclick">
                                                <button type="button" class="dz-button">{{ __('Drop files here or click to upload.') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group w-100 d-inline-block">
                                    <div class="">
                                        <label for="comment">{{__('mot-products.review_comment')}}</label>
                                        <textarea required
                                                  oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"
                                                  oninput="this.setCustomValidity('')"
                                                  placeholder="{{__('Your review ')}}" class="form-control"
                                                  name="comment" rows="4"></textarea>
                                    </div>
                                </div>
                                <button type="submit" id="submit-review_{{$order_item->id}}" class="btn btn-primary">{{__('mot-products.submit_review')}}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="action_btns">
            @if(!empty($storeOrder->getPossibleStatusButton()))
                @if(in_array('Track' ,$storeOrder->getPossibleStatusButton()))
                    <a href="{{route('track-package',$storeOrder->id)}}" class="btn_return">{{__('Track Package')}}</a>
                @endif
                @if(in_array('Return' ,$storeOrder->getPossibleStatusButton()))
                    <a href="{{route('order-return-form',$storeOrder->id)}}" class="btn_return">{{__('Return')}}</a>
                @endif
                @if(in_array('Cancel' ,$storeOrder->getPossibleStatusButton()))
                    <a href="{{route('order-cancel-request', $storeOrder->id)}}" class="btn_cancl">{{__('Cancel')}}</a>
                @endif
            @endif
        </div>
    </div>

    <div class="order_history_shipping_info">
        <div class="d-flex align-items-center justify-content-between">
            <div class="shipping">
                <ul>
                    <li>{{__('Sub Total')}} <span>{{$storeOrder->currency->code}} {{convertTryForexRate($storeOrder->sub_total, $storeOrder->forex_rate, $storeOrder->base_forex_rate, $storeOrder->currency->code)}}</span></li>
                    @if($storeOrder->getDiscount() > 0)
                        <li>{{__('Discount')}} <span>{{$storeOrder->currency->code}} -{{convertTryForexRate($storeOrder->getDiscount(), $storeOrder->forex_rate, $storeOrder->base_forex_rate, $storeOrder->currency->code)}} </span></li>
                    @endif
                    <li>{{__('Free Shipping')}}  <span>{{$storeOrder->currency->code}} {{convertTryForexRate($storeOrder->delivery_fee, $storeOrder->forex_rate, $storeOrder->base_forex_rate, $storeOrder->currency->code)}}</span></li>
                    <li>{{__('Estimated Total')}} <span>{{$storeOrder->currency->code}} {{convertTryForexRate($storeOrder->total, $storeOrder->forex_rate, $storeOrder->base_forex_rate, $storeOrder->currency->code)}}</span></li>
                </ul>
            </div>
            @if($storeOrder->status < 5 && $totalDeliveryDays > 0 && $remainingDeliveryDays > 0)
            <div class="delivery">
                <p>{{__('Delivery Days')}}</p>
                <div class="progress">
                    <div class="progress-bar" role="progressbar" aria-valuenow="{{$deliveryPercent}}" aria-valuemin="0" aria-valuemax="100" style="width:{{$deliveryPercent}}%">
                        <span class="sr-only">{{$deliveryPercent}}% {{__('Complete')}}</span>
                    </div>
                </div>
                <p>{{__('Approximately ') . $remainingDeliveryDays . __(' days remaining')}}</p>
            </div>
            @endif
            <div class="oders">
                <ul>
                    <li>{{__('Order updated on')}} : <span>{{$storeOrder->getStatus()}} {{$storeOrder->getLastStatusUpdateDate()->format('d M Y')}}</span></li>
                    <li>{{__('Payment Mode')}} <span>{{$storeOrder->payment_type}}</span></li>
                    <li>{{__('Shipping Address')}}</li>
                </ul>
                <div class="adrs">
                    <p>{!! $storeOrder->address !!}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- order details page-->
<!--    <div class="bg-white pt-2 pl-5 pr-5 mt-minus">
        <div class="pb-5">
            @if(isset($returnRequests) ? $returnRequests->count(): 0  > 0)
            <div class="row">
                <div class="col-lg-12 p-3 bg-white rounded shadow-sm mb-2 mt-3">
                    <h1>{{__('Return Requests')}}</h1>
                    @foreach($returnRequests ?? '' as $returnRequest)
                    <div class="col-lg-12 p-3 bg-white rounded shadow-sm mb-2 mt-3">
                        <div class="return-request row">
                            <div class="col-md-3">
                                <h4>{{__('Status')}}</h4>
                                <p><span class="label btn-sm btn-success w-auto">{{$returnRequest->getStatus()}}</span>
                                </p>
                            </div>
                            <div class="col-md-3">
                                <h4>{{__('Requested On')}}</h4>
                                <p>{{$returnRequest->created_at->format('d F, Y')}}</p>
                            </div>
                            @if($returnRequest->isApproved())
                            <div class="col-md-3">
                                <h4>{{__('Tracking Id')}}</h4>
                                <p>{{$returnRequest->tracking_id}}</p>
                                <h4>{{__('Courier Company Name')}}</h4>
                                <p>{{$returnRequest->company_name}}</p>
                            </div>
                            <div>
                                <button type="button" data-toggle="modal" data-target="#myModal">{{__('Add Tracking Id')}}</button>
                            </div>
                            @endif
                            <div class="col-lg-12 p-3 mb-2 mt-3">
                                <h3>{{__('Returning Items')}}</h3>
                                @foreach($returnRequest->return_order_items as $returnRequestItems)
                                <p>{{$returnRequestItems->order_item->product->title}}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
             Tracking Modal Start
            <div id="myModal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <form method="POST" action="{{route('update-order-return-request')}}">
                            @csrf
                            <div class="modal-body">
                                <input type="hidden" name="store_order_id" value="{{isset($returnRequest->store_order_id) ? $returnRequest->store_order_id : ''}}">
                                <h4 class="modal-title">{{__('Tracking Id')}}</h4>
                                <input type="text" name="tracking_id">
                                <h4 class="modal-title">{{__('Company Name')}}</h4>
                                <input type="text" name="company_name">
                                <button type="submit">{{__('Submit')}}</button>
                            </div>
                        </form>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">{{__('Close')}}</button>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>-->
</div>
@endsection

@section('scripts')
<script type="text/javascript">
//    $(function () {
//        $('[data-toggle="popover"]').popover()
//    })
//    $('#example').popover('show');
</script>
<script type="text/javascript" src="{{ asset('assets/backend') }}/js/dropzone.min.js"></script>

<script>
    Dropzone.autoDiscover = false;
    var store_order_id = <?php echo $storeOrder->order_items[0]->id; ?>;
    $(document).ready(() => {
        const dropzones = []
        $('.dropzone').each(function (i, el) {
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
                renameFile: function (file) {
                    let newName = new Date().getTime() + '_' + file.name.replace("=", "").replace(/\s+/g, "_").replace(/[^a-z0-9\_\-\.]/i, "");
                    return newName;
                }
            })
            dropzones.push(myDropzone)
        })

//        document.querySelector("#submit-review_" + store_order_id).click("click", function (e) {
//
//            e.preventDefault();
//            e.stopPropagation();
//            var count = 1;
//            dropzones.forEach(dropzone => {
//                var fileList = [];
//                dropzone.files.forEach((file, i) => {
//                    // console.log(file, i);
//                    fileList.push(file.upload.filename);
//                })
//                $('#gallery-' + count).val(fileList.toString());
//                // console.log(count ,fileList.toString());
//                count = count + 1;
//            })
//        });

        $('.review-form').on('submit', function (e) {
            e.preventDefault();

            var count = 1;
            dropzones.forEach(dropzone => {
                var fileList = [];
                dropzone.files.forEach((file, i) => {
                    // console.log(file, i);
                    fileList.push(file.upload.filename);
                })
                $('#gallery-' + count).val(fileList.toString());
                // console.log(count ,fileList.toString());
                count = count + 1;
            })
            $("#spinner").html("<i class='fa fa-refresh fa-spin'></i> &nbsp;");
            var form = $(this);
            var url = form.attr('action');
            var id = form.find('input[name = order_item_id]').val();
            var order_item_id = <?php echo $storeOrder->order_items[0]->id; ?>;
            //  console.log(id);

            $.ajax({
                type: "POST",
                dataType: "json",
                url: url,
                data: form.serialize(),
                success: function (data) {
                    // show the success message from the server ...
                    let message = form.data("message") ?? data.message;
                            ShowSuccessModal(message, null);
                    $('#sellerfeed_' + id).hide();
                    $('#review_button_' + id).addClass('d-none');
                    location.reload();
                },
                error: function (data) {
                    $("#spinner").html("&nbsp;");
                    if (data.message) {
                        ShowFailureToaster(data.message);
                    }
                    // console.log(data);
                }
            });
        });
    })
</script>
<script type="text/javascript">
//    $(function() {
//        $("#allitemselect").change(function () {
//            alert('test');
//          $(this).siblings('.order_items')
//                 .find("input[type='checkbox']")
//                 .prop('checked', this.checked);
//        });
//    });

    function fnTest(check){

        if($(check).is(':checked')){
//            console.log($(check).siblings('tr').find('.child'));
            $(check).siblings('tr').find('.child').prop('checked', this.checked);
        }
        else{
            $(check).siblings('tr').find('.child').prop("checked",false);
        }

//         $(this).find('.order_items:first .child').prop('checked',$(this).is(':checked'));
    }
</script>
@endsection
