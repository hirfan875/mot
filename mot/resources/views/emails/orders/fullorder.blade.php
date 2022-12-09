@php
    $headerImage = 'order_confirm.png';

    if($order->status == 4){
        $headerImage = 'shipped1.png';
    }

    if($order->status == 5){
        $headerImage = 'order_cancel2.png';
    }

@endphp
@component('mail::message', ['headerImageName' => $headerImage])

<!-- comment -->
@if($order->status == 1)
<h1 class="main_title">    {{__('Order ')}} {{ __('Confirmed') }}</h1>
<h2 class="secondry_title">{{__('Dear')}} {{ $order->customer->name }}</h2>
<div class="order_number">
    <div class="order">{{__('Order')}}</div>
    <div class="number">#{{ $order->order_number }}</div>
</div>
<p>{{__('Your order has been confirmed') }}</p>
@endif

@if($order->status == 2)
<h1 class="main_title">    {{__('Order ')}} {{ __('Paid') }}</h1>
<h2 class="secondry_title">{{__('Dear')}} {{ $order->customer->name }}</h2>
<div class="order_number">
    <div class="order">{{__('Order')}}</div>
    <div class="number">#{{ $order->order_number }}</div>
</div>
<p>{{__('Your order has been paid') }}</p>
@endif

@if($order->status == 3)
<h1 class="main_title">    {{__('Your order is ')}} {{ __('ready to be shipped') }}</h1>
<h2 class="secondry_title">{{__('Dear')}} {{ $order->customer->name }}</h2>
<div class="order_number">
    <div class="order">{{__('Order No')}}</div>
    <div class="number">#{!! $order->order_number !!}</div>
    <div class="order"> Order Date </div>
    <div class="number"> {!! date('d-m-Y', strtotime($order->order_date)) !!} </div>
</div>
<p>If you wish to cancel this order then please go to order history page available on my account.</p>
@endif

@if($order->status == 4)
<h1 class="main_title">{{__('Your order has been shipped')}}</h1>
<h2 class="secondry_title">{{__('Dear')}}, {{ $order->customer->name }}</h2>
<p style="text-align: left !important;">{{__('Great news your order is on it’s way! You can check your shipment details or track order by clicking on the button below.')}}</p>
<div class="link"><a href="{{route('track-package',$order->id)}}">{{__('Track Your Order')}}</a></div>
<div class="order_number">
    <div class="order">{{__('Order')}}</div>
    <div class="number">#{{ $order->order_number }}</div>
    <div class="order"> Order Date </div>
    <div class="number"> {!! date('d-m-Y', strtotime($order->order_date)) !!} </div>
</div>
<p>{{__('Your order has been Shipped')}}</p>
@endif

@if($order->status == 5)
<h1 class="main_title">{{__('Your order has been delivered')}}</h1>
<h2 class="secondry_title">{{__('Dear')}} {{ $order->customer->name }}</h2>
<p style="text-align: left !important;">{{__("Click the button below to track the delivery details of your shipment")}}</p>
<div class="link"><a href="{{route('track-package',$order->id)}}">{{__('Track Your Order')}}</a></div>
<div class="order_number">
    <div class="order">{{__('Order')}}</div>
    <div class="number">#{{ $order->order_number }}</div>
    <div class="order">Order Date</div>
    <div class="number"> {!! date('d-m-Y', strtotime($order->order_date)) !!} </div>
</div>
@endif

@if($order->status == 6)
<h1 class="main_title">    {{__('Order ')}} {{ __('Cancellation Requested') }}</h1>
<h2 class="secondry_title">{{__('Dear')}} {{ $order->customer->name }}</h2>
<p>{{__('Your order has been successfully CANCELED. Please retain this cancellation information for your records. You can find all your details below.') }}</p>
<div class="order_number">
    <div class="order">{{__('Order')}}</div>
    <div class="number">#{{ $order->order_number }}</div>
</div>
@endif

@if($order->status == 7)
<h1 class="main_title"> {{__('Your order has been cancelled')}}</h1>
<h2 class="secondry_title">{{__('Dear')}} {{ $order->customer->name }}</h2>
<p>{{__('Your order cancellation request has been approved, soon your amount will be refunded.') }}</p>
<div class="order_number">
    <div class="order">{{__('Order')}}</div>
    <div class="number">#{{ $order->order_number }}</div>
    <div class="order">Order Date</div>
    <div class="number"> {!! date('d-m-Y', strtotime($order->order_date)) !!} </div>
</div>
@endif

@if($order->status == 8)
<h1 class="main_title">    {{__('Order ')}} {{ __('Return Requested') }}</h1>
<h2 class="secondry_title">{{__('Dear')}} {{ $order->customer->name }}</h2>
<div class="order_number">
    <div class="order">{{__('Order')}}</div>
    <div class="number">#{{ $order->order_number }}</div>
</div>
<p>{{__('Your purchase of product has been refunded. It may take up-to -7 days to show up in your account.')}}</p>
<p>{{__('Best,')}}</p>
<p>{{__('The Mall of Turkeya Team,')}}</p>
<p>{{__('On successful review, you will receive a shipment label to send the product back to us.')}}</p>
@endif

@if($order->status == 9)
<h1 class="main_title">    {{__('Order ')}} {{ __('Delivery Failure') }}</h1>
<h2 class="secondry_title">{{__('Dear')}}{{ $order->customer->name }}</h2>
<div class="order_number">
    <div class="order">{{__('Order')}}</div>
    <div class="number">#{{ $order->order_number }}</div>
</div>
<p>{{ __('Delivery Failure') }}</p>
@endif

@if($order->status == 11)
<h1 class="main_title">    {{__('Order ')}} {{ __('TERMINATED') }}</h1>
<h2 class="secondry_title">{{__('Dear')}}{{ $order->customer->name }}</h2>
<div class="order_number">
    <div class="order">{{__('Order')}}</div>
    <div class="number">#{{ $order->order_number }}</div>
</div>
<p>{{ __('Delivery Failure') }}</p>
@endif

<div class="table_container mt_top1">
    <div class="table_area mt_top1 ">
        <table width="100%" border="1"  cellspacing="1" cellpadding="3" style='border-collapse:collapse'>
            <thead>
                <tr>
                    <th>{{__('Product Name')}}</th>
                    <th>{{__('Store Name')}}</th>
                    <th>{{__('Price')}}</th>
                    <th>{{__('Qty')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->order_items as $item)
                <tr>
                    <td>
                        @if($item->product->parent_id != null)
                            {!! str_replace("''","",str_replace("'","",str_replace("/","",$item->product->parent->title))) !!} {!! implode(' -', getVariationNames($item->product)) !!}  <b>{!! $item->discounted_at !!}</b>
                        @else
                           {!! str_replace("''","",str_replace("'","",str_replace("/","", $item->product->title))) !!}  <b>{!! $item->discounted_at !!}</b>
                        @endif
                    </td>
                    <td>{!! $item->product->store->name !!}</td>
                    <td>{!! $item->unit_price !!}</td>
                    <td>{!! $item->quantity !!}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<hr>
<div class="table_area mt_top1 ">
    <table width="100%" border="0" cellspacing="1" cellpadding="1">
        <tbody>
<!--            <tr>
                <td ><strong>Shipping Method</strong></td>
                <td class="align-right">DHL</td>
            </tr>
            <tr>
                <td ><strong>Payment Method</strong></td>
                <td class="align-right">MyFatoorah</td>
            </tr>-->
            <tr>
                <td ><strong>{{__('Sub Total')}}:</strong></td>
                <td class="align-right">{!! $order->sub_total !!}</td>
            </tr>
            <tr>
                <td ><strong>{{__('Delivery Fee')}}:</strong></td>
                <td class="align-right">{!! $order->delivery_fee !!}</td>
            </tr>
            @if($order->getDiscount() > 0)
            <tr>
                <td ><strong>{{__('Discount')}}:</strong></td>
                <td class="align-right">-{!! $order->getDiscount() !!}</td>
            </tr>
            @endif
            <tr>
                <td ><strong>{{__('Order Total')}}:</strong></td>
                <td class="align-right"><span class="redMark">{!! $order->total !!}</span></td>
            </tr>
        </tbody>
    </table>
</div>
<hr/>
<div class="table_area">
    <table width="100%" border="0" cellspacing="1" cellpadding="3">
        <tbody><tr>
                <td width="30%"><strong>{{__('Shipping To')}} </strong></td>
                <td style="color:#666;">{!! $order->customer->name !!}</td>
            </tr>
            <tr>
                <td><strong>{{__('Address')}}</strong></td>
                <td style="color:#666;">{!! str_replace(",", ", ", $order->address) !!}</td>
            </tr>
        </tbody>
    </table>
</div>
<hr/>
@if($order->status == 5)
{!! "<p>Haven't received your package yet? <strong>Let us Know</strong></p>" !!}
@endif
@endcomponent
