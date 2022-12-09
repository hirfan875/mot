@extends('seller.layouts.app')

@section('content')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">{{ __('Dashboard') }}</a></li>
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
              <form action="{{ route('seller.pending.orders') }}" method="GET">
                <div class="form-row align-items-center mb-3">
                  <div class="col-sm-3 my-1">
                    <select class="custom-select select2 mr-sm-2" name="product" id="product">
                      <option value="" selected>--{{__('All products')}}--</option>
                      @foreach ($products as $product)
                      <option value="{{ $product->id }}" @if (isset($request_params['product']) && $request_params['product'] == $product->id) selected @endif >{{ $product->title }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-auto my-1">
                    <button type="submit" class="btn btn-success">{{__('Filter')}}</button>
                    @if ( !empty($request_params) )
                    <a href="{{ route('seller.pending.orders') }}" class="text-link" style="text-decoration: underline">{{__('Reset Filters')}}</a>
                    @endif
                  </div>
                </div>
              </form>
              <table class="table table-responsive-sm" id="datatables">
                <thead>
                  <tr>
                    <th class="text-center" width="12%">{{ __('Order #') }}</th>
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
                    <td class="text-center">{{ $row->order_number }}</td>
                    <td class="text-left">{{ $row->order->customer->name }}</td>
                    <td class="text-center">{{ " ".currency_format($row->total) }}</td>
                    <td class="text-center">{{ date_format($row->created_at,"d/m/Y H:i:s") }}</td>
                    <td class="text-center">{{ $row->getStatus($row->status) }}</td>
                    <td class="text-center">
                      <a href="{{ route('seller.pending.orders.detail', ['order' => $row->id]) }}" class="btn btn-outline-primary btn-sm mb-1">{{ __('Detail') }}</a>
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
@endsection

@push('header')
  <x-admin.datatable-css />
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet">
  <link rel="stylesheet" href="//select2.github.io/select2-bootstrap-theme/css/select2-bootstrap.css" rel="stylesheet">
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
  </script>
@endpush
