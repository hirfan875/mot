@extends('seller.layouts.app')

@section('content')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('seller.pending.orders') }}">{{ __($section_title) }}</a></li>
    <li class="breadcrumb-item active">{{ __($title) }}</li>
  </ol>
  <div class="container-fluid">
    <div class="animated fadeIn">
      <div class="row">
        <div class="col-lg-8">
          <div class="card">
            <div class="card-header">
              {{ __('Items') }}
              <x-admin.back-button :url="route('seller.pending.orders')" />
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
                    @foreach ($row->order_items as $item)
                    <tr>
                        <td>{{ $item->product->product_translates ? $item->product->product_translates->title : $item->product->title }}</td>
                      <td>{{ $item->quantity }}</td>
                      <td>{{ $row->order->currency->code }} {{ currency_format($item->unit_price) }}</td>
                      <td class="text-right">{{ $row->order->currency->code }} {{ currency_format($item->total) }}</td>
                    </tr>
                    @endforeach
                    <tr>
                      <th width="85%" class="text-right" colspan="3">{{__('Sub Total')}}:</th>
                      <td width="15%" class="text-right">{{ $row->order->currency->code }} {{ currency_format($row->sub_total) }}</td>
                    </tr>
                    <tr>
                      <th width="85%" class="text-right" colspan="3">{{__('Delivery')}}:</th>
                      <td width="15%" class="text-right">{{ $row->order->currency->code }} {{ currency_format($row->delivery_fee) }}</td>
                    </tr>
                    <tr>
                      <th width="85%" class="text-right" colspan="3">{{__('Total')}}:</th>
                      <td width="15%" class="text-right">{{ $row->order->currency->code }} {{ currency_format($row->total) }}</td>
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
                    <td width="50%">{{ $row->order_number }}</td>
                  </tr>
                  <tr>
                    <td>{{__('Customer')}}:</td>
                    <td>{{ $row->order->customer->name }}</td>
                  </tr>
                  <tr>
                    <td>{{__('Sub Total')}}:</td>
                    <td>{{ $row->order->currency->code }} {{ currency_format($row->sub_total) }}</td>
                  </tr>
                  @if ($row->delivery_fee)
                  <tr>
                    <td>{{__('Devliery')}}:</td>
                    <td>{{ $row->order->currency->code }} {{ currency_format($row->delivery_fee) }}</td>
                  </tr>
                  @endif
                  @if ($row->tax)
                  <tr>
                    <td>{{__('Tax')}}:</td>
                    <td>{{ $row->order->currency->code }} {{ currency_format($row->tax) }}</td>
                  </tr>
                  @endif
                  <tr>
                    <td>{{__('Total')}}:</td>
                    <td>{{ $row->order->currency->code }} {{ currency_format($row->total) }}</td>
                  </tr>
                  <tr>
                    <td>{{__('Current Status')}}:</td>
                    <td>{{ $row->getStatus($row->status) }}</td>
                  </tr>
                  @if ($status_buttons)
                  <tr>
                    <td>{{__('Update Status')}}:</td>
                    <td>
                    @foreach ($status_buttons as $statusID => $status)
                        @if($status == 'Shipped')
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">{{ __('Print Lable') }}</button>
                            @if($row->shipment_reponse)
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal1">{{ __('Pickup Request') }}</button>
                            @endif
                        @endif
                    @endforeach
                    </td>
                  </tr>
                  @endif
                    @if($row->order->customer->phone)
                        <tr>
                            <td>{{__('Phone')}}:</td>
                            <td>{{isset($row->order->customer->phone) ? $row->order->customer->phone : ''}}</td>
                        </tr>
                    @endif
                  <tr>
                    <td>{{__('Address')}}:</td>
                    <td>{!! str_replace(",", "<br>", $row->order->address) !!}</td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

<!-- The Modal -->
<div class="modal" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">{{__('Create Shipment')}}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            @php
            $insured_value = isset($row->shipment_requests->insured_value) ? $row->shipment_requests->insured_value : '0';
            $weight = isset($row->shipment_requests->weight) ? $row->shipment_requests->weight : '0.2';
            $length = isset($row->shipment_requests->length) ? $row->shipment_requests->length : '0';
            $width = isset($row->shipment_requests->width) ? $row->shipment_requests->width : '0';
            $height = isset($row->shipment_requests->height) ? $row->shipment_requests->height : '0';
            $shiptimestamp = isset($row->shipment_requests->shiptimestamp) ? $row->shipment_requests->shiptimestamp : date('Y-m-d', strtotime('+1 week'));
            $customer_references = isset($row->shipment_requests->customer_references) ? $row->shipment_requests->customer_references : 'TEST TR-KW';
            @endphp
            <!-- Modal body -->
            <div class="modal-body">
                <form action="{{ route('admin.shipment.request', ['storeOrder' => $row->id]) }}" method="POST" id="add_form">
                    @csrf
                    <div class="form-group">
                        <label for="weight">{{ __('Weight') }}</label>
                        <input type="number" name="weight" id="weight" class="form-control" value="{{ old('weight',$weight) }}" step="0.01" />
                        @error('weight')
                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ $message }}</strong> </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="shiptimestamp">{{ __('Shipment Date') }}</label>
                        <input type="text" name="shiptimestamp" id="shiptimestamp" class="form-control loaddatepicker" value="{{ old('shiptimestamp',$shiptimestamp) }}" autocomplete="off" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')" onkeypress="return false;">
                        @error('shiptimestamp')
                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="ship_time">{{ __('Shipment Time') }}</label>
                        <input id="ship_time" type="text" class="form-control loadtimpicker" name="ship_time" autocomplete="off" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')" onkeypress="return false;">
                        @error('ship_time')
                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="data">{{ __('Customer References') }}</label>
                        <textarea name="customer-references" id="customer-references" cols="30" rows="5" class="form-control TinyEditor">{!! old('customer-references',$customer_references) !!}</textarea>
                    </div>
                    <div class="text-center">
                        <!-- submit button -->
                        <x-admin.publish-button />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- The Modal -->
<div class="modal" id="myModal1">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">{{__('PickUp Request')}}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <form action="{{ route('admin.pickup.request', ['storeOrder' => $row->id]) }}" method="POST" id="add_form">
                    @csrf
                    <input type="hidden" name="weight" id="weight" class="form-control" value="{{$weight}}"  />
                    <input type="hidden" name="length" id="length" class="form-control" value="{{$length}}"  />
                    <input type="hidden" name="width" id="width" class="form-control" value="{{$width}}"  />
                    <input type="hidden" name="height" id="height" class="form-control" value="{{$height}}"  />
                    <input type="hidden" name="customer-references" id="customer-references" class="form-control" value="{{$customer_references}}"  />

                    <div class="form-group">
                        <label for="pickup_date">{{ __('Pickup Date') }}</label>
                        <input type="text" name="pickup_date" id="pickup_date" class="form-control loaddatepicker" value="{{ old('pickup_date') }}" autocomplete="off" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')" onkeypress="return false;">
                        @error('pickup_date')
                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="pickup_time">{{ __('Pickup Time') }}</label>
                        <input id="pickup_time" type="text" class="form-control loadtimpicker" name="pickup_time" autocomplete="off" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')" onkeypress="return false;">
                        @error('pickup_time')
                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="location_close_time">{{ __('Pickup Location Close Time') }}</label>
                        <input id="location_close_time" type="text" class="form-control loadtimpicker" name="location_close_time" autocomplete="off" oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')" onkeypress="return false;">
                        @error('location_close_time')
                        <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                        @enderror
                    </div>
                    <div class="text-center">
                        <!-- submit button -->
                        <x-admin.publish-button />
                    </div>
                </form>
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
