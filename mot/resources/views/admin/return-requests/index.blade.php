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
              <table class="table table-responsive-sm" id="datatables">
                <thead>
                  <tr>
                    <th class="text-center" width="10%">{{ __('Order #') }}</th>
                    <th class="text-center" width="10%">{{ __('Store Order #') }}</th>
                    <th class="text-left" width="15%">{{ __('Customer') }}</th>
                    <th class="text-left" width="15%">{{ __('Tracking ID') }}</th>
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
                    <td class="text-left">{{ $row->tracking_id }}</td>
                    <td class="text-left">{{ $row->company_name }}</td>
                    <td class="text-center">{{ date_format($row->created_at,"d/m/Y H:i:s") }}</td>
                    <td class="text-center">
                      <a href="{{ route('admin.return.requests.detail', ['record' => $row->id]) }}" class="btn btn-outline-primary btn-sm mb-1">{{ __('Detail') }}</a>
                      @if ($row->status == 0)
                      <a href="{{ route('admin.return.requests.approve', ['record' => $row->id]) }}" class="btn btn-success btn-sm mb-1" onclick="return window.confirm('{{ __('Are you sure you wanted to approve this return request?' ) }}');">{{ __('Approve') }}</a>
                      <a href="{{ route('admin.return.requests.reject', ['record' => $row->id]) }}" class="btn btn-danger btn-sm mb-1" onclick="return window.confirm('{{ __('Are you sure you want to reject this return request?') }}');">{{ __('Reject') }}</a>
                      @endif

                      @if ($row->status == 1 && $row->received_expected == 0)
                      <a href="{{ route('admin.return.requests.received.expected', ['record' => $row->id]) }}" class="btn btn-success btn-sm mb-1" onclick="return window.confirm('{{ __('Are you sure you want to do this to :order', ['order' => $row->store_order->order_number.'?']) }}');">{{ __('Received as expected') }}</a>
                      <a href="{{ route('admin.return.requests.received.not.expected', ['record' => $row->id]) }}" class="btn btn-danger btn-sm mb-1" onclick="return window.confirm('{{ __('Are you sure you want to do this to :order', ['order' => $row->store_order->order_number.'?']) }}');">{{ __('Received but not as expected') }}</a>
                      @endif

                      @if ($row->status != 0 && $row->received_expected != 0)
                      <a href="{{ route('admin.return.requests.archive', ['record' => $row->id]) }}" class="btn btn-danger btn-sm mb-1" onclick="return window.confirm('{{ __('Are you sure you want to archive :order', ['order' => $row->store_order->order_number.'?']) }}');">{{ __('Archive') }}</a>
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
        { 'bSortable': true, 'aTargets': [ 0 ], 'name': 'order_number' },
        { 'bSortable': true, 'aTargets': [ 1 ], 'name': 'name' },
        { 'bSortable': true, 'aTargets': [ 2 ], 'name': 'tracking_id' },
        { 'bSortable': true, 'aTargets': [ 3 ], 'name': 'company_name' },
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
