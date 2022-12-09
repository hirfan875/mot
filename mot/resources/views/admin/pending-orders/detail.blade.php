@extends('admin.layouts.app')

@section('content')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.pending.orders') }}">{{ __($section_title) }}</a></li>
    <li class="breadcrumb-item active">{{ __($title) }}</li>
</ol>
<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        {{ __('Items') }}
                        <x-admin.back-button :url="route('admin.pending.orders')" />
                    </div>
                    <div class="card-body">
                        <!-- alerts -->
                        <x-alert class="alert-success" :status="session('success')" />
                        <x-alert class="alert-danger" :status="session('error')" />
                        
                        <div class="table-responsiev">
                            <table class="table table-borderless table-sm">
                                <thead>
                                    <tr>
                                        <th width="55%">{{__('Product')}}</th>
                                        <th width="15%">{{__('Quantity')}}</th>
                                        <th width="15%">{{__('Unit Price')}}</th>
                                        <th width="15%" class="text-right">{{__('Total')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->order_items as $item)
                                    <tr>
                                        <td >
                                            <span><h4>{{isset($item->product->store->store_profile_translates)? $item->product->store->store_profile_translates->name : $item->product->store->name}}</h4></span>
                                        <hr>
                                        </td>
                                   </tr>
                                    <tr>
                                        <td >
                                            @if($item->product->parent_id != null)
                                                {{isset($item->product->parent->product_translates)? $item->product->parent->product_translates->title : $item->product->parent->title}}
                                                <br>
                                                <span class="admin-order-detail-arbt">{{ count(getAttributeWithOption($item->product)) > 0 ? getAttrbiuteString(getAttributeWithOption($item->product)) : null}}</span>
                                                <br/>
                                                <span style="font-weight: bolder; color: red">{{ $item->discounted_at != null ?  __('Get Free') : null}}</span>
                                                <br/>
                                                SKU: {{$item->product->sku}}
                                            @else
                                                {{isset($item->product->product_translates)? $item->product->product_translates->title : $item->product->title}}
                                                <br/>
                                                <span style="font-weight: bolder; color: red">{{ $item->discounted_at != null ?  __('Get Free') : null}}</span>
                                                <br/>
                                                SKU: {{$item->product->sku}}
                                            @endif
                                            <!--<span><h4>{{isset($item->product->store->store_profile_translates)? $item->product->store->store_profile_translates->name : $item->product->store->name}}</h4></span>-->
                                        </td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{__($order->currency->code)}}&nbsp;{{convertTryForexRate($item->unit_price, $order->forex_rate, $order->base_forex_rate, $order->currency->code)}}</td>
                                        <td class="text-right"> {{__($order->currency->code)}}&nbsp;{{convertTryForexRate($item->total, $order->forex_rate, $order->base_forex_rate, $order->currency->code)}}</td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <th width="85%" class="text-right" colspan="3">{{__('Sub Total')}}:</th>
                                        <td width="15%" class="text-right">{{__($order->currency->code)}}&nbsp;{{convertTryForexRate($order->sub_total, $order->forex_rate, $order->base_forex_rate, $order->currency->code)}}</td>
                                    </tr>
                                    <tr>
                                        <th width="85%" class="text-right" colspan="3">{{__('Delivery')}}:</th>
                                        <td width="15%" class="text-right">{{__($order->currency->code)}}&nbsp;{{convertTryForexRate($order->delivery_fee, $order->forex_rate, $order->base_forex_rate, $order->currency->code)}}</td>
                                    </tr>
                                    <tr>
                                        <th width="85%" class="text-right" colspan="3">{{__('Total')}}:</th>
                                        <td width="15%" class="text-right">{{__($order->currency->code)}}&nbsp;{{convertTryForexRate($order->total, $order->forex_rate, $order->base_forex_rate, $order->currency->code)}}</td>
                                    </tr>
                                    <tr>
                                        <td> Ordered Currency forex rate: {{$order->currency->code}} : {{ $order->forex_rate }}</td><td colspan="3"> Ordered Default Currency forex rate: {{ $order->base_forex_rate}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        {{ $title }}
                        <a class="btn btn-warning btn-sm pull-right ml-2" href="{{ route('admin.orders.detail.export', ['order' => $order->id]) }}"><i class="fa fa-arrow-down"></i> Print Invoice</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="50%">{{__('Order')}} #</td>
                                    <td width="50%">{{ $order->order_number }}</td>
                                </tr>
                                
                                <tr>
                                    <td>{{__('Customer')}}:</td>
                                    <td>{{ $order->customer->name }}</td>
                                </tr>
                                <tr>
                                    <td>{{__('Sub Total')}}:</td>
                                    <td>{{__($order->currency->code)}}&nbsp;{{convertTryForexRate($order->sub_total, $order->forex_rate, $order->base_forex_rate, $order->currency->code)}}</td>
                                </tr>
                                @if ($order->delivery_fee)
                                <tr>
                                    <td>{{__('Delivery')}}:</td>
                                    <td>{{__($order->currency->code)}}&nbsp;{{convertTryForexRate($order->delivery_fee, $order->forex_rate, $order->base_forex_rate, $order->currency->code)}}</td>
                                </tr>
                                @endif
                                @if ($order->tax)
                                <tr>
                                    <td>{{__('Tax')}}:</td>
                                    <td>{{__($order->currency->code)}}&nbsp;{{convertTryForexRate($order->tax, $order->forex_rate, $order->base_forex_rate, $order->currency->code)}}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td>{{__('Total')}}:</td>
                                    <td>{{__($order->currency->code)}}&nbsp;{{convertTryForexRate($order->total, $order->forex_rate, $order->base_forex_rate, $order->currency->code)}}</td>
                                </tr>
                                <tr>
                                    <td>{{__('Current Status')}}:</td>
                                    <td>{{ __($order->getStatus($order->status)) }}</td>
                                </tr>
                               @if ($status_buttons)
                                <tr>
                                    <td>{{__('Update Status')}}:</td>
                                    <td>
                                        @foreach ($status_buttons as $statusID => $status)
                                        <a href="{{ route('admin.orders.update.status', ['storeOrder' => $order->id, 'status' => $statusID]) }}" class="btn btn-outline-primary btn-sm mb-1">{{ $status }}</a>
                                        @endforeach
                                    </td>
                                </tr>
                                @endif
                                @if($order->status == 1 || $order->status == 2)
                                @if ($status_buttons)
                                <tr>
                                    <td>{{__('Order From')}}:</td>
                                    <td>
                                        
                                        <a href="{{ route('admin.orders.update.whatsapp', ['order' => $order->id, 'status' => $statusID]) }}" class="btn btn-outline-primary btn-sm mb-1">{{ __('Whatsapp') }}</a>
                                        
                                    </td>
                                </tr>
                                @endif
                                @endif
                                @if($order->customer->phone)
                                <tr>
                                    <td>{{__('Phone')}}:</td>
                                    <td>{{isset($order->customer->phone) ? $order->customer->phone : ''}}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td>{{__('Address')}}:</td>
                                    <td>{!! str_replace(",", "<br>", $order->address) !!}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('header')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.min.css" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet">
<link rel="stylesheet" href="//select2.github.io/select2-bootstrap-theme/css/select2-bootstrap.css" rel="stylesheet">
@endpush

@push('footer')
<x-validation />
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
<script type="text/javascript" charset="utf-8">
  $.fn.select2.defaults.set( "theme", "bootstrap" );
  $(document).ready(function(){
    $("#add_form");
    $(".select2").select2({
      width: '100%'
    });
    $('.loaddatepicker').datepicker({
      format: "yyyy-mm-dd",
      startDate: new Date(),
      autoclose: true,
      todayHighlight: true,
      orientation: "bottom auto"
    });
    $('.loadtimpicker').timepicker({
      minuteStep: 1,
      "format": "HH:mm",
      icons: {
        up: 'fa fa-arrow-up',
        down: 'fa fa-arrow-down'
      }
    });

  });
</script>
@endpush
