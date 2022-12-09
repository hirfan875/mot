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
              <table class="table table-responsive-sm" id="datatables">
                <thead>
                  <tr>
                      <th width="21%">{{ __('Image') }}</th>
                    <th width="21%">{{ __('Product') }}</th>
                    <th width="15%">{{ __('Customer') }}</th>
                    <th width="38%">{{ __('Comment') }}</th>
                    <th class="text-center" width="10%">{{ __('Rating') }}</th>
                    <th class="text-center" width="16%">{{ __('Posted at') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($records as $row)
                  <tr>
                      <td>
                          <img src="{{$row->order_item->product->product_listing()}}" alt="" width="120">
                      </td>
                    <td><a href="{{ route('seller.products.edit', ['product' => $row->order_item->product->id]) }}">{{ $row->order_item->product->product_translates ? $row->order_item->product->product_translates->title : $row->order_item->product->title }}</a></td>
                    <td>{{ $row->customer->name }}</td>
                    <td>{{ $row->comment }}</td>
                    <td class="text-center">{{ $row->rating }}</td>
                    <td class="text-center">{{ date_format($row->created_at,"d/m/Y H:i:s") }}</td>
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
@endpush

@push('footer')
  <x-admin.datatable-js />
  <script type="text/javascript" charset="utf-8">
    $(document).ready(function(){
      var table = $('#datatables').DataTable({
        "iDisplayLength": 25,
        "order": [],
        "autoWidth": false,
        "aoColumnDefs": [
        { 'bSortable': true, 'aTargets': [ 0 ], 'name': 'product' },
        { 'bSortable': true, 'aTargets': [ 1 ], 'name': 'customer' },
        { 'bSortable': false, 'aTargets': [ 2 ], 'name': 'comment' },
        { 'bSortable': true, 'aTargets': [ 3 ], 'name': 'rating' },
        { 'bSortable': true, 'aTargets': [ 4 ], 'name': 'created_at' }
        ],
          "language": {
              "emptyTable": "{{__('No data available in table')}}",
              "zeroRecords": "{{__('No matching records found')}}",
              "search":      "{{__('Search:')}}"
          }
      });
      $( table.table().container() ).removeClass( 'form-inline' );
    });
  </script>
@endpush
