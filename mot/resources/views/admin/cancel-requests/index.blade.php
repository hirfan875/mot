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
              {{ $title }}
            </div>
            <div class="card-body">
              <!-- alerts -->
              <x-alert class="alert-success" :status="session('success')" />
              <x-alert class="alert-danger" :status="session('error')" />
              <table class="table table-responsive-sm" id="datatables">
                <thead>
                  <tr>
                    <th class="text-center" width="10%">{{ __('Order #') }}</th>  
                    <th class="text-center" width="10%">{{ __('Store Order #') }}</th>
                    <th class="text-left" width="15%">{{ __('Customer') }}</th>
                    <th class="text-left" width="15%">{{ __('Company') }}</th>
                    <th class="text-center" width="15%">{{ __('Created at') }}</th>
                    <th class="text-center" width="20%">{{ __('Action') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($records as $row)
                  <tr>
                      <td class="text-center">{{ $row->store_order->order->order_number }}</td>
                    <td class="text-center">{{ $row->store_order->order_number }}</td>
                    <td class="text-left">{{ $row->store_order->order->customer->name }}</td>
                    <td class="text-left">{{ $row->company_name }}</td>
                    <td class="text-center">{{ date_format($row->created_at,"d/m/Y H:i:s") }}</td>
                    <td class="text-center">
                      <a href="{{ route('admin.orders.detail', ['storeOrder' => $row->store_order_id]) }}" class="btn btn-outline-primary btn-sm mb-1">{{ __('Detail') }}</a>
                      @foreach ($row->store_order->getPossibleStatusButtonAdmin() as $statusID => $status)
                        <a href="{{ route('admin.orders.update.status', ['storeOrder' => $row->store_order->id, 'status' => $statusID]) }}" class="btn btn-outline-primary btn-sm mb-1">{{ $status }}</a>
                      @endforeach
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
        { 'bSortable': true, 'aTargets': [ 0 ], 'name': 'order_number' },
        { 'bSortable': true, 'aTargets': [ 1 ], 'name': 'name' },
        { 'bSortable': true, 'aTargets': [ 2 ], 'name': 'tracking_id' },
        { 'bSortable': true, 'aTargets': [ 3 ], 'name': 'company_name' },
        { 'bSortable': true, 'aTargets': [ 4 ], 'name': 'created_at' },
        { 'bSortable': false, 'aTargets': [ 5 ], 'name': 'action' }
        ]
      });
      $( table.table().container() ).removeClass( 'form-inline' );
    });
  </script>
@endpush