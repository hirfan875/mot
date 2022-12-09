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
              <x-admin.add-button :url="route('admin.free.delivery.add')" />
            </div>
            <div class="card-body">
              <!-- alerts -->
              <x-alert class="alert-success" :status="session('success')" />
              <x-alert class="alert-danger" :status="session('error')" />
              <table class="table table-responsive-sm" id="datatables">
                <thead>
                  <tr>
                    <th width="22%">{{ __('Product') }}</th>
                    <th class="text-center" width="10%">{{ __('Type') }}</th>
                    <th class="text-center" width="12%">{{ __('Brand') }}</th>
                    <th class="text-center" width="10%">{{ __('Price') }}</th>
                    <th class="text-center" width="11%">{{ __('SKU') }}</th>
                    <th class="text-center" width="15%">{{ __('Last updated') }}</th>
                    <th class="text-center" width="10%">{{ __('Action') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($records as $row)
                  <tr>
                    <td>
                      <a href="{{ route('admin.products.edit', ['product' => $row->id]) }}">{{ $row->product_translates ? $row->product_translates->title : $row->title }}</a>
                    </td>
                    <td class="text-center">{{ $row->type }}</td>
                    <td class="text-center">{{ $row->brand ? $row->brand->brand_translates ? $row->brand->brand_translates->title : $row->brand->title : '' }}</td>
                    <td class="text-center">{{ config('app.currency') }} {{ currency_format($row->price) }}</td>
                    <td class="text-center">{{ $row->sku }}</td>
                    <td class="text-center">{{ date_format($row->updated_at,"d/m/Y H:i:s") }}</td>
                    <td class="text-center">
                      <x-admin.edit-button :url="route('admin.products.edit', ['product' => $row->id])" />
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
        { 'bSortable': true, 'aTargets': [ 0 ], 'name': 'title' },
        { 'bSortable': true, 'aTargets': [ 1 ], 'name': 'type' },
        { 'bSortable': true, 'aTargets': [ 2 ], 'name': 'brand_id' },
        { 'bSortable': true, 'aTargets': [ 3 ], 'name': 'price' },
        { 'bSortable': true, 'aTargets': [ 4 ], 'name': 'sku' },
        { 'bSortable': true, 'aTargets': [ 5 ], 'name': 'updated_at' },
        { 'bSortable': false, 'aTargets': [ 6 ], 'name': 'action' }
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
