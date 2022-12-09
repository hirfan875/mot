@extends('admin.layouts.app')

@section('content')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item active">{{ $title }}</li>
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
                    <th width="15%">{{ __('Image') }}</th>
                    <th width="20%">{{ __('Product') }}</th>
                    <th width="10%">{{ __('Seller') }}</th>
                    <th width="10%">{{ __('Customer') }}</th>
                    <th width="20%">{{ __('Comment') }}</th>
                    <th class="text-center" width="10%">{{ __('Rating') }}</th>
                    <th class="text-center" width="10%">{{ __('Posted at') }}</th>
                    <th class="text-center" width="20%">{{ __('Action') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($records as $row)
                  <tr>
                       <td>
                           <img src="{{$row->order_item->product->product_listing()}}" alt="" width="120">
                      </td>
                    <td><a href="{{ route('admin.products.edit', ['product' => $row->order_item->product->id]) }}">{{ $row->order_item->product->product_translates ? $row->order_item->product->product_translates->title : $row->order_item->product->title }}</a></td>
                    <td><a href="{{ route('admin.stores.edit', ['store' => $row->order_item->product->store->id]) }}">{{ $row->order_item->product->store ? $row->order_item->product->store->store_profile_translates ? $row->order_item->product->store->store_profile_translates->name : $row->order_item->product->store->name : '' }}</a></td>
                    <td><a href="{{ route('admin.customers.edit', ['customer' => $row->customer->id]) }}">{{ $row->customer->name }}</a></td>
                    <td>{{ $row->comment }}</td>
                    <td class="text-center">{{ $row->rating }}</td>
                    <td class="text-center">{{ date_format($row->created_at,"d/m/Y H:i:s") }}</td>
                    <td class="text-center">
                        <a href="{{ route('admin.product.reviews.show', ['item' => $row->id]) }}" class="btn btn-danger btn-sm mb-1" >{{ __('Show') }}</a>
                      @if (!$row->is_approved)
                      
                      <a href="{{ route('admin.product.reviews.approve', ['item' => $row->id]) }}" class="btn btn-success btn-sm mb-1" onclick="return window.confirm('{{ __('Are you sure you want to approve this review?') }}');">{{ __('Approve') }}</a>
                      <a href="{{ route('admin.product.reviews.reject', ['item' => $row->id]) }}" class="btn btn-danger btn-sm mb-1" onclick="return window.confirm('{{ __('Are you sure you want to reject this review?') }}');">{{ __('Reject') }}</a>
                      @endif
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
        { 'bSortable': true, 'aTargets': [ 4 ], 'name': 'created_at' },
        { 'bSortable': false, 'aTargets': [ 5 ], 'name': 'action' }
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
