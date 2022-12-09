@component('mail::message', ['headerImageName' => 'order_confirm.png'])

<h1 class="main_title">
    {{__('Order is confirmed')}}
</h1>
<h1 style="text-align: center; padding:0px 35px">Thank you!</h1>
<p style="text-align: left !important;">For your purchase from <span style="color:#E72128 !important;">mallofturkeya.com</span></p>
<div class="order_number">
    <div class="order">{{__('Order')}}</div>
    <div class="number">#{!! $orders->order_number !!}</div>
</div>
<div class="order_number">
    <div class="order">{{__('Order Date')}}</div>
    <div class="number">{!! $orders->order_date->format('d F Y') !!}</div>
</div>
<h2 class="secondry_title">{!! $orders->customer->name !!}</h2>
<p style="text-align: left !important;">Your order is in process and will be delivered soon.</p>

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
                @foreach($orders['order_items'] as $item)
                <tr>
                    <td>
                        @if($item['product']['parent_id'] != null)
                        {!! str_replace("''","",str_replace("'","",str_replace("/","",$item['product']['parent']['title']))) !!} {!! implode(' -', getVariationNames($item['product'])) !!}  <b>{!! $item['discounted_at'] !!}</b>
                        @else
                        {!! str_replace("''","",str_replace("'","",str_replace("/","", $item['product']['title']))) !!} <b>{!! $item['discounted_at'] !!}</b>
                        @endif
                        </td>
                        <td>{!! $item['product']['store']['name'] !!}</td>
                    <td>{!! $item['unit_price'] !!}</td>
                    <td>{!! $item['quantity'] !!}</td>
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
                <td class="align-right">{!! $orders->sub_total !!}</td>
            </tr>
            @if($orders->getDiscount() > 0)
            <tr>
                <td ><strong>{{__('Discount')}}:</strong></td>
                <td class="align-right">-{!! $orders->getDiscount() !!}</td>
            </tr>
            @endif
            <tr>
                <td ><strong>{{__('Delivery Fee')}}:</strong></td>
                <td class="align-right">{!! $orders->delivery_fee !!}</td>
            </tr>
            <tr>
                <td ><strong>{{__('Order Total')}}:</strong></td>
                <td class="align-right"><span class="redMark">{{ $orders->total - $orders->getDiscount() }}</span></td>
            </tr>
        </tbody>
    </table>
</div>

<hr/>

<div class="table_area">
    <table width="100%" border="0" cellspacing="1" cellpadding="3">
        <tbody>
            <tr>
                <td width="30%"><strong>{{__('Shipping To')}} </strong></td>
                <td style="color:#666;">{!! $orders->customer->name !!}</td>
            </tr>
            <tr>
                <td><strong>{{__('Address')}}</strong></td>
                <td style="color:#666;">{!! str_replace(",", ", ", $orders->address) !!}</td>
            </tr>
        </tbody>
    </table>
  </div>
<hr/>

<h3>For further details contact us on : +965 99732998 | +90 5355103999 </h3>

@endcomponent
