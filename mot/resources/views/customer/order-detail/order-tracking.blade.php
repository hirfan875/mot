@extends('web.layouts.app')
@section('content')
<!--=================
  Start breadcrumb
  ==================-->
<div class="breadcrumb-container">
    <h1>{{__('breadcrumb.order_tracking')}}</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('breadcrumb.home')}}</a></li>
        <li class="breadcrumb-item"><a href="{{route('order-history')}}">{{__('breadcrumb.order_history')}}</a></li>
        <li class="breadcrumb-item"><a href="{{route('order-detail' ,$order->id) }}">{{__('breadcrumb.order_detail')}}</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{__('breadcrumb.order_tracking')}}</li>
    </ol>
</div>
<!--=================
      End breadcrumb
      ==================-->
<div class="container">
    <!--=================
      Start Order Tracking
      ==================-->
    <div class="order-tracking mt-minus bg-white p-2 p-md-5">
        <h2>{{__('Order Details')}}</h2>
        <div class="row mt-4">
            <div class="col-md-12">
                <p class="float-left">{{__('Order')}} <b class="color-primary"><span class="thash"> # {{$order->order_number}}</span><b> <br> {{__('Total')}}:</b> <span>{{$order->currency->code}} {{convertTryForexRate($order->total, $order->forex_rate, $order->base_forex_rate, $order->currency->code)}}</span></b>
                </p>
                <p class="float-right">
                    <span class="d-block">{{__('Placed on')}} {{ $order->created_at->format('d F Y') }}</span>
                </p>
            </div>
        </div>
        <div class="col-md-12 text-center mb-5">
            <!-- Add class 'active' to progress -->
            <ol class="progtrckr" data-progtrckr-steps="5">
                <li class={{in_array('Confirmed' ,$order->getTrackStatus()) ? "progtrckr-done" : "progtrckr-todo" }}>{{__('Confirmed')}}</li>
                <li class={{in_array('Processing' ,$order->getTrackStatus()) ? "progtrckr-done" : "progtrckr-todo" }}>{{__('Processing')}}</li>
                <li class={{in_array('On-the-way' ,$order->getTrackStatus()) ? "progtrckr-done" : "progtrckr-todo" }}>{{__('On the way')}}</li>
                <li class={{in_array('Delivered' ,$order->getTrackStatus()) ? "progtrckr-done" : "progtrckr-todo" }}>{{__('Delivered')}}</li>
            </ol>
            @if(in_array('Delivered' ,$order->getTrackStatus()))
            <div class="card delivery_message p-4">
                <!-- <div class="arrow-up"></div> -->
                <p>{{$order->updated_at->format('d F Y H:i:s')}} <span class="pl-3 font-weight-bold">{{__('Your package has been delivered. Thank you for shopping at MOT!')}}</span>
                </p>
            </div>
            @endif
        </div>
        <!-- Part 01 -->
        <div class="card mt-4 pb-5">

            <hr class="m-0">
            <div class="row d-flex justify-content-between p-3">
                <div class="col-md-6">
                    @if(in_array('Delivered' ,$order->getTrackStatus()))
                    <h5>{{__('Delivered on')}} {{$order->updated_at->format('d F Y H:i:s')}}</h5>
                    @endif
                </div>
                <div class="col-md-6 text-sm-right">
                    <!-- <p class="mb-0"><i class="icon-present"></i> {{__('Standard')}}</p> -->
                </div>
            </div>
            <div class="table-content table-responsive  mt-3 mb-3 p-3">
                <table>
                    <tbody>
                        @foreach($order->order_items as $order_item )
                        <tr>
                            <td class="product-thumbnail">
                                <a href="{{ $order_item->product->getViewRoute()}}"><img src="{{$order_item->product->product_listing()}}" alt="{{$order_item->product->title}}"></a>
                            </td>
                            <td class="product-name">
                                <a href="{{ $order_item->product->getViewRoute()}}">
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
                                <a href="{{route('shop', $order_item->product->store->slug)}}">{{isset($order_item->product->store->store_profile_translates)? $order_item->product->store->store_profile_translates->name : $order_item->product->store->name}}</a>
                            </td>
                            <td class="product-price"><span class="amount">{{$order->currency->code}} {{convertTryForexRate($order_item->unit_price, $order->forex_rate, $order->base_forex_rate, $order->currency->code)}}</span></td>
                            <td class="product-quantity">
                                <p><b>{{__('Qty')}}:</b> {{$order_item->quantity}}</p>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-md-12 text-center d-none">
                <!-- Add class 'active' to progress -->
                <ol class="progtrckr" data-progtrckr-steps="5">
                    <li class={{in_array('Confirmed' ,$order->getTrackStatus()) ? "progtrckr-done" : "progtrckr-todo" }}>{{__('Confirmed')}}</li>
                    <li class={{in_array('Processing' ,$order->getTrackStatus()) ? "progtrckr-done" : "progtrckr-todo" }}>{{__('Processing')}}</li>
                    <li class={{in_array('On-the-way' ,$order->getTrackStatus()) ? "progtrckr-done" : "progtrckr-todo" }}>{{__('On the way')}}</li>
                    <li class={{in_array('Delivered' ,$order->getTrackStatus()) ? "progtrckr-done" : "progtrckr-todo" }}>{{__('Delivered')}}</li>
                </ol>
                @if(in_array('Delivered' ,$order->getTrackStatus()))
                <div class="card delivery_message p-4">
                    <!-- <div class="arrow-up"></div> -->
                    <p>{{$order->updated_at->format('d F Y H:i:s')}} <span class="pl-3 font-weight-bold">{{__('Your package has been delivered. Thank you for shopping at MOT!')}}</span>
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="row mt-3">
    </div>
</div>
<!--=================
  End Order Tracking
  ==================-->

@endsection

{{--@section('scripts')--}}
{{--@endsection--}}
