@extends('admin.layouts.app')

@section('content')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.return.requests') }}">{{ __($section_title) }}</a></li>
    <li class="breadcrumb-item active">{{ __($title) }}</li>
  </ol>
  <div class="container-fluid">
    <div class="animated fadeIn">
      <div class="row">
        <div class="col-lg-8">
          <div class="card">
            <div class="card-header">
              {{ __('Items') }}
              <x-admin.back-button :url="route('admin.return.requests')" />
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
                    @php
                      $final_total = 0;
                    @endphp
                    @foreach ($row->return_order_items as $item)
                    @php
                      $total = $item->quantity * $item->order_item->unit_price;
                      $final_total = $final_total + $total;
                    @endphp
                    <tr>
                      <td>{{ $item->order_item->product->product_translates ? $item->order_item->product->product_translates->title : $item->order_item->product->title }}</td>
                      <td>{{ $item->quantity }}</td>
                      <td>{{__($row->store_order->order->currency->code)}}&nbsp;{{convertTryForexRate($item->order_item->unit_price, $row->store_order->order->forex_rate, $row->store_order->order->base_forex_rate, $row->store_order->order->currency->code)}}</td>
                      <td class="text-right">{{__($row->store_order->order->currency->code)}}&nbsp;{{convertTryForexRate($total, $row->store_order->order->forex_rate, $row->store_order->order->base_forex_rate, $row->store_order->order->currency->code)}}</td>
                    </tr>
                    @endforeach
                    <tr>
                      <th width="85%" class="text-right" colspan="3">{{__('Total:')}}</th>
                      <td width="15%" class="text-right">
                          {{__($row->store_order->order->currency->code)}}&nbsp;{{convertTryForexRate($final_total, $row->store_order->order->forex_rate, $row->store_order->order->base_forex_rate, $row->store_order->order->currency->code)}}
                          </td>
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
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-sm table-borderless">
                  <tr>
                    <td width="50%">{{__('Order')}} #</td>
                    <td width="50%">{{ $row->store_order->order->order_number }}</td>
                  </tr>
                  <tr>
                    <td width="50%">{{__('Store Order')}} #</td>
                    <td width="50%">{{ $row->store_order->order_number }}</td>
                  </tr>
                  <tr>
                    <td>{{__('Customer')}}:</td>
                    <td>{{ $row->store_order->order->customer->name }}</td>
                  </tr>
                  <tr>
                    <td>{{__('Order Status')}}:</td>
                    <td>{{__($row->getStatus($row->store_order->status))}}</td>
                  </tr>
                  <tr>
                    <td>{{__('Address')}}:</td>
                    <td>{!! str_replace(",", "<br>", $row->store_order->order->address) !!}</td>
                  </tr>
                  <tr>
                    <td>{{__('Tracking ID')}}:</td>
                    <td>{{ $row->tracking_id }}</td>
                  </tr>
                  <tr>
                    <td>{{__('Company')}}:</td>
                    <td>{{ $row->company_name }}</td>
                  </tr>
                  <tr>
                      <td colspan="2">{{__('Notes')}}:<br>
                      @php
                          $note = array();
                              foreach($row->return_order_items as $return){
                                    $note[] =  $return->note;
                             }
                      @endphp

                          {{implode(' ,',$note)}}
                      </td>
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
