@php
    $headerImage = 'order_confirm.png';

    if($orders->status == 4){
        $headerImage = 'shipped1.png';
    }

    if($orders->status == 5){
        $headerImage = 'order_cancel2.png';
    }

@endphp
@component('mail::message', ['headerImageName' => $headerImage])

<!-- comment -->
@if($orders->status == 1)
<h1 class="main_title">    {{__('Order ')}} {{ __('Confirmed') }}</h1>
<h2 class="secondry_title">Hello Admin</h2>
<div class="order_number">
    <div class="order">{{__('Order')}}</div>
    <div class="number">#{{ $orders->order->order_number }}</div>
</div>
<p>{{__('Your order has been confirmed') }}</p>
@endif

@if($orders->status == 2)
<h1 class="main_title">    {{__('Order ')}} {{ __('Paid') }}</h1>
<h2 class="secondry_title">Hello Admin</h2>
<div class="order_number">
    <div class="order">{{__('Order')}}</div>
    <div class="number">#{{ $orders->order->order_number }}</div>
</div>
@endif

@if($orders->status == 3)
<h1 class="main_title">    {{__('Your order is ')}} {{ __('ready to be shipped') }}</h1>
<h2 class="secondry_title">Hello Admin</h2>
<div class="order_number">
    <div class="order">{{__('Order No')}}</div>
    <div class="number">#{!! $orders->order->order_number !!}</div>
    <div class="order"> Order Date </div>
    <div class="number"> {!! date('d-m-Y', strtotime($orders->order_date)) !!} </div>
</div>
@endif

@if($orders->status == 4)
<h1 class="main_title">{{__('Your order has been shipped')}}</h1>
<h2 class="secondry_title">Hello Admin</h2>
<div class="link"><a href="{{route('track-package',$orders->id)}}">{{__('Track Your Order')}}</a></div>
<div class="order_number">
    <div class="order">{{__('Order')}}</div>
    <div class="number">#{{ $orders->order->order_number }}</div>
    <div class="order"> Order Date </div>
    <div class="number"> {!! date('d-m-Y', strtotime($orders->order_date)) !!} </div>
</div>
@endif

@if($orders->status == 5)
<h1 class="main_title">{{__('Your order has been delivered')}}</h1>
<h2 class="secondry_title">Hello Admin</h2>
<div class="order_number">
    <div class="order">{{__('Order')}}</div>
    <div class="number">#{{ $orders->order->order_number }}</div>
    <div class="order">Order Date</div>
    <div class="number"> {!! date('d-m-Y', strtotime($orders->order_date)) !!} </div>
</div>
@endif

@if($orders->status == 6)
<h1 class="main_title">    {{__('Order ')}} {{ __('Cancellation Requested') }}</h1>
<h2 class="secondry_title">Hello Admin</h2>
<div class="order_number">
    <div class="order">{{__('Order')}}</div>
    <div class="number">#{{ $orders->order->order_number }}</div>
</div>
@endif

@if($orders->status == 7)
<h1 class="main_title"> {{__('Your order has been cancelled')}}</h1>
<h2 class="secondry_title">Hello Admin</h2>
<div class="order_number">
    <div class="order">{{__('Order')}}</div>
    <div class="number">#{{ $orders->order->order_number }}</div>
    <div class="order">Order Date</div>
    <div class="number"> {!! date('d-m-Y', strtotime($orders->order_date)) !!} </div>
</div>
@endif

@if($orders->status == 8)
<h1 class="main_title">{{__('Order ')}} {{ __('Return Requested') }}</h1>
<h2 class="secondry_title">Hello Admin</h2>
<div class="order_number">
    <div class="order">{{__('Order')}}</div>
    <div class="number">#{{ $orders->order->order_number }}</div>
</div>
@endif

@if($orders->status == 9)
<h1 class="main_title">{{__('Order ')}} {{ __('Delivery Failure') }}</h1>
<h2 class="secondry_title">Hello Admin</h2>
<div class="order_number">
    <div class="order">{{__('Order')}}</div>
    <div class="number">#{{ $orders->order->order_number }}</div>
</div>
@endif

@if($orders->status == 11)
<h1 class="main_title">{{__('Order ')}} {{ __('TERMINATED') }}</h1>
<h2 class="secondry_title">Hello Admin</h2>
<div class="order_number">
    <div class="order">{{__('Order')}}</div>
    <div class="number">#{{ $orders->order->order_number }}</div>
</div>
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
                @foreach($orders->order_items as $item)
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
            </tr>-->
<!--            <tr>
                <td ><strong>Payment Method</strong></td>
                <td class="align-right">MyFatoorah</td>
            </tr>-->
            <tr>
                <td ><strong>{{__('Sub Total')}}:</strong></td>
                <td class="align-right">{!! $orders->sub_total !!}</td>
            </tr>
            <tr>
                <td ><strong>{{__('Delivery Fee')}}:</strong></td>
                <td class="align-right">{!! $orders->delivery_fee !!}</td>
            </tr>
            @if($orders->getDiscount() > 0)
            <tr>
                <td ><strong>{{__('Discount')}}:</strong></td>
                <td class="align-right">-{!! $orders->getDiscount() !!}</td>
            </tr>
            @endif
            <tr>
                <td ><strong>{{__('Order Total')}}:</strong></td>
                <td class="align-right"><span class="redMark">{!! $orders->total !!}</span></td>
            </tr>
        </tbody>
    </table>
</div>
<hr/>
<div class="table_area">
    <table width="100%" border="0" cellspacing="1" cellpadding="3">
        <tbody><tr>
                <td width="30%"><strong>{{__('Shipping To')}} </strong></td>
                <td style="color:#666;">{!! $orders->customer->name !!}</td>
            </tr>
            <tr>
                <td><strong>{{__('Address')}}</strong></td>
                <td style="color:#666;">{!! str_replace(",", ", ", $orders->order->address) !!}</td>
            </tr>
        </tbody>
    </table>
</div>
<hr/>
@endcomponent
