@extends('admin.layouts.app')

@section('content')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item active">{{ __($title) }}</li>
  </ol>
  <div class="container-fluid">
    <div class="animated fadeIn">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              {{ __($title) }}
            </div>
            <div class="card-body">
              <!-- alerts -->
              <x-alert class="alert-success" :status="session('success')" />
              <x-alert class="alert-danger" :status="session('error')" />
              <form action="{{ route('admin.pending.orders') }}" method="GET">
                <div class="form-row align-items-center mb-3">
                  <div class="col-sm-2 my-1">
                    <select class="custom-select select2 mr-sm-2" name="customer" id="customer">
                      <option value="" selected>--{{__('All customers')}}--</option>
                      @foreach ($customers as $customer)
                      <option value="{{ $customer->id }}" @if (isset($request_params['customer']) && $request_params['customer'] == $customer->id) selected @endif >{{ $customer->name }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-sm-2 my-1">
                    <select class="custom-select select2 mr-sm-2" name="store" id="store">
                      <option value="" selected>--{{__('All stores')}}--</option>
                      @foreach ($stores as $store)
                      <option value="{{ $store->id }}" @if (isset($request_params['store']) && $request_params['store'] == $customer->id) selected @endif >{{isset($store->store_profile_translates)? $store->store_profile_translates->name : $store->name}}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-sm-3 my-1">
                    <select class="custom-select select2 mr-sm-2" name="product" id="product">
                      <option value="" selected>--{{__('All products')}}--</option>
                      @foreach ($products as $product)
                      <option value="{{ $product->id }}" @if (isset($request_params['product']) && $request_params['product'] == $product->id) selected @endif >{{isset($product->product_translates)? $product->product_translates->title : $product->title}}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-auto my-1">
                    <button type="submit" class="btn btn-success">{{__('Filter')}}</button>
                    @if ( !empty($request_params) )
                    <a href="{{ route('admin.pending.orders') }}" class="text-link" style="text-decoration: underline">{{__('Reset Filters')}}</a>
                    @endif
                  </div>
                </div>
              </form>
              <table class="table table-responsive-sm" id="datatables">
                <thead>
                  <tr>
                    <th class="text-center" width="12%">{{ __('Order #') }}</th>
                    <!--<th class="text-center" width="12%">{{ __('Store Order #') }}</th>-->
                    <th class="text-left" width="28%">{{ __('Customer') }}</th>
                    <th class="text-center" width="15%">{{ __('Order Total') }}</th>
                    <th class="text-center" width="15%">{{ __('Order Date') }}</th>
                    <th class="text-center" width="15%">{{ __('Status') }}</th>
                    <th class="text-center" width="15%">{{ __('Action') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($records as $row)
                  <tr>
                  <td class="text-center">{{ (isset($row->order_number))? $row->order_number:"" }}</td>
                    <!--<td class="text-center">{{ $row->order_number }}</td>-->
                    <td class="text-left">{{ isset($row->customer) ? $row->customer->name :'' }} - {{ ($row->customer->is_guest == 1 ) ? 'Guest':''}} {{ ($row->order_from != null ) ? $row->order_from :''}}</td>
                    <td class="text-center">{{__($row->currency->code)}}&nbsp;{{convertTryForexRate($row->total, $row->forex_rate, $row->base_forex_rate, $row->currency->code)}} 
                        <br/>{{__('TRY')}} {{ number_format($row->total, 2, ".", ","); }}</td>
                    <td class="text-center">{{ date_format($row->created_at,"d/m/Y H:i:s")  }}</td>
                    <td class="text-center">{{ __($row->getStatus($row->status)) }}</td>
                    <td class="text-center">
                      <a href="{{ route('admin.pending.orders.detail', ['order' => $row->id]) }}" class="btn btn-outline-primary btn-sm mb-1">{{ __('Detail') }}</a>
                      <a href="#orderOverviewModal" data-toggle="modal" data-target="#orderOverviewModal" data-url="{{ route('admin.orders.overview', ['order' => $row->id]) }}" class="btn btn-outline-primary btn-sm mb-1">{{ __('Overview') }}</a>
                      
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

<!-- Order Overview Modal -->
  <div class="modal fade" id="orderOverviewModal" tabindex="-1" aria-labelledby="orderOverviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content" id="load_order_detail"></div>
    </div>
  </div>
@endsection

@push('header')
  <x-admin.datatable-css />
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet">
  <link rel="stylesheet" href="//select2.github.io/select2-bootstrap-theme/css/select2-bootstrap.css" rel="stylesheet">
  <style>
    .spinner_box { background:rgba(255,255,255,0.70);position: fixed;z-index: 9999; top: 0; left:0; right:0; height: 100%;}
    .spinner { width: 50px; height: 40px; text-align: center; font-size: 10px; position: absolute; z-index: 99999; top: 50%; left: 50%; transform: translate(-50%,-50%); }
    .spinner > div { background-color: #333; height: 100%; width: 6px; display: inline-block; -webkit-animation: sk-stretchdelay 1.2s infinite ease-in-out; animation: sk-stretchdelay 1.2s infinite ease-in-out; }
    .spinner .rect2 {-webkit-animation-delay: -1.1s; animation-delay: -1.1s;}
    .spinner .rect3 {-webkit-animation-delay: -1.0s; animation-delay: -1.0s;}
    .spinner .rect4 {-webkit-animation-delay: -0.9s; animation-delay: -0.9s;}
    .spinner .rect5 {-webkit-animation-delay: -0.8s; animation-delay: -0.8s;}
    @-webkit-keyframes sk-stretchdelay {
      0%, 40%, 100% { -webkit-transform: scaleY(0.4) }
      20% { -webkit-transform: scaleY(1.0) }
    }
    @keyframes sk-stretchdelay {
      0%, 40%, 100% {
        transform: scaleY(0.4);
        -webkit-transform: scaleY(0.4);
      }  20% {
        transform: scaleY(1.0);
        -webkit-transform: scaleY(1.0);
      }
    }
  </style>
  @endpush

@push('footer')
  <x-admin.datatable-js />
  <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
  <script type="text/javascript" charset="utf-8">
    $.fn.select2.defaults.set( "theme", "bootstrap" );
    $(document).ready(function(){
      var table = $('#datatables').DataTable({
        "iDisplayLength": 25,
        "order": [],
        "autoWidth": false,
        "aoColumnDefs": [
        { 'bSortable': true, 'aTargets': [ 0 ], 'name': 'order_number' },
        { 'bSortable': true, 'aTargets': [ 1 ], 'name': 'name' },
        { 'bSortable': true, 'aTargets': [ 2 ], 'name': 'total' },
        { 'bSortable': true, 'aTargets': [ 3 ], 'name': 'order_date' },
        { 'bSortable': true, 'aTargets': [ 4 ], 'name': 'status' },
        { 'bSortable': false, 'aTargets': [ 5 ], 'name': 'action' }
        ],
          "language": {
              "emptyTable": "{{__('No data available in table')}}",
              "zeroRecords": "{{__('No matching records found')}}",
              "search":      "{{__('Search:')}}"
          }
      });
      $( table.table().container() ).removeClass( 'form-inline' );
      $(".select2").select2({
        width: '100%'
      });
    });
    $('#orderOverviewModal').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget);
      var url = button.data('url');
      $('#spinner_box').show();

      $("#load_order_detail" ).load(url, function () {
        $('#spinner_box').hide();
      });
    });
  </script>
@endpush
