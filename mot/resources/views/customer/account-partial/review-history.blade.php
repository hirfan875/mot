<div class="tab-pane fade active show" id="history" aria-labelledby="user-requests-tab">

    <div class="table-content table-responsive mb-45 tbUser">
        <h2 class="mt-4 mb-3">{{__('Seller Review')}}</h2>
        @if($storeOrders)
        @foreach($storeOrders as $storeOrder)
        <div class="table-responsive">
            <table class="table">
                <tbody>
                    <tr class="table-active table-border-double">
                        <td class="text-left text-nowrap">{{__('Order Number')}}: <a href="{{route('order-detail' ,$storeOrder->id) }}"></a>{{$storeOrder->order_number}}</td>
                        <td><span class="active_green"></span>{{$storeOrder->getStatus()}} {{$storeOrder->getLastStatusUpdateDate()->format('d M Y')}}</td>
                        <td><span class="font-weight-normal  d-block"><b>{{__($storeOrder->order->currency)}}&nbsp;{{convertTryForexRate($storeOrder->total, $storeOrder->order->forex_rate, $storeOrder->order->base_forex_rate, $storeOrder->order->currency)}} </b> </span></td>
                        <td class="text-right text-nowrap">
                            @if(in_array($storeOrder->status, $storeOrder->isReviewable()))
                           <p  class="btn btn-default">{{__('Click on feedback button')}}</p>
                            @endif
                        </td>
                        <td class="text-right text-nowrap pr-3">
                            @if(in_array($storeOrder->status, $storeOrder->isReviewable()))
                            <a href="#" class="btn btn-outline-danger" class="seller-feedback" data-toggle="modal" data-target="#sellerfeed_{{$storeOrder->order_number}}">{{__('Feedback')}}</a>
                            @endif
                        </td>
                    </tr>
                    @foreach($storeOrder->order_items as $orderItem)
                    <tr>
                        <td scope="row" class="border-0 text-left" colspan="3">
                            <div class="p-2">
                                <a href="{{$orderItem->product->getViewRoute()}}"><img src="{{$orderItem->product->parent_id != null ? $orderItem->product->parent->product_thumbnail() : $orderItem->product->product_thumbnail()}}" width="69px" alt="{{$orderItem->product->title}}"/></a>
                                <div class="ml-3 d-inline-block align-middle">
                                    <h6 class="mb-0"> <a href="{{$orderItem->product->getViewRoute()}}" class="text-dark d-inline-block align-middle">{{isset($orderItem->product->product_translates)? $orderItem->product->product_translates->title : $orderItem->product->title}} <b>{!! $orderItem->discounted_at !!}</b></a></h6>
                                    <span class="text-muted font-weight-normal  d-block text-left"><strong>Seller: </strong><a href="{{route('shop', $orderItem->product->store->slug)}}"> {{isset($orderItem->product->store->store_profile_translates)? $orderItem->product->store->store_profile_translates->name : $orderItem->product->store->name}}</a></span>
                                </div>
                            </div>
                        </td>
                        <td class="border-0 align-middle" width="320">{{__('QTY')}}: {{$orderItem->quantity}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <hr>
        <!-- The Modal -->
        <div class="modal fade" id="sellerfeed_{{$storeOrder->order_number}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{__('Seller Feedback')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{route('store-review')}}">
                            @csrf
                            <input type="hidden" name="language_id" value="{{getLocaleId(app()->getLocale())}}">
                            <h6>Order Number : # {{$storeOrder->order_number}}</h6>
                            <div class="form-group" hidden>
                                <label for="recipient-name" class="col-form-label">{{__('Store Id')}}</label>
                                <input type="text" class="form-control" name="store_id" value="{{$storeOrder->store_id}}">
                            </div>
                            <div class="form-group" hidden>
                                <label for="recipient-name" class="col-form-label">{{__('Customer Id')}}</label>
                                <input type="text" class="form-control" name="customer_id" value="{{ Auth::guard('customer')->user() != null ? Auth::guard('customer')->user()->id : null }}">
                            </div>
                            <div class="form-group" hidden>
                                <label for="recipient-name" class="col-form-label">{{__('Store Order Id')}}</label>
                                <input type="text" class="form-control" name="store_order_id" value="{{$storeOrder->id}}">
                            </div>
                            <div class="form-group" hidden>
                                <label for="recipient-name" class="col-form-label">{{__('Is Approved')}}</label>
                                <input type="text" class="form-control" name="is_approved" value="1">
                            </div>
                            <div class="form-group">
                                <fieldset class="rating">
                                    <input type="radio" id="star5_{{$storeOrder->id}}" name="rating" value="5" /><label class="full" for="star5_{{$storeOrder->id}}" title="{{__('Awesome - 5 stars')}}"></label>
                                    <input type="radio" id="star4_{{$storeOrder->id}}" name="rating" value="4" /><label class="full" for="star4_{{$storeOrder->id}}" title="{{__('Pretty good - 4 stars')}}"></label>
                                    <input type="radio" id="star3_{{$storeOrder->id}}" name="rating" value="3" /><label class="full" for="star3_{{$storeOrder->id}}" title="{{__('Meh - 3 stars')}}"></label>
                                    <input type="radio" id="star2_{{$storeOrder->id}}" name="rating" value="2" /><label class="full" for="star2_{{$storeOrder->id}}" title="{{__('Kinda bad - 2 stars')}}"></label>
                                    <input type="radio" id="star1_{{$storeOrder->id}}" name="rating" value="1" checked /><label class="full" for="star1_{{$storeOrder->id}}" title="{{__('Sucks big time - 1 star')}}"></label>
                                </fieldset>
                            </div>

                            <div class="form-group">
                                <label for="message-text" class="col-form-label">{{__('Feedback')}}:</label>
                                <textarea class="form-control" name="comment" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">{{__('Send')}}</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        @endforeach
        @else
        <div>{{__('You have no orders yet.')}} </div>
        @endif
    </div>
</div>
<!-- History End -->

<!-- The Modal -->
